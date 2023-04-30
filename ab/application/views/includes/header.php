<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Admin</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css/animate.min.css" rel="stylesheet">
  <!-- Custom styling plus plugins -->
  <link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/maps/jquery-jvectormap-2.0.3.css" />
  <link href="<?php echo base_url(); ?>css/icheck/flat/green.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css/floatexamples.css" rel="stylesheet" />
  <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
  <link href="<?php echo base_url(); ?>css/select/select2.min.css" rel="stylesheet">

</head>
<style>
.menu_section >ul {
    margin-top: 70px;
}
</style>
<body class="nav-md">

  <div class="container body">

    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo base_url('admin'); ?>" class="site_title"> <span>Site Title</span></a>

          </div>
          <div class="clearfix"></div>
		<?php $admin = $this->common_model->getsingle('admin', array('admin_id' => $this->session->userdata('admin_id')));?>
          <!-- menu prile quick info -->
          <div class="profile" >
            <div class="profile_pic">
              <img src="<?php echo base_url(); ?>images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo $admin->name; ?></h2>
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <h3></h3>
              <ul class="nav side-menu">
                 <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="<?php echo base_url('admin'); ?>">Dashboard</a>
                    </li>
                  </ul>
                </li>

                <li><a href="<?php echo base_url('admin/student_list') ?>"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Students </a></li>
				<li><a href="<?php echo base_url('admin/sellers') ?>"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Sellers </a></li>
                <li><a href="<?php echo base_url('admin/ads') ?>"><i class="fa fa-file-video-o" aria-hidden="true"></i> Ads </a></li>
				<li><a href="<?php echo base_url('admin/support_list') ?>"><i class="fa fa-info-circle" aria-hidden="true"></i> Support </a></li>

				<!--<li><a><i class="fa fa-product-hunt"></i> Quantity <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
					  <li><a href="<?php //echo base_url('admin/add_product'); ?>">Add Quantity</a>
					  <li><a href="<?php //echo base_url('admin/product_list'); ?>">Quantity List</a>
                    </li>
                  </ul>
                </li>-->
              </ul>
            </div>
          </div>

        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img src="<?php echo base_url(); ?>images/img.jpg" alt=""><?php echo $admin->name; ?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                 <li><a href="<?php echo base_url('admin/change_password'); ?>">Change Password</a></li>
                <?php if ($this->session->userdata('type') == 'super') {?>
                    <li><a href="<?php echo base_url('admin/setting/' . $this->session->userdata('admin_id')); ?>">Setting</a></li>
                <?php }?>
                    <li><a href="<?php echo base_url('login/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </div>