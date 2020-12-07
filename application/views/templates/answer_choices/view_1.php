<ul class="list-unstyled">
	<?php
	$a = 'a';
	for ($i=1; $i<=QB_NUM_ANSWER_CHOICES; $i++) : 
		if ( $result['answer_'.$i] == $i) {
			$class = 'text-success';
		} else {
			$class = '';
		}
		if ($result['choice_'.$i] != '') {			
			?>
			<li class="media <?php echo $class; ?>">
				<div class="media-left">
					<?php echo $a; ?>. 
				</div>
				<div class="media-body">
					<?php echo $result['choice_'.$i]; ?>
				</div>
			</li>
			<?php 
		}
		$a++;
	endfor; 
	?>
</ul>