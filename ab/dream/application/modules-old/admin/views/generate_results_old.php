<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Add Draw for Manual Result
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
			</div>
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
		
			<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title"></h5>
						<div>
							<form class="form-inline" action="<?php echo base_url('admin/generate_results/'.$draw_master_id) ?>" method="post">
								
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Series</label>
									<select name="series_master_id" class="form-control">
										<option value="">Select Series </option>
										<?php for($i=0;$i<=10;$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($series_master_id==$i && $series_master_id!=""){ echo "selected"; } ?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
								
								</div>
								<div class="mb-3 mr-sm-3 mb-sm-0 position-relative form-group">
									<label for="examplePassword22" class="mr-sm-2">Bajar</label>
									<select name="bajar_master_id" class="form-control">
										<option value="" selected >Select Bajar </option>
										<?php for($i=0;$i<=10;$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($bajar_master_id==$i && $bajar_master_id!=""){ echo "selected"; } ?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
									<span style="color:red;"><?php echo form_error('bajar_master_id'); ?></span>
								</div>
								<div class="mt-2 col-md-12 mb-3">
									<span style="color:red;"><?php echo form_error('series_master_id'); ?></span>
								</div>
								<div class="mt-2 col-md-12 mb-3">
									<div class="form-row">
										<input type="submit" name="search" value="Get Records" class=" mr-2 btn-transition btn btn-outline-primary">
									</div>
								</div>
							</form>
						</div>
					</div> 
				</div>
		<?php if($series_master_id!=""){ ?>
		<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Manual Draw</h5>
						<div class="table-responsive">
					
					<form action="<?php echo base_url(); ?>admin/declare_result/<?php echo $draw_master_id; ?>" class="needs-validation" novalidate method="post" >
				
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>Series - Bajar</th>
									<th>Number</th>
									<th>Bid Amount </th>
									<th>Winning Amount</th>
									<th>Amount Collected</th>									
									<th>% Win</th>
									<th>No of Bids</th>
									<th>No of Users</th>
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
			<?php } ?>
	</div>
	
	<style>
	input[type="Radio"] {
		width: 25px;
		height: 25px;
	}
</style>
	
	