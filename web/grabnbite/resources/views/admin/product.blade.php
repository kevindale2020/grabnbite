@extends('layouts.admin')
<link href="{{asset('chosen/chosen.css')}}" rel="stylesheet">
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
</style>
@section('content')

<!-- content -->
<div class="tabs">
  <div class="tab-button-outer">
    <ul id="tab-button">
      <li><a href="#tab01">Products</a></li>
      <li><a href="#tab02">Add-ons</a></li>
    </ul>
  </div>
  <div class="tab-select-outer">
    <select id="tab-select">
      <option value="#tab01">Products</option>
      <option value="#tab02">Add-ons</option>
    </select>
  </div>

  <div id="tab01" class="tab-contents">
   <div class="row m-t-30">

        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">products</h2>
                <button id="btnAdd" class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#productModal"><i class="zmdi zmdi-plus"></i>add item</button>
            </div>
            <div id="success" style="margin-top: 8px;"></div>
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="products"></div>                           
             </div>
        </div>
    </div>
  </div>
  <div id="tab02" class="tab-contents">
    <div class="row m-t-30">

        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">add-ons</h2>
                <button id="btnAdd" class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#addOnsModal"><i class="zmdi zmdi-plus"></i>add item</button>
            </div>
            <div id="success2" style="margin-top: 8px;"></div>
             <!-- DATA TABLE-->
             <div class="table-responsive m-b-40" style="margin-top: 16px;">
                <div id="addons"></div>                           
             </div>
        </div>
    </div>
  </div>
</div>

<!-- add product modal -->
       <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add Product</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                   <div class="card">
                                    <div class="card-header">
                                        <strong>Product</strong> Form
                                    </div>
                                    <div class="card-body card-block">
                                        <form id="productForm" enctype="multipart/form-data" class="form-horizontal">
                                           <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="file-input" class=" form-control-label">Image</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="file" id="image" name="image" class="form-control-file">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Name</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-name" name="product-name" placeholder="Name of product" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Description</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-desc" name="product-desc" placeholder="Description" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Price</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-price" name="product-price" class="form-control" placeholder="Price">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Quantity</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-qty" name="product-qty" class="form-control" placeholder="Quantity">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Category</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                  <div id="category-dropdown"></div>
                                                </div>
                                            </div>
                                             <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Add-ons</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                  <div id="add-ons-dropdown"></div>
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

    <!-- edit product modal -->
       <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Product</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                   <div class="card">
                                    <div class="card-header">
                                        <strong>Edit Product</strong> Form
                                    </div>
                                    <div class="card-body card-block">
                                        <form id="editProductForm" enctype="multipart/form-data" class="form-horizontal">
                                            <input type="hidden" id="current-product-id" name="current-product-id">
                                            <input type="hidden" id="current-product-image" name="current-product-image">
                                           <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="file-input" class=" form-control-label">Image</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="file" id="image" name="image" class="form-control-file">
                                                </div>
                                            </div>
                                             <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="file-input" class="form-control-label">Display</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <img id="display" class="rounded" alt="display" width="120" height="auto"> 
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Name</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-name2" name="product-name" placeholder="Name of product" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Description</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-desc2" name="product-desc" placeholder="Description" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Price</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-price2" name="product-price" class="form-control" placeholder="Price">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Quantity</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="product-qty2" name="product-qty" class="form-control" placeholder="Quantity">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Category</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                  <div id="category-dropdown2"></div>
                                                </div>
                                            </div>
                                             <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Add-ons</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                  <div id="add-ons-dropdown2"></div>
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

    <!-- add add-ons modal -->
       <div class="modal fade" id="addOnsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add Add-ons</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                   <div class="card">
                                    <div class="card-header">
                                        <strong>Add-ons</strong> Form
                                    </div>
                                    <div class="card-body card-block">
                                        <form id="addOnsForm" class="form-horizontal">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Name</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="addons-name" name="product-name" placeholder="Name of product" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Price</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="addons-price" name="product-price" class="form-control" placeholder="Price">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Quantity</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="addons-qty" name="product-qty" class="form-control" placeholder="Quantity">
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

    <!-- edit add-ons modal -->
       <div class="modal fade" id="editAddOnsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Add-ons</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            
                <div class="modal-body">
                   <div class="card">
                                    <div class="card-header">
                                        <strong>Edit Add-ons</strong> Form
                                    </div>
                                    <div class="card-body card-block">
                                        <form id="editAddOnsForm" class="form-horizontal">
                                          <input type="hidden" id="current-addon-id" name="current-addon-id">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Name</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="addons-name2" name="product-name" placeholder="Name of product" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Price</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="addons-price2" name="product-price" class="form-control" placeholder="Price">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Quantity</label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="text" id="addons-qty2" name="product-qty" class="form-control" placeholder="Quantity">
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

        getProducts(); 

        getAddOns();

        loadAddOns();

        loadCategories();

        /* modal */
        $('#productModal').appendTo("body");

        $('#editProductModal').appendTo("body");

        $('#addOnsModal').appendTo("body");

        $('#editAddOnsModal').appendTo("body");
        /* end /*

        /* allow numeric only */
        setInputFilter(document.getElementById("product-price"), function(value) {
          return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
        });
        setInputFilter(document.getElementById("product-qty"), function(value) {
          return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
        });
        /* end */

        $('#image').on('change',function(){
            //get the file name
            var fileName = $(this).val();

            if(Math.round(this.files[0].size / (1024 * 1024) > 2)) {

                alert('Please upload file less than 2MB. Thanks!!');
                $(this).val('');
            }
            });

        /* add product */
        $('#productForm').submit(function(e){

            e.preventDefault();

            var extension = $('#image').val().split('.').pop().toLowerCase();
            var image = $('#image').val();
            var name = $('#product-name').val();
            var desc = $('#product-desc').val();
            var price = $('#product-price').val();
            var qty = $('#product-qty').val();
            var category = $('.chosen-select3').val();
            var formData = new FormData(this);

            formData.append('product-addons', $('.chosen-select').val());

            formData.append('product-category', $('.chosen-select3').val());

            if(image==''||name==''||desc==''||price==''||qty==''||category=='') {

                alert('Image is required\nName is required\nDescription is required\nPrice is required\nQuantity is required\nCategory is required');
                return false;
            }

            if(image!='') {

                if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {

                    alert('Invalid File');

                    return false;
                }
            }

            $.ajax({

                url: '{{route('admin-add-product')}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if(data.success==1) {
                        $('#productForm')[0].reset();
                        $('#productModal').modal('hide');
                        $('#success').addClass('alert alert-success alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>New product has been added');
                        getProducts();
                    } else {
                        $('#productModal').modal('hide');
                        $('#success').addClass('alert alert-danger alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>This product already exists');
                    }
                }
            });


        });
        /* end */

        /* product details */
        $(document).on('click', '.edit_product', function(){

            var id = $(this).attr('id');
            var output = "<select data-placeholder='Choose from the lists...' multiple='multiple' class='chosen-select2' tabindex='2'>";
            var output2 = "<select data-placeholder='Choose category...' class='chosen-select4' tabindex='2'>";

           $.ajax({
                url: '/admin/product/'+id,
                method: 'GET',
                success: function(value) {

                  var data = value['details'];
                  var lists = value['addons'];
                  var categories = value['categories'];
                  var selected = data.addon_id;
                  var current_category = value['current'];

                  $('#current-product-id').val(data.id);
                  $('#current-product-image').val(data.image);
                  $('#product-name2').val(data.name);
                  $('#product-desc2').val(data.description);
                  $('#product-price2').val(data.price);
                  $('#product-qty2').val(data.qty);
                  $('#display').attr('src', '{{asset('images/products')}}/'+data.image);


                  // addons dropdown
                  for(var i=0; i<lists.length; i++) {

                    if(selected!=null) {
                      if($.inArray(lists[i].id.toString(), selected.split(',')) != -1) {
                        output+="<option value="+lists[i].id+" selected>"+lists[i].name+" - "+lists[i].price+"</option>";
                      } else {
                        output+="<option value="+lists[i].id+">"+lists[i].name+" - "+lists[i].price+"</option>";
                      }
                    } else {
                      output+="<option value="+lists[i].id+">"+lists[i].name+" - "+lists[i].price+"</option>";
                    }
                  }

                  output+="</select>";

                  $('#add-ons-dropdown2').html(output);

                  $('#add-ons-dropdown2').find('select').chosen({
                    width: "347px"
                  });

                  // categories dropdown
                  for(var i=0; i<categories.length; i++) {
                    if(categories[i].id==current_category.id) {
                       output2+="<option value="+categories[i].id+" selected>"+categories[i].name+"</option>";
                    } else {
                       output2+="<option value="+categories[i].id+">"+categories[i].name+"</option>";
                    }
                  }

                  output2+="</select>";

                  $('#category-dropdown2').html(output2);

                  $('#category-dropdown2').find('select').chosen({
                    width: "347px"
                  });


                  $('#editProductModal').modal('show');
                }
            });
        });
        /* end */

        /* delete product */
        $(document).on('click', '.delete_product', function(){

            var id = $(this).attr("id");

            if(confirm('Are you sure you want to delete this product?')) {

                $.ajax({
                  url: '{{route('admin-delete-product')}}',
                  method: 'POST',
                  async: false,
                  data: {
                    id: id
                  },
                  success: function(data) {

                    if(data.success==1) {
                      $('#success').addClass('alert alert-success alert-dismissible');
                      $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully deleted');
                      getProducts();
                    }
                 }
                });
            } else {
              return false;
            }
        });
        /* end */

        /* edit product */
        $('#editProductForm').submit(function(e){

            e.preventDefault();

            var extension = $('#image').val().split('.').pop().toLowerCase();
            var image = $('#image').val();
            var name = $('#product-name2').val();
            var desc = $('#product-desc2').val();
            var price = $('#product-price2').val();
            var qty = $('#product-qty2').val();
            var formData = new FormData(this);

            formData.append('product-addons', $('.chosen-select2').val());
            formData.append('product-category', $('.chosen-select4').val());

            if(name==''||desc==''||price==''||qty=='') {

                alert('All fields are required');
                return false;
            }

            if(image!='') {

                if(jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {

                    alert('Invalid File');

                    return false;
                }
            }

            $.ajax({

                url: '{{route('admin-edit-product')}}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if(data.success==1) {
                        $('#editProductForm')[0].reset();
                        $('#editProductModal').modal('hide');
                        $('#success').addClass('alert alert-success alert-dismissible');
                        $('#success').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully saved');
                        getProducts();
                    }
                }
            });

        });
        /* end */

        /* add ad-ons */
        $('#addOnsForm').submit(function(e){

            e.preventDefault();

            var name = $('#addons-name').val();
            var price = $('#addons-price').val();
            var qty = $('#addons-qty').val();

            if(name==''||price==''||qty=='') {

                alert('All fields are required');

                return false;
            }

            $.ajax({

                url: '{{route('admin-add-addons')}}',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {

                   if(data.success==1) {
                        $('#addOnsForm')[0].reset();
                        $('#addOnsModal').modal('hide');
                        $('#success2').addClass('alert alert-success alert-dismissible');
                        $('#success2').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully saved');
                        getAddOns();
                   } else {
                        $('#addOnsForm')[0].reset();
                        $('#addOnsModal').modal('hide');
                        $('#success2').addClass('alert alert-danger alert-dismissible');
                        $('#success2').html('<button type="button" class="close" data-dismiss="alert">&times;</button>This product already exists');
                   }
                }
            });

        });
        /* end */

        /* add-ons details */
        $(document).on('click', '.edit_addon', function(){

          var id = $(this).attr('id');

          $.ajax({
                url: '/admin/addon/'+id,
                method: 'GET',
                success: function(data) {

                  var data = data['details'];

                  $('#current-addon-id').val(data.id);
                  $('#addons-name2').val(data.name);
                  $('#addons-price2').val(data.price);
                  $('#addons-qty2').val(data.qty);
                  $('#editAddOnsModal').modal('show');
                }
            });
        });
        /* end */

        /* edit ad-ons */
        $('#editAddOnsForm').submit(function(e){

            e.preventDefault();

            var name = $('#addons-name2').val();
            var price = $('#addons-price2').val();
            var qty = $('#addons-qty2').val();

            if(name==''||price==''||qty=='') {

                alert('All fields are required');

                return false;
            }

            $.ajax({

                url: '{{route('admin-edit-addon')}}',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {

                   if(data.success==1) {
                        $('#editAddOnsForm')[0].reset();
                        $('#editAddOnsModal').modal('hide');
                        $('#success2').addClass('alert alert-success alert-dismissible');
                        $('#success2').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully saved');
                        getAddOns();
                   }
                }
            });

        });
        /* end */

         /* delete add-ons */
        $(document).on('click', '.delete_addon', function(){

            var id = $(this).attr("id");

            if(confirm('Are you sure you want to delete this add-on?')) {

                $.ajax({
                  url: '{{route('admin-delete-addon')}}',
                  method: 'POST',
                  async: false,
                  data: {
                    id: id
                  },
                  success: function(data) {

                    if(data.success==1) {
                      $('#success2').addClass('alert alert-success alert-dismissible');
                      $('#success2').html('<button type="button" class="close" data-dismiss="alert">&times;</button>Successfully deleted');
                      getAddOns();
                    }
                 }
                });
            } else {
              return false;
            }
        });
        /* end */

    });

      function getProducts() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>ID</th>";
        output+="<th>Image</th>";
        output+="<th>Name</th>";
        output+="<th>Description</th>";
        output+="<th>Price</th>";
        output+="<th>Qty</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-products')}}',
            method: 'GET',
            success: function(data) {
                
                var data = data['products'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].id+"</td>";
                    output+="<td><img class='img-fluid img-thumbnail' src='../images/products/"+data[i].image+"' width='100' height='auto'></td>";
                    output+="<td>"+data[i].name+"</td>";
                    output+="<td>"+data[i].description+"</td>";
                    output+="<td>"+data[i].price+"</td>";
                    output+="<td>"+data[i].qty+"</td>";
                    output+="<td><div class='table-data-feature'><button class='item edit_product' id="+data[i].id+"><i class='zmdi zmdi-edit'></i></button><button class='item delete_product' id="+data[i].id+"><i class='zmdi zmdi-delete'></i></button></div></td>";
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#products').html(output);
            }
        });
    }

    function getAddOns() {

        var output = "<table class='table table-borderless table-data3'>";
        output+="<thead>";
        output+="<tr>";
        output+="<th>ID</th>";
        output+="<th>Name</th>";
        output+="<th>Price</th>";
        output+="<th>Qty</th>";
        output+="<th></th>";
        output+="</tr>";
        output+="</thead>";
        output+="<tbody>";

        $.ajax({

            url: '{{route('admin-get-addons')}}',
            method: 'GET',
            success: function(data) {
                
                var data = data['addons'];

                for(var i=0; i<data.length; i++) {

                    output+="<tr>";
                    output+="<td>"+data[i].id+"</td>";
                    output+="<td>"+data[i].name+"</td>";
                    output+="<td>"+data[i].price+"</td>";
                    output+="<td>"+data[i].qty+"</td>";
                    output+="<td><div class='table-data-feature'><button class='item edit_addon' id="+data[i].id+"><i class='zmdi zmdi-edit'></i></button><button class='item delete_addon' id="+data[i].id+"><i class='zmdi zmdi-delete'></i></button></div></td>";
                    output+="</tr>";
                }

                output+="</tbody></table>";

                $('#addons').html(output);
            }
        });
    }

    function loadAddOns() {

      var output = "<select data-placeholder='Choose from the lists...' multiple='multiple' class='chosen-select' tabindex='2'>";

      $.ajax({
        url: '{{route('admin-load-addons')}}',
        method: 'GET',
        success: function(data) {
          
          var data = data['addons'];

          for(var i=0; i<data.length; i++) {

            output+="<option value="+data[i].id+">"+data[i].name+" - "+data[i].price+"</option>";
          }

          output+="</select>";

          console.log(output);

          $('#add-ons-dropdown').html(output);

          $('#add-ons-dropdown').find('select').chosen({
            width: "347px"
          });
        }
      });
    }

     function loadCategories() {

      var output = "<select data-placeholder='Choose category' class='chosen-select3' tabindex='2'>";

      output+="<option value=''></option>";

      $.ajax({
        url: '{{route('admin-load-categories')}}',
        method: 'GET',
        success: function(data) {
          
          var data = data['categories'];

          for(var i=0; i<data.length; i++) {

            output+="<option value="+data[i].id+">"+data[i].name+"</option>";
          }

          output+="</select>";

          console.log(output);

          $('#category-dropdown').html(output);

          $('#category-dropdown').find('select').chosen({
            width: "347px"
          });
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
