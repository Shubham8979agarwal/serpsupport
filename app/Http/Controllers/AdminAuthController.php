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
use App\Models\Promocodes;
use App\Models\Plan;
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
	public function __construct(){
        $this->middleware('disable_back_btn');
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function adminlogin(){  
        if(auth('admin')->check()){
            return redirect('admin-dashboard');
        }
        else{
            return view('admin.login');
        }
    }

    public function make_admin_login(Request $request){
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

    public function admindashboard(){
       $data['count_all_users'] = DB::table('users')->count();
       $data['count_total_websites'] = DB::table('websites')->count();
       //$data['count_total_messages'] = DB::table('ch_messages')->count();
       $data['count_total_plans'] = DB::table('plans')->count();
       $data['count_total_backlink_connections'] = DB::table('backlinks')->count();
       $data['count_total_outlink_connections'] = DB::table('outlinks')->count();
       $data['count_total_connections'] = $data['count_total_backlink_connections'] + $data['count_total_outlink_connections'];
       return view('admin.admin-dashboard',$data);  
    }

    public function all_users(){
       $data['allusers'] = DB::table('users')->get(); 
       return view('admin.all-users',$data);   
    }

    public function all_websites(){
       $data['allwebsites'] = DB::table('websites')->get(); 
       return view('admin.all-websites',$data);   
    }

    public function connections(){
       $data['connections'] = DB::table('submitlinks')->get(); 
       return view('admin.connections',$data);   
    }

    public function delete_connection($chat_id){
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

        $ch_messages_record = DB::table('ch_messages')
            ->where('from_user_id', $from_user_id)->orwhere('from_user_id', $to_user_id)
            ->where('to_user_id', $to_user_id)->orwhere('to_user_id',$from_user_id)
            ->first();

        if($ch_messages_record){
           DB::table('ch_messages')
            ->where('from_user_id', $from_user_id)->orwhere('from_user_id', $to_user_id)
            ->where('to_user_id', $to_user_id)->orwhere('to_user_id',$from_user_id)
            ->delete();
        }

        $submitlinks_record = DB::table('submitlinks')
            ->where('acceptedby_to', $from_user_id)->orwhere('acceptedby_from', $to_user_id)
            ->where('acceptedby_from', $to_user_id)->orwhere('acceptedby_to',$from_user_id)
            ->first();

        if($submitlinks_record){
           DB::table('submitlinks')
            ->where('acceptedby_to', $from_user_id)->orwhere('acceptedby_from', $to_user_id)
            ->where('acceptedby_from', $to_user_id)->orwhere('acceptedby_to',$from_user_id)
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
        $delete_user = DB::delete('delete from websites where user_id = ?',[$userid]);
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

    public function plans(){
        return view('admin.plans-module');
    }

    public function add_plans(Request $request)
    {
        // Validate the form input
        $data = $request->validate([
            'plan_name' => 'required|unique:plans',
            'plan_pricing' => 'required',
            'plan_type' => 'required',
            'plan_description' => 'required'
        ]);

        $plainDescription = strip_tags($data['plan_description']);
        
        if (str_word_count($plainDescription) > 250) {
            return back()->withErrors(['plan_description' => 'Plan description exceeds 250 words.'])->withInput();
        }

        $data['plan_status'] = "on";

        Plan::create($data);

        return redirect('show-plans')->with('message', 'Plan Added Successfully ...');
    }

    public function show_plans(){
        $data['pushedplans'] = DB::table('plans')->get();
        return view('admin.show-plans',$data);
    }

    public function delete_plan($id){
        $id = decrypt($id);
        $deletereq_website = DB::delete('delete from plans where id = ?',[$id]);
        return back()->with('message','Plan Deleted Successfully...');
    }

    public function turn_off_plan($planid){
        $planid = decrypt($planid);
        $updateDetails = ['plan_status' => 'off'];    
            $update = DB::table('plans')->where('id', $planid)->update($updateDetails);
        return back()->with('message','Plan Deactivated Successfully...');
    }

    public function turn_on_plan($planid){
        $planid = decrypt($planid);
        $updateDetails = ['plan_status' => 'on'];    
            $update = DB::table('plans')->where('id', $planid)->update($updateDetails);
        return back()->with('message','Plan Activated Successfully...');
    }

    public function promocode(){
        $data['data'] = DB::table('promocodes')->get();
        return view('admin.promocode',$data);
    }

    public function createpromocode(Request $request){
      $data = $request->validate([
            'promocode_name' => 'required|unique:promocodes',
            'discount' => 'required',
            'promocode_description' => 'required'
        ]);
        $promocode_description = strip_tags($data['promocode_description']);
        
        if (str_word_count($promocode_description) > 250) {
            return back()->withErrors(['promocode_description' => 'Promocode description exceeds 250 words.'])->withInput();
        }
        $data = $request->all();
        $data['status'] = "1";
        $createpromocode = Promocodes::create($data);
        return back()->with('message', 'Promocode Created Successfully...'); 
    }

    public function show_promocode(){
        $data['promocode'] = DB::table('promocodes')->get();
        return view('admin.show-promocode',$data);
    }

    public function delete_promocode($promocodeid){
        $promocodeid = decrypt($promocodeid);
        $deletereq_website = DB::delete('delete from promocodes where id = ?',[$promocodeid]);
        return back()->with('message','Promocode Deleted Successfully...');
    }

    public function turn_off_promocode($promocodeid){
        $promocodeid = decrypt($promocodeid);
        $updateDetails = ['status' => '0'];    
            $update = DB::table('promocodes')->where('id', $promocodeid)->update($updateDetails);
        return back()->with('message','Promocode Deactivated Successfully...');
    }

    public function turn_on_promocode($promocodeid){
        $promocodeid = decrypt($promocodeid);
        $updateDetails = ['status' => '1'];    
            $update = DB::table('promocodes')->where('id', $promocodeid)->update($updateDetails);
        return back()->with('message','Promocode Activated Successfully...');
    }

    public function adminsignout(){
        Session::flush();
        auth()->guard('admin')->logout();
        return redirect('/admin');
    }

}