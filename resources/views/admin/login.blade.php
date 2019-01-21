<!DOCTYPE html>
<html>
<head>
    <title>Chipre Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.ico" rel="shortcut icon">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main class="container login-container">
        <div class="row">
            <div class="col-8 offset-2 col-lg-6 offset-lg-3 login-form">
                <div class="login-logo">
                    <img src="../img/logo.jpg" alt=""/>
                </div>
                <h3>Sección administrativa</h3>
                <form method="POST" action="{{action('Session@admin_authenticate')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" placeholder="Email *" data-rule="email" data-msg="Por favor ingrese un email válido." />
                    </div>
                    @foreach ($errors->get('email') as $message)
                    <label class="mt-0 mb-2 text-white">
                            {{ $message }}
                    </label>
                    @endforeach
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Contraseña *"/>
                    </div>
                    @foreach ($errors->get('password') as $message)
                    <label class="mt-0 mb-2 text-white">
                            {{ $message }}
                    </label>
                    @endforeach
                    @foreach ($errors->get('auth') as $message)
                    <label class="mt-0 mb-2 text-danger">
                            {{ $message }}
                    </label>
                    @endforeach
                    <div class="form-group">
                        <button type="submit" class="btnSubmit"> Ingresar </button>
                    </div>
                    <div class="form-group">
                        <a href="#" class="btnForgetPwd">¿Olvidó su contraseña?</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>




<style>
    .login-container{
        margin-top: 15%;
        margin-bottom: 5%;
    }
    .login-logo{
        position: relative;
        margin-left: 37%;
    }
    .login-logo img{
        position: absolute;
        width: 50%;
        margin-top: -70%;
        background: #ffffff;
        border-radius: 5rem;
        padding: 5%;
    }
    .login-form{
        padding: 9% 9% 1% 9%;
        background: #343a40;
        box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 9px 26px 0 rgba(0, 0, 0, 0.19);
    }
    .login-form h3{
        text-align: center;
        margin-bottom:12%;
        color: #fff;
    }
    .btnSubmit{
        font-weight: 600;
        width: 50%;
        color: #282726;
        background-color: #ffffff;
        border: none;
        border-radius: 1.5rem;
        padding:2%;
        margin-left: 25%;
        margin-top: 5%;
    }
    .btnSubmit:hover{
        color: #ffffff;
        background-color: #007bff;
        cursor: pointer;
    }
    .btnForgetPwd{
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
        margin-left: 26%;
    }
    .btnForgetPwd:hover{
        text-decoration: none;
        color: #007bff;
    }
    
    
    @media (min-width: 1601px){
        body{
            background: url(../img/bg/loginbg1920.jpg) no-repeat center center fixed rgba(0, 0, 0, 0.5);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    }

    @media (max-width: 1600px){
        body{
            background: url(../img/bg/loginbg1600.jpg) no-repeat center center fixed rgba(0, 0, 0, 0.5);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    }

    @media (max-width: 1280px){
        body{
            background: url(../img/bg/loginbg1280.jpg) no-repeat center center fixed rgba(0, 0, 0, 0.5);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    }

    @media (max-width: 1024px){
        body{
            background: url(../img/bg/loginbg1024.jpg) no-repeat center center fixed rgba(0, 0, 0, 0.5);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    }

    @media (max-width: 800px){
        body{
            background: url(../img/bg/loginbg800.jpg) no-repeat center center fixed rgba(0, 0, 0, 0.5);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    }

    @media (max-width: 512px){
        body{
            background: url(../img/bg/loginbg512.jpg) no-repeat center center fixed rgba(0, 0, 0, 0.5);
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    }
</style>

</html>