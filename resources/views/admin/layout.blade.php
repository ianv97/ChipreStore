<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Chipre Store - Tienda de ropa online. Ropa importada al mejor precio. Aceptamos todas las tarjetas. Camisas, remeras, chombas, jeans, bermudas, shorts, mallas, gorras.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chipre Store | Administración</title>
        <link rel="canonical" href="chiprestore.com">
        <link rel="shortcut icon" href="../img/logo.ico">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="../font/stylesheet.css">
        <link rel="stylesheet" href="../css/mCustomScrollbar.min.css">
        <link rel="stylesheet" href="../css/sidebar.css">
        <link rel="stylesheet" href="../css/estilos.css">
        <link rel="stylesheet" href="../css/toastr.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/r-2.2.2/datatables.min.css"/><style>.menuopt:hover{color:#007bff !important;}</style>
        @yield('head')
    </head>

    <body>
        <div class="page-wrapper ice-theme toggled">
            <aside>
                <a id="show-sidebar" class="btn btn-sm btn-dark menuopt" style="z-index: 999999; background-color:#efefef">
                    <i class="fas fa-bars"></i>
                </a>
                <nav id="sidebar" class="sidebar-wrapper" style="z-index: 999999;">
                    <div class="sidebar-content">
                        <!-- TÍTULO -->
                        <div class="sidebar-brand">
                            <a href="#">MENÚ</a>
                            <div id="close-sidebar">
                                <i class="fas fa-times menuopt"></i>
                            </div>
                        </div>
                        <!-- CABECERA  -->
                        <div class="sidebar-header">
                            <div class="user-pic bhover">
                                <img id="userphoto" class="img-responsive img-rounded cursor" src="../img/users/{{$_SESSION['photo']}}">
                                <form method="post" action="{{action('UserController@change_photo')}}" id="userphotoform" name="userphotoform" enctype="multipart/form-data" style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="file" id="userphotofile" name="userphotofile" accept=".jpeg,.jpg,.png">
                                </form>
                            </div>
                            <div class="user-info">
                                <span class="user-name">
                                    <strong style="color: #bdd4de">{{$_SESSION['name']}}</strong>
                                </span>
                                <span class="user-role">{{$_SESSION['role']}}</span>
                                <span class="user-status">
                                    <i class="fa fa-circle"></i>
                                    <span>Online</span>
                                </span>
                            </div>
                            <div class="user-info" style="margin-top:10px;">
                                <a href="{{ action('UserController@edit_password')}}">
                                    <i class="fa fa-unlock"></i>
                                    <span>Cambiar contraseña</span>
                                </a>
                            </div>
                        </div>
                        <!-- MENÚ  -->
                        <div class="sidebar-menu">
                            <ul>
                                <li class="header-menu">
                                    <span>General</span>
                                </li>
                                <li>
                                    <a href="{{action('CategoryController@list_categories')}}">
                                        <i class="fas fa-object-group"></i>
                                        <span class="menuopt">Categorías</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{action('WaistController@list_waists')}}">
                                        <i class="fas fa-tape"></i>
                                        <span class="menuopt">Talles</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{action('ProductController@list_products')}}">
                                        <i class="fas fa-tshirt"></i>
                                        <span class="menuopt">Productos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{action('OfferController@list_offers')}}">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span class="menuopt">Ofertas</span>
                                    </a>
                                </li>
                            @if ($_SESSION['role'] == 'Administrador')
                                <li class="header-menu">
                                    <span>Administrador</span>
                                </li>
                                <li>
                                    <a href="{{action('UserController@list_users')}}">
                                        <i class="fas fa-users"></i>
                                        <span class="menuopt">Usuarios</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{action('SaleController@list_sales')}}">
                                        <i class="fas fa-money-bill"></i>
                                        <span class="menuopt">Ventas</span>
                                    </a>
                                </li>
                            @endif
                                
                            </ul>
                        </div>
                        <div class="sidebar-header">
                            <li style="list-style-type:none;">
                                <a href="{{ action('SessionController@logout') }}" class="thover nosub" style="color:#ef6603">
                                    <i class="fa fa-sign-out-alt"></i>
                                    <span>Cerrar Sesión</span>
                                </a>
                            </li>
                        </div>
                    </div>
                </nav>
            </aside>
            <main class="page-content">
                @yield('body')
            </main>
        </div>
    </body>

    <footer>
        <script src="../js/jquery3.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/mCustomScrollbar.min.js"></script>
        <script src="../js/sidebar.js"></script>
        <script src="../js/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/r-2.2.2/datatables.min.js"></script>
        @yield('footer')
    </footer>
</html>

<script type="text/javascript">
$('#userphoto').click(function(){
    $('#userphotofile').click();
  
    $('#userphotofile').change(function(){
        $('#userphotoform').submit();
    });
});
 </script>