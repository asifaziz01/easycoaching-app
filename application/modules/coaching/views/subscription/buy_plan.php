<section class="content">
	<div class="container">
<div class="row" data-toggle="isotope">
	<div class="item col-xs-12 col-sm-6 col-lg-6">
		<div class="card card-default bg-primary paper-shadow" data-z="0.5">	
			<div class="card-header">
				<h4 class="text-bold ">Description</h4>
			</div>
			<div class="card-body">
				<div class="text-white">
					Plan upgradation process has three simple steps-
					<ol>
						<li>Send in your request for plan upgrade.</li>
						<li>Our support team will contact you.</li>
						<li>Your plan will be upgraded.</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	
	<div class="item col-xs-12 col-sm-6 col-lg-6">
		<div class="card card-default paper-shadow" data-z="0.5">	
			<div class="card-header">
				<h4 class="text-bold ">Send your request</h4>
			</div>
			<div class="card-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label class="col-md-3">Coaching Name</label>
						<div class="col-md-9"><?php echo $coaching['coaching_name']; ?></div>
					</div>
					<div class="form-group">
						<label class="col-md-3">Current Plan</label>
						<div class="col-md-9"><?php echo $current_plan['title']; ?></div>
					</div>
					<div class="form-group">
						<label class="col-md-3">Selected Plan</label>
						<div class="col-md-9"><?php echo $plan['title']; ?></div>
					</div>
				</form>
			</div>
			<div class="card-footer">
				<a href="<?php echo site_url ('coachings/admin/buy_plan/'.$coaching_id.'/'.$plan_id.'/'.$member_id); ?>" class="btn btn-primary">Upgrade My Subscription</a>
			</div>
		</div>
	</div>
</div>
	</div>
</section>