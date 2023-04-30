<style>
.newc{
	color:#fff;
}
.Bet {
    background: #2c4a80d9;
}
.Won {
    background: #33d41f;
}
.Remove {
    background: #e43811;
}
.Final-Coins {
    background: #e21dca;
}
.Total-Revenue {
    background: #f1f107;
}
.Agent-Revenue {
    background: #edf108b8;
}
.Client-Revenue {
    background: #f1f10794;
}
.Net-Coins {
    background: #1319efc7;
}
.Agent-Coins {
    background: #1319ef8f;
}
.Client-Coins {
    background: #1319ef63;
}
</style>

<!-- page content -->
      <div class="right_col" role="main">

        <br />
        <div class="">

          <div class="row top_tiles">
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Bet">
                <div class="count"><?php echo $sales; ?></br>Bet</div>

                <h3></h3>
              </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Won">
                <div class="count"><?php echo $winning; ?></br>Won</div>

                <h3></h3>
              </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Remove">
                <div class="count"><?php echo $cancle; ?></br>Remove</div>

                <h3></h3>
              </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Final-Coins">
                <div class="count"><?php echo $sales - $winning; ?></br>Final Coins</div>

                <h3></h3>
              </div>
            </div>
			<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Total-Revenue">
                <div class="count"><?php echo $total_commision_d + $total_commision_r; ?></br>Total Revenue</div>

                <h3></h3>
              </div>
            </div>
			<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Agent-Revenue">
                <div class="count"><?php echo $total_commision_d; ?></br>Own Revenue</div>

                <h3></h3>
              </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Client-Revenue">
                <div class="count"><?php echo $total_commision_r; ?></br>Client Revenue</div>

                <h3></h3>
              </div>
            </div>
			 <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Net-Coins">
                <div class="count"><?php echo $sales - $winning - $total_commision_d - $total_commision_r - $bonus; ?></br>Net Coins</div>

                <h3></h3>
              </div>
            </div>
			
			
			<div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Agent-Coins">
                <div class="count"><?php echo $current_balances_d; ?></br>Own Balance</div>

                <h3></h3>
              </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="tile-stats newc Client-Coins">
                <div class="count"><?php echo $current_balances_r; ?></br>Client Balance</div>

                <h3></h3>
              </div>
            </div>
          
			
          </div>
		  
		  <?php 
							
		$green_array[] = 0;
		$yellow_array[] = 0;
		$grey_array[] =0;
		$reccordss = $this->common_model->getAllwhere_result_new2('result_master',array('result_date'=>date('Y-m-d')));
		
		if($reccordss)
		{
			$draw_master_id = $reccordss[0]->draw_master_id;
			for($i=1;$i<=3;$i++)
			{
				if($draw_master_id > 0)
				{
					$green_array[] = $draw_master_id;
					$draw_master_id = $draw_master_id-1;
				}
			}
			
			$draw_master_id = $reccordss[0]->draw_master_id;
			for($i=1;$i<=12;$i++)
			{
				if($draw_master_id > 0)
				{
					$yellow_array[] = $draw_master_id;
					$draw_master_id = $draw_master_id-1;
				}
			}
			
			$draw_master_id = $reccordss[0]->draw_master_id;
			for($i=1;$i<=48;$i++)
			{
				if($draw_master_id > 0)
				{
					$grey_array[] = $draw_master_id;
					$draw_master_id = $draw_master_id-1;
				}
			}
		}
		?>
		
		<div class="row">
            <div class="col-md-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Clients<small></small></h2>
                  <div class="filter">
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
						<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">Client</th>
							<th class="text-center">Coins</th>
							<th class="text-center">Bet</th>
							<th class="text-center">Won</th>
							<th class="text-center">Remove</th>
							<th class="text-center">Revenue</th>
							<th class="text-center">Net Payout</th>
						</tr>
						</thead>
						<tbody>
						<?php $sno=1; foreach($retailers as $retailer){ ?>

						<?php 
						$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$retailer->user_master_id));
						$my_downs =array();
						if(count($my_reports)>0)
						{
							foreach($my_reports as $ids)
							{
								$my_downs[] = $ids->user_master_id;
							}
						}
						$my_downs[] = $retailer->user_master_id;
						$transaction_detail_total_sale=$this->common_model->transaction_detail_total_sale_new($my_downs);
						$transaction_detail_total_winning=$this->common_model->winning_count_data_for_admin($my_downs);
						$transaction_detail_total_cancle=$this->common_model->cancle_count_data_for_admin($my_downs);
						$commision=$this->common_model->total_comission_data_new($my_downs);
						$bonus=$this->common_model->total_bonus_data_new($my_downs);
						$balance=$this->common_model->getcurrent_balance($retailer->user_master_id);							
						
						$chkgreen = $this->common_model->checkdraw_play($retailer->user_master_id,$green_array,date('Y-m-d'));
						$chkyellow = $this->common_model->checkdraw_play($retailer->user_master_id,$yellow_array,date('Y-m-d'));
						$chkgrey = $this->common_model->checkdraw_play($retailer->user_master_id,$grey_array,date('Y-m-d'));
						if($chkgreen)
						{
							$color = 'style="color:green;"';
						}
						else if($chkyellow)
						{
							$color = 'style="color:darkorange;"';
						}
						else if($chkgrey)
						{
							$color = 'style="color:grey;"';
						}
						else
						{
							$color = 'style="color:red;"';
						}
						?>
						<tr id="<?php echo $retailer->user_master_id; ?>" <?php echo $color; ?> >
							<td class="text-center text-muted"><?php echo $sno; ?></td>							
							<?php if($transaction_detail_total_sale[0]->total!="")
							{
								$total_sale = $transaction_detail_total_sale[0]->total;
							}else{
								$total_sale =0;
							} 
							if($transaction_detail_total_winning[0]->total!="")
							{
								$total_winning = $transaction_detail_total_winning[0]->total;
							}else{
								$total_winning =0;
							}
							if($transaction_detail_total_cancle[0]->total!="")
							{
								$total_cancle = $transaction_detail_total_cancle[0]->total;
							}else{
								$total_cancle =0;
							}
							if($commision[0]->total!="")
							{
								$total_commision = $commision[0]->total;
							}else{
								$total_commision =0;
							}
							if($bonus[0]->total!="")
							{
								$total_bonus = $bonus[0]->total;
							}else{
								$total_bonus =0;
							}
							?>
							<td class="text-center"><?php echo $retailer->user_name; ?> (<?php echo $retailer->name; ?>) </td>
							<td class="text-center"><div class=""><?php echo $balance; ?></div></td>
							<td class="text-center"><div class=""><?php echo $total_sale; ?></div></td>
							<td class="text-center"><div class=""><?php echo $total_winning; ?></div></td>
							<td class="text-center"><div class=""><?php echo $total_cancle; ?></div></td>
							<td class="text-center"><div class=""><?php echo $total_commision; ?></div></td>							
							<td class="text-center"><div class=""><?php echo $total_sale - $total_winning - $commision - $total_bonus ; ?></div></td>
						</tr>
						<?php $sno++; } ?>
						
						</tbody>
					</table>
                  </div>

                </div>
              </div>
            </div>
          </div>
		  
		  
		  

		</div>

      

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



 <script src="<?php echo base_url(); ?>css_js/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>css_js/js/nicescroll/jquery.nicescroll.min.js"></script>

  <!-- bootstrap progress js -->
  <script src="<?php echo base_url(); ?>css_js/js/progressbar/bootstrap-progressbar.min.js"></script>
  <!-- icheck -->
  <script src="<?php echo base_url(); ?>css_js/js/icheck/icheck.min.js"></script>
  <!-- daterangepicker -->
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/moment/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/datepicker/daterangepicker.js"></script>
  <!-- chart js -->
  <script src="<?php echo base_url(); ?>css_js/js/chartjs/chart.min.js"></script>
  <!-- sparkline -->
  <script src="<?php echo base_url(); ?>css_js/js/sparkline/jquery.sparkline.min.js"></script>

  <script src="<?php echo base_url(); ?>css_js/js/custom.js"></script>

  <!-- flot js -->
  <!--[if lte IE 8]><script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/excanvas.min.js"></script><![endif]-->
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.pie.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.orderBars.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/date.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.spline.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.stack.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/curvedLines.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>css_js/js/flot/jquery.flot.resize.js"></script>
  <!-- pace -->
  <script src="<?php echo base_url(); ?>css_js/js/pace/pace.min.js"></script>
  <!-- flot -->
  <script type="text/javascript">
    //define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
    var chartColours = ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'];

    //generate random number for charts
    randNum = function() {
      return (Math.floor(Math.random() * (1 + 40 - 20))) + 20;
    }

    $(function() {
      var d1 = [];
      //var d2 = [];

      //here we generate data for chart
      for (var i = 0; i < 30; i++) {
        d1.push([new Date(Date.today().add(i).days()).getTime(), randNum() + i + i + 10]);
        //    d2.push([new Date(Date.today().add(i).days()).getTime(), randNum()]);
      }

      var chartMinDate = d1[0][0]; //first day
      var chartMaxDate = d1[20][0]; //last day

      var tickSize = [1, "day"];
      var tformat = "%d/%m/%y";

      //graph options
      var options = {
        grid: {
          show: true,
          aboveData: true,
          color: "#3f3f3f",
          labelMargin: 10,
          axisMargin: 0,
          borderWidth: 0,
          borderColor: null,
          minBorderMargin: 5,
          clickable: true,
          hoverable: true,
          autoHighlight: true,
          mouseActiveRadius: 100
        },
        series: {
          lines: {
            show: true,
            fill: true,
            lineWidth: 2,
            steps: false
          },
          points: {
            show: true,
            radius: 4.5,
            symbol: "circle",
            lineWidth: 3.0
          }
        },
        legend: {
          position: "ne",
          margin: [0, -25],
          noColumns: 0,
          labelBoxBorderColor: null,
          labelFormatter: function(label, series) {
            // just add some space to labes
            return label + '&nbsp;&nbsp;';
          },
          width: 40,
          height: 1
        },
        colors: chartColours,
        shadowSize: 0,
        tooltip: true, //activate tooltip
        tooltipOpts: {
          content: "%s: %y.0",
          xDateFormat: "%d/%m",
          shifts: {
            x: -30,
            y: -50
          },
          defaultTheme: false
        },
        yaxis: {
          min: 0
        },
        xaxis: {
          mode: "time",
          minTickSize: tickSize,
          timeformat: tformat,
          min: chartMinDate,
          max: chartMaxDate
        }
      };
      var plot = $.plot($("#placeholder33x"), [{
        label: "Bet",
        data: d1,
        lines: {
          fillColor: "rgba(150, 202, 89, 0.12)"
        }, //#96CA59 rgba(150, 202, 89, 0.42)
        points: {
          fillColor: "#fff"
        }
      }], options);
    });
  </script>
  <!-- /flot -->
  <!--  -->
  <script>
    $('document').ready(function() {
      $(".sparkline_one").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 4, 5, 6, 3, 5, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
        type: 'bar',
        height: '125',
        barWidth: 13,
        colorMap: {
          '7': '#a1a1a1'
        },
        barSpacing: 2,
        barColor: '#26B99A'
      });

      $(".sparkline11").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 6, 2, 4, 3, 4, 5, 4, 5, 4, 3], {
        type: 'bar',
        height: '40',
        barWidth: 8,
        colorMap: {
          '7': '#a1a1a1'
        },
        barSpacing: 2,
        barColor: '#26B99A'
      });

      $(".sparkline22").sparkline([2, 4, 3, 4, 7, 5, 4, 3, 5, 6, 2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 6], {
        type: 'line',
        height: '40',
        width: '200',
        lineColor: '#26B99A',
        fillColor: '#ffffff',
        lineWidth: 3,
        spotColor: '#34495E',
        minSpotColor: '#34495E'
      });

      var doughnutData = [{
        value: 30,
        color: "#455C73"
      }, {
        value: 30,
        color: "#9B59B6"
      }, {
        value: 60,
        color: "#BDC3C7"
      }, {
        value: 100,
        color: "#26B99A"
      }, {
        value: 120,
        color: "#3498DB"
      }];

      Chart.defaults.global.legend = {
        enabled: false
      };

      var canvasDoughnut = new Chart(document.getElementById("canvas1i"), {
        type: 'doughnut',
        showTooltips: false,
        tooltipFillColor: "rgba(51, 51, 51, 0.55)",
        data: {
          labels: [
            "Symbian",
            "Blackberry",
            "Other",
            "Android",
            "IOS"
          ],
          datasets: [{
            data: [15, 20, 30, 10, 30],
            backgroundColor: [
              "#BDC3C7",
              "#9B59B6",
              "#455C73",
              "#26B99A",
              "#3498DB"
            ],
            hoverBackgroundColor: [
              "#CFD4D8",
              "#B370CF",
              "#34495E",
              "#36CAAB",
              "#49A9EA"
            ]

          }]
        }
      });

      var canvasDoughnut = new Chart(document.getElementById("canvas1i2"), {
        type: 'doughnut',
        showTooltips: false,
        tooltipFillColor: "rgba(51, 51, 51, 0.55)",
        data: {
          labels: [
            "Symbian",
            "Blackberry",
            "Other",
            "Android",
            "IOS"
          ],
          datasets: [{
            data: [15, 20, 30, 10, 30],
            backgroundColor: [
              "#BDC3C7",
              "#9B59B6",
              "#455C73",
              "#26B99A",
              "#3498DB"
            ],
            hoverBackgroundColor: [
              "#CFD4D8",
              "#B370CF",
              "#34495E",
              "#36CAAB",
              "#49A9EA"
            ]

          }]
        }
      });

      var canvasDoughnut = new Chart(document.getElementById("canvas1i3"), {
        type: 'doughnut',
        showTooltips: false,
        tooltipFillColor: "rgba(51, 51, 51, 0.55)",
        data: {
          labels: [
            "Symbian",
            "Blackberry",
            "Other",
            "Android",
            "IOS"
          ],
          datasets: [{
            data: [15, 20, 30, 10, 30],
            backgroundColor: [
              "#BDC3C7",
              "#9B59B6",
              "#455C73",
              "#26B99A",
              "#3498DB"
            ],
            hoverBackgroundColor: [
              "#CFD4D8",
              "#B370CF",
              "#34495E",
              "#36CAAB",
              "#49A9EA"
            ]

          }]
        }
      });
    });
  </script>
  <!-- -->
  <!-- datepicker -->
  <script type="text/javascript">
    $(document).ready(function() {

      var cb = function(start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
      }

      var optionSet1 = {
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        minDate: '01/01/2012',
        maxDate: '12/31/2015',
        dateLimit: {
          days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
          applyLabel: 'Submit',
          cancelLabel: 'Clear',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom',
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
          firstDay: 1
        }
      };
      $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
      $('#reportrange').daterangepicker(optionSet1, cb);
      $('#reportrange').on('show.daterangepicker', function() {
        console.log("show event fired");
      });
      $('#reportrange').on('hide.daterangepicker', function() {
        console.log("hide event fired");
      });
      $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
      });
      $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
        console.log("cancel event fired");
      });
      $('#options1').click(function() {
        $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
      });
      $('#options2').click(function() {
        $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
      });
      $('#destroy').click(function() {
        $('#reportrange').data('daterangepicker').remove();
      });
    });
  </script>