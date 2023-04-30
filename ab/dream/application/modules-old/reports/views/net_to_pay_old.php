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
					<div>Net To Pay
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
					<div class="card-body"><h5 class="card-title">Search By </h5>
						<div>
							<form class="form-inline" action="<?php echo base_url('reports/net_to_pay') ?>" method="post">
								
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">From Date</label>
									<input name="from_date" id="from_date"  value="<?php echo $from_date; ?>" placeholder="Enter From date" type="date" class="form-control">
								</div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">To Date</label>
									<input name="to_date" id="to_date"  value="<?php echo $to_date; ?>" placeholder="Enter To date" type="date" class="form-control">
								</div>
								<span id="datepicker1"></span>
								<?php if($this->session->userdata('user_type')!='1'){ ?>
								 <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">UserName</label>
									<input name="username" id="username" placeholder="Enter Username" type="text" value="<?php echo $username; ?>" class="form-control">
								</div> 
								<?php } ?>		
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Search" class=" mr-2 btn-transition btn btn-outline-primary">
										<!--<a href="<?php echo base_url('reports/net_to_pay'); ?>" class=" mr-2 btn-transition btn btn-outline-success">Refresh</a> -->
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Net To Pay</h5>
						<button onclick="printDiv('printdata')">Print</button>
						<?php if (isset($links)) { ?>
							<nav class="" aria-label="Page navigation example">
							<?php echo $links ?>
							</nav>
						<?php } ?>
						<div class="table-responsive" id="printdata">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>Name</th>
									<th>UserName</th>	
									<th style="text-align:right;">Sales Points</th>
									<th style="text-align:right;">Winning Points</th>
									<th style="text-align:right;" >Commission</th>
									<th style="text-align:right;" >Retail Bonus</th>
									<th style="text-align:right;" >Net Points</th>
								</tr>
								</thead>
								<tbody>
								<?php 
								if($from_date!='' && $to_date!='')
								{									
									$final_date = date('d-M-Y',strtotime($from_date)).' - '.date('d-M-Y',strtotime($to_date));
								}else{
									$final_date = "All";
								} 
								?>
									<?php 
									$r_t_bid = 0;
									$p_t_bid = 0;
									$r_t_win = 0;
									$p_t_win = 0;
									$r_t_c = 0;
									$p_t_c = 0;
									$r_t_b = 0;
									$p_t_b = 0;
									$r_net_pay_t = 0;
									$p_net_pay_t = 0;
									
									?>
									<?php if(count($transactions)>0){ ?>
									<?php foreach($transactions as $tr){ ?>
									<?php 
									if($username!="")
									{
										$from_date = date('Y-m-d',strtotime($tr->result_date));	
										$to_date = date('Y-m-d',strtotime($tr->result_date));	
									}
									$total_bid = $this->common_model->total_bid_counter_sale($tr->user_master_id,$from_date,$to_date,'');
									$total_win = $this->common_model->total_bid_counter_sale($tr->user_master_id,$from_date,$to_date,'1');
									
									$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$tr->user_master_id));
									if($user_data->user_type=="1")
									{
										$total_comission  = ($total_bid[0]->total*$user_data->user_comission)/100;
									}else{
										$user_data2 = $this->common_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));
										$total_comission  = ($total_bid[0]->total*$user_data2->user_comission)/100;
									}
									
									
									//$total_comissionsss = $this->common_model->total_comission_new($tr->user_master_id,$from_date,$to_date);
									//echo "<pre>"; print_r($total_comission);
									//$total_comission = $total_comissionsss;
									//$total_comission = $total_comissionsss['0']['Retailer_commission'];
									
									$total_bonus = $this->common_model->total_bonus($tr->user_master_id,$from_date,$to_date);
									
									$net_pay_t = $total_bid[0]->total - ($total_win[0]->total*90) - $total_comission - $total_bonus[0]->total;
									
									$t_bid = $total_bid[0]->total;
									$t_win = $total_win[0]->total*90;
									$t_c = $total_comission;
									$t_b = $total_bonus[0]->total;
									if($tr->user_type=="1")
									{
										$r_t_bid = $r_t_bid + $t_bid;
										$r_t_win = $r_t_win + $t_win;
										$r_t_c = $r_t_c + $t_c;
										$r_t_b = $r_t_b + $t_b;
										$r_net_pay_t = $r_net_pay_t + $net_pay_t;
									}
									if($tr->user_type=="0")
									{
										$p_t_bid = $p_t_bid + $t_bid;
										$p_t_win = $p_t_win + $t_win;
										$p_t_c = $p_t_c + $t_c;
										$p_t_b = $p_t_b + $t_b;
										$p_net_pay_t = $p_net_pay_t + $net_pay_t;
									}
									
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td>
											<?php 
											if($username!="")
											{
												echo date('d-M-Y',strtotime($tr->result_date));	
											}else{
												echo $final_date; 
											}
											?>
										</td>
										<td><?php echo $tr->name; ?></td>
										<td><?php echo $tr->user_name; ?></td>
										<td style="text-align:right;"><?php echo number_format((float)$t_bid, 2, '.', ''); ?></td>
										<td style="text-align:right;" ><?php echo number_format((float)$t_win, 2, '.', '');  ?></td>
										<td style="text-align:right;" ><?php echo number_format((float)$t_c, 2, '.', '');  ?></td>
										<td style="text-align:right;" ><?php echo number_format((float)$t_b, 2, '.', '');  ?></td>
										<td style="text-align:right;" ><?php echo number_format((float)$net_pay_t, 2, '.', ''); ?></td>
									</tr>
									<?php $sno++; } ?>
									<tr>
										<th colspan="4">Player Total</th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_t_bid, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_t_win, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_t_c, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_t_b, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_net_pay_t, 2, '.', ''); ?></th>
									</tr>
									<tr>
										<th colspan="4">Retailer  Total</th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_bid, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_win, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_c, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_b, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_net_pay_t, 2, '.', ''); ?></th>
									</tr>
									<tr>
										<th colspan="4">All Total</th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_bid+$p_t_bid, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_win+$p_t_win, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_c+$p_t_c, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_b+$p_t_b, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_net_pay_t+$p_net_pay_t, 2, '.', ''); ?></th>
									</tr>
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
/*
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
        } *//*
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
        } *//*
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
}); */
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

  /* function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
} */
  </script>
  <script type="text/javascript">     
    function printDiv(divName) {    
       var divToPrint = document.getElementById(divName);
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            } 
 </script>
  <!-- /select2 -->


	