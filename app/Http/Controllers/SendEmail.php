<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;


class SendEmail extends Controller
{
    public function send_mail($order_id)
    {
        $orders = DB::table('orders')
        ->join('carts', 'carts.cart_id', '=', 'orders.cart_id')
        ->join('users', 'users.user_id', '=', 'carts.user_id')
        ->where('order_id', $order_id)
        ->get();
        if (!empty($orders)) {
            /*  Mail::to($email_user[0]->user_email)->send(new OrderShipped($orders)); */
            Mail::to($orders[0]->user_email)
                ->send(new OrderShipped($orders));
        }
    }
}
