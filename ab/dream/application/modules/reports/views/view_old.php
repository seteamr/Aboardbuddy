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
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Play History
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
					<div class="card-body"><h5 class="card-title">INFO</h5>
						<div class="table-responsive">
							<table class="align-middle mb-0 table table-borderless table-striped table-hover">
							<thead>
							<tr>
								<th class="text-center">Username</th>
								<td class="text-center"><?php echo $user_detail->user_name; ?></td>
								<th class="text-center">Result Date</th>
								<td class="text-center"><?php echo date('d-M-Y',strtotime($chk_data->result_date)); ?></td>
							</tr>
							<tr>
								<th class="text-center">Total Play</th>
								<td class="text-center"><?php 
								if($total_cancles==""){ echo $total_sales; }else{ echo $total_cancles;}
								?></td>
								<th class="text-center">Total Winning</th>
								<td class="text-center"><?php echo $total_winning; ?></td>
							</tr>
							<tr>
								<th class="text-center">Draw Time</th>
								<td  class="text-center"><?php echo $draw_master_detail->draw_end_time; ?></td>
									<?php if($this->session->userdata('user_type')=='3'){ ?>
								<th class="text-center">Ticket Number</th>
								<td  class="text-center"><?php echo $chk_data->bar_code_number; ?></td>
								<?php } ?>
							</tr>
							<tr>
								<th class="text-center">Device Type</th>
								<td  class="text-center"><?php if($draw_master_detail->play_device_type=="0"){ echo "Android"; }else{ echo "Window"; } ?></td>
								<th class="text-center">Status</th>
								<td  class="text-center"><?php if($chk_data->is_deleted=="1"){ echo '<span style="color:red;">Cancel</span>'; }else if($chk_data->is_claim=="1"){ echo '<span style="color:blue;">Claimed</span>'; } else{ echo '<span style="color:green;">Active</span>'; } ?></td>
							</tr>
							</thead>
							<tbody>
							
							<!-- <tr id="">
								<td class="text-center text-muted">1</td>
							</tr> -->
							</tbody>
						</table>
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Beat Details</h5>
						
						<div class="col-md-12 new">
						<?php if(count($transactions)>0){ ?>
						<?php foreach($transactions as $tr){ ?>							
							<div disabled style="text-align: center; color:#000; <?php if($tr->is_winning=="1"){ echo 'background: green; color: #fff;'; }else{ echo 'background: #000; color: #fff;'; } ?>" class="new col-md-1 card-shadow-primary border mb-1 card card-body border-primary"><?php echo $tr->series_master_id.$tr->bajar_master_id.$tr->bid_akada_number; ?><h5 class="card-title"><?php echo $tr->bid_units*$tr->bid_points*$tr->bid_points_multiplier; ?></h5></div>
						<?php } ?>
						<?php } ?>
						</div>
						
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
  $('input[name="from_to_date"]').daterangepicker({
		locale: {
            format: 'DD-MMM-YYYY'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
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


	