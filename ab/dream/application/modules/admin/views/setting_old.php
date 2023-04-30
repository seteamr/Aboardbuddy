<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Setting
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
		</div>            
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Please Update Your setting</h5>
				<?php if($msg!=''){ ?>
				   <div class="alert alert-success " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $msg; ?>
					</div>
				<?php } ?>
				<form action="<?php echo base_url(); ?>admin/setting"  method="post" enctype="multipart/form-data">
					
						<div class="col-md-6 mb-2">
							<label for="user_name">Current Cron Schedular *</label>
							<select name="cron" id="cron" class="form-control" >
								<option value="1" <?php if($setting->cron=="1"){ echo "selected"; } ?> >5 Block Wise  Divided Points</option>
								<option value="0" <?php if($setting->cron=="0"){ echo "selected"; } ?> >Amount collection 80% </option>
								
							</select>
							<span style="color:red;"> 
								<?php echo form_error('cron'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Cron Schedular Distribution % *</label>
							<input type="number" class="form-control" id="distribute_per" name="distribute_per" placeholder="Cron Schedular Distribution %" value="<?php echo $setting->distribute_per; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('distribute_per'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Cancle Transaction Per Day Limit *</label>
							<input type="number" class="form-control" id="cancle_per_day_limit" name="cancle_per_day_limit" placeholder="Cancle Transaction Per Day Limit" value="<?php echo $setting->cancle_per_day_limit; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('cancle_per_day_limit'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Number Of Returing Records in History *</label>
							<input type="number" onkeyup="chk();" onblur="chk();" class="form-control" id="returning_history_records" name="returning_history_records" placeholder="Number Of Returing Records in History" value="<?php echo $setting->returning_history_records; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('returning_history_records'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Claim After days not allowed *</label>
							<input type="number" class="form-control" id="claim_before_day" name="claim_before_day" placeholder="Claim After days not allowed" value="<?php echo $setting->claim_before_day; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('claim_before_day'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">If Set Manual result and not declare after minit auto Current Cron Schedular call (minit) *</label>
							<input type="number" class="form-control" id="manual_result_after_auto" name="manual_result_after_auto" placeholder="Claim After days not allowed" value="<?php echo $setting->manual_result_after_auto; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('manual_result_after_auto'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Result Display time delay (in Minit) *</label>
							<input type="number" class="form-control" id="result_delay_time" name="result_delay_time" placeholder="Result Display time delay" value="<?php echo $setting->result_delay_time; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('result_delay_time'); ?>
							</span>
						</div>
						
						<div class="col-md-6 mb-2">
							<label for="user_name">Welcome Message *</label>
							<textarea class="form-control" id="welcome" name="welcome" placeholder="Welcome Message"><?php echo $welcome->description; ?></textarea>						
							<span style="color:red;"> 
								<?php echo form_error('welcome'); ?>
							</span>
						</div>
								
					<input type="submit" name="submit" value="Update Setting" class="btn btn-primary">
				</form>

			</div>
		</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
	<script>
	function chk(){
		var returning_history_records = $("#returning_history_records").val();
		if(returning_history_records<0)
		{
			$("#returning_history_records").val('0');
		}
		else if(returning_history_records>100)
		{
			$("#returning_history_records").val('100');
		}
		else if(!/^[0-9]+$/.test(returning_history_records)){
			$("#returning_history_records").val('');			
		 }
	}
	</script>