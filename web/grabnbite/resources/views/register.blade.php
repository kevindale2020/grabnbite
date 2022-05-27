@extends('layouts.app')

<style type="text/css">
    /*--thank you pop starts here--*/
.thank-you-pop{
    width:100%;
    padding:20px;
    text-align:center;
}
.thank-you-pop img{
    width:76px;
    height:auto;
    margin:0 auto;
    display:block;
    margin-bottom:25px;
}

.thank-you-pop h1{
    font-size: 42px;
    margin-bottom: 25px;
    color:#5C5C5C;
}
.thank-you-pop p{
    font-size: 20px;
    margin-bottom: 27px;
    color:#5C5C5C;
}
.thank-you-pop h3.cupon-pop{
    font-size: 25px;
    margin-bottom: 40px;
    color:#222;
    display:inline-block;
    text-align:center;
    padding:10px 20px;
    border:2px dashed #222;
    clear:both;
    font-weight:normal;
}
.thank-you-pop h3.cupon-pop span{
    color:#03A9F4;
}
.thank-you-pop a{
    display: inline-block;
    margin: 0 auto;
    padding: 9px 20px;
    color: #fff;
    text-transform: uppercase;
    font-size: 14px;
    background-color: #8BC34A;
    border-radius: 17px;
}
.thank-you-pop a i{
    margin-right:5px;
    color:#fff;
}
#ignismyModal .modal-header{
    border:0px;
}
/*--thank you pop ends here--*/
</style>
@section('content')
<div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/logo-main-nobg.png" alt="CoolAdmin">
                            </a>
                        </div>
                        <div class="login-form">
                            <div id="regErr"></div>
                            <form id="regForm" method="post">
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
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input class="au-input au-input--full" id="password2" type="password" name="password2" placeholder="Password">
                                    <div id="password2Err"></div>
                                </div>
                               {{--  <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="aggree">Agree the terms and policy
                                    </label>
                                </div> --}}
                                <button class="au-btn au-btn--block au-btn--green m-b-20" id="btnRegister" name="btnRegister">register</button>
                                {{-- <div class="social-login-content">
                                    <div class="social-button">
                                        <button class="au-btn au-btn--block au-btn--blue m-b-20">register with facebook</button>
                                        <button class="au-btn au-btn--block au-btn--blue2">register with twitter</button>
                                    </div>
                                </div> --}}
                            </form>
                            <div class="register-link">
                                <p>
                                    Already have account?
                                    <a href="{{route('guest-index')}}">Sign In</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Model Popup starts-->
<div class="container">
    <div class="row">
        <div class="modal fade" id="successModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
          
                    <div class="modal-body">
                       
            <div class="thank-you-pop">
              <img src="http://goactionstations.co.uk/wp-content/uploads/2017/03/Green-Round-Tick.png" alt="">
              <h1>Thanks For Signing Up!</h1>
              <p>A verification link has been sent to your email. Please verify your account.</p>
              
            </div>
                         
                    </div>
          
                </div>
            </div>
        </div>
    </div>
</div>
<!--Model Popup ends-->
@stop

@section('footer')
<script type="text/javascript">
    
    $(document).ready(function(){

        $('#regForm').submit(function(e){

            e.preventDefault();

            var email = $('#email').val();
            var pass = $('#password').val();
            var pass2 = $('#password2').val();

            if(!validateEmail() | !validatePassword() | !validatePassword2()) {
                return;
            }

            if(!validatePasswodMatch()) {
                return;
            }

            $.ajax({

                url: '{{route('guest-register-save')}}',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    
                    if(data.success==1) {
                        $('#email').val('');
                        $('#password').val('');
                        $('#password2').val('');
                        $('#successModal').modal('show');
                    } else {

                        $('#regErr').addClass('alert alert-danger');
                        $('#regErr').html(data.message);
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

    function validatePassword2() {

        var pass2 = $('#password2').val();

        if(pass2=='') {
            $('#password2Err').addClass('alert alert-danger');
            $('#password2Err').html('This is  required');
            return false;
        } else {
            $('#password2Err').removeClass('alert alert-danger');
            $('#password2Err').html('');
            return true;
        }
    }

    function validatePasswodMatch() {

        var pass = $('#password').val();
        var pass2 = $('#password2').val();

        if(pass!=pass2) {
            $('#passwordErr').addClass('alert alert-danger');
            $('#passwordErr').html('Password not match');
            $('#password2Err').addClass('alert alert-danger');
            $('#password2Err').html('Password not match');
            return false;
        } else {
            $('#passwordErr').removeClass('alert alert-danger');
            $('#passwordErr').html('');
            $('#password2Err').removeClass('alert alert-danger');
            $('#password2Err').html('');
            return true;
        }
    }
</script>
@stop