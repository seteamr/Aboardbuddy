<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>View User
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div>            
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">User Details</h5>
				
				<form action="<?php echo base_url(); ?>users" class="needs-validation" novalidate method="post" >
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="user_name">Name </label>
							<input disabled type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name" value="<?php echo $user->name; ?>" required>							
							<span style="color:red;"> 
								<?php echo form_error('user_name'); ?>
							</span>
						</div>
						
						<div class="col-md-4 mb-3">
							<label for="user_name">User Name </label>
							<input disabled type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name" value="<?php echo $user->user_name; ?>" required>							
							<span style="color:red;"> 
								<?php echo form_error('user_name'); ?>
							</span>
						</div>
						
					</div>
					<div class="form-row">						
						<div class="col-md-3 mb-3">
							<label for="user_type">User Type</label>
							<select disabled name="user_type" id="user_type" onchange="getreporting_user('<?php echo $user->user_master_id; ?>');" class="form-control" required>
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
							<input disabled type="number" max="<?php echo $com; ?>" min="0" onkeyup="if(this.value > <?php  echo $com; ?> || this.value < 0) this.value = null;" class="form-control" id="user_comission" name="user_comission" placeholder="Enter Comission" value="<?php echo $user->user_comission; ?>" required>							
							<?php }else{ ?>
							<input disabled type="number" max="10.00" min="0" onkeyup="if(this.value > 10 || this.value < 0) this.value = null;" class="form-control" id="user_comission" name="user_comission" placeholder="Enter Comission" value="<?php echo $user->user_comission; ?>" required>							
							<?php } ?>
							<span style="color:red;"> 
								<?php echo form_error('user_comission'); ?>
							</span>
						</div>
						<div id="shop_master_id_div" class="col-md-3 mb-3" <?php if($user->user_type=="1" ){ echo "style='display:block;';"; }else{ echo "style='display:none;';"; } ?>>
							<label for="shop_master_id">Shop</label>
							<select disabled name="shop_master_id" id="shop_master_id" class="form-control" required>
								<option value="">Select Shop</option>
								<?php if(count($shops)>0){ ?>
								<?php foreach($shops as $sh){ ?>
									<?php $chk_exist = $this->common_model->getsingle('user_master',array('shop_master_id'=>$sh->shop_master_id)); ?>
									<?php if(!$chk_exist){ ?>
									<option  value="<?php echo $sh->shop_master_id; ?>"  <?php if(set_value('shop_master_id')==$sh->shop_master_id){ echo "selected"; } ?> ><?php echo $sh->shop_name; ?></option>
									<?php } ?>
								<?php } ?>								
								<?php } ?>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('shop_master_id'); ?>
							</span>
						</div>
						
					</div>
					
					<div class="form-row">
						<?php if($this->session->userdata('user_type')=='3'){ ?>
						<div class="col-md-3 mb-3">
							<label for="point_password">Winning Distribution</label>
							<input disabled type="text" class="form-control" id="winning_distribution" name="winning_distribution" value="<?php echo $user->winning_distribution; ?>">
							<span style="color:red;"> 
								<?php echo form_error('winning_distribution'); ?>
							</span>
						</div>
						<div class="col-md-3 mb-3">
							<label for="point_password">Max Winning</label>
							<input disabled type="number" class="form-control" onkeyup="chk3();" onblur="chk3();" id="max_winning" name="max_winning" placeholder="Max Winning" value="<?php echo $user->max_winning; ?>">
							<span style="color:red;"> 
								<?php echo form_error('max_winning'); ?>
							</span>
						</div>
						<div class="col-md-3 mb-3">
							<label for="point_password">Bonus %</label>
							<input disabled type="number" onkeyup="chk();" onblur="chk();" class="form-control" id="bonus_percent" name="bonus_percent" placeholder="Bonus %" value="<?php echo $user->bonus_percent; ?>">
							<span style="color:red;"> 
								<?php echo form_error('bonus_percent'); ?>
							</span>
						</div>
						<?php } ?>
						<div class="col-md-3 mb-3">
							<label for="is_user_deleted">Active Status</label>
							<select disabled name="is_user_deleted" id="is_user_deleted" class="form-control" required>
								<option value="">Select Status</option>
								<option value="0" <?php if($user->is_user_deleted=="0"){ echo "selected"; } ?> >Active</option>
								<option value="1" <?php if($user->is_user_deleted=="1"){ echo "selected"; } ?> >Disable</option>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('is_user_deleted'); ?>
							</span>
						</div>
						
					</div>
					
					
					<div class="form-row">
						
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Added By</label>
							<select disabled name="is_shop_deleted" id="validationCustom05" class="form-control" required>
								<option value=""></option>
								<?php foreach($users as $u){ ?>
								<option <?php if($user->created_user_master_id==$u->user_master_id){ echo "selected"; } ?> ><?php echo $u->user_name; ?></option>
								<?php } ?>
							</select>							
						</div>
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Added By Date</label>
							<input disabled type="text" class="form-control" id="validationCustom02" value="<?php echo $user->created_date; ?>" required>							
						</div>
				  </div>
				  <div class="form-row">
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Updated By</label>
							<select disabled name="is_shop_deleted" id="validationCustom05" class="form-control" required>
								<option value=""></option>
								<?php foreach($users as $u){ ?>
								<option <?php if($user->updated_user_master_id==$u->user_master_id){ echo "selected"; } ?> ><?php echo $u->user_name; ?></option>
								<?php } ?>
							</select>							
						</div>
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Updated By Date</label>
							<input disabled type="text" class="form-control" id="validationCustom02" value="<?php echo $user->updated_date; ?>" required>							
						</div>
				  </div>
				  <div class="form-row">	
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Deleted By</label>
							<select disabled name="is_shop_deleted" id="validationCustom05" class="form-control" required>
								<option value=""></option>
								<?php foreach($users as $u){ ?>
								<option <?php if($user->deleted_user_master_id==$u->user_master_id){ echo "selected"; } ?> ><?php echo $u->user_name; ?></option>
								<?php } ?>
							</select>							
						</div>
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Deleted By Date</label>
							<input disabled type="text" class="form-control" id="validationCustom02" value="<?php echo $user->deleted_date; ?>" required>							
						</div>
						
					</div>
					
					<button class="btn btn-primary" type="submit">Back</button>
				</form>

			</div>
		</div>
		
		
	</div>
	