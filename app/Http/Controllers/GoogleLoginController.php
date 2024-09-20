<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\LinkService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Session;
use Hash;
use App\Models\UserVerify;
use App\Models\Website;
use App\Models\Backlink;
use App\Models\Outlink;
use App\Models\RejectedPair;
use App\Models\ChMessage;
use App\Models\ChFavorite;
use App\Models\Submitlink;
use App\Models\Notification;
use Mail;
use Illuminate\Support\Str;
use DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Chatify\Facades\ChatifyMessenger as Chatify;


class GoogleLoginController extends Controller
{
    protected $linkService;

    public function __construct(LinkService $linkService)
   {
        $this->middleware('disable_back_btn');
        $this->linkService = $linkService;
   }

    public function signup()
    {
        if(Auth::check()){
            //return redirect('account-settings');
            return redirect('addwebsite');
        }else{
            return view('frontend.signup');
        }
    }

    public function login()
    {
        if(Auth::check() && auth()->user()->is_email_verified==1){
            //return redirect('account-settings');
            return redirect('addwebsite');
        }
        else{
            return view('frontend.login');
        }
    }

    public function accountsettings()
    {
        $data['data'] = Auth::user();
        $data['pushedwebsites'] = DB::table('websites')->where('website_uploader_email',Auth::user()->email)->get();
        return view('frontend.dashboard.accountsettings',$data);
    }

    public function dashboard()
    {
        $data['data'] = Auth::user();
        $data['total_websites'] = DB::table('websites')->count();
        $data['confirmed_backlinks'] = DB::table('submitlinks')->where(function($query) {
        $query->where('acceptedby_to', Auth::user()->id)
              ->orWhere('acceptedby_from', Auth::user()->id);
        })->where('chat_status', 'closed')->where('connection_type','backlinks')->count();
        $data['confirmed_outlinks'] = DB::table('submitlinks')->where(function($query) {
        $query->where('acceptedby_to', Auth::user()->id)
              ->orWhere('acceptedby_from', Auth::user()->id);
        })->where('chat_status', 'closed')->where('connection_type','outlinks')->count();
        $data['fetch_user_website'] =  DB::table('websites')->where('website_uploader_email',Auth::user()->email)->value('website_url');

        $data['find_backlink_connection'] = DB::table('backlinks')->where('forwhich_user_url',$data['fetch_user_website'])->orwhere('website_url',$data['fetch_user_website'])->count();

        $data['find_outlink_connection'] = DB::table('outlinks')->where('forwhich_user_url',$data['fetch_user_website'])->orwhere('website_url',$data['fetch_user_website'])->count();
        $data['connections'] = $data['find_backlink_connection'] + $data['find_outlink_connection'];
        return view('frontend.dashboard.dashboard',$data);
    }

    public function archivedchat_and_linkdetails()
    {
        $data['data'] = Auth::user();
        $data['linkdetails'] = DB::table('submitlinks')
        ->where(function($query) {
        $query->where('acceptedby_to', Auth::user()->id)
              ->orWhere('acceptedby_from', Auth::user()->id);
        })
        ->where('chat_status', 'closed')
        ->get();
        return view('frontend.dashboard.archivedchat_and_linkdetails',$data);
    }

    public function addwebsite()
    {
        $data['data'] = Auth::user();
        return view('frontend.dashboard.addwebsite',$data);
    }

    public function deletewebsite($id)
    {
        $id = decrypt($id);
        // Get the website URL based on the website ID
        $getwebsitename = DB::table('websites')
            ->where('website_id', $id)
            ->select('website_url')
            ->pluck('website_url')
            ->first();

        if ($getwebsitename) {
            // Delete from websites table
            DB::table('websites')->where('website_id', $id)->delete();
            
            // Delete from backlinks table where website_url or forwhich_user_url matches $getwebsitename
            DB::table('backlinks')
                ->where('website_url', $getwebsitename)
                ->orWhere('forwhich_user_url', $getwebsitename)
                ->delete();
            
            // Delete from outlinks table where website_url or forwhich_user_url matches $getwebsitename
            DB::table('outlinks')
                ->where('website_url', $getwebsitename)
                ->orWhere('forwhich_user_url', $getwebsitename)
                ->delete();
            
            return redirect('account-settings')->with('message', 'Website and associated records deleted successfully');
        } else {
            return redirect('account-settings')->with('error', 'Website not found');
        }
    }

    public function acceptedby_to_outlink_connection($id, $forwhich_user_url, $website_url)
    {
        $id = decrypt($id);
        $forwhich_user_url = decrypt($forwhich_user_url);
        $website_url = decrypt($website_url);

        // Fetch the outlink data
        $outlink = DB::table('outlinks')
            ->where('id', $id)
            ->where('forwhich_user_url', $forwhich_user_url)
            ->where('website_url', $website_url)
            ->first();

        if ($outlink) {
            // Update the acceptedby_to field in the outlink
            DB::table('outlinks')
                ->where('id', $id)
                ->where('forwhich_user_url', $forwhich_user_url)
                ->where('website_url', $website_url)
                ->update(['acceptedby_to' => 'yes']);        

        // Re-fetch the updated outlink record to check the new status
        $outlink = DB::table('outlinks')
            ->where('id', $id)
            ->where('forwhich_user_url', $forwhich_user_url)
            ->where('website_url', $website_url)
            ->first();

        // Determine the new status
        $newStatus = (
            $outlink->acceptedby_from == 'yes' && $outlink->acceptedby_to == 'yes'
        ) ? 'accepted' : 'pending';

        // Update the outlink record with the new status
        DB::table('outlinks')
            ->where('id', $id)
            ->where('forwhich_user_url', $forwhich_user_url)
            ->where('website_url', $website_url)
            ->update(['status' => $newStatus]);

            // Add notification to the notifications table
            $notification = [
                'forwhich_user_url' => $forwhich_user_url,
                'website_url' => $website_url,
                'acceptedby_from' => 'yes',
                'acceptedby_to' => 'yes',
                'connnection_text' => "Connection accepted by {$forwhich_user_url}",
                'seen' => false,
            ];
            DB::table('notifications')->insert($notification);    

        // If the status is 'accepted', create a record in the ch_messages table
        if ($newStatus == 'accepted') {
            $from_id = $outlink->from_user_id;
            $to_id = $outlink->to_user_id;

            // Generate chat_id by combining $from_id and $to_id
            $chat_id = $from_id . '_' . $to_id;
            
            // Update the chat_id in the websites table
            DB::table('outlinks')->where('from_user_id', $from_id)->where('to_user_id', $to_id)->update(['chat_id' => $chat_id]);

            $html = 'In this chat, we will discuss the possibility of a backlink/outlink. Make sure to discuss: The type of link,the URL and the anchor text. When done, the one giving the link has to submit the link details using green button on the right.';

            $sendtochat = [
                'from_id' => $from_id,
                'to_id' => $to_id,
                'forwhich_user_url' => $forwhich_user_url,
                'website_url' => $website_url,
                'myuniqueid' => $from_id."_".$to_id."_@@!!",
                'body' => $html,
                'seen' => '0'
            ];

            ChMessage::create($sendtochat);
        }

        return back()->with('message_acceptedby_to_outlink_connection', 'Thank you for approving the connection');
        } else {
            // Handle backlinks similarly as done for outlinks
            // Fetch the backlink data
            $backlink = DB::table('backlinks')
                ->where('id', $id)
                ->where('forwhich_user_url', $forwhich_user_url)
                ->where('website_url', $website_url)
                ->first();

            if ($backlink) {
                // Update the acceptedby_to field in the backlink
                DB::table('backlinks')
                    ->where('id', $id)
                    ->where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->update(['acceptedby_to' => 'yes']);

                // Re-fetch the updated backlink record to check the new status
                $backlink = DB::table('backlinks')
                    ->where('id', $id)
                    ->where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->first();

                // Determine the new status
                $newStatus = (
                    $backlink->acceptedby_from == 'yes' && $backlink->acceptedby_to == 'yes'
                ) ? 'accepted' : 'pending';

                // Update the backlink record with the new status
                DB::table('backlinks')
                    ->where('id', $id)
                    ->where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->update(['status' => $newStatus]);

                // Add notification to the notifications table
                $notification = [
                    'forwhich_user_url' => $forwhich_user_url,
                    'website_url' => $website_url,
                    'acceptedby_from' => 'yes',
                    'acceptedby_to' => 'yes',
                    'connnection_text' => "Connection accepted by {$website_url}",
                    'seen' => false,
                ];
                DB::table('notifications')->insert($notification);    

                // If the status is 'accepted', create a record in the ch_messages table
                if ($newStatus == 'accepted') {
                    $from_id = $backlink->from_user_id;
                    $to_id = $backlink->to_user_id;

                    // Generate chat_id by combining $from_id and $to_id
                    $chat_id = $from_id . '_' . $to_id;
                
                    // Update the chat_id in the websites table
                    DB::table('backlinks')->where('from_user_id', $from_id)->where('to_user_id', $to_id)->update(['chat_id' => $chat_id]);

                    $html = 'In this chat, we will discuss the possibility of a backlink/outlink. Make sure to discuss: The type of link,the URL and the anchor text. When done, the one giving the link has to submit the link details using green button on the right.';

                    $sendtochat = [
                        'from_id' => $from_id,
                        'to_id' => $to_id,
                        'forwhich_user_url' => $forwhich_user_url,
                        'website_url' => $website_url,
                        'myuniqueid' => $from_id."_".$to_id."_@@!!",
                        'body' => $html,
                        'seen' => '0'
                    ];

                    ChMessage::create($sendtochat);
                }

                return back()->with('message_acceptedby_to_outlink_connection', 'Thank you for approving the connection');
            } else {
                return back()->with('error', 'Record not found in both outlinks and backlinks');
            }
        }
    }

    public function acceptedby_from_backlink_connection($id, $forwhich_user_url, $website_url)
    {
        $id = decrypt($id);
        $forwhich_user_url = decrypt($forwhich_user_url);
        $website_url = decrypt($website_url);

        // Fetch the outlink data
        $outlink = DB::table('outlinks')
            ->where('id', $id)
            ->where('forwhich_user_url', $forwhich_user_url)
            ->where('website_url', $website_url)
            ->first();

        if ($outlink) {
            // Update the acceptedby_from field in the outlink
            DB::table('outlinks')
                ->where('id', $id)
                ->where('forwhich_user_url', $forwhich_user_url)
                ->where('website_url', $website_url)
                ->update(['acceptedby_from' => 'yes']);

            // Re-fetch the updated outlink record to check the new status
            $outlink = DB::table('outlinks')
                ->where('id', $id)
                ->where('forwhich_user_url', $forwhich_user_url)
                ->where('website_url', $website_url)
                ->first();

            // Determine the new status
            $newStatus = (
                $outlink->acceptedby_from == 'yes' && $outlink->acceptedby_to == 'yes'
            ) ? 'accepted' : 'pending';

            // Update the outlink record with the new status
            DB::table('outlinks')
                ->where('id', $id)
                ->where('forwhich_user_url', $forwhich_user_url)
                ->where('website_url', $website_url)
                ->update(['status' => $newStatus]);
            // Add notification to the notifications table
            $notification = [
                'forwhich_user_url' => $forwhich_user_url,
                'website_url' => $website_url,
                'acceptedby_from' => 'yes',
                'acceptedby_to' => 'yes',
                'connnection_text' => "Connection accepted by {$website_url}",
                'seen' => false,
            ];
            DB::table('notifications')->insert($notification);    

            // If the status is 'accepted', create a record in the ch_messages table
            if ($newStatus == 'accepted') {
                $from_id = $outlink->from_user_id;
                $to_id = $outlink->to_user_id;

                // Generate chat_id by combining $from_id and $to_id
                $chat_id = $from_id . '_' . $to_id;

                // Update the chat_id in the websites table
                DB::table('outlinks')->where('from_user_id', $from_id)->where('to_user_id', $to_id)->update(['chat_id' => $chat_id]);

                $html = 'In this chat, we will discuss the possibility of a backlink/outlink. Make sure to discuss: The type of link,the URL and the anchor text. When done, the one giving the link has to submit the link details using green button on the right.';

                $sendtochat = [
                    'from_id' => $from_id,
                    'to_id' => $to_id,
                    'forwhich_user_url' => $forwhich_user_url,
                    'website_url' => $website_url,
                    'myuniqueid' => $from_id."_".$to_id."_@@!!",
                    'body' => $html,
                    'seen' => '0'
                ];

                ChMessage::create($sendtochat);
            }

            return back()->with('message_acceptedby_from_backlink_connection', 'Thank you for approving the connection');
        } else {
            // Handle backlinks similarly as done for outlinks
            // Fetch the backlink data
            $backlink = DB::table('backlinks')
                ->where('id', $id)
                ->where('forwhich_user_url', $forwhich_user_url)
                ->where('website_url', $website_url)
                ->first();

            if ($backlink) {
                // Update the acceptedby_from field in the backlink
                DB::table('backlinks')
                    ->where('id', $id)
                    ->where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->update(['acceptedby_from' => 'yes']);

                // Re-fetch the updated backlink record to check the new status
                $backlink = DB::table('backlinks')
                    ->where('id', $id)
                    ->where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->first();

                // Determine the new status
                $newStatus = (
                    $backlink->acceptedby_from == 'yes' && $backlink->acceptedby_to == 'yes'
                ) ? 'accepted' : 'pending';

                // Update the backlink record with the new status
                DB::table('backlinks')
                    ->where('id', $id)
                    ->where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->update(['status' => $newStatus]);

                // Add notification to the notifications table
            $notification = [
                'forwhich_user_url' => $forwhich_user_url,
                'website_url' => $website_url,
                'acceptedby_from' => 'yes',
                'acceptedby_to' => 'yes',
                'connnection_text' => "Connection accepted by {$forwhich_user_url}",
                'seen' => false,
            ];

            DB::table('notifications')->insert($notification);    

                // If the status is 'accepted', create a record in the ch_messages table
                if ($newStatus == 'accepted') {
                    $from_id = $backlink->from_user_id;
                    $to_id = $backlink->to_user_id;

                    // Generate chat_id by combining $from_id and $to_id
                    $chat_id = $from_id . '_' . $to_id;
                
                    // Update the chat_id in the websites table
                    DB::table('backlinks')->where('from_user_id', $from_id)->where('to_user_id', $to_id)->update(['chat_id' => $chat_id]);

                    $html = 'In this chat, we will discuss the possibility of a backlink/outlink. Make sure to discuss: The type of link,the URL and the anchor text. When done, the one giving the link has to submit the link details using green button on the right.';

                    $sendtochat = [
                        'from_id' => $from_id,
                        'to_id' => $to_id,
                        'forwhich_user_url' => $forwhich_user_url,
                        'website_url' => $website_url,
                        'myuniqueid' => $from_id."_".$to_id."_@@!!",
                        'body' => $html,
                        'seen' => '0'
                    ];

                    ChMessage::create($sendtochat);
                }

                return back()->with('message_acceptedby_from_backlink_connection', 'Thank you for approving the connection');
            } else {
                return back()->with('error', 'Record not found in both outlinks and backlinks');
            }
        }
    }

    public function push_website(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'website_niche' => 'required',
            'website_url' => 'required|unique:websites,website_url',
            'website_description' => 'required',
        ]);

        // Retrieve the validated data
        $data = $request->only(['website_niche', 'website_url', 'website_description']);

        $websiteCount = Website::where('user_id', Auth::user()->id)->count();
        if ($websiteCount >= 1) {
            return back()->with('error_message', 'You cannot add more than 1 website');
        }

        // Validate the length of website description
        if (str_word_count($data['website_description']) > 250) {
            return back()->with('error_message', 'Failed! The website description exceeds 250 words.');
        }

        // Add additional data
        $data['website_id'] = Str::random(10);
        $data['user_id'] = Auth::user()->id;
        $data['website_uploader_email'] = Auth::user()->email;

        // Check for existing website with the same URL and email
        $exists = Website::where('website_url', $data['website_url'])
                          ->where('website_uploader_email', $data['website_uploader_email'])
                          ->exists();

        if ($exists) {
            return back()->with('error_message', 'Website already exists.');
        }

        // Save the website to the database
        Website::create($data);

        // Call the addWebsite method from LinkService to create backlinks and outlinks
        $this->linkService->addWebsite(Auth::user(), $data);

        return redirect('account-settings')->with('message', 'Website Added Successfully ...');
    }

    public function backlinks($forwhich_user_url)
    {
        $forwhich_user_url = decrypt($forwhich_user_url);
        $website_url = $forwhich_user_url;
        $data['data'] = Auth::user();

        // Fetch user outlink URLs
        $userOutlinkUrls = DB::table('outlinks')
            ->where('website_url', $website_url)
            ->get()
            ->toArray(); 

        // Fetch backlinks
        $backlink_data = DB::table('backlinks')
            ->where('forwhich_user_url', $forwhich_user_url)
            ->get()
            ->toArray();

        // Get all outlink records to compare
        $outlinks = DB::table('outlinks')->get()->toArray();

        // Filter out backlinks that have matching reversed from_user_id and to_user_id in outlinks
        $filtered_backlink_data = array_filter($backlink_data, function($backlink) use ($outlinks) {
            foreach ($outlinks as $outlink) {
                if ($backlink->from_user_id == $outlink->to_user_id && $backlink->to_user_id == $backlink->from_user_id) {
                    return false;
                }
            }
            return true;
        });

        // Merge and filter unique data based on both `forwhich_user_url` and `website_url`
        $all_backlinks = array_merge($userOutlinkUrls, $filtered_backlink_data);

        $unique_backlink_data = [];
        $seen = [];

        foreach ($all_backlinks as $backlink) {
            $key = $backlink->forwhich_user_url . '-' . $backlink->website_url;
            if (!in_array($key, $seen)) {
                $seen[] = $key;
                $unique_backlink_data[] = $backlink;
            }
        }

        // Filter out any records with status 'rejected'
        $unique_backlink_data = array_filter($unique_backlink_data, function($backlink) {
            return $backlink->status !== 'rejected';
        });

        // Assign the unique and filtered data to backlink_data
        $data['backlink_data'] = $unique_backlink_data;

        return view('frontend.dashboard.backlinks', $data);
    }

    public function outlinks($forwhich_user_url)
    {
        $forwhich_user_url = decrypt($forwhich_user_url);
        $website_url = $forwhich_user_url;
        $data['data'] = Auth::user();

        // Fetch user backlink URLs
        $userBacklinkUrls = DB::table('backlinks')
            ->where('website_url', $website_url)
            ->get()
            ->toArray();   

        // Fetch outlinks
        $outlink_data = DB::table('outlinks')
            ->where('forwhich_user_url', $forwhich_user_url)
            ->get()
            ->toArray();

        // Get all backlink records to compare
        $backlinks = DB::table('backlinks')->get()->toArray();

        // Filter out outlinks that have matching reversed from_user_id and to_user_id in backlinks
        $filtered_outlink_data = array_filter($outlink_data, function($outlink) use ($backlinks) {
            foreach ($backlinks as $backlink) {
                if ($outlink->from_user_id == $backlink->to_user_id && $outlink->to_user_id == $backlink->from_user_id) {
                    return false;
                }
            }
            return true;
        });

        // Merge and filter unique data based on both `forwhich_user_url` and `website_url`
        $all_outlinks = array_merge($userBacklinkUrls, $filtered_outlink_data);

        $unique_outlink_data = [];
        $seen = [];

        foreach ($all_outlinks as $outlink) {
            $key = $outlink->forwhich_user_url . '-' . $outlink->website_url;
            if (!in_array($key, $seen)) {
                $seen[] = $key;
                $unique_outlink_data[] = $outlink;
            }
        }

        // Filter out any records with status 'rejected'
        $unique_outlink_data = array_filter($unique_outlink_data, function($outlink) {
            return $outlink->status !== 'rejected';
        });

        // Assign the unique and filtered data to outlink_data
        $data['outlink_data'] = $unique_outlink_data;

        //dd($data['outlink_data']);
        return view('frontend.dashboard.outlinks', $data);
    }

    public function rejectPair($forwhich_user_url, $website_url)
    {
        $forwhich_user_url = decrypt($forwhich_user_url);
        $website_url = decrypt($website_url);


        $toUserId  = DB::table('websites')->where('website_url', $forwhich_user_url)->select('user_id')->pluck('user_id')->first();
        //dd($fromUserId);


        $fromUserId = DB::table('websites')->where('website_url', $website_url)->select('user_id')->pluck('user_id')->first();

        //dd($toUserId);

        // Check if the rejection already exists
        $exists = RejectedPair::where('from_user_id', $fromUserId)
                    ->where('to_user_id', $toUserId)
                    ->exists();

        if (!$exists) {
            RejectedPair::create([
                'from_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
            ]);
        }

        // Check in backlinks table
        $backlinkExists = Backlink::where('forwhich_user_url', $forwhich_user_url)
                                    ->where('website_url', $website_url)
                                    ->exists();

        // Check in outlinks table
        $outlinkExists = Outlink::where('forwhich_user_url', $forwhich_user_url)
                                ->where('website_url', $website_url)
                                ->exists();

        // Update status to "rejected" in the appropriate table
        if ($backlinkExists) {
            Backlink::where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->update(['status' => 'rejected']);
        }

        if ($outlinkExists) {
            Outlink::where('forwhich_user_url', $forwhich_user_url)
                    ->where('website_url', $website_url)
                    ->update(['status' => 'rejected']);
        }

        return back()->with('reject_message', 'Connection request rejected successfully');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();
        $endpart = '@serpsupport';
        if(!$user)
        {
            $user = User::create(['name' => $googleUser->name, 'email' => $googleUser->email, 'password' => Hash::make($googleUser->name.$endpart), 'is_email_verified'=>1,'status'=>'active']);
        }
        Auth::login($user);
        $name = Auth::user()->name;
        Mail::send('email.welcome', ['name' => $name] , function($message) use ($name) {
            $message->to(Auth::user()->email)->subject('Welcome to SerpSupport');
        });
        //return redirect('account-settings');
        return redirect('addwebsite');
        #return redirect(RouteServiceProvider::HOME);
    }

    public function make_account(Request $request)
    {  
        $request->validate([
            'name' => 'required|min:3|unique:users',
            //'last_name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            //'confirm_password' => 'required|min:6|same:password',
            //'profilepic'=> 'public/userprofiles/default.jpg',
        ]);
        
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['status'] = "active";
        $createUser = User::create($data);
        $token = Str::random(64);
        UserVerify::create([
              'user_id' => $createUser->id, 
              'token' => $token
            ]);
        Mail::send('email.emailVerificationEmail', ['token' => $token], function($message) use($request){
              $message->to($request->email);
              $message->subject('Email Verification Mail');
          });
        return redirect('login')->with('message', 'Email verification link has been sent to your email. Please verify your email address ...');
        //return redirect("/")->with('register_success','Registered Successfully. Login Now :)');
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();
  
        $message = 'Sorry your email cannot be identified.';
  
        if(!is_null($verifyUser) ){
            $user = $verifyUser->user;
              
            if(!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }
  
      return redirect()->route('login')->with('message', $message);
    }

    public function make_login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $data = User::where('email', $request->email)->first();
            if($data->status=='active' && $data->is_email_verified=='1'){
                return redirect()->intended('addwebsite');
            }
            elseif($data->status=='blocked'){
                $this->signout();
                return back()->with('status_signin_failed','Account Blocked. Please contact Admin .. ');    
            }elseif($data->is_email_verified==0){
                $this->signout();
                return back()->with('status_signin_failed','Please verify your email address ..');
            }else{
                $this->signout();
                return redirect("login")->with('status_signin_failed','Login details are not valid. Try forget password or login with Google.');
            }
        }
        else{
            $this->signout();
            //return redirect("login")->with('status_signin_failed','User Not Exists');
            return redirect("login")->with('status_signin_failed','Login details are not valid. Try forget password or login with Google.');
        }
    }

    public function chat(Request $request, $id)
    {
        $currentUrl = url()->current(); // Get the current URL
        $get = 'users'; // You can modify this based on your logic
        $user = Auth::user(); // Get the authenticated user
        
        // Fetch the last message between the authenticated user and the user with the given $id
        $lastMessage = \DB::table('ch_messages')
            ->where(function($query) use ($user, $id) {
                $query->where('from_id', $user->id)
                      ->where('to_id', $id);
            })
            ->orWhere(function($query) use ($user, $id) {
                $query->where('from_id', $id)
                      ->where('to_id', $user->id);
            })
            ->latest()
            ->first(); // Retrieve the latest message

        $messenger_color = $user->messenger_color;

        // Calculate $lastMessageBody if $lastMessage is not null
        if ($lastMessage) {
            $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
            $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8').'..' : $lastMessageBody;
        } else {
            $lastMessageBody = null;
        }

        return view('Chatify::pages.app', [
            'id' => $id ?? 0,
            'currentUrl' => $currentUrl, // Pass current URL to the view
            'get' => $get,
            'user' => $user,
            'lastMessage' => $lastMessage,
            'lastMessageBody' => $lastMessageBody, // Pass lastMessageBody to the view
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => $user->dark_mode < 1 ? 'light' : 'dark',
        ]);
    }

    public function submitlinkdetails(Request $request)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'typeoflink' => 'required',
            'outlink_on' => 'required',
            'backlink_to' => 'required',
            'anchor_text' => 'required',
            'outlink_placed_on_your_website' => 'required',
            'chat_id' => 'required'
        ]);

        // Retrieve the matching record from 'submitlinks' based on 'chat_id'
        $submitlink = DB::table('submitlinks')
            ->where('chat_id', $data['chat_id'])
            ->first();

        // Helper function to extract domain from URL
        function extractDomain($url) {
            // Ensure URL has a scheme (http/https)
            if (!preg_match('#^http(s)?://#', $url)) {
                $url = 'http://' . $url; // Add a scheme if missing
            }

            // Extract host (domain) from the URL
            $parsedUrl = parse_url($url, PHP_URL_HOST);

            // Remove subdomains and keep only the base domain (e.g., example.com)
            $domainParts = explode('.', $parsedUrl);
            $domain = implode('.', array_slice($domainParts, -2));

            return $domain; // e.g., returns 'physicswala.com'
        }

        // Extract domain from $submitlink->outlink_on and $data['backlink_to'
        $outlinkOnDomain = extractDomain($submitlink->outlink_on);
        $backlinkToDomain = extractDomain($data['backlink_to']);

        // Extract domain from $submitlink->backlink_to and $data['outlink_on']
        $backlinkToDomainSubmit = extractDomain($submitlink->backlink_to);
        $outlinkOnDomainData = extractDomain($data['outlink_on']);

        // Check if domains match, not the full URLs
        if (!$submitlink || $outlinkOnDomain !== $backlinkToDomain || $backlinkToDomainSubmit !== $outlinkOnDomainData) {
            // Redirect back with an error message if there's a mismatch
            return back()->with('error', 'The provided outlink or backlink does not match our records.');
        }

        // If everything matches, proceed with the update
        $data['chat_status'] = "closed";

        // Update the 'submitlinks' table
        DB::table('submitlinks')
            ->where('chat_id', $data['chat_id'])
            ->update($data);

        // Update 'ch_messages' table to archive the chat
        $myuniqueid = $data['chat_id'] . "_@@!!";
        DB::table('ch_messages')
            ->where('myuniqueid', $myuniqueid)
            ->update(['chatarchieve' => 'yes']);

        // Check if chat_id exists in 'backlinks' table
        $backlinkExists = DB::table('backlinks')
            ->where('chat_id', $data['chat_id'])
            ->exists();

        // Check if chat_id exists in 'outlinks' table
        $outlinkExists = DB::table('outlinks')
            ->where('chat_id', $data['chat_id'])
            ->exists();

        // Update 'chat_status' to 'closed' in 'backlinks' if chat_id is found there
        if ($backlinkExists) {
            DB::table('backlinks')
                ->where('chat_id', $data['chat_id'])
                ->update(['chat_status' => 'closed']);
        }

        // Update 'chat_status' to 'closed' in 'outlinks' if chat_id is found there
        if ($outlinkExists) {
            DB::table('outlinks')
                ->where('chat_id', $data['chat_id'])
                ->update(['chat_status' => 'closed']);
        }

        // Add notification to the notifications table
        $notification = [
            'forwhich_user_url' => $data['outlink_on'],
            'website_url' => $data['backlink_to'],
            'acceptedby_from' => 'yes',
            'acceptedby_to' => 'yes',
            'connnection_text' => "Link details submitted",
            'seen' => false,
        ];
        DB::table('notifications')->insert($notification);

        // Return back with success
        return back()->with('success', 'Link details updated successfully.');
    }

    public function signOut() 
    {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}