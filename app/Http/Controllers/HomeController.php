<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(){
        $category_product = DB::table('tbl_category_product')->where('category_status','1')->orderBy('category_id','desc')
        ->get();
        $brand_product = DB::table('brands')
        ->join('tbl_category_product','brands.category_id','=','tbl_category_product.category_id')
        ->where('tbl_category_product.category_status','1')//lấy các danh mục sản phẩm có trạng thái là 1
        ->where('brands.brand_status','1')// lấy các thương hiệu có trạng thái là 1
        ->orderBy('brands.brand_id','desc')
        ->get();
        ;
        $all_product = DB::table('products')
            ->join('brands', 'brands.brand_id', '=', 'products.brand_id')
            ->join('tbl_category_product', 'tbl_category_product.category_id', '=', 'products.category_id')
            ->where('tbl_category_product.category_status', '=',1)//lay ra tat ca san pham co trang thai la 1
            ->where('brands.brand_status','=', 1)//lấy ra các thương hiệu có trạng thái bằng 1
            ->where('products.product_status','=', 1)//lấy ra các sản phẩm có trạng thái bằng 1
            ->get();//phù hợp 2 điều kiện trên thì hiển thị, không thì không hiển thị
        return view('pages.home')->with('all_product', $all_product)->with('category',$category_product)->with('brand',$brand_product);
    }

    
}