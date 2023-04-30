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
                  <h2>Records<small></small></h2>
					
                  <div class="clearfix"></div>
                </div>
				  
                <div class="x_content"> 
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
									<label for="examplePassword22" class="mr-sm-2">Client Code</label>
									<input name="username" id="from_to_date" placeholder="Enter Client Code" type="text" value="<?php echo $username; ?>" class="form-control">
								</div> 
								<?php } ?>	
								
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Draw</label>
									<input name="draw_type" type="Radio" <?php if($draw_type=="Current"){ echo "checked"; }else if($draw_type==""){ echo "checked"; } ?> value="Current" class="form-control"> &nbsp;&nbsp;This &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="draw_type" type="Radio" <?php if($draw_type=="Advance"){ echo "checked"; } ?> value="Advance" class="form-control"> &nbsp;&nbsp;Advance Day &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="draw_type" type="Radio" <?php if($draw_type=="All"){ echo "checked"; } ?> value="All" class="form-control"> &nbsp;&nbsp;All
								</div> 
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Search" class=" mr-2 btn-transition btn btn-outline-primary">
										<!--<a href="<?php echo base_url('reports/play_history'); ?>" class=" mr-2 btn-transition btn btn-outline-success">Refresh</a> -->
									</div>
								</div>
							</form>
                 <table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Bet Date</th>
									<th>Client Code</th>
									<th>Draw Time</th>
									<th style="text-align:right;">Coins Bet</th>
									<th style="text-align:right;">Claim Date</th>
									<th style="text-align:right;">Win Coins</th>
									<?php if($this->session->userdata('user_type')=='3'){ ?>
									<th>Coupon No.</th>
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
										<td><?php echo date('d-m-Y h:i:s A',strtotime($tr->created_date)); ?></td>
										<td><a href="<?php echo base_url('reports/view_details/'.$tr->user_master_id); ?>"><?php echo $tr->user_name; ?> (<?php echo $tr->name; ?>)</a></td>
																		
										<td><?php //echo $draw_data->draw_start_time; ?>  <?php echo $draw_data->draw_end_time; ?></td>
										
										<td style="text-align:right;"><?php echo number_format((float)$total_bid[0]->total, 2, '.', ''); ?></td>
										
										<td><?php if($tr->is_claim=="1"){ echo date('d-M-Y h:i:s A',strtotime($tr->claim_date_time)); } ?></td>
										
										<td style="text-align:right;"><?php echo number_format((float)$total_win[0]->total*90, 2, '.', ''); ?></td>
										
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
              <!-- footer content -->			  
             <?php //$this->load->view('includes/footer'); ?>			 
              <!-- /footer content -->

            </div>
            <!-- /page content -->
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


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