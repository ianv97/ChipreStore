@extends('layout')
@section('body')
<div class="shop_sidebar_area" style="background-color: #212529;">
    <div class="widget catagory mb-50">
        <h6 class="widget-title mb-20">Categorías</h6>
        <div class="catagories-menu">
            <ul>
                <li class="category_selector active" value="all"><a href="#">Todas</a></li>
                @foreach ($categories as $category)
                <li class="category_selector" value="{{$category->name}}"><a href="#">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <div class="widget catagory mb-50">
        <h6 class="widget-title mb-20">Talle</h6>
        <div class="catagories-menu">
            <ul>
                <li class="waist_selector active" value="all"><a href="#">Todos</a></li>
                @foreach ($waists as $waist)
                <li class="waist_selector" value="{{$waist->name}}"><a href="#">{{$waist->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>


<div class="amado_product_area pt-0">
    <div class="container-fluid">
       <div class="row">
           <nav class="navbar col-12" style="background-color: #212529;">
                <div class="form-inline my-2 mx-auto justify-content-center">
                    <input class="form-control mr-1" id="search_input" type="search" placeholder="Búsqueda" style="max-width: 80%;">
                    <button class="btn btn-outline-warning fa fa-search"></button>
                </div>
            </nav>
       </div>   

        <div class="row">
            @foreach ($products as $product)
            <div class="single-product-wrapper px-0 col-12 col-sm-6 col-md-12 col-lg-6 col-xl-4 product all
                 <?php $discount = 0;
                 foreach($product->categories_products as $cat_prod){
                     echo ($cat_prod->category->name.' ');
                     foreach ($cat_prod->category->offers as $offer){
                        if ($offer->state == 1){
                            $discount += $offer->discount_percentage;
                        }
                     }
                 }
                 foreach($product->products_waists as $prod_waist){
                     echo($prod_waist->waist->name.' ');
                 }?>" name="{{$product->name}}">
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
                        <?php
                        foreach ($product->products_offers as $product_offer){
                            if ($product_offer->offer->state == 1){
                                $discount += $product_offer->offer->discount_percentage;
                            }
                        }?>
                        @if ($discount > 0)
                        <i class="fa fa-circle discounti" style="position:absolute; top:-110px; right:10px; font-size: 90px; color: #fbb710; z-index: 100;"></i>
                        <label class="discountl" style="position:absolute; top:-85px; right:11px; font-size: 30px; font-weight: 600; color: #212529; z-index: 105;">-{{$discount}}%</label>
                        <p class="product-price"><span class="tachado mr-2">${{number_format($product->sale_price, 2, ',', '.')}}</span><span class="fa fa-angle-double-right mr-2"></span>${{number_format($product->sale_price - ($product->sale_price * ($discount/100)), 2, ',', '.')}}</p>
                        @else
                            <p class="product-price">${{number_format($product->sale_price, 2, ',', '.')}}</p>
                        @endif
                        <a href="{{action('ShopController@product_details', ['id'=>$product->id])}}">
                            <h6 class="product_name" style="font-weight:600;">{{$product->name}}</h6>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination justify-content-end">
            {!!$products->render()!!}
        </div>
        
    </div>
</div>
@endsection

@section('footer')
<script>
    $('#products').addClass('active');
    $('.category_selector').click(function(){
        $('.category_selector').removeClass('active');
        $(this).addClass('active');
        $('.product').hide();
        $('.product.'+$('.category_selector.active').attr('value')+'.'+$('.waist_selector.active').attr('value')).show();
        $('.product:visible').each(function(){
            if ($(this).attr('name').toLowerCase().includes($('#search_input').val().toLowerCase())){
                $(this).show();
            }else{
                $(this).hide();
            }
        })
    })
    $('.waist_selector').click(function(){
        $('.waist_selector').removeClass('active');
        $(this).addClass('active');
        $('.product').hide();
        $('.product.'+$('.category_selector.active').attr('value')+'.'+$('.waist_selector.active').attr('value')).show();
        $('.product:visible').each(function(){
            if ($(this).attr('name').toLowerCase().includes($('#search_input').val().toLowerCase())){
                $(this).show();
            }else{
                $(this).hide();
            }
        })
    })
    $('#search_input').on('input', function(){
        $('.product.'+$('.category_selector.active').attr('value')+'.'+$('.waist_selector.active').attr('value')).show();
        $('.product:visible').each(function(){
            if ($(this).attr('name').toLowerCase().includes($('#search_input').val().toLowerCase())){
                $(this).show();
            }else{
                $(this).hide();
            }
        })
    })
   
    @if (isset($category_filter))
       $('.category_selector[value="{{$category_filter}}"]').click();
    @endif
</script>
<style>
    .product:hover .discounti{color:#212529 !important;}
    .product:hover .discountl{color:#fbb710 !important;}
</style>

@endsection