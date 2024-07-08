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

    public function createbacklinks(){  //this function calls every hour//
        $data['data'] = Auth::user();
        $a = User::all();
        $pairs = array();
        for ($i = 0; $i < count($a) - 1; $i++) {
            $getarray = $a[$i]->toArray();
            $pairs[] = $getarray['id'] . "," . $getarray['id']+1;
            $getfirstuserdata = User::where('id',$getarray['id'])->get()->toArray();
            $getseconduserdata = User::where('id',$getarray['id']+1)->get()->toArray();
            $getfirstuserwebsites = Website::where('website_uploader_email',$getarray['email'])->get()->toArray();
            foreach($getfirstuserwebsites as $send) {
            $inserts = array_unique(array());
            $checkifalreadyexists = Backlink::where('website_url',$send['website_url'])->get()->toArray();
            if(empty($checkifalreadyexists) || (!$checkifalreadyexists)){
                $inserts[] = [ 'from_user_id' => $getarray['id'],
                'to_user_id' =>$getarray['id']+1 , 
                'website_id' => $send['website_id'] , 
                'website_url' => $send['website_url'],
                'website_niche' => $send['website_niche'], 
                'website_description' => $send['website_description'] , 
                'status' => "not_accepted_yet",
            ];
            //dd($inserts);
            $createbackink =DB::table('backlinks')->insert($inserts);
            }else{
                //echo "Backlink already created";
            }
            }
        }
    }

    public function backlinks($id){
        $id = decrypt($id);
        $data['data'] = Auth::user();
        $data['retrieve'] = DB::table('websites')->where('website_id',$id)->get();
        return view('frontend.dashboard.backlinks',$data);
    }

    public function outlinks($id){
        $id = decrypt($id);
        $data['data'] = Auth::user();
        $data['retrieve'] = DB::table('websites')->where('website_id',$id)->get();
        return view('frontend.dashboard.outlinks',$data);
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
        $data['website_uploader_email'] = Auth::user()->email;
        $pushwebsitetodatabse = Website::create($data);
        return redirect('account-settings')->with('message', 'Website Added Successfully ...');
        }else{
            return back()->with('error_message', 'You can add Max 10 websites');
        }
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