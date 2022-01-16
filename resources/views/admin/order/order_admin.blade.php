@extends('admin_Layout')
@section('admin_content')
    <style>


    </style>
    <section class="panel">
        <div class="card-header py-3 row" style="justify-content: space-between">
            <h6 class="m-0 font-weight-bold text-primary" style="min-height: 40px">Danh mục</h6>
            <a class="collapse-item" href="{{ URL::to('/add-product') }}"><i style="font-size: 25px"
                    class="fas fa-plus"></i></a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table dataTable" id="dataTable" width="100%" cellspacing="0" role="grid"
                        aria-describedby="dataTable_info">
                        <thead>
                            <tr role="row">
                                <th style="width: 20%;">Mã đơn hàng</th>
                                <th>Tên khách hàng</th>
                                <th>Số lượng</th>
                                <th>Tổng tiền</th>
                                <th class="col4">Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($orders as $order)
                                <tr class="odd">
                                    <td class="col1">{{ $order->order_id }}</td>
                                    <td class="col5">{{ $order->user_name }}</td>
                                    <td class="col2">{{ $order->quantity_total }}</td>
                                    <td class="col3">
                                        {{ number_format($order->sub_total) }} VNĐ
                                    </td>
                                    <td class="col4" style="position: relative">
                                        @if ($order->order_status == 0)
                                            <a class="btn btn-warning" data-toggle="collapse"
                                                href="#waitting{{ $order->order_id }}" role="button" aria-expanded="false"
                                                aria-controls="waitting{{ $order->order_id }}">Chờ xử lý . . .</a>
                                            <ul class="list-group collapse multi-collapse"
                                                id="waitting{{ $order->order_id }}"
                                                style="position: absolute; top:80%;left:10%; z-index:10;"
                                                data-id="{{ $order->order_id }}">
                                                <li class="list-group-item confirm">Xác nhận đơn hàng</li>
                                                <li class="list-group-item transport">Đang Vận chuyển</li>

                                                <li class="list-group-item delivery">Đã giao hàng</li>
                                                <li class="list-group-item cancel">Hủy đơn</li>
                                            </ul>
                                        @elseif($order->order_status==1)
                                            <a class="btn btn-info" data-toggle="collapse"
                                                href="#waitting{{ $order->order_id }}" role="button" aria-expanded="false"
                                                aria-controls="waitting{{ $order->order_id }}">Xác nhận</a>
                                            <ul class="list-group collapse multi-collapse"
                                                id="waitting{{ $order->order_id }}"
                                                style="position: absolute; top:80%;left:10%; z-index:10;"
                                                data-id="{{ $order->order_id }}">
                                                <li class="list-group-item transport">Đang Vận chuyển</li>

                                                <li class="list-group-item delivery">Đã giao hàng</li>
                                            </ul>
                                        @elseif($order->order_status==2)
                                            <a class="btn btn-info" data-toggle="collapse"
                                                href="#waitting{{ $order->order_id }}" role="button"
                                                aria-expanded="false" aria-controls="waitting{{ $order->order_id }}">Đang
                                                vận chuyển</a>
                                            <ul class="list-group collapse multi-collapse"
                                                id="waitting{{ $order->order_id }}"
                                                style="position: absolute; top:80%;left:10%; z-index:10;"
                                                data-id="{{ $order->order_id }}">
                                                <li class="list-group-item delivery">Đã giao hàng</li>
                                            </ul>
                                        @elseif($order->order_status==3)
                                            <a class="btn btn-success" data-toggle="collapse"
                                                href="#waitting{{ $order->order_id }}" role="button"
                                                aria-expanded="false" aria-controls="waitting{{ $order->order_id }}">Đã
                                                giao hàng</a>
                                        @elseif($order->order_status==4)
                                            <a class="btn btn-dark" data-toggle="collapse"
                                                href="#waitting{{ $order->order_id }}" role="button"
                                                aria-expanded="false" aria-controls="waitting{{ $order->order_id }}">Đã
                                                hủy đơn hàng</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        </div>
    </section>

    </div>
    <!-- /.container-fluid -->
    <script>
         let $table = null;
        $(document).ready(function() {
            $table = $('#dataTable').DataTable({
                "search": "Đang sang tìm kiếm",
                "processing": true,
                "language": {
                    "search": "Tìm kiếm sản phẩm:",
                    "paginate": {
                        "first": "Trang đầu",
                        "last": "Trang cuối",
                        "next": "Trang sau",
                        "previous": "Trang trước",
                    },
                    "lengthMenu":"Hiển thị _MENU_ /1trang",
                    "processing": "Vui lòng đợi...",
                    "infoEmpty": "Không có sản phẩm nào",
                    "info": "Có _START_ đến _END_ sản phẩm trong _TOTAL_ sản phẩm",
                    "emptyTable": "Dữ liệu trống",
                    "loadingRecords": "Đang tải...",
                    "infoFiltered": "(Lọc từ _MAX_ sản phẩm)",
                    "zeroRecords": "Không tìm thấy sản phẩm nào",
                    "searchPlaceholder": "Tìm kiếm...",
                    
                },

            })
            .columns('.col4')
            .order('asc')
            .draw();

        });
        //

        document.querySelectorAll(`li.list-group-item`).forEach(function(item) {
            item.addEventListener('mouseover', function(e) {
                e.preventDefault();
                this.style.cursor = "pointer";
                this.classList.add("active");
            });
        });
        document.querySelectorAll(`li.list-group-item`).forEach(function(item) {
            item.addEventListener('mouseout', function(e) {
                e.preventDefault();
                this.classList.remove("active");
                this.style.backgroundColor = "";
            });
        });
        document.querySelectorAll(`li.cancel`).forEach(function(item) {
            item.addEventListener('mouseover', function(e) {
                e.preventDefault();
                this.classList.add("active");
                this.style.backgroundColor = "red";
            });
        });
        document.querySelectorAll(`li.list-group-item`).forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                let order_id = this.parentElement.getAttribute('data-id');
                let a = document.querySelector(`a[data-toggle="collapse"][href="#waitting${order_id}"]`);
                let li = this;
                let u = this.parentElement;
                let lst_children = u.children;
                let orderstatus = null;
                if (this.classList.contains('confirm')) { //xác nhận đơn hàng
                    for (i = 0; i < lst_children.length; i++) {
                        /* nếu  click vào xác nhận thì 2 mục này sẽ bị xóa khỏi td */
                        if (lst_children[i].classList.contains('cancel')) {
                            lst_children[i].remove();
                        }
                    }
                    a.innerHTML = "Xác nhận";
                    a.classList.remove("btn-warning"); //xóa các trạng thái cũ
                    a.classList.add("btn-info");
                    orderstatus=1;
                } else if (this.classList.contains('transport')) { //vận chuyển đơn hàng

                    for (i = 0; i < lst_children.length; i++) {
                        /* nếu  click vào xác nhận thì 2 mục này sẽ bị xóa khỏi td */
                        if (lst_children[i].classList.contains('confirm') || lst_children[i].classList
                            .contains('cancel')) {
                            lst_children[i].remove();
                        }
                    }
                    a.classList.remove("btn-warning");
                    a.classList.add("btn-info");
                    a.innerHTML = "Đang vận chuyển";
                    orderstatus=2;
                } else if (this.classList.contains('delivery')) { //đã giao hàng
                    u.innerHTML = "";
                    a.innerHTML = "Đã giao hàng";
                    a.classList.remove("btn-warning");
                    a.classList.remove("btn-info");
                    a.classList.add("btn-success");
                    orderstatus=3;
                } else if (this.classList.contains('cancel')) {
                    u.innerHTML = "";
                    a.innerHTML = "Đã hủy đơn hàng";
                    a.classList.remove("btn-warning");
                    a.classList.remove("btn-info");
                    a.classList.remove("btn-success");
                    a.classList.add("btn-dark");
                    orderstatus=4;
                }
                li.remove();
                u.classList.remove("show"); // ẩn ul
                //xóa chính nó vì thực hiện thằng cha ko thể xóa thằng con được
                $.ajax({
                    url: 'api/update-order-status',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        //csrf
                        _token: '{{ csrf_token() }}',
                        order: order_id,
                        order_status: orderstatus
                    },
                }).done(function(res) {
                    if (res.status) {
                        /*    //xóa li hiện tại
                            a.innerHTML = "Xác nhận";
                            a.classList.remove("btn-warning"); //xóa các trạng thái cũ
                            a.classList.add("btn-info");

                        } else if (res.status == 2) {

                            a.classList.remove("btn-warning");
                            a.classList.add("btn-info");
                            a.innerHTML = "Đang vận chuyển";

                        } else if (res.status == 3) {
                            a.innerHTML = "Đã giao hàng";
                            a.classList.remove("btn-warning");
                            a.classList.remove("btn-info");
                            a.classList.add("btn-success");
                        } else {
                            a.innerHTML = "Đã hủy đơn hàng";

                            a.classList.remove("btn-warning");
                            a.classList.remove("btn-info");
                            a.classList.remove("btn-success");
                            a.classList.add("btn-dark");

                        } */

                        toast({
                            type: 'success',
                            message: res.message,
                            title: 'Thành công'
                        })
                    }else{
                        toast({
                            type: 'error',
                            message: res.message,
                            title: 'Thất bại'
                        })
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
