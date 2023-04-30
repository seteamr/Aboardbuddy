<style>
ul.pagination.pagination-sm li span {
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #fff;
    background-color: #007bff;
    border: 1px solid #007bff;
}
.pagination li.active a {
    z-index: 1;
    color: #3f6ad8;
    background-color: #fff;
	border: 1px solid #dee2e6;
}
</style>
<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
						</i>
					</div>
					<div>Users
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
				<div class="page-title-actions">				
				<?php if($this->session->userdata('user_type')=='2' OR $this->session->userdata('user_type')=='3'){ ?>
				<div class="d-inline-block dropdown">
					<a href="<?php echo base_url(); ?>users/add" class="btn-shadow btn btn-info">						
					  +	New
					</a>					
				</div>
				<?php } ?>
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
                                    <div class="card-body"><h5 class="card-title">Search By</h5>
                                        <div>
                                            <form class="form-inline" action="<?php echo base_url('users/index') ?>" method="post">
                                               
                                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
													<label for="examplePassword22" class="mr-sm-2">UserName</label>
													<input name="username" id="from_to_date" placeholder="Enter Username" type="text" value="<?php echo $username; ?>" class="form-control">
												</div>                                               
                                               <input type="submit" name="search" value="Search" class="btn btn-primary">                                               
                                            </form>
                                        </div>
                                    </div>
                                </div>
				<div class="main-card mb-3 card">
					<div class="card-body"><h5 class="card-title">Users</h5>
						<div class="table-responsive">
							<table class="mb-0 table">
								<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>User Name</th>
									<th>User Type</th>
									<th>Shop</th>
									<th>Reporting</th>
									<th>Commision</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($users)>0){ ?>
									<?php foreach($users as $user){ ?>
									<?php 
									if($user->user_type==0)
									{
										$u_type ="Player";
									}
									else if($user->user_type==1)
									{
										$u_type ="Retailer";
									}
									else if($user->user_type==2)
									{
										$u_type ="Distributor ";
									}
									if($user->shop_master_id!=null)
									{
										$shop_data = $this->common_model->getsingle('shop_master',array('shop_master_id'=>$user->shop_master_id));
										$shop = $shop_data->shop_name;
									}else{
										$shop="";
									}
									$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$user->reporting_user_master_id));
									$reporting = $user_data->user_name.' ( '.$user_data->name.' ) ';
									
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo $user->name; ?></td>
										<td><a href="<?php echo base_url('reports/view_details/'.$user->user_master_id); ?>" ><?php echo $user->user_name; ?></a></td>
										
										<td><?php echo $u_type; ?></td>
										<td><?php echo $shop; ?></td>
										<td><?php echo $reporting; ?></td>
										<td><?php echo $user->user_comission; ?></td>
										<td>
											<?php if($user->is_user_deleted==1){ ?>
											<div class="badge badge-danger">Disabled</div>
											<?php }else{ ?>
											<div class="badge badge-success">Actived</div>
											<?php } ?>
										</td>
										<td>
											<a href="<?php echo base_url('users/view/'.$user->user_master_id); ?>" id="PopoverCustomT-1" class="btn btn-primary btn-sm">Details</a>
												<?php if($this->session->userdata('user_type')=='3'){ ?>
												<a href="<?php echo base_url('users/edit/'.$user->user_master_id); ?>" id="PopoverCustomT-2" class="btn btn-info btn-sm">Edit</a>
												<?php } ?>
											<?php if($user->is_user_deleted==1){ ?>
											<a onclick="return confirm('Are you sure you want to active this user?');" href="<?php echo base_url('users/user_status/'.$user->user_master_id.'/0'); ?>" id="PopoverCustomT-3" class="btn btn-success btn-sm">Active</a>
											<?php }else{ ?>
											<a onclick="return confirm('Are you sure you want to deactive this user?');" href="<?php echo base_url('users/user_status/'.$user->user_master_id.'/1'); ?>" id="PopoverCustomT-3" class="btn btn-danger btn-sm">Deactive</a>
											<?php } ?>
										</td>
									</tr>
									<?php $sno++; } ?>
									<?php } ?>
								</tbody>
							</table>
							
						</div>
						</br>
						<?php if (isset($links)) { ?>
							<nav class="" aria-label="Page navigation example">
							<?php echo $links ?>
							</nav>
						<?php } ?>
					</div>
				</div>
			</div>
			
		 </div>
	</div>
	