@extends('admin_Layout')
@section('admin_content')
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Cập nhật danh mục sản phẩm
            </header>
            <div class="panel-body" style="margin-top: 20px">
                <div class="position-center">
                    <form id="form-update-brand">
                        @foreach ($brand_product as $key)
                            <div class="form-group" style="display: none">
                                <label for="brand-product-name">id</label>
                                <input type="text" class="form-control" name="brand_product_id"
                                    value="{{ $key->brand_id }}" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="brand-product-name">Tên thương hiệu</label>
                                <input type="text" required=true class="form-control" name="brand_product_name"
                                    value="{{ $key->brand_name }}" placeholder="Tên danh mục">
                            </div>
                            <div class="form-group">
                                <label for="brand-product-desc">Mô tả thương hiệu</label>
                                <textarea style="resize: none;" rows="5" class="form-control" name="brand_product_desc"
                                    placeholder="Mô tả danh mục">{{ $key->brand_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="select-item">Danh mục sản phẩm</label>
                                <select name="category_id" class="form-control m-bot15" id="select-item">
                                    @foreach ($category as $key_cate => $cate)
                                        @if ($cate->category_id == $key->category_id)
                                            <option value="{{ $cate->category_id }}" selected>{{ $cate->category_name }}</option>
                                        @else
                                            <option value="{{ $cate->category_id }}">{{ $cate->category_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="display: none">
                                <label for="select-item">Ẩn hiện</label>
                                <select name="brand_product_status" class="form-control m-bot15" id="select-item">
                                    <option value="1">Hiện</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-info">Cập nhật thương hiệu sản phẩm</button>
                        <a class="collapse-item" href="{{ URL::to('/all-brand-product') }}">Trở về danh sách thương hiệu</a>
                    </form>
                </div>

            </div>
        </section>
    </div>
    <script>
        var x, i
        x = document.querySelectorAll("#select-item > option");
        for (i = 0; i < x.length; i++) {
            if (x[i].value == "{{ $key->brand_status }}") {
                x[i].selected = true;
            }
        }
        let form = document.getElementById('form-update-brand');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                    url: '/api/update-brand-product',
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
        })
    </script>
@endsection
