<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class Product extends Controller
{
    //
    public function add_product()
    {

        $cate_product = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get();
        $last_category_product = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get()->first();
        $brand_product = DB::table('brands')->orderby('brand_id', 'desc')->get();
        return view('admin_product.product.add_product')->with('category', $cate_product)->with('brand', $brand_product)->with('last_category', $last_category_product);
    }

    public function edit_product($product_id)
    {
        $cate_product = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('brands')->orderby('brand_id', 'desc')->get();

        $edit_product = DB::table('products')
            ->join('tbl_category_product', 'products.category_id', '=', 'tbl_category_product.category_id')
            ->join('brands', 'products.brand_id', '=', 'brands.brand_id')
            ->where('products.product_id', $product_id)->get();

        return view('admin_product.product.edit_product')->with('edit_product', $edit_product)->with('category', $cate_product)->with('brand', $brand_product);
    }
    public function all_product()
    {
        $all_product = DB::table('products')
            ->join('brands', 'brands.brand_id', '=', 'products.brand_id')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'products.category_id')
            ->where('tbl_category_product.category_status', '=', 1) //lay ra tat ca san pham co trang thai la 1
            ->where('brands.brand_status', '=', 1) //lấy ra các thương hiệu có trạng thái bằng 1
            ->get(); //phù hợp 2 điều kiện trên thì hiển thị, không thì không hiển thị
        return view('admin_product.product.all_product')->with('all_product', $all_product);
    }
    public function save_product(Request $request)
    {
        $kt = DB::table('products')->where('product_name', $request->product_name)->count();
        if ($kt) {
            return response()->json([
                "status" => false,
                "message" => "Tên sản phẩm đã tồn tại"
            ]);
        } else {
            $data = array();
            $data['product_name'] = $request->product_name;
            $data['category_id'] =  $request->cate;
            $data['brand_id'] =  $request->brand;
            $data['product_desc'] = $request->product_desc;
            $data['product_content'] = $request->product_content;
            $data['product_price'] = $request->product_price;
            $data['product_status'] = $request->product_status;
            $get_image = $request->file('product_image');
            if ($get_image) {
                /* $get_name_image = $get_image->hashName();
            $name_image = current(explode('.',$get_name_image));    
            $new_image = $name_image.rand(0,99).'.'.$get_image->extension();   
            $get_image->move('public/uploads/product',$new_image);   
            $data['product_image'] = $new_image;  */
                $data['product_image'] = $request->file('product_image')->store('public/uploads/product');
                $result = DB::table('products')->insert($data);
                if ($result) {

                    return response()->json([
                        "status" => true,
                        "message" => "Thêm thành công",
                        "name" => $data['product_name']
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Thêm thất bại"
                    ]);
                }
            }
        }
    }
    public function active_product($product_id)
    {
        $result = DB::table('products')
            ->where('product_id', $product_id)
            ->update(['product_status' => 0]);
        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Ẩn thành công",
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ẩn thất bại"
            ]);
        }
    }
    public function unactive_product($product_id)
    {
        $result = DB::table('products')
            ->where('product_id', $product_id)
            ->update(['product_status' => 1]);
        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Hiện thành công",
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Hiện thất bại"
            ]);
        }
    }

    public function update_product(Request $request)
    {
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['category_id'] =  $request->cate;
        $data['brand_id'] =  $request->brand;
        $data['product_desc'] = $request->product_desc;
        $data['product_content'] = $request->product_content;
        $data['product_price'] = $request->product_price;
        $data['product_status'] = $request->product_status;
        $get_image = $request->file('product_image');

        if ($get_image) {
            /* $get_name_image = $get_image->hashName();
            $name_image = current(explode('.',$get_name_image));    
            $new_image = $name_image.rand(0,99).'.'.$get_image->extension();   
            $get_image->move('public/uploads/product',$new_image);   
            $data['product_image'] = $new_image;  */
            $data['product_image'] = $request->file('product_image')->store('public/uploads/product');
            $result = DB::table('products')->where('product_id', $request->product_id)->update($data);
            if ($result) {
                return response()->json([
                    "status" => true,
                    "message" => "Cập nhật thành công",
                    "name" => $data['product_name']
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Không có gì thay đổi"
                ]);
            }
        } else {
            $result = DB::table('products')->where('product_id', $request->product_id)->update($data);
            if ($result) {
                return response()->json([
                    "status" => true,
                    "message" => "Cập nhật thành công",
                    "name" => $data['product_name']
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Không có gì thay đổi"
                ]);
            }
        }
    }
    public function delete_product($product_id)
    {
        $result = DB::table('products')->where('product_id', $product_id)->delete();
        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Xóa thành công"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Xóa thất bại"
            ]);
        }
    }
    //end admin product
    //start user product
    public function detail_product($product_id)
    {
        $result = DB::table('products')
        ->join('brands', 'products.brand_id', '=', 'brands.brand_id')
        ->join('tbl_category_product', 'products.category_id', '=', 'tbl_category_product.category_id')
        ->where('products.product_id', $product_id)
        ->get();
        
        if ($result->count() > 0) {
            foreach ($result as $key => $value) {
                $value->product_price = number_format($value->product_price);
                $value->product_image = asset('storage/app/' . $value->product_image);
            }
            return response()->json([
                "status" => true,
                "message" => "Lấy thông tin thành công",
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Lấy thông tin thất bại"
            ]);
        }
    }
}
