<!DOCTYPE html>

<html lang="en">

<head>
  <!-- <meta content="text/html; charset=UTF-8"> -->
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ URL::asset('fevicol.png') }}" type="image/gif" sizes="16x16">
  <title>{{ getNameSystem() }}</title>

  <!-- Bootstrap -->
  <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ URL::asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  <!-- NProgress -->
  <link href="{{ URL::asset('vendors/nprogress/nprogress.css') }}" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  {{-- <link href="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.css') }} "
  rel="stylesheet"> --}}

  <!-- Custom Theme Style -->
  <link href="{{ URL::asset('build/css/custom.min.css') }} " rel="stylesheet">
  <!-- Own Theme Style -->
  <link href="{{ URL::asset('build/css/own.css') }} " rel="stylesheet">
  <link href="{{ URL::asset('build/css/roboto.css') }} " rel="stylesheet">

  <!-- sweetalert -->
  {{-- <link href="{{ URL::asset('vendors/sweetalert/sweetalert.css') }}"
  rel="stylesheet"
  type="text/css"> --}}

  <!-- Custom Theme Scripts -->
  <script src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ URL::asset('build/js/custom.min.js') }}" defer="defer"></script>
  <script src="{{ URL::asset('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $(".input").click(function() {
        $('.login-username label').addClass("active");
        $('.login-password label').addClass("active");
      });
    });

    // 
  </script>

  
  <style>

    .form-vertical{
        display:flex;
        flex-direction:column;
        width:500px;
    }
    .new_login_page_section{
   font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
      height: 100vh;
    }
    .new_login_page_logo{
        width:100%;
        max-width:700px;
        background-color:#2A3F54;
        height:100vh;
        text-align:center;
        padding-top:200px;
    }
    .new_login_page_logo img{
         width:400px;
    }
    .new_login_page_section{
        display:flex;
        align-items:center;
        gap:150px;
    }
    .new_input{
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ddd;
      font-size: 16px;
    }
    .new_login_btn{
         width: 100%;
      padding: 10px;
      background-color: #009DEC;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
    }
    .new_custom_forget_password{
        margin:10px;
    }
   @media (max-width: 1304px) and (min-width: 1025px) {
       
                .form-vertical{
   
        width:350px;
        margin:0px 50px;
    }
    }
    @media only screen and (max-width: 575px) {
                  .new_login_page_section{
     flex-direction:column;
    }  
        .form-vertical{
   
        width:300px;
    }
        .new_login_page_logo img{
         width:300px;
    }
      }
      @media (max-width: 1024px) and (min-width: 768px) {
            .new_login_page_section{
     flex-direction:column;
     gap:50px;
    }  
        .new_login_page_logo img{
         width:300px;
    }
    .new_login_page_logo{
        max-width:1000px;
         padding-top:100px;
    }
      }

  </style>
</head>

<script>
  $(document).ready(function() {
    $("#user_login").attr("autocomplete", "off");
    $("#user_pass").attr("autocomplete", "new-password");
  });
</script>

<body>

  
  <div class="new_login_page_section">
      <div class="new_login_page_logo">
          <img src="{{URL::asset('public/general_setting/loginlogo.png')}}"> 
      </div>
      <div class="">
       <form class="form-vertical" method="POST" action="{{ url('/login') }}">
                <input type="hidden" name="_token" value="ng6dqKQpcfVoWUABxW33aHAYV681V6asws3AxuZ0">
                {{ csrf_field() }}
                <p class="login-username">
                  <label for="user_login"> Email </label>
                  <input type="text" name="email" id="user_login" autocomplete="off" class="new_input" value="" size="20">
                  @if ($errors->has('email'))
                  <span class="help-block text-danger mt-1" style="width: 280px;">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                </p>
                <p class="login-password">
                  <label for="user_pass">Password</label>
                  <input type="password" name="password" id="user_pass" autocomplete="new-password" class="new_input" value="" size="20">
                  @if ($errors->has('password'))
                  <span class="help-block text-danger">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </p>
                <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" />&nbsp;Remember me</label>
                </p>
                

                <p class="login-submit">
                  <input type="submit" name="wp-submit" id="wp-submit" class="new_login_btn" value="Log In">
                  <input type="hidden" name="redirect_to" value=" ">

                </p>
                <!--<a class="forgot_pwd_scl new_custom_forget_password"  href="{{ url('/password/reset') }}" title="Lost Password">Forgot Password?</a>-->
              </form>
              </div>
  </div>

  @if (!empty(session('firsttime')))
  <script>
    var msg1 = "Your Installation is Successful"
    $(document).ready(function() {
      swal({
        title: msg1,

      }, function() {

        window.location.reload()
      });
    });
  </script>
  <?php Session::flush(); ?>
  @endif
</body>

</html>