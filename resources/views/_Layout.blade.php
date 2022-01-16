<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | Khavy-shopper</title>
    <link href="{{ asset('public/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/frontend/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/frontend/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('public/frontend/css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('public/frontend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('public/frontend/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('public/backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('public/backend/css/dtlaravel.css') }}" rel="stylesheet">
</head>
<!--/head-->

<body>

    <header id="header">
        <!--header-->
        <div class="header_top">
            <!--header_top-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">
                            <ul class="nav nav-pills">
                                <li><a href="#"><i class="fa fa-phone"></i> +84 98 79 80 417</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i> Khavy1203@gmail.com.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header_top-->

        <div class="header-middle">
            <!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="index.html"><img src="{{ '' }}" alt="" />KhaVy_Logo</a>
                        </div>

                    </div>

                    <div class="col-sm-8">
                        <div class="main-menu pull-right">
                            <ul class="nav navbar-nav navbar-collapse">
                                <li><a href="#"><i class="fa fa-user"></i> Tài khoản</a></li>
                                {{-- <li><a href="#"><i class="fa fa-star">Yêu thích</i> </a></li> --}}
                                <li><a id="order"><i class="fa fa-crosshairs"></i> Đơn hàng</a></li>
                                @if (session()->has('user_id'))

                                    <li><a id="cart" class="fa fa-shopping-cart submit_cart cart_user">
                                            Giỏ hàng
                                        </a></li>
                                    <li class="dropdown"> <a href="#">{{ session()->get('user_name') }} <i
                                                class="fas fa-user-tie"></i></a>
                                        <ul id="menu" data-id="{{ session()->get('user_id') }}"
                                            class="sub-menu">
                                            <li><a href="#">Tài khoản</a></li>
                                            <li><a href="#">Giỏ hàng</a></li>
                                            <li><a href="{{ URL::to('/user-logout') }}">Đăng xuất</a></li>
                                        </ul>
                                    </li>
                                @else
                                    <li><a href="{{ URL::to('/user') }}"><i class="fa fa-shopping-cart"></i> Giỏ
                                            hàng</a></li>
                                    <li class="dropdown"> <a href="{{ URL::to('/user') }}"> <i
                                                class="fas fa-user-lock"></i> Đăng nhập</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header-middle-->

        <div class="header-bottom">
            <!--header-bottom-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav navbar-collapse">
                                <li><a href="{{ URL::to('/trang-chu') }}" class="active">Trang chủ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="search_box pull-right">
                            <input type="text" placeholder="Search" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/header-bottom-->
    </header>
    <!--/header-->

    <section id="slider">
        <!--slider-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#slider-carousel" data-slide-to="1"></li>
                            <li data-target="#slider-carousel" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="col-sm-6">
                                    <h1><span>Khavy</span>-SHOP</h1>
                                    <h2>Iphone 12</h2>
                                    <p>Hiệu năng vượt xa mọi giới hạn
                                        Apple đã trang bị con chip mới nhất của hãng (tính đến 11/2020) cho iPhone
                                        12 đó
                                        là A14 Bionic, được sản xuất trên tiến trình 5 nm với hiệu suất ổn định hơn
                                        so
                                        với chip A13 được trang bị trên phiên bản tiền nhiệm iPhone 11.</p>
                                    <button type="button" class="btn btn-default get">Xem chi tiết</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{ 'public/frontend/images/iphone.jpeg' }}" class="girl img-responsive"
                                        alt="" />
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span>Khavy</span>-SHOP</h1>
                                    <h2>OPPO Find X3 Pro 5G</h2>
                                    <p>OPPO đã làm khuynh đảo thị trường smartphone khi cho ra đời chiếc điện thoại
                                        OPPO
                                        Find X3 Pro 5G. Đây là một sản phẩm có thiết kế độc đáo, sở hữu cụm camera
                                        khủng, cấu hình thuộc top đầu trong thế giới Android.</p>
                                    <button type="button" class="btn btn-default get">Xem chi tiết</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{ 'public/frontend/images/oppo.png' }}" class="girl img-responsive"
                                        alt="" />
                                </div>
                            </div>

                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span>Khavy</span>-SHOP</h1>
                                    <h2>Samsung Galaxy Z Fold3 5G 512GB </h2>
                                    <p>Galaxy Z Fold3 5G đánh dấu bước tiến mới của Samsung trong phân khúc điện
                                        thoại
                                        gập cao cấp khi được cải tiến về độ bền cùng những nâng cấp đáng giá về cấu
                                        hình
                                        hiệu năng, hứa hẹn sẽ mang đến trải nghiệm sử dụng đột phá cho người dùng.
                                    </p>
                                    <button type="button" class="btn btn-default get">Xem chi tiết</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{ 'public/frontend/images/samsung.jpeg' }}"
                                        class="girl img-responsive" alt="" />
                                </div>
                            </div>

                        </div>

                        <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--/slider-->

    <section id="cart_items">
        @yield('content')
    </section>

    <footer id="footer">
        <!--Footer-->
        FOOTER
    </footer>
    <div id="toast"></div>
    <!--/Footer-->
    <script src="{{ asset('public/frontend/js/jquery.js') }}"></script>
    <script src="{{ asset('public/frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/frontend/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('public/frontend/js/price-range.js') }}"></script>
    <script src="{{ asset('public/frontend/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('public/frontend/js/main.js') }}"></script>
    <script src="{{ asset('public/backend/js/dtlaravel.js') }}"></script>

    <script>
        document.getElementById('cart').addEventListener('click', function(e) {
            e.preventDefault();
            //thay đổi url /cart
            window.location.href = "/cart";
            document.title="Giỏ hàng";
           
        });
        document.getElementById('order').addEventListener('click', function(e) {
            e.preventDefault();
            //thay đổi url /cart
            window.location.href = "/order";
        });
    </script>
</body>

</html>
