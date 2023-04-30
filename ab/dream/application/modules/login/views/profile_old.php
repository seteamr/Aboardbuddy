<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Profile
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div>            
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Please Update Your Profile</h5>
				<?php if($msg!=''){ ?>
				   <div class="alert alert-success " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $msg; ?>
					</div>
				<?php } ?>
				<form action="<?php echo base_url(); ?>login/profile"  method="post" enctype="multipart/form-data">
					<div class="form-row">
						<div class="col-md-6 mb-2">
							<label for="user_name">User Name</label>
							<input disabled type="text" class="form-control"  value="<?php echo $user->user_name; ?>">							
							
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="<?php echo $user->name; ?>" required>							
							<span style="color:red;"> 
								<?php echo form_error('name'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="is_user_deleted">Profile Image</label>
								<input type="file" name="profile_image" class="form-control">
							<span style="color:red;"> 
								<?php echo $errors; ?>
							</span>
						</div>
						<?php if($user->profile_image!=''){ ?>
						<div class="col-md-6 mb-2">
							<label for="is_user_deleted">Current Image</label>
							<img src="<?php echo base_url('uploads/profile_image/'.$user->profile_image) ?>" width="100px">
						</div>
						<?php } ?>
						
					</div>
										
					<input type="submit" name="submit" value="Update" class="btn btn-primary">
				</form>

			</div>
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- <script type="text/javascript">

    function getreporting_user()
    {
		var user_type = $("#user_type").val();
		$("#user_comission").val('');
		
		if(user_type==1)
		{
			$("#user_comission_div").hide();
			$("#shop_master_id_div").show();
		}else{
			$("#shop_master_id_div").hide();
			if(user_type==2)
			{
				$("#user_comission_div").show();
				$("#com").html('Comission (max 10.00)');
				$("#user_comission").attr('max','10.00');
				$("#user_comission").attr('onkeyup','if(this.value > 10.00 || this.value < 0) this.value = null;');
			}
		}
		
		var params = {user_type: user_type};
		$.ajax({
			url: '<?php echo base_url();?>users/getreporting_user',
			type: 'post',
			data: params,
			success: function (r)
			 {
				 $("#reporting_user_master_id").html(r);
			 }
		});                     
    }
	function getcommision(){
		var user_type = $("#user_type").val();
		var user_master_id = $("#reporting_user_master_id").val();
		if(user_type==1)
		{
			var params = {user_master_id: user_master_id};
			$.ajax({
				url: '<?php echo base_url();?>users/get_user_commision',
				type: 'post',
				data: params,
				success: function (rr)
				 { 
					var r = rr.trim();
					$("#com").html('Comission (max '+r+')');
					$("#user_comission_div").show();
					$("#user_comission").attr('max',r);
					$("#user_comission").attr('onkeyup','if(this.value >'+r+' || this.value < 0) this.value = null;');
				 }
			});  
		}
	}
    </script> -->
	