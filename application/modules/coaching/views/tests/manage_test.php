<div class="card mb-3 ">
	<div class="card-header">
		<p class="card-title font-weight-bold">Prepare</p>
	</div>
	<div class="card-body">
		<ul class="list-inline">
		  <li class="list-inline-item">
		  	<a class="" href="<?php echo site_url ('coaching/tests/questions/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>">Questions</a>
		  </li>
		  <?php if ($test['finalized'] == 0) { ?>
			  <li class="list-inline-item ml-4">
		      	<a class="" href="<?php echo site_url ('coaching/tests/question_group_create/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>">Add Question</a>
		      </li>
		  <?php } ?>

		  <li class="list-inline-item ml-4">
		  	<a class="" href="<?php echo site_url ('coaching/tests/enrolments/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>">Enrolments</a>
		  </li>

		</ul>

	</div>
</div>


<div class="card mb-3">
	<div class="card-header">
		Publish
	</div>
	<div class="card-body">
		<ul class="list-inline">
		  	<?php if ($test['finalized'] == 0) { ?>
			  <li class="list-inline-item">
		      	<a class="" href="javascript:void(0)" onclick="javascript:show_confirm ('Publish this test?', '<?php echo site_url('coaching/tests_actions/finalise_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Publish Test">Publish</a>
		      </li>
	      	<?php } else { ?>
			  <li class="list-inline-item">
		      	<a class="" href="javascript:void(0)" onclick="javascript:show_confirm ('Un-publish this test? Test will not be available to users after un-publish.', '<?php echo site_url('coaching/tests_actions/unfinalise_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Un-Publish Test">Un-Publish</a>
		      </li>
	      	<?php } ?>
		  <li class="list-inline-item ml-4">
		      <a class="" href="<?php echo site_url ('coaching/tests/preview_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>" title="Preview Test">Preview </a>
		  </li>		  
		  <li class="list-inline-item ml-4">
		      <a class="d-none" href="<?php echo site_url ('coaching/tests/print_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>" target="_blank" title="Print Test">Print</a>
		  </li>
		  <li class="list-inline-item ml-4">
		      <a class="d-none" href="<?php echo site_url ('coaching/tests_actions/export_pdf/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>" target="_blank" title="Export As PDF">Export</a>
		  </li>
		</ul>

		<ul class="list-inline mt-4">
		  <li class="list-inline-item ">
		      <a class="" href="<?php echo site_url ('coaching/reports/submissions/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>">Submissions</a>
		  </li>
		  <li class="list-inline-item ml-4">
		  	<?php ?>
		      	<a class="" href="javascript:void(0)" onclick="javascript:show_confirm ('Release test results for users?', '<?php echo site_url('coaching/tests_actions/release_result/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="">Release Result</a>
		    <?php ?>
		  </li>
		</ul>

	</div>
</div>

<div class="card  mb-3 ">
	<div class="card-header">
		<p class="card-title font-weight-bold">Delete</p>
	</div>
	<div class="card-body">
		<ul class="list-inline">
		  <li class="list-inline-item">
			<a class="" href="javascript:void(0)" onclick="javascript:show_confirm ('This will delete all questions in this test. Continue?', '<?php echo site_url('coaching/tests_actions/reset_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Reset Test">Reset</a>
		  </li>
		  <li class="list-inline-item ml-4">
			<a class="btn btn-danger" href="javascript:void(0)" onclick="javascript:show_confirm ('Are you sure you want to delete this test?', '<?php echo site_url('coaching/tests_actions/delete_test/'.$coaching_id.'/'.$category_id.'/'.$test_id); ?>')" title="Delete Test" >Delete</a>
	      </li>
		</ul>

	</div>
</div>
