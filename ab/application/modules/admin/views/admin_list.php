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
                  <h2>Admin List<small></small></h2>
				<a href="<?php echo base_url('admin/add_admin'); ?>" class="btn btn-success pull-right">Add New Admin</a>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">  

                    
                  <table id="datatable1" class="table table-striped table-bordered">
                    <thead>
                      <tr>                       
						<th>Sno.</th>
						<th>Name</th>
                        <th>Category</th>
						<th>Email</th>
						<th width="10%" >status</th>						
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
					  <?php if($records){ ?>
					  <?php foreach($records as $rec){
						$cat = $this->common_model->getsingle('categories', array('id'=>$rec->cat_id,'status!='=>2));   
					  ?>
                      <tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $rec->name; ?></td> 
						<td><?php echo $cat->category_name; ?></td> 
						<td><?php echo $rec->email; ?></td> 
						<td><?php if($rec->status==0){ ?>
							<div class="btn btn-danger btn-xs">Deactive</div>
							<?php }else{ ?>
							<div class="btn btn-success btn-xs">Active</div>
							<?php } ?>
						</td>						
						
						<td>
						
						<a title="Edit" href="<?php echo base_url('admin/edit_admin/'.$rec->admin_id); ?>"  class="btn btn-primary btn-xs" > <i class="fa fa-pencil"></i></a>
						<!--<a title="Delete" href="<?php echo base_url('admin/delete_admin/'.$rec->admin_id); ?>" onclick="return confirm('Are you sure you want to delete this admin?');" class="btn btn-primary btn-xs" > <i class="fa fa-trash"></i></a>-->
						<?php if($rec->status==1){ ?>
							<a title="Enable" onclick="return confirm('Are you sure you want to deactive this admin?');" href="<?php echo base_url('admin/admin_status/'.$rec->admin_id.'/0'); ?>"  class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
						<?php }else{ ?>
							<a title="Disable" onclick="return confirm('Are you sure you want to active this admin?');" href="<?php echo base_url('admin/admin_status/'.$rec->admin_id.'/1'); ?>"  class="btn btn-success btn-xs" ><i class="fa fa-check"></i></a>
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
          
        </script> 
  