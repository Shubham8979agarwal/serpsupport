<?php

namespace App\Services;

use App\Models\User;
use App\Models\Website;
use App\Models\Backlink;
use App\Models\Outlink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinkService
{
    public function createBacklinks()
    {
        $this->createLinks('backlinks');
    }

    public function createOutlinks()
    {
        $this->createLinks('outlinks');
    }

    private function createLinks($type)
    {
        $usersWithWebsites = User::whereHas('websites')->orderBy('id')->get();

        // If fewer than 2 users, no backlinks or outlinks can be created
        if (count($usersWithWebsites) < 2) {
            Log::info("Not enough users to create links.");
            return;
        }

        // Apply Round Robin logic for pairing users
        $inserts = [];
        $totalUsers = count($usersWithWebsites);

        for ($i = 0; $i < $totalUsers; $i++) {
            $fromUser = $usersWithWebsites[$i];
            $toUser = $usersWithWebsites[($i + 1) % $totalUsers]; // Circular round-robin pairing

            // Ensure valid websites exist for both users
            foreach ($fromUser->websites as $fromWebsite) {
                foreach ($toUser->websites as $toWebsite) {
                    $linkData = $this->prepareLinkData(
                        $fromUser->id,
                        $toUser->id,
                        $fromWebsite,
                        $toWebsite,
                        $type
                    );

                    // Check for duplicates before adding
                    if (!$this->isDuplicate($linkData, $type)) {
                        $inserts[] = $linkData;
                    }
                }
            }
        }

        if (!empty($inserts)) {
            DB::beginTransaction();
            try {
                // Insert the links (backlinks or outlinks)
                DB::table($type)->insert($inserts);

                // Also insert records into the submitlinks table
                $this->createSubmitLinks($inserts, $type);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Database insert failed: " . $e->getMessage());
            }
        }
    }

    private function prepareLinkData($fromUserId, $toUserId, $fromWebsite, $toWebsite, $type)
    {
        return [
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'forwhich_user_url' => $type === 'backlinks' ? $fromWebsite->website_url : $toWebsite->website_url,
            'website_id' => $type === 'backlinks' ? $fromWebsite->website_id : $toWebsite->website_id,
            'website_url' => $type === 'backlinks' ? $toWebsite->website_url : $fromWebsite->website_url,
            'website_niche' => $fromWebsite->website_niche,
            'website_description' => $fromWebsite->website_description,
            'status' => "",
        ];
    }

    private function isDuplicate($linkData, $type)
    {
        // Check for duplicates in the same table (both ways)
        $existsInSameTable = DB::table($type)
            ->where('from_user_id', $linkData['from_user_id'])
            ->where('to_user_id', $linkData['to_user_id'])
            ->exists();

        $existsInReverseTable = DB::table($type)
            ->where('from_user_id', $linkData['to_user_id'])
            ->where('to_user_id', $linkData['from_user_id'])
            ->exists();

        if ($existsInSameTable || $existsInReverseTable) {
            return true;
        }

        // Check across tables for the same pair
        if ($type === 'backlinks') {
            // Check in outlinks
            return DB::table('outlinks')
                ->where('from_user_id', $linkData['from_user_id'])
                ->where('to_user_id', $linkData['to_user_id'])
                ->exists();
        } else {
            // Check in backlinks
            return DB::table('backlinks')
                ->where('from_user_id', $linkData['from_user_id'])
                ->where('to_user_id', $linkData['to_user_id'])
                ->exists();
        }
    }

    private function createSubmitLinks($inserts, $type)
    {
        foreach ($inserts as $insert) {
            $chatId = "{$insert['from_user_id']}_{$insert['to_user_id']}";

            DB::table('submitlinks')->insert([
                'connection_type' => $type,
                'chat_id' => $chatId,
                'acceptedby_to' => $insert['to_user_id'],
                'acceptedby_from' => $insert['from_user_id'],
                'outlink_on' => $insert['website_url'],
                'backlink_to' => $insert['forwhich_user_url'],
            ]);
        }
    }

    public function addWebsite($user, $websiteData)
    {
        $existingWebsite = Website::where('website_url', $websiteData['website_url'])
            ->where('website_uploader_email', $user->email)
            ->first();

        // If the website already exists for this user, don't add again
        if ($existingWebsite) {
            return;
        }

        // Create a new website
        $newWebsite = new Website();
        $newWebsite->website_uploader_email = $user->email;
        $newWebsite->website_url = $websiteData['website_url'];
        $newWebsite->website_niche = $websiteData['website_niche'];
        $newWebsite->website_description = $websiteData['website_description'];
        $newWebsite->website_id = $websiteData['website_id'];
        $newWebsite->user_id = $user->id;
        $newWebsite->save();

        // Create backlinks and outlinks for the new website
        $this->createSingleLink($newWebsite, 'backlinks');
        $this->createSingleLink($newWebsite, 'outlinks');
    }

    public function createSingleLink(Website $website, $linkType)
    {
        $linkClass = $linkType === 'backlinks' ? Backlink::class : Outlink::class;

        // Prepare link data
        $linkData = [
            'from_user_id' => $website->user_id,
            'website_url' => $website->website_url,
            'forwhich_user_url' => $website->website_url, // Same for single link
            'website_id' => $website->website_id,
        ];

        // Check for duplicates
        if ($this->isDuplicate($linkData, $linkType)) {
            return; // Skip creation if it's a duplicate
        }

        // Proceed to create the link
        $link = new $linkClass();
        $link->website_url = $website->website_url;
        $link->from_user_id = $website->user_id;

        // Select the next user for pairing (using Round Robin logic)
        if ($linkType === 'backlinks') {
            $toUser = $this->getBacklinkUser($website->user_id);
        } else {
            $toUser = $this->getOutlinkUser($website->user_id);
        }

        // If a valid pairing user exists, assign the link to that user
        if ($toUser) {
            $link->to_user_id = $toUser->id;
            $link->forwhich_user_url = $website->website_url;
            $link->save();
        }
    }

    private function getBacklinkUser($userId)
    {
        // Get all users who have websites
        $usersWithWebsites = User::whereHas('websites')->pluck('id')->toArray();
        $index = array_search($userId, $usersWithWebsites);

        if ($index === false || $index >= count($usersWithWebsites) - 1) {
            return null;
        }

        // Get the next user in the list for backlink pairing
        $targetUserId = $usersWithWebsites[$index + 1];
        return User::find($targetUserId);
    }

    private function getOutlinkUser($userId)
    {
        // Get all users who have websites
        $usersWithWebsites = User::whereHas('websites')->pluck('id')->toArray();
        $index = array_search($userId, $usersWithWebsites);

        if ($index === false || $index >= count($usersWithWebsites) - 1) {
            return null;
        }

        // Get the next user in the list for outlink pairing
        $targetUserId = $usersWithWebsites[$index + 1];
        return User::find($targetUserId);
    }

    public function weeklyUpdate()
    {
        $this->createBacklinks();
        $this->createOutlinks();
    }
}
