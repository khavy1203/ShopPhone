@extends('admin_Layout')
@section('admin_content')
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Chỉnh sửa sản phẩm
            </header>
            <div class="panel-body" style="margin-top: 20px">
                <div class="position-center">
                    @foreach ($edit_product as $pro)
                        <form id="form-update-product" enctype="multipart/form-data">
                            <div class="form-group" style="display: none">
                                <label>id</label>
                                <input type="text" class="form-control" name="product_id" value="{{ $pro->product_id }}">
                            </div>
                            <div class="form-group">
                                <label>Tên sản phẩm</label>
                                <input type="text" required=true class="form-control" name="product_name"
                                    value="{{ $pro->product_name }}">
                            </div>
                            <div class="form-group">
                                <label for="">Giá sản phẩm</label>
                                <input type="text" required=true class="form-control" name="product_price"
                                    value="{{ $pro->product_price }}">
                            </div>
                            <div class="form-group">
                                <label for="">Hình ảnh sản phẩm</label>
                                <input type="file" class="form-control" id="product_image" name="product_image">
                                <img src="{{ asset('storage/app/' . $pro->product_image) }}" width="100px" height="100px">
                            </div>
                            <div class="form-group">
                                <label for="">Mô tả sản phẩm</label>
                                <textarea style="resize: none;" rows="5" class="form-control " required=true
                                    name="product_desc">{{ $pro->product_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Nội dung sản phẩm</label>
                                <textarea style="resize: none;" rows="5" class="form-control " required=true
                                    name="product_content">{{ $pro->product_content }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="select-item">Danh mục sản phẩm</label>
                                <select name="cate" onchange="category_change(this)" class="form-control m-bot15" id="select-item-cate">
                                    @foreach ($category as $key => $cate)
                                        @if ($cate->category_id == $pro->category_id)
                                            <option selected value="{{ $cate->category_id }}">{{ $cate->category_name }}
                                            </option>
                                        @else
                                            <option value="{{ $cate->category_id }}">{{ $cate->category_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select-item">Thương hiệu</label>
                                <select name="brand" class="form-control m-bot15" id="select-item-brand">
                                    @foreach ($brand as $key => $brand){{-- lấy hết các thương hiệu --}}
                                        @if ($brand->category_id == $pro->category_id){{-- xem thương hiệu có danh mục trùng với danh mục của sản phẩm thì lấy hết --}}
                                            @if ($brand->brand_id == $pro->brand_id){{-- xem thương hiệu có id bằng id trên thương hiệu của sản phẩm thì select --}}
                                                <option selected value="{{ $brand->brand_id }}">
                                                    {{ $brand->brand_name }}
                                                </option>
                                            @else
                                                <option value="{{ $brand->brand_id }}"> {{-- không có thì không select --}}
                                                    {{ $brand->brand_name }}
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="select-item">Ẩn hiện</label>
                                <select name="product_status" class="form-control m-bot15" id="select-item">
                                    @if ($pro->product_status == 0)
                                        <option selected value="0">Ẩn</option>
                                        <option value="1">Hiện</option>
                                    @else
                                        <option selected value="1">Hiện</option>
                                        <option value="0">Ẩn</option>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Cập nhật sản phẩm</button>
                            <a class="collapse-item" href="{{ URL::to('/all-product') }}">Trở về</a>
                        </form>
                    @endforeach
                </div>

            </div>
        </section>

    </div>
    <script>
        function category_change(e) {// thay đổi danh mục seclect
            var category_id = e.value;
            $.ajax({
                    url: '/api/get-brand-by-category/' + category_id,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res) {
                    if (res.status) {
                        var html = '';
                        $.each(res.data, function(index, val) {
                            html += '<option value="' + val.brand_id + '">' + val.brand_name + '</option>';
                        });
                        $('#select-item-brand').html(html);
                    } else {
                        toast({
                            'message': res.message,
                            'title': 'Thất bại',
                            'type': 'error'
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

        //form update product
        let form = document.getElementById('form-update-product');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            var file_data = $('#product_image').prop('files')[0];
            if (file_data) {
                var type = file_data.type;
                var match = ["image/gif", "image/png", "image/jpg", "image/jpeg"];
                //formData.append('product_image', file_data);
                if (type == match[0] || type == match[1] || type == match[2] || type == match[3]) {
                    $.ajax({
                            url: '/api/update-product',
                            type: 'POST',
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            data: formData,
                        })
                        .done(function(res) {
                            if (res.status) {
                                toast({
                                    'message': `${res.message} <b>${res.name}</b>`,
                                    'title': 'Thành công'
                                })
                            } else {
                                toast({
                                    'message': res.message,
                                    'title': 'Cảnh báo',
                                    'type': 'warn'
                                })
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
                    toast({
                        title: "Lỗi",
                        message: "File không đúng định dạng",
                        type: "error"
                    });
                }
            } else {
                $.ajax({
                        url: '/api/update-product',
                        type: 'POST',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        data: formData,
                    })
                    .done(function(res) {
                        if (res.status) {
                            toast({
                                'message': `${res.message} <b>${res.name}</b>`,
                                'title': 'Thành công'
                            })
                        } else {
                            toast({
                                'message': res.message,
                                'title': 'Cảnh báo',
                                'type': 'warn'
                            })
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
    </script>
@endsection
