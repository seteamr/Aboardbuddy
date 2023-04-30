<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

   <title><?php echo $title; ?> </title>

  <!-- Bootstrap core CSS -->

  <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css/icheck/flat/green.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

  <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>


</head>
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
<body class="nav-md">

  <div class="container body">


    <div class="main_container">

      <?php //$this->load->view('includes/sidebar'); ?>
	  
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
                  <h2>Category List<small></small></h2>
					<a href="<?php echo base_url('admin/add_category'); ?>" class="btn btn-success pull-right">Add New Category</a>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">                  
                  <table id="datatable1" class="table table-striped table-bordered">
                    <thead>
                      <tr>                       
						<th width="2%">Sno.</th>
                        <th width="10%" >Category Name</th>
						<th width="10%" >Category image</th>
						<th width="10%" >status</th>
						<th width="13%" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
					  <?php if($records){ ?>
					  <?php foreach($records as $rec){ ?>
                      <tr>
						<td><?php echo $sno; ?></td>                        
                        <td><?php echo $rec->category_name; ?></td>
						<td><img src="<?php echo base_url().'uploads/category/'.$rec->image; ?>" class="img-responsive"  width="50" height="50"></td>                        
						<td><?php if($rec->status==0){ ?>
							<div class="btn btn-danger btn-xs">Deactive</div>
							<?php }else{ ?>
							<div class="btn btn-success btn-xs">Active</div>
							<?php } ?>
						</td>
						<td>
							<a title="Edit"  href="<?php echo base_url('admin/edit_category/'.$rec->id); ?>"  class="btn btn-primary btn-xs" > <i class="fa fa-pencil"></i> </i></a>
							<!--<a title="Delete"  onclick="return confirm('Are you sure you want to delete this category?');" href="<?php echo base_url('admin/delete_category/'.$rec->id); ?>"  class="btn btn-danger btn-xs" > <i class="fa fa-trash"></i></a>-->
							
						<?php if($rec->status==1){ ?>
							<a title="Enable" onclick="return confirm('Are you sure you want to deactive this category?');" href="<?php echo base_url('admin/category_status/'.$rec->id.'/0'); ?>"  class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
						<?php }else{ ?>
							<a title="Disable" onclick="return confirm('Are you sure you want to active this category?');" href="<?php echo base_url('admin/category_status/'.$rec->id.'/1'); ?>"  class="btn btn-success btn-xs" ><i class="fa fa-check"></i></a>
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
          </div>

        </div>

        <div id="custom_notifications" class="custom-notifications dsp_none">
          <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
          </ul>
          <div class="clearfix"></div>
          <div id="notif-group" class="tabbed_notifications"></div>
        </div>

        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

        <!-- bootstrap progress js -->
        <script src="<?php echo base_url(); ?>js/progressbar/bootstrap-progressbar.min.js"></script>
        <script src="<?php echo base_url(); ?>js/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- icheck -->
        <script src="<?php echo base_url(); ?>js/icheck/icheck.min.js"></script>

        <script src="<?php echo base_url(); ?>js/custom.js"></script>


        <!-- Datatables -->
        <!-- <script src="js/datatables/js/jquery.dataTables.js"></script>
  <script src="js/datatables/tools/js/dataTables.tableTools.js"></script> -->

        <!-- Datatables-->
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


        <!-- pace -->
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
</body>

</html>
