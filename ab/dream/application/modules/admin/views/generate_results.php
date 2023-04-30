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

.zoom {
  padding: 50px;
  background-color: white;
  transition: transform .2s; /* Animation */
  width: 100px;
  height: 50px;
  margin: 0 auto;
}

.zoom:hover {
  transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>
 <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Declare Results<small></small></h2>
                 
                  <div class="clearfix"></div>
                </div>
				<?php if($msg!=''){ ?>
				   <div class="alert alert-success " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $msg; ?>
					</div>
				<?php } ?>
				<?php if($error_msg!=''){ ?>
				   <div class="alert alert-danger " role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					  </button>
					  <?php echo $error_msg; ?>
					</div>
				<?php } ?>
                <div class="x_content">
                  <br />
                  <form action="<?php echo base_url('admin/generate_results/'.$draw_master_id) ?>" data-parsley-validate class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Series <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
						<select name="series_master_id" class="form-control">
										<option value="">Select Series </option>
										<?php for($i=0;$i<=10;$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($series_master_id==$i && $series_master_id!=""){ echo "selected"; } ?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
							<span style="color:red;"> 
								<?php echo form_error('series_master_id'); ?>
							</span>
						</div>
                    </div>
					
					<div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bajar <span class="required">*</span>
                      </label>
					  <div class="col-md-6 col-sm-6 col-xs-12">  
						<select name="bajar_master_id" class="form-control">
								<option value="" selected >Select Bajar </option>
								<?php for($i=0;$i<=10;$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($bajar_master_id==$i && $bajar_master_id!=""){ echo "selected"; } ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
							<span style="color:red;"><?php echo form_error('bajar_master_id'); ?></span>
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
		  
		  <?php if($series_master_id!=""){ ?>
		   <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
				<div class="table-responsive">
							<form action="<?php echo base_url(); ?>admin/declare_result/<?php echo $draw_master_id; ?>" class="needs-validation" novalidate method="post" >
				
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>Series - Bajar</th>
									<th>Number</th>
									<th>Bid Amount </th>
									<th>Won Amount</th>
									<th>Collection</th>									
									<th>% Won</th>
									<th>No of Bet</th>
									<th>No of Clients</th>
									<th>#</th>
								</tr>
								</thead>
								<tbody>
									<?php

									
									$all_bajar = array(); $all_bajar2 = array(); ?>
									<?php if($records){ ?>
									<?php foreach($records as $r){ ?>									
									<?php									
									if(!in_array($r->bajar_master_id,$all_bajar)){ 
									$all_bajar[] = $r->bajar_master_id;
									
									$fsql2 ="Select bid_akada_number from all_bid_akada where bid_akada_number NOT IN 
												( Select bid_akada_number from draw_transaction_details where
												result_date='".$r->result_date."' AND draw_master_id='".$draw_master_id."'
												AND bajar_master_id='".$r->bajar_master_id."'AND series_master_id='".$r->series_master_id."'
												) ORDER BY rand() LIMIT 2 ";
									$qqq2 = $this->db->query($fsql2);
									$fresults2 = $qqq2->result();
									$bid_akada_number1 = $fresults2[0]->bid_akada_number;
									$bid_akada_number2 = $fresults2[1]->bid_akada_number;
									
									?>
									<tr> <td colspan="9" style="background:#3f6ad8;"></td></tr>
										<?php if($bid_akada_number1){ ?>
										<tr>
											<td><?php echo $r->series_master_id.$r->bajar_master_id; ?></td>
											<td><?php echo $bid_akada_number1; ?></td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0%</td>
											<td>0</td>
											<td>0</td>
											<td>
											<input type="Radio" checked value="<?php echo $bid_akada_number1; ?>" name="<?php echo $r->series_master_id.'_'.$r->bajar_master_id; ?>">
											</td>
										</tr>
										<?php } ?>
										
										<?php if($bid_akada_number2){ ?>
										<tr>
											<td><?php echo $r->series_master_id.$r->bajar_master_id; ?></td>
											<td><?php echo $bid_akada_number2; ?></td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0%</td>
											<td>0</td>
											<td>0</td>
											<td>
											<input type="Radio" checked value="<?php echo $bid_akada_number2; ?>" name="<?php echo $r->series_master_id.'_'.$r->bajar_master_id; ?>">
											</td>
										</tr>
										<?php } ?>
									
									<?php }?>
									<tr>
										<td><?php echo $r->series_master_id.$r->bajar_master_id; ?></td>
										<td><?php echo $r->bid_akada_number; ?></td>
										<td><?php echo $r->bid_points; ?></td>
										<td><?php echo $r->winning_points; ?></td>
										<td><?php echo $r->total_bid; ?></td>
										<td><?php echo $r->winning_percent; ?>%</td>
										<td><?php echo $r->number_of_bids; ?></td>
										<td><?php echo $r->number_of_users; ?></td>
										<td>
										<input type="Radio" value="<?php echo $r->bid_akada_number; ?>" name="<?php echo $r->series_master_id.'_'.$r->bajar_master_id; ?>">
										</td>
									</tr>
									
									
									<?php } ?>
									<?php }else{ ?>
										
										<?php 
										if(!$bajar_master_id){
										for($i=0;$i<10;$i++){ $result_date=date('Y-m-d'); ?>
										
										<?php
											$fsql2 ="Select bid_akada_number from all_bid_akada where bid_akada_number NOT IN 
													( Select bid_akada_number from draw_transaction_details where
													result_date='".$result_date."' AND draw_master_id='".$draw_master_id."'
													AND bajar_master_id='".$i."'AND series_master_id='".$series_master_id."'
													) ORDER BY rand() LIMIT 2 ";
										$qqq2 = $this->db->query($fsql2);
										$fresults2 = $qqq2->result();
										$bid_akada_number1 = $fresults2[0]->bid_akada_number;
										$bid_akada_number2 = $fresults2[1]->bid_akada_number;
										
										?>
											<?php if($bid_akada_number1){ ?>
											<tr>
												<td><?php echo $series_master_id.$i; ?></td>
												<td><?php echo $bid_akada_number1; ?></td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0%</td>
												<td>0</td>
												<td>0</td>
												<td>
												<input type="Radio" <?php echo "checked"; ?> value="<?php echo $bid_akada_number1; ?>" name="<?php echo $series_master_id.'_'.$i; ?>">
												</td>
											</tr>
											<?php } ?>
											
											<?php if($bid_akada_number2){ ?>
											<tr>
												<td><?php echo $series_master_id.$i; ?></td>
												<td><?php echo $bid_akada_number2; ?></td>
												<td>0</td>
												<td>0</td>
												<td>0</td>
												<td>0%</td>
												<td>0</td>
												<td>0</td>
												<td>
												<input type="Radio" value="<?php echo $bid_akada_number2; ?>" name="<?php echo $series_master_id.'_'.$i; ?>">
												</td>
											</tr>
											<?php } ?>
											
											<tr> <td colspan="9" style="background:#3f6ad8;"></td></tr>
											<?php } ?>
											
											<?php }else{ ?>
													<?php
													$i = $bajar_master_id;
													$result_date=date('Y-m-d');
														$fsql2 ="Select bid_akada_number from all_bid_akada where bid_akada_number NOT IN 
																( Select bid_akada_number from draw_transaction_details where
																result_date='".$result_date."' AND draw_master_id='".$draw_master_id."'
																AND bajar_master_id='".$i."'AND series_master_id='".$series_master_id."'
																) ORDER BY rand() LIMIT 2 ";
													$qqq2 = $this->db->query($fsql2);
													$fresults2 = $qqq2->result();
													$bid_akada_number1 = $fresults2[0]->bid_akada_number;
													$bid_akada_number2 = $fresults2[1]->bid_akada_number;
													
													?>
														<?php if($bid_akada_number1){ ?>
														<tr>
															<td><?php echo $series_master_id.$i; ?></td>
															<td><?php echo $bid_akada_number1; ?></td>
															<td>0</td>
															<td>0</td>
															<td>0</td>
															<td>0%</td>
															<td>0</td>
															<td>0</td>
															<td>
															<input type="Radio" <?php echo "checked"; ?> value="<?php echo $bid_akada_number1; ?>" name="<?php echo $series_master_id.'_'.$i; ?>">
															</td>
														</tr>
														<?php } ?>
														
														<?php if($bid_akada_number2){ ?>
														<tr>
															<td><?php echo $series_master_id.$i; ?></td>
															<td><?php echo $bid_akada_number2; ?></td>
															<td>0</td>
															<td>0</td>
															<td>0</td>
															<td>0%</td>
															<td>0</td>
															<td>0</td>
															<td>
															<input type="Radio" value="<?php echo $bid_akada_number2; ?>" name="<?php echo $series_master_id.'_'.$i; ?>">
															</td>
														</tr>
														<?php } ?>
											<?php } ?>
											
										
										
									<?php  } ?>
									
								</tbody>
							</table>
							</br> </br>
							<button class="btn btn-primary" type="submit">Declare Result</button>
							</form>
			 </div>
			 
			 </div>
			 </div>
          </div>
		  <?php } ?>
          
    </div>
  </div>
  
<script type="text/javascript">
setInterval(function() {
	console.log('ss');
	location.reload();
 }, 10000);
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
