@extends('layouts.admin')
<link href="{{asset('chosen/chosen.css')}}" rel="stylesheet">
<style type="text/css">

</style>
@section('content')

<!-- content -->


   <div class="row m-t-30">

        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">coupons</h2>
                <button id="btnAdd" class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#couponModal"><i class="zmdi zmdi-plus"></i>add item</button>
            </div>
            <div id="success" style="margin-top: 8px;"></div>
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="coupons"></div>                           
             </div>
        </div>
    </div>
  

<!-- add coupon modal -->
       <div class="modal fade" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add Coupon</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                   <div class="card">
                                    <div class="card-header">
                                        <strong>Coupon</strong> Form
                                    </div>
                                    <div class="card-body card-block">
                                        <form id="couponForm" class="form-horizontal">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Code</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-code" name="coupon-code" placeholder="Coupon code" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Description</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-desc" name="coupon-desc" placeholder="Description" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Value</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-value" name="coupon-value" class="form-control" placeholder="Amount or percent off">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Type</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <select name="coupon-type" id="coupon-type" class="form-control">
                                                        <option selected hidden>Please select</option>
                                                        <option value="Fixed">Fixed</option>
                                                        <option value="Percent off">Percent off</option>
                                                        <option value="Minimum">Minimum</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group" id="coupon-constraint-container">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Constraint</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-constraint" name="coupon-constraint" class="form-control" placeholder="For mininum order coupon type only">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right">
                                              <i class="fa fa-dot-circle-o" style="color: #fff;"></i> Submit
                                            </button>
                                        </form>
                                    </div>
                                </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->

    <!-- edit coupon modal -->
        <div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Coupon</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                   <div class="card">
                                    <div class="card-header">
                                        <strong>Coupon</strong> Form
                                    </div>
                                    <div class="card-body card-block">
                                        <form id="editCouponForm" class="form-horizontal">
                                            <input type="hidden" id="current-coupon-id" name="current-coupon-id"/>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Code</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-code2" name="coupon-code" placeholder="Coupon code" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Description</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-desc2" name="coupon-desc" placeholder="Description" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Value</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-value2" name="coupon-value" class="form-control" placeholder="Amount or percent off">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Type</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                   <div id="select-coupons"></div>
                                                </div>
                                            </div>
                                              <div class="row form-group" id="coupon-constraint2-container">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Constraint</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="coupon-constraint2" name="coupon-constraint" class="form-control" placeholder="For mininum order coupon type only">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm pull-right">
                                              <i class="fa fa-dot-circle-o" style="color: #fff;"></i> Save
                                            </button>
                                        </form>
                                    </div>
                                </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->

<!-- end -->
@stop
@section('footer')

<!-- scripts -->
<script type="text/javascript">
    $(function() {
  var $tabButtonItem = $('#tab-button li'),
      $tabSelect = $('#tab-select'),
      $tabContents = $('.tab-contents'),
      activeClass = 'is-active';

  $tabButtonItem.first().addClass(activeClass);
  $tabContents.not(':first').hide();

  $tabButtonItem.find('a').on('click', function(e) {
    var target = $(this).attr('href');

    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);
    $tabSelect.val(target);
    $tabContents.hide();
    $(target).show();
    e.preventDefault();
  });

  $tabSelect.on('change', function() {
    var target = $(this).val(),
        targetSelectNum = $(this).prop('selectedIndex');

    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.hide();
    $(target).show();
  });
});
</script>

<!-- chosen plugin -->
<script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    
    $(document).ready(function(){

        getCoupons(); 

        /* hide select options */
        $('#coupon-constraint-container').hide();
        $('#coupon-constraint2-container').hide();

        /* modal */
        $('#couponModal').appendTo("body");

        $('#editCouponModal').appendTo("body");

        /* end /*

        /* allow numeric only */
        setInputFilter(document.getElementById("coupon-value"), function(value) {
          return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
        });
        /* end */

        /* add coupon */
        $('#couponForm').submit(function(e){

            e.preventDefault();

            var code = $('#coupon-code').val();
            var desc = $('#coupon-desc').val();
            var value = $('#coupon-value').val();
            var type = $('#coupon-type').find(":selected").text();

            if(code==''||desc==''||value==''||type=='') {

                var str = '';

                if(code=='') {
                    str+='Code field is needed\n';
                }

                if(desc=='') {
                    str+='Description field is needed\n';
                }

                if(value=='') {
                    str+='Value field is needed\n';
                }

                if(type=='Please select') {
                    str+='Type field is required\n';
                }

                alert(str);
                return false;
            }

            $.ajax({

                url: '{{route('admin-add-coupon')}}',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if(data.success==1) {
                        $('#couponForm')[0].reset();
                        $('#couponModal').modal('hide');
                        $('#success').addClass('alert alert-success alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>New coupon has been added');
                        getCoupons();
                    } else {
                        $('#couponModal').modal('hide');
                        $('#success').addClass('alert alert-danger alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>This coupon already exists');
                    }
                }
            });


        });
        /* end */

        /* coupon details */
        $(document).on('click', '.edit_coupon', function(){

           var id = $(this).attr('id');

           var output = "<select name='coupon-type' id='coupon-type2' class='form-control'>";

           $.ajax({
                url: '/admin/coupon/'+id,
                method: 'GET',
                success: function(value) {

                  var data = value['details'];

                  $('#current-coupon-id').val(data.id);
                  $('#coupon-code2').val(data.code);
                  $('#coupon-desc2').val(data.description);
                  $('#coupon-value2').val(data.value);
                  if(data.type!='Minimum') {
                    $('#coupon-constraint2').val(0);
                    $('#coupon-constraint2-container').hide();
                  } else {             
                    $('#coupon-constraint2-container').show();
                    $('#coupon-constraint2').val(data.constraint);                 
                  }
                  output+="<option selected hidden value="+data.type+">"+data.type+"</option>";
                  output+="<option value='Fixed'>Fixed</option>";
                  output+="<option value='Percent off'>Percent off</option>";
                  output+="<option value='Minimum'>Minimum</option>";
                  output+="</select>";

                  $('#select-coupons').html(output);

                  $('#editCouponModal').modal('show');

                  /* select event */

                   $('#coupon-type2').change(function(){

                    var value = $('#coupon-type2').find(":selected").text();
        
                    if(value=='Minimum') {
                        $('#coupon-constraint2-container').show();
                    } else {
                        $('#coupon-constraint2').val(0);
                        $('#coupon-constraint2-container').hide();
                    }
                 });

                   /* end */
          
                }
            });

        });
        /* end */

        /* delete coupon */
        $(document).on('click', '.delete_coupon', function(){

            var id = $(this).attr("id");

            if(confirm('Are you sure you want to delete this coupon?')) {

                $.ajax({
                  url: '{{route('admin-delete-coupon')}}',
                  method: 'POST',
                  async: false,
                  data: {
                    id: id
                  },
                  success: function(data) {

                    if(data.success==1) {
                      $('#success').addClass('alert alert-success alert-dismissible');
                      $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully deleted');
                      getCoupons();
                    }
                 }
                });
            } else {
              return false;
            }
        });
        /* end */

        /* edit coupon */
        $('#editCouponForm').submit(function(e){

            e.preventDefault();

            var code = $('#coupon-code2').val();
            var desc = $('#coupon-desc2').val();
            var value = $('#coupon-value2').val();
            var type = $('#coupon-type2').find(":selected").text();

            if(code==''||desc==''||value==''||type=='') {

                var str = '';

                if(code=='') {
                    str+='Code field is needed\n';
                }

                if(desc=='') {
                    str+='Description field is needed\n';
                }

                if(value=='') {
                    str+='Value field is needed\n';
                }

                if(type=='Please select') {
                    str+='Type field is required\n';
                }

                alert(str);
                return false;
            }

            $.ajax({

                url: '{{route('admin-edit-coupon')}}',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if(data.success==1) {
                        $('#editCouponForm')[0].reset();
                        $('#editCouponModal').modal('hide');
                        $('#success').addClass('alert alert-success alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully saved');
                        getCoupons();
                    } else {
                        $('#editCouponModal').modal('hide');
                        $('#success').addClass('alert alert-danger alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>This coupon already exists');
                    }
                }
            });


        });
        /* end */

    // disable
    $(document).on('click', '.disable_coupon', function(){

      var id = $(this).attr('id');

      if(confirm('Are you sure you want to disable this coupon?')) {

         $.ajax({
          url: '{{route('admin-disable-coupon')}}',
          method: 'POST',
          async: false,
          data: {
            id: id
          },
          success: function(data) {

            if(data.success==1) {
              getCoupons();
            }
          }
        });
      } else {

        return false;
      }
    });

    // enable
    $(document).on('click', '.enable_coupon', function(){

      var id = $(this).attr('id');

      if(confirm('Are you sure you want to enable this coupon?')) {

        $.ajax({
          url: '{{route('admin-enable-coupon')}}',
          method: 'POST',
          async: false,
          data: {
            id: id
          },
          success: function(data) {

            if(data.success==1) {
              getCoupons();
            }
          }
        });
      } else {

        return false;
      }

    });

    /* on change coupon-type */
    $('#coupon-type').change(function(){

        var value = $('#coupon-type').find(":selected").text();

        if(value=='Minimum') {
            $('#coupon-constraint-container').show();
        }
    });

    /* end */

    });

      function getCoupons() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>#</th>";
        output+="<th>Code</th>";
        output+="<th>Description</th>";
        output+="<th>Value</th>";
        output+="<th>Constraint</th>";
        output+="<th>Type</th>";
        output+="<th>Status</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-coupons')}}',
            method: 'GET',
            success: function(data) {
                
                var data = data['coupons'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].id+"</td>";
                    output+="<td>"+data[i].code+"</td>";
                    output+="<td>"+data[i].description+"</td>";
                    output+="<td>"+data[i].value+"</td>";
                    if(data[i].constraint!=null) {
                        output+="<td>"+data[i].constraint+"</td>";
                    } else {
                        output+="<td>None</td>";
                    }
                    output+="<td>"+data[i].type+"</td>";
                    if(data[i].is_enabled==1) {
                        output+="<td><span class='text-success'>Enabled</span></td>";
                        output+="<td><div class='table-data-feature'><button class='item edit_coupon' id="+data[i].id+"><i class='zmdi zmdi-edit'></i></button><button class='item delete_coupon' id="+data[i].id+"><i class='zmdi zmdi-delete'></i></button><button id="+data[i].id+" class='item disable_coupon'><i class='zmdi zmdi-block'></i></button></div></td>";
                    } else {
                        output+="<td><span class='text-danger'>Disabled</span></td>";
                        output+="<td><div class='table-data-feature'><button class='item edit_coupon' id="+data[i].id+"><i class='zmdi zmdi-edit'></i></button><button class='item delete_coupon' id="+data[i].id+"><i class='zmdi zmdi-delete'></i></button><button id="+data[i].id+" class='item enable_coupon'><i class='zmdi zmdi-refresh'></i></button></div></td>";
                    }
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#coupons').html(output);
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
</script>
<!-- end -->
@stop
