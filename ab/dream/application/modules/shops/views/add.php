<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Add Shop
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div>            
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Please Enter Shop Details</h5>
				
				<form action="<?php echo base_url(); ?>shops/add" class="needs-validation" novalidate method="post" >
					<div class="form-row">
						<div class="col-md-4 mb-3">
							<label for="validationCustom01">Shop Name </label>
							<input type="text" class="form-control" id="validationCustom01" name="shop_name" placeholder="Shop Name" value="<?php echo set_value('shop_name'); ?>" required>							
							<span style="color:red;"> 
								<?php echo form_error('shop_name'); ?>
							</span>
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationCustom02">Shop Contact Person</label>
							<input type="text" class="form-control" id="validationCustom02" name="shop_contact_person" placeholder="Shop Contact Person" value="<?php echo set_value('shop_contact_person'); ?>" required>
							<span style="color:red;"> 
								<?php echo form_error('shop_contact_person'); ?>
							</span>
						</div>
						<div class="col-md-4 mb-3">
							<label for="validationCustom03">Shop Contact Number</label>
							<input type="number" class="form-control" id="validationCustom03" name="shop_contact_number" placeholder="Shop Contact Number" value="<?php echo set_value('shop_contact_number'); ?>" required>
							<span style="color:red;"> 
								<?php echo form_error('shop_contact_number'); ?>
							</span>
						</div>
					</div>
					<div class="form-row">
						<div class="col-md-6 mb-3">
							<label for="validationCustom04">Shop Address</label>
							<textarea name="shop_address" id="validationCustom04" placeholder="Shop Address" class="form-control" required><?php echo set_value('shop_address'); ?></textarea>
							<span style="color:red;"> 
								<?php echo form_error('shop_address'); ?>
							</span>
						</div>
						<div class="col-md-3 mb-3">
							<label for="validationCustom05">Active Status</label>
							<select name="is_shop_deleted" id="validationCustom05" class="form-control" required>
								<option value="">Select Status</option>
								<option value="0" <?php if(set_value('is_shop_deleted')=="0"){ echo "selected"; } ?> >Active</option>
								<option value="1" <?php if(set_value('is_shop_deleted')=="1"){ echo "selected"; } ?> >Disable</option>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('is_shop_deleted'); ?>
							</span>
						</div>
						
					</div>
					
					<button class="btn btn-primary" type="submit">Add Shop</button>
				</form>

			</div>
		</div>
		
	</div>
	