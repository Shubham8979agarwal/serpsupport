<?php

namespace App\Services;

use App\Models\User;
use App\Models\Website;
use App\Models\Backlink;
use App\Models\Outlink;
use App\Models\RejectedPair;
use Illuminate\Support\Facades\DB;

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
        $usersWithWebsites = User::whereHas('websites')->get();

        if ($usersWithWebsites->count() < 3) {
            return;
        }

        $users = $usersWithWebsites->pluck('id')->toArray();
        $maxIndex = count($users);

        $pairs = [];
        if ($type === 'outlinks') {
            for ($i = 0; $i < $maxIndex - 1; $i++) {
                $pairs[] = [$users[$i], $users[$i + 1]];
            }
        } elseif ($type === 'backlinks') {
            for ($i = 2; $i < $maxIndex; $i++) {
                $pairs[] = [$users[$i], $users[$i - 2]];
            }
        }

        $inserts = [];
        foreach ($pairs as $pair) {
            $fromUserId = $pair[0];
            $toUserId = $pair[1];

            $fromUser = User::find($fromUserId);
            $toUser = User::find($toUserId);

            $fromWebsite = $fromUser->websites->first();
            $toWebsite = $toUser->websites->first();

            if ($fromWebsite && $toWebsite) {
                $linkData = [
                    'from_user_id' => $fromUserId,
                    'to_user_id' => $toUserId,
                    'forwhich_user_url' => $type == 'backlinks' ? $fromWebsite->website_url : $toWebsite->website_url,
                    'website_id' => $type == 'backlinks' ? $fromWebsite->website_id : $toWebsite->website_id,
                    'website_url' => $type == 'backlinks' ? $toWebsite->website_url : $fromWebsite->website_url,
                    'website_niche' => $fromWebsite->website_niche,
                    'website_description' => $fromWebsite->website_description,
                    'status' => "",
                ];

                if (!$this->isPairExisting($fromUserId, $toUserId, $type)) {
                    $inserts[] = $linkData;
                }
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
            }
        }
    }

    private function isPairExisting($fromUserId, $toUserId, $type)
    {
        return DB::table($type)
            ->where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
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
        $toUser = User::where('email', $website->website_uploader_email)->first();
        if ($toUser) {
            $link->to_user_id = $toUser->id;
            $link->save();
        }
    }

    public function weeklyUpdate()
    {
        $this->createBacklinks();
        $this->createOutlinks();
    }
}
