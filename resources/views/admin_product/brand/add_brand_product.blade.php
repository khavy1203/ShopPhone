@extends('admin_Layout')
@section('admin_content')
<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm thương hiệu sản phẩm  
        </header>
        <div class="panel-body" style="margin-top: 20px">
            <div class="position-center">
                <form id="form-add-brand">
                    <div class="form-group">
                        <label for="brand-product-name">Tên thương hiệu</label>
                        <input type="text" required=true class="form-control"  name="brand_product_name" placeholder="Tên thương hiệu">
                    </div>
                    <div class="form-group">
                        <label for="brand-product-desc">Mô tả</label>
                        <textarea style="resize: none;" rows="5" class="form-control "  name="brand_product_desc" required=true placeholder="Mô tả thương hiệu"></textarea>
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="select-item">Ẩn hiện</label>
                        <select name="brand_product_status" class="form-control m-bot15" id="select-item">
                            <option value="0">ẩn</option>
                            <option selected value="1">hiện</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="select-item">Danh mục sản phẩm</label>
                        <select name="category_id" class="form-control m-bot15" id="select-item">
                            @foreach ($category as $key => $cate)
                                <option value="{{ $cate->category_id }}">{{ $cate->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Thêm thương hiệu sản phẩm</button>
                    <a class="collapse-item" href="{{ URL::to('/all-brand-product') }}">Trở về</a>
                </form>
            </div>

        </div>
    </section>

</div>
<script>
    let form = document.getElementById('form-add-brand');
    form.addEventListener('submit',function(e){
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: '/api/save-brand-product',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,
        })
        .done(function(res) {
            if (res.status) {
                toast({
                    'message': `${res.message} <b>${res.data['brand_name']}</b>`,
                    'title': 'Thành công'
                })
            } else {
                toast({
                    'message': res.message,
                    'title': 'Thất bại',
                    'type': 'error'
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