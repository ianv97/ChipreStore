<?php
session_start();
$_SESSION['product'] = $product->id;
?>

@extends('layout');
@section('body')
<div class="single-product-area section-padding-100 clearfix">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="single_product_thumb">
                    <div id="product_details_slider" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php $i = 0 ?>
                            @foreach ($product->photos as $photo)
                            @if ($i == 0)
                                <li class="active" data-target="#product_details_slider" data-slide-to="{{$i}}" style="background-image: url('/img/product-img/{{$photo->name}}');"></li>
                            @else
                                <li data-target="#product_details_slider" data-slide-to="{{$i}}" style="background-image: url('/img/product-img/{{$photo->name}}');"></li>
                            @endif
                            <?php $i += 1 ?>
                            @endforeach
                        </ol>
                        <div class="carousel-inner" style="max-height: 600px;">
                            <?php $i = 0 ?>
                            @foreach ($product->photos as $photo)
                            @if ($i == 0)
                                <div class="carousel-item active justify-content-center">
                                    <a class="gallery_img" href="/img/product-img/{{$photo->name}}">
                                        <img class="d-block w-100" src="/img/product-img/{{$photo->name}}">
                                    </a>
                                </div>
                            @else
                                <div class="carousel-item justify-content-center">
                                    <a class="gallery_img" href="/img/product-img/{{$photo->name}}">
                                        <img class="d-block w-100" src="/img/product-img/{{$photo->name}}">
                                    </a>
                                </div>
                            @endif
                            <?php $i += 1 ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="single_product_desc">
                    <!-- Product Meta Data -->
                    <div class="product-meta-data">
                        <div class="line"></div>
                        <p class="product-price">${{$product->sale_price}}</p>
                        <a href="product-details.html">
                            <h6>{{$product->name}}</h6>
                        </a>
                        <!-- Avaiable -->
                        <p class="avaibility" id="available"><i class="fa fa-circle"></i> En Stock</p>
                    </div>

                    <div class="short_overview my-5">
                        <p>{{$product->description}}</p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form class="cart clearfix" method="post">
                        <div class="row mb-50">
                            <div class="input-group d-flex" style="width: 100px;">
                                <select class="form-control" name="waists[]" id="waist" onchange="check_stock()">
                                    <option>Talle</option>
                                    @foreach (\App\Waist::all() as $waist)
                                    <option value="{{$waist->id}}">{{$waist->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="cart-btn d-flex ml-2">
                                <p>Cantidad</p>
                                <div class="quantity">
                                    <span class="qty-minus" onclick=" var effect = document.getElementById('qty'); var qty = effect.value; if(!isNaN(qty) &amp;&amp; qty &gt; 1){ effect.value--;} check_stock();"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                                    <input type="text" class="qty-text" id="qty" step="1" min="1" max="300" name="quantity" value="1" pattern="\d{3}" onchange="check_stock()">
                                    <span class="qty-plus" onclick="var effect = document.getElementById('qty'); var qty = effect.value; if(!isNaN(qty)){effect.value++;} check_stock();"><i class="fa fa-caret-up" aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="addtocart" class="btn amado-btn">Añadir al carrito</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
   $('#product_details').addClass('active');
   
   function check_stock(){
       switch($('#waist').val()){
            @foreach (\App\Waist::all() as $waist)
                @if ($product_waist = \App\ProductsWaist::where('product_id', $product->id)->where('waist_id', $waist->id)->first())
                    case "{{$waist->id}}": 
                        if ($("#qty").val() > {{$product_waist->stock_quantity}}){
                            $("#available").html('<i class="fa fa-circle" style="color: #ff0000;"></i> Sin Stock');
                        }else{
                            $("#available").html('<i class="fa fa-circle" style="color: #20d34a;"></i> En Stock');
                        }; break;
                @endif
            @endforeach
            default: $("#available").html('<i class="fa fa-circle" style="color: #ff0000;"></i> Sin Stock');
       }
   }
</script>
@endsection