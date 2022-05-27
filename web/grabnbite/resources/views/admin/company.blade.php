@extends('layouts.admin')

<style type="text/css">
     #map {
     height: 350px;
     z-index: 1;
  }
</style>
@section('content')
 <!-- map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>


<!-- content -->
<div class="container py-2">
<div class="row my-2">
        <!-- edit form column -->
        <div class="col-lg-4">
            <h2 class="text-center font-weight-light">Business Profile</h2>
        </div>
        <div class="col-lg-8">
            <div id="success"></div>
            <div id="error"></div>
        </div>
        <div class="col-lg-8 order-lg-1 personal-info">
            <form id="company-profile-form" method="post"
            enctype="multipart/form-data">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                    <div class="col-lg-9">
                        <input id="cname" name="cname" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Contact</label>
                    <div class="col-lg-9">
                        <input id="ccontact" name="ccontact" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">About</label>
                    <div class="col-lg-9">
                        <input id="description" name="description" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Location</label>
                    <div class="col-lg-9">
                        <input id="location" name="location" class="form-control" type="text" value="" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Latitude</label>
                    <div class="col-lg-9">
                        <input id="lat" name="lat" class="form-control" type="text" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Longitude</label>
                    <div class="col-lg-9">
                        <input id="lng" name="lng" class="form-control" type="text" />
                    </div>
                </div>
                <div id="map_section" class="form-group row">
                    <label class="col-lg-3 col-form-label form-control-label">Map</label>
                    <div class="col-lg-9">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="file-input" class="col-lg-3 col-form-label form-control-label">Business Permit</label>
                    <div class="col-lg-9">
                        <input type="file" id="doc1" name="doc1" class="form-control-file">
                        <small id="doc1-value" class="help-block form-text"></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="file-input" class="col-lg-3 col-form-label form-control-label">DTI Certification</label>
                    <div class="col-lg-9">
                        <input type="file" id="doc2" name="doc2" class="form-control-file">
                        <small id="doc2-value" class="help-block form-text"></small>
                    </div>
                </div>
                 <div class="form-group row">
                    <label for="file-input" class="col-lg-3 col-form-label form-control-label">DTI Form (Form 9, 13 or 49)</label>
                    <div class="col-lg-9">
                        <input type="file" id="doc3" name="doc3" class="form-control-file">
                        <small id="doc3-value" class="help-block form-text"></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="file-input" class="col-lg-3 col-form-label form-control-label">Valid ID</label>
                    <div class="col-lg-9">
                        <input type="file" id="doc4" name="doc4" class="form-control-file">
                        <small id="doc4-value" class="help-block form-text"></small>
                    </div>
                </div>
                 <div class="form-group row">
                    <div class="col-lg-9 ml-auto text-right">
                        <button id="btnSave" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
       
        </div>
        <div class="col-lg-4 order-lg-0 text-center">

            <img id="myImage" class="mx-auto img-fluid rounded-circle" alt="avatar" width="172" height="172" />
           
            <h6 class="my-4">Business Logo</h6>
            <form id="company_image_form" enctype="multipart/form-data">
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

    var map = null;
    
    $(document).ready(function(){

        getCompanyProfile();
        // loadMap();

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

     /* allow numeric only */
    setInputFilter(document.getElementById("lat"), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
    setInputFilter(document.getElementById("lng"), function(value) {
      return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
    });
    /* end */

    /* upload image */
    $('#company_image_form').submit(function(e) {
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
                    url: '{{route('admin-company-image-upload')}}',
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                      console.log(data);
                      getCompanyProfile();
                      $('.custom-file-label').html('Choose File');
                    }
                });
            }
        }
    });
    /* end */

    /* save profile */
    $('#company-profile-form').submit(function(e){

        e.preventDefault();

        var name = $('#cname').val();
        var contact = $('#ccontact').val();
        var desc = $('#description').val();
        var location = $('#location').val();
        var lat = $('#lat').val();
        var lng = $('#lng').val();

        if(name==''||contact==''||desc==''||location==''||lat==''||lng=='') {

            alert('All fields are required');

            return false;
        }

        $.ajax({

            url: '{{route('admin-save-company')}}',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(data) {
                if(data.success==1) {
                    $('#success').addClass('alert alert-success alert-dismissible');
                    $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully saved');
                    $('#company-profile-form')[0].reset();
                    getCompanyProfile();
                }
            }
        });
    });
    /* end */
    });

    function getCompanyProfile() {

        $.ajax({
            url: '{{route('admin-get-company')}}',
            method: 'GET',
            success: function(data) {
                console.log(data);

                if(data.success==1) {
                    $('#myImage').attr('src', '{{asset('images/company')}}/'+data.image);
                    $('#cname').val(data.name);
                    $('#ccontact').val(data.contact);
                    $('#description').val(data.desc);
                    $('#location').val(data.location);
                    $('#lat').val(data.lat);
                    $('#lng').val(data.lng);

                    var value = (data.business_permit!="") ? '<a href="{{asset('documents')}}/'+data.business_permit+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.business_permit+'</a>' : '';

                    var value2 = (data.dti_cert!="") ? '<a href="{{asset('documents')}}/'+data.dti_cert+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.dti_cert+'</a>' : '';

                    var value3 = (data.dti_form!="") ? '<a href="{{asset('documents')}}/'+data.dti_form+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.dti_form+'</a>' : '';

                    var value4 = (data.valid_id!="") ? '<a href="{{asset('documents')}}/'+data.valid_id+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.valid_id+'</a>' : '';

                    $('#doc1-value').html(value);
                    $('#doc2-value').html(value2);
                    $('#doc3-value').html(value3);
                    $('#doc4-value').html(value4);
                    $('#map_section').show();
                    loadMap(data.lat,data.lng);
                } else {
                    $('#myImage').attr('src', '{{asset('images/company')}}/company_none.png');
                    $('#map_section').hide();
                }
            }
        });
    }

      // Restricts input for the given textbox to the given inputFilter function.
function setInputFilter(textbox, inputFilter) {
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    textbox.addEventListener(event, function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  });
}
 function loadMap(lat, lng) {

       /* map */
       if(map != undefined) map.remove();
       
        map = L.map('map');

        L.tileLayer('https://api.maptiler.com/maps/streets/{z}/{x}/{y}.png?key=wNvVC9jSjaMiVJj0T1sK', {
            attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
        }).addTo(map);

        map.setView([lat,lng], 15);
        var marker = L.marker([lat,lng]).addTo(map);
        /* end */
    }

</script>
<!-- end -->
@stop
