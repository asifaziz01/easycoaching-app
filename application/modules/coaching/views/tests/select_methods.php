<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="card card-default">
			<div class="card-header">
				<h4> <?php echo $page_title; ?></h4>
			</div>
			<div class="list-group">
				<a href="<?php echo site_url ('coaching/tests_actions/set_method/'.$category_id.'/'.$test_id.'/'.TEST_ADDQ_CREATE); ?>" class="list-group-item "> <i class="icon-circle"></i> Create and Add Questions <i class="icon-arrow-right pull-right"></i> </a>			
				<a href="<?php echo site_url ('coaching/tests_actions/set_method/'.$category_id.'/'.$test_id.'/'.TEST_ADDQ_UPLOAD); ?>" class="list-group-item"> <i class="icon-circle"></i> Upload Questions <i class="icon-arrow-right pull-right"></i> </a>			
			</div>	
		</div>
	</div>
</div>