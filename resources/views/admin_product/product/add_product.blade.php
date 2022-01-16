@extends('admin_Layout')
@section('admin_content')
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Thêm sản phẩm
            </header>
            <div class="panel-body" style="margin-top: 20px">
                <div class="position-center">
                    <form id="form-add-product" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="category-product-name">Tên sản phẩm</label>
                            <input type="text" required=true class="form-control" name="product_name"
                                placeholder="Tên danh mục">
                        </div>
                        <div class="form-group">
                            <label for="">Giá sản phẩm</label>
                            <input type="text" required=true class="form-control" name="product_price"
                                placeholder="Tên danh mục">
                        </div>
                        <div class="form-group">
                            <label for="">Hình ảnh sản phẩm</label>
                            <input type="file" required=true class="form-control" id="product_image" name="product_image">
                        </div>
                        <div class="form-group">
                            <label for="">Mô tả sản phẩm</label>
                            <textarea style="resize: none;" rows="5" class="form-control " required=true
                                name="product_desc" placeholder="Mô tả danh mục"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Nội dung sản phẩm</label>
                            <textarea style="resize: none;" rows="5" class="form-control " required=true
                                name="product_content" placeholder="Mô tả danh mục"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="select-item">Danh mục sản phẩm</label>
                            <select name="cate" class="form-control m-bot15" id="select-item-product" onchange="category_change(this);">
                                @foreach ($category as $key => $cate)
                                    <option value="{{ $cate->category_id }}">{{ $cate->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select-item">Thương hiệu</label>
                            <select name="brand" class="form-control m-bot15" id="select-item-brand">
                                    @foreach ($brand as $key => $brand)
                                        @if ($brand->category_id == $last_category->category_id)
                                            <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                        @endif
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="select-item">Ẩn hiện</label>
                            <select name="product_status" class="form-control m-bot15" id="select-item">
                                <option value="0">ẩn</option>
                                <option selected value="1">hiện</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info">Thêm sản phẩm</button>
                        <a class="collapse-item" href="{{ URL::to('/all-product') }}">Trở về</a>
                    </form>
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

        let form = document.getElementById('form-add-product');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            var file_data = $('#product_image').prop('files')[0];
            var type = file_data.type;
            var match = ["image/gif", "image/png", "image/jpg", "image/jpeg"];
            //formData.append('product_image', file_data);
            if (type == match[0] || type == match[1] || type == match[2] || type == match[3]) {
                $.ajax({
                        url: '/api/save-product',
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
            } else {
                toast({
                    title: "Cảnh báo",
                    message: "File không đúng định dạng",
                    type: "error"
                });
            }

        })
    </script>
@endsection
