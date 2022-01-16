<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Cart;
use App\Http\Controllers\SendEmail;

class Order extends Controller
{
    //admin
    public function update_order_status(Request $request)
    {
        $order_id = $request->order;
        $order_status = $request->order_status;
        $order = DB::table('orders')
            ->where('order_id', $order_id)
            ->update(['order_status' => $order_status]);

        if ($order) {
            if ($order_status == 1) {
                (new SendEmail)->send_mail($order_id);
                return response()->json([
                    'status' => 1,
                    'message' => 'Cập nhật trạng thái xác nhận đơn hàng thành công',
                ]);
            } else if ($order_status == 2) {
                (new SendEmail)->send_mail($order_id);
                return response()->json([
                    'status' => 2,
                    'message' => 'Cập nhật trạng thái đang vận chuyển thành công',
                ]);
            } else if ($order_status == 3) {
                (new SendEmail)->send_mail($order_id);
                return response()->json([
                    'status' => 3,
                    'message' => 'Cập nhật trạng thái đang giao hàng thành công',
                ]);
            } else if ($order_status == 4) {
                (new SendEmail)->send_mail($order_id);
                return response()->json([
                    'status' => 4,
                    'message' => 'Hủy đơn hàng thành công',
                ]);
            }
        }
    }
    public function show_order_admin()
    {
        $orders = DB::table('orders')
            ->join('carts', 'orders.cart_id', '=', 'carts.cart_id')
            ->join('users', 'carts.user_id', '=', 'users.user_id')
            ->get();
        return view('admin.order.order_admin')->with('orders', $orders);
    }
    //user
    public function cancel($order_id)
    {
        $order = DB::table('orders')
            ->where('order_id', $order_id)
            ->where('order_status', 0)
            ->update(['order_status' => 4]);
        if ($order) {
            (new SendEmail)->send_mail($order_id);
            return response()->json([
                'status' => 1,
                'message' => 'Hủy đơn hàng thành công',
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Hủy đơn hàng thất bại',
            ]);
        }
    }
    public function show_order(Request $request)
    {
        if ($request->session()->has('user_id')) {
            $user_id = $request->session()->get('user_id');
            /* dd($cart_id); */
            $orders = DB::table('orders')
                ->join('carts', 'orders.cart_id', '=', 'carts.cart_id')
                ->where('carts.user_id', $user_id)
                ->get();

            if (!empty($orders)) {
                return view('pages.order')->with('orders', $orders);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Không có đơn hàng nào'
                ]);
            }
        } else {
            return Redirect::to('/user');
        }
    }
    public function add_order(Request $request)
    {
        if ($request->session()->has('cart_id')) { // kiểm tra có tồn tại session cart_id không
            $cart_id = $request->session()->get('cart_id');
            $cart = new Cart();
            $order = DB::table('orders')->insertGetId([
                'cart_id' => $cart_id,
                'sub_total' => $cart->sum($cart_id), // tổng tiền
                'quantity_total' => $cart->productTotal($cart_id), //tổng sản phẩm
                'order_status' => 0, // đang chờ xử lý
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            if ($order) {
                //cập nhật lại trạng thái giỏ hàng
                DB::table('carts')->where('cart_id', $cart_id)->update(['cart_status' => 2]);
                $request->session()->forget('cart_id');
                return response()->json([
                    'status' => true,
                    'message' => 'Đặt hàng thành công',
                    'order_id' => $order
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'massage' => 'Giỏ hàng trống'
            ]);
        }
    }
}
