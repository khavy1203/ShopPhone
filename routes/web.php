<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Category;
use App\Http\Controllers\Brand;
use App\Http\Controllers\Product;
use App\Http\Controllers\User;
use App\Http\Controllers\Cart;
use App\Http\Controllers\Order;
use App\Http\Controllers\SendEmail;
use App\Http\Controllers\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//frontend
Route::get('/',[HomeController::class,'index'] )->name('home');
Route::get('/trang-chu',[HomeController::class,'index']);

Route::get('/brand-product',[Brand::class,'show_brand_product/{brand_id}']);//hiển thị sản phẩm theo thương hiệu

Route::get('/user',[User::class,'index'])->name('login');//hiên thị trang user login
Route::post('/user-home',[User::class,'home'] );// kiểm tra tính hợp lệ của dữ liệu rồi mới hiển thị trang /home
Route::get('/user-logout',[User::class,'logout'] );//đăng xuất

Route::get('/cart',[Cart::class,'show_cart']);//hiên thị cart


Route::get('/order',[Order::class,'show_order']);//hiên thị cart
Route::post('/payment',[Order::class,'add_order']);//thanh toán

//backend
Route::get('/admin',[AdminController::class,'index'] );//hiên login thị trang admin login
Route::post('/admin-dashboard',[AdminController::class,'dashboard'] );// kiểm tra tính hợp lệ của dữ liệu rồi mới hiển thị trang /dashboard
Route::get('/dashboard',[AdminController::class,'show_dashboard'] );//hiển thị trang dashboard
Route::get('/logout',[AdminController::class,'logout'] );

Route::get('/add-category-product',[Category::class,'add_category_product'] );
Route::get('/all-category-product',[Category::class,'all_category_product'] );
Route::get('/edit-category-product/{category_product_id}',[Category::class,'edit_category_product'] );

Route::get('/add-brand-product',[Brand::class,'add_brand_product'] );
Route::get('/all-brand-product',[Brand::class,'all_brand_product'] );
Route::get('/edit-brand-product/{brand_product_id}',[Brand::class,'edit_brand_product'] );

Route::get('/add-product',[Product::class,'add_product'] );
Route::get('/all-product',[Product::class,'all_product'] );
Route::get('/edit-product/{product_id}',[Product::class,'edit_product'] );

Route::get('/order-admin',[Order::class,'show_order_admin'] );
// Google URL
Route::prefix('google')->name('google.')->group( function(){
    Route::get('login', [User::class, 'loginWithGoogle'])->name('login');
    Route::any('callback', [User::class, 'callbackFromGoogle'])->name('callback');
});


//sendmail
Route::get('/mail/{order_id}',[SendEmail::class,'send_mail'] );
//login google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);