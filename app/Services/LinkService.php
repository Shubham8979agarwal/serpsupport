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

        if ($usersWithWebsites->count() < 3) {
            return;
        }

        $users = $usersWithWebsites->pluck('id')->toArray();
        $maxIndex = count($users);

        $pairs = $this->generatePairs($type, $users, $maxIndex);
        $inserts = [];

        foreach ($pairs as $pair) {
            $fromUserId = $pair[0];
            $toUserId = $pair[1];

            // Skip rejected pairs
            if (DB::table('rejected_pairs')
                ->where('from_user_id', $fromUserId)
                ->where('to_user_id', $toUserId)
                ->exists()) {
                continue;
            }

            // Ensure valid pairings according to the selected pattern
            if (($type === 'outlinks' && abs(array_search($fromUserId, $users) - array_search($toUserId, $users)) !== 1) ||
                ($type === 'backlinks' && abs(array_search($fromUserId, $users) - array_search($toUserId, $users)) !== 2)) {
                continue;
            }

            $fromUser = User::find($fromUserId);
            $toUser = User::find($toUserId);

            // Check if users exist
            if (!$fromUser || !$toUser) {
                continue;
            }

            foreach ($fromUser->websites as $fromWebsite) {
                foreach ($toUser->websites as $toWebsite) {
                    // Create a unique key for each potential link
                    $linkData = [
                        'from_user_id' => $fromUserId,
                        'to_user_id' => $toUserId,
                        'forwhich_user_url' => $type === 'backlinks' ? $fromWebsite->website_url : $toWebsite->website_url,
                        'website_id' => $type === 'backlinks' ? $fromWebsite->website_id : $toWebsite->website_id,
                        'website_url' => $type === 'backlinks' ? $toWebsite->website_url : $fromWebsite->website_url,
                        'website_niche' => $fromWebsite->website_niche,
                        'website_description' => $fromWebsite->website_description,
                        'status' => "",
                    ];

                    // Check for duplicate records in the same table and across tables
                    if (!$this->isDuplicate($linkData, $type)) {
                        $inserts[] = $linkData;
                    }
                }
            }
        }

        if (!empty($inserts)) {
            DB::beginTransaction();
            try {
                DB::table($type)->insert($inserts);

                foreach ($inserts as $insert) {
                    $chatId = "{$insert['from_user_id']}_{$insert['to_user_id']}";

                    DB::table('submitlinks')->insert([
                        'connection_type' => $type,
                        'chat_id' => $chatId,  // Generated chat_id as a combination of from_user_id and to_user_id
                        'acceptedby_to' => $insert['to_user_id'],
                        'acceptedby_from' => $insert['from_user_id'],
                        
                        // Add 'outlink_on' and 'backlink_to' fields
                        'outlink_on' => $insert['website_url'],   // You can modify this to point to the actual value for 'outlink_on'
                        'backlink_to' => $insert['forwhich_user_url'],   // Modify this as needed based on where 'backlink_to' should point
                    ]);
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Database insert failed: " . $e->getMessage());
            }
        }
    }


    private function generatePairs($type, $users, $maxIndex)
    {
        $pairs = [];

        if ($type === 'outlinks') {
            // Generate outlinks as sequential pairs
            for ($i = 0; $i < $maxIndex - 1; $i++) {
                $pairs[] = [$users[$i], $users[$i + 1]];
            }
        } elseif ($type === 'backlinks') {
            // Generate backlinks with the pattern (i, i-2)
            for ($i = 2; $i < $maxIndex; $i++) {
                $pairs[] = [$users[$i], $users[$i - 2]];
            }
        }

        // Shuffle pairs to ensure randomness
        shuffle($pairs);

        return $pairs;
    }

    private function isDuplicate($linkData, $type)
    {
        // Ensure consistent order for cross-table uniqueness
        $firstUserId = min($linkData['from_user_id'], $linkData['to_user_id']);
        $secondUserId = max($linkData['from_user_id'], $linkData['to_user_id']);

        // Check within the same table
        if (DB::table($type)
            ->where('from_user_id', $linkData['from_user_id'])
            ->where('to_user_id', $linkData['to_user_id'])
            ->where('website_url', $linkData['website_url'])
            ->exists()) {
            return true;
        }

        // Check for reverse pair in the same table
        if (DB::table($type)
            ->where('from_user_id', $linkData['to_user_id'])
            ->where('to_user_id', $linkData['from_user_id'])
            ->where('website_url', $linkData['forwhich_user_url'])
            ->exists()) {
            return true;
        }

        // Check across tables for the same pair
        if (DB::table('backlinks')
            ->where('from_user_id', $firstUserId)
            ->where('to_user_id', $secondUserId)
            ->where('website_url', $linkData['website_url'])
            ->exists()) {
            return true;
        }

        if (DB::table('outlinks')
            ->where('from_user_id', $firstUserId)
            ->where('to_user_id', $secondUserId)
            ->where('website_url', $linkData['website_url'])
            ->exists()) {
            return true;
        }

        return false;
    }

    public function addWebsite($user, $websiteData)
    {
        $existingWebsite = Website::where('website_url', $websiteData['website_url'])
                                  ->where('website_uploader_email', $user->email)
                                  ->first();

        if ($existingWebsite) {
            return;
        }

        $newWebsite = new Website();
        $newWebsite->website_uploader_email = $user->email;
        $newWebsite->website_url = $websiteData['website_url'];
        $newWebsite->website_niche = $websiteData['website_niche'];
        $newWebsite->website_description = $websiteData['website_description'];
        $newWebsite->website_id = $websiteData['website_id'];
        $newWebsite->user_id = $user->id;
        $newWebsite->save();

        $this->createSingleLink($newWebsite, 'backlinks');
        $this->createSingleLink($newWebsite, 'outlinks');
    }

    public function createSingleLink(Website $website, $linkType)
    {
        $linkClass = $linkType === 'backlinks' ? Backlink::class : Outlink::class;

        $existingLink = $linkClass::where('website_url', $website->website_url)
                                  ->where('from_user_id', $website->user_id)
                                  ->first();

        if ($existingLink) {
            return;
        }

        $link = new $linkClass();
        $link->website_url = $website->website_url;
        $link->from_user_id = $website->user_id;

        if ($linkType === 'backlinks') {
            $toUser = $this->getBacklinkUser($website->user_id);
        } else {
            $toUser = $this->getOutlinkUser($website->user_id);
        }

        if ($toUser) {
            $link->to_user_id = $toUser->id;
            $link->forwhich_user_url = $website->website_url;
            $link->save();
        }
    }

    private function getBacklinkUser($userId)
    {
        $usersWithWebsites = User::whereHas('websites')->pluck('id')->toArray();

        $index = array_search($userId, $usersWithWebsites);

        if ($index === false || $index < 2) {
            return null;
        }

        $targetUserId = $usersWithWebsites[$index - 2];
        return User::find($targetUserId);
    }

    private function getOutlinkUser($userId)
    {
        $usersWithWebsites = User::whereHas('websites')->pluck('id')->toArray();

        $index = array_search($userId, $usersWithWebsites);

        if ($index === false || $index >= count($usersWithWebsites) - 1) {
            return null;
        }

        $targetUserId = $usersWithWebsites[$index + 1];
        return User::find($targetUserId);
    }

    public function weeklyUpdate()
    {
        $this->createBacklinks();
        $this->createOutlinks();
    }
}
