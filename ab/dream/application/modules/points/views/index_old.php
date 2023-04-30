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
</style>
<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Points Transaction
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
				<div class="page-title-actions">				
				<div class="d-inline-block dropdown">
					<a href="<?php echo base_url(); ?>points/transfer" class="btn-shadow btn btn-info">						
					  +	Transfer
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
							<form class="form-inline" action="<?php echo base_url('points/index') ?>" method="post">
								<!-- <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group"><label for="exampleEmail22" class="mr-sm-2">UserName</label> -->
									<!-- <input name="user_name" id="name" placeholder="Enter Name" type="text" class="form-control"> -->
									<!-- <select class="form-control" name="user_name" id="name">
										<option>Select Users</option>
										<?php foreach ($user_master as $value) { ?>
											<option value="<?php echo $value->user_master_id; ?>"><?php echo $value->user_name; ?></option>
										<?php } ?>
									</select> -->
								<!-- </div> -->
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">From Date</label>
									<input name="from_date" id="from_date11"  value="<?php echo $from_date; ?>" placeholder="Enter From date" type="date" class="form-control">
								</div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">To Date</label>
									<input name="to_date" id="to_date11"  value="<?php echo $to_date; ?>" placeholder="Enter To date" type="date" class="form-control">
								</div>
								<span id="datepicker1"></span>
								<?php if($this->session->userdata('user_type')!='1' && $this->session->userdata('user_type')!='0'){ ?>
								 <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">UserName</label>
									<input name="username" id="from_to_date" placeholder="Enter Username" type="text" value="<?php echo $username; ?>" class="form-control">
								</div> 
								<?php } ?>	
							   <input type="submit" name="search" value="Search" class="btn btn-primary">
								<!-- <button class="btn btn-primary">Search</button> -->
							</form>
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Transaction</h5>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>From User</th>									
									<th>To user </br> ( Transaction With )</th>
									<th style="text-align:right;">Opning</th> 
									<th style="text-align:right;">Credit</th>
									<th style="text-align:right;">Debit</th>
									<th style="text-align:right;" >Balance</th>
									
									<th>Narration</th>									
									<th>Nature</th>
									<?php if($this->session->userdata('user_type')=='3'){ ?>
									<!--<th>Reference No.</th> -->
									<?php } ?>
								</tr>
								</thead>
								<tbody>
								<?php 
								$all_cr = 0;
								$all_dr = 0;
								$all_bal = 0;
								?>
									<?php if(count($transactions)>0){ ?>
									<?php foreach($transactions as $tr){ ?>
									<?php 									
										$u_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$tr->from_user_master_id));										
										$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$tr->to_user_master_id));										
										$ref_data = $this->common_model->getsingle('draw_transaction_master',array('draw_transaction_master_id'=>$tr->draw_transaction_master_id));										
									
										$ubalance = $this->common_model->getsingle('points_transactions',array('from_user_master_id'=>$tr->to_user_master_id,'to_user_master_id'=>$tr->from_user_master_id,'tr_no'=>$tr->tr_no));
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo date('d-M-Y',strtotime($tr->transactions_date)) .' '.date('H:i',strtotime($tr->created_date)); ?></td>										
										
										
										<?php if($this->session->userdata('user_type')=='3'){ ?>
										<td><?php echo $u_data->user_name; ?></td>
										<td><?php echo $user_data->user_name; ?></td>
										<td style="text-align:right;" ><?php $opp = $ubalance->opening_points; echo $ubalance->opening_points; ?></td>
										<?php } ?>
										
										<?php if($this->session->userdata('user_type')=='2'){ ?>
											<?php if($user_data->user_type==3){ ?>
											<td><?php echo $user_data->user_name; ?></td>
											<td><?php echo $u_data->user_name; ?></td>											
											<td style="text-align:right;" ><?php $opp = $tr->opening_points; echo $tr->opening_points; ?></td>
											<?php }else{ ?>
											<td><?php echo $u_data->user_name; ?></td>
											<td><?php echo $user_data->user_name; ?></td>
											<td style="text-align:right;" ><?php $opp = $ubalance->opening_points; echo $ubalance->opening_points; ?></td>
											<?php } ?>
										<?php } ?>
										<?php if($this->session->userdata('user_type')=='1'){ ?>
											<?php if($user_data->user_type==2){ ?>
											<td><?php echo $user_data->user_name; ?></td>
											<td><?php echo $u_data->user_name; ?></td>
											<td style="text-align:right;" ><?php $opp = $tr->opening_points; echo $tr->opening_points; ?></td>
											<?php }else{ ?>
											<td><?php echo $u_data->user_name; ?></td>
											<td><?php echo $user_data->user_name; ?></td>
											<td style="text-align:right;" ><?php $opp = $ubalance->opening_points; echo $ubalance->opening_points; ?></td>
											<?php } ?>
										<?php } ?>
										
										
										
										<?php if($this->session->userdata('user_type')=='3'){ ?>
											<?php if($tr->transaction_narration=="Point Send"){ ?>										
												<td style="text-align:right;" ><?php $all_cr = $all_cr + $tr->points_transferred; $bal = $opp + $tr->points_transferred;  echo $tr->points_transferred; ?></td>
												<td ></td>
											<?php }else{ ?>
												<td ></td>
												<td style="text-align:right;" ><?php $all_dr = $all_dr + $tr->points_transferred; $bal = $opp - $tr->points_transferred;  echo $tr->points_transferred; ?></td>
											<?php } ?>
										<?php } ?> 
										
										<?php if($this->session->userdata('user_type')=='2'){ ?>
											<?php if($user_data->user_type==3){ ?>
												<?php if($tr->transaction_narration=="Point Received"){ ?>										
													<td style="text-align:right;" ><?php $all_cr = $all_cr + $tr->points_transferred; $bal = $opp + $tr->points_transferred;  echo $tr->points_transferred; ?></td>
													<td ></td>
												<?php }else{ ?>
													<td ></td>
													<td style="text-align:right;" ><?php $all_dr = $all_dr + $tr->points_transferred; $bal = $opp - $tr->points_transferred;  echo $tr->points_transferred; ?></td>
												<?php } ?>
											<?php }else{ ?>
											
												<?php if($tr->transaction_narration=="Point Send"){ ?>										
													<td style="text-align:right;" ><?php $all_cr = $all_cr + $tr->points_transferred; $bal = $opp + $tr->points_transferred;  echo $tr->points_transferred; ?></td>
													<td ></td>
												<?php }else{ ?>
													<td ></td>
													<td style="text-align:right;" ><?php $all_dr = $all_dr + $tr->points_transferred; $bal = $opp - $tr->points_transferred; echo $tr->points_transferred; ?></td>
												<?php } ?>
											
											<?php } ?>
										<?php } ?>
										
										<?php if($this->session->userdata('user_type')=='1'){ ?>
											<?php if($user_data->user_type==2){ ?>
												<?php if($tr->transaction_narration=="Point Received"){ ?>										
													<td style="text-align:right;" ><?php $all_cr = $all_cr + $tr->points_transferred; $bal = $opp + $tr->points_transferred;  echo $tr->points_transferred; ?></td>
													<td ></td>
												<?php }else{ ?>
													<td ></td>
													<td style="text-align:right;" ><?php $all_dr = $all_dr + $tr->points_transferred; $bal = $opp - $tr->points_transferred; echo $tr->points_transferred; ?></td>
												<?php } ?> 
											<?php }else{ ?>
												<?php if($tr->transaction_narration=="Point Send"){ ?>										
													<td style="text-align:right;" ><?php $all_cr = $all_cr + $tr->points_transferred;  $bal = $opp + $tr->points_transferred; echo $tr->points_transferred; ?></td>
													<td ></td>
												<?php }else{ ?>
													<td ></td>
													<td style="text-align:right;" ><?php $all_dr = $all_dr + $tr->points_transferred; $bal = $opp - $tr->points_transferred;  echo $tr->points_transferred; ?></td>
												<?php } ?>											
											<?php } ?>
										<?php } ?>
										
										<?php if($this->session->userdata('user_type')=='0'){ ?>
												<?php if($tr->transaction_narration=="Point Send"){ ?>										
													<td style="text-align:right;" ><?php $all_cr = $all_cr + $tr->points_transferred; $bal = $opp + $tr->points_transferred; echo $tr->points_transferred; ?></td>
													<td ></td>
												<?php }else{ ?>
													<td ></td>
													<td style="text-align:right;" ><?php $all_dr = $all_dr + $tr->points_transferred;  $bal = $opp - $tr->points_transferred; echo $tr->points_transferred; ?></td>
												<?php } ?>
										<?php } ?>
										
										<td style="text-align:right;" ><?php $all_bal = $all_bal + $bal; echo number_format((float)$bal, 2, '.', ''); ?></td>
										
										<?php /* ?>
										<!--Admin -->
										<?php if($this->session->userdata('user_type')=='3'){ ?>
										<td style="text-align:right;" ><?php $all_bal = $all_bal + $ubalance->closing_points; echo $ubalance->closing_points; ?></td>
										<?php } ?>
										
										<!--Distributer -->
										<?php if($this->session->userdata('user_type')=='2'){ ?>
											<?php if($user_data->user_type==3){ ?>
											<td style="text-align:right;" ><?php $all_bal = $all_bal + $ubalance->closing_points; echo $tr->closing_points; ?></td>
											<?php }else{ ?>
											<td style="text-align:right;" ><?php $all_bal = $all_bal + $ubalance->closing_points; echo $ubalance->closing_points; ?></td>
											<?php } ?>
										<?php } ?>
										
										<!--Retailer -->
										<?php if($this->session->userdata('user_type')=='1'){ ?>
											<?php if($user_data->user_type==2){ ?>
											<td style="text-align:right;" ><?php echo $all_bal = $all_bal + $ubalance->closing_points; $tr->closing_points; ?></td>
											<?php }else{ ?>
											<td style="text-align:right;" ><?php echo $all_bal = $all_bal + $ubalance->closing_points; $ubalance->closing_points; ?></td>
											<?php } ?>
										<?php } ?>
										
										<!--Player -->
										<?php if($this->session->userdata('user_type')=='0'){ ?>
										<td style="text-align:right;" ><?php $all_bal = $all_bal + $ubalance->closing_points; echo $tr->closing_points; ?></td>
										<?php } ?>
										
										<?php */ ?>
										
										<td><?php echo $tr->transaction_narration; ?></td>	
										<td>
											<?php if($tr->transaction_nature==0){ ?>
											<div class="badge">Transfer</div>
											<?php }else if($tr->transaction_nature==1){ ?>
											<div class="badge">Comission Transfer</div>
											<?php }else if($tr->transaction_nature==2){ ?>
											<div class="badge">Ticket Generate</div>
											<?php }else if($tr->transaction_nature==3){ ?>
											<div class="badge">Winnig</div>
											<?php }else if($tr->transaction_nature==4){ ?>
											<div class="badge">Withdraw</div>
											<?php }else if($tr->transaction_nature==5){ ?>
											<div class="badge">Bonus</div>
											<?php }else if($tr->transaction_nature==6){ ?>
											<div class="badge">Comission Remove</div>
											<?php }else if($tr->transaction_nature==7){ ?>
											<div class="badge">Ticket Remove</div>
											<?php }else{ ?>
											<div class="badge"></div>
											<?php } ?>
										</td>
										<?php if($this->session->userdata('user_type')=='3'){ ?>
										<!--<td><?php echo $ref_data->bar_code_number; ?></td> -->
										<?php } ?>
									</tr>
									<?php $sno++; } ?>
									<?php } ?>
									<tr>
									<td colspan="5" style="text-align:left;font-weight: bold;">Total </td>
									<td  style="text-align:right;font-weight: bold;"> <?php echo number_format((float)$all_cr, 2, '.', '');  ?> </td>
									<td  style="text-align:right;font-weight: bold;"> <?php echo number_format((float)$all_dr, 2, '.', '');  ?> </td>
									<td  style="text-align:right;font-weight: bold;"> <?php //echo number_format((float)$all_bal, 2, '.', '');  ?> </td>
									<td colspan="2" style="text-align:center;"> </td>
									</tr>
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
	var today = new Date();
  $('input[name="from_date111"]').daterangepicker({
		locale: {
            format: 'DD-MMM-YYYY'
        },
		singleDatePicker: true,		
		maxDate: today,
		autoclose:true,
       /* ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        } */
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
  $('input[name="to_date111"]').daterangepicker({
		locale: {
            format: 'DD-MMM-YYYY'
        },
		singleDatePicker: true,
		maxDate: today,
		autoclose:true,
       /* ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        } */
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>


	