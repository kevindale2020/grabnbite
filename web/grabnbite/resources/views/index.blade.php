@extends('layouts.app')

@section('content')
<div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/logo-main-nobg.png" alt="CoolAdmin">
                            </a>
                        </div>
                        <div class="login-form">
                            <form id="loginForm" method="post">
                                <div id="loginErr"></div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="au-input au-input--full" id="email" type="email" name="email" placeholder="Email">
                                    <div id="emailErr"></div>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" id="password" type="password" name="password" placeholder="Password">
                                    <div id="passwordErr"></div>
                                </div>
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" id="remember" value="1">Remember Me
                                    </label>
                                    <label>
                                        <a href="{{route('password.request')}}">Forgotten Password?</a>
                                    </label>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" id="btnLogin" name="btnLogin">sign in</button>
                               {{--  <div class="social-login-content">
                                    <div class="social-button">
                                        <button class="au-btn au-btn--block au-btn--blue m-b-20">sign in with facebook</button>
                                        <button class="au-btn au-btn--block au-btn--blue2">sign in with twitter</button>
                                    </div>
                                </div> --}}
                            </form>
                            <div class="register-link">
                                <p>
                                    Don't you have account?
                                    <a href="{{route('guest-register')}}">Sign Up Here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
@stop

@section('footer')
<script type="text/javascript">
    
    $(document).ready(function(){

        $('#loginForm').submit(function(e){

            e.preventDefault();

            var email = $('#email').val();
            var pass = $('#password').val();

            if(!validateEmail() | !validatePassword()) {
                return;
            }

            $.ajax({

                url: '{{route('guest-login')}}',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    
                    if(data.success==1) {

                      if(data.verified==1) {
                         window.location.href='{{route('admin-index')}}';
                      } else {
                         window.location.href='/notverified';
                      }
                    } else {
                        
                        $('#loginErr').addClass('alert alert-danger');
                        $('#loginErr').html(data.message);
                    }
                },
                error: function(jqXHR, status, error) {
                    console.log(status);
                    console.log(error);
                }
            });
        });
    });

    function validateEmail() {

        var email = $('#email').val();

        if(email=='') {
            $('#emailErr').addClass('alert alert-danger');
            $('#emailErr').html('This is required');
            return false;
        } else {
            $('#emailErr').removeClass('alert alert-danger');
            $('#emailErr').html('');
            return true;
        }
    }

    function validatePassword() {

        var pass = $('#password').val();

        if(pass=='') {
            $('#passwordErr').addClass('alert alert-danger');
            $('#passwordErr').html('This is required');
            return false;
        } else {
            $('#passwordErr').removeClass('alert alert-danger');
            $('#passwordErr').html('');
            return true;
        }
    }
</script>
@stop