<?php

namespace App\Services;
use App\Models\User;
use App\Models\UserVerify;
use App\Models\Website;
use App\Models\Backlink;
use App\Models\Outlink;
use App\Models\RejectedPair;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LinkService
{

	public function createbacklinks() {
	    $data['data'] = Auth::user();
	    $user = Auth::user();
	    $lastCreatedAt = $user->last_backlinks_created_at;
	    $currentDate = now();

	    // Check if it has been a week since the last creation
	    if ($lastCreatedAt && $currentDate->diffInDays($lastCreatedAt) < 7) {
	        return;
	        //return response()->json(['message' => 'You can only create backlinks once per week.'], 400);
	    }

	    $getverified_users = User::where('is_email_verified', '1')->get();

	    // Ensure there are at least 4 verified users
	    if ($getverified_users->count() < 4) {
	        return;
	        //return response()->json(['message' => 'Not enough users with websites to create backlinks. At least 4 users with websites are required.'], 400);
	    }

	    // Prepare users and their websites
	    $checkarray = [];
	    foreach ($getverified_users as $check) {
	        $websites = Website::where('website_uploader_email', $check->email)->get()->toArray();
	        if (!empty($websites)) {
	            $checkarray[] = [
	                'user_id' => $check->id,
	                'websites' => $websites,
	            ];
	        }
	    }

	    // Ensure there are enough users with websites to form pairs
	    if (count($checkarray) < 4) {
	        return;
	        //return response()->json(['message' => 'Not enough users with websites to create backlinks. At least 4 users with websites are required.'], 400);
	    }

	    // Create backlinks where user i links to user i - 2
	    $totalUsers = count($checkarray);
	    $inserts = [];
	    
	    // Avoid reciprocal pairs and incorrect links
	    for ($i = 2; $i < $totalUsers; $i++) {
	        $currentUserId = $checkarray[$i]['user_id'];
	        $targetUserId = $checkarray[$i - 2]['user_id'];

	        // Check if the pair is rejected
	        if (RejectedPair::where('from_user_id', $currentUserId)->where('to_user_id', $targetUserId)->exists()) {
	            continue;
	        }

	        // Avoid reciprocal pairs
	        if (RejectedPair::where('from_user_id', $targetUserId)->where('to_user_id', $currentUserId)->exists()) {
	            continue;
	        }

	        // Check if backlink already exists
	        foreach ($checkarray[$i]['websites'] as $currentWebsite) {
	            foreach ($checkarray[$i - 2]['websites'] as $targetWebsite) {
	                $checkIfAlreadyExists = Backlink::where('website_url', $targetWebsite['website_url'])
	                                                ->where('from_user_id', $currentUserId)
	                                                ->where('to_user_id', $targetUserId)
	                                                ->exists();
	                if (!$checkIfAlreadyExists) {
	                    $inserts[] = [
	                        'from_user_id' => $currentUserId,
	                        'to_user_id' => $targetUserId,
	                        'forwhich_user_url' => $currentWebsite['website_url'],
	                        'website_id' => $currentWebsite['website_id'],
	                        'website_url' => $targetWebsite['website_url'],
	                        'website_niche' => $currentWebsite['website_niche'],
	                        'website_description' => $currentWebsite['website_description'],
	                        'status' => "",
	                    ];
	                }
	            }
	        }
	    }

	    // Insert data into the database if there are new backlinks
	    if (!empty($inserts)) {
	        DB::beginTransaction();
	        try {
	            DB::table('backlinks')->insert($inserts);
	            DB::commit();
	        } catch (\Exception $e) {
	            DB::rollback();
	            // Handle the exception
	        }
	    }

	    // Update the last backlinks creation time
	    $user->last_backlinks_created_at = now();
	    $user->save();
	    return;
    //return response()->json(['message' => 'Backlinks created successfully.'], 200);
}


public function createoutlinks() {
    $user = Auth::user();
    $lastCreatedAt = $user->last_outlinks_created_at;
    $currentDate = now();

    // Check if it has been a week since the last creation
    if ($lastCreatedAt && $currentDate->diffInDays($lastCreatedAt) < 7) {
        return;
        //return response()->json(['message' => 'You can only create outlinks once per week.'], 400);
    }

    $getverified_users = User::where('is_email_verified', '1')->get();

    // Check if there are enough users
    $userCount = $getverified_users->count();
    if ($userCount < 2) {
        return;
        //return response()->json(['message' => 'Not enough users to create outlinks. At least 2 users are required.'], 400);
    }

    // Prepare users and their websites
    $checkarray = [];
    foreach ($getverified_users as $check) {
        $websites = Website::where('website_uploader_email', $check->email)->get()->toArray();
        if (!empty($websites)) {
            $checkarray[] = [
                'user_id' => $check->id,
                'websites' => $websites,
            ];
        }
    }

    // Ensure there are at least 2 users with websites
    if (count($checkarray) < 2) {
        return;
        //return response()->json(['message' => 'Not enough users with websites to create outlinks. At least 2 users with websites are required.'], 400);
    }

    // Create outlinks with unique pairs
    $pairs = [];
    $totalUsers = count($checkarray);

    // Generate unique pairs in a cyclic manner
    for ($i = 0; $i < $totalUsers - 1; $i++) {
        $currentUserId = $checkarray[$i]['user_id'];
        $nextUserId = $checkarray[$i + 1]['user_id']; // Get the next user in the array

        // Check if the pair is rejected
        if (RejectedPair::where('from_user_id', $currentUserId)->where('to_user_id', $nextUserId)->exists()) {
            continue;
        }

        // Ensure non-reciprocal pairs
        if ($currentUserId < $nextUserId) {
            $pairs[] = $currentUserId . "," . $nextUserId;

            $currentWebsites = $checkarray[$i]['websites'];
            $nextWebsites = $checkarray[$i + 1]['websites'];

            $inserts = [];
            foreach ($currentWebsites as $currentWebsite) {
                foreach ($nextWebsites as $nextWebsite) {
                    // Check if outlink already exists
                    $checkIfAlreadyExists = Outlink::where('from_user_id', $currentUserId)
                                                    ->where('to_user_id', $nextUserId)
                                                    ->where('website_url', $currentWebsite['website_url'])
                                                    ->exists();
                    if (!$checkIfAlreadyExists) {
                        $inserts[] = [
                            'from_user_id' => $currentUserId,
                            'to_user_id' => $nextUserId,
                            'forwhich_user_url' => $nextWebsite['website_url'],
                            'website_id' => $currentWebsite['website_id'],
                            'website_url' => $currentWebsite['website_url'],
                            'website_niche' => $currentWebsite['website_niche'],
                            'website_description' => $currentWebsite['website_description'],
                            'status' => "",
                        ];
                    }
                }
            }

            // Insert data into the database if there are new outlinks
            if (!empty($inserts)) {
                DB::beginTransaction();
                try {
                    DB::table('outlinks')->insert($inserts);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    // Handle the exception
                }
            }
        }
    }

    // Update the last outlinks creation time
    $user->last_outlinks_created_at = now();
    $user->save();
    return;
    //return response()->json(['message' => 'Outlinks created successfully.'], 200);
}

}
