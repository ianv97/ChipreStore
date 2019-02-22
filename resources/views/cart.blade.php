<?php if (!isset($_SESSION)){
    session_start();
}?>
@extends('layout')
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
                                <th style="-ms-flex: 0 0 25%;flex: 0 0 25%;width: 25%;max-width: 25%;"></th>
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
                                        $product = \App\Product::find($cookie_waist['id']); ?>
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
                                                <span>{{\App\Waist::find($cookie_waist['waist'])->name}}</span>
                                            </td>
                                            <td class="price" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                                                <span>${{number_format($product->sale_price, 2, ',', '.')}}</span>
                                            </td>
                                            <td class="qty" style="-ms-flex: 0 0 10%;flex: 0 0 10%;width: 10%;max-width: 10%;">
                                                <span>{{$cookie_waist['qty']}}</span>
                                            </td>
                                            <td class="subtotal" style="-ms-flex: 0 0 15%;flex: 0 0 15%;width: 15%;max-width: 15%;">
                                                <span>${{number_format($product->sale_price * $cookie_waist['qty'], 2, ',', '.')}}</span>
                                            </td>
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
            <div class="col-12 col-lg-3">
                <div class="cart-summary">
                    <h5>Resumen</h5>
                    <ul class="summary-table">
                        <li><span>Total (sin envío):</span> <span>${{number_format($total, 2, ',', '.')}}</span></li>
                        <li><span>Envío:</span> <span>${{number_format($ship, 2, ',', '.')}}</span></li>
                        <li><span>Total final:</span> <span>${{number_format($total+$ship, 2, ',', '.')}}</span></li>
                    </ul>
                    <div class="cart-btn">
                        <a href="{{action('Shop@checkout')}}" class="btn amado-btn w-100">Finalizar compra</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
   $('#cart').addClass('active');
</script>
@endsection