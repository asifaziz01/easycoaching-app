<div class="row justify-content-center">
  <div class="col-md-8">
	<div class="card">
		<div class="card-header p-3">
			<h4 class="card-title"><?php echo $coaching['title']; ?></h4>
		</div>
		<div class="card-body">
			<p class="card-text"><?php echo $coaching['description']; ?></p>
			<ul class="list-group list-group-flush ">
				<li class="list-group-item">
					<div class="media">
						<div class="media-body">
							<p class="mb-0"><i class="fa fa-calendar fa-fw"></i> Date of Joining: <?php echo date ('F d, Y', $coaching['starting_from']); ?></p>
							<p class="mb-0"><i class="fa fa-calendar fa-fw"></i> Valid Till: <?php echo date ('F d, Y', $coaching['ending_on']); ?></p>
						</div>
						<div class="media-right">
							<label>Status: <?php if ($coaching['ending_on'] < time()) echo '<span class="badge badge-danger">Expired</span>'; else echo '<span class="badge badge-success">Active</span>'; ?></label>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="card-footer">
			<a href="<?php echo site_url ('coaching/subscription/browse_plans/'.$coaching_id.'/'.$coaching['sp_id']); ?>" class="btn btn-primary" >Change My Plan</a>
		</div>
	</div>
  </div>
</div>