@extends('admin_Layout')
@section('admin_content')
<div class="col-md-12">
    <section class="panel">
        <header class="panel-heading">
            Thêm danh mục sản phẩm  
        </header>
        <div class="panel-body" style="margin-top: 20px">
            <div class="position-center">
                <form id="form-add-catogory">
                
                    <div class="form-group">
                        <label for="category-product-name">Tên danh mục</label>
                        <input type="text" required=true class="form-control"  name="category_product_name" placeholder="Tên danh mục">
                    </div>
                    <div class="form-group">
                        <label for="category-product-desc">Mô tả danh mục</label>
                        <textarea style="resize: none;" rows="5" class="form-control " required=true  name="category_product_desc" placeholder="Mô tả danh mục"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="select-item">Ẩn hiện</label>
                        <select required="true" name="category_product_status" class="form-control m-bot15" id="select-item">
                            <option value="0">ẩn</option>
                            <option selected value="1">hiện</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-info">Thêm danh mục sản phẩm</button>
                    <a class="collapse-item" href="{{ URL::to('/all-category-product') }}">Trở về</a>
                </form>
            </div>

        </div>
    </section>

</div>
<script>
    let form = document.getElementById('form-add-catogory');
    form.addEventListener('submit',function(e){
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: '/api/save-category-product',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,
        })
        .done(function(res) {
            if (res.status) {
                toast({
                    'message': `${res.message} <b>${res.data['category_name']}</b>`,
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