<?php
if ( ! empty($all_plans)) {
	foreach ($all_plans as $plan) {
		$plan_id = $plan['id'];
		//if ($current_plan <> $plan_id) {
			?>			
			<div class="row justify-content-center mb-4">
			  <div class="col-sm-6">
				<div class="card my-2 "> 
				  <div class="card-header">
					<h4 class="card-title"><?php echo $plan['title']; ?></h4>
				  </div>
				  <div class="card-body">
					<?php
					$price = $plan['price'];
					if ($price > 0) {
						$amount = $price;
						$amount = round ($amount);
						echo '<h5 class="card-price"> <i class="fa fa-rupee-sign"></i> '. $amount. ' per month  </h5>';
					} else {
						echo '<h5 class="card-price"> Free </h5>';
					}
					?>
					<h6 class="card-price"> <i class="fa fa-user"></i> User Limit <?php echo $plan['max_users']; ?></h6>
					<p class="card-text"><?php echo $plan['description']; ?></p>
					<span class="tag tag-pill tag-info"></span>
					<span class="tag tag-pill tag-info"></span>
				  </div>
				  <div class="card-footer">
				  	<?php if ($plan['id'] == $current_plan) { ?>
				  		<span class="badge badge-success">Current Plan</span>
				  	<?php } ?>
					<a href="<?php echo site_url ('coaching/cart_actions/add_item/'.$coaching_id.'/'.$plan['id']); ?>" class="btn btn-primary"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Select Plan</a>
				  </div>
				</div>
			  </div>
			</div>
			<?php
		//}
	}
}
?>