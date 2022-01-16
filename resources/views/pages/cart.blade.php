{{-- cart không sử dụng layout mà khi gọi đến thì chèn luôn html vào --}}
@extends('_Layout')
@section('content')
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Trang chủ</a></li>
                <li class="active">Giỏ hàng</li>
            </ol>
        </div>
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                @if ($listcartItem && $sumPrice && $productTotal)
                    <thead>
                        <tr class="cart_menu">
                            <td class="image">Hình ảnh</td>
                            <td class="description">Tên sản phẩm</td>
                            <td class="price">Giá</td>
                            <td class="quantity">Số lượng</td>
                            <td class="total">Thành tiền</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($listcartItem as $item)
                            <tr data-id={{ $item->cart_item_id }}>
                                <td class="cart_product">
                                    <a><img src="{{ $item->product_image }}" alt="" width="150px" height="100px"></a>
                                </td>
                                <td class="cart_description">
                                    <h4><a>{{ $item->product_name }}</a></h4>
                                </td>
                                <td class="cart_price">
                                    <p>{{ number_format($item->product_price) }} đ</p>
                                </td>
                                <td class="cart_quantity">
                                    <div class="cart_quantity_button">
                                        <a class="cart_quantity_up"> + </a>
                                        <input class="cart_quantity_input" items-id="{{ $item->cart_item_id }}"
                                            data-id="{{ $item->cart_item_id }}" data-price="{{ $item->product_price }}"
                                            type="text" name="quantity" value="{{ $item->quantity }}" autocomplete="off"
                                            size="2">
                                        <a class="cart_quantity_down"> - </a>
                                    </div>
                                </td>
                                <td class="cart_total">
                                    <p class="cart_total_price">
                                        {{ number_format($item->product_price * $item->quantity) }} đ</p>
                                </td>
                                <td class="cart_delete">
                                    <a class="cart_quantity_delete"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4">&nbsp;</td>
                            <td colspan="2">
                                <table class="table table-condensed total-result">
                                    <tbody>
                                        <tr>
                                            <td>Tổng sản phẩm</td>
                                            <td class="productTotal">{{ $productTotal }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tổng tiền</td>
                                            <td><span class="sum"> {{ number_format($sumPrice) }}
                                                    VNĐ</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><a class="payment"
                                                    style="font-size: 20px; border: 1px solid gray; padding: 4px; border-radius:4px ">Đặt
                                                    hàng</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                @else
                    <h3 style="text-align: center">Giỏ hàng của bạn đang trống ^-^</h3>
                @endif
            </table>
        </div>
    </div>

    <!--/#cart_items-->
    <script>
        let payment = document.querySelector('.payment');
        if(payment){
            payment.addEventListener('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/payment',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                },
            }).done(function(res) {
                if (res.status) {
                    let html = `<h3 style="text-align: center">Giỏ hàng của bạn đang trống ^-^</h3>`;
                    document.querySelector('.table').innerHTML = html;
                    toast({
                        type: 'success',
                        message: res.message,
                        title: 'Thông báo'
                    })
                }else{
                    toast({
                        type: 'error',
                        message: res.message,
                        title: 'Thông báo'
                    })
                }
            });
        })
        }
        document.querySelectorAll(`.cart_quantity_down`).forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                let input = this.previousElementSibling; //lấy input
                let userID = document.getElementById('menu').getAttribute('data-id');


                let cartItemID = input.getAttribute('items-id');
                let productPrice = input.getAttribute('data-price');

                let value = input.value; //lấy giá trị value hiện tỊ

                if (value <= 1) {
                    delete_cartitem(userID, cartItemID)
                } else {
                    input.value = value - 1;
                    let quantity = input.value;
                    update_cartitem(userID, cartItemID, quantity, productPrice);
                }
            })
        })
        document.querySelectorAll(`.cart_quantity_up`).forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                let input = this.nextElementSibling; //lấy input
                let userID = document.getElementById('menu').getAttribute('data-id');


                let cartItemID = input.getAttribute('items-id');
                let productPrice = input.getAttribute('data-price');

                let value = input.value; //lấy giá trị value hiện tỊ

                if (value < 0) {
                    delete_cartitem(userID, cartItemID)
                } else {
                    input.value = parseInt(value) + 1;
                    let quantity = input.value;
                    update_cartitem(userID, cartItemID, quantity, productPrice);
                }
            })
        })
        document.querySelectorAll(`.cart_quantity_delete`).forEach(function(item) {
            item.addEventListener('click', function(e) {
                let userID = document.getElementById('menu').getAttribute('data-id');
                let cartItemID = this.parentElement.parentElement.getAttribute('data-id');
                delete_cartitem(userID, cartItemID);
            })
        })

        function delete_cartitem(userID, cartItemID) {
            if (confirm('Bạn muốn xóa sản phẩm này khỏi giỏ hàng')) {
                $.ajax({
                    url: 'api/del-cart-item',
                    type: 'POST',
                    ContentType: 'application/json',
                    dataType: 'json',
                    data: {
                        cartItemID: cartItemID,
                        userID: userID
                    },

                }).done(function(res) {
                    if (res.status) {
                        let cartItem = document.querySelector(
                            `tr[data-id="${cartItemID}"]`);
                        cartItem.remove();
                        $('.sum').text(res.sum + ' VNĐ');
                        $('.productTotal').text(res.productTotal);
                        toast({
                            title: 'Thành công',
                            message: res.message,
                            type: 'success'
                        });
                        return true;
                    } else if (res.clear) {
                        document.querySelectorAll(`.submit_cart`)[0].click();
                    } else {
                        toast({
                            title: 'Thất bại',
                            message: res.message,
                            type: 'error'
                        });
                    }
                });
            }
        }

        function update_cartitem(userID, cartItemID, quantity, productPrice) {
            $.ajax({
                url: 'api/update-item-onCart/' + userID + '/' + cartItemID + '/' +
                    quantity,
                type: 'GET',
                dataType: 'json',
            }).done(function(res) {
                if (res.status) {
                    $('.sum').text(res.sum + ' VNĐ');
                    $(`tr[data-id="${cartItemID}"] .cart_total_price`)
                        .text(new Intl.NumberFormat('ja-JP').format(quantity *
                            productPrice) + ' đ');
                    $('.productTotal').text(res.productTotal);
                } else if (res.mmax) {
                    toast({
                        title: 'Thất bại',
                        message: res.message,
                        type: 'error'
                    });
                } else {
                    toast({
                        title: 'Cảnh báo',
                        message: res.message,
                        type: 'warn'
                    });
                }
            })
        }
        document.querySelectorAll(`.cart_quantity_input`).forEach(function(item) {
            item.addEventListener('change', function(event) {
                event.preventDefault();
                let userID = document.getElementById('menu').getAttribute('data-id');
                let quantity = this.value;
                let cartItemID = this.getAttribute('items-id');
                let productPrice = this.getAttribute('data-price');
                console.log(cartItemID);
                if (userID) {
                    if (quantity <= 0) {
                        delete_cartitem(userID, cartItemID);
                    } else {
                        update_cartitem(userID, cartItemID, quantity, productPrice);
                    }
                }
            });
        })
    </script>
@endsection
