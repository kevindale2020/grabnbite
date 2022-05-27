@extends('layouts.admin')

<style type="text/css">
  .role.driver {
    background: #5cb85c;
  }
   #map {
     height: 350px;
     z-index: 1;
  }
  .modal-lg {
    max-width: 930px !important;
    width: 930px !important;
  }
</style>
@section('content')
 <!-- map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<!-- content -->
<div class="row m-t-30">

    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">users</h2>
        </div>
         <!-- DATA TABLE-->
         <div class="table-responsive m-b-40" style="margin-top: 16px;">
            <div id="users"></div>                           
         </div>
    </div>
</div>

<!-- end -->

       <!-- User Details Modal -->
  <div class="modal fade" id="userModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="title_owner"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title mb-4">
                            <div class="d-flex justify-content-start">
                                <div class="image-container">
                                    <img src="" id="imgProfile" style="width: 150px; height: 150px;" class="img-thumbnail" />
                                </div>
                                <div class="userData ml-3">
                                    <h2 class="d-block" style="font-size: 1.5rem; font-weight: bold"><a id="main_header_name" href="javascript:void(0);"></a></h2>
                                    <h6 class="d-block"><a href="javascript:void(0)">1,500</a> Video Uploads</h6>
                                    <h6 class="d-block"><a href="javascript:void(0)">300</a> Blog Posts</h6>
                                </div>
                                <div class="ml-auto">
                                    <input type="button" class="btn btn-primary d-none" id="btnDiscard" value="Discard Changes" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo">Basic Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" style="display: none;" id="connectedServices-tab" data-toggle="tab" href="#connectedServices" role="tab" aria-controls="connectedServices">Business Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" style="display: none;" id="connectedServices-tab2" data-toggle="tab" href="#connectedServices2" role="tab" aria-controls="connectedServices2">Driver Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" style="display: none;" id="connectedServices-tab3" data-toggle="tab" href="#connectedServices3" role="tab" aria-controls="connectedServices3">Reviews</a>
                                    </li>
                                </ul>
                                <div class="tab-content ml-1" id="myTabContent">
                                    <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
                                        

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Full Name</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_fullname">
                                         
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Address</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_address">
                                                
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Email</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_email"></div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Contact</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_contact">
                                              
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Created</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_created">
                                              
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Updated</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_updated">
                                              
                                            </div>
                                        </div>
                                    </div>
                                     <div class="tab-pane fade" id="connectedServices" role="tabpanel" aria-labelledby="ConnectedServices-tab">
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Logo</label>
                                            </div>
                                            <div class="col-md-8 col-6">
                                                <div class="image-container" id="o_business_logo">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Name</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_business_name">
                                            
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Description</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_business_desc">
                        
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Location</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_business_location">
                        
                                            </div>
                                        </div>
                                        <hr/>
                                         <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Map</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="map">
                        
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Business Permit</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_business_permit">
                        
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">DTI Certification</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_dti_cert">
                        
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">DTI Form</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_dti_form">
                        
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Valid ID</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="o_valid_id">
                        
                                            </div>
                                        </div>
                                        <hr />
                                    </div>

                                     <div class="tab-pane fade" id="connectedServices2" role="tabpanel" aria-labelledby="ConnectedServices-tab">
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Vehicle Type</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_vehicle_type">
                                            
                                            </div>
                                        </div>
                                         <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Vehicle Color</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_vehicle_color">
                                            
                                            </div>
                                        </div>
                                         <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Vehicle Plate No.</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_vehicle_plate_no">
                                            
                                            </div>
                                        </div>
                                         <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">TIN (Tax Identification Number)</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_tin">
                                            
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">BIR Form (1902 or 1904)</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_bir_form">
                        
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Government Issued ID</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_id">
                        
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-sm-3 col-md-2 col-5">
                                                <label style="font-weight:bold;">Driver's License</label>
                                            </div>
                                            <div class="col-md-8 col-6" id="driver_license">
                        
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                    <div class="tab-pane fade" id="connectedServices3" role="tabpanel" aria-labelledby="ConnectedServices-tab3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive m-b-40">
                                                    <div id="reviews"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
@stop
@section('footer')

<!-- scripts -->
<script type="text/javascript">

    var map = null;
    
    $(document).ready(function(){

       $('#userModal').appendTo("body");

       getUsers();

    $(".modal").on("hidden.bs.modal", function(){
        $('#imgProfile').attr('src', '');
        $('#main_header_name').html('');
        $('#o_fullname').html('');
        $('#o_address').html('');
        $('#o_email').html('');
        $('#o_contact').html('');
        $('#o_created').html('');
        $('#o_updated').html('');

        $('#o_business_logo').html('');
        $('#o_business_name').html('');
        $('#o_business_desc').html('');
        $('#o_business_location').html('');

        $('#o_business_permit').html('');
        $('#o_dti_cert').html('');
        $('#o_dti_form').html('');
        $('#o_valid_id').html('');

        $('#reviews').html('');
    });

    // disable
    $(document).on('click', '.disable_user', function(){

      var id = $(this).attr('id');

      if(confirm('Are you sure you want to disable this user?')) {

         $.ajax({
          url: '{{route('admin-delete-user')}}',
          method: 'POST',
          async: false,
          data: {
            id: id
          },
          success: function(data) {

            if(data.success==1) {
              getUsers();
            }
          }
        });
      } else {

        return false;
      }
    });

    // restore
    $(document).on('click', '.restore_user', function(){

      var id = $(this).attr('id');

      if(confirm('Are you sure you want to restore this user?')) {

        $.ajax({
          url: '{{route('admin-restore-user')}}',
          method: 'POST',
          async: false,
          data: {
            id: id
          },
          success: function(data) {

            if(data.success==1) {
              getUsers();
            }
          }
        });
      } else {

        return false;
      }

    });

    // user details
    $(document).on('click', '.user_details', function(){

      var id = $(this).attr('id');

      $('#myTab a[href="#basicInfo"]').tab('show');

      $.ajax({

        url: '/admin/user/'+id,
        method: 'GET',
        success: function(data) {

          var data2 = data['reviews'];
          var data3 = data['reviews2'];
          var data = data['details'];

          console.log(data2);

          if(data.roles[0].name=='User') {
            $('#connectedServices-tab').css('display', 'none');
            $('#connectedServices-tab2').css('display', 'none');
            $('#connectedServices-tab3').css('display', 'none');
          } else if(data.roles[0].name=='Admin') {
            $('#connectedServices-tab').css('display', 'block');
            $('#connectedServices-tab2').css('display', 'none');
            $('#connectedServices-tab3').css('display', 'block');
          } else {
            $('#connectedServices-tab').css('display', 'none');
            $('#connectedServices-tab2').css('display', 'block');
            $('#connectedServices-tab3').css('display', 'block');
          }

          // basic info
          $('#imgProfile').attr('src', '{{asset('images/users')}}/'+data.image);
          $('#main_header_name').html(data.fname+" "+data.lname);
          $('#o_fullname').html(data.fname+" "+data.lname);
          $('#o_address').html(data.address);
          $('#o_email').html(data.email);
          $('#o_contact').html(data.phone);
          $('#o_created').html(moment(data.created_at).format('MMM D YYYY, h:mm a'));
          $('#o_updated').html(moment(data.updated_at).format('MMM D YYYY, h:mm a'));

          // business info
          if(!$.isEmptyObject(data.company)) {
            $('#o_business_logo').html('<img src="{{asset('images/company')}}/'+data.company.image+'" id="imgBusiness" style="width: 150px; height: 150px;" class="img-thumbnail" />');
            $('#o_business_name').html(data.company.name);
            $('#o_business_desc').html(data.company.description);
            $('#o_business_location').html(data.company.location);

            var value = (data.company.business_permit!="") ? '<a href="{{asset('documents')}}/'+data.company.business_permit+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.company.business_permit+'</a>' : '';

            var value2 = (data.company.dti_cert!="") ? '<a href="{{asset('documents')}}/'+data.company.dti_cert+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.company.dti_cert+'</a>' : '';

            var value3 = (data.company.dti_form!="") ? '<a href="{{asset('documents')}}/'+data.company.dti_form+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.company.dti_form+'</a>' : '';

            var value4 = (data.company.valid_id!="") ? '<a href="{{asset('documents')}}/'+data.company.valid_id+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.company.valid_id+'</a>' : '';

            $('#o_business_permit').html(value);
            $('#o_dti_cert').html(value2);
            $('#o_dti_form').html(value3);
            $('#o_valid_id').html(value4);

            loadMap(data.company.latitude, data.company.longitude);

             if(!$.isEmptyObject(data2)) {

                var output = "<table class='table table-borderless table-data3'>";
                 output+="<thead>";
                 output+="<tr>";
                 output+="<th>Date</th>";
                 output+="<th>Name</th>";
                 output+="<th>Feedback</th>";
                 output+="<th>Rate</th>";
                 output+="<th></th>";
                 output+="</tr>";
                 output+="</thead>";
                 output+="<tbody>";

                 for(var i=0; i<data2.length; i++) {

                    output+="<tr>";
                    output+="<td>"+moment(data2[i].date).format('MMM D YYYY')+"</td>";
                    output+="<td>"+data2[i].name+"</td>";
                    output+="<td>"+data2[i].feedback+"</td>";
                    output+="<td><ul class='list-inline mx-auto justify-content-center'>";
                        for(var j=1; j<=5; j++) {
                          if(j<=data2[i].rate) {
                            var color = 'color: #ffcc00;';
                          } else {
                            var color = 'color: #ccc;';
                          }
                          output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                        }
                    output+="</td></ul>";
                 }
                  output+="</tr>";
                  $('#reviews').html(output); 
             }
          }

          // driver info
          if(!$.isEmptyObject(data.driver)) {

            var value = (data.driver.tin!="") ? '<a href="{{asset('documents')}}/'+data.driver.tin+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.driver.tin+'</a>' : '';

            var value2 = (data.driver.bir_form!="") ? '<a href="{{asset('documents')}}/'+data.driver.bir_form+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.driver.bir_form+'</a>' : '';

            var value3 = (data.driver.gov_issued_id!="") ? '<a href="{{asset('documents')}}/'+data.driver.gov_issued_id+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.driver.gov_issued_id+'</a>' : '';

            var value4 = (data.driver.driver_license!="") ? '<a href="{{asset('documents')}}/'+data.driver.driver_license+'" target="_blank"><i class="fa fa-paperclip" aria-hidden="true"></i> '+data.driver.driver_license+'</a>' : '';


            $('#driver_vehicle_type').html(data.driver.vehicle_type);
            $('#driver_vehicle_color').html(data.driver.vehicle_color);
            $('#driver_vehicle_plate_no').html(data.driver.vehicle_plate_no);
            $('#driver_tin').html(value);
            $('#driver_bir_form').html(value2);
            $('#driver_id').html(value3);
            $('#driver_license').html(value4);

             var output = "<table class='table table-borderless table-data3'>";
             output+="<thead>";
             output+="<tr>";
             output+="<th>Date</th>";
             output+="<th>Name</th>";
             output+="<th>Feedback</th>";
             output+="<th>Rate</th>";
             output+="<th></th>";
             output+="</tr>";
             output+="</thead>";
             output+="<tbody>";

             for(var i=0; i<data3.length; i++) {

                output+="<tr>";
                output+="<td>"+moment(data3[i].date).format('MMM D YYYY')+"</td>";
                output+="<td>"+data3[i].name+"</td>";
                output+="<td>"+data3[i].feedback+"</td>";
                output+="<td><ul class='list-inline mx-auto justify-content-center'>";
                    for(var j=1; j<=5; j++) {
                      if(j<=data3[i].rate) {
                        var color = 'color: #ffcc00;';
                      } else {
                        var color = 'color: #ccc;';
                      }
                      output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                    }
                output+="</td></ul>";
             }
              output+="</tr>";
              $('#reviews').html(output);
          }

          $('#userModal').modal('show');
        }
      });
    });


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
         setTimeout(function() {
            map.invalidateSize();
        }, 10);
    });

    });

      function getUsers() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>ID</th>";
        output+="<th>Name</th>";
        output+="<th>Address</th>";
        output+="<th>Email</th>";
        output+="<th>Phone</th>";
        output+="<th>Role</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-users')}}',
            method: 'GET',
            success: function(data) {
                
                var data = data['users'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].id+"</td>";
                    output+="<td>"+data[i].fname+" "+data[i].lname+"</td>";
                    output+="<td>"+data[i].address+"</td>";
                    output+="<td>"+data[i].email+"</td>";
                    output+="<td>"+data[i].phone+"</td>";

                    var role = data[i].roles;

                    for(var j=0; j<role.length; j++) {

                        var rolename = role[j].name;

                        if(rolename=='User') {
                            output+="<td><span class='role user'>"+rolename+"</span></td>";
                        } else if(rolename=='Admin') {
                            output+="<td><span class='role admin'>"+rolename+"</span></td>";
                        } else {
                           output+="<td><span class='role driver'>"+rolename+"</span></td>";
                        }
                    }
                    if(data[i].deleted_at != null) {
                        output+="<td><div class='table-data-feature'><button id="+data[i].id+" class='item user_details'><i class='zmdi zmdi-info'></i></i></button> <button id="+data[i].id+" class='item restore_user'><i class='zmdi zmdi-refresh'></i></button></div></td>";
                    } else {
                        output+="<td><div class='table-data-feature'><button id="+data[i].id+" class='item user_details'><i class='zmdi zmdi-info'></i></button><button id="+data[i].id+" class='item disable_user'><i class='zmdi zmdi-block'></i></button></div></td>";
                    }
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#users').html(output);
            }
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
