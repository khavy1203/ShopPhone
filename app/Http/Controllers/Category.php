<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class Category extends Controller
{

    //
    public function add_category_product()
    {
        return view('admin_product.category.add_category_product');
    }
    public function edit_category_product($category_id)
    {
        $category_product = DB::table('tbl_category_product')->where('category_id', $category_id)->get();
        return view('admin_product.category.edit_category_product')->with('category_product', $category_product);
    }
    public function all_category_product()
    {
        $all_category_product = DB::table('tbl_category_product')
            ->select('category_id', 'category_name', 'category_status')
            ->get();
        return view('admin_product.category.all_category_product')->with('all_category_product', $all_category_product);
    }
    public function save_category_product(Request $request)
    {
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $kt = DB::table('tbl_category_product')->where('category_name', $request->category_product_name)->get();
        if ($kt->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Tên danh mục đã tồn tại',
                'data' => $data
            ]);
        } else {
            $data['category_desc'] = $request->category_product_desc;
            $data['category_status'] = $request->category_product_status;
            $result = DB::table('tbl_category_product')->insert($data);
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
    public function active_category_product($category_product_id)
    {
        $result = DB::table('tbl_category_product')
            ->where('category_id', $category_product_id)
            ->update(['category_status' => 0]);
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
    public function update_category_product(Request $request)
    {
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['category_desc'] = $request->category_product_desc;
        $data['category_status'] = $request->category_product_status;
        $result = DB::table('tbl_category_product')->where('category_id', $request->category_product_id)->update($data);
        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Cập nhật thành công",
                "name" => $data['category_name']
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Không có gì thay đổi"
            ]);
        }
    }
    public function delete_category_product($category_product_id)
    {
        $product_of_category = DB::table('products')
            ->where('products.category_id', $category_product_id)
            ->get()
            ->count();
        if ($product_of_category > 0) {
            return response()->json([
                "status" => false,
                "message" => "Còn sản phẩm trong danh mục này",
                "title" => "Không thể xóa",
                "type" => "warn"
            ]);
        } else {
            $brand_of_category = DB::table('brands')
                ->where('category_id', $category_product_id)
                ->get()
                ->count();
            if ($brand_of_category > 0) {
                return response()->json([
                    "status" => "have_brand",
                    "message" => "Còn thương hiệu trong danh mục này",
                    "title" => "Không thể xóa",
                    "type" => "warn"
                ]);
            } else {
                $result = DB::table('tbl_category_product')->where('category_id', $category_product_id)->delete();
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
    }
    public function unactive_category_product($category_product_id)
    {
        $result = DB::table('tbl_category_product')
            ->where('category_id', $category_product_id)
            ->update(['category_status' => 1]);
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
}
