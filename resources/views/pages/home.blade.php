@extends('_Layout')
@section('content')
    <!--category-productsr-->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2>Danh mục sản phẩm</h2>
                        <div class="panel-group category-products" id="accordian">
                            <!--category-productsr-->
                            @foreach ($category as $cate)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordian"
                                                href="#{{ $cate->category_id }}">
                                                <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                                {{ $cate->category_name }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="{{ $cate->category_id }}" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul>
                                                @foreach ($brand as $bra)
                                                    @if ($bra->category_id == $cate->category_id)
                                                        <li><a href="" class="brand"
                                                                data-id={{ $bra->brand_id }}>{{ $bra->brand_name }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!--/category-products-->
                    </div>
                </div>
                <div class="col-sm-9 padding-right">
                    <div class="features_items"><!--features_items-->
                        
                    </div><!--features_items-->
                </div>
            </div>
            
    </section>
    <script>
        function addtocart(product_id,quantity = 1){
            //kiểm tra có session chưa
            let user = document.querySelector(`#menu`);
            let quantity_detail = document.querySelector(`#quantity`);
            if(quantity_detail){
                quantity = quantity_detail.value;
            }
            if (user) {
                let user_id = user.getAttribute('data-id');
                $.ajax({
                    url: 'api/add-to-cart/' + user_id + '/' + product_id + '/' + quantity,
                    type: 'GET',
                    dataType: 'json'
                }).done(function(res) {
                    if (res.status) {
                        toast({
                            title: "Thông báo",
                            message: res.message,
                            type: res.type
                        });
                    }
                })
            } else {
                window.location.href = "{{ route('login') }}";
            }
        }

        function detailproduct(id) {
            $.ajax({
                    url: '/api/detail-product/' + id,
                    type: 'GET',
                    dataType: 'json'
                })
                .done(function(res) {
                    if (res.status == true) {
                        $.each(res.data, function(index, val) {
                            let html = '';
                            // chú ý đổi đường dẫn hình ảnh khi úp lên host
                            html += `
                            <div class="product-details"><!--product-details-->
                                <div class="col-sm-5">
                                    <div class="view-product">
                                        <img src="${val.product_image}" alt="" />
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="product-information"><!--/product-information-->
                                        <h2>${val.product_name}</h2>
                                        <span>
                                            <h1 style="color: #d16430">${val.product_price}đ</h1>
                                            <label>Số lượng: </label>
                                            <input type="text" id="quantity" name="quantity" value="1" />
                                            <button onclick='addtocart(${val.product_id})' type="button" class="btn btn-fefault cart">
                                                <i   class="fa fa-shopping-cart"></i>
                                                Thêm vào giỏ hàng
                                            </button>
                                        </span>
                                        <p><b>Tình trạng: </b> Còn hàng</p>
                                        <p><b>Thương hiệu: </b>${val.brand_name}</p>
                                </div>
                            </div><!--/product-details-->
                             `;
                            document.getElementsByClassName('features_items')[0].innerHTML = html;
                        })

                    } else {
                        toast({
                            title: "Thông báo",
                            message: res.message,
                            type: "warn"
                        });
                    }
                })
        }

        document.querySelectorAll(`a.brand`).forEach(function(e) {//click vào danh mục sp
            e.addEventListener('click', function(event) {
                event.preventDefault();
                let brandId = this.getAttribute('data-id');
                $.ajax({
                        url: '/api/show-brand-product/' + brandId,
                        type: 'GET',
                        dataType: 'json'
                    })
                    .done(function(res) {
                        if (res.status == true) {
                            let html = '';
                            $.each(res.data, function(index, val) {
                                // chú ý đổi đường dẫn hình ảnh khi úp lên host
                                html += `
                                <div class="col-sm-4">
                                    <div class="product-image-wrapper">
                                        <div class="single-products">
                                            <div class="productinfo text-center">
                                                <a onclick='detailproduct(${val.product_id})' class="detail" data-id="${val.product_id}">
                                                    <img src="${val.product_image}" alt="" />
                                                    <h2>${val.product_price}<span style="color: #9fa8c9">đ</span></h2>
                                                    <p>${val.product_name}</p>
                                                </a>
                                                <a onclick='addtocart(${val.product_id})' class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ
                                                    hàng</a>
                                            </div>
                                        </div>
                                        <div class="choose">
                                            <ul class="nav nav-pills nav-justified">
                                                <li><a href="#"><i class="fa fa-plus-square"></i>Yêu thích</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                             `;
                            });
                            document.getElementsByClassName('features_items')[0].innerHTML = html;
                        } else {
                            toast({
                                title: "Thông báo",
                                message: res.message,
                                type: "warn"
                            });
                        }
                    })
            })
        });
    </script>
@endsection
