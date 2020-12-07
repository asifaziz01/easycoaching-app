<div class="row">
	<div class="col-md-3">
		<div class="card card-default">
		  <div class="card-header d-flex justify-content-between">
			<h4 class="card-title">Categories</h4>
		  </div>
		  <nav aria-label="breadcrumb" class="bg-white">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo site_url ('plans/admin/add_tests/'.$plan_id); ?>">All Categories</a></li>
				  <?php if (! empty($breadcrumbs)) { ?>
					<?php foreach ($breadcrumbs as $bc) { ?>
					<li class="breadcrumb-item"><a href="<?php echo site_url ('plans/admin/add_tests/'.$plan_id.'/'.$bc['id']); ?>"><?php echo $bc['title']; ?></a></li>
					<?php } ?>
				  <?php } ?>
			  </ol>
		  </nav>
          <ul class="list-group list-group-menu">
            <?php  
			if (! empty ($categories)) {
				foreach ($categories as $row) {
					?>
					<li class="list-group-item <?php if ($row['id'] == $category_id) echo 'active'; ?>">
                      <a href="<?php echo site_url ('plans/admin/add_tests/'.$plan_id.'/'.$row['id']); ?>"><i class="fa fa-chevron-right fa-fw"></i> <?php echo $row['title']; ?> </a>
                    </li>
					<?php
				}
			} else {
				?>
                <li class="list-group-item text-danger">
                  None found
                </li>
                <?php
			}
			?>
          </ul>

		</div>
	</div>
	
	<div class="col-md-9">
		

		<div class="card mb-2"> 
			<div class="card-body ">
				<strong>Search</strong>
				<?php echo form_open('plans/admin/add_tests/'.$plan_id.'/'.$category_id.'/'.$offset, array('class'=>"", 'id'=>'search-forms')); ?>
					<div class="form-group row mb-2">
						<div class="col-md-6 mb-2">
							<select name="search_status" class="form-control" id="search-status" >
								<option value="-1">All Status</option>
								<option value="<?php echo TEST_STATUS_PUBLISHED; ?>" <?php echo  set_select('search_status', TEST_STATUS_PUBLISHED); ?> >Published</option>
								<option value="<?php echo TEST_STATUS_UNPUBLISHED; ?>" <?php echo  set_select('search_status', TEST_STATUS_UNPUBLISHED); ?> >Unpublished</option>
							</select>
						</div>
						<div class="col-md-6">
							<div class="input-group ">
								<input name="search_text" class="form-control " type="search" placeholder="Search" aria-label="Search Test" aria-describedby="search-button" value="<?php echo set_value ('search_text'); ?>">
								<div class="input-group-append">
									<button class="btn btn-sm btn-primary " type="submit" id="search-button"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						Tests Found: <span id="count-tests"><?php echo $count_tests; ?></span>
					</div> 
					<div class="form-group">
						<div class="pagination" id="pagination">
							<?php echo $this->pagination->create_links(); ?>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="card card-default">
			<?php echo form_open ('plans/functions/add_tests/'.$plan_id.'/'.$category_id, array ('id'=>'validate-1')); ?>
				<div class="table-responsive" id="test-lists">
					<table class="table">
						<thead>
							<tr>
								<td colspan="5">
									<button type="submit" class="btn btn-primary" ><i class="fa fa-plus"></i> Add Tests</button>
								</td>
							</tr>
							<tr>
								<th width="5%">#</th>
								<th width="5%">
									<input type="checkbox" name="" id="check-all">
								</th>
								<th width="70%">Test Name</th>
								<th>Duration</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$i = $offset + 1;
						if ( ! empty ($tests)) { 
							foreach ($tests as $row) { 
								?>
								<tr>
									<td><?php echo $i; ?>
									<td>
										<input type="checkbox" name="tests[]" value="<?php echo $row['test_id']; ?>" class="check" >
									</td>
									<td>
										<?php echo $row['title']; ?><br>
										<?php echo $row['unique_test_id']; ?>
									</td>
									<td>
										<?php echo $duration = $row['time_min'] . ' mins'; ?>
									</td>
									<td>
										<?php 
										if ($row['finalized'] == 1) {
											echo '<span class="badge badge-primary">Published</span>';
										} else {
											echo '<span class="badge badge-light">Draft</span>';
										}
										?>
									</td>
								</tr>
								<?php 
								$i++; 
							} 
						} else {
							?>
							<tr>
								<td colspan="5"><span class="text-danger">No tests found</span></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
				</div>
				<hr>
	
				<div class="card-footer">
					<button type="submit" class="btn btn-primary" ><i class="fa fa-plus"></i> Add Tests</button>
				</div>
			</form>
		</div>
	</div>
</div>
	