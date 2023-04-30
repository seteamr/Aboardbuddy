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
                  <h2>View Info<small></small></h2>
					
                  <div class="clearfix"></div>
                </div>
				  
                <div class="x_content"> 
				  
				   <div class="row">


          <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel tile fixed_height_320">
              <div class="x_title">
                <h2>Retailer Details</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
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
          </div>
		  
		  
		   <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel tile fixed_height_320">
              <div class="x_title">
                <h2>Current Week</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
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
									<label for="examplePassword22" class="mr-sm-2">Revenue: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_commision; ?></label>
								</div> 
								
								<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Net Coin: </label>
									<label for="examplePassword22" class="mr-sm-2"><?php echo $current_week_sale-$current_week_winning-$current_week_commision-$current_week_bonus; ?></label>
								</div> 
								
						</div>
              </div>
            </div>
          </div>
		  
		   <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel tile fixed_height_320">
              <div class="x_title">
                <h2>Last Week</h2>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
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
						<label for="examplePassword22" class="mr-sm-2">Revenue: </label>
						<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_commision; ?></label>
					</div> 
					
					<div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
						<label for="examplePassword22" class="mr-sm-2">Net Coin: </label>
						<label for="examplePassword22" class="mr-sm-2"><?php echo $last_week_sale-$last_week_winning-$last_week_commision-$last_week_bonus; ?></label>
					</div> 
					

					
				</div>
              </div>
            </div>
          </div>
			<div>
						<a href="javascript:history.back()" class="btn btn-success">Back</a>
					</div>
        </div>
				  
                 <table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Login Name</th>
									<th>Role</th>
									<th>Reporting</th>
									<th>Commision</th>
									<th>Current Coin</th>
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
