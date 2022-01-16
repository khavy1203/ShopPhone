@extends('admin_Layout')
@section('admin_content')
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                Cập nhật danh mục sản phẩm
            </header>
            <div class="panel-body" style="margin-top: 20px">
                <div class="position-center">
                    <form id="form-update-catogory">
                        @foreach ($category_product as $key)
                            <div class="form-group" style="display: none">
                                <label for="category-product-name">id</label>
                                <input type="text" class="form-control" name="category_product_id"
                                    value="{{ $key->category_id }}" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="category-product-name">Tên danh mục</label>
                                <input type="text" required=true class="form-control" name="category_product_name"
                                    value="{{ $key->category_name }}" placeholder="Tên danh mục">
                            </div>
                            <div class="form-group">
                                <label for="category-product-desc">Mô tả danh mục</label>
                                <textarea style="resize: none;" rows="5" class="form-control" name="category_product_desc"
                                    placeholder="Mô tả danh mục">{{ $key->category_desc }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="select-item">Ẩn hiện</label>
                                <select name="category_product_status" class="form-control m-bot15" id="select-item">
                                    <option value="1">Hiện</option>
                                    <option value="0">Ẩn</option>
                                </select>
                            </div>
                            
                        @endforeach

                        <button type="submit" class="btn btn-info">Cập nhật danh mục sản phẩm</button>
                        <a class="collapse-item" href="{{ URL::to('/all-category-product') }}">Trở về danh sách sản
                            phẩm</a>
                    </form>
                </div>

            </div>
        </section>
    </div>
    <script>
        var x, i
        x = document.querySelectorAll("#select-item > option");
        for (i = 0; i < x.length; i++) {
            if (x[i].value == "{{ $key->category_status }}") {
                x[i].selected = true;
            }
        }
        let form = document.getElementById('form-update-catogory');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                    url: '/api/update-category-product',
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
