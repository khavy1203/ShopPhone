<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Brand extends Controller
{
    //

    public function add_brand_product()
    {
        $category = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get();
        return view('admin_product.brand.add_brand_product')->with('category', $category);
    }
    public function edit_brand_product($brand_id)
    {
        $brand_product = DB::table('brands')->where('brand_id', $brand_id)->get();
        $category = DB::table('tbl_category_product')->orderby('category_id', 'desc')->get();
        return view('admin_product.brand.edit_brand_product')->with('brand_product', $brand_product)->with('category', $category);
    }
    public function all_brand_product()
    {

        $all_brand_product = DB::table('brands')
            ->join('tbl_category_product', function ($join) {
                $join->on('brands.category_id', '=', 'tbl_category_product.category_id')
                    ->where('tbl_category_product.category_status', '1')
                    ->select('brands.*', 'tbl_category_product.category_name as category_name');
            })
            ->get();
        return view('admin_product.brand.all_brand_product')->with('all_brand_product', $all_brand_product);
    }
    public function save_brand_product(Request $request)
    {

        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $kt = DB::table('brands')->where('brand_name', $request->brand_product_name)->get();
        if ($kt->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Tên thương hiệu đã tồn tại',
                'data' => $data
            ]);
        } else {
            $data['brand_desc'] = $request->brand_product_desc;
            $data['brand_status'] = $request->brand_product_status;
            $data['category_id'] = $request->category_id;
            $result = DB::table('brands')->insert($data);
            if ($result) {
                return response()->json([
                    'status' => true,
                    'message' => 'Thêm thành công',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Thêm thất bại',
                    'data' => $data
                ]);
            }
        }
    }
    public function active_brand_product($brand_product_id)
    {

        $result = DB::table('brands')
            ->where('brand_id', $brand_product_id)
            ->update(['brand_status' => 0]);
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
    public function unactive_brand_product($brand_product_id)
    {

        $result = DB::table('brands')
            ->where('brand_id', $brand_product_id)
            ->update(['brand_status' => 1]);
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
    public function update_brand_product(Request $request)
    {

        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $data['brand_desc'] = $request->brand_product_desc;
        $data['category_id'] = $request->category_id;
        $data['brand_status'] = $request->brand_product_status;
        $result = DB::table('brands')->where('brand_id', $request->brand_product_id)->update($data);
        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Cập nhật thành công",
                "name" => $data['brand_name']
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Không có gì thay đổi"
            ]);
        }
    }
    public function delete_brand_product($brand_product_id)
    {
        $product_of_brand = DB::table('products')
            ->where('products.brand_id', $brand_product_id)
            ->get()
            ->count();
        if ($product_of_brand > 0) {
            return response()->json([
                "status" => false,
                "message" => "Còn sản phẩm trong thương hiệu này",
                "title" => "Không thể xóa",
                "type" => "warn"
            ]);
        } else {
            $result = DB::table('brands')->where('brand_id', $brand_product_id)->delete();
            if ($result) {
                return response()->json([
                    "status" => true,
                    "message" => "Xóa thành công"
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Xóa thất bại",
                    "title" => "Thất bại",
                    "type" => "error"
                ]);
            }
        }
    }
    public function get_brand_by_category($category_id)
    {
        $result = DB::table('brands')->where('category_id', $category_id)->get();
        if ($result) {
            return response()->json([
                "status" => true,
                "data" => $result
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Không có dữ liệu"
            ]);
        }
    }
    //end brand_product admin
    // start brand_product home
    // lấy sảnp phẩm theo thương hiệu
    public function show_brand_product($brand_id)
    {
        $result = DB::table('products')->where('brand_id', $brand_id)
        ->where('product_status', 1)
        ->get();
        //duyệt hết mảng $result
        
        if ($result) {
            foreach ($result as $key => $value) {
                $value->product_price = number_format($value->product_price);
                $value->product_image = asset('storage/app/' . $value->product_image);
            }
            return response()->json([
                "status" => true,
                "data" => $result,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Không có dữ liệu"
            ]);
        }
    }
}
