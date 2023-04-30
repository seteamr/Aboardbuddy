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
                  <h2>Revenue<small></small></h2>
					
                  <div class="clearfix"></div>
                </div>
				  
                <div class="x_content"> 
				   <form class="form-inline" action="<?php echo base_url('reports/commission') ?>" method="post">
								
						<div class="form-group">
							<label for="examplePassword22" class="mr-sm-2">Date</label>
							<input name="from_to_date" id="from_to_date"  value="<?php echo $from_to_date; ?>" placeholder="Enter From date" type="text" class="form-control ">
						</div>						
						<span id="datepicker1"></span>
															
						<div class="mt-2 col-md-12 mb-3">
							<div class="form-row">
								<input type="submit" name="search" value="Search" class=" mr-2 btn-transition btn btn-outline-primary">
								<!--<a href="<?php echo base_url('reports/transactions'); ?>" class=" mr-2 btn-transition btn btn-outline-success">Refresh</a> -->
							</div>
						</div>
					</form>
							
                  <table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th>Client</th>																										
									<th>Type</th>									
									<th>Revenue Coins</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($transactions)>0){ ?>
									<?php foreach($transactions as $tr){ ?>
									<?php 									
										$from_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$tr->from_user_master_id));
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo date('d-M-Y',strtotime($tr->transactions_date)); ?></td>
										<td><?php echo $from_data->user_name; ?></td>
										<td><?php echo $tr->transaction_narration; ?></td>	
										<td><?php echo $tr->points_transferred; ?></td>																				
										
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
