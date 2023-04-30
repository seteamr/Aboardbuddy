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
	color: #000;
}
h5.card-title.new {
    text-align: center;
    font-size: 20px;
}
.col-md-12.new {
    margin-left: 15px;
}
.col-md-12.sub {
    width: 98%;
}
@media only screen and (min-width: 320px) and (max-width: 480px){
.new.col-md-1.card-shadow-primary.border.mb-1.card.card-body.border-primary {
    display: inline-block;
	max-width: 8.5%;
	padding: 0.25rem;
}
	}
</style>
<div class="app-main__inner">
		<!-- <div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Results
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
				<div class="page-title-actions">				
				<div class="d-inline-block dropdown">
					
					<a href="<?php echo base_url(); ?>login" class="btn-shadow btn btn-info">	
						<?php if($this->session->userdata('user_master_id')==''){ ?>
						  Login
						<?php }else{ ?>
						Dashborad
						<?php } ?>
					</a>
										
				</div>
			</div>
			</div>
			
		 </div>   -->
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
							<form class="form-inline" action="<?php echo base_url('result/index') ?>" method="post">
								
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
						
						
						<!--<div class="col-md-12">
							<div class="col-md-12 card-shadow-primary border mb-1 card card-body border-primary"><h5 class="card-title new"><?php echo date('d-M-Y',strtotime($results[0]->result_date)); ?> <?php echo $draw_data->draw_end_time; ?></h5></div>
						</div> -->
						
						<div class="col-md-12 new">
						<?php  $draw_array = array(); ?>
						<?php foreach($results as $tr){ ?>						
						<?php 
						if(!in_array($tr->draw_master_id,$draw_array))
						{
							$draw_array[] = $tr->draw_master_id;							
							$draw_data = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$tr->draw_master_id));																				
						?>
						<div class="col-md-12 sub">
							<div class="col-md-12 card-shadow-primary border mb-1 card card-body border-primary"><h5 class="card-title new"><?php echo date('d-M-Y',strtotime($results[0]->result_date)); ?> <?php echo $draw_data->draw_end_time; ?></h5></div>
						</div>
						<?php } ?>

							<?php 
							if($tr->bajar_master_id==0){
								$rand_background='#8fbc8f';
							}elseif($tr->bajar_master_id==1){
								$rand_background='#DDA0DD';
							}elseif($tr->bajar_master_id==2){
								$rand_background='#E0FFFF';
							}elseif($tr->bajar_master_id==3){
								$rand_background='#D3D3D3';
							}elseif($tr->bajar_master_id==4){
								$rand_background='#D2B48C';
							}elseif($tr->bajar_master_id==5){
								$rand_background='#e9967a';
							}elseif($tr->bajar_master_id==6){
								$rand_background='#d8bfd8';
							}elseif($tr->bajar_master_id==7){
								$rand_background='#bdb76b';
							}elseif($tr->bajar_master_id==8){
								$rand_background='#FFDEAD';
							}elseif($tr->bajar_master_id==9){
								$rand_background='#D3D3D3';
							}
							//$rand_background = $background_colors[array_rand($background_colors)];
							
							$dup_data = $this->common_model->getsingle('result_master',array('draw_master_id'=>$tr->draw_master_id,'series_master_id'=>$tr->series_master_id,'bajar_master_id'=>$tr->bajar_master_id,'is_result_declare'=>'2'));																				
							if($dup_data && $dup_data->bid_akada_number!=$tr->bid_akada_number)
							{
								$newdable= ' / '.$tr->series_master_id.$tr->bajar_master_id.$dup_data->bid_akada_number;
							}else{
								$newdable='';
							}
														
							?>
						
							<div style="background:<?php echo $rand_background; ?>;color:#fff;" class="new col-md-1 card-shadow-primary border mb-1 card card-body border-primary"><h5 class="card-title"><?php echo $tr->series_master_id.$tr->bajar_master_id.$tr->bid_akada_number.$newdable; ?></h5></div>
						<?php } ?>
						</div>
                                    
				</div>
					<div class="card-body">
						
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


	