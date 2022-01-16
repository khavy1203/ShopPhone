<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;

class User extends Controller
{

    public function index()
    {
        return view('userlogin');
    }
    public function home(Request $request)
    { //kiểm tra và trả về message
        $user_email = $request->user_email;
        $user_password = md5($request->user_password);
        $result = DB::table('users')->where('user_email', $user_email)->where('user_password', $user_password)->first();
        if ($result) {
            session(['user_name' => $result->user_name]);
            session(['user_id' => $result->user_id]);
            return Redirect::to('/'); //đúng thì chuyển hướng sang đồng thời đẩy dữ liệu name và id để hiển thị trang
        } else {
            session(['message' => "sai thông tin đăng nhập hoặc mật khẩu"]);
            return Redirect::to('/user'); //sai thì chuyển hướng lại user đồng thời put message cho trang user thông báo
        }
    }

    public function logout()
    {

        /* session(['user_name'=>null]);//xóa các session name
        session(['user_id'=>null]); */
        session()->forget('user_name');
        session()->forget('user_id');
        return redirect('/');
    }
    //loginuser
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Check Users Email If Already There
            $is_user = DB::table('users')->where('user_email', $user->email)->first();
            if (!$is_user) {

                $saveUser = DB::table('users')
                    ->insert([
                        'user_name' => $user->getName(),
                        'user_email' => $user->getEmail(),
                        'user_password' => md5($user->getId()),
                        'phone' => $user->getID(),
                        'google_id' => $user->getId(),
                        'user_address' =>  $user->getID(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
            }else{
                $saveUser = DB::table('users')
                    ->where('user_email', $user->email)
                    ->update([
                        'google_id' => $user->getId(),
                    ]);
                $saveUser = DB::table('users')
                ->where('user_email', $user->email)
                ->first();
            }

            Auth::loginUsingId($saveUser->id);

            return redirect()->route('home');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
