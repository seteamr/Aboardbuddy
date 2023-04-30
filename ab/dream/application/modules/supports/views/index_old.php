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
					<div>Supports
						<div class="page-title-subheading">
						</div>
					</div>
				</div>
				<div class="page-title-actions">				
				
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
                                            <form class="form-inline" action="<?php echo base_url('supports/index') ?>" method="post">
                                               
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
									<th>Title</th>
									<th>Description</th>
									<th>Date</th>
								</tr>
								</thead>
								<tbody>
									<?php if(count($supports)>0){ ?>
									<?php foreach($supports as $user){ ?>
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
									else if($user->user_type==3)
									{
										$u_type ="Admin ";
									}
									
									?>
									<tr>
										<th scope="row"><?php echo $sno; ?></th>
										<td><?php echo $user->name; ?></td>
										<td><?php echo $user->user_name; ?></td>
										<td><?php echo $u_type; ?></td>
										<td><?php echo $user->title; ?></td>
										<td><?php echo $user->description; ?></td>
										<td><?php echo date('d-M-Y',strtotime($user->entry_date)); ?></td>
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
	