@extends('admin/layout')

@section('head')
<link rel="stylesheet" href="../css/datatable.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
<!-- TABLA DE PRODUCTOS -->
<div class="justify-content-center">
<div class="card col-12 px-0">
    <div class="card-body">
        <div class="row px-3">
            <div class="d-inline-flex ml-auto mb-2">
            <button type="button" class="btn btn-danger px-2" data-toggle="modal" data-target="#new_product_modal" style="font-weight:bold;">
                <span class="fas fa-plus-square"></span> Nuevo producto
            </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover text-center" id="products_table">
                <thead class="bg-dark" style="color:white; font-weight:bold;">
                    <tr>
                        <th style="padding-left:30px;">Id</th>
                        <th style="padding-left:30px;">Producto</th>
                        <th style="padding-left:30px;">Descripción</th>
                        <th style="padding-left:30px;">Categoría</th>
                        <th style="padding-left:30px;">Costo</th>
                        <th style="padding-left:30px;">Precio de Venta</th>
                        <th style="padding-left:30px;">Precio con Crédito</th>
                        <th style="padding-left:30px;">Ventas S/Stock</th>
                        <th style="padding-left:30px;">Visible</th>
                        <td style="padding-left:15px;">Editar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $products = \App\Product::all() ?>
                    @foreach ($products as $product)
                    <tr>
                        <td class="negrita">{{$product->id}}</td>
                        <td class="negrita">{{$product->name}}</td>
                        <td class="negrita">{{$product->description}}</td>
                        <?php $categories=''; foreach ($product->categories_products as $category_product){$categories .= $category_product->category->name . ', ';}?>
                        <td class="negrita">{{substr($categories, 0, -2)}}</td>
                        <td class="negrita">{{$product->cost_price}}</td>
                        <td class="negrita">{{$product->sale_price}}</td>
                        <td class="negrita">{{$product->credit_price}}</td>
                        <td class="negrita">@if ($product->without_stock_sales==1)Sí @else No @endif</td>
                        <td class="negrita">@if ($product->visible==1)Sí @else No @endif</td>
                        <td>
                            <span class="btn btn-danger" class="btn btn-warning border-radius" onclick="edit_product({{$product->id}})" data-toggle="modal" data-target="#edit_product_modal">
                                    <i class="fas fa-user-edit"></i>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-danger">
                        <th>Id</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Costo</th>
                        <th>Precio de venta</th>
                        <th>Precio con crédito</th>
                        <th>Ventas S/Stock</th>
                        <th>Visible</th>
                        <td>Editar</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<!-- MODAL NUEVO PRODUCTO -->
<div class="modal fade" id="new_product_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Nuevo producto</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-white">Registrar Producto</h2>
                </div>
                <form method="POST" action="{{action('Product@new_product')}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="normal_width">
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Nombre *">
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4">
                            <textarea class="form-control col-12 col-lg-10 col-xl-8" name="description" rows='3' placeholder="Descripción">{{old('description')}}</textarea>
                    </div>
                    
                    
                    <div id="categories">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Categorías</label>
                        </div>
                        @if (!empty(old('categories.0')))
                            <?php $n = 0;
                            while (!empty(old('categories.'.$n))){ ?>
                            <div class="row d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-danger mr-3" onclick="remove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                                <div class="input-group ml-1 mr-5 normal_width">
                                    <select class="form-control" name="categories[]" value="{{old('categories.'.$n)}}">
                                        <option>Seleccionar</option>
                                        @foreach (\App\Category::all() as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php $n += 1;} ?>
                        @else
                        <div class="row d-flex justify-content-center mt-2">
                            <button type="button" class="btn btn-danger mr-3" onclick="remove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="input-group ml-1 mr-5 normal_width">
                                <select class="form-control" name="categories[]">
                                    <option>Seleccionar</option>
                                    @foreach (\App\Category::all() as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="append_category()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>
                    
                    
                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-4 col-lg-3">Costo</label>
                        <label class="text-white negrita col-4 col-lg-3 mx-lg-4 mx-xl-5">Precio de venta *</label>
                        <label class="text-white negrita col-4 col-lg-3">Precio con crédito</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-4 col-lg-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="cost_price" name="cost_price" min="0" step="0.01" placeholder="0.00" value="{{old('cost_price')}}">
                        </div>
                      
                        <div class="input-group col-4 col-lg-3 mx-lg-4 mx-xl-5">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="sale_price" name="sale_price" min="0" step="0.01" placeholder="0.00" value="{{old('sale_price')}}">
                        </div>
                        
                        <div class="input-group col-4 col-lg-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="credit_price" name="credit_price" min="0" step="0.01" placeholder="0.00" value="{{old('credit_price')}}">
                        </div>
                    </div>
                    
                    
                    <div id="waists">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Talles - Stock *</label>
                        </div>
                        <div class="row d-flex justify-content-center mt-2 pr-5">
                            <button type="button" class="btn btn-danger mr-1 mr-lg-3" onclick="remove_waist(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="input-group ml-1 normal_width">
                                <select class="form-control" name="waists[]">
                                    <option>Seleccionar</option>
                                    @foreach (\App\Waist::all() as $waist)
                                    <option value="{{$waist->id}}">{{$waist->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="normal_width">
                                <input type="number" class="form-control stock_quantity text-center ml-4" name="stock_quantity[]" placeholder="Cantidad en stock" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="append_waist()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>
                    
                    
                    <div id="photos">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Fotos *</label>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <button type="button" class="btn btn-danger mr-1 mr-lg-3 my-auto" onclick="remove_photo(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <input name="photos[]" type="file" class="file-input col-5 col-md-4 col-lg-3 my-auto" style="color:transparent; max-height: 30px;" onchange="file_input(this)"/>
                            <img style="max-width:355px; max-height:200px"/>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="append_photo()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>
                    
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <label class="text-white negrita mt-1" for="without_stock_sales">Ventas sin stock</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="without_stock_sales" name="without_stock_sales" @if (!empty(old('without_stock_sales'))) checked="true" @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-2">
                        <label class="text-white negrita mt-1" for="visible">Visible en la tienda</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="visible" name="visible" @if (!empty(old('visible'))) checked="true" @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    <div class="row d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg mt-4 negrita">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- MODAL EDITAR PRODUCTO -->
<div class="modal fade" id="edit_product_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin-top: 5vh;">
            <div class="modal-header">
                <div class="d-flex justify-content-center">
                    <h5 class="modal-title negrita">Edición de datos</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-dark">
                <div class="row align-middle d-flex justify-content-center">
                    <h2 class="negrita nosub text-white">Editar Producto</h2>
                </div>
                <form method="POST" action="{{action('Product@edit_product')}}" enctype="multipart/form-data" id="editform">
                    {{ csrf_field() }}
                    <input type="hidden" name="eid" id="eid" value="{{old('eid')}}">
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <div class="normal_width">
                            <input type="text" class="form-control" id="ename" name="ename" value="{{old('ename')}}" placeholder="Nombre *">
                        </div>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-4">
                            <textarea class="form-control col-12 col-lg-10 col-xl-8" id="edescription" name="edescription" value="{{old('edescription')}}" rows='3' placeholder="Descripción"></textarea>
                    </div>
                    
                    
                    <div id="ecategories">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Categorías</label>
                        </div>
                        <div class="row d-flex justify-content-center mt-2">
                            <button type="button" class="btn btn-danger mr-3 ecategories_btn" onclick="eremove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="input-group ml-1 mr-5 normal_width">
                                <select class="form-control ecategories" name="ecategories[]">
                                    <option>Seleccionar</option>
                                    @foreach (\App\Category::all() as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="eappend_category()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>
                    
                    
                    <div class="row d-flex justify-content-center mt-4 mb-0">
                        <label class="text-white negrita col-4 col-lg-3">Costo</label>
                        <label class="text-white negrita col-4 col-lg-3 mx-lg-4 mx-xl-5">Precio de venta *</label>
                        <label class="text-white negrita col-4 col-lg-3">Precio con crédito</label>
                    </div>
                    <div class="row d-flex justify-content-center mt-0">
                        <div class="input-group col-4 col-lg-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="ecost_price" name="ecost_price" min="0" step="0.01" placeholder="0.00">
                        </div>
                      
                        <div class="input-group col-4 col-lg-3 mx-lg-4 mx-xl-5">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="esale_price" name="esale_price" min="0" step="0.01" placeholder="0.00">
                        </div>
                        
                        <div class="input-group col-4 col-lg-3">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="ecredit_price" name="ecredit_price" min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                    
                    
                    <div id="ewaists">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Talles - Stock *</label>
                        </div>
                        <div class="row d-flex justify-content-center mt-2 pr-5">
                            <button type="button" class="btn btn-danger mr-1 mr-lg-3 ewaists_btn" onclick="eremove_waist(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <div class="input-group ml-1 normal_width">
                                <select class="form-control ewaists" name="ewaists[]">
                                    <option>Seleccionar</option>
                                    @foreach (\App\Waist::all() as $waist)
                                    <option value="{{$waist->id}}">{{$waist->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="normal_width">
                                <input type="number" class="form-control estock_quantity text-center ml-4" name="estock_quantity[]" placeholder="Cantidad en stock" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="eappend_waist()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>
                    
                    
                    <div id="ephotos">
                        <div class="row d-flex justify-content-center mt-4">
                            <label class="text-primary negrita mb-0" style="font-size:30px;">Fotos *</label>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <button type="button" class="btn btn-danger mr-1 mr-lg-3 my-auto ephotos_btn" onclick="eremove_photo(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                            <input name="ephotos[]" type="file" class="file-input col-5 col-md-4 col-lg-3 my-auto ephotos" style="color:transparent; max-height: 30px;" onchange="file_input(this)"/>
                            <img class="ephotos_img" style="max-width:355px; max-height:200px"/>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-2 mb-3">
                        <button type="button" class="btn btn-success" onclick="eappend_photo()" style="height:40px; border-radius:20px;"><span class="fas fa-plus-square"></span></button>
                    </div>
                    
                    
                    <div class="row d-flex justify-content-center mt-4">
                        <label class="text-white negrita mt-1" for="ewithout_stock_sales">Ventas sin stock</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="ewithout_stock_sales" name="ewithout_stock_sales">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    <div class="row d-flex justify-content-center mt-2">
                        <label class="text-white negrita mt-1" for="evisible">Visible en la tienda</label>
                        <label class="switch  mx-3">
                            <input type="checkbox" id="evisible" name="evisible">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    
                    <div class="modal-footer justify-content-center">
                        <div style="margin-right:10vw;">
                            <button type="button" class="btn btn-danger negrita" id="deletebtn"><i class="fa fa-times"></i> Eliminar</button>
                        </div>
                        <button type="submit" form="editform" class="btn btn-success negrita"><i class="fa fa-check"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection


@section('footer')
<script type="text/javascript">
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if ($errors->get('name'))
        $('#new_product_modal').modal('show');
    @endif
    
    @if ($errors->get('ename'))
        $('#edit_product_modal').modal('show');
    @endif
    
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
</script>

<script type="text/javascript">    
    function edit_product(id){
        $('#eid').val(id);
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
          type:"POST",
          data:"product_id=" + id,
          url:"/ajax/find_product",
            success:function(r){
                $('#ename').val(r['product']['name']);
                $('#edescription').val(r['product']['description']);
                $('#ecost_price').val(r['product']['cost_price']);
                $('#esale_price').val(r['product']['sale_price']);
                $('#ecredit_price').val(r['product']['credit_price']);
                if (r['product']['without_stock_sales'] == 1){
                    $('#ewithout_stock_sales').prop("checked", true);
                };
                if (r['product']['visible'] == 1){
                    $('#evisible').prop("checked", true);
                };
                
                r['categories'].forEach(function(valor, indice, array) {
                    $('.ecategories').last().val(valor['id']);
                    eappend_category();
                });
                $('.ecategories_btn').last().parent().remove();
                
                r['waists'].forEach(function(valor, indice, array) {
                    $('.ewaists').last().val(valor['id']);
                    $('.estock_quantity').last().val(valor['stock_quantity']);
                    eappend_waist();
                });
                $('.ewaists_btn').last().parent().remove();
                
                r['photos'].forEach(function(valor, indice, array) {
                    $('.ephotos_img').last().prop('src', '../img/product-img/'.concat(valor['name']));
                    eappend_photo();
                });
                $('.ephotos_btn').last().parent().remove();
            }
        });
    }
    
    $('#cost_price').blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    $('#sale_price').blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    $('#credit_price').blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    $('.stock_quantity').blur(function() {
        this.value = parseFloat(this.value).toFixed(0);
    });
    
    $('#ecost_price').blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    $('#esale_price').blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    $('#ecredit_price').blur(function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
    $('.estock_quantity').blur(function() {
        this.value = parseFloat(this.value).toFixed(0);
    });
    
    function remove_category(category){
        $(category).parent().remove();
    }
    function append_category(){
        $('#categories').append(
            `<div class="row d-flex justify-content-center mt-2">
                <button type="button" class="btn btn-danger mr-3" onclick="remove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <div class="input-group ml-1 mr-5 normal_width">
                    <select class="form-control" name="categories[]">
                        <option value="null">Seleccionar</option>
                        @foreach (\App\Category::all() as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>`);
    }
    function remove_waist(waist){
        $(waist).parent().remove();
    }
    function append_waist(){
        $('#waists').append(
            `<div class="row d-flex justify-content-center mt-2 pr-5">
                <button type="button" class="btn btn-danger mr-1 mr-lg-3" onclick="remove_waist(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <div class="input-group ml-1 normal_width">
                    <select class="form-control" name="waists[]">
                        <option value="null">Seleccionar</option>
                        @foreach (\App\Waist::all() as $waist)
                        <option value="{{$waist->id}}">{{$waist->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="normal_width">
                    <input type="number" class="form-control stock_quantity text-center ml-4" name="stock_quantity[]" placeholder="Cantidad en stock" min="0">
                </div>
            </div>`);
    }
    function remove_photo(photo){
        $(photo).parent().remove();
    }
    function append_photo(){
        $('#photos').append(
            `<div class="row d-flex justify-content-center">
                <button type="button" class="btn btn-danger mr-1 mr-lg-3 my-auto" onclick="remove_photo(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <input name="photos[]" type="file" class="file-input col-5 col-md-4 col-lg-3 my-auto" style="color:transparent; max-height: 30px;" onchange="file_input(this)"/>
                <img style="max-width:355px; max-height:200px"/>
            </div>`);
    }
    
    
    function eremove_category(category){
        $(category).parent().remove();
    }
    function eappend_category(){
        $('#ecategories').append(
            `<div class="row d-flex justify-content-center mt-2">
                <button type="button" class="btn btn-danger mr-3 ecategories_btn" onclick="remove_category(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <div class="input-group ml-1 mr-5 normal_width">
                    <select class="form-control ecategories" name="ecategory[]">
                        <option value="null">Seleccionar</option>
                        @foreach (\App\Category::all() as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>`);
    }
    function eremove_waist(waist){
        $(waist).parent().remove();
    }
    function eappend_waist(){
        $('#ewaists').append(
            `<div class="row d-flex justify-content-center mt-2 pr-5">
                <button type="button" class="btn btn-danger mr-1 mr-lg-3 ewaists_btn" onclick="eremove_waist(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <div class="input-group ml-1 normal_width">
                    <select class="form-control ewaists" name="ewaists[]">
                        <option value="null">Seleccionar</option>
                        @foreach (\App\Waist::all() as $waist)
                        <option value="{{$waist->id}}">{{$waist->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="normal_width">
                    <input type="number" class="form-control stock_quantity text-center ml-4 estock_quantity" name="estock_quantity[]" placeholder="Cantidad en stock" min="0">
                </div>
            </div>`);
    }
    function eremove_photo(photo){
        $(photo).parent().remove();
    }
    function eappend_photo(){
        $('#ephotos').append(
            `<div class="row d-flex justify-content-center">
                <button type="button" class="btn btn-danger mr-1 mr-lg-3 my-auto ephotos_btn" onclick="eremove_photo(this)" style="height:40px; border-radius:20px;"><span class="fas fa-minus-square"></span></button>
                <input name="ephotos[]" type="file" class="file-input col-5 col-md-4 col-lg-3 my-auto ephotos" style="color:transparent; max-height: 30px;" onchange="file_input(this)"/>
                <img class="ephotos_img" style="max-width:355px; max-height:200px"/>
            </div>`);
    }
    
    
    
    function file_input(input){
        var file = input.files[0], imageType = /image.*/;
        if (!file.type.match(imageType))
            return;
        var reader = new FileReader();
        reader.onload = function(e) {
            var result = e.target.result;
            $(input).next().prop('src', result);
        }
        reader.readAsDataURL(file);
    }
</script>

<script>
$('#deletebtn').popover({
    title: "<div class='d-flex justify-content-center'><label>Está por eliminar un producto ¿Desea continuar?</label></div>",
    content: "<div class='d-flex justify-content-center'><button type='submit' form='editform' class='btn btn-warning' name='delete_product'>Confirmar</button></div>",
    html: true,
    placement: "right"}); 
</script>

<script src="../js/datatable.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>

<!--DATATABLES-->
<script type="text/javascript">
$(document).ready(function () {

    // Setup - add a text input to each footer cell
    $('#products_table tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" class="tcentrado negrita" style="border-radius: 10px;"/>');
    });

    var table = $('#products_table').DataTable({
        dom: '<"top"<"row col-12"<"mt-2"B><"d-inline-flex ml-auto mt-2"l>><"row col-12 mt-2"f>>rt<"bottom"<"row"<"col-8"i><"col-4"p>>>',
        buttons: [
            {
                extend: 'colvis',
                columns: ':not(.noVis)',
                text: 'Columnas',
                className: 'bg-primary negrita'
            }
        ],
        "order": [[1, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": 9 },
            { "searchable": false, "targets": 9 }
          ],
        "autoWidth": false,
        "language": {
            "emptyTable": "Tabla vacía",
            "lengthMenu": "Mostrar _MENU_ productos por página",
            "zeroRecords": "No hay coincidencias con el criterio de búsqueda",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
            "infoEmpty": "No hay productos para mostrar",
            "infoFiltered": "(filtrado de un total de _MAX_ productos)",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Búsqueda:",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
    table.columns( [0, 2, 4, 6, 7] ).visible( false );

    // Apply the search
    table.columns().every(function () {
        var that = this;

        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that
                        .search(this.value)
                        .draw();
            }
        });
    });

    var r = $('#products_table tfoot tr');
    $('#products_table thead').append(r);
    $('#search_0').css('text-align', 'center');

});
</script>
    
@endsection