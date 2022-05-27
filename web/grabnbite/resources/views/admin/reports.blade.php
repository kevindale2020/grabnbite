@extends('layouts.admin')
<link href="{{asset('chosen/chosen.css')}}" rel="stylesheet">
<style type="text/css">

/* end */
</style>
@section('content')

<!-- content -->
 <div class="row m-t-30">

         <div class="col">
         <h3>Earnings</h3>
        <div class="py-3">
          <div class="col-md-4">
            <form id="report-form">
              <div class="form-group">
                <input id="start_date" type="text" class="form-control" name="start_date" placeholder="Start Date" onfocus="(this.type='date')">
              </div>
              <div class="form-group">
               <input id="end_date" type="text" class="form-control" name="end_date" placeholder="End Date" onfocus="(this.type='date')">
              </div>
              <button id="btnGo" name="btnGo" class='btn btn-primary btn-sm'>Go</button>
            </form>
          </div>
        </div>
        <div id="reports"></div>
      </div>
    </div>
</div>

<!-- end -->
@stop
@section('footer')

<!-- chosen plugin -->
<script src="{{asset('chosen/chosen.jquery.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    
    $(document).ready(function(){

      // getReviews();

      $('#report-form').submit(function(e){

        e.preventDefault();

        var start = $('#start_date').val();
        var end = $('#end_date').val();
        var str = '';
        var total = 0;

        var output="<h4 style='text-align: center;'>List of Earnings Between "+start+ " and "+end+"</h4><br/>";
        output+="<a href='/admin/convert/pdf/"+start+"/"+end+"' target='_blank' style='margin-bottom: 8px;float: right;' class='au-btn au-btn-icon au-btn--blue' href=''><i class='zmdi zmdi-download'></i>download as pdf</a>";
        output+= "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>Date</th>";
        output+="<th>Order No</th>";
        output+="<th>Customer</th>";
        output+="<th>Total</th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        if(start==''||end=='') {

           if(start=='') {

            str+='Start Date is required\n';
          }

          if(end=='') {

            str+='End Date is required'
          }

          alert(str);

          return false;
        }

        $.ajax({

          url: '{{route('admin-submit-date')}}',
          method: 'POST',
          data: new FormData(this),
          contentType: false,
          processData: false,
          success: function(data) {

            if(data.success==1) {

              var data = data['reports'];
              
              for(var i=0; i<data.length; i++) {

                total += data[i].total;

                output+="<tr>";
                output+="<td>"+data[i].date+"</td>";
                output+="<td>"+data[i].id+"</td>";
                output+="<td>"+data[i].fname+" "+data[i].lname+"</td>";
                output+="<td>"+data[i].total.toFixed(2)+"</td>";
                output+="</tr>";
              }

              output+="</tbody></table>";
              output+="<p style='margin: 5px;text-align: right;'><b>Total Earnings:</b> "+total.toFixed(2)+"</p>";

              $('#reports').html(output);
            }
          }
        });
      });      
    });

</script>
<!-- end -->
@stop
