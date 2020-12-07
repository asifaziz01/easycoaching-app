<?php echo form_open ('coaching/tests_actions/save_test_questions/'.$category_id.'/'.$test_id.'/'.$lesson_id.'/'.$cat_ids.'/'.$diff_ids.'/'.$exclude, array('class'=>'form-horizontal', 'id'=>'validate-1'));	?> 
	<div class="row">	
		
		<div class="col-md-3">
			<div class="widget box"> 
				<div class="widget-header">
					<h4>Filter: Category </h4>
				</div>
				<div class="widget-content">
					<div class="scroller" data-height="150" data-always-visible="1">
						<?php 
						$selectedCats = array ();
						if ($cat_ids != 0) {
							$selectedCats = explode ('-', $cat_ids);
						}
						
						$categories = $this->common_model->get_sys_parameters (SYS_QUESTION_CATEGORIES);
						if (is_array ($categories)) {
							foreach ($categories as $cat) {
								if ( in_array ($cat['paramkey'], $selectedCats)) {
									$cat_checked = 'checked="checked"';
								} else {
									$cat_checked = '';
								}
								echo  '<label class="radio">';
									echo '<input type="checkbox" class="uniform" '.$cat_checked.' name="categories[]" value="'.$cat['paramkey'].'" onclick="show_questions (this.value)">'; 
									echo $cat['paramval'];
								echo '</label>';
							}
						}
					
						?>
					</div>
				</div>
			</div>
			
			<div class="widget box">
				<div class="widget-header">
					<h4>Filter: Difficulty </h4>
				</div>
				<div class="widget-content">
					
					<div class="scroller" data-height="150" data-always-visible="1">
						<?php 
						$selectedDiff = array ();
						if ($diff_ids != 0) {
							$selectedDiff = explode ('-', $diff_ids);
						}
						
						$difficulties = $this->common_model->get_sys_parameters (SYS_QUESTION_DIFFICULTIES);
						if (is_array ($difficulties)) {
							foreach ($difficulties as $cat) {
								if ( in_array ($cat['paramkey'], $selectedDiff)) {
									$dif_checked = 'checked="checked"';
								} else {
									$dif_checked = '';
								}
								echo  '<label class="radio">';
									echo '<input type="checkbox" class="uniform" '.$dif_checked.' name="difficulties[]" value="'.$cat['paramkey'].'" onclick="show_questions (this.value)">';
									echo $cat['paramval'];
								echo '</label>';
							}
						}
						
						?>
					</div>
				</div>
			</div>
			
			<div class="widget box">
				<div class="widget-header">
					<h4>Other Options </h4>
				</div>
				<div class="widget-content">
					
					<div class="scroller" data-height="150" data-always-visible="1">
						<label class="radio">
							<input type="checkbox" class="uniform" name="exclude" id="exclude" value="1" onclick="show_questions (this.value)">
							Exclude deployed questions
						</label>
						
					</div>
				</div>
			</div>		
			
		</div>
	
	
		<div class="col-md-9">			
			<div class="widget box "> 
				<div class="widget-header">
					<h4>Select Questions</h4>
					<?php
					// Page Specific ToolBar Buttons
					if ( isset ($toolbar_buttons) > 0) { 
						echo $this->common_model->generate_toolbar ($toolbar_buttons); 
					} 
					?>
				</div><!-- /.widget-header -->
						
				<div class="widget-content no-padding">
					<div id="question_div">
						<?php $this->load->view ('ajax/add_questions', $data); ?>
					</div><!-- /.qUESTIONDIV -->

				</div><!-- /.widget-content -->
			</div><!-- /.widget -->
		</div> <!-- /.col-md-9 -->
	</div> <!-- /.row -->
</form> 


<script>
function show_questions (id) {
	
	var selected = [];
	
	$.each($("input[name='tree_check']:checked"), function() {
		var lesson_id = $(this).val();
		selected.push (lesson_id);
	}); 
	
	var str_selected = selected.join("-");

	// categories
	var cats = document.getElementsByName('categories[]');
	var selectedCats = [];
	for (var i = 0, l = cats.length; i < l; i++) {
		if (cats[i].checked) {
			selectedCats.push(cats[i].value);
		}
	}
	var allCats = selectedCats.join('-');
	if (allCats == '') {
		allCats = 0;
	}
	
	// difficulties
	var diffs = document.getElementsByName('difficulties[]');
	var selectedDiffs = [];
	for (var i = 0, l = diffs.length; i < l; i++) {
		if (diffs[i].checked) {
			selectedDiffs.push(diffs[i].value);
		}
	}
	var allDiffs = selectedDiffs.join('-');
	if (allDiffs == '') { 
		allDiffs = 0;
	}
	
	// exclude
	var excl = document.getElementById('exclude');
	if (excl.checked) {
		var exclude = excl.value;
	} else {
		var exclude = 0;
	}
	
	// num questions
	//	var num_questions = 0;
	//	$('#show_num_questions').change (function () {
	//		num_questions = $(this).val ();
	//	var num_questions = $('#show_num_questions').val ();
	//	});
	
	var num_questions = 0;
	var pageURL = '<?php echo site_url ('coaching/tests_actions/question_db/'.$category_id.'/'.$test_id); ?>/'+str_selected+'/'+allCats+'/'+allDiffs+'/'+exclude+'/'+num_questions;	

	$.ajax ({ 
		beforeSend: function() {
			NProgress.start();
		},
		complete: function(){ 
			NProgress.done();
		},
		type: 'POST',
		dataType: 'html',
		url: pageURL,
		success: function(result) {
			$('#question_div').html (result);
			var $content = $('#question_div');
			$content[0].scrollTop = $content[0].scrollHeight;
		}
	});	
}


// When tree node is clicked
$('.tree_item').click(function(e) {
	if ($(this).closest("li").children("ul").length) {
		e.preventDefault ();
	} else { 		
		var elem_id = $(this).attr ('id');
		show_questions (elem_id);	
	}
});

/*
// Delete confirmation 
function show_confirm_ajax (pageURL, msg) {
	var k = confirm (msg);
	if (k) {		
		$.ajax ({ 
			beforeSend: function(){
				NProgress.start();
			},
			complete: function(){
				NProgress.done();
			},
			type: 'POST',
			url: pageURL,
			success: function(result) {
				if (is_int(result) == true) {
					$('#validation-message').html ('<p class="text-success"> Action completed successfully</p>');
					document.location.href = gotoURL;
				} else {
					$('#validation-message').html ('<p class="errors">'+result+'</p>');
				}
				
				$('#question_div').html (result);
			}
		});
	}
}

/*
*/
</script>