<?php
namespace App\Http\Controllers;
use Validator;
use Session;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ChMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
#use App\Rules\MatchOldPasswordAdmin;
use Stripe;
use Carbon\Carbon;
use Mail;


class AdminAuthController extends Controller
{
	public function __construct()
    {
        $this->middleware('disable_back_btn');
        $this->middleware('guest', ['except' => 'logout']);
    }

	#login
    public function adminlogin()
    {  
        if(auth('admin')->check()){
            return redirect('admin-dashboard');
        }
        else{
            return view('admin.login');
        }
        
    }

    public function make_admin_login(Request $request)
    {
    	$request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('admin-dashboard');
        }
        return redirect("/admin")->with('status_signin_failed','Login details are not valid');
    }

    public function admindashboard()
    {
       $data['count_all_users'] = DB::table('users')->count();
       $data['count_total_websites'] = DB::table('websites')->count();
       $data['count_total_messages'] = DB::table('ch_messages')->count();
       $data['count_total_backlink_connections'] = DB::table('backlinks')->count();
       $data['count_total_outlink_connections'] = DB::table('outlinks')->count();
       $data['count_total_connections'] = $data['count_total_backlink_connections'] + $data['count_total_outlink_connections'];
       return view('admin.admin-dashboard',$data);  
    }

    public function all_users()
    {
       $data['allusers'] = DB::table('users')->get(); 
       return view('admin.all-users',$data);   
    }

    public function all_websites()
    {
       $data['allwebsites'] = DB::table('websites')->get(); 
       return view('admin.all-websites',$data);   
    }

    public function connections()
    {
       $data['connections'] = DB::table('submitlinks')->get(); 
       return view('admin.connections',$data);   
    }

    public function delete_connection($chat_id)
    {
        $chat_id = decrypt($chat_id);
        list($from_user_id, $to_user_id) = explode('_', $chat_id);

        $backlink_record = DB::table('backlinks')
            ->where('from_user_id', $from_user_id)
            ->where('to_user_id', $to_user_id)
            ->first();

        if ($backlink_record) {
            DB::table('backlinks')
                ->where('from_user_id', $from_user_id)
                ->where('to_user_id', $to_user_id)
                ->delete();
        }

        $outlink_record = DB::table('outlinks')
            ->where('from_user_id', $from_user_id)
            ->where('to_user_id', $to_user_id)
            ->first();

        if ($outlink_record) {
            DB::table('outlinks')
                ->where('from_user_id', $from_user_id)
                ->where('to_user_id', $to_user_id)
                ->delete();
        }

        $deletereq_connection = DB::delete('delete from submitlinks where chat_id = ?',[$chat_id]);
        
        return back()->with('message', 'Connection Deleted Successfully');
    }

    public function delete_website($id){
            $id = decrypt($id);
            $deletereq_website = DB::delete('delete from websites where id = ?',[$id]);
            return back()->with('message','Website Deleted Successfully');
    }

    public function delete_user($userid){
            $userid = decrypt($userid);
            $deletereq_website = DB::delete('delete from websites where user_id = ?',[$userid]);
            $deletereq = DB::delete('delete from users where id = ?',[$userid]);
            return back()->with('message','User Deleted Successfully');
    }

    public function block_user($userid){
            $userid = decrypt($userid);
            $updateDetails = ['status' => 'blocked'];    
                $update = DB::table('users')->where('id', $userid)->update($updateDetails);
            return back()->with('message','User Blocked Successfully');
    }

    public function unblock_user($userid){
            $userid = decrypt($userid);
            $updateDetails = ['status' => 'active'];    
                $update = DB::table('users')->where('id', $userid)->update($updateDetails);
            return back()->with('message','User Unblocked Successfully');
    }

    public function verify_email($userid){
            $userid = decrypt($userid);
            $updateDetails = ['is_email_verified' => '1'];    
                $update = DB::table('users')->where('id', $userid)->update($updateDetails);
            return back()->with('message','User Verified Successfully');
    }

    public function plans_module(){
            /*$userid = decrypt($userid);
            $updateDetails = ['is_email_verified' => '1'];    
                $update = DB::table('users')->where('id', $userid)->update($updateDetails);
            return back()->with('message','User Verified Successfully');*/
            return view('admin.plans-module');
    }

    public function adminsignout()
    {
        Session::flush();
        auth()->guard('admin')->logout();
        return redirect('/admin');
    }
}