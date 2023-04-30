<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>New RajaShree </title>

  <!-- Bootstrap core CSS -->

  <link href="<?php echo base_url(); ?>css_js/css/bootstrap.min.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>css_js/fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css_js/css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="<?php echo base_url(); ?>css_js/css/custom.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css_js/css/maps/jquery-jvectormap-2.0.3.css" />
  <link href="<?php echo base_url(); ?>css_js/css/icheck/flat/green.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>css_js/css/floatexamples.css" rel="stylesheet" type="text/css" />

  <script src="<?php echo base_url(); ?>css_js/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>css_js/js/nprogress.js"></script>

  <style>
  .menu_section a:hover {
    cursor: pointer;
}
</style>

</head>

 <?php 
 $uri1 = $this->uri->segment('1');
 $uri2 = $this->uri->segment('2');
 $user=$this->common_model->getsingle('user_master',array('user_master_id'=>$this->session->userdata('user_master_id')));

   if($this->session->userdata('user_type')=='3')
	{
		$myrole="admin";
	}
	else if($this->session->userdata('user_type')=='2')
	{
		$myrole="distributor";
	}
	else if($this->session->userdata('user_type')=='1')
	{
		$myrole="retailer";
	}
	else{
		$myrole="player";
	}
   ?>
<body class="nav-md">

  <div class="container body">


    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
			<a href="<?php echo base_url($myrole); ?>" class="site_title"><i class="fa fa-paw"></i> <span>New RajaShree</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
				<?php if($user->profile_image!=''){ ?>
				<img width="42" class="img-circle profile_img" src="<?php echo base_url('uploads/profile_image/'.$user->profile_image); ?>" alt="">
				<?php }else{ ?>
				 <img width="42" class="img-circle profile_img" src="<?php echo base_url('uploads/profile_image/empty_profile_image.png'); ?>" alt="">
				<?php } ?>
              
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo $this->session->userdata('user_name'); ?> </br>
					<?php echo $user->name; ?></h2>
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
              <h3>General</h3>
              <ul class="nav side-menu">
                <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="<?php echo base_url($myrole); ?>">Dashboard</a>
                    </li>
					<li><a href="<?php echo base_url('results'); ?>">Results</a>
                    </li>                      
                  </ul>
                </li>
				<?php if($this->session->userdata('user_type')!='0'){ ?>
                
				<li><a><i class="fa fa-users"></i> Clients <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="<?php echo base_url(); ?>users/add">Add Client</a></li>
					<li><a href="<?php echo base_url(); ?>users">Clients</a></li>
                  </ul>
                </li>
				
				<?php } ?>
				
                <li><a><i class="fa fa-money"></i>Coins Management <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="<?php echo base_url(); ?>points/transfer">Transfer Coins</a></li>
					<?php if($this->session->userdata('user_type')!='0'){ ?>
				   <li><a href="<?php echo base_url(); ?>points/withdraw">Adjust Coins</a></li>
					<?php } ?>
					<li><a href="<?php echo base_url(); ?>points">Coins List</a></li>
                  </ul>
                </li>
				
                <li><a><i class="fa fa-bar-chart-o"></i> Bet History <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <?php if($this->session->userdata('user_type')!='0'){ ?>
					<li><a href="<?php echo base_url(); ?>reports/play_history">Records</a></li>
					<li><a href="<?php echo base_url(); ?>reports/net_to_pay">Client Payout</a></li>
					<li><a href="<?php echo base_url(); ?>reports/net_to_pay_user">Agent Payout</a></li>
					<!--<li><a href="<?php echo base_url(); ?>reports/counter_sale">Salling</a></li> -->
					<!--<li><a href="<?php echo base_url(); ?>reports/bonus_history">History</a></li> -->
					<?php } ?>
					
					<?php if($this->session->userdata('user_type')=='1' or $this->session->userdata('user_type')=='2'){ ?>
					<li><a href="<?php echo base_url(); ?>reports/commission">Revenue</a></li>
					<?php } ?>
					
					<?php if($this->session->userdata('user_type')=='3'){ ?>
					<!--<li><a href="<?php echo base_url(); ?>reports/point_managment">Points</a></li> -->
					<li><a href="<?php echo base_url(); ?>reports/transactions">All Records</a></li>
					<?php } ?>
                  </ul>
                </li>
				
				<!--<li><a><i class="fa fa-windows"></i> Helps <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="<?php echo base_url(); ?>supports">List</a></li>
                  </ul>
                </li> -->
				
                
              </ul>
            </div>
            
          </div>
          <!-- /sidebar menu -->

          
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
                  <?php if($user->profile_image!=''){ ?>
					<img src="<?php echo base_url('uploads/profile_image/'.$user->profile_image); ?>" alt="">
					<?php }else{ ?>
					 <img src="<?php echo base_url('uploads/profile_image/empty_profile_image.png'); ?>" alt="">
					<?php } ?>
				  
				 <?php echo $user->name; ?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                   <?php if($this->session->userdata('user_type')=='3'){ ?>
					<li><a href="<?php echo base_url(); ?>admin/setting" >Setting</a> </li> 
					<li><a href="<?php echo base_url(); ?>admin/offlist" >On Off </a> </li> 
					<li><a href="<?php echo base_url('admin/results'); ?>" > Decleare Akada </a> </li> 										   
				   <?php } ?>
				  
				  <li><a href="<?php echo base_url(); ?>login/profile">  Account</a> </li> 
				  <li><a href="<?php echo base_url(); ?>login/change_password">  Change Password</a> </li>                  
                  <li><a href="<?php echo base_url(); ?>login/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </li>
                </ul>
              </li>

            </ul>
          </nav>
        </div>

      </div>
      <!-- /top navigation -->
