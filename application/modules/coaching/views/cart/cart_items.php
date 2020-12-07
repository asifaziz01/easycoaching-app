<div class="row justify-content-center">
	<div class="col-md-12">
		<?php
		echo form_open ('coaching/cart/make_payment', array ('class'=>''));
		echo form_hidden ('owner_name', $user['first_name'] . " " . $user['last_name']);
		echo form_hidden ('email', $user['email']);
		echo form_hidden ('contact', $user['primary_contact']);
		echo form_hidden ('coaching_id', $coaching_id);
		echo form_hidden ('plan_id', $plan_id);
		?>
		<div class="card card-default">
			<div class="table-responsive" id="users-list">
				<table class="table table-bordered mb-0">
					<thead>
						<tr>
							<th width="60%">Title</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					if (! empty ($plan)) { 
						?>
						<tr>
							<td>
								<?php echo form_hidden('productinfo', $plan['title']); ?>
								<strong><?php echo $plan['title']; ?></strong><br>
								<?php echo $plan['description']; ?><br>
								<a href="#" onclick="show_confirm ('Remove this plan from cart?', '<?php echo site_url ('coaching/cart_actions/remove_item/'.$coaching_id.'/'.$plan['id']); ?>');">Remove</a>
							</td>
							<td>
								<?php
								$options = [];
								for ($i=1; $i<=12; $i++) {
									if ($i > 1)
										$options[$i] =  "$i Months";
									else
										$options[$i] =  "$i Month";
									if ($plan['id'] == FREE_SUBSCRIPTION_PLAN_ID) {
										break; // Show Only 1 month
									}
								}
								echo form_dropdown('plan_duration', $options, '', ['id'	=> 'plan_duration','class' => 'form-control']);
								?><br>
								<?php
								$price = $plan['price'];
								$amount = 0;
								$gst = ($price * $gst_slab)/100;
								$amount = $price * 1;
								$total_amount = $amount + $gst;
								$total_amount = round ($total_amount, 2);
								?>
								<i class="fa fa-rupee-sign"></i> <span id="price_html"><?php echo $price; ?></span>  per month
								<input type="hidden" name="price" value="<?php echo $price; ?>" id="price" >
							</td>
						</tr>
						<tr>
							<th class="justify-content-right">
								Amount 
							</th>
							<th class="justify-content-left">
								<i class="fa fa-rupee-sign"></i> <span id="amount_html"><?php echo $amount; ?></span>
								<input type="hidden" name="gst" value="<?php echo $amount; ?>" id="amount" >
							</th>
						<tr>
						<tr>
							<th class="justify-content-right">
								GST @ <?php echo $gst_slab; ?>%
							</th>
							<th class="justify-content-left">
								<i class="fa fa-rupee-sign"></i> <span id="gst_html"><?php echo $gst; ?></span>
								<input type="hidden" name="gst" value="<?php echo $gst; ?>" id="gst" >
							</th>
						<tr>
						<tr>
							<th class="justify-content-right">
								Total Amount
							</th>
							<th class="justify-content-left">
								<i class="fa fa-rupee-sign"></i> <span id="total_amount_html"><?php echo $total_amount; ?></span>
								<input type="hidden" name="amount" value="<?php echo $total_amount; ?>" id="total_amount" >
							</th>
						<tr>
						<tr>
							<th class="justify-content-between">
								<a href="<?php echo site_url ('coaching/subscription/browse_plans/'.$coaching_id.'/'.$plan_id); ?>" class="btn btn-link">Cancel</a>
							</th>
							<th>
								<?php if ($price > 0) { ?>
									<?php echo form_button(array('type' => 'submit','class' => 'btn btn-primary','content' => 'Make Payment'));?>
								<?php } else { ?> 
									<a href="<?php echo site_url ('coaching/subscription_actions/change_plan/'.$coaching_id.'/'.$plan_id); ?>" class="btn btn-primary">Subscribe Plan</a>
								<?php } ?>
							</th>
						</tr>
						<?php 
					} else {
						?>
						<tr>
							<td colspan="3">No items in cart <?php echo anchor ('coaching/subscription/browse_plans/'.$coaching_id.'/'.$current_plan, 'Browse Plans' ); ?></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>