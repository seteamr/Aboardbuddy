 <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Setting <small></small></h2>
                 
                  <div class="clearfix"></div>
                </div>
				<?php if($msg!=''){ ?>
				   <div class="alert alert-success " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $msg; ?>
					</div>
				<?php } ?>
                <div class="x_content">
                  <br />
                  <form action="<?php echo base_url(); ?>admin/setting" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Auto System <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
						<select name="cron" id="cron" class="form-control" >
							<option value="1" <?php if($setting->cron=="1"){ echo "selected"; } ?> >5 Block Wise  Divided Points</option>
							<!--<option value="0" <?php if($setting->cron=="0"){ echo "selected"; } ?> >Amount collection 80% </option>							-->
						</select>
						</div>
                    </div>
					
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Auto System Bet %<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">                        
						<input type="number" class="form-control" id="distribute_per" name="distribute_per" placeholder="Auto System Bet %" value="<?php echo $setting->distribute_per; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('distribute_per'); ?>
							</span>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Remove Limit <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
                      <input type="number" class="form-control" id="cancle_per_day_limit" name="cancle_per_day_limit" placeholder="Remove Limit" value="<?php echo $setting->cancle_per_day_limit; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('cancle_per_day_limit'); ?>
							</span>
						</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">History no <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
                      <input type="number" onkeyup="chk();" onblur="chk();" class="form-control" id="returning_history_records" name="returning_history_records" placeholder="History no" value="<?php echo $setting->returning_history_records; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('returning_history_records'); ?>
							</span>
					  </div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Claim After days not allowed <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
                      <input type="number" class="form-control" id="claim_before_day" name="claim_before_day" placeholder="Claim After days not allowed" value="<?php echo $setting->claim_before_day; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('claim_before_day'); ?>
							</span>
						</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Auto call cron if not set <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
                      <input type="number" class="form-control" id="manual_result_after_auto" name="manual_result_after_auto" placeholder="" value="<?php echo $setting->manual_result_after_auto; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('manual_result_after_auto'); ?>
							</span>
						</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Result Display time delay (in Minit) <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
                      <input type="number" class="form-control" id="result_delay_time" name="result_delay_time" placeholder="Result Display time delay" value="<?php echo $setting->result_delay_time; ?>">							
							<span style="color:red;"> 
								<?php echo form_error('result_delay_time'); ?>
							</span>
						</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Welcome Message<span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
                      <textarea class="form-control" id="welcome" name="welcome" placeholder="Welcome Message"><?php echo $welcome->description; ?></textarea>						
							<span style="color:red;"> 
								<?php echo form_error('welcome'); ?>
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
