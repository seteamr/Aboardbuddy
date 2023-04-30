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
                  <h2>Order List<small></small></h2>
				<!--<a href="<?php echo base_url('admin/add_admin'); ?>" class="btn btn-success pull-right">Add New Admin</a>-->
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">  
					<form method="post" action="<?php echo base_url('/admin/orders_list/');?>" id="searchdate">
						<div class="form-group newspart">
							<div class="col-sm-3 col-xl-6">                                                            
								<input type="date" name="from_date" onfocus="this.value=''" placeholder="From Date" value="<?php echo $from_date; ?>" class="form-control">
							</div>
							<div class="col-sm-3 col-xl-6">                                                            
								<input type="date" name="to_date" onfocus="this.value=''" placeholder="To Date" value="<?php echo $to_date; ?>" class="form-control">
							</div>
							<div class="col-sm-1 col-xl-1">                                                            
								<input type="submit" Value="Search" class="btn btn-success btn-xs form-control" >
							</div>
							<!--<div class="col-sm-1 col-xl-1">                                                            
								<button onclick="myFunction()" class="btn btn-success btn-xs form-control" >Reset</button>
							</div>-->
							<?php if($this->session->userdata('type')=='super'){ ?>
							<div class="col-sm-1 col-xl-1"> 
								<button type="button" class="btn btn-success btn-xs form-control bpop" >Delivered</button>
							</div>
							<div class="col-sm-1 col-xl-1"> 
							    <a href="<?php echo base_url('/admin/orders_list/export');?>" style='float: right;' class="btn btn-success btn-xs form-control"><i class="dwn"></i>Export</a>
								<!--<button type="button" class="btn btn-success btn-xs form-control exportOrder" >Export CSV</button>-->
							</div>
							<?php } ?>
						</div>
					</form>
					
                  <table id="datatable1" class="table table-striped table-bordered">
                    <thead>
                      <tr> 
						<?php if($this->session->userdata('type')=='super'){ ?>
						<th><input type="checkbox" class="checkbox" id="select_all" />Select all</th>
						<?php } ?>
						<th>Sno.</th>
						<th>Order no</th>
                        <th>User</th>
						<th>Order Amount</th>
						<th>Payment Mode</th>
						<th>Order Date</th>
						<th>Deliver Status</th>
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
					  <?php if($records){ ?>
					  <?php foreach($records as $rec){
						$allp='';
						$user = $this->common_model->getsingle('users', array('user_id'=>$rec->user_id));
						$products = $this->common_model->getsingle('products', array('id'=>$rec->p_id)); 
						$payment = $this->common_model->getsingle('payment', array('order_no'=>$rec->order_no));
						
						$all_for_admin = $this->common_model->getAll_order('orders', array('order_no'=>$rec->order_no),$this->session->userdata('admin_id'));
						foreach($all_for_admin as $all_rec)
						{
							$total_price = number_format((float)($all_rec->price+$all_rec->delivery_charge)-$all_rec->discount_price,2,'.','');
							$allp = $allp+$total_price;
						}
						if($this->session->userdata('type')=='super')
						{ 
							$ttl_payment = number_format((float)$payment->price,2,'.','');
						}
						else
						{
							$ttl_payment = $allp;
						}
						
					  ?>
                      <tr>
						<?php if($this->session->userdata('type')=='super'){ ?>
						<td><input type="checkbox" class="checkbox" name="checkbox[]" value="<?php echo $rec->order_no; ?>"/></td>
						<?php } ?>
						<td><?php echo $sno; ?></td>
						<td><?php echo $rec->order_no; ?></td> 
						<td><?php echo $user->user_name; ?></td> 
						<td><?php echo $ttl_payment." Rs"; ?></td> 
						<td><?php echo ucfirst($payment->type); ?></td>
						<td><?php echo date('d-m-Y',strtotime($rec->created_date)); ?></td> 
						<td><?php echo $rec->delivered==1?"Delivered":"Pending"; ?></td> 
						
						<td>
							<a title="View" href="<?php echo base_url('admin/view_order_details/'.$rec->id.'/'.$rec->order_no); ?>"  class="btn btn-primary btn-xs" > <i class="fa fa-eye"></i></a>
							<!--<a title="Edit" href="<?php echo base_url('admin/edit_admin/'.$rec->admin_id); ?>"  class="btn btn-primary btn-xs" > <i class="fa fa-pencil"></i></a>
							<a title="Delete" href="<?php echo base_url('admin/delete_admin/'.$rec->admin_id); ?>" onclick="return confirm('Are you sure you want to delete this admin?');" class="btn btn-primary btn-xs" > <i class="fa fa-trash"></i></a>-->
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
		<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
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
		
		$(document).ready(function(){
			$('#select_all').on('click',function(){
				$(this).closest('table').find(':checkbox').prop('checked',this.checked);
			});
			
			$('.checkbox').on('click',function(){
				var array = [];
				$.each($(".checkbox:checked"), function(){
					array.push($(this).val());
				}); 
				//alert("My chk are: " + array.join(", "));
				
				if($('.checkbox:checked').length == $('.checkbox').length){
					$('#select_all').prop('checked',true);
					
				}else{
					$('#select_all').prop('checked',false);
				}
				
				$('.bpop').click(function() {
				var chkId = array.join(",");
				$.ajax({
				  url: '<?php echo base_url();?>admin/delivered_chk',
				  type: 'post',
				  data: { chkId: chkId },
				  success: function (response) {
					console.log(response);
					location.reload();
				  }
				});
			  });
			});
		});
		</script>
       
  