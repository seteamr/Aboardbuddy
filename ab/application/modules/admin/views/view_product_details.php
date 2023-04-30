 <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>  
                    View Product Details
                    <small>
                       
                    </small>
                </h3>
            </div>
			
          </div>
          <div class="clearfix"></div>

          <div class="row">
			<?php if($msg!=''){ ?>
			   <div class="alert alert-success alert-dismissible fade in" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <?php echo $msg; ?>
				</div>
		  <?php } ?>
		 
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
					
					<?php 
						$category = $this->common_model->getsingle('categories', array('id'=>$records->cat_id));
						$admin = $this->common_model->getsingle('admin', array('admin_id'=>$records->admin_id,'type'=>'admin'));
						$p_type= $this->common_model->getsingle('p_type', array('id'=>$records->p_type));
						
					?>
					
					<?php if($this->session->userdata('type')=='super'){?> 
					<div class="form-group">
					  <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="first-name">Category : </label>
					  <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
						<?php  echo $category->category_name; ?>
					  </div>
					</div>
					
					<div class="form-group">
					  <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="first-name">Admin : </label>
					  <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
						<?php  echo $admin->name; ?>
					  </div>
					</div>
					<?php } ?> 
					
					<div class="form-group">
					  <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="first-name">Product Title : </label>
					  <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
						<?php  echo $records->name; ?>
					  </div>
					</div>
				
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Price :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->price." Rs."; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Product Type :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $p_type->name; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Product Type Qty :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->p_type_qty; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Discount Price :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->discount_price." Rs."; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Delivery Charge :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->delivery_charge	." Rs."; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Current Quantity :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->qty; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Description :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->description; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Product Latlong :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo $records->p_lat." - ".$records->p_long; ?>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Created Date :</label>
                      <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 8px;">
                        <?php  echo date('d M Y', strtotime($records->created_date)); ?>
					  </div>
                    </div>

					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="Phone">Product Images :</label>
					  
					  <?php foreach($p_images as $p){?>
						<div class="col-md-2">
							<?php if($p->images!=''){ ?>
								<img src="<?php echo base_url('uploads/products/'.$p->images); ?>" alt="image not found" style="float: left;width: 60%;">
							<?php }else{ echo "NA"; }?>
						</div>
					  <?php } ?>
                    </div>
					
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-3">
                        <a href="javascript: history.go(-1)" class="btn btn-primary">Back</a>
                        
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
	  
   <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

  <!-- gauge js -->
  <script type="text/javascript" src="<?php echo base_url(); ?>js/gauge/gauge.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/gauge/gauge_demo.js"></script>
  <!-- bootstrap progress js -->
  <script src="<?php echo base_url(); ?>js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="<?php echo base_url(); ?>js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="<?php echo base_url(); ?>js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="<?php echo base_url(); ?>js/moment/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="<?php echo base_url(); ?>js/chartjs/chart.min.js"></script>

  <script src="<?php echo base_url(); ?>js/custom.js"></script>

  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/jquery-jvectormap-2.0.3.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/gdp-data.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/jquery-jvectormap-world-mill-en.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/jquery-jvectormap-us-aea-en.js"></script>
  <!-- pace -->
  <script src="<?php echo base_url(); ?>js/pace/pace.min.js"></script>
  <style>
  .form-horizontal .control-label {
    text-align: left;
}
  </style>