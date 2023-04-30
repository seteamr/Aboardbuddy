<style>
.pagination {
  display: inline-block;
  margin: 10px 0 10px 0px;
}
.pagination li {
 float: left;
  list-style: none;
  text-decoration: none;
  transition: background-color .3s;
}
.pagination li a {
  color: black;  
  padding: 9px 16px;  
  border: 1px solid #ddd;
  text-decoration:none;
}
.pagination>li>span {
   position: relative;
    float: none;
    padding: 9px 15px 7px 15px;
    margin-left: -1px;
    line-height: normal;
    color: #fff;
    text-decoration: none;
    background-color: #337ab7;
    border: 1px solid #337ab7;
}
.pagination li a.active {
  background-color: #4CAF50;
  color: white;
  border: 1px solid #4CAF50;
}
.pagination li a:hover:not(.active) {background-color: #ddd;}

.zoom {
  padding: 50px;
  background-color: white;
  transition: transform .2s; /* Animation */
  width: 100px;
  height: 50px;
  margin: 0 auto;
}

.zoom:hover {
  transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>
                    <small>                       
                    </small>
                </h3>
            </div>
			
          </div>
          

          <div class="row">
			<?php if($msg!=''){ ?>
			   <div class="alert alert-success alert-dismissible fade in" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				  </button>
				  <?php echo $msg; ?>
				</div>
		  <?php } ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Coins Record<small></small></h2>
					
                  <div class="clearfix"></div>
                </div>
				  
                <div class="x_content"> 
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
									<label for="examplePassword22" class="mr-sm-2">Client Code</label>
									<input name="username" id="from_to_date" placeholder="Enter Client Code" type="text" value="<?php echo $username; ?>" class="form-control">
								</div> 
								<?php } ?>	
							   <input type="submit" name="search" value="Search" class="btn btn-primary">
								<!-- <button class="btn btn-primary">Search</button> -->
							</form>
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
										<td><?php echo date('d-m-Y',strtotime($tr->transactions_date)) .' '.date('H:i',strtotime($tr->created_date)); ?></td>										
										
										
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
										
										
										
										<td><?php echo $tr->transaction_narration; ?></td>	
										<td>
											<?php if($tr->transaction_nature==0){ ?>
											<div class="badge">Transfer</div>
											<?php }else if($tr->transaction_nature==1){ ?>
											<div class="badge">Revenue Transfer</div>
											<?php }else if($tr->transaction_nature==2){ ?>
											<div class="badge">Ticket Generate</div>
											<?php }else if($tr->transaction_nature==3){ ?>
											<div class="badge">Winnig</div>
											<?php }else if($tr->transaction_nature==4){ ?>
											<div class="badge">Withdraw</div>
											<?php }else if($tr->transaction_nature==5){ ?>
											<div class="badge">Bonus</div>
											<?php }else if($tr->transaction_nature==6){ ?>
											<div class="badge">Revenue Remove</div>
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
              <!-- footer content -->			  
             <?php //$this->load->view('includes/footer'); ?>			 
              <!-- /footer content -->

            </div>
            <!-- /page content -->
          
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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


	
		  
		  
        <script src="<?php echo base_url(); ?>css_js/js/bootstrap.min.js"></script>
        <!-- bootstrap progress js -->
        <script src="<?php echo base_url(); ?>css_js/js/progressbar/bootstrap-progressbar.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- icheck -->
        <script src="<?php echo base_url(); ?>css_js/js/icheck/icheck.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/custom.js"></script>
        <!-- Datatables -->
        <!-- <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script> -->
        <!-- Datatables-->
        <script src="<?php echo base_url(); ?>css_js/js/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/buttons.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/vfs_fonts.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/responsive.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>css_js/js/datatables/dataTables.scroller.min.js"></script>
        <!-- pace -->
        <script src="<?php echo base_url(); ?>css_js/js/pace/pace.min.js"></script>
        <script type="text/javascript">
          $(document).ready(function() {
            $('#datatable1').dataTable({
				"searching": ture,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": false,
			});
          });
        </script>
</body>
</html>
<script>
$(document).ready(function(){

	

	$('#search_text').keyup(function(){
		var keyword = $(this).val();
	    alert(keyword)
			$.ajax({
			url:"<?php echo base_url(); ?>admin/search_order",
			method:"POST",
			data:{keyword:keyword},
			success:function(data){
				$('#response').html(data);
			}
		});
	});
});
</script>
