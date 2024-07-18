<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Session;
use Hash;
use App\Models\UserVerify;
use App\Models\Website;
use App\Models\Backlink;
use App\Models\Outlink;
use Mail;
use Illuminate\Support\Str;
use DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class GoogleLoginController extends Controller
{

    public function __construct()
   {
        $this->middleware('disable_back_btn');
        #$this->middleware('auth');
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

    public function addwebsite(){
        $data['data'] = Auth::user();
        return view('frontend.dashboard.addwebsite',$data);
    }

    public function deletewebsite($id){
        $id = decrypt($id);
        $deletereq = DB::delete('delete from websites where website_id = ?',[$id]);
        return redirect('account-settings')->with('message','Website Deleted Successfully');
    }

    public function push_website(Request $request){
        $numberOfwebsite = Website::where('website_uploader_email',Auth::user()->email)->count();
        if($numberOfwebsite<10){
            $request->validate([
            'website_niche' => 'required',
            'website_url' => 'required',
            'website_description' => 'required',
         ]);
        $data = $request->all();
        if(str_word_count($data['website_description'])>250){
           return back()->with('error_message', 'Failed! The website description exceeds 250 words.'); 
        }
        $data['website_id'] = Str::random(10);
        $data['user_id'] = Auth::user()->id;
        $data['website_uploader_email'] = Auth::user()->email;
        $pushwebsitetodatabse = Website::create($data);
        return redirect('account-settings')->with('message', 'Website Added Successfully ...');
        }else{
            return back()->with('error_message', 'You can add Max 10 websites');
        }
    }

    public function backlinks($forwhich_user_url){
        $forwhich_user_url = decrypt($forwhich_user_url);
        $data['data'] = Auth::user();
        $data['backlink_data'] = DB::table('backlinks')->where('forwhich_user_url',$forwhich_user_url)->get()->toArray();
        return view('frontend.dashboard.backlinks',$data);
    }

    public function outlinks($forwhich_user_url){
        $forwhich_user_url = decrypt($forwhich_user_url);
        $data['data'] = Auth::user();
        $data['outlink_data'] = DB::table('outlinks')->where('forwhich_user_url',$forwhich_user_url)->get()->toArray();
        return view('frontend.dashboard.outlinks',$data);
    }

    public function createbacklinks(){  //this function calls 5 minutes//   
        $data['data'] = Auth::user();
        $getverified_users = User::where('is_email_verified', '1')->get();

        $checkarray = [];
        foreach ($getverified_users as $check) {
            $websites = Website::where('website_uploader_email', $check->email)->get()->toArray();
            if (!empty($websites)) {
                $checkarray[] = [
                    'user_id' => $check->id,  // Assuming 'id' is the user_id field
                    'websites' => $websites,
                ];
            }
        }

        $pairs = [];
        for ($i = 2; $i < count($checkarray); $i++) {
            $currentUserId = $checkarray[$i]['user_id'];
            $previousUserId = $checkarray[$i - 2]['user_id'];

            $pairs[] = $currentUserId . "," . $previousUserId;

            $currentWebsites = $checkarray[$i]['websites'];
            $previousWebsites = $checkarray[$i - 2]['websites'];

            $inserts = [];
            foreach ($currentWebsites as $currentWebsite) {
                    foreach ($previousWebsites as $previousWebsite) {
                        $checkIfAlreadyExists = Backlink::where('website_url', $previousWebsite['website_url'])->exists();
                        if (!$checkIfAlreadyExists) {
                        $inserts[] = [
                            'from_user_id' => $currentUserId,
                            'to_user_id' => $previousUserId,
                            'forwhich_user_url' => $currentWebsite['website_url'],
                            'website_id' => $currentWebsite['website_id'],
                            'website_url' => $previousWebsite['website_url'],
                            'website_niche' => $currentWebsite['website_niche'],
                            'website_description' => $currentWebsite['website_description'],
                            'status' => "not_accepted_yet",
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                // Insert data into the database
                DB::beginTransaction();
                try {
                    DB::table('backlinks')->insert($inserts);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    // Handle the exception (e.g., log it, throw it)
                    // Example: throw new \Exception("Error inserting data: " . $e->getMessage());
                }
            }
        }
        //dd($pairs);
    }

    public function createoutlinks(){  //this function calls 5 minutes//

        $data['data'] = Auth::user();
        $getverified_users = User::where('is_email_verified', '1')->get();

        $checkarray = [];
        foreach ($getverified_users as $check) {
            $websites = Website::where('website_uploader_email', $check->email)->get()->toArray();
            if (!empty($websites)) {
                $checkarray[] = [
                    'user_id' => $check->id,  // Assuming 'id' is the user_id field
                    'websites' => $websites,
                ];
            }
        }

        $pairs = [];
        for ($i = 0; $i < count($checkarray) - 1; $i++) {
            $currentUserId = $checkarray[$i]['user_id'];
            $nextUserId = $checkarray[$i + 1]['user_id'];

            $pairs[] = $currentUserId . "," . $nextUserId;

            $currentWebsites = $checkarray[$i]['websites'];
            $nextWebsites = $checkarray[$i + 1]['websites'];

            $inserts = [];
            foreach ($currentWebsites as $currentWebsite) {
                $checkIfAlreadyExists = Outlink::where('website_url', $currentWebsite['website_url'])->exists();
                if (!$checkIfAlreadyExists) {
                    foreach ($nextWebsites as $nextWebsite) {
                        $inserts[] = [
                            'from_user_id' => $currentUserId,
                            'to_user_id' => $nextUserId,
                            'forwhich_user_url' => $nextWebsite['website_url'],
                            'website_id' => $currentWebsite['website_id'],
                            'website_url' => $currentWebsite['website_url'],
                            'website_niche' => $currentWebsite['website_niche'],
                            'website_description' => $currentWebsite['website_description'],
                            'status' => "not_accepted_yet",
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                // Insert data into the database
                DB::beginTransaction();
                try {
                    DB::table('outlinks')->insert($inserts);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    // Handle the exception (e.g., log it, throw it)
                    // Example: throw new \Exception("Error inserting data: " . $e->getMessage());
                }
            }
        }
        //dd($pairs);
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
    
    public function signOut() {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}