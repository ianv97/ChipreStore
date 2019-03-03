
@extends('admin/layout')

@section('head')
    <link rel="stylesheet" type="text/css" href="../css/change_password.css">
@endsection


@section('body')
<div class="container-login100">
    <div class="wrap-login100 bg-dark">
        <form class="login100-form validate-form" method="POST" action="{{action('UserController@change_password')}}">
            <span class="login100-form-title" style="color:#ffffff;">
                Cambio de Contraseña
            </span>
            {{ csrf_field() }}
            <div class="wrap-input100 validate-input mb-0">
                <input class="input100" type="password" name="oldpassword" placeholder="Contraseña actual">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </span>
            </div>

            <div class="wrap-input100 validate-input mt-4 mb-0">
                <span class="badge badge-pill badge-danger" style="position: absolute; margin:15px 15px;">New</span>
                <input class="input100" type="password" name="newpassword1" placeholder="Nueva contraseña">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-input100 validate-input mt-4 mb-0">
                <span class="badge badge-pill badge-danger" style="position: absolute; margin:15px 15px;">New</span>
                <input class="input100" type="password" name="newpassword2" placeholder="Repita la nueva contraseña">
                <span class="focus-input100"></span>
            </div>

            <div class="container-login100-form-btn">
                <button type="submit" class="login100-form-btn">
                    Cambiar contraseña
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('footer')
<script type="text/javascript">
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
</script>
@endsection