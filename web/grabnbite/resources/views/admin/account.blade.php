@extends('layouts.admin')

<style type="text/css">
    
</style>
@section('content')

<!-- content -->
<div class="container py-2">
<div class="row my-2">
        <!-- edit form column -->
        <div class="col-lg-4">
            <h2 class="text-center font-weight-light">Account</h2>
        </div>
        <div class="col-lg-8">
            <div id="success"></div>
            <div id="error"></div>
        </div>
        <div class="col-lg-8 order-lg-1 personal-info">
            <form id="profile-form">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">First name</label>
                    <div class="col-lg-9">
                        <input id="fname" name="fname" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Last name</label>
                    <div class="col-lg-9">
                        <input id="lname" name="lname" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Address</label>
                    <div class="col-lg-9">
                        <input id="address" name="address" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Email</label>
                    <div class="col-lg-9">
                        <input id="email" name="email" class="form-control" type="email" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Phone</label>
                    <div class="col-lg-9">
                        <input id="phone" name="phone" class="form-control" type="text" value="" />
                    </div>
                </div>
                 <div class="form-group row">
                    <div class="col-lg-9 ml-auto text-right">
                        <button id="btnSave" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>

            <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Current Password</label>
                    <div class="col-lg-9">
                        <input id="current" class="form-control" type="password" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">New Password</label>
                    <div class="col-lg-9">
                        <input id="newpass" class="form-control" type="password" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Confirm password</label>
                    <div class="col-lg-9">
                        <input id="confirm" class="form-control" type="password" />
                    </div>
                </div>
                 <div class="form-group row">
                    <div class="col-lg-9 ml-auto text-right">
                        <input id="btnChange" type="submit" class="btn btn-primary" value="Change Password" />
                    </div>
                </div>
       
        </div>
        <div class="col-lg-4 order-lg-0 text-center">

            <img id="myImage" class="mx-auto img-fluid rounded-circle" alt="avatar" width="172" height="172" />
           
            <h6 class="my-4">Upload a new photo</h6>
            <form id="image_form" enctype="multipart/form-data">
                <div id="error"></div>
            <div class="input-group px-xl-4">
                <div class="custom-file">
                    <input type="file" id="inputGroupFile02" name="image">
                    <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose file</label>
                </div>
                <div class="input-group-append">
                    <button class="btn btn-secondary"><i class="fa fa-upload"></i></button>
                </div>
            </div>
          </form>
        </div>
    </div>
</div>
</div>
<!-- end -->
@stop
@section('footer')

<!-- scripts -->
<script type="text/javascript">
    
    $(document).ready(function(){

        getUserProfile();

         /* select and display image */
     $('#inputGroupFile02').on('change',function(){
        //get the file name
        var fileName = $(this).val();

        if(Math.round(this.files[0].size / (1024 * 1024) > 2)) {

            $('#error').addClass('text-danger');
            $('#error').html('Please upload file less than 2MB. Thanks!!');
            $(this).val('');
        } else {
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        }
    });
    /* end */

    /* upload image */
    $('#image_form').submit(function(e) {
        e.preventDefault();

        var image_name = $('#inputGroupFile02').val();

        if(image_name=="") {
            alert("Please Select Image");
            return false;
        } else {
            var extension = $('#inputGroupFile02').val().split('.').pop().toLowerCase();
            if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                 $('#error').addClass('text-danger');
                 $('#error').html('Invalid File');
                 return false;
            } else {
                $.ajax({
                    url: "{{route('admin-image-upload')}}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                      console.log(data);
                      getUserProfile();
                      $('.custom-file-label').html('Choose File');
                    }
                });
            }
        }
    });
    /* end */

    /* save profile */
    $('#profile-form').submit(function(e){

        e.preventDefault();

        $('.loading').css('display', 'block');

        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var address = $('#address').val();
        var email = $('#email').val();
        var phone = $('#phone').val();

        if(fname==""||lname==""||address==""||email==""||phone=="") {

            alert('This is required');

            return false;
        }

        $.ajax({

            url: '{{route('admin-save-profile')}}',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(data) {
                if(data.success==1) {
                    $('#success').addClass('alert alert-success alert-dismissible');
                    $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully saved');
                    console.log(data);
                    getUserProfile();
                }
            }
        });
    });
    /* end */

     /* change password */
    $('#btnChange').click(function(){

        var current = $('#current').val();
        var newpass = $('#newpass').val();
        var confirm = $('#confirm').val();

        if(current==''||newpass==''||confirm=='') {

            if(current=='') {

                alert('Current password is required');
            } else if(newpass=='') {

                alert('New password is required');
            } else {

                alert('Confirm password is required');
            }
            return false;
        }

        if(newpass!=confirm) {

            alert('Confirm and new password do not match');

            return false;
        }

        $.ajax({

            url: '{{route('admin-change-password')}}',
            method: 'POST',
            async: false,
            data: {
                current: current,
                newpass: newpass
            },
            success: function(data) {
                console.log(data);

                if(data.success==1) {
                    $('#current').val('');
                    $('#newpass').val('');
                    $('#confirm').val('');
                    $('#success').addClass('alert alert-success alert-dismissible');
                    $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Password has been changed.');
                } else {
                   $('#error').addClass('alert alert-danger alert-dismissible');
                   $('#error').html('<button type="button" class="close" data-dismiss="alert">&times;</button><strong>Failed!</strong> This is not your current password.');
                }
            },
            error: function(jqXHR, status, error) {
                console.log(status);
                console.log(error);
            }
        });
    });
    /* end */
    });

    function getUserProfile() {

        $.ajax({
            url: '{{route('admin-get-profile')}}',
            method: 'GET',
            success: function(data) {
                console.log(data);
                if(data.image!="") {
                    $('#myImage').attr('src', '{{asset('images/users')}}/'+data.image);
                } else {
                    $('#myImage').attr('src', '{{asset('images/users')}}/user_none.png');
                }

                $('#fname').val(data.fname);
                $('#lname').val(data.lname);
                $('#address').val(data.address);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
            }
        });
    }
</script>
<!-- end -->
@stop
