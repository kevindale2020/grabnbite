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
                <h2 class="title-1">Reviews</h2>
            </div>
            <div id="success2" style="margin-top: 8px;"></div>
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="reviews">
                  
                </div>                           
             </div>
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

      getReviews();

      $(document).on('click', '.delete_review', function(){

        var id = $(this).attr('id');

         if(confirm('Are you sure you want to delete this review?')) {

                $.ajax({
                  url: '{{route('admin-delete-review')}}',
                  method: 'POST',
                  async: false,
                  data: {
                    id: id
                  },
                  success: function(data) {

                    if(data.success==1) {
                      $('#success2').addClass('alert alert-success alert-dismissible');
                      $('#success2').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully deleted');
                      getReviews();
                    }
                 }
                });
            } else {
              return false;
            }
      });
        
    });

    function getReviews() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>#</th>";
        output+="<th>FirstName</th>";
        output+="<th>LastName</th>";
        output+="<th>Feedback</th>";
        output+="<th>Rate</th>";
        output+="<th>Date</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-reviews')}}',
            method: 'GET',
            success: function(data) {
                
                var data = data['reviews'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].id+"</td>";
                    output+="<td>"+data[i].user.fname+"</td>";
                    output+="<td>"+data[i].user.lname+"</td>";
                    output+="<td>"+data[i].feedback+"</td>";
                    output+="<td>";
                    output+="<ul class='list-inline mx-auto justify-content-center'>";
                    for(var j=1; j<=5; j++) {
                      if(j<=data[i].rate) {
                        var color = 'color: #ffcc00;';
                      } else {
                        var color = 'color: #ccc;';
                      }
                      output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                    }
                    output+="</ul>";
                    output+="</td>";
                    output+="<td>"+moment(data[i].rated_date).format('MMM D, YYYY')+"</td>";
                    output+="<td><div class='table-data-feature'><button class='item delete_review' id="+data[i].id+"><i class='zmdi zmdi-delete'></i></button></div></td>";
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#reviews').html(output);
            }
        });
    }

</script>
<!-- end -->
@stop
