<?php
if(!isset($_SESSION)){ 
        session_start(); 
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- Title  -->
    <title>Chipre Store</title>
    <!-- Favicon  -->
    <link rel="shortcut icon" href="/img/logo.ico">
    <!-- Core Style CSS -->
    <link rel="stylesheet" href="/css/Template/core-style.css">
    
    @yield('head')
</head>

<body>
<!-- Search Wrapper -->
<div class="search-wrapper py-5">
    <div class="search-close">
        <i class="fa fa-close" aria-hidden="true"></i>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="search-content">
                    <form action="#" method="get">
                        <input type="search" name="search" id="search" placeholder="Ingresa tu búsqueda...">
                        <button type="submit"><img src="/img/core-img/search.png" alt=""></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ##### Main Content Wrapper Start ##### -->
<div class="main-content-wrapper d-flex clearfix">
    
    <!-- Mobile Nav (max width 767px)-->
    <div class="mobile-nav">
        <!-- Navbar Brand -->
        <div class="amado-navbar-brand">
            <a href="/"><img src="/img/logo.svg" alt=""></a>
        </div>
        <!-- Navbar Toggler -->
        <div class="amado-navbar-toggler">
            <span></span><span></span><span></span>
        </div>
    </div>

    <aside class="header-area clearfix mt-0">
        <!-- Close Icon -->
        <div class="nav-close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </div>
        <!-- Logo -->
        <div>
            <a href="index.html"><img src="/img/logo.svg" alt=""></a>
        </div>
        <!-- Nav -->
        <nav class="amado-nav">
            <ul>
                <li id="index"><a href="{{action('Shop@index')}}">Inicio</a></li>
                <li id="products"><a href="{{action('Shop@products')}}">Productos</a></li>
                <li id="product_details"><a href="@if (isset($_SESSION['product'])) {{action('Shop@product_details', ['id'=>$_SESSION['product']])}} @else # @endif">Detalles</a></li>
                <li id="cart"><a href="{{action('Shop@cart')}}" class="cart-nav"><img src="/img/core-img/cart.png"> Carrito <span>(0)</span></a></li>
                <li id="checkout"><a href="{{action('Shop@checkout')}}">Finalizar compra</a></li>
            </ul>
        </nav>
        <!-- Button Group -->
        <div class="amado-btn-group mt-30 mb-30">
            <a href="#" class="btn amado-btn mb-15">En descuento</a>
            <a href="#" class="btn amado-btn">Lo nuevo</a>
        </div>
        <!-- Cart Menu -->
        <div class="cart-fav-search mb-30">
            <a href="#" class="search-nav"><img src="/img/core-img/search.png"> Búsqueda</a>
        </div>
        <!-- Social Buttons -->
        <div class="social-info d-flex justify-content-between mx-4">
            <a href="https://www.instagram.com/chiprestore/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            <a href="https://www.facebook.com/Chipre-Store-995097487294669/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
        </div>
    </aside>
    
    
    @yield('body')
</div>
    
    
    <footer class="footer_area clearfix">
        <div class="container">
            <div class="row align-items-center">
                <!-- Single Widget Area -->
                <div class="col-12 col-lg-4">
                    <div class="single_widget_area">
                        <!-- Logo -->
                        <div class="footer-logo mr-50">
                            <a href="index.html"><img src="/img/logo.svg"></a>
                        </div>
                    </div>
                </div>
                <!-- Single Widget Area -->
                <div class="col-12 col-lg-8">
                    <div class="single_widget_area">
                        <!-- Footer Menu -->
                        <div class="footer_menu">
                            <nav class="navbar navbar-expand-lg justify-content-end">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#footerNavContent" aria-controls="footerNavContent" aria-expanded="false" aria-label="Toggle navigation"><i class="fa fa-bars"></i></button>
                                <div class="collapse navbar-collapse" id="footerNavContent">
                                    <ul class="navbar-nav ml-auto">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="{{action('Shop@index')}}">Inicio</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('Shop@products')}}">Productos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="@if (isset($_SESSION['product'])) {{action('Shop@product_details', ['id'=>$_SESSION['product']])}} @else # @endif">Detalles</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('Shop@cart')}}">Carrito</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{action('Shop@checkout')}}">Finalizar compra</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="/js/Template/jquery/jquery-2.2.4.min.js"></script>
        <script src="/js/Template/popper.min.js"></script>
        <script src="/js/Template/bootstrap.min.js"></script>
        <script src="/js/Template/plugins.js"></script>
        <script src="/js/Template/active.js"></script>
        @yield('footer')
    </footer>
    
    
</body>
</html>