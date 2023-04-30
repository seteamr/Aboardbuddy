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
							<form class="form-inline" action="<?php echo base_url('reports/transactions') ?>" method="post">
								
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">From Date</label>
									<input name="from_date" id="from_date"  value="<?php echo $from_date; ?>" placeholder="Enter From date" type="text" class="form-control">
								</div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">To Date</label>
									<input name="to_date" id="to_date"  value="<?php echo $to_date; ?>" placeholder="Enter To date" type="text" class="form-control">
								</div>
								<span id="datepicker1"></span>
								
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group"><label for="exampleEmail22" class="mr-sm-2">From Name</label>									
									<select onchange="gettransaction_user();" class="form-control from_user_master_id" name="from_user_master_id" id="from_user_master_id">
										<option value="">Select From Name</option>
										<?php foreach ($user_master as $value) { ?>
											<option <?php if($from_user_master_id==$value->user_master_id){ echo "selected"; } ?> value="<?php echo $value->user_master_id; ?>"><?php echo $value->user_name; ?> ( <?php echo $value->name; ?> ) </option>
										<?php } ?>
									</select>
								</div> 
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group"><label for="exampleEmail22" class="mr-sm-2">To Name</label>									
									<select class="col-md-12 form-control to_user_master_id" name="to_user_master_id" id="to_user_master_id">
										<option value="">Select To Name</option>
										<?php 
										$tousersdata = $this->common_model->gettransaction_user($from_user_master_id);
										if($tousersdata)
										{
										foreach ($tousersdata as $value) { ?>
											<option <?php if($to_user_master_id==$value->user_master_id){ echo "selected"; } ?> value="<?php echo $value->user_master_id; ?>"><?php echo $value->user_name; ?> ( <?php echo $value->name; ?> ) </option>
										<?php }
										}
										?>
									</select>
								</div>
								
								<div class="mb-2 mt-2 mr-sm-2 mb-sm-0 position-relative form-group"><label for="exampleEmail22" class="mr-sm-2">Nature</label>									
									<select class="col-md-12 form-control transaction_nature" name="transaction_nature" id="transaction_nature">
										<option value="">Select Nature</option>
										<option <?php if($transaction_nature=="0"){ echo "selected"; } ?> value="0">Transfer</option>
										<option <?php if($transaction_nature=="1"){ echo "selected"; } ?> value="1">Comission Transfer</option>
										<option <?php if($transaction_nature=="2"){ echo "selected"; } ?> value="2">Ticket Generate</option>
										<option <?php if($transaction_nature=="3"){ echo "selected"; } ?> value="3">Winnig</option>
										<option <?php if($transaction_nature=="4"){ echo "selected"; } ?> value="4">Withdraw</option>	
										<option <?php if($transaction_nature=="5"){ echo "selected"; } ?> value="5">Bonus</option>
										<option <?php if($transaction_nature=="6"){ echo "selected"; } ?> value="6">Comission Remove</option>
										<option <?php if($transaction_nature=="7"){ echo "selected"; } ?> value="7">Ticket Remove</option>										
									</select>
								</div>
								
								
									
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Search" class=" mr-2 btn-transition btn btn-outline-primary">
										<!--<a href="<?php echo base_url('reports/transactions'); ?>" class=" mr-2 btn-transition btn btn-outline-success">Refresh</a> -->
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Transaction</h5>
						<?php if (isset($links)) { ?>
							<nav class="" aria-label="Page navigation example">
							<?php echo $links ?>
							</nav>
						<?php } ?>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>From Name</th>
									<th>To Name</th>
									<th style="text-align:right;">Opning</th>
									<th style="text-align:right;">Transferred</th>
									<th style="text-align:right;">Closing</th>																		
									<th>Narration</th>									
									<th>Nature</th>
									<th>Reference No.</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($transactions)>0){ ?>
									<?php foreach($transactions as $tr){ ?>
									<?php 									
										$from_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$tr->from_user_master_id));
										$to_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$tr->to_user_master_id));										
										$ref_data = $this->common_model->getsingle('draw_transaction_master',array('draw_transaction_master_id'=>$tr->draw_transaction_master_id));										
									
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo date('d-M-Y',strtotime($tr->transactions_date)); ?></td>
										<td><?php echo $from_data->user_name; ?></td>
										<td><?php echo $to_data->user_name; ?></td>										
										<td style="text-align:right;"><?php echo $tr->opening_points ?></td>
										<td style="text-align:right;"><?php echo $tr->points_transferred; ?></td>																				
										<td style="text-align:right;"><?php echo $tr->closing_points; ?></td>
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
										<td><?php echo $ref_data->bar_code_number; ?></td>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">

function gettransaction_user()
{
	var from_user_master_id = $("#from_user_master_id").val();
	
	var params = {from_user_master_id: from_user_master_id};
	$.ajax({
		url: '<?php echo base_url();?>reports/gettransaction_user',
		type: 'post',
		data: params,
		success: function (r)
		 {
			 $("#to_user_master_id").html(r);
		 }
	});                     
}

</script>
	
	
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
$(function() {
	var today = new Date();
  $('input[name="from_date"]').daterangepicker({
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
  $('input[name="to_date"]').daterangepicker({
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


<!-- select2 -->
  <link href="<?php echo base_url(); ?>/css/select/select2.min.css" rel="stylesheet">
<!-- select2 -->
<script src="<?php echo base_url(); ?>/js/select/select2.full.js"></script>
<!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".from_user_master_id").select2({
        placeholder: "Select From Name",
        allowClear: true
      });   
	  $(".to_user_master_id").select2({
        placeholder: "Select To Name",
        allowClear: true
      });
	   $(".transaction_nature").select2({
        placeholder: "Select Nature",
        allowClear: true
      });
	  
	  
    });
  </script>
  <!-- /select2 -->


	