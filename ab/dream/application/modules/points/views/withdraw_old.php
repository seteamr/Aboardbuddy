<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Withdraw Point
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div> 
		<?php if($msg!=''){ ?>
		   <div class="alert alert-success " role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <?php echo $msg; ?>
			</div>
		<?php } ?>
		<div class="main-card mb-3 card">
			<div class="card-body">
				<?php if($this->session->userdata('point_password_w')!=""){ ?>
				<h5 class="card-title">Please Select Withdraw Details</h5>
				<?php } ?>
				
				<form action="<?php echo base_url(); ?>points/withdraw" class="needs-validation" novalidate method="post" >
					<?php if($this->session->userdata('point_password_w')==""){ ?>
					
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="user_name">Point Password </label>
							<input type="password" name="point_password" class="form-control" value="<?php echo set_value('point_password'); ?>" required>	
							<span style="color:red;"> 
								<?php echo form_error('point_password'); ?>
							</span>
						</div>
					</div>
													
					<button class="btn btn-primary" type="submit">Submit</button>
					<?php }else{ ?>
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="user_name">Current Points </label>
							<input disabled type="text" class="form-control" value="<?php echo $balance; ?>" required>	
						</div>						
						
						<?php
						
						$user_master_id 	= $this->session->userdata('user_master_id');
						$mydata 			= $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
						
						$users ="";
						//if you admin
						if($this->session->userdata('user_type')=='3'){
							$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'2','is_user_deleted'=>'0'));
						}
						
						//if you distributer
						if($this->session->userdata('user_type')=='2'){							
							$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'1','is_user_deleted'=>'0'));							
						}
						
						//if you Retails
						if($this->session->userdata('user_type')=='1'){							
							$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'0','is_user_deleted'=>'0'));							
						}
						
						?>
						<div class="col-md-3 mb-3">
							<label for="user_master_id">Select User</label>
							<select name="to_user_master_id" onchange="get_points();" id="to_user_master_id" class="form-control" required>
								<option value="">Select User</option>
								<?php if($users!=""){ ?>
								<?php foreach($users as $us){ ?>
								<option  value="<?php echo $us->user_master_id; ?>"  <?php if(set_value('to_user_master_id')==$us->user_master_id){ echo "selected"; } ?> ><?php echo $us->user_name; ?> (<?php echo $us->name; ?>)</option>
								<?php } ?>								
								<?php } ?>								
							</select>
							<span style="color:red;"> 
								<?php echo form_error('to_user_master_id'); ?>
							</span>
						</div>	
					<?php 
					
					$ubalance = $this->common_model->getcurrent_balance(set_value('to_user_master_id'));
					?>		
						<div class="col-md-4 mb-3">
							<label for="user_name">User Current Points </label>
							<input disabled type="text" id="user_current_points" class="form-control" value="<?php echo $ubalance; ?>" required>	
						</div>	
						
					</div>
					
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="user_name">Withdraw Points </label>
							<input type="number" max="<?php echo $ubalance; ?>" min="0" onkeyup="if(this.value > <?php echo $ubalance; ?> || this.value <= 0) this.value = null;" id="withdraw_points" name="withdraw_points" class="form-control" value="<?php echo set_value('withdraw_points'); ?>" required>	
							<span style="color:red;"> 
								<?php echo form_error('withdraw_points'); ?>
							</span>
						</div>
					</div>
													
					<button class="btn btn-primary" type="submit">Withdraw</button>
				<?php } ?>
				</form>

			</div>
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">

	function get_points(){
		var to_user_master_id = $("#to_user_master_id").val();			
		var params = {to_user_master_id: to_user_master_id};
		$.ajax({
			url: '<?php echo base_url();?>points/get_balance',
			type: 'post',
			data: params,
			success: function (r)
			 { 
				var rr = r.trim();
				$("#user_current_points").val(rr);
				$("#withdraw_points").attr('max',rr);
				$("#withdraw_points").attr('onkeyup','if(this.value > '+rr+' || this.value <= 0) this.value = null;');
			 }
		});  
		
	}
    </script>
	