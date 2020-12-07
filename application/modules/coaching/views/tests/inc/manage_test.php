<div class="card card-body mb-3 ">
	<div class="row text-center">
		<div class="col-md-3">
			MM: <?php echo $test_marks; ?><br>
			QUESTIONS: <?php echo $num_test_questions; ?>
		</div>

		<div class="col-md-6 ">
		 	<a class="navbar-brand" href="<?php echo site_url ('coaching/tests/manage/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>"><?php echo character_limiter ($test['title'], 20); ?></a><br>
		 	<?php if ($test['finalized'] == 1) { ?>
		 		<span class="badge badge-success">Published</span>
		 	<?php } else { ?>
		 		<span class="badge badge-secondary">Un-Published</span>
		 	<?php } ?>
		</div>


		<div class="col-md-3 ">
			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
			    Actions
			  </button>
			  <div class="dropdown-menu dropdown-menu-lg-right">
			      <a class="dropdown-item" href="<?php echo site_url ('coaching/tests/questions/'.$coaching_id.'/'.	$category_id.'/'.$test_id); ?>">Questions</a>
			      <a class="dropdown-item" href="<?php echo site_url ('coaching/tests/question_group_create/'.$coaching_id.'/'.	$category_id.'/'.$test_id); ?>">Add Section</a>
			      <?php if ($test['test_type'] == TEST_TYPE_REGULAR) { ?>
			      	<a class="dropdown-item" href="<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.	$category_id.'/'.$test_id); ?>">Enrolments</a>
				  <?php } ?>
			      
			      <div class="dropdown-divider"></div>
				  <?php if ($test['finalized'] == 0) { ?>
			      	<a class="dropdown-item" href="javascript:void(0)" onclick="javascript:show_confirm ('Publish this test?', '<?php echo site_url('coaching/tests_actions/finalise_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Publish Test">Publish</a>
			      <?php } else { ?>
			      	<a class="dropdown-item" href="javascript:void(0)" onclick="javascript:show_confirm ('Un-publish this test? Test will not be available to users after un-publish.', '<?php echo site_url('coaching/tests_actions/unfinalise_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Un-Publish Test">Un-Publish</a>
			      <?php } ?>
			      <a class="dropdown-item" href="<?php echo site_url ('coaching/tests/preview_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>" title="Preview Test">Preview </a>
			      <a class="dropdown-item d-none" href="<?php echo site_url ('coaching/tests/print_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>" target="_blank" title="Print Test">Print</a>
			      <a class="dropdown-item d-none" href="<?php echo site_url ('coaching/tests_actions/export_pdf/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>" target="_blank" title="Export As PDF">Export</a>
			      
			      <div class="dropdown-divider"></div>
			      <a class="dropdown-item" href="<?php echo site_url ('coaching/reports/submissions/'.$coaching_id.'/'.	$category_id.'/'.$test_id); ?>">Submissions</a>

			      <div class="dropdown-divider"></div>
			      <a class="dropdown-item" href="<?php echo site_url ('coaching/tests/create_test/'.$coaching_id.'/'.	$category_id.'/'.$test_id); ?>">Edit</a>
			      <a class="dropdown-item" href="javascript:void(0)" onclick="javascript:show_confirm ('This will delete all questions in this test. Continue?', '<?php echo site_url('coaching/tests_actions/reset_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Reset Test">Reset</a>
			      <a class="dropdown-item" href="javascript:void(0)" onclick="javascript:show_confirm ('Are you sure you want to delete this test?', '<?php echo site_url('coaching/tests_actions/delete_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Delete Test" >Delete</a>
			  </div>
			</div>
		</div>
	</div>
</div>