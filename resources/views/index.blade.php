@extends('layout')
@section('body')
    <div class="products-catagories-area clearfix">
        <div class="amado-pro-catagory clearfix">
            @foreach ($categories as $category)
                <div class="single-products-catagory clearfix">
                    <a href="/products/{{$category->cname}}">
                        <img src="img/product-img/{{$category->photo}}">
                        <div class="hover-content">
                            <div class="line"></div>
                            <p>Desde ${{number_format($category->price, 2, ',', '.')}}</p>
                            <h4>{{$category->cname}}</h4>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('footer')
<script>
   $('#index').addClass('active');
</script>
@endsection