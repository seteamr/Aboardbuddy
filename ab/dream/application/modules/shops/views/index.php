<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Shops
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
				<div class="page-title-actions">				
				<div class="d-inline-block dropdown">
					<a href="<?php echo base_url(); ?>shops/add" class="btn-shadow btn btn-info">						
					  +	New
					</a>					
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
		 <div class="row">
			<div class="col-lg-12">
				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Shops</h5>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Shop Name</th>
									<th>Shop Address</th>
									<th>Contact Person</th>
									<th>Contact Number</th>
									<th>Jodi Rate</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($shops)>0){ ?>
									<?php $sno=1; foreach($shops as $shop){ ?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo $shop->shop_name; ?></td>
										<td><?php echo $shop->shop_address; ?></td>
										<td><?php echo $shop->shop_contact_person; ?></td>
										<td><?php echo $shop->shop_contact_number; ?></td>
										<td><?php echo $shop->shop_jodi_rate; ?></td>
										<td>
											<?php if($shop->is_shop_deleted==1){ ?>
											<div class="badge badge-danger">Disabled</div>
											<?php }else{ ?>
											<div class="badge badge-success">Actived</div>
											<?php } ?>
										</td>
										<td>
											<a href="<?php echo base_url('shops/view/'.$shop->shop_master_id); ?>" id="PopoverCustomT-1" class="btn btn-primary btn-sm">Details</a>
												<?php if($this->session->userdata('user_type')=='3'){ ?>
												<a href="<?php echo base_url('shops/edit/'.$shop->shop_master_id); ?>" id="PopoverCustomT-2" class="btn btn-info btn-sm">Edit</a>
												<?php } ?>
											<?php if($shop->is_shop_deleted==1){ ?>
											<a onclick="return confirm('Are you sure you want to active this shop?');" href="<?php echo base_url('shops/shop_status/'.$shop->shop_master_id.'/0'); ?>" id="PopoverCustomT-3" class="btn btn-success btn-sm">Active</a>
											<?php }else{ ?>
											<a onclick="return confirm('Are you sure you want to deactive this shop?');" href="<?php echo base_url('shops/shop_status/'.$shop->shop_master_id.'/1'); ?>" id="PopoverCustomT-3" class="btn btn-danger btn-sm">Deactive</a>
											<?php } ?>
											
										</td>
									</tr>
									<?php $sno++; } ?>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
		 </div>
	</div>
	