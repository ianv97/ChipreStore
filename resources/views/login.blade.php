@extends('layout')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/toastr.min.css">
@endsection

@section('body')
<div class="cart-table-area section-padding-100">
    <div class="container-fluid">
        <div class="col-12 col-lg-10 col-xl-8 mx-auto">
            <div class="form-structor">
                <form method="post" action="{{action('CustomerController@signup')}}">
                    {{ csrf_field() }}
                    <div class="signup">
                        <h2 class="form-title" id="signup">Registrarse</h2>
                        <div class="form-holder row justify-content-center signup-form">
                            <div class="col-8 my-3 webflow-style-input">
                                <input type="email" id="email" name="email" placeholder="Email" value="{{old('email')}}" required>
                            </div>
                            <div class="col-5 mb-3 webflow-style-input">
                                <input type="password" id="password" name="password" pattern=".{6,}" placeholder="Contraseña" oninvalid="setCustomValidity('Debe contener al menos 6 caracteres.')" oninput="setCustomValidity('')" required>
                            </div>
                            <div class="col-5 mb-3 ml-3 webflow-style-input">
                                <input type="password" id="repeat_password" name="repeat_password" pattern=".{6,}" placeholder="Repita su contraseña" oninvalid="setCustomValidity('Debe contener al menos 6 caracteres.')" oninput="setCustomValidity('')" required>
                            </div>
                            <div class="col-5 mb-3 webflow-style-input">
                                <input type="text" id="first_name" name="first_name" placeholder="Nombre" value="{{old('first_name')}}" required>
                            </div>
                            <div class="col-5 mb-3 ml-3 webflow-style-input">
                                <input type="text" id="last_name" name="last_name" placeholder="Apellido" value="{{old('last_name')}}" required>
                            </div>
                            <div class="col-5 mb-3 webflow-style-input">
                                <select class="w-100" id="province" name="province" required>
                                    <option></option>
                                    @foreach (\App\Province::all() as $province)
                                    <option value="{{$province->id}}">{{$province->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5 mb-3 ml-3 webflow-style-input">
                                <select class="w-100" id="city" name="city" required>
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-8 mb-3 webflow-style-input">
                                <input type="text" id="address" name="address" placeholder="Dirección" value="{{old('address')}}" required>
                            </div>
                            <div class="col-md-6 mb-3 webflow-style-input">
                                <input type="text" id="phone" name="phone" placeholder="Teléfono" value="{{old('phone')}}" required>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Registrarse</button>
                    </div>
                </form>
                <form method="post" action="{{action('SessionController@authenticate')}}">
                    {{ csrf_field() }}
                    <div class="login slide-up">
                        <div class="center">
                            <h2 class="form-title" id="login">Ingresar</h2>
                            <div class="form-holder row justify-content-center login-form">
                                <div class="col-8 my-3 webflow-style-input">
                                    <input type="email" name='email' placeholder="Email" data-rule="email" data-msg="Por favor ingrese un email válido." required>
                                </div>
                                <div class="col-8 mb-3 webflow-style-input">
                                    <input type="password" name='password' placeholder="Contraseña" required>
                                </div>
                                @foreach ($errors->get('auth') as $message)
                                <label class="mt-0 mb-2 text-danger">
                                        {{ $message }}
                                </label>
                                @endforeach
                            </div>
                            <button type="submit" class="submit-btn">Ingresar</button>
                            <div class="form-group d-flex mx-auto justify-content-center">
                                <a href="#" class="btnForgetPwd">¿Olvidó su contraseña?</a>
                            </div>
                        </div>
                    </div>
                </form>
                <form method='POST' action="{{route('customer.password.email')}}" id="reset_password">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/toastr.min.js"></script>
<script>
    $('.btnForgetPwd').popover({
    title: "<div class='d-flex justify-content-center'><label>Ingrese su email</label></div>",
    content: "\
        <input type='email' name='email' form='reset_password' class='form-control d-flex justify-content-center' placeholder='Email' required/>\n\
        <button type='submit' form='reset_password' class='btn btn-primary d-flex mx-auto justify-content-center mt-1'>Confirmar</button>\n\
    </div>",
    html: true,
    placement: "bottom"});
    
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (session('status'))
        toastr.success("{{ session('status') }}");
    @endif
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
    
    
    $(document).ready(function() {
        $('#province').select2({
            placeholder: "Provincia",
            allowClear: true
        });
        $('#city').select2({
            placeholder: "Ciudad",
            allowClear: true
        });
    });

   $('#province').change(function(){
        $('#city').children().remove();
        $('#city').append('<option></option>');
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        $.ajax({
          type:"POST",
          data:"province_id=" + $('#province').val(),
          url:"/ajax/find_cities",
            success:function(r){
                r.forEach(function(city) {
                    $('#city').append("<option ".concat("value=",city['id'], ">", city['name'], "</option>"));
                });
            }
        });
    });
</script>

<script>
const loginBtn = document.getElementById('login');
const signupBtn = document.getElementById('signup');

loginBtn.addEventListener('click', (e) => {
	let parent = e.target.parentNode.parentNode;
	Array.from(e.target.parentNode.parentNode.classList).find((element) => {
		if(element !== "slide-up") {
			parent.classList.add('slide-up');
		}else{
			signupBtn.parentNode.classList.add('slide-up');
			parent.classList.remove('slide-up');
		}
	});
});

signupBtn.addEventListener('click', (e) => {
	let parent = e.target.parentNode;
	Array.from(e.target.parentNode.classList).find((element) => {
		if(element !== "slide-up") {
			parent.classList.add('slide-up');
		}else{
			loginBtn.parentNode.parentNode.classList.add('slide-up');
			parent.classList.remove('slide-up');
		}
	});
});
</script>



<style>
.btnForgetPwd{
    color: #ffffff;
    font-size:0.8rem;
    font-weight: 600;
    text-decoration: none;
}
.btnForgetPwd:hover{
    color: #fbb710;
    font-size:0.8rem;
    font-weight: 600;
    text-decoration: none;
}
.btnForgetPwd:focus{
    color: #fbb710;
    font-size:0.8rem;
    font-weight: 600;
    text-decoration: none;
}
@-webkit-keyframes gradient {
  0% {background-position: 0 0;}
  100% {background-position: 100% 0;}
}
@keyframes gradient {
  0% {background-position: 0 0;}
  100% {background-position: 100% 0;}
}
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
    transition-delay: 3600s;
}
.webflow-style-input {
  border-radius: 2px;
  padding: 0.5rem 1rem;
  background: rgba(57, 63, 84, 0.8);
}
.webflow-style-input input{
  border-style: none;
  background: transparent;
  outline: none;
  flex-grow: 1;
  color: #BFD2FF;
  vertical-align: middle;
}
.webflow-style-input input::-webkit-input-placeholder {
  color: #7881A1;
}

@import url("https://fonts.googleapis.com/css?family=Fira+Sans");

.signup-form{
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  background-color: #212529;
}
.form-structor {
  font-family: "Fira Sans", Helvetica, Arial, sans-serif;
  background-color: #fbb710;
  border-radius: 15px;
  height: 750px;
  width: 100%;
  position: relative;
  overflow: hidden;
}
.form-structor::after {
  content: '';
  opacity: .8;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: #fbb710;
}
.form-structor .signup {
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  width: 65%;
  z-index: 5;
  -webkit-transition: all .3s ease;
}
.form-structor .signup.slide-up {
  top: 5%;
  -webkit-transform: translate(-50%, 0%);
  -webkit-transition: all .3s ease;
}
.form-structor .signup.slide-up .form-holder, .form-structor .signup.slide-up .submit-btn {
  opacity: 0;
  visibility: hidden;
}
.form-structor .signup.slide-up .form-title {
  font-size: 1em;
  cursor: pointer;
}
.form-structor .signup.slide-up .form-title span {
  margin-right: 5px;
  opacity: 1;
  visibility: visible;
  -webkit-transition: all .3s ease;
}
.form-structor .signup .form-title {
  color: #212529;
  font-size: 1.7em;
  text-align: center;
  font-weight: 600;
}
.form-structor .signup .form-title span {
  color: rgba(0, 0, 0, 0.4);
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all .3s ease;
}
.form-structor .signup .form-holder {
  border-radius: 15px;
  overflow: hidden;
  margin-top: 50px;
  opacity: 1;
  visibility: visible;
  -webkit-transition: all .3s ease;
}
.form-structor .signup .form-holder .input {
  border: 0;
  outline: none;
  box-shadow: none;
  display: block;
  height: 30px;
  line-height: 30px;
  padding: 8px 15px;
  border-bottom: 1px solid #eee;
  width: 100%;
  font-size: 12px;
}
.form-structor .signup .form-holder .input:last-child {
  border-bottom: 0;
}
.form-structor .signup .form-holder .input::-webkit-input-placeholder {
  color: rgba(0, 0, 0, 0.4);
}
.form-structor .signup .submit-btn {
  background-color: #fff;
  color: #212529;
  border: 0;
  border-radius: 15px;
  display: block;
  margin: 15px auto;
  padding: 15px 45px;
  width: 50%;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  opacity: 1;
  visibility: visible;
  -webkit-transition: all .3s ease;
}
.form-structor .signup .submit-btn:hover {
  transition: all .3s ease;
  background-color: #212529;
  color: #fbb710;
}
.login{
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  background-color: #212529;
}
.form-structor .login {
  position: absolute;
  top: 20%;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 5;
  -webkit-transition: all .3s ease;
}
.form-structor .login::before {
  content: '';
  position: absolute;
  left: 50%;
  top: -20px;
  -webkit-transform: translate(-50%, 0);
  background-color: #212529;
  width: 200%;
  height: 250px;
  border-radius: 50%;
  z-index: 4;
  -webkit-transition: all .3s ease;
}
.form-structor .login .center {
  position: absolute;
  top: calc(50% - 10%);
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  width: 65%;
  z-index: 5;
  -webkit-transition: all .3s ease;
}
.form-structor .login .center .form-title {
  color: #fff;
  font-size: 1.7em;
  text-align: center;
}
.form-structor .login .center .form-title span {
  color: rgba(0, 0, 0, 0.4);
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all .3s ease;
}
.form-structor .login .center .form-holder {
  border-radius: 15px;
  background-color: transparent;
  overflow: hidden;
  margin-top: 50px;
  opacity: 1;
  visibility: visible;
  -webkit-transition: all .3s ease;
}
.form-structor .login .center .form-holder .input {
  border: 0;
  outline: none;
  box-shadow: none;
  display: block;
  height: 30px;
  line-height: 30px;
  padding: 8px 15px;
  border-bottom: 1px solid #eee;
  width: 100%;
  font-size: 12px;
}
.form-structor .login .center .form-holder .input:last-child {
  border-bottom: 0;
}
.form-structor .login .center .form-holder .input::-webkit-input-placeholder {
  color: rgba(0, 0, 0, 0.4);
}
.form-structor .login .center .submit-btn {
  background-color: #fff;
  color: #000;
  border: 0;
  border-radius: 15px;
  display: block;
  margin: 15px auto;
  padding: 15px 45px;
  width: 50%;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  opacity: 1;
  visibility: visible;
  -webkit-transition: all .3s ease;
}
.form-structor .login .center .submit-btn:hover {
  transition: all .3s ease;
  background-color: #fbb710;
  color: #212529;
}
.form-structor .login.slide-up {
  top: 90%;
  -webkit-transition: all .3s ease;
}
.form-structor .login.slide-up .center {
  top: 10%;
  -webkit-transform: translate(-50%, 0%);
  -webkit-transition: all .3s ease;
}
.form-structor .login.slide-up .form-holder, .form-structor .login.slide-up .submit-btn {
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all .3s ease;
}
.form-structor .login.slide-up .form-title {
  font-size: 1em;
  margin: 0;
  padding: 0;
  cursor: pointer;
  -webkit-transition: all .3s ease;
}
.form-structor .login.slide-up .form-title span {
  margin-right: 5px;
  opacity: 1;
  visibility: visible;
  -webkit-transition: all .3s ease;
}
</style>
@endsection
