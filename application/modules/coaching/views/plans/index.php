<?php
if ($category_id > 0) {
    ?>
    <div class="row justify-content-center" >
    	<div class="col-md-10">
    	  <div class="card">
    		 <ul class="list-group">
				<?php 
				if ( ! empty ($plans)) {
					foreach ($plans as $row) {
						?>
						<li class="list-group-item ">
						        
							<p class="text-bold mb-0">
							<?php echo anchor ('coaching/plans/tests_in_plan/'.$coaching_id.'/'.$category_id.'/'.$row['plan_id'], $row['title'], array ('title'=>'Browse all tests in this plan ')); ?>
							</p>
							<span class="text-grey-500">
								Category: <?php echo $row['cat_title']; ?>
							</span>
                            <br>

						    <div class="d-flex justify-content-between">
								<?php 
								if ($row['amount'] == 0) {
									echo '<span class="label label-primary">Free</span>';
								} else {
									echo '<span><i class="fa fa-rupee-sign"></i> '.$row['amount'] . ' per month</span>';
								}
								?>

								<span>
									<?php 
										echo $row['tests_in_plan'] . ' Tests';
									?>
								</span>
								
								<?php if ($row['amount'] == 0) { ?>
									<a href="<?php echo site_url('coaching/plans/subscribe_plan/'.$coaching_id.'/'.$category_id.'/'.$row['plan_id']); ?>" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="bottom" data-original-title="Subscribe For Free"><i class="fa fa-edit"></i> Subscribe For Free</a>
								<?php } else {
								    if ($this->session->userdata ('is_admin') == true) {
								        ?>
									    <a href="<?php echo site_url('coaching/plans/subscribe_plan/'.$coaching_id.'/'.$category_id.'/'.$row['plan_id']); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" data-original-title="Add this plan to coaching"><i class="fa fa-plus"></i> Add Test Plan</a>
								        <?php 
								    } else {
								        ?>
									    <a href="<?php echo site_url('coaching/plans/buy_plan/'.$coaching_id.'/'.$category_id.'/'.$row['plan_id']); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" data-original-title="Buy Plan"><i class="fa fa-shopping-cat"></i> Add To Cart</a>
								        <?php
								    }
								    ?>
								<?php } ?>
						    </div>

						</li>
						<?php 
					}
				} else { 
				?>
				<tr>
					<td colspan="6"><?php echo 'No Plans Found'; ?></td>
				</tr>
			    <?php } // if result ?>
			  </ul>
    	  </div>
    	</div>
    </div>
    <?php
} else {
    ?>
    <div class="row justify-content-center">
    <?php
    if (! empty ($categories)) {
        foreach ($categories as $cat) {
           ?>
           <div class="col-md-3">
               <div class="card my-2 border-primary">
    				<div class="card-body height-100 d-flex align-items-center justify-content-center">
    					<h4 class="card-title text-center my-3">
                           <a class="text-black text-decoration-none stretched-link " href="<?php echo site_url ('coaching/plans/index/'.$coaching_id.'/'.$cat['id']); ?>"><?php echo  $cat['title']; ?></a>
    					</h4>
    				</div>
    			</div>
    		</div>
           <?php
        }
    } else {
    	?>
    	<div class="col-md-8">
        	<div class="alert alert-danger">
        		<p>No test categories have been added yet</p>
        	</div>
    	</div>
    	<?php
    }
    ?>
    </div>
    <?php
}

?>
