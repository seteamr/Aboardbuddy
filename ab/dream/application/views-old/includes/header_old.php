<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TheDream99</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="TheDream99">
    <meta name="msapplication-tap-highlight" content="no">
  
<link href="<?php echo base_url(); ?>main.css" rel="stylesheet"></head>
<body>
    <style>
    .app-sidebar__inner {
    overflow-y: scroll;
    height: 100%;
}
</style>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        
		 <?php 
		 $uri1 = $this->uri->segment('1');
		 $uri2 = $this->uri->segment('2');
		 $user=$this->common_model->getsingle('user_master',array('user_master_id'=>$this->session->userdata('user_master_id')));
		 ?>
		<div class="app-header header-shadow">
            <div class="app-header__logo">
                <!-- <div class="logo-src"></div> -->
                <div>
                	<img src="<?php echo base_url('uploads/logo.jpg'); ?>" width="52px">
                </div>
				<div class="new_adddedd" style="padding:10px;">
					<?php echo $this->session->userdata('user_name'); ?> </br>
					<?php echo $user->name; ?>
				</div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>    
			<div class="app-header__content">
                <!--
				<div class="app-header-left">
                    <div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Type to search">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="close"></button>
                    </div>
                    <ul class="header-menu nav">
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon fa fa-database"> </i>
                                Statistics
                            </a>
                        </li>
                        <li class="btn-group nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon fa fa-edit"></i>
                                Projects
                            </a>
                        </li>
                        <li class="dropdown nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon fa fa-cog"></i>
                                Settings
                            </a>
                        </li>
                    </ul>        
				</div>
				-->
                
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <?php if($user->profile_image!=''){ ?>
                                            <img width="42" class="rounded-circle" src="<?php echo base_url('uploads/profile_image/'.$user->profile_image); ?>" alt="">
                                            <?php }else{ ?>
                                             <img width="42" class="rounded-circle" src="<?php echo base_url('uploads/profile_image/empty_profile_image.png'); ?>" alt="">
                                            <?php } ?>
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                           <?php if($this->session->userdata('user_type')=='3'){ ?>
										   <a href="<?php echo base_url(); ?>admin/setting" tabindex="0" class="dropdown-item">General Setting</a> 
										   <a href="<?php echo base_url(); ?>admin/offlist" tabindex="0" class="dropdown-item">Off List</a> 
										   <a href="<?php echo base_url('admin/results'); ?>" tabindex="0" class="dropdown-item" > Generate Result </a>
										   <?php } ?>
                                           <a href="<?php echo base_url(); ?>login/profile" tabindex="0" class="dropdown-item">Profile</a>           
                                           <a href="<?php echo base_url(); ?>login/change_password" tabindex="0" class="dropdown-item">Change Password</a>         
                                           <a href="<?php echo base_url(); ?>login/logout" tabindex="0" class="dropdown-item">Logout</a>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        <?php echo $this->session->userdata('user_name'); ?> </br>
										<?php echo $user->name; ?>
                                    </div>
                                    <div class="widget-subheading">
                                       <?php 
									   if($this->session->userdata('user_type')=='3')
										{
											echo "Admin";
											$myrole="admin";
										}
										else if($this->session->userdata('user_type')=='2')
										{
											echo 'Distributor';
											$myrole="distributor";
										}
										else if($this->session->userdata('user_type')=='1')
										{
											echo 'Retailer';
											$myrole="retailer";
										}
										else{					
											echo 'Player';
											$myrole="player";
										}
									   ?>
                                    </div>
                                </div>
                               <!-- <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                        <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                                    </button>
                                </div> -->
                            </div>
                        </div>
                    </div>        
				</div>
            </div>
        </div>        
		    
		<div class="app-main">
                <div class="app-sidebar sidebar-shadow">
                    <div class="app-header__logo">
                        <div class="logo-src"></div>
                        <div class="header__pane ml-auto">
                            <div>
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div>
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="app-header__menu">
                        <span>
                            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                <span class="btn-icon-wrapper">
                                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                                </span>
                            </button>
                        </span>
                    </div>    <div class="scrollbar-sidebar">
                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Dashboards</li>
                                <li>
                                    <a href="<?php echo base_url('results'); ?>">
                                        <i class="metismenu-icon pe-7s-back"></i>
                                        Result
                                    </a>
                                </li>
							
								<li>
                                    <a href="<?php echo base_url($myrole); ?>" <?php if($uri1==$myrole && $uri2=="" ){ echo 'class="mm-active" '; } ?>>
                                        <i class="metismenu-icon pe-7s-culture"></i>
                                        Dashboard
                                    </a>
                                </li>
							<?php if($this->session->userdata('user_type')!='0'){ ?>
                                <li class="app-sidebar__heading">User Management</li>
                                <li <?php if($uri1=="users"){ echo 'class="mm-active" '; } ?> >
                                    <a href="#" <?php if($uri1=="users" && $uri2==""){ echo 'class="mm-active" '; } ?> >
                                        <i class="metismenu-icon pe-7s-users"></i>
                                        Users
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url(); ?>users/add" <?php if($uri1=="users" && $uri2=="add"){ echo 'class="mm-active" '; } ?> >
                                                <i class="metismenu-icon"></i>
                                                Add User
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url(); ?>users" <?php if($uri1=="users" && ( $uri2=="index" or $uri2=="" ) ){ echo 'class="mm-active" '; } ?> >
                                                <i class="metismenu-icon">
                                                </i>User List
                                            </a>
                                        </li>                                        
                                    </ul>
                                </li>
								<!--
								<li class="app-sidebar__heading">Shop Management</li>
                                <li <?php if($uri1=="shops"){ echo 'class="mm-active" '; } ?> >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pendrive"></i>
                                        Shops
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url(); ?>shops/add" <?php if($uri1=="shops" &&  $uri2=="add" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Add shop
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url(); ?>shops" <?php if($uri1=="shops" && ( $uri2=="index" or $uri2=="" ) ){ echo 'class="mm-active" '; } ?> >
                                                <i class="metismenu-icon">
                                                </i>Shop List
                                            </a>
                                        </li>                                        
                                    </ul>
                                </li>
								-->								
						<?php } ?>
							
							<li class="app-sidebar__heading">Points Management</li>
							<li <?php if($uri1=="points"){ echo 'class="mm-active" '; } ?> >
								<a href="#">
									<i class="metismenu-icon pe-7s-cash"></i>
									Points
									<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
								</a>
								<ul>
									<li>
										<a href="<?php echo base_url(); ?>points/transfer" <?php if($uri1=="points" && $uri2=="transfer"){ echo 'class="mm-active" '; } ?>>
											<i class="metismenu-icon"></i>
											Transfer
										</a>
									</li>
									<?php if($this->session->userdata('user_type')!='0'){ ?>
									<li>
										<a href="<?php echo base_url(); ?>points/withdraw" <?php if($uri1=="points" && $uri2=="withdraw"){ echo 'class="mm-active" '; } ?>>
											<i class="metismenu-icon"></i>
											Withdraw
										</a>
									</li>
									<?php } ?>
									<li>
										<a href="<?php echo base_url(); ?>points" <?php if($uri1=="points" && ( $uri2=="index" or $uri2=="" ) ){ echo 'class="mm-active" '; } ?> >
											<i class="metismenu-icon">
											</i>List
										</a>
									</li>                                        
								</ul>
							</li>
							
						
								<li class="app-sidebar__heading">Report</li>
                                <li <?php if($uri1=="reports"){ echo 'class="mm-active" '; } ?>>
								   <a href="#">
                                        <i class="metismenu-icon pe-7s-display2"></i>
                                        Transactions
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
										
										<?php if($this->session->userdata('user_type')!='0'){ ?>
										<li>
                                            <a href="<?php echo base_url(); ?>reports/play_history" <?php if($uri1=="reports" && $uri2=="play_history" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Player History
                                            </a>
                                        </li>  
										<?php } ?>
										
										<?php if($this->session->userdata('user_type')!='0'){ ?>
										<li>
                                            <a href="<?php echo base_url(); ?>reports/net_to_pay" <?php if($uri1=="reports" && $uri2=="net_to_pay" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Net to Pay By Retailer
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo base_url(); ?>reports/net_to_pay_user" <?php if($uri1=="reports" && $uri2=="net_to_pay_user" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Net To Pay By Distributor
                                            </a>
                                        </li>  
										<?php } ?>
										
										<?php if($this->session->userdata('user_type')!='0'){ ?>
										<li>
                                            <a href="<?php echo base_url(); ?>reports/counter_sale" <?php if($uri1=="reports" && $uri2=="counter_sale" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Counter Sale
                                            </a>
                                        </li>  
										<?php } ?>
										
										 <?php if($this->session->userdata('user_type')!='0'){ ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>reports/bonus_history" <?php if($uri1=="reports" && $uri2=="bonus_history" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Bonus History
                                            </a>
                                        </li>  
                                        <?php } ?>
										
										<?php if($this->session->userdata('user_type')=='1' or $this->session->userdata('user_type')=='2'){ ?>
										<li>
                                            <a href="<?php echo base_url(); ?>reports/commission" <?php if($uri1=="reports" && $uri2=="commission" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Commission
                                            </a>
                                        </li>  
										<?php } ?>
										
										<?php if($this->session->userdata('user_type')=='3'){ ?>
										<li>
                                            <a href="<?php echo base_url(); ?>reports/point_managment" <?php if($uri1=="reports" && $uri2=="point_managment" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Point Managment
                                            </a>
                                        </li>
									   <li>
                                            <a href="<?php echo base_url(); ?>reports/transactions" <?php if($uri1=="reports" && $uri2=="transactions" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                All Transaction
                                            </a>
                                        </li> 
										
										
										
										<?php } ?>
										
										
										
                                    </ul>
									
																		
                                </li>
								
								<?php if($this->session->userdata('user_type')=='3'){ ?>
								<li class="app-sidebar__heading">Support</li>
								<li <?php if($uri1=="supports"){ echo 'class="mm-active" '; } ?>>
								   <a href="#">
                                        <i class="metismenu-icon pe-7s-display2"></i>
                                        List
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo base_url(); ?>supports" <?php if($uri1=="supports" ){ echo 'class="mm-active" '; } ?>>
                                                <i class="metismenu-icon"></i>
                                                Support List
                                            </a>
                                        </li> 
                                    </ul>
									
																		
                                </li>
								
								<?php } ?>
						
                                
                            </ul>
                        </div>
                    </div>
                </div>    
				<div class="app-main__outer">