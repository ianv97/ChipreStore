<?php if (!isset($_SESSION)){
    session_start();
}?>
@extends('layout')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/toastr.min.css">
@endsection

@section('body')
<div class="cart-table-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="cart-title mt-50">
                    <h2>Carrito de compras</h2>
                </div>

                <div class="cart-table clearfix">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%; padding-top:0;"><form method="post" action="{{action("Shop@empty_cart")}}">{{ csrf_field() }}<button type="submit" class="btn btn-danger"><i class="fa fa-cart-arrow-down"></i> Vaciar carrito</button></form></th>
                                <th style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;">Nombre</th>
                                <th style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">Talle</th>
                                <th style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">Precio</th>
                                <th style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">Cantidad</th>
                                <th style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; $ship = 100;
                            if (isset($_COOKIE["product"])){
                                foreach($_COOKIE["product"] as $cookie_product){
                                    foreach($cookie_product as $cookie_waist){
                                        $product = \App\Product::find($cookie_waist['id']);
                                        $waist = \App\Waist::find($cookie_waist['waist']);
                                        $subtotal = $product->sale_price * $cookie_waist['qty'];?>
                                        <tr>
                                            <td class="cart_product_img" style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;">
                                                <a href="{{action('Shop@product_details', ['id'=>$cookie_waist['id']])}}">
                                                    <img src="img/product-img/{{$product->photos[0]->name}}" style="max-height: 200px;">
                                                </a>
                                            </td>
                                            <td class="cart_product_desc" style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;">
                                                <h5>{{$product->name}}</h5>
                                            </td>
                                            <td class="waist" style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                                                <span>{{$waist->name}}</span>
                                            </td>
                                            <td class="price" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                                                <span>${{number_format($product->sale_price, 2, ',', '.')}}</span>
                                            </td>
                                            <td class="qty" style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                                                <span>{{$cookie_waist['qty']}}</span>
                                            </td>
                                            <td class="subtotal" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                                                <span>${{number_format($subtotal, 2, ',', '.')}}</span>
                                            </td>
                                            <input type="text" form="order-form" name="product_id[]" value="{{$product->id}}" hidden>
                                            <input type="text" form="order-form" name="product_name[]" value="{{$product->name}}" hidden>
                                            <input type="text" form="order-form" name="product_description[]" value="{{$product->description}}" hidden>
                                            <input type="text" form="order-form" name="product_photo[]" value="http://chipre.test/img/product-img/{{$product->photos[0]->name}}" hidden>
                                            <input type="text" form="order-form" name="waist_id[]" value="{{$waist->id}}" hidden>
                                            <input type="text" form="order-form" name="waist_name[]" value="{{$waist->name}}" hidden>
                                            <input type="text" form="order-form" name="qty[]" value="{{$cookie_waist['qty']}}" hidden>
                                            <input type="text" form="order-form" name="subtotal[]" value="{{$subtotal}}" hidden>
                                        </tr>
                                        <?php $total+= $product->sale_price * $cookie_waist['qty'];
                                    }
                                }
                            }else{ ?>
                            <tr>
                                <td style="width: 100%;max-width: 100%;">
                                    <h2 style="color:#ffc107;">El carrito está vacío</h2>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-xl-3">
                <div class="cart-summary">
                    <h5>Resumen</h5>
                    <ul class="summary-table">
                        <form method="post" action="{{action('Shop@order')}}" id="order-form" onsubmit="complete_inputs()">
                        {{ csrf_field() }}
                        @if (isset($_SESSION['role']) and $_SESSION['role']=='Cliente')
                            <?php $customer = \App\Customer::find($_SESSION['id']) ?>
                            <li class="mb-1">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input ship_radio" id="ship_radio1" name="ship" value="0" required>
                                    <label class="custom-control-label" for="ship_radio1" style="text-transform: none;">Enviar a la dirección registrada</label>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input ship_radio" id="ship_radio2" name="ship" value="1" required>
                                    <label class="custom-control-label" for="ship_radio2" style="text-transform: none;">Enviar a otra dirección</label>
                                </div>
                            </li>
                            <li class="row mb-1">
                                <div class="col-6">
                                    <select class="w-100" id="province" name="province" required disabled>
                                        <option></option>
                                        @foreach (\App\Province::all() as $province)
                                        <option value="{{$province->id}}">{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="w-100" id="city" name="city" required disabled>
                                        <option></option>
                                    </select>
                                </div>
                            </li>
                            <li class="row px-3">
                                <input type="text" class="col-12 pt-1" id="address" name="address" placeholder="Dirección" required disabled>
                            </li>
                        @endif
                            <li>
                                <span style="text-transform: none;">Total (sin envío):</span> <span>${{number_format($total, 2, ',', '.')}}</span>
                            </li>
                            
                            <input type="text" name="total" value="{{$total}}" hidden>
                            <input type="text" id="icity" name="city" hidden>
                            <input type="text" id="iaddress" name="address"  hidden>
                        </form>
                    </ul>
                    <div class="cart-btn">
                        <button type="submit" form="order-form" class="btn amado-btn w-100">Finalizar compra</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/toastr.min.js"></script>
<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        <?php break; ?>
    @endforeach
   
    $('#cart').addClass('active');
    
    @if (isset($_SESSION['role']) and $_SESSION['role']=='Cliente')
    $(document).ready(function() {
         $('#province').select2({
             placeholder: "Provincia",
             allowClear: true
         });
         $('#city').select2({
             placeholder: "Ciudad",
             allowClear: true
         });
         $('#province').val({{$customer->city->province->id}}).trigger('change');
     });

    $('#province').change(function(){
         $('#city').children().remove();
         $('#city').append('<option></option>');
         $.ajaxSetup({
             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
         });
         $.ajax({
           type:"POST",
           data:"province_id=" + $('#province').val(),
           url:"/ajax/find_cities",
             success:function(r){
                 r.forEach(function(city) {
                     $('#city').append("<option ".concat("value=",city['id'], ">", city['name'], "</option>"));
                 });
                 $('#city').val({{$customer->city->id}}).trigger('change');
                 $('#address').val('{{$customer->address}}');
             }
         });
    });
    
    $('.ship_radio').change(function(){
        if ($('#ship_radio1').prop('checked') == true){
            if ($('#address').prop('disabled') == false){
                $('#province').val({{$customer->city->province->id}}).trigger('change');
                $('#province').prop('disabled', true);
                $('#city').prop('disabled', true);
                $('#address').prop('disabled', true);
            }
        }else{
            if ($('#ship_radio2').prop('checked') == true){
                $('#province').prop('disabled', false);
                $('#city').prop('disabled', false);
                $('#address').prop('disabled', false);
            }
        }
    })
    @endif
    function complete_inputs(){
        $('#icity').val($('#city').val());
        $('#iaddress').val($('#address').val());
    }
</script>
@endsection