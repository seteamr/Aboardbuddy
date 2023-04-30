<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>New RajaShree</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="TheDream99">
    <meta name="msapplication-tap-highlight" content="no">
  
<link href="<?php echo base_url(); ?>main.css" rel="stylesheet"></head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        
		 <?php 
		 $uri1 = $this->uri->segment('1');
		 $uri2 = $this->uri->segment('2');
		 ?>
		 <!--
		<div class="app-header header-shadow">
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
            </div>    
			<div class="app-header__content">
                
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="<?php echo base_url(); ?>assets/images/avatars/1.jpg" alt="">
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <button type="button" tabindex="0" class="dropdown-item">User Account</button>
                                            <button type="button" tabindex="0" class="dropdown-item">Settings</button>                                           
                                            <a href="<?php echo base_url(); ?>login/logout" tabindex="0" class="dropdown-item">Logout</a>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        <?php echo $this->session->userdata('user_name'); ?>
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
                              
                            </div>
                        </div>
                    </div>        
				</div>
            </div>
        </div>        
		-->
		<style>
		.fixed-sidebar .app-main .app-main__outer.logout {
			padding: 0;
		}
		</style>
		<div class="app-main">
                <div class="app-main__outer logout">