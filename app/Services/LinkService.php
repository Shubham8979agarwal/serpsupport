<?php

namespace App\Services;

use App\Models\User;
use App\Models\Website;
use App\Models\Backlink;
use App\Models\Outlink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        // Fetch users with websites
        $usersWithWebsites = User::whereHas('websites')->orderBy('id')->get();

        if ($usersWithWebsites->count() < 3) {
            return;
        }

        $users = $usersWithWebsites->pluck('id')->toArray();
        $maxIndex = count($users);

        $pairs = $this->generatePairs($type, $users, $maxIndex);

        $inserts = [];
        foreach ($pairs as $pair) {
            $fromUserId = (string) $pair[0]; // Ensure UUID handling
            $toUserId = (string) $pair[1]; // Ensure UUID handling

            $fromUser = User::find($fromUserId);
            $toUser = User::find($toUserId);

            if ($fromUser && $toUser) {
                foreach ($fromUser->websites as $fromWebsite) {
                    foreach ($toUser->websites as $toWebsite) {
                        $forwhich_user_url = $type === 'backlinks' ? $fromWebsite->website_url : $toWebsite->website_url;
                        $website_url = $type === 'backlinks' ? $toWebsite->website_url : $fromWebsite->website_url;

                        // Check if the pair already exists
                        if (!$this->isPairExisting($fromUserId, $toUserId, $forwhich_user_url, $website_url, $type)) {
                            $linkData = [
                                'from_user_id' => $fromUserId,
                                'to_user_id' => $toUserId,
                                'forwhich_user_url' => $forwhich_user_url,
                                'website_id' => $type === 'backlinks' ? $fromWebsite->website_id : $toWebsite->website_id,
                                'website_url' => $website_url,
                                'website_niche' => $fromWebsite->website_niche,
                                'website_description' => $fromWebsite->website_description,
                                'status' => "",
                            ];
                            $inserts[] = $linkData;
                        }
                    }
                }
            } else {
                // Log the error or handle it
                Log::error("User not found: fromUserId={$fromUserId}, toUserId={$toUserId}");
            }
        }

        if (!empty($inserts)) {
            DB::beginTransaction();
            try {
                DB::table($type)->insert($inserts);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                // Handle exception or log it
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

    private function isPairExisting($fromUserId, $toUserId, $forwhich_user_url, $website_url, $type)
    {
        $table = ($type === 'backlinks') ? 'backlinks' : 'outlinks';

        return DB::table($table)
            ->where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('forwhich_user_url', $forwhich_user_url)
            ->where('website_url', $website_url)
            ->exists();
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
