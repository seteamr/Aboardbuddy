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
</style>
<?php $role = $this->session->userdata('role'); ?>

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
          <div class="clearfix"></div>

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
                  <h2>View Order Details<small></small></h2>
				
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">  
					
                  <table id="datatable1" class="table table-striped table-bordered">
                    <thead>
                      <tr>                       
						<th>Sno.</th>
						<th>Order no</th>
                        <th>User</th>
						<th>Product Title</th>
						<?php if($this->session->userdata('type')=='super'){ ?>
						<th>Product by</th>
						<?php } ?>
						<th>Product Type</th>
						<th>Qty</th>
						<th>Price</th>
						<th>Discount Price</th>
						<th>Delivery Charge</th>
						<th>Total Amount</th>
                      </tr>
                    </thead>
                    <tbody>
					  <?php if($records){
						$sno=1;	
						foreach($records as $rec){
						$user = $this->common_model->getsingle('users', array('user_id'=>$rec->user_id));
						$products = $this->common_model->getsingle('products', array('id'=>$rec->p_id)); 
						$admin = $this->common_model->getsingle('admin', array('admin_id'=>$products->admin_id));
						$payment = $this->common_model->getsingle('payment', array('order_no'=>$rec->order_no));
						$p_type = $this->common_model->getsingle('p_type', array('id'=>$products->p_type));	
						$total_price = number_format((float)($products->price+$products->delivery_charge)-$products->discount_price,2,'.','');						
						if($this->session->userdata('type')=='super')
						{ 
							$grand_total = number_format((float)$payment->price,2,'.','');
						}else{
							$grand_total = $grand_total + $total_price;
						}
						
					  ?>
                      <tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $rec->order_no; ?></td> 
						<td><?php echo $user->user_name; ?></td> 
						<td><?php echo $products->name; ?></td>
						<?php if($this->session->userdata('type')=='super'){ ?>
						<td><?php echo $admin->name; ?></td>
						<?php } ?>
						<td><?php echo $p_type->name; ?></td> 	
						<td><?php echo $rec->order_qty; ?></td>
						<td><?php echo number_format((float)$products->price,2,'.',''); ?></td> 
						<td><?php echo number_format((float)$products->discount_price,2,'.',''); ?></td>
						<td><?php echo number_format((float)$products->delivery_charge,2,'.',''); ?></td> 
						<td><?php echo $total_price; ?></td>
                      </tr>	
					  <?php $sno++; } ?>
					  <?php } ?>
                    </tbody>
                  </table>
				  
				<div style="background-color:#F5F5F5;width:175px;border:1px solid;padding:4px;float:right;text-align:center;">Grand Total = <?php echo  $grand_total;  ?></div>
				  
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
            <!-- /page content -->
	  
   <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

  <!-- gauge js -->
  <script type="text/javascript" src="<?php echo base_url(); ?>js/gauge/gauge.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/gauge/gauge_demo.js"></script>
  <!-- bootstrap progress js -->
  <script src="<?php echo base_url(); ?>js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="<?php echo base_url(); ?>js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="<?php echo base_url(); ?>js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="<?php echo base_url(); ?>js/moment/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="<?php echo base_url(); ?>js/chartjs/chart.min.js"></script>

  <script src="<?php echo base_url(); ?>js/custom.js"></script>

  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/jquery-jvectormap-2.0.3.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/gdp-data.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/jquery-jvectormap-world-mill-en.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/maps/jquery-jvectormap-us-aea-en.js"></script>
  <!-- pace -->
  <script src="<?php echo base_url(); ?>js/pace/pace.min.js"></script>
   <script src="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/buttons.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/vfs_fonts.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/responsive.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>js/datatables/dataTables.scroller.min.js"></script>

        <script src="<?php echo base_url(); ?>js/pace/pace.min.js"></script>
        
        <script type="text/javascript">
          $(document).ready(function() {
            $('#datatable1').dataTable({
				"searching": false,
				"bPaginate": false,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": false,
			});
          });
          
		  
		function myFunction() {
			document.getElementById("searchdate").reset();
		}
</script>

       
  