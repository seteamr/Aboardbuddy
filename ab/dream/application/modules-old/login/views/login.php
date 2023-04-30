<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>New Rajashree  </title>

  <!-- Bootstrap core CSS -->

  <link href="<?php echo base_url(); ?>css_js/css/bootstrap.min.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>css_js/fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css_js/css/animate.min.css" rel="stylesheet">

  <!-- Custom styling plus plugins -->
  <link href="<?php echo base_url(); ?>css_js/css/custom.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>css_js/css/icheck/flat/green.css" rel="stylesheet">


  <script src="<?php echo base_url(); ?>css_js/js/jquery.min.js"></script>

</head>

<body style="background:#F7F7F7;">

  <div class="">
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>

    <div id="wrapper">
      <div id="login" class="animate form">
        <section class="login_content">
          <form id="login_form" name="login_form" action="<?php echo base_url('login'); ?>" method="POST">
            <h1>Login Form</h1>
            <div>
               <input type="text" placeholder="Client Code" title="Please enter Client Code" name="username" id="username" class="form-control" value="<?php echo set_value('username'); ?>">
				<span style="color:red"><?php echo form_error('username'); ?></span>
            </div>
            <div>
              <input type="password" title="Please enter your password" placeholder="******" name="password" id="password" class="form-control" value="<?php echo set_value('password'); ?>">
			   <span style="color:red"><?php echo form_error('password'); ?></span>
			   <span style="color:red"><?php echo $errors; ?></span>
            </div>
            <div>
               <button class="btn btn-success btn-block loginbtn" type="submit">Log In</button>
                  
            </div>
            <div class="clearfix"></div>
            <div class="separator">

              
              <div class="clearfix"></div>
              <br />
              <div>
                <h1><i class="fa fa-paw" style="font-size: 26px;"></i> New Rajashree </h1>

                <p>©2021 All Rights Reserved.</p>
              </div>
            </div>
          </form>
          <!-- form -->
        </section>
        <!-- content -->
      </div>
      </div>
  </div>

</body>

</html>
