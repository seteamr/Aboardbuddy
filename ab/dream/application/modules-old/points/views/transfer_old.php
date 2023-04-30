<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Transfer Point
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
				<?php if($this->session->userdata('point_password')!=""){ ?>
				<h5 class="card-title">Please Select Transfer Details</h5>
				<?php } ?>
				
				<form action="<?php echo base_url(); ?>points/transfer" class="needs-validation" novalidate method="post" >
					<?php if($this->session->userdata('point_password')==""){ ?>
					
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
						<div class="col-md-3 mb-3">
							<label for="user_type">User Type</label>
							<select name="user_type" id="user_type" onchange="get_user();" class="form-control" required>
								<option value="">Select Type</option>								
								<?php if($this->session->userdata('user_type')=='3'){ ?>
								<option value="2" <?php if(set_value('user_type')=="2"){ echo "selected"; } ?> >Distributor</option>
								<?php } ?>
								
								<?php if($this->session->userdata('user_type')=='2'){ ?>
								<option value="3" <?php if(set_value('user_type')=="3"){ echo "selected"; } ?> >Admin</option>
								<option value="1" <?php if(set_value('user_type')=="1"){ echo "selected"; } ?> >Retailer</option>
								<?php } ?>
								
								<?php if($this->session->userdata('user_type')=='1'){ ?>
								<option value="2" <?php if(set_value('user_type')=="2"){ echo "selected"; } ?> >Distributor</option>
								<option value="0" <?php if(set_value('user_type')=="0"){ echo "selected"; } ?> >Player</option>
								<?php } ?>
								
								<?php if($this->session->userdata('user_type')=='0'){ ?>
								<option value="1" <?php if(set_value('user_type')=="1"){ echo "selected"; } ?> >Retailer</option>
								<?php } ?>
								
							</select>
							<span style="color:red;"> 
								<?php echo form_error('user_type'); ?>
							</span>
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
							if(set_value('user_type')==3)
							{
								$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'3','is_user_deleted'=>'0'));
							}else{
								$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'1','is_user_deleted'=>'0'));
							}
						
						}
						
						//if you Retails
						if($this->session->userdata('user_type')=='1'){
							if(set_value('user_type')==2)
							{
								$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$mydata->reporting_user_master_id,'user_type'=>'2','is_user_deleted'=>'0'));
							}else{
								$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'0','is_user_deleted'=>'0'));
							}
						
						}
						
						//if you Player
						if($this->session->userdata('user_type')=='0'){			
								$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$mydata->reporting_user_master_id,'user_type'=>'1','is_user_deleted'=>'0'));			
						}
						
						
						?>
						<div class="col-md-3 mb-3">
							<label for="user_master_id">Select User</label>
							<select onchange="get_balance();" name="to_user_master_id" id="to_user_master_id" class="form-control" required>
								<option value="">Select User</option>
								<?php if($users!=""){ ?>
								<?php foreach($users as $us){ ?>
								<option  value="<?php echo $us->user_master_id; ?>"  <?php if(set_value('to_user_master_id')==$us->user_master_id){ echo "selected"; } ?> ><?php echo $us->user_name; ?> ( <?php echo $us->name; ?> )</option>
								<?php } ?>								
								<?php } ?>		 						
							</select>
							<span style="color:red;"> 
								<?php echo form_error('to_user_master_id'); ?>
							</span>
						</div>
						
					</div>
										
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="user_name">To User Current Balance </label>
							<div class="form-control" id="to_c_balance" > <?php echo $to_c_balance; ?> </div>	
							
						</div>
						<div class="col-md-4 mb-3">
							<label for="user_name">Transfer Points </label>
							<input type="number" max="<?php echo $balance; ?>" min="0" onkeyup="if(this.value > <?php echo $balance; ?> || this.value < 0) this.value = null;" name="transfer_points" class="form-control" value="<?php echo set_value('transfer_points'); ?>" required>	
							<span style="color:red;"> 
								<?php echo form_error('transfer_points'); ?>
							</span>
						</div>
					</div>
													
					<button class="btn btn-primary" type="submit">Transfer</button>
				<?php } ?>
				</form>

			</div>
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">
	function get_user(){
		var user_type = $("#user_type").val();		
		var params = {user_type: user_type};
		$("#to_c_balance").html('0');
		$.ajax({
			url: '<?php echo base_url();?>points/get_users',
			type: 'post',
			data: params,
			success: function (rr)
			 { 
				$("#to_user_master_id").html(rr);
			 }
		}); 
	}
	
	function get_balance(){
		var to_user_master_id = $("#to_user_master_id").val();		
		var params = {to_user_master_id: to_user_master_id};
		$.ajax({
			url: '<?php echo base_url();?>points/get_balance',
			type: 'post',
			data: params,
			success: function (rr)
			 { 
				$("#to_c_balance").html(rr);
			 }
		});  
		
	}
    </script>
	