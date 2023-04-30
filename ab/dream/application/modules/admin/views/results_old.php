<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Add Draw for Manual Result
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
		<?php if($error_msg!=''){ ?>
		   <div class="alert alert-danger " role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <?php echo $error_msg; ?>
			</div>
		<?php } ?>
		<div class="main-card mb-3 card">
			<div class="card-body">
				<h5 class="card-title">Please select Draw</h5>
				
				<form action="<?php echo base_url(); ?>admin/results" class="needs-validation" novalidate method="post" >
						
					<div class="form-row">						
						<div class="col-md-3 mb-3">
							<label for="user_type">Select Draw</label>
							<select name="draw_master_id" id="draw_master_id"  class="form-control" required>
								<option value="">Select Draw</option>
								<?php foreach($records as $r){ 
								$fentime = date('H:i',strtotime($r->draw_end_time));
								$ctime = strtotime(date('Y-m-d H:i:s'));
								
								$wining_date_time =  strtotime ( date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':00 +1 seconds'))); 
								if($ctime <= $wining_date_time)	
								{
								?>
								<option value="<?php echo $r->draw_master_id; ?>"><?php echo $r->draw_start_time.' To '.$r->draw_end_time; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('draw_master_id'); ?>
							</span>
						</div>
						
						
					</div>
										
					<button class="btn btn-primary" type="submit">Add</button>
				</form>

			</div>
		</div>
		
		<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Manual Draw</h5>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Draw </th>
									<th>Manual Start Time</th>
									<th>Manual add Result Before Time</th>									
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
									<?php $sno=1; if(count($results)>0){ ?>
									<?php foreach($results as $r){ ?>
									<?php 
									
									$draw_master = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));
									
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo $draw_master->draw_start_time.' To '.$draw_master->draw_end_time; ?></td>
										<?php 
										$ctimefff =   date('Y-m-d H:i:s',strtotime($r->date_time.' -300 seconds')); 
										?>
										<td><?php echo $ctimefff; ?></td>
										<td><?php echo $r->expiry_date_time; ?></td>
										<td>
											<?php 
											$ctime =  strtotime ( date('Y-m-d H:i:s',strtotime($r->date_time.' -300 seconds'))); 
											
											if( strtotime(date('Y-m-d H:i:s'))>= $ctime && strtotime(date('Y-m-d H:i:s'))< strtotime($r->expiry_date_time) ){ ?>
											<a target="_Blank" href="<?php echo base_url(); ?>admin/generate_results/<?php echo $r->draw_master_id; ?>" id="PopoverCustomT-1" class="btn btn-primary btn-sm">Generate Results</a>
											<?php } ?>	
										</td>
									</tr>
									<?php $sno++; } ?>
									<?php } ?>
								</tbody>
							</table>
							
						</div>
						
					</div>
				</div>
		
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">
setInterval(function() {
   location.reload();
 }, 10000);
    </script>
	