@extends('layout')
@section('body')
<div class="shop_sidebar_area">
    
    <!-- ##### Single Widget ##### -->
    <div class="widget catagory mb-50">
        <!-- Widget Title -->
        <h6 class="widget-title mb-20">Categorías</h6>
        <!--  Catagories  -->
        <div class="catagories-menu">
            <ul>
                <li class="active"><a href="#">Todas</a></li>
                @foreach (\App\Category::where('state', 1)->get() as $category)
                <li><a href="#">{{$category->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <!-- ##### Single Widget ##### -->
    <div class="widget catagory mb-50">
        <!-- Widget Title -->
        <h6 class="widget-title mb-20">Talle</h6>
        <!--  Catagories  -->
        <div class="catagories-menu">
            <ul>
                <li class="active"><a href="#">Todos</a></li>
                @foreach (\App\Waist::all() as $waist)
                <li><a href="#">{{$waist->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

<!--     ##### Single Widget ##### 
    <div class="widget brands mb-50">
         Widget Title 
        <h6 class="widget-title mb-30">Marcas</h6>

        <div class="widget-desc">
             Single Form Check 
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="amado">
                <label class="form-check-label" for="amado">Amado</label>
            </div>
             Single Form Check 
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="ikea">
                <label class="form-check-label" for="ikea">Ikea</label>
            </div>
             Single Form Check 
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="furniture">
                <label class="form-check-label" for="furniture">Furniture Inc</label>
            </div>
             Single Form Check 
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="factory">
                <label class="form-check-label" for="factory">The factory</label>
            </div>
             Single Form Check 
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="artdeco">
                <label class="form-check-label" for="artdeco">Artdeco</label>
            </div>
        </div>
    </div>

     ##### Single Widget ##### 
    <div class="widget color mb-50">
         Widget Title 
        <h6 class="widget-title mb-30">Color</h6>

        <div class="widget-desc">
            <ul class="d-flex">
                <li><a href="#" class="color1"></a></li>
                <li><a href="#" class="color2"></a></li>
                <li><a href="#" class="color3"></a></li>
                <li><a href="#" class="color4"></a></li>
                <li><a href="#" class="color5"></a></li>
                <li><a href="#" class="color6"></a></li>
                <li><a href="#" class="color7"></a></li>
                <li><a href="#" class="color8"></a></li>
            </ul>
        </div>
    </div>-->

    <!-- ##### Single Widget ##### -->
    <div class="widget price mb-50">
        <!-- Widget Title -->
        <h6 class="widget-title mb-30">Precio</h6>

        <div class="widget-desc">
            <div class="slider-range">
                <div data-min="10" data-max="1000" data-unit="$" class="slider-range-price ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-value-min="10" data-value-max="1000" data-label-result="">
                    <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                    <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                    <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                </div>
                <div class="range-price">$0 - $10.000</div>
            </div>
        </div>
    </div>
</div>

<div class="amado_product_area section-padding-100">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="product-topbar d-xl-flex align-items-end justify-content-between">
                    <!-- Total Products -->
                    <div class="total-products">
                        <p>Mostrando 1-8 de 25</p>
                        <div class="view d-flex">
                            <a href="#" class="active"><i class="fa fa-th-large" aria-hidden="true"></i></a>
                            <a href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <!-- Sorting -->
                    <div class="product-sorting d-flex">
                        <div class="sort-by-date d-flex align-items-center mr-15">
                            <p>Ordenar por</p>
                            <form action="#" method="get">
                                <select name="select" id="sortBydate">
                                    <option value="value">Date</option>
                                    <option value="value">Newest</option>
                                    <option value="value">Popular</option>
                                </select>
                            </form>
                        </div>
                        <div class="view-product d-flex align-items-center">
                            <p>Ver</p>
                            <form action="#" method="get">
                                <select name="select" id="viewProduct">
                                    <option value="value">12</option>
                                    <option value="value">24</option>
                                    <option value="value">48</option>
                                    <option value="value">96</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach (\App\Product::where('visible', 1)->get() as $product)
            
            <div class="col-12 col-sm-6 col-md-12 col-xl-4">
                <div class="single-product-wrapper">
                    <!-- Product Image -->
                    <div class="product-img" style="max-height:500px;">
                        <a href="{{action('Shop@product_details', ['id'=>$product->id])}}">
                            <img src="img/product-img/{{$product->photos[0]->name}}">
                            <!-- Hover Thumb -->
                            @if (isset($product->photos[1]))
                                <img class="hover-img" src="img/product-img/{{$product->photos[1]->name}}">
                            @endif
                        </a>
                    </div>

                    <!-- Product Description -->
                    <div class="product-description d-flex align-items-center justify-content-between">
                        <!-- Product Meta Data -->
                        <div class="product-meta-data">
                            <div class="line"></div>
                            <p class="product-price">${{$product->sale_price}}</p>
                            <a href="{{action('Shop@product_details', ['id'=>$product->id])}}">
                                <h6 style="font-weight:600;">{{$product->name}}</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            @endforeach
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Pagination -->
                <nav aria-label="navigation">
                    <ul class="pagination justify-content-end mt-50">
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
   $('#products').addClass('active');
</script>
@endsection