@extends('layout')
@section('body')

<div class="amado_product_area pt-0">
    <div class="container-fluid">   

        <div class="row">
        <?php foreach ($products as $product){
            $discount = 0;
            foreach($product->categories_products as $cat_prod){
                foreach ($cat_prod->category->offers as $offer){
                   if ($offer->state == 1){
                       $discount += $offer->discount_percentage;
                   }
                }
            }
            foreach ($product->products_offers as $product_offer){
                if ($product_offer->offer->state == 1){
                    $discount += $product_offer->offer->discount_percentage;
                }
            }
            if ($discount > 0){?>
            
            <div class="single-product-wrapper px-0 col-12 col-sm-6 col-md-12 col-lg-6 col-xl-4 product" name="{{$product->name}}">
                 <!--Product Image--> 
                <div class="product-img" style="max-height:500px;">
                    <a href="{{action('ShopController@product_details', ['id'=>$product->id])}}">
                        <img src="/img/product-img/{{$product->photos[0]->name}}">
                         <!--Hover Thumb--> 
                        @if (isset($product->photos[1]))
                            <img class="hover-img" src="/img/product-img/{{$product->photos[1]->name}}">
                        @endif
                    </a>
                </div>
                 <!--Product Description--> 
                <div class="product-description d-flex align-items-center justify-content-between ml-3">
                     <!--Product Meta Data--> 
                    <div class="product-meta-data">
                        <div class="line"></div>
                        <i class="fa fa-circle discounti" style="position:absolute; top:-110px; right:10px; font-size: 90px; color: #fbb710; z-index: 100;"></i>
                        <label class="discountl" style="position:absolute; top:-85px; right:11px; font-size: 30px; font-weight: 600; color: #212529; z-index: 105;">-{{$discount}}%</label>
                        <p class="product-price"><span class="tachado mr-2">${{number_format($product->sale_price, 2, ',', '.')}}</span><span class="fa fa-angle-double-right mr-2"></span>${{number_format($product->sale_price - ($product->sale_price * ($discount/100)), 2, ',', '.')}}</p>
                        <a href="{{action('ShopController@product_details', ['id'=>$product->id])}}">
                            <h6 class="product_name" style="font-weight:600;">{{$product->name}}</h6>
                        </a>
                    </div>
                </div>
            </div>
      <?php }
        } ?>
        </div>
        <div class="pagination justify-content-end">
            {!!$products->render()!!}
        </div>
        
    </div>
</div>
@endsection

@section('footer')
<script>
    $('#discount_products').addClass('active');
</script>
<style>
    .product:hover .discounti{color:#212529 !important;}
    .product:hover .discountl{color:#fbb710 !important;}
</style>

<style>
    @media only screen and (min-width: 768px){
      .main-content-wrapper .amado_product_area {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 calc(100% - 220px);
        flex: 0 0 calc(100% - 220px);
        width: calc(100% - 220px);
        max-width: calc(100% - 220px); } }
</style>

@endsection