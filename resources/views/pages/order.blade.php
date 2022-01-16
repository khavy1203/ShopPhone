@extends('_Layout')
@section('content')
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Trang chủ</a></li>
                <li class="active">Đơn hàng</li>
            </ol>
        </div>
        <div class="table-responsive cart_info">
            <table class="table table-condensed" id="dataTable">
                @if ($orders->count() > 0)
                    <thead>
                        <tr class="cart_menu">
                            <th class="image">Mã đơn hàng</th>
                            <th class="quantity">Số lượng</th>
                            <td class="total">Tổng tiền</td>
                            <th>Tình trạng</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="cart_product">
                                    <p>{{ $order->order_id }}</p>
                                </td>
                                <td class="cart_description">
                                    <p>{{ $order->quantity_total }}</p>
                                </td>
                                <td class="cart_price">
                                    <p>{{ number_format($order->sub_total) }} VNĐ</p>
                                </td>
                                <td class="cart_quantity">
                                    @if ($order->order_status == 0)
                                        <div class="btn btn-warning">
                                            Chờ xử lý . . .
                                        </div>
                                    @elseif($order->order_status == 1)
                                        <div class="btn btn-info">
                                            Xác nhận
                                        </div>
                                    @elseif($order->order_status == 2)
                                        <div class="btn btn-info">
                                            Đang giao hàng
                                        </div>
                                    @elseif($order->order_status == 3)
                                        <div class="btn btn-success">
                                            Đã giao hàng
                                        </div>
                                    @elseif($order->order_status == 4)
                                        <div class="btn btn-danger">
                                            Đã hủy
                                        </div>
                                    @endif
                                </td>
                                @if ($order->order_status == 0)
                                    <td>
                                        <button type="button" class="btn btn-danger cancel"
                                            data-id="{{ $order->order_id }}">Hủy đơn</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <h3 style="text-align: center">Đơn hàng của bạn đang trống ^-^</h3>
                @endif
            </table>
        </div>
    </div>
    <script>
        document.querySelectorAll('.cancel').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let td_parent = this.parentElement;
                let sibling_td = td_parent.previousElementSibling;
                if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                    td_parent.remove();
                    sibling_td.innerHTML = `<div class="btn btn-danger">
                                                    Đã hủy
                                                 </div>`;
                    $.ajax({
                        url: 'api/cancel/' + this.getAttribute('data-id'),
                        type: 'GET',
                        dataType: 'json',
                    }).done(function(res) {
                        if (res.status) {
                            toast({
                                type: 'success',
                                message: res.message,
                                title: 'Thông báo'
                            })

                        } else {
                            toast({
                                type: 'error',
                                message: res.message,
                                title: 'Thông báo'
                            })
                            //reload page
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    });
                }
            });
        });
    </script>
@endsection
