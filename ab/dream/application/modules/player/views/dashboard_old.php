<style>
	a.canvasjs-chart-credit {
    display: none;
}

</style>

	<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-car icon-gradient bg-mean-fruit">
						</i>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-xl-6">
					<a href="<?php echo base_url('App/KuberaAndroidApp.apk'); ?>" class="btn btn-primary" >Download Application for Mobile </a>
				</div>
				<div class="col-md-6 col-xl-6">
					<a href="<?php echo base_url('App/KuberaWindowSetup.msi'); ?>" class="btn btn-primary" >Download Application for Window </a>
				</div>
			</div>
		</div>            
		<div class="row">
			<div class="col-md-6 col-xl-4">
				<div class="card mb-3 widget-content bg-midnight-bloom">
					<div class="widget-content-wrapper text-white">
						<div class="widget-content-left">
							<div class="widget-heading">Winnings</div>
							<div class="widget-subheading"></div>
						</div>
						<div class="widget-content-right">
							<div class="widget-numbers text-white"><span><?php echo $winning; ?></span></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-4">
				<div class="card mb-3 widget-content bg-arielle-smile">
					<div class="widget-content-wrapper text-white">
						<div class="widget-content-left">
							<div class="widget-heading">Sales</div>
							<div class="widget-subheading"></div>
						</div>
						<div class="widget-content-right">
							<div class="widget-numbers text-white"><span><?php echo $sales; ?></span></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-4">
				<div class="card mb-3 widget-content bg-grow-early">
					<div class="widget-content-wrapper text-white">
						<div class="widget-content-left">
							<div class="widget-heading">Cancel</div>
							<div class="widget-subheading"></div>
						</div>
						<div class="widget-content-right">
							<div class="widget-numbers text-white"><span><?php echo $cancle; ?></span></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-4">
				<div class="card mb-3 widget-content bg-premium-dark">
					<div class="widget-content-wrapper text-white">
						<div class="widget-content-left">
							<div class="widget-heading">End Points</div>
							<div class="widget-subheading"></div>
						</div>
						<div class="widget-content-right">
							<div class="widget-numbers text-white"><span><?php echo $sales - $winning; ?></span></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-4">
				<div class="card mb-3 widget-content bg-happy-green">
					<div class="widget-content-wrapper text-white">
						<div class="widget-content-left">
							<div class="widget-heading">My Balance</div>
							<div class="widget-subheading"></div>
						</div>
						<div class="widget-content-right">
							<div class="widget-numbers text-white"><span><?php echo $current_balances_p; ?></span></div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="mb-3 card">
					<div class="card-header-tab card-header-tab-animation card-header">
						<div class="card-header-title">
							<i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
							Monthly Transaction Report
						</div>
					</div>
					<div class="card-body">
						<div class="tab-content">
							<div class="tab-pane fade show active" id="tabs-eg-77">
								<div class="card mb-3 widget-chart widget-chart2 text-left w-100">
									<div class="widget-chat-wrapper-outer">
										<div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 m-0">
											<div id="chartContainer" style="height: 370px; width: 100%;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-lg-12">
				<div class="mb-3 card">
					<div class="card-header-tab card-header">
						<div class="card-header-title">
							<i class="header-icon lnr-rocket icon-gradient bg-tempting-azure"> </i>
							Day Reports
						</div>
						<div class="btn-actions-pane-right">
							<div class="nav">
							</div>
						</div>
					</div>
					<div class="tab-content">
						<div class="tab-pane fade active show" id="tab-eg-55">
							<div class="widget-chart p-3">
								<div style="height: 350px">
									<!-- <canvas id="line-chart"></canvas> -->
									<div id="lineChart" style="height: 370px; width: 100%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="main-card mb-3 card">
					<div class="card-header">Players
						<div class="btn-actions-pane-right">
						</div>
					</div>
					<div class="table-responsive">
						<table class="align-middle mb-0 table table-borderless table-striped table-hover">
							<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Name</th>
								<th class="text-center">Username</th>
								<th class="text-center">Sales</th>
								<th class="text-center">Winnings</th>
								<th class="text-center">Cancel</th>
								<th class="text-center">Commision</th>
								<th class="text-center">Bonus</th>
								<th class="text-center">Balance</th>
							</tr>
							</thead>
							<tbody>
							<?php $sno=1; foreach($retailers as $retailer){ ?>

							<?php 
							$my_downs =array();
							
							$my_downs[] = $retailer->user_master_id;
							$transaction_detail_total_sale=$this->common_model->transaction_detail_total_sale($my_downs);
							$transaction_detail_total_winning=$this->common_model->transaction_detail_total_winning($my_downs);
							$transaction_detail_total_cancle=$this->common_model->transaction_detail_total_cancle($my_downs);
							$commision=$this->common_model->total_comission_data($my_downs);
							$bonus=$this->common_model->total_bonus_data($my_downs);
							$balance=$this->common_model->getcurrent_balance($retailer->user_master_id);
							?>
							<tr id="<?php echo $retailer->user_master_id; ?>">
								<td class="text-center text-muted"><?php echo $sno; ?></td>
								<td>
									<div class="widget-content p-0">
										<div class="widget-content-wrapper">
											<div class="widget-content-left mr-3">
												<div class="widget-content-left">
													<?php if($retailer->profile_image!=''){ ?>
													<img width="40" class="rounded-circle" src="<?php echo base_url('uploads/profile_image/'.$retailer->profile_image); ?>" alt="">
												<?php }else{ ?>
													<img width="40" class="rounded-circle" src="<?php echo base_url('uploads/profile_image/empty_profile_image.png'); ?>" alt="">
												<?php } ?>
												</div>
											</div>
											<div class="widget-content-left flex2">
												<div class="widget-heading"><?php echo $retailer->name; ?></div>
											</div>
										</div>
									</div>
								</td>
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
								<td class="text-center"><?php echo $retailer->user_name; ?></td>
								<td class="text-center"><div class="badge badge-warning"><?php echo $total_sale; ?></div></td>
								<td class="text-center"><div class="badge badge-success"><?php echo $total_winning; ?></div></td>
								<td class="text-center"><div class="badge badge-danger"><?php echo $total_cancle; ?></div></td>
								<td class="text-center"><div class="badge badge-info"><?php echo $total_commision; ?></div></td>
								<td class="text-center"><div class="badge badge-primary"><?php echo $total_bonus; ?></div></td>
								<td class="text-center"><div class="badge badge-green"><?php echo $balance; ?></div></td>								
							</tr>
							<?php $sno++; } ?>
							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
               
<script>
	const dataSource = {
  chart: {
    caption: "",
    subcaption: "",
    yaxisname: "Count of Reports",
    numvisibleplot: "18",
    labeldisplay: "auto",
    theme: "fusion"
  },
  categories: [
    {
      category: <?php echo json_encode($category, JSON_NUMERIC_CHECK); ?>
    }
  ],
  dataset: [
    {
      color: "#f8bd19",
      seriesname: "Sales",
      data: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
    },
    {
   	   color: "#004c00",
      seriesname: "Winning",
      data: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
    },
    {
    	color: "#cc0000",
      seriesname: "Cancel",
      data: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
    }
  ]
};

FusionCharts.ready(function() {
  var myChart = new FusionCharts({
    type: "scrollcolumn2d",
    renderAt: "chartContainer",
    width: "100%",
    height: "100%",
    dataFormat: "json",
    dataSource
  }).render();
});
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>


 
 var chart = new CanvasJS.Chart("lineChart",
    {
      title:{
      text: "Count of Day wise Report"  
      },
      data: [
      {        
        type: "line",
        color: "#004c00",
        seriesname: "Winning",
        dataPoints: <?php echo json_encode($dataset2, JSON_NUMERIC_CHECK); ?>
      },
        {        
        type: "line",
        color: "#f8bd19",
        seriesname: "Sales",
        dataPoints: <?php echo json_encode($dataset1, JSON_NUMERIC_CHECK); ?>
      },
        {        
        type: "line",
        color: "#cc0000",
        seriesname: "Cancel",
        dataPoints: <?php echo json_encode($dataset3, JSON_NUMERIC_CHECK); ?>
      }
      ]
    });

    chart.render();
 

</script>
				   