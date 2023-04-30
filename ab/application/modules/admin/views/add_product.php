       <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Add New Product</h3>
            </div>
          </div>
          <div class="clearfix"></div>

        <div class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
					 
					  <div class="clearfix"></div>
					</div>
					 
					<div class="x_content"><br />
						<form id="demo-form2" action="" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
							
							<?php if($this->session->userdata('type') == 'super'){?> 
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Category<span class="required">*</span></label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
							  <select id="category_id" name="category_id" onchange="getadmin();" class="form-control col-md-7 col-xs-12">
									<option value="" >Select Category </option>
									<?php if($categories){ ?>
									<?php foreach($categories as $cat){ ?>
									<option value="<?php echo $cat->id; ?>" <?php if(set_value('category_id')==$cat->id){ echo "selected"; } ?> >
									<?php echo $cat->category_name; ?> </option>
									<?php } ?>
									<?php } ?>
									</select>
								<span style="color:red;"><?php echo form_error('category_id'); ?></span>
							  </div>
							</div>
							<?php //echo "<pre>";print_r($admin);?>
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Admin<span class="required">*</span></label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
							  <select id="admin_id" name="admin_id" class="form-control col-md-7 col-xs-12">
									<option value="" >Select Admin </option>
									<?php if($admin){ ?>
									<?php foreach($admin as $ad){ ?>
									<option value="<?php echo $ad->id; ?>" <?php if(set_value('admin_id')==$ad->id){ echo "selected"; } ?> >
									<?php echo $ad->category_name; ?> </option>
									<?php } ?>
									<?php } ?>
									</select>
								<span style="color:red;"><?php echo form_error('admin_id'); ?></span>
							  </div>
							</div>
							<?php } ?>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Title </label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="name" name="name" value="<?php  echo set_value('name'); ?>"  placeholder="Plese enter product name"  class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('name'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Type</label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
							  <select id="p_type" name="p_type" class="form-control col-md-7 col-xs-12">
									<option value="" >Select Product Type </option>
									<?php if($p_type){ ?>
									<?php foreach($p_type as $p){ ?>
									<option value="<?php echo $p->id; ?>" <?php if(set_value('p_type')==$p->id){ echo "selected"; } ?> >
									<?php echo $p->name; ?> </option>
									<?php } ?>
									<?php } ?>
									</select>
								<span style="color:red;"><?php echo form_error('p_type'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Product Type Qty</label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="p_type_qty" name="p_type_qty" value="<?php  echo set_value('p_type_qty'); ?>"  placeholder="Plese enter product type quantity" class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('p_type_qty'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Price </label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="price" name="price" value="<?php  echo set_value('price'); ?>"  placeholder="Plese enter actual price"  class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('price'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Discount Price </label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="discount_price" name="discount_price" value="<?php  echo set_value('discount_price'); ?>"  placeholder="Plese enter discount price"  class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('discount_price'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Delivery Charge </label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="delivery_charge" name="delivery_charge" value="<?php  echo set_value('delivery_charge'); ?>"  placeholder="Plese enter delivery charge"  class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('delivery_charge'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Quantity </label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="qty" name="qty" value="<?php  echo set_value('qty'); ?>"  placeholder="Plese enter quantity" class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('qty'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Description </label>
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="description" name="description" value="<?php  echo set_value('description'); ?>"  placeholder="Plese enter description"  class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('description'); ?></span>
							  </div>
							</div>

							<div class="form-group">
							  <!--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Location</label>-->
							  <div class="col-md-6 col-sm-6 col-xs-12">
								<input type="hidden" id="from" name="from" value="<?php  echo set_value('from'); ?>"  placeholder="Plese enter from location" required="required" class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('from'); ?></span>
							  </div>
							</div>
							
							<div class="form-group">
							  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Latlong</label>
							  <div class="col-md-3 col-sm-6 col-xs-12">
								<input type="text" id="lat" name="lat" readonly placeholder="Plese enter Latitude" onkeyup="moveMarker();" onblur="moveMarker();" value="<?php echo set_value('lat'); ?>" class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('lat'); ?></span>
							  </div>
							  <div class="col-md-3 col-sm-6 col-xs-12">
								<input type="text" id="long" name="long" readonly value="<?php  echo set_value('long'); ?>" onkeyup="moveMarker();" onblur="moveMarker();"  placeholder="Plese enter logitude" class="form-control col-md-7 col-xs-12">
								<span style="color:red;"><?php echo form_error('long'); ?></span>
							  </div>
							  <div class="col-md-3 col-sm-6 col-xs-12">
								<input id = "btnShow1" data-toggle="modal" data-target="#exampleModal" type="button" onclick="getvalue()" value="Select From location"/>
								<input  type="hidden" id = "btn1" value="from"/>
							  </div>
							</div>


							<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Product Images</label>
									<div class="col-md-6 col-sm-6 col-xs-12" style="padding-bottom: 37px;">
										<input type="file"  name="images[]" class="form-control" >
									</div>
									<div class="form-group col-md-2" id="addmore" >
										<input type="button" id="add" value="Add Image" class="btn btn-success">
						            </div>
									<span style="color:red;"><?php echo form_error('images'); ?></span>
						    </div>
							
							
							<div class="form-group col-md-12">
								<style>
									#mapCanvas {
										width: 100%;
										height: 520px;
										float: left;
									}
									#infoPanel {
										float: left;
										margin-left: 10px;
									}
									#infoPanel div {
										margin-bottom: 5px;
									}
								</style>
							
							<!-- Modal -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document" >
										<div class="modal-content" style="height: 620px;width: 665px">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Map</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<div id="mapCanvas"></div>
													<h2 id="add"></h2>
														<div id="infoPanel" style="display:none">
															<b>Marker status:</b>
															<div id="markerStatus"><i>Click and drag the marker.</i></div>
															<b>Current position:</b>
															<div id="info"></div>
															<b>Closest matching address:</b>
															<div id="address"></div>
														</div>
											</div>
										</div>
										<div class="modal-footer" style="height: 620px;width: 665px">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
										</div>
									</div>
								</div>
							</div>

							
							<div class="ln_solid"></div>
							<div class="form-group">
							  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">Submit</button>
							  </div>
							</div>

						</form>
					</div>
				</div>
            </div>
        </div>
		  
				
              </div>
             
    </div>
            <!-- /page content -->

  
<script src="<?php echo base_url(); ?>js/custom.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/jquery.datetimepicker.css"/ >
<script src="<?php echo base_url(); ?>js/datepicker/jquery.js"></script>
<script src="<?php echo base_url(); ?>js/datepicker/jquery.datetimepicker.full.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/moment/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/datepicker/daterangepicker.js"></script>

<script>  
	$(document).ready(function(){
            var i = 1;
            $('#add').click(function(){
                i++;
                $('#addmore').after('<br><div class="form-group"><div class="col-md-9 col-sm-6 col-xs-12 row'+i+'" style="padding-left: 268px;" ><input type="file"  name="images[]" class="form-control col-md-7 col-xs-12"></div><div class="form-group col-md-2" id="addmore" style="float:left;padding-left:0px;" ><input type="button" id="'+i+'" value="Delete" class="row'+i+' btn_remove btn btn-danger"></div></div>');
            });

            $(document).on('click','.btn_remove', function(){
                var button_id = $(this).attr("id");
                $(".row"+button_id+"").remove();
            });
        });
   
	function getadmin()
	{
	  var category_id = $('#category_id').val();	 
	  var params = {category_id: category_id};
		$.ajax({
			url: '<?php echo base_url();?>admin/getadmin',
			type: 'post',
			data: params,
			success: function (r)
			 {
				 $("#admin_id").html(r);
			 }
		});
	}
</script>
  
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/jquery-ui.js" type="text/javascript"></script>
<link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/themes/blitzer/jquery-ui.css" rel="stylesheet" type="text/css" />
 <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" type="text/css" href="./style.css" />
    <script src="./index.js"></script>
	<script type="text/javascript">
	function getvalue() {
		alert($(this).val());
	}
	
	$(document).ready(function(){
		$("#btnShow1").click(function(){
			$("#btn1").val('from');
			$('#myModal').on('shown.bs.modal', function () {
			$('#myInput').trigger('focus')
			})  });
	});

	var geocoder = new google.maps.Geocoder();

	function geocodePosition(pos) {
		geocoder.geocode({
			latLng: pos
		}, function(responses) {
		if (responses && responses.length > 0) {
		  updateMarkerAddress(responses[0].formatted_address);
		} else {
		  updateMarkerAddress('Cannot determine address at this location.');
		}
	  });
	}

	function updateMarkerStatus(str) {
		document.getElementById('markerStatus').innerHTML = str;
	}

	function updateMarkerPosition(latLng) {
		
		document.getElementById('info').innerHTML = [
			latLng.lat(),
			latLng.lng()
		].join(', ');
	}

	function updateMarkerAddress(str) {
		document.getElementById('address').innerHTML = str;
	}

	function moveMarker() {
		var lat = parseFloat(document.getElementById('lat').value);
		var lng = parseFloat(document.getElementById('long').value);
		
			var latLng = new google.maps.LatLng(lat, lng);
			var map = new google.maps.Map(document.getElementById('mapCanvas'), {
				zoom: 14,
				center: latLng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			var marker = new google.maps.Marker({
				position: latLng,
				title: 'Point A',
				map: map,
				draggable: true
			});

			// Update current position info.
			updateMarkerPosition(latLng);
			geocodePosition(latLng);

			// Add dragging event listeners.
			google.maps.event.addListener(marker, 'dragstart', function() {
			updateMarkerAddress('Dragging...');
			
				document.getElementById('lat').value = marker.getPosition().lat();
				document.getElementById('long').value = marker.getPosition().lng();
			});

			google.maps.event.addListener(marker, 'drag', function() {
				updateMarkerStatus('Dragging...');
				updateMarkerPosition(marker.getPosition());
		
				document.getElementById('lat').value = marker.getPosition().lat();
				document.getElementById('long').value = marker.getPosition().lng();
			});

			google.maps.event.addListener(marker, 'dragend', function() {
				updateMarkerStatus('Drag ended');
				geocodePosition(marker.getPosition());
			
				document.getElementById('lat').value = marker.getPosition().lat();
				document.getElementById('long').value = marker.getPosition().lng();
			});
	}

	function initialize() {
		var latLng = new google.maps.LatLng(22.71792, 75.8333);
		var map = new google.maps.Map(document.getElementById('mapCanvas'), {
			zoom: 14,
			center: latLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		var marker = new google.maps.Marker({
			position: latLng,
			title: 'Point A',
			map: map,
			draggable: true
		});

		// Update current position info.
		updateMarkerPosition(latLng);
		geocodePosition(latLng);

		// Add dragging event listeners.
		google.maps.event.addListener(marker, 'dragstart', function() {
		updateMarkerAddress('Dragging...');
		
			document.getElementById('lat').value = marker.getPosition().lat();
			document.getElementById('long').value = marker.getPosition().lng();
		});

		google.maps.event.addListener(marker, 'drag', function() {
			updateMarkerStatus('Dragging...');
			updateMarkerPosition(marker.getPosition());
		
			document.getElementById('lat').value = marker.getPosition().lat();
			document.getElementById('long').value = marker.getPosition().lng();
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			updateMarkerStatus('Drag ended');
			geocodePosition(marker.getPosition());
		
			document.getElementById('lat').value = marker.getPosition().lat();
			document.getElementById('long').value = marker.getPosition().lng();
		});
	}
	// Onload handler to fire off the app.
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>

	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCRo7V4f7wZd0vROUSNM0joDbY1AoRLe6k&callback=myMap"></script>
		
