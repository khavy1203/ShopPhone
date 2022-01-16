<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
class AdminController extends Controller
{
    
    public function index(){
        return view('admin');
    }
    public function show_dashboard(){
        return view('admin.dashboard');
    }
    public function dashboard(Request $request){//kiểm tra và trả về message
        $admin_email = $request->admin_email;
        $admin_password=md5($request->admin_password);
        $result = DB::table('tbl_admin')->where('admin_email',$admin_email)->where('admin_password',$admin_password)->first();
        if($result){
            session(['admin_name'=>$result->admin_name]);
            session(['admin_id'=>$result->id]);
            return Redirect::to('/dashboard');//đúng thì chuyển hướng sang dashboard đồng thời đẩy dữ liệu name và id để hiển thị trang dashboard
        }else{
            session(['message'=>"sai thông tin đăng nhập hoặc mật khẩu"]);
            return Redirect::to('/admin');//sai thì chuyển hướng lại admin đồng thời put message cho trang admin thông báo
        }

    }
    public function logout(){
       /*  
        session(['admin_name'=>null]);//xóa các session name
        session(['admin_id'=>null]); */
        session()->forget('admin_name');
        session()->forget('admin_id');
        return redirect('/admin');
    }
}
