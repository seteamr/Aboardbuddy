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
					<div>View Details
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
					<div class="card-body"><h5 class="card-title">Retailer Details</h5>
						<div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">UserName: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $user->user_name; ?></label>
								</div>
								
								 <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Name: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $user->name; ?></label>
								</div> 
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Revenue: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $user->user_comission; ?></label>
								</div> 
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Credit: </label>
									<label for="examplePassword22" class="mr-sm-2" style="color: red;"><?php echo $credit; ?></label>
								</div> 
						</div>
					</div>
				</div>

				

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Current Week</h5>
						<div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Total Played: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_sale; ?></label>
								</div>
								
								 <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Total Won: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_winning; ?></label>
								</div> 
								
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Bonus: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_bonus; ?></label>
								</div>
									
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Commission: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_commision; ?></label>
								</div> 
								
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Net Point: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_sale-$current_week_winning-$current_week_commision-$current_week_bonus; ?></label>
								</div> 
								
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Last Week</h5>
						<div>
							<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
								<label for="examplePassword22" class="mr-sm-2">Total Played: </label>
								<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_sale; ?></label>
							</div>
							
							 <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
								<label for="examplePassword22" class="mr-sm-2">Total Won: </label>
								<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_winning; ?></label>
							</div> 
							
							<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
								<label for="examplePassword22" class="mr-sm-2">Bonus: </label>
								<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_bonus; ?></label>
							</div>
							
							<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
								<label for="examplePassword22" class="mr-sm-2">Commission: </label>
								<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_commision; ?></label>
							</div> 
							
							<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
								<label for="examplePassword22" class="mr-sm-2">Net Point: </label>
								<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_sale-$last_week_winning-$last_week_commision-$last_week_bonus; ?></label>
							</div> 
							

							<div>
								<a href="javascript:history.back()" class="btn btn-success">Back</a>
							</div>
						</div>
					</div>
				</div>

				
			</div>
			
		 </div>
		 
		 
		<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Reporting Users</h5>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>User Name</th>
									<th>User Type</th>
									<th>Reporting</th>
									<th>Commision</th>
									<th>Current Point</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($rusers)>0){ ?>
									<?php $sno=1; foreach($rusers as $us){ ?>
									<?php 
									if($us->user_type==0)
									{
										$u_type ="Player";
									}
									else if($us->user_type==1)
									{
										$u_type ="Retailer";
									}
									else if($us->user_type==2)
									{
										$u_type ="Distributor ";
									}
									if($us->shop_master_id!=null)
									{
										$shop_data = $this->common_model->getsingle('shop_master',array('shop_master_id'=>$us->shop_master_id));
										$shop = $shop_data->shop_name;
									}else{
										$shop="";
									}
									$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$us->reporting_user_master_id));
									$reporting = $user_data->user_name.' ( '.$user_data->name.' ) ';
									
									$balance = $this->common_model->getcurrent_balance($us->user_master_id);
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo $us->name; ?></td>
										<td><a href="<?php echo base_url('reports/view_details/'.$us->user_master_id); ?>" ><?php echo $us->user_name; ?></a></td>
										
										<td><?php echo $u_type; ?></td>
										<td><?php echo $reporting; ?></td>
										<td><?php echo $us->user_comission; ?></td>
										<td><?php echo number_format((float)$balance, 2, '.', ''); ?></td>
										<td>
											<?php if($us->is_user_deleted==1){ ?>
											<div class="badge badge-danger">Disabled</div>
											<?php }else{ ?>
											<div class="badge badge-success">Actived</div>
											<?php } ?>
										</td>
										<td>
											<a href="<?php echo base_url('users/view/'.$us->user_master_id); ?>" id="PopoverCustomT-1" class="btn btn-primary btn-sm">Details</a>
												<?php if($this->session->userdata('user_type')=='3'){ ?>
												<a href="<?php echo base_url('users/edit/'.$us->user_master_id); ?>" id="PopoverCustomT-2" class="btn btn-info btn-sm">Edit</a>
												<?php } ?>
											<?php if($us->is_user_deleted==1){ ?>
											<a onclick="return confirm('Are you sure you want to active this user?');" href="<?php echo base_url('users/user_status/'.$us->user_master_id.'/0'); ?>" id="PopoverCustomT-3" class="btn btn-success btn-sm">Active</a>
											<?php }else{ ?>
											<a onclick="return confirm('Are you sure you want to deactive this user?');" href="<?php echo base_url('users/user_status/'.$us->user_master_id.'/1'); ?>" id="PopoverCustomT-3" class="btn btn-danger btn-sm">Deactive</a>
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


	