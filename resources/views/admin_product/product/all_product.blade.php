@extends('admin_Layout')
@section('admin_content')
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
                                <th style="width: 20%;">Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Hình ảnh</th>
                                <th>Danh mục</th>
                                <th>Thương hiệu</th>
                                <th style="text-align: center">Hiển thị</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_product as $product)
                                <tr class="odd">
                                    <td class="col1">{{ $product->product_name }}</td>
                                    <td class="col2">{{ number_format($product->product_price) }}đ</td>
                                    <td class="col3">
                                        <img src="{{ asset('storage/app/' . $product->product_image) }}" alt=""
                                            height="100px" width="150px">
                                    </td>
                                    <td class="col4">{{ $product->category_name }}</td>
                                    <td class="col5">{{ $product->brand_name }}</td>
                                    <td class="col6">
                                        <a class="eye" data-id={{ $product->product_id }}
                                            style="display: block; text-align: center; font-size: 18px;">
                                            <i style="cursor: pointer; "
                                                class="fas {{ $product->product_status ? 'fa-eye text-success' : 'fa-eye-slash text-danger' }}"></i>
                                        </a>
                                    </td>
                                    <td style="text-align: right;border: none;">
                                        <a href="{{ URL::to('/edit-product/' . $product->product_id) }}"
                                            class="btn btn-success" type="button">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a class="delete btn btn-danger" type="button">
                                            <i class="far fa-window-close"></i>
                                        </a>

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

            });

        });
        document.querySelectorAll(`#dataTable > tbody > tr > td > a.delete`).forEach(function(e) {
            e.addEventListener('click', function(event) {
                event.preventDefault();
                let productId = this.parentElement.parentElement.querySelector(`.col6 > a`)
                    .getAttribute('data-id');
                let productname = this.parentElement.parentElement.querySelector(`.col1`)
                    .innerText;
                let del = this.parentElement.parentElement;
                if (confirm('Bạn có chắc chắn muốn xóa danh mục này không?')) {
                    $.ajax({
                            url: '/api/delete-product/' + productId,
                            type: 'GET',
                            dataType: 'json'
                        })
                        .done(function(res) {
                            if (res.status) {
                                $table.row(del).remove().draw(false);
                                toast({
                                    title: "Thành công",
                                    message: `${res.message} <b>${productname}</b>`,
                                });
                            } else {
                                toast({
                                    title: "Lỗi",
                                    message: res.message,
                                    type: "error"
                                });

                            }
                        })
                        .catch(function(err) {
                            toast({
                                title: "Lỗi",
                                message: "Lỗi không thể gửi yêu cầu",
                                type: "error"
                            });
                        });
                }
            });
        });
        document.querySelectorAll(`#dataTable > tbody > tr > td > a.eye`).forEach(function(el) {
            el.addEventListener('click', function(event) {
                event.preventDefault();
                let productId = this.getAttribute('data-id');
                let icon = this.querySelector('i');
                let status = icon.classList.contains('fa-eye');
                let productName = this.parentElement.parentElement.querySelector('.col1')
                    .innerText;
                if (status) {
                    // call API
                    $.ajax({
                            url: '/api/active-product/' + productId,
                            type: 'GET',
                            dataType: 'json'
                        })
                        .done(function(res) {
                            if (res.status) {
                                icon.classList.remove('fa-eye');
                                icon.classList.remove('text-success');
                                icon.classList.add('fa-eye-slash');
                                icon.classList.add('text-danger');
                                toast({
                                    title: "Thành công",
                                    message: `${res.message} <b>${productName}</b>`
                                });
                            } else {
                                toast({
                                    title: "Thất bại",
                                    message: res.message,
                                    type: "error"
                                });
                            }
                        })
                        .catch(function(err) {
                            toast({
                                title: "Lỗi",
                                message: "Lỗi không thể gửi yêu cầu",
                                type: "error"
                            });
                        });
                } else {
                    $.ajax({
                            url: '/api/unactive-product/' + productId,
                            type: 'GET',
                            dataType: 'json'
                        })
                        .done(function(res) {
                            if (res.status) {
                                icon.classList.remove('fa-eye-slash');
                                icon.classList.remove('text-danger');
                                icon.classList.add('fa-eye');
                                icon.classList.add('text-success');
                                icon.style.color = 'limegreen';
                                toast({
                                    title: "Thành công",
                                    message: `${res.message} <b>${productName}</b>`
                                });
                            } else {
                                toast({
                                    title: "Thất bại",
                                    message: res.message,
                                    type: "error"
                                });
                            }
                        })
                        .catch(function(err) {
                            toast({
                                title: "Lỗi",
                                message: "Lỗi không thể gửi yêu cầu",
                                type: "error"
                            });
                        });
                }
            })
        });
    </script>
@endsection
