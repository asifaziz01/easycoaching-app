<ul class="list-unstyled">
	<?php
	if ( $result['answer_1'] == 1) {
		$class = 'text-success';
	} else {
		$class = '';
	}
	?>
	<li class="media <?php echo $class; ?>">
		<div class="media-left">
			a. 
		</div>
		<div class="media-body">
			True
		</div>
	</li>

	<?php
	if ( $result['answer_2'] == 1) {
		$class = 'text-success';
	} else {
		$class = '';
	}
	?>
	<li class="media <?php echo $class; ?>">
		<div class="media-left">
			a. 
		</div>
		<div class="media-body">
			False
		</div>
	</li>
</ul>