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
                  <h2>Selling<small></small></h2>
					
                  <div class="clearfix"></div>
                </div>
				  
                <div class="x_content"> 
				  <form class="form-inline" action="<?php echo base_url('reports/counter_sale') ?>" method="post">
								
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
									<label for="examplePassword22" class="mr-sm-2">LoginName</label>
									<input name="username" id="from_to_date" placeholder="Enter LoginName" type="text" value="<?php echo $username; ?>" class="form-control">
								</div> 
								<?php } ?>	
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Search" class=" mr-2 btn-transition btn btn-outline-primary">
										<!--<a href="<?php echo base_url('reports/counter_sale'); ?>" class=" mr-2 btn-transition btn btn-outline-success">Refresh</a> -->
									</div>
								</div>
							</form>
                 <table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>Name</th>
									<th>UserName</th>	
									<th style="text-align:right;">Sales Coins</th>
									<th style="text-align:right;">Winning Coins</th>
									<th style="text-align:right;" >Net Coins</th>
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
									
									$total_comissionsss = $this->common_model->total_comission_new2($tr->user_master_id,$from_date,$to_date);
									//echo "<pre>"; print_r($total_comission);
									$total_comission = $total_comissionsss['0']['Retailer_commission'];
									
									$total_bonus = $this->common_model->total_bonus($tr->user_master_id,$from_date,$to_date);
									
									$net_pay_t = $total_bid[0]->total - ($total_win[0]->total*90) - $total_comission - $total_bonus[0]->total;
									
									$t_bid = $total_bid[0]->total;
									$t_win = $total_win[0]->total*90;
									
									if($tr->user_type=="1")
									{
										$r_t_bid = $r_t_bid + $t_bid;
										$r_t_win = $r_t_win + $t_win;
										
										$r_net_pay_t = $r_net_pay_t + $net_pay_t;
									}
									if($tr->user_type=="0")
									{
										$p_t_bid = $p_t_bid + $t_bid;
										$p_t_win = $p_t_win + $t_win;
										
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
										<td style="text-align:right;" ><?php echo number_format((float)$net_pay_t, 2, '.', ''); ?></td>
									</tr>
									<?php $sno++; } ?>
									<tr>
										<th colspan="4">Player Total</th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_t_bid, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_t_win, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$p_net_pay_t, 2, '.', ''); ?></th>
									</tr>
									<tr>
										<th colspan="4">Retailer  Total</th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_bid, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_win, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_net_pay_t, 2, '.', ''); ?></th>
									</tr>
									<tr>
										<th colspan="4">All Total</th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_bid+$p_t_bid, 2, '.', ''); ?></th>
										<th style="text-align:right;" ><?php echo number_format((float)$r_t_win+$p_t_win, 2, '.', ''); ?></th>
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
			<button onclick="printDiv('printdata')">Print Out</button>
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
