 <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Adjust Coin <small></small></h2>
                 
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
				<?php if($msg!=''){ ?>
				   <div class="alert alert-success " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $msg; ?>
					</div>
				<?php } ?>
                  <br />
				  
					<?php if($this->session->userdata('point_password_w')!=""){ ?>
					<h5 class="card-title">Please Select Adjust coins Details</h5>
					<?php } ?>
				
                  <form action="<?php echo base_url(); ?>points/withdraw" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
					
					<?php if($this->session->userdata('point_password_w')==""){ ?>
					
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Coin Password <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="password" name="point_password" class="form-control" value="<?php echo set_value('point_password'); ?>" required>	
							<span style="color:red;"> 
								<?php echo form_error('point_password'); ?>
							</span>
					  </div>
                    </div>
					<button class="btn btn-primary" type="submit">Submit</button>
					<?php }else{ ?>
					
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Current Coins <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input disabled type="text" class="form-control" value="<?php echo $balance; ?>" required>	
					  </div>
                    </div>
					
					<?php
						
					$user_master_id 	= $this->session->userdata('user_master_id');
					$mydata 			= $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
					
					$users ="";
					//if you admin
					if($this->session->userdata('user_type')=='3'){
						$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'2','is_user_deleted'=>'0'));
					}
					
					//if you distributer
					if($this->session->userdata('user_type')=='2'){							
						$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'1','is_user_deleted'=>'0'));							
					}
					
					//if you Retails
					if($this->session->userdata('user_type')=='1'){							
						$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'0','is_user_deleted'=>'0'));							
					}
					
					?>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Head <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12"> 
						<select name="to_user_master_id" onchange="get_points();" id="to_user_master_id" class="form-control" required>
							<option value="">Select User</option>
							<?php if($users!=""){ ?>
							<?php foreach($users as $us){ ?>
							<option  value="<?php echo $us->user_master_id; ?>"  <?php if(set_value('to_user_master_id')==$us->user_master_id){ echo "selected"; } ?> ><?php echo $us->user_name; ?> (<?php echo $us->name; ?>)</option>
							<?php } ?>								
							<?php } ?>								
						</select>
						<span style="color:red;"> 
							<?php echo form_error('to_user_master_id'); ?>
						</span>
					  </div>
                    </div>
					
					<?php 
					
					$ubalance = $this->common_model->getcurrent_balance(set_value('to_user_master_id'));
					?>	
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Client Current Coins<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
						<input disabled type="text" id="user_current_points" class="form-control" value="<?php echo $ubalance; ?>" required>	
						
					  </div>
                    </div>
					
					
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Withdraw Coins<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="number" max="<?php echo $ubalance; ?>" min="0" onkeyup="if(this.value > <?php echo $ubalance; ?> || this.value <= 0) this.value = null;" id="withdraw_points" name="withdraw_points" class="form-control" value="<?php echo set_value('withdraw_points'); ?>" required>	
							<span style="color:red;"> 
								<?php echo form_error('withdraw_points'); ?>
							</span>
					  </div>
                    </div>
					<button class="btn btn-primary" type="submit">Submit</button>
					<?php } ?>
					
                  </form>
                </div>
              </div>
            </div>
          </div>

         

    </div>
  </div>
  
  	<script type="text/javascript">

	function get_points(){
		var to_user_master_id = $("#to_user_master_id").val();			
		var params = {to_user_master_id: to_user_master_id};
		$.ajax({
			url: '<?php echo base_url();?>points/get_balance',
			type: 'post',
			data: params,
			success: function (r)
			 { 
				var rr = r.trim();
				$("#user_current_points").val(rr);
				$("#withdraw_points").attr('max',rr);
				$("#withdraw_points").attr('onkeyup','if(this.value > '+rr+' || this.value <= 0) this.value = null;');
			 }
		});  
		
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
