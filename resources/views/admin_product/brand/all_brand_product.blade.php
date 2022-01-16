@extends('admin_Layout')
@section('admin_content')
    <section class="panel">
        <div class="card-header py-3 row" style="justify-content: space-between">
            <h6 class="m-0 font-weight-bold text-primary" style="min-height: 40px">Thương hiệu</h6>
            <a class="collapse-item" href="{{ URL::to('/add-brand-product') }}"><i style="font-size: 25px"
                    class="fas fa-plus"></i></a>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table dataTable" id="dataTable" width="100%" cellspacing="0" role="grid"
                        aria-describedby="dataTable_info">
                        <thead>
                            <tr role="row">
                                <th>Thương hiệu</th>
                                <th>Danh mục</th>
                                <th style="text-align: center">Hiển thị</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_brand_product as $brand_product)
                                <tr id="lst-header">
                                    <td style="text-align: left">{{ $brand_product->brand_name }}</td>
                                    <td>{{$brand_product->category_name}}</td>
                                    <td style="text-align: left">
                                        <a class="eye" data-id={{ $brand_product->brand_id }}
                                            style="display: block; text-align: center; font-size: 18px;">
                                            <i style="cursor: pointer; "
                                                class="fas {{ $brand_product->brand_status ? 'fa-eye text-success' : 'fa-eye-slash text-danger' }}"></i>
                                        </a>
                                    </td>
                                    <td style="text-align: right;border: none;">
                                        <a href="{{ URL::to('/edit-brand-product/' . $brand_product->brand_id) }}"
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
    <!-- Page Heading -->
    <!-- DataTales Example -->


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
                let brandId = this.parentElement.parentElement.querySelector(`td:nth-child(3) > a`)
                    .getAttribute('data-id');
                let brandname = this.parentElement.parentElement.querySelector(`td:nth-child(1)`).innerText;
                let del = this.parentElement.parentElement;
                if (confirm('Bạn có chắc chắn muốn xóa danh mục này không?')) {
                    $.ajax({
                            url: '/api/delete-brand-product/' + brandId,
                            type: 'GET',
                            dataType: 'json'
                        })
                        .done(function(res) {
                            if (res.status) {
                                $table.row(del).remove().draw(false);
                                toast({
                                    title: "Thành công",
                                    message: `${res.message} <b>${brandname}</b>`,
                                });
                            } else {
                                toast({
                                    title: res.title,
                                    message: res.message,
                                    type: res.type,

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
                let brandId = this.getAttribute('data-id');
                let icon = this.querySelector('i');
                let status = icon.classList.contains('fa-eye');
                let brandName = this.parentElement.parentElement.querySelector('td:nth-child(1)')
                    .innerText;

                if (status) {
                    // call API
                    $.ajax({
                            url: '/api/active-brand-product/' + brandId,
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
                                    message: `${res.message} <b>${brandName}</b>`
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
                            url: '/api/unactive-brand-product/' + brandId,
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
                                    message: `${res.message} <b>${brandName}</b>`
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
