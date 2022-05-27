@extends('layouts.admin')
<link href="{{asset('chosen/chosen.css')}}" rel="stylesheet">
<style type="text/css">

/* end */
</style>
@section('content')

<!-- content -->
 <div class="row m-t-30">

        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">Orders</h2>
            </div>
            <div id="success2" style="margin-top: 8px;"></div>
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="orders">
                  
                </div>                           
             </div>
        </div>
    </div>
</div>

<!-- details modal -->
       <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Order Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                  <p id="header" class="pull-right" style="font-size: 22px;"></p><br/>
                  <hr>
                  <div class="row">
                    <div class="col-sm-10" id="recipient"></div>
                    <div class="col-sm-2" id="order-date"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <address>
                        <strong>Payment Method:</strong><br>
                        Cash on Delivery
                      </address>
                    </div>
                  </div>
                   <div id="details"></div><br><br>
                  <div class="row">
                    <div class="col-sm-9" id="cancel-reason" style="display: none;"></div>
                    <div class="col-sm-3" id="cancel-date" style="display: none;"></div>
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

<!-- chosen plugin -->
<script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    
    $(document).ready(function(){

      getOrders();

      /* modal */
      $('#detailsModal').appendTo("body");

      $(document).on('click', '.order_details', function(){

        var id = $(this).attr('id');

        $.ajax({

          url: '/admin/order/detail/'+id,
          method: 'GET',
          success: function(data) {

            var details = data['details'];
            var subtotal = 0;
            var total = 0;
            var totalAddOns = 0;
            var totalSub = 0;
            var delivery_fee = 0;
            var coupon_value = 0;
            var discount = 0;
            var new_total = 0;

            $('#header').html("Order # "+data.orderno);

            $('#recipient').html("<address><strong>Billed To:</strong><br>"+data.fname+" "+data.lname+"<br>"+data.location_str+"</address>")

            $('#order-date').html("<address><strong>Order Date:</strong><br>"+data.date+"</address>");

            if(data.status=='Cancelled') {

              $('#cancel-reason').css('display', 'block');
              $('#cancel-date').css('display', 'block');

              $('#cancel-reason').html("<address><strong><span class='text-danger'>Cancel Reason:</span></strong><br>"+data.cancel_reason+"</address>");
              $('#cancel-date').html("<address><strong><span class='text-danger'>Cancelled Date:</span></strong><br>"+data.cancelled_date+"</address>");
            } else {
              $('#cancel-reason').css('display', 'none');
              $('#cancel-date').css('display', 'none');
            }

              var output = "<table class='table table-borderless table-data3'>";
              output+="<thead>";
              output+="<tr>";
              output+="<th>Product</th>";
              output+="<th>Price</th>";
              output+="<th>Qty</th>";
              output+="<th>Subtotal</th>";
              output+="</tr>";
              output+="</thead>";
              output+="<tbody>";

              for(var i=0; i<details.length; i++) {

                    subtotal = details[i].price * details[i].qty + details[i].total;
                    totalSub += subtotal;

                    output+="<tr>";
                    if(details[i].addons!="") {
                      output+="<td>"+details[i].name+"<small class='help-block form-text'><i>("+details[i].addons+") - ₱"+details[i].total.toFixed(2)+"</i></small></td>";
                    } else {
                      output+="<td>"+details[i].name+"</td>";
                    }
                    output+="<td>₱"+details[i].price+"</td>";
                    output+="<td>"+details[i].qty+"</td>";
                    output+="<td>₱"+subtotal.toFixed(2)+"</td>";
                    output+="</tr>";
                }

                delivery_fee = parseFloat(data.delivery_fee);
                coupon_value = parseFloat(data.coupon_value);

                total = parseFloat(totalSub) + parseFloat(delivery_fee);

                 if(data.coupon_type=='Fixed') {

                    discount = coupon_value;
                    new_total = total - discount;

                    if(new_total < 0) total = 0;
                 } else if(data.coupon_type=='Percent off') {
                    
                    discount = (coupon_value/100) * totalSub;
                    new_total = total - discount;

                     if(new_total < 0) total = 0;
                 } else if(data.coupon_type=='Minimum') {

                    if(totalSub >= data.coupon_constraint) {

                      discount = coupon_value;
                      new_total = total - discount;

                       if(new_total < 0) total = 0;
                    }
                 }


                output+="</tbody></table>";

                output+="<p style='margin: 5px;text-align: right;'>Subtotal: ₱"+totalSub.toFixed(2)+"</b></p>";

                output+="<p style='margin: 5px;text-align: right;'>Delivery fee: ₱"+delivery_fee.toFixed(2)+"</b></p>";

                if(data.coupon_desc!="") {

                  output+="<p style='margin: 5px;text-align: right;color: #5cb85c;'>Coupon("+data.coupon_desc+"): -₱"+discount.toFixed(2)+"</b></p>";
                  if(total > 0) {
                     output+="<p style='margin: 5px;text-align: right;'>Total: <del><b>₱"+total.toFixed(2)+"</b></del></p>";
                  } else {
                    output+="<p style='margin: 5px;text-align: right;'>Total: <b>₱"+total.toFixed(2)+"</b></p>";
                  }
                  if(new_total > 0) {
                    output+="<p style='margin: 5px;text-align: right;'>₱"+new_total.toFixed(2)+"</b></p>";
                  }
                } else {
                   output+="<p style='margin: 5px;text-align: right;'>Total: <b>₱"+total.toFixed(2)+"</b></p>";
                }


            $('#details').html(output);

            $('#detailsModal').modal('show');

          }
        });
      });
        
    });

    function getOrders() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>#</th>";
        output+="<th>FirstName</th>";
        output+="<th>LastName</th>";
        output+="<th>Date</th>";
        output+="<th>Status</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-orders')}}',
            method: 'GET',
            success: function(data) {
                
                var data = data['orders'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].id+"</td>";
                    output+="<td>"+data[i].user.fname+"</td>";
                    output+="<td>"+data[i].user.lname+"</td>";
                    output+="<td>"+moment(data[i].date).format('MMM D, YYYY')+"</td>";
                    output+="<td>"+data[i].status+"</td>";
                    output+="<td><div class='table-data-feature'><button class='item order_details' id="+data[i].id+"><i class='zmdi zmdi-info'></i></button></div></td>";
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#orders').html(output);
            }
        });
    }

</script>
<!-- end -->
@stop
