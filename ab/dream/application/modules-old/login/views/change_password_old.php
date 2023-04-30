<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Change Password
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div>            
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Please Change Your Password</h5>
				<?php if($msg!=''){ ?>
				   <div class="alert alert-success " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $msg; ?>
					</div>
				<?php } ?>
				<form action="<?php echo base_url(); ?>login/change_password"  method="post" enctype="multipart/form-data">
					
						<div class="col-md-6 mb-2">
							<label for="user_name">Current Password</label>
							<input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" value="<?php echo set_value('current_password'); ?>">							
							<span style="color:red;"> 
								<?php echo form_error('current_password'); ?>
							</span>
						</div>
						<div class="col-md-6 mb-2">
							<label for="user_name">New Password</label>
							<input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" value="<?php echo set_value('new_password'); ?>">							
							<span style="color:red;"> 
								<?php echo form_error('new_password'); ?>
							</span>
						</div>

						<div class="col-md-6 mb-2">
							<label for="user_name">Confirm Password</label>
							<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="<?php echo set_value('confirm_password'); ?>">							
							<span style="color:red;"> 
								<?php echo form_error('confirm_password'); ?>
							</span>
						</div>
								
					<input type="submit" name="submit" value="Change" class="btn btn-primary">
				</form>

			</div>
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
	