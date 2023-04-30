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
					<div>Off List
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
					<div class="card-body"><h5 class="card-title">Add Off Day</h5>
						<div>
							<form class="form-inline" action="<?php echo base_url('admin/offlist') ?>" method="post">
								
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Date</label>
									<input name="off_date" id="off_date"  value="" placeholder="Enter Off Date" type="text" class="form-control" required>
								</div>
								
								
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Add" class=" mr-2 btn-transition btn btn-outline-primary">										
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Off List</h5>
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
									<th>Off Date</th>
									<th>Added by</th>
									<th>Action</th>									
								</tr>
								</thead>
								<tbody>
									<?php if(count($list)>0){ ?>
									<?php foreach($list as $tr){ ?>
									
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo date('d-M-Y',strtotime($tr->off_date)); ?></td>
										<td><?php echo date('d-M-Y',strtotime($tr->entry_date)); ?></td>
										<td>
										<a onclick="return confirm('Are you sure you want to delete this Date?');" href="<?php echo base_url('admin/delete_offdate/'.$tr->id); ?>" class="btn btn-danger btn-sm">Delete</a>
										</td>
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
  $('input[name="off_date"]').daterangepicker({
		locale: {
            format: 'DD-MMM-YYYY'
        },
		singleDatePicker: true,		
		minDate: today,
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


	