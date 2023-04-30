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
					<div>Player History
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
							<form class="form-inline" action="<?php echo base_url('reports/play_history') ?>" method="post">
								
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">From Date</label>
									<input name="from_date" id="from_date11"  value="<?php echo $from_date; ?>" placeholder="Enter From date" type="date" class="form-control">
								</div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">To Date</label>
									<input name="to_date" id="to_date11"  value="<?php echo $to_date; ?>" placeholder="Enter To date" type="date" class="form-control">
								</div>
								<span id="datepicker1"></span>
								<?php if($this->session->userdata('user_type')!='1'){ ?>
								 <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">UserName</label>
									<input name="username" id="from_to_date" placeholder="Enter Username" type="text" value="<?php echo $username; ?>" class="form-control">
								</div> 
								<?php } ?>	
								
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Draw Type</label>
									<input name="draw_type" type="Radio" <?php if($draw_type=="Current"){ echo "checked"; }else if($draw_type==""){ echo "checked"; } ?> value="Current" class="form-control"> &nbsp;&nbsp;Current &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="draw_type" type="Radio" <?php if($draw_type=="Advance"){ echo "checked"; } ?> value="Advance" class="form-control"> &nbsp;&nbsp;Advance &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="draw_type" type="Radio" <?php if($draw_type=="All"){ echo "checked"; } ?> value="All" class="form-control"> &nbsp;&nbsp;All
								</div> 
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Search" class=" mr-2 btn-transition btn btn-outline-primary">
										<!--<a href="<?php echo base_url('reports/play_history'); ?>" class=" mr-2 btn-transition btn btn-outline-success">Refresh</a>
									</div>
								</div>
							</form>
						</div>
					</div> -->
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
									<th>UserName</th>
									<th>Draw Time</th>
									<th style="text-align:right;">Opening Points</th>
									<th style="text-align:right;">Play Points</th>
									<th style="text-align:right;">Closing Points</th>
									<th style="text-align:right;">Claim Date</th>
									<th style="text-align:right;">Opening Points</th>
									<th style="text-align:right;">Win Points</th>
									<th style="text-align:right;">Closing Points</th>
									<?php if($this->session->userdata('user_type')=='3'){ ?>
									<th>Ticket No.</th>
									<?php } ?>
									<th style="text-align:right;">Status</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($transactions)>0){ ?>
									<?php foreach($transactions as $tr){ ?>
									<?php 
									$draw_data = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$tr->draw_master_id));										
									$total_bid = $this->common_model->total_bid_winning($tr->result_date,$tr->draw_transaction_master_id,'');
									$total_win = $this->common_model->total_bid_winning($tr->result_date,$tr->draw_transaction_master_id,'1');
									
									$balancesss = $this->common_model->getsingle('points_transactions',array('draw_transaction_master_id'=>$tr->draw_transaction_master_id,'transaction_type'=>'0','transaction_nature'=>'2','from_user_master_id'=>$tr->user_master_id));	
									$balancesss_com = $this->common_model->getsingle('points_transactions',array('draw_transaction_master_id'=>$tr->draw_transaction_master_id,'transaction_type'=>'1','transaction_nature'=>'1','from_user_master_id'=>$tr->user_master_id));
									$balancesss_com_cancle = $this->common_model->getsingle('points_transactions',array('draw_transaction_master_id'=>$tr->draw_transaction_master_id,'transaction_type'=>'0','transaction_nature'=>'6','from_user_master_id'=>$tr->user_master_id));	
									
									$balancesss2 = $this->common_model->getsingle('points_transactions',array('draw_transaction_master_id'=>$tr->draw_transaction_master_id,'transaction_type'=>'1','transaction_nature'=>'3','from_user_master_id'=>$tr->user_master_id));	
									
									
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo date('d-M-Y h:i:s A',strtotime($tr->created_date)); ?></td>
										<td><a href="<?php echo base_url('reports/view_details/'.$tr->user_master_id); ?>"><?php echo $tr->user_name; ?> (<?php echo $tr->name; ?>)</a></td>
																		
										<td><?php //echo $draw_data->draw_start_time; ?>  <?php echo $draw_data->draw_end_time; ?></td>
										
										<?php if($tr->is_deleted=="1"){ ?>
										<td style="text-align:right;"><?php echo $balancesss->closing_points; ?></td>
										<td style="text-align:right;"><?php echo number_format((float)$total_bid[0]->total, 2, '.', ''); ?></td>										
										<td style="text-align:right;"><?php echo $balancesss->opening_points; ?></td>
										<?php }else{ ?>
										<td style="text-align:right;"><?php echo $balancesss->opening_points; ?></td>
										<td style="text-align:right;"><?php echo number_format((float)$total_bid[0]->total, 2, '.', ''); ?></td>
										<td style="text-align:right;"><?php echo $balancesss->closing_points + $balancesss_com->points_transferred ; ?></td>
										<?php } ?>
										
										<td><?php if($tr->is_claim=="1"){ echo date('d-M-Y h:i:s A',strtotime($tr->claim_date_time)); } ?></td>
										<td style="text-align:right;"><?php if($balancesss2){echo $balancesss2->opening_points; }else{ echo 0; } ?></td>
										<td style="text-align:right;"><?php echo number_format((float)$total_win[0]->total*90, 2, '.', ''); ?></td>
										<td style="text-align:right;"><?php if($balancesss2){echo $balancesss2->closing_points; }else{ echo 0; } ?></td>
										<?php if($this->session->userdata('user_type')=='3'){ ?>
										<td><?php echo $tr->bar_code_number; ?></td>
										<?php } ?>		
										<td>
										<?php if($tr->is_deleted=="1"){ echo '<span style="color:red;">Cancel</span>'; }else if($tr->is_claim=="1"){ echo '<span style="color:blue;">Claimed</span>'; } else{ echo '<span style="color:green;">Active</span>'; } ?>
										</td>
										<td><a  target="_Blank" href="<?php echo base_url('reports/view/'.$tr->bar_code_number); ?>" class="btn btn-success" >View</a></td>
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
  $('input[name="from_date11"]').daterangepicker({
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
  $('input[name="to_date11"]').daterangepicker({
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


	