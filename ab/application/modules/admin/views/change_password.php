 <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>
                    Change Passowrd
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
                  <h2>Enter Below New Password details <small></small></h2>
                 
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form id="demo-form2" action="" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Old Password <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="old_password" name="old_password" value="<?php  echo set_value('old_password'); ?>"  placeholder="Plese enter Old Password" class="form-control col-md-7 col-xs-12">
						<span style="color:red;"><?php echo form_error('old_password'); ?></span>
					  </div>
                    </div>
					
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">New Password <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="new_password" name="new_password" value="<?php  echo set_value('new_password'); ?>"  placeholder="Plese enter New Password" class="form-control col-md-7 col-xs-12">
						<span style="color:red;"><?php echo form_error('new_password'); ?></span>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">New Confirm Password <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" id="c_password" name="c_password" value="<?php  echo set_value('c_password'); ?>"  placeholder="Plese enter New Confirm Password" class="form-control col-md-7 col-xs-12">
						<span style="color:red;"><?php echo form_error('c_password'); ?></span>
					  </div>
                    </div>
                   
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-3 ">
                        <button type="submit" class="btn btn-success">Change Password</button>
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
    padding-top:7px;
	 padding-left:15px;
    margin-bottom: 0;
    text-align: left;
}
  .mylist {
	border: 1px ;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	}
	
  </style>
  