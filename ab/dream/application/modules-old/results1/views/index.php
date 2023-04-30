<style>
ul.pagination.pagination-sm li span {
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #fff;
    background-color: #007bff;
    border: 1px solid #007bff;
}
.pagination li.active a {
    z-index: 1;
    color: #3f6ad8;
    background-color: #fff;
	border: 1px solid #dee2e6;
}
.new.col-md-1.card-shadow-primary.border.mb-1.card.card-body.border-primary {
    display: inline-block;
	max-width: 9.5%;
	padding: 0.25rem;
}
.new.col-md-1.card-shadow-primary.border.mb-1.card.card-body.border-primary h5 {
    text-align: center;
    margin: 0;
    padding: 3px;
	color: #fff;
}
h5.card-title.new {
    text-align: center;
    font-size: 20px;
}
.col-md-12.new {
    margin-left: 15px;
}
</style>
<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<!--<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div> -->
					<div>Records
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
				<div class="page-title-actions">				
				<div class="d-inline-block dropdown">
					
					<a href="<?php echo base_url(); ?>login" class="btn-shadow btn btn-info">	
						<?php if($this->session->userdata('user_master_id')==''){ ?>
						  Sign In
						<?php }else{ ?>
						Home
						<?php } ?>
					</a>
										
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
		 <div class="row">
			<div class="col-lg-12">
				
				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Search By</h5>
						<div>
							<form class="form-inline" action="<?php echo base_url('results1/index') ?>" method="post">
								
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Date</label>
									<input name="date" id="date"  value="<?php echo $date; ?>" placeholder="Enter Date" type="text" class="form-control">
								</div>
								<span id="datepicker1"></span>
							   <input type="submit" name="search" value="Search" class="btn btn-primary">
								<!-- <button class="btn btn-primary">Search</button> -->
							</form>
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
				<div class="row">
						<?php 									
							$draw_data = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$results[0]->draw_master_id));																				
						?>
						
						<div class="col-md-12">
						<div class="col-md-12 card-shadow-primary border mb-1 card card-body border-primary"><h5 class="card-title new"><?php echo date('d-M-Y',strtotime($results[0]->result_date)); ?> <?php echo $draw_data->draw_start_time; ?> To <?php echo $draw_data->draw_end_time; ?></h5></div>
						</div>
						<div class="col-md-12 new">
						<?php foreach($results as $tr){ ?>
							<?php 
							$background_colors = array('#0A6E2B', '#8E0F7E', '#1817A2', '#9F4511', '#26235b');
							$rand_background = $background_colors[array_rand($background_colors)];
							?>
						
							<div style="background:<?php echo $rand_background; ?>" class="new col-md-1 card-shadow-primary border mb-1 card card-body border-primary"><h5 class="card-title">S<?php echo $tr->series_master_id.'B'.$tr->bajar_master_id.$tr->bid_akada_number; ?></h5></div>
						<?php } ?>
						</div>
                                    
				</div>
					<div class="card-body"><h5 class="card-title">Records</h5>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>Play Start</th>
									<th>Play End</th>
									<th>Series</th>
									<th>Bajar</th>
									<th>Akada Number</th>
									<th>Balance</th>
								</tr>
								</thead>
								<tbody>
									<?php 
									
									if(count($results)>0){ ?>
									<?php foreach($results as $tr){ ?>
									<?php 									
										$draw_data = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$tr->draw_master_id));																				
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo $tr->result_date; ?></td>
										<td><?php echo $draw_data->draw_start_time; ?></td>										
										<td><?php echo $draw_data->draw_end_time; ?></td>
										<td><?php echo $tr->series_master_id; ?></td>
										<td><?php echo $tr->bajar_master_id; ?></td>
										<td><?php echo $tr->bid_akada_number; ?></td>
										<td><?php echo $tr->balance; ?></td>
									</tr>
									<?php $sno++; } ?>
									<?php } ?>
								</tbody>
							</table>
							
							
							
						</div>
						</br>
						<?php if (isset($links)) { ?>
							<nav class="" aria-label="Page navigation example">
							<?php echo $links ?>
							</nav>
						<?php } ?>
					</div>
				</div>
			</div>
			
		 </div>
	</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
$(function() {
  $('input[name="date"]').daterangepicker({
		locale: {
            format: 'DD-MMM-YYYY'
        },
		singleDatePicker: true
       
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>


	