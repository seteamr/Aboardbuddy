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
                  <h2>Support List</h2>
				
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">  

                    
                  <table id="datatable1" class="table table-striped table-bordered text-center">
                    <thead>
                      <tr>                       
						<th style="text-align: center">Sr. No.</th>
						<th style="text-align: center">First Name</th>
                        <th style="text-align: center">Last Name</th>
						<th style="text-align: center">Country</th>
						<th style="text-align: center">Description</th>
						<th style="text-align: center">Date</th>
                      </tr>
                    </thead>
                    <tbody>
					  <?php if($data){ ?>
					  <?php foreach($data as $row){ 
					  ?>
                      <tr>
						<td><?php echo $sno; ?></td>
						<td><?php echo $row->first_name; ?></td> 
						<td><?php echo $row->last_name; ?></td> 
						<td><?php echo $row->country; ?></td> 
						<td><?php echo $row->description; ?></td> 
						<td><?php echo $row->created_at; ?></td> 
                      </tr>	
					  <?php $sno++; } ?>
					  <?php } ?>
                    </tbody>
                  </table>
					<!-- pagination start -->
                    <div class="mr-10" style="float:right;">
                        <?php if (isset($links)) { ?>
                            <nav class="" aria-label="Page navigation example">
                        <?php echo $links ?>
                            </nav>
                        <?php } ?>
                    </div>
                    <!-- pagination end  -->
                    </div>
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
  