<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;    
use Illuminate\Support\Facades\DB;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
            $user = Socialite::driver('google')->user();
            $finduser = DB::table('tbl_admin')->where('admin_google_id', $user->getId())->first();
            if ($finduser) {
                session(['admin_name'=>$finduser->admin_name]);
                session(['admin_id'=>$finduser->id]);
                return Redirect::to('/dashboard');
            } else {
                $newUser = DB::table('tbl_admin')->insert([
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                    'admin_google_id' => $user->id,
                    'admin_password' => md5($user->id),
                ]);
                if ($newUser) {
                    session(['admin_name'=>$user->name]);
                    session(['admin_id'=>$user->id]);
                    return Redirect::to('/dashboard');
                }
                else{
                    return Redirect::to('/admin');
                }
            }
        
    }
}
