<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Edit User
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div>            
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Please Enter User Details</h5>
				
				<form action="<?php echo base_url(); ?>users/edit/<?php echo $user->user_master_id; ?>" class="needs-validation" novalidate method="post" enctype="multipart/form-data">
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="user_name">Full Name </label>
							<input type="text" class="form-control" id="name" name="name" placeholder="User Name" value="<?php echo $user->name; ?>" required>							
							<span style="color:red;"> 
								<?php echo form_error('name'); ?>
							</span>
						</div>
						<div class="col-md-4 mb-3">
							<label for="user_name">User Name </label>
							<input type="text" class="form-control" disabled placeholder="User Name" value="<?php echo $user->user_name; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('user_name'); ?>
							</span>
						</div>
						<div class="col-md-3 mb-3">
							<label for="user_password">Password</label>
							<input type="text" class="form-control" id="user_password" name="user_password" placeholder="Enter Password" value="<?php echo $user->user_password; ?>" required>
							<span style="color:red;"> 
								<?php echo form_error('user_password'); ?>
							</span>
						</div>
					<!--	<div class="col-md-3 mb-3">
							<label for="c_user_password">Confirm Password</label>
							<input type="password" class="form-control" id="c_user_password" name="c_user_password" placeholder="Enter Confirm Password" value="<?php echo set_value('c_user_password'); ?>" required>
							<span style="color:red;"> 
								<?php echo form_error('c_user_password'); ?>
							</span>
						</div> -->
					</div>
					
					<div class="form-row">						
						<div class="col-md-3 mb-3">
							<label for="user_type">User Type</label>
							<select disabled name="user_type" id="user_type" onchange="getreporting_user('<?php echo $user->user_master_id; ?>');" class="form-control" >
								<option value="">Select Type</option>								
								<?php if($this->session->userdata('user_type')=='3'){ ?>
								<option value="0" <?php if($user->user_type=="0"){ echo "selected"; } ?> >Player</option>
								<option value="1" <?php if($user->user_type=="1"){ echo "selected"; } ?> >Retailer</option>
								<option value="2" <?php if($user->user_type=="2"){ echo "selected"; } ?> >Distributor</option>
								<?php } ?>
								
								<?php if($this->session->userdata('user_type')=='2'){ ?>
								<option value="0" <?php if($user->user_type=="0"){ echo "selected"; } ?> >Player</option>
								<option value="1" <?php if($user->user_type=="1"){ echo "selected"; } ?> >Retailer</option>
								<?php } ?>
								
								<?php if($this->session->userdata('user_type')=='1'){ ?>
								<option value="0" <?php if($user->user_type=="0"){ echo "selected"; } ?> >Player</option>
								<?php } ?>
								
							</select>
							<span style="color:red;"> 
								<?php echo form_error('user_type'); ?>
							</span>
						</div>
						
						<?php
							$users ="";
						if($user->user_type=="0"){
							$users = $this->common_model->getAllwhere('user_master',array('user_master_id !='=>$user->user_master_id,'user_type'=>'1','is_user_deleted'=>'0'));
						}
						else if($user->user_type=="1")
						{
							$users = $this->common_model->getAllwhere('user_master',array('user_master_id !='=>$user->user_master_id,'user_type'=>'2','is_user_deleted'=>'0'));
						}
						else if($user->user_type=="2")
						{
							$users = $this->common_model->getAllwhere('user_master',array('user_master_id !='=>$user->user_master_id,'user_type'=>'3','is_user_deleted'=>'0'));
						}
						
						$shops = $this->common_model->getAllwhere('shop_master',array('is_shop_deleted'=>'0'));
						?>
						<div class="col-md-3 mb-3">
							<label for="reporting_user_master_id">Reporting User</label>
							<select disabled onchange="getcommision();" name="reporting_user_master_id" id="reporting_user_master_id" class="form-control" required>
								<option value="">Select User</option>
								<?php if($users!=""){ ?>
								<?php foreach($users as $us){ ?>
								<option  value="<?php echo $us->user_master_id; ?>"  <?php if($user->reporting_user_master_id==$us->user_master_id){ echo "selected"; } ?> ><?php echo $us->user_name; ?></option>
								<?php } ?>								
								<?php } ?>								
							</select>
							<span style="color:red;"> 
								<?php echo form_error('reporting_user_master_id'); ?>
							</span>
						</div>
						
						<div id="user_comission_div" class="col-md-3 mb-3" <?php if($user->user_type=="2" or $user->user_type=="1" ){ echo "style='display:block;';"; }else{ echo "style='display:none;';"; } ?> >
							<label for="user_comission" id="com">Comission </label>
							<?php 
							if($user->user_type=="1")
							{
								$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$user->reporting_user_master_id));
								$com = $user_data->user_comission;
							?>
							<input type="number" max="<?php echo $com; ?>" min="0" onkeyup="if(this.value > <?php  echo $com; ?> || this.value < 0) this.value = null;" class="form-control" id="user_comission" name="user_comission" placeholder="Enter Comission" value="<?php echo $user->user_comission; ?>" required>							
							<?php }else{ ?>
							<input type="number" max="10.00" min="0" onkeyup="if(this.value > 10 || this.value < 0) this.value = null;" class="form-control" id="user_comission" name="user_comission" placeholder="Enter Comission" value="<?php echo $user->user_comission; ?>" required>							
							<?php } ?>
							<span style="color:red;"> 
								<?php echo form_error('user_comission'); ?>
							</span>
						</div>
						<!--
						<div id="shop_master_id_div" class="col-md-3 mb-3" <?php if($user->user_type=="1" ){ echo "style='display:block;';"; }else{ echo "style='display:none;';"; } ?>>
							<label for="shop_master_id">Shop</label>
							<select name="shop_master_id" id="shop_master_id" class="form-control" required>
								<option value="">Select Shop</option>
								<?php if(count($shops)>0){ ?>
								<?php foreach($shops as $sh){ ?>
									<?php $chk_exist = $this->common_model->getsingle('user_master',array('user_master_id !='=>$user->user_master_id,'shop_master_id'=>$sh->shop_master_id)); ?>
									<?php if(!$chk_exist){ ?>
									<option  value="<?php echo $sh->shop_master_id; ?>"  <?php if($user->shop_master_id==$sh->shop_master_id){ echo "selected"; } ?> ><?php echo $sh->shop_name; ?></option>
									<?php } ?>
								<?php } ?>								
								<?php } ?>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('shop_master_id'); ?>
							</span>
						</div>
						-->
					</div>
					
					<div class="form-row">
						<!--<div class="col-md-4 mb-3">
							<label for="point_password">Point Transfer Password</label>
							<input type="password" class="form-control" id="point_password" name="point_password" placeholder="Enter Point Transfer Password" value="<?php echo set_value('point_password'); ?>" required>
							<span style="color:red;"> 
								<?php echo form_error('point_password'); ?>
							</span>
						</div> -->
						<?php if($this->session->userdata('user_type')=='3'){ ?>
						<div class="col-md-3 mb-3">
							<label for="point_password">Winning Distribution</label>
							<input type="number" class="form-control" onkeyup="chk2();" onblur="chk2();" id="winning_distribution" name="winning_distribution" value="<?php echo $user->winning_distribution; ?>">
							<span style="color:red;"> 
								<?php echo form_error('winning_distribution'); ?>
							</span>
						</div>
						<div class="col-md-3 mb-3">
							<label for="point_password">Max Winning</label>
							<input type="number" class="form-control" onkeyup="chk3();" onblur="chk3();" id="max_winning" name="max_winning" placeholder="Max Winning" value="<?php echo $user->max_winning; ?>">
							<span style="color:red;"> 
								<?php echo form_error('max_winning'); ?>
							</span>
						</div>
						<div class="col-md-3 mb-3">
							<label for="point_password">Bonus %</label>
							<input type="number" onkeyup="chk();" onblur="chk();" class="form-control" id="bonus_percent" name="bonus_percent" placeholder="Bonus %" value="<?php echo $user->bonus_percent; ?>">
							<span style="color:red;"> 
								<?php echo form_error('bonus_percent'); ?>
							</span>
						</div>
						<?php } ?>
						
						<div class="col-md-3 mb-3">
							<label for="is_user_deleted">Active Status</label>
							<select name="is_user_deleted" id="is_user_deleted" class="form-control" required>
								<option value="">Select Status</option>
								<option value="0" <?php if($user->is_user_deleted=="0"){ echo "selected"; } ?> >Active</option>
								<option value="1" <?php if($user->is_user_deleted=="1"){ echo "selected"; } ?> >Disable</option>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('is_user_deleted'); ?>
							</span>
						</div>

						<div class="col-md-4 mb-3">
							<label for="user_name">Profile Image</label>
							<input type="file" class="form-control" id="profile_image" name="profile_image" placeholder="Choose file" value="<?php echo set_value('profile_image'); ?>" required>							
							<span style="color:red;"> 
								<?php echo $errors; ?>
							</span>
						</div>
						<?php if($user->profile_image!=''){ ?>
						<div class="col-md-4 mb-3">
							<label for="user_name">Current Image</label>
							<img src="<?php echo base_url('uploads/profile_image/'.$user->profile_image) ?>" width="100px">	
						</div>
						<?php } ?>
					</div>
										
					<button class="btn btn-primary" type="submit">Update User</button>
				</form>

			</div>
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">
	function chk3(){
		var max_winning = $("#max_winning").val();
		if(max_winning<0)
		{
			$("#max_winning").val('0');
		}
		else if(max_winning>100000)
		{
			$("#max_winning").val('100000');
		}
		
	}
	
	function chk2(){
		var winning_distribution = $("#winning_distribution").val();
		if(winning_distribution<0)
		{
			$("#winning_distribution").val('0');
		}
		else if(winning_distribution>200)
		{
			$("#winning_distribution").val('200');
		}
		
	}
	function chk(){
		var bonus_percent = $("#bonus_percent").val();
		if(bonus_percent<0)
		{
			$("#bonus_percent").val('0');
		}
		else if(bonus_percent>5)
		{
			$("#bonus_percent").val('5');
		}
		
	}
    function getreporting_user(user_master_id)
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
		
		var params = {user_type: user_type,user_master_id:user_master_id};
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
    </script>
	