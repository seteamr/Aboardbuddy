 <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Add Client <small></small></h2>
                 
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <form action="<?php echo base_url(); ?>users/add" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Name <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="text" class="form-control col-md-7 col-xs-12" id="name" name="name" placeholder="Full Name" value="<?php echo set_value('name'); ?>" required>							
							<span style="color:red;"> 
								<?php echo form_error('name'); ?>
							</span>
					  </div>
                    </div>
					
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<select name="user_type" id="user_type" onchange="getreporting_user();" class="form-control" required>
							<option value="">Select Role</option>								
							<?php if($this->session->userdata('user_type')=='3'){ ?>
							<option value="0" <?php if(set_value('user_type')=="0"){ echo "selected"; } ?> >Customer</option>
							<option value="1" <?php if(set_value('user_type')=="1"){ echo "selected"; } ?> >Client</option>
							<option value="2" <?php if(set_value('user_type')=="2"){ echo "selected"; } ?> >Agent</option>
							<?php } ?>
							
							<?php if($this->session->userdata('user_type')=='2'){ ?>
							<option value="0" <?php if(set_value('user_type')=="0"){ echo "selected"; } ?> >Customer</option>
							<option value="1" <?php if(set_value('user_type')=="1"){ echo "selected"; } ?> >Client</option>
							<?php } ?>
							
							<?php if($this->session->userdata('user_type')=='1'){ ?>
							<option value="0" <?php if(set_value('user_type')=="0"){ echo "selected"; } ?> >Customer</option> 
							<?php } ?>
							
						</select>
						<span style="color:red;"> 
							<?php echo form_error('user_type'); ?>
						</span>
					  </div>
                    </div>
					
					<?php
							$users ="";
						if(set_value('user_type')=="0"){
							if($this->session->userdata('user_type')=='1')
							{
								$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$this->session->userdata('user_master_id'),'user_type'=>'1','is_user_deleted'=>'0'));
							}
							else if($this->session->userdata('user_type')=='2')
							{
								$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$this->session->userdata('user_master_id'),'user_type'=>'1','is_user_deleted'=>'0'));
							}else{
								$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'1','is_user_deleted'=>'0'));
							}
						}
						else if(set_value('user_type')=="1")
						{
							if($this->session->userdata('user_type')=='2')
							{
								$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$this->session->userdata('user_master_id'),'user_type'=>'2','is_user_deleted'=>'0'));
							}else{
								$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'2','is_user_deleted'=>'0'));
							}
						}
						else if(set_value('user_type')=="2")
						{
							$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'3','is_user_deleted'=>'0'));
						}
						
						$shops = $this->common_model->getAllwhere('shop_master',array('is_shop_deleted'=>'0'));
						?>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Head <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						
						<select onchange="getcommision();" name="reporting_user_master_id" id="reporting_user_master_id" class="form-control" required>
							<option value="">Select Head</option>
							<?php if($users!=""){ ?>
							<?php foreach($users as $us){ ?>
							<option  value="<?php echo $us->user_master_id; ?>"  <?php if(set_value('reporting_user_master_id')==$us->user_master_id){ echo "selected"; } ?> ><?php echo $us->user_name; ?> ( <?php echo $us->name; ?> ) </option>
							<?php } ?>								
							<?php } ?>								
						</select>
							<span style="color:red;"> 
								<?php echo form_error('reporting_user_master_id'); ?>
							</span>
					  </div>
                    </div>
					
					<div id="user_comission_div" class="form-group" <?php if(set_value('user_type')=="2" or set_value('user_type')=="1" ){ echo "style='display:block;';"; }else{ echo "style='display:none;';"; } ?> >
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_comission" id="com">Revenue </label>
							<div class="col-md-6 col-sm-6 col-xs-12">  
							<?php 
							if(set_value('user_type')=="1")
							{
								$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>set_value('reporting_user_master_id')));
								$com = $user_data->user_comission;
							?>
							<input type="number" max="<?php echo $com; ?>" min="0" onkeyup="if(this.value > <?php  echo $com; ?> || this.value < 0) this.value = null;" class="form-control" id="user_comission" name="user_comission" placeholder="Enter Revenue" value="<?php echo set_value('user_comission'); ?>" required>							
							<?php }else{ ?>
							<input type="number" max="10.00" min="0" onkeyup="if(this.value > 10 || this.value < 0) this.value = null;" class="form-control" id="user_comission" name="user_comission" placeholder="Enter Revenue" value="<?php echo set_value('user_comission'); ?>" required>							
							<?php } ?>
							<span style="color:red;"> 
								<?php echo form_error('user_comission'); ?>
							</span>
							</div>
					</div>	
					
					<?php if($this->session->userdata('user_type')=='3'){ ?>
					<div style="display:none">
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Won Distribution <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="number" class="form-control" onkeyup="chk2();" onblur="chk2();" id="winning_distribution" name="winning_distribution" placeholder="Won Distribution" value="<?php echo set_value('winning_distribution'); ?>">
						<span style="color:red;"> 
							<?php echo form_error('winning_distribution'); ?>
						</span>
					  </div>
                    </div>
					
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Max Won <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="number" class="form-control" onkeyup="chk3();" onblur="chk3();" id="max_winning" name="max_winning" placeholder="Max Won" value="<?php echo set_value('max_winning'); ?>">
						<span style="color:red;"> 
							<?php echo form_error('max_winning'); ?>
						</span>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bonus % <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="number" onkeyup="chk();" onblur="chk();" class="form-control" id="bonus_percent" name="bonus_percent" placeholder="Bonus %" value="<?php echo "0";//set_value('bonus_percent'); ?>">
						<span style="color:red;"> 
							<?php echo form_error('bonus_percent'); ?>
						</span>
					  </div>
                    </div>
					
					</div>
					<?php } ?>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<select name="is_user_deleted" id="is_user_deleted" class="form-control" required>
								<option value=""> Status</option>
								<option value="0" <?php if(set_value('is_user_deleted')=="0"){ echo "selected"; } ?> >Active</option>
								<option value="1" <?php if(set_value('is_user_deleted')=="1"){ echo "selected"; } ?> >Disable</option>
							</select>
							<span style="color:red;"> 
								<?php echo form_error('is_user_deleted'); ?>
							</span>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Profile Image <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="file" class="form-control" id="profile_image" name="profile_image" placeholder="Choose file" value="<?php echo set_value('profile_image'); ?>" required>							
							<span style="color:red;"> 
								<?php echo $errors; ?>
							</span>
					  </div>
                    </div>
                   
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>

          <script type="text/javascript">
            $(document).ready(function() {
              $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
              }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
              });
            });
          </script>


    </div>
  </div>
  
  	<script type="text/javascript">
	
	function chk3(){
		var max_winning = $("#max_winning").val();
		if(max_winning<0)
		{
			$("#max_winning").val('0');
		}
		else if(max_winning>100000)
		{
			$("#max_winning").val('100000');
		}
		
	}
	
	function chk2(){
		var winning_distribution = $("#winning_distribution").val();
		if(winning_distribution<0)
		{
			$("#winning_distribution").val('0');
		}
		else if(winning_distribution>200)
		{
			$("#winning_distribution").val('200');
		}
		
	}
	function chk(){
		var bonus_percent = $("#bonus_percent").val();
		if(bonus_percent<0)
		{
			$("#bonus_percent").val('0');
		}
		else if(bonus_percent>5)
		{
			$("#bonus_percent").val('5');
		}
		
	}
    function getreporting_user()
    {
		var user_type = $("#user_type").val();
		$("#user_comission").val('');
		
		if(user_type==1)
		{
			$("#user_comission_div").hide();
			$("#shop_master_id_div").show();
		}else{
			$("#shop_master_id_div").hide();
			if(user_type==2)
			{
				$("#user_comission_div").show();
				$("#com").html('Revenue (max 10.00)');
				$("#user_comission").attr('max','10.00');
				$("#user_comission").attr('onkeyup','if(this.value > 10.00 || this.value < 0) this.value = null;');
			}
		}
		
		var params = {user_type: user_type};
		$.ajax({
			url: '<?php echo base_url();?>users/getreporting_user',
			type: 'post',
			data: params,
			success: function (r)
			 {
				 $("#reporting_user_master_id").html(r);
			 }
		});                     
    }
	function getcommision(){
		var user_type = $("#user_type").val();
		var user_master_id = $("#reporting_user_master_id").val();
		if(user_type==1)
		{
			var params = {user_master_id: user_master_id};
			$.ajax({
				url: '<?php echo base_url();?>users/get_user_commision',
				type: 'post',
				data: params,
				success: function (rr)
				 { 
					var r = rr.trim();
					$("#com").html('Revenue (max '+r+')');
					$("#user_comission_div").show();
					$("#user_comission").attr('max',r);
					$("#user_comission").attr('onkeyup','if(this.value >'+r+' || this.value < 0) this.value = null;');
				 }
			});  
		}
	}
    </script>
	
	
  <script src="<?php echo base_url(); ?>css_js/js/bootstrap.min.js"></script>

  <!-- bootstrap progress js -->
  <script src="<?php echo base_url(); ?>css_js/js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="<?php echo base_url(); ?>css_js/js/nicescroll/jquery.nicescroll.min.js"></script>
  <!-- icheck -->
  <script src="<?php echo base_url(); ?>css_js/js/icheck/icheck.min.js"></script>
  <!-- tags -->
  <script src="<?php echo base_url(); ?>css_js/js/tags/jquery.tagsinput.min.js"></script>
  <!-- switchery -->
  <script src="<?php echo base_url(); ?>css_js/js/switchery/switchery.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/moment/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/datepicker/daterangepicker.js"></script>
  <!-- richtext editor -->
  <script src="<?php echo base_url(); ?>css_js/js/editor/bootstrap-wysiwyg.js"></script>
  <script src="<?php echo base_url(); ?>css_js/js/editor/external/jquery.hotkeys.js"></script>
  <script src="<?php echo base_url(); ?>css_js/js/editor/external/google-code-prettify/prettify.js"></script>
  <!-- select2 -->
  <script src="<?php echo base_url(); ?>css_js/js/select/select2.full.js"></script>
  <!-- form validation -->
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/parsley/parsley.min.js"></script>
  <!-- textarea resize -->
  <script src="<?php echo base_url(); ?>css_js/js/textarea/autosize.min.js"></script>
  <script>
    autosize($('.resizable_textarea'));
  </script>
  <!-- Autocomplete -->
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/autocomplete/countries.js"></script>
  <script src="<?php echo base_url(); ?>css_js/js/autocomplete/jquery.autocomplete.js"></script>
  <!-- pace -->
  <script src="<?php echo base_url(); ?>css_js/js/pace/pace.min.js"></script>
  <script type="text/javascript">
    $(function() {
      'use strict';
      var countriesArray = $.map(countries, function(value, key) {
        return {
          value: value,
          data: key
        };
      });
      // Initialize autocomplete with custom appendTo:
      $('#autocomplete-custom-append').autocomplete({
        lookup: countriesArray,
        appendTo: '#autocomplete-container'
      });
    });
  </script>
  <script src="<?php echo base_url(); ?>css_js/js/custom.js"></script>


  <!-- select2 -->
  <script>
    $(document).ready(function() {
      $(".select2_single").select2({
        placeholder: "Select a state",
        allowClear: true
      });
      $(".select2_group").select2({});
      $(".select2_multiple").select2({
        maximumSelectionLength: 4,
        placeholder: "With Max Selection limit 4",
        allowClear: true
      });
    });
  </script>
  <!-- /select2 -->
  <!-- input tags -->
  <script>
    function onAddTag(tag) {
      alert("Added a tag: " + tag);
    }

    function onRemoveTag(tag) {
      alert("Removed a tag: " + tag);
    }

    function onChangeTag(input, tag) {
      alert("Changed a tag: " + tag);
    }

    $(function() {
      $('#tags_1').tagsInput({
        width: 'auto'
      });
    });
  </script>
  <!-- /input tags -->
  <!-- form validation -->
  <script type="text/javascript">
    $(document).ready(function() {
      $.listen('parsley:field:validate', function() {
        validateFront();
      });
      $('#demo-form .btn').on('click', function() {
        $('#demo-form').parsley().validate();
        validateFront();
      });
      var validateFront = function() {
        if (true === $('#demo-form').parsley().isValid()) {
          $('.bs-callout-info').removeClass('hidden');
          $('.bs-callout-warning').addClass('hidden');
        } else {
          $('.bs-callout-info').addClass('hidden');
          $('.bs-callout-warning').removeClass('hidden');
        }
      };
    });

    $(document).ready(function() {
      $.listen('parsley:field:validate', function() {
        validateFront();
      });
      $('#demo-form2 .btn').on('click', function() {
        $('#demo-form2').parsley().validate();
        validateFront();
      });
      var validateFront = function() {
        if (true === $('#demo-form2').parsley().isValid()) {
          $('.bs-callout-info').removeClass('hidden');
          $('.bs-callout-warning').addClass('hidden');
        } else {
          $('.bs-callout-info').addClass('hidden');
          $('.bs-callout-warning').removeClass('hidden');
        }
      };
    });
    try {
      hljs.initHighlightingOnLoad();
    } catch (err) {}
  </script>
  <!-- /form validation -->
  <!-- editor -->
  <script>
    $(document).ready(function() {
      $('.xcxc').click(function() {
        $('#descr').val($('#editor').html());
      });
    });

    $(function() {
      function initToolbarBootstrapBindings() {
        var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
            'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
            'Times New Roman', 'Verdana'
          ],
          fontTarget = $('[title=Font]').siblings('.dropdown-menu');
        $.each(fonts, function(idx, fontName) {
          fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
        });
        $('a[title]').tooltip({
          container: 'body'
        });
        $('.dropdown-menu input').click(function() {
            return false;
          })
          .change(function() {
            $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
          })
          .keydown('esc', function() {
            this.value = '';
            $(this).change();
          });

        $('[data-role=magic-overlay]').each(function() {
          var overlay = $(this),
            target = $(overlay.data('target'));
          overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
        });
        if ("onwebkitspeechchange" in document.createElement("input")) {
          var editorOffset = $('#editor').offset();
          $('#voiceBtn').css('position', 'absolute').offset({
            top: editorOffset.top,
            left: editorOffset.left + $('#editor').innerWidth() - 35
          });
        } else {
          $('#voiceBtn').hide();
        }
      };

      function showErrorAlert(reason, detail) {
        var msg = '';
        if (reason === 'unsupported-file-type') {
          msg = "Unsupported format " + detail;
        } else {
          console.log("error uploading file", reason, detail);
        }
        $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
          '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
      };
      initToolbarBootstrapBindings();
      $('#editor').wysiwyg({
        fileUploadError: showErrorAlert
      });
      window.prettyPrint && prettyPrint();
    });
  </script>
  <!-- /editor -->
