<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class Cart extends Controller
{
    public function priceDetail($product_id, $quantity)// tính giấ riêng từng sản phẩm( viết nhưng chưa sài)
    {
        $product = DB::table('products')->where('product_id', $product_id)->first();
        $price = $product->product_price;
        $priceDetail = $price * $quantity;
        return $priceDetail;
    }
    public function sum($cart_id){//tính tổng cart_item có trong giỏ hàng
        $sum = null;
        $result = DB::table('cart_items')
            ->join('products','products.product_id','=','cart_items.product_id')
            ->where('cart_id',$cart_id)
            ->where('is_active',1)
            ->get();
        if($result){
            foreach ($result as $key => $value) {
                $sum += $value->quantity * $value->product_price;
            }
        }
        return $sum;
    }
    public function productTotal($cart_id){//tính tổng sản phẩm trong giỏ hàng
        $productTotal = null;
        $result = DB::table('cart_items')
            ->join('products','products.product_id','=','cart_items.product_id')
            ->where('cart_id',$cart_id)
            ->where('is_active',1)
            ->get();
        if($result){
            foreach ($result as $key => $value) {
                $productTotal += $value->quantity;
            }
        }
        return $productTotal;
    }
    public function del_cart_item(Request $request)
    {
        $data = $request->all();
        $userID = $data['userID'];
        $cartItemId = $data['cartItemID'];
        $cart = DB::table('carts')
            ->where('user_id', $userID)
            ->where('cart_status', 0)
            ->get();
        if (count($cart) > 0) {
            $cart_id = $cart[0]->cart_id;
            $result = DB::table('cart_items')
                ->where('cart_id', $cart_id)
                ->where('cart_item_id', $cartItemId)
                ->delete();
            $have_item = DB::table('cart_items')
                ->where('cart_id', $cart_id)
                ->where('is_active', 1)
                ->get();
            if ($result && $have_item->count() > 0) {
                return response()->json([
                    'productTotal'=>$this->productTotal($cart_id),
                    'priceDetail'=> $this->priceDetail($have_item[0]->product_id, $have_item[0]->quantity),
                    'sum'=> number_format($this->sum($cart_id)),
                    'status' => true,
                    'message' => 'Xóa thành công'
                ]);
            } else {
                return response()->json([
                    'clear' => true,
                    'status' => false,
                    'message' => 'Xóa sạch giỏ hàng'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Không tồn tại giỏ hàng'
            ]);
        }
    }

    public function update_cart_item($cart_item_id, $quantity)
    {
        $cart_item = DB::table('cart_items')->where('cart_item_id', $cart_item_id)->get();
        if ($cart_item->count() > 0) {
            if ($quantity > 0 && $quantity < 1000) {
                $result = DB::table('cart_items')->where('cart_item_id', $cart_item_id)->update(['quantity' => $quantity]);
                if ($result) {
                    return response()->json([
                        'productTotal'=>$this->productTotal($cart_item[0]->cart_id),
                        'sum' => number_format($this->sum($cart_item[0]->cart_id)),
                        'status' => true,
                        'message' => 'Cập nhật số lượng thành công'
                    ]);
                } else {
                    return response()->json([
                        'productTotal'=>$this->productTotal($cart_item[0]->cart_id),
                        'sum' => number_format($this->sum($cart_item[0]->cart_id)),
                        'status' => false,
                        'message' => 'Không có gì thay đổi'
                    ]);
                }
            } else if($quantity >=1000){
                return response()->json([
                    'max' => true,
                    'message' => 'Giá trị đặt hàng quá lớn, vui lòng liên hệ với chúng tôi để được hỗ trợ'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm không tồn tại'
            ]);
        }
    }
    public function update_cart_item_onCart($userID, $cart_item_id, $quantity)
    {
        $user = DB::table('users')->where('user_id', $userID)->get(); //kiểm tra có user ko
        if ($user->count() > 0) {
            $cart = DB::table('carts')->where('user_id', $userID)->get(); // kiểm tra có giỏ  hàng mầ user có trỏng không
            if ($cart->count() > 0) { //tồn tại giỏ hàng
                return $this->update_cart_item($cart_item_id, $quantity);
            }
        } else {
            return response()->json([
                "status" => "not_found",
                "message" => "Không tìm thấy người dùng",
                "type" => "error"
            ]);
        }
    }

    public function show_cart(Request $request)
    {
        // get session user id
        $userId = $request->session()->get('user_id');

        $productTotal = null;
        $listcartItem = null;
        $sumPrice = null;
        $carID = DB::table('carts') // lấy ra giỏ hàng thuộc userID
            ->where('user_id', $userId)
            ->where('cart_status', 0)
            ->get();

        if(!empty($carID)){//kiểm tra truy vấn không được rỗng
            if ($carID->count() > 0) {
                $listcartItem = DB::table('cart_items')
                    ->join('products', 'products.product_id', '=', 'cart_items.product_id')
                    ->where('cart_id', $carID[0]->cart_id)
                    ->get();
                if ($listcartItem->count() > 0) {
                    session(['cart_id' => $carID[0]->cart_id]);
                    foreach ($listcartItem as $key => $value) {
                        $value->product_image = asset('storage/app/' . $value->product_image);
                        $sumPrice += $value->product_price * $value->quantity;
                        $productTotal += $value->quantity;
                    }
                }
            }
        }
        return view('pages.cart')->with('listcartItem', $listcartItem)->with('sumPrice', $sumPrice)->with('productTotal', $productTotal);
    }
    public function add_to_cart($userId, $productId, $quantity)
    {
        $user = DB::table('users')->where('user_id', $userId)->get(); //kiểm tra có user ko
        if ($user->count() > 0) {
            $cart = DB::table('carts')->where('user_id', $userId)
            ->where('cart_status', 0)//chỉ lấy giỏ hàng chưa thanh toán
            ->get(); // kiểm tra có giỏ  hàng mầ user có trỏng không
            if ($cart->count() > 0) {
                //tồn tại giỏ hàng của user thì tiến hành thêm hàng vào giỏ hàng
                foreach ($cart as $c) {
                    if ($c->cart_status == 0) { //chỉ lấy giỏ hàng mà trạng thái của giỏ hàng là =0(giỏ hàng mới hoặc chưa thanh toán)
                        return $this->add_cart_item($c->cart_id, $productId, $quantity);
                    }
                }
            } else {
                // khi chưa có giỏ hàng nào của user thì tiến hành tạo giỏ hàng mới
                foreach ($user as $u) {
                    $data = array();
                    $data['user_id'] = $u->user_id;
                    $data['phone'] = $u->phone;
                    $data['address'] = $u->user_address;
                    $data['cart_status'] = 0; // =   0 là giỏ hàng tồn tại, 1 là đã thanh toán, 2 là hoàn thầnh, 3 là bỏ
                    $data['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');
                    $data['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
                    $result = DB::table('carts')->insert($data);
                    // tạo thêm giỏ hàng mới đồng thời sẽ thêm sản phẩm
                    if ($result) {
                        //sau khi tạo xong tiến hành thêm sản phẩm vào giỏ hàng và trạng thái sản phẩm tồn tại trong giỏ sẽ là 1
                        $cart_id = DB::table('carts')
                            ->where('user_id', $u->user_id)
                            ->where('cart_status', 0)
                            ->get();
                        return $this->add_cart_item($cart_id[0]->cart_id, $productId, $quantity);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Tạo  thất bại',
                            'type' => 'error'
                        ]);
                    }
                }
            }
        } else {
            return response()->json([
                "status" => "not_found",
                "message" => "Không tìm thấy người dùng",
                "type" => "error"
            ]);
        }
    }
    public function add_cart_item($cartId, $productId, $quantity)
    {

        $cartResult = DB::table('carts')
            ->where('cart_id', $cartId)
            ->where('cart_status', 0)
            ->get()
            ->count();
        $productIdResult = DB::table('products')
            ->where('product_id', $productId)
            ->where('product_status', 1) // kiểm tra sản phẩm tồn tại trạng thái hiện
            ->get();
        $cart_items_exist = DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->where('is_active', 1)
            ->get();
        if ($cartResult > 0 && $productIdResult->count() > 0 && $quantity > 0) {
            if ($cart_items_exist->count() > 0) {
                //update lại sản phẩm
                return $this->update_cart_item($cart_items_exist[0]->cart_item_id, $quantity + $cart_items_exist[0]->quantity);
            } else { //khi chưa tồn tại sản phẩm trong giỏ hàng thì tiến hành thêm sản phẩm vào giỏ hàng
                $arr = array();
                $arr['cart_id'] = $cartId;
                $arr['product_id'] = $productId;
                $arr['quantity'] = $quantity;
                $arr['is_active'] = 1; //đã xác nhận tồn tại trong giỏ hàng
                $arr['created_at'] = Carbon::now('Asia/Ho_Chi_Minh');
                $arr['updated_at'] = Carbon::now('Asia/Ho_Chi_Minh');
                $result = DB::table('cart_items')->insert($arr);
                if ($result) {
                    return response()->json([
                        'status' => 'add_product_success',
                        'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                        'type' => 'success'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'add_product_error',
                        'message' => 'Thêm sản phẩm thất bại',
                        'type' => 'error'
                    ]);
                }
            }
        }
        else if($quantity <=0){
            return response()->json([
                "status" => "error",
                "message" => "Số lượng sản phẩm phải lớn hơn 0",
                "type" => "error"
            ]);
        }else{
            return response()->json([
                "status" => "error",
                "message" => "Lỗi không mong đợi",
                "type" => "error"
            ]);
        }
    }
}
