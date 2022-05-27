@extends('layouts.admin')

<style type="text/css">
.tabs {

  margin: 0 auto;
  padding: 0 20px;
}
#tab-button {
  display: table;
  table-layout: fixed;
  width: 100%;
  margin: 0;
  padding: 0;
  list-style: none;
}
#tab-button li {
  display: table-cell;
  width: 20%;
}
#tab-button li a {
  display: block;
  padding: .5em;
  background: #eee;
  border: 1px solid #ddd;
  text-align: center;
  color: #000;
  text-decoration: none;
}
#tab-button li:not(:first-child) a {
  border-left: none;
}
#tab-button li a:hover,
#tab-button .is-active a {
  border-bottom-color: transparent;
  background: #fff;
}
.tab-contents {
  padding: .5em 2em 1em;
  border: 1px solid #ddd;
}



.tab-button-outer {
  display: none;
}
.tab-contents {
  margin-top: 20px;
}
@media screen and (min-width: 768px) {
  .tab-button-outer {
    position: relative;
    z-index: 2;
    display: block;
  }
  .tab-select-outer {
    display: none;
  }
  .tab-contents {
    position: relative;
    top: -1px;
    margin-top: 0;
  }
}
 .modal-lg {
    max-width: 976px !important;
    width: 976px !important;
  }
</style>
@section('content')

<!-- content -->
<div class="tabs">
  <div class="tab-button-outer">
    <ul id="tab-button">
      <li><a href="#tab01">Merchants</a></li>
      <li><a href="#tab02">Riders</a></li>
    </ul>
  </div>
  <div class="tab-select-outer">
    <select id="tab-select">
      <option value="#tab01">Merchants</option>
      <option value="#tab02">Riders</option>
    </select>
  </div>

  <div id="tab01" class="tab-contents">
   <div class="row m-t-30">

        <div class="col-md-12">
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="merchant_ratings"></div>                           
             </div>
        </div>
    </div>
  </div>
  <div id="tab02" class="tab-contents">
    <div class="row m-t-30">

        <div class="col-md-12">
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="rider_ratings"></div>                           
             </div>
        </div>
    </div>
  </div>
</div>

<!-- end -->

<!-- merchant ratings -->
<div class="modal fade" id="merchantModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="merchantLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                 <div id="merchantRatings"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<!-- end -->

<!-- rider ratings -->
<div class="modal fade" id="riderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="riderLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                 <div id="riderRatings"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
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

<script type="text/javascript">
    
    $(document).ready(function(){

      getMerchantRatings();

      getRiderRatings();

       /* modal */
        $('#merchantModal').appendTo("body");

        $('#riderModal').appendTo("body");

      // view merchant ratings
      $(document).on('click', '.view_merchant_ratings', function(){

        var id = $(this).attr('id');

        $.ajax({

          url: '/admin/merchant/ratings/'+id,
          method: 'GET',
          success: function(data) {

            var label = data.label;

            var data = data['reviews'];

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

                 for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+moment(data[i].date).format('MMM D YYYY')+"</td>";
                    output+="<td>"+data[i].name+"</td>";
                    output+="<td>"+data[i].feedback+"</td>";
                    output+="<td><ul class='list-inline mx-auto justify-content-center'>";
                        for(var j=1; j<=5; j++) {
                          if(j<=data[i].rate) {
                            var color = 'color: #ffcc00;';
                          } else {
                            var color = 'color: #ccc;';
                          }
                          output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                        }
                    output+="</td></ul>";
                 }
                  output+="</tr>";
              $('#merchantRatings').html(output); 
              $('#merchantLabel').html('All Reviews of '+label);
              $('#merchantModal').modal('show');
          }
        });
      });

      // view rider ratings
      $(document).on('click', '.view_rider_ratings', function(){

        var id = $(this).attr('id');

        $.ajax({

          url: '/admin/rider/ratings/'+id,
          method: 'GET',
          success: function(data) {

            var label = data.label;

            var data = data['reviews'];

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

                 for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+moment(data[i].date).format('MMM D YYYY')+"</td>";
                    output+="<td>"+data[i].name+"</td>";
                    output+="<td>"+data[i].feedback+"</td>";
                    output+="<td><ul class='list-inline mx-auto justify-content-center'>";
                        for(var j=1; j<=5; j++) {
                          if(j<=data[i].rate) {
                            var color = 'color: #ffcc00;';
                          } else {
                            var color = 'color: #ccc;';
                          }
                          output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                        }
                    output+="</td></ul>";
                 }
                  output+="</tr>";
              $('#riderRatings').html(output); 
               $('#riderLabel').html('All Reviews of Rider '+label);
              $('#riderModal').modal('show');
          }
        });
      });

    });

    function getMerchantRatings() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>Name</th>";
        output+="<th>Business</th>";
        output+="<th>Average Ratings</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-merchant-ratings')}}',
            method: 'GET',
            success: function(data) {

                var data = data['merchants'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].name+"</td>";
                    output+="<td>"+data[i].business+"</td>";
                    output+="<td>";
                    output+="<ul class='list-inline mx-auto justify-content-center'>";
                    for(var j=1; j<=5; j++) {
                      if(j<=data[i].value) {
                        var color = 'color: #ffcc00;';
                      } else {
                        var color = 'color: #ccc;';
                      }
                      output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                    }
                    output+="</ul>";
                    output+="</td>";
                    output+="<td><div class='table-data-feature'><button class='item view_merchant_ratings' id="+data[i].id+"><i class='zmdi zmdi-info'></i></button></div></td>";
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#merchant_ratings').html(output);
            }
        });
    }

    function getRiderRatings() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>Name</th>";
        output+="<th>Average Ratings</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-rider-ratings')}}',
            method: 'GET',
            success: function(data) {

                var data = data['merchants'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].name+"</td>";
                    output+="<td>";
                    output+="<ul class='list-inline mx-auto justify-content-center'>";
                    for(var j=1; j<=5; j++) {
                      if(j<=data[i].value) {
                        var color = 'color: #ffcc00;';
                      } else {
                        var color = 'color: #ccc;';
                      }
                      output+="<li class='list-inline-item' style='cursor: pointer;"+color+"font-size: 16px;'>&#9733;</li>";
                    }
                    output+="</ul>";
                    output+="</td>";
                    output+="<td><div class='table-data-feature'><button class='item view_rider_ratings' id="+data[i].id+"><i class='zmdi zmdi-info'></i></button></div></td>";
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#rider_ratings').html(output);
            }
        });
    }
</script>
<!-- end -->
@stop
