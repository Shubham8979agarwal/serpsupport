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
use App\Models\RejectedPair;
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

    public function acceptedby_to_outlink_connection($id){
        $id = decrypt($id);
        $updateDetails = ['acceptedby_to' => 'yes', 'status' => 'pending'];
        $update = DB::table('outlinks')->where('id', $id)->update($updateDetails);
        return back()->with('message_acceptedby_to_outlink_connection','Thank you for approving the connection, we are now awaiting the approval of the other user');
    }

    public function acceptedby_from_outlink_connection($id){
        $id = decrypt($id);
        $updateDetails = ['acceptedby_from' => 'yes', 'status' => 'accepted'];
        $update = DB::table('outlinks')->where('id', $id)->update($updateDetails);
        return back()->with('message_acceptedby_from_outlink_connection','Thank you for approving the connection, You are now able to chat');
    }

    public function acceptedby_from_backlink_connection($id){
        $id = decrypt($id);
        $updateDetails = ['acceptedby_from' => 'yes', 'status' => 'pending'];
        $update = DB::table('backlinks')->where('id', $id)->update($updateDetails);
        return back()->with('message_acceptedby_from_backlink_connection','Thank you for approving the connection, we are now awaiting the approval of the other user');
    }

    public function acceptedby_to_backlink_connection($id){
        $id = decrypt($id);
        $updateDetails = ['acceptedby_to' => 'yes', 'status' => 'accepted'];
        $update = DB::table('backlinks')->where('id', $id)->update($updateDetails);
        return back()->with('message_acceptedby_to_backlink_connection','Thank you for approving the connection, You are now able to chat');
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


    public function rejectPair($from_user_id, $to_user_id)
    {
        $fromUserId = decrypt($from_user_id);
        $toUserId = decrypt($to_user_id);

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
        $backlinkExists = Backlink::where('from_user_id', $fromUserId)
                                    ->where('to_user_id', $toUserId)
                                    ->exists();

        // Check in outlinks table
        $outlinkExists = Outlink::where('from_user_id', $fromUserId)
                                ->where('to_user_id', $toUserId)
                                ->exists();

        // Update status to "rejected" in the appropriate table
        if ($backlinkExists) {
            Backlink::where('from_user_id', $fromUserId)
                    ->where('to_user_id', $toUserId)
                    ->update(['status' => 'rejected']);
        }

        if ($outlinkExists) {
            Outlink::where('from_user_id', $fromUserId)
                    ->where('to_user_id', $toUserId)
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
    
    public function signOut() {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
}