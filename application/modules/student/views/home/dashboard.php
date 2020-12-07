<div class="card mb-4 shadow-sm">
	<div class="card-header ">
		<h4 class="my-1">My Classrooms</h4>
	</div>
		<?php 
		$i=1;
		if (! empty ($my_classrooms)) {
			?>
			<ul class="list-group ">
			<?php
			foreach($my_classrooms as $row) { 
				?>
				<li class="list-group-item media">
					<div class="media-left">
						<?php if ($row['running'] == 'true') { ?>
							<span class="icon-block half rounded-circle bg-success">
								<i class="fa fa-video"></i>
							</span>
						<?php } else { ?>
							<span class="icon-block half rounded-circle bg-grey-200">
								<i class="fa fa-video"></i>									
							</span>
						<?php } ?>
					</div>
					<div class="media-body">
						<h4 class=""><?php echo $row['class_name']; ?></h4>
						<?php if ($row['running'] == 'true') { ?>
							<span class="badge badge-success">Class has started</span>
						<?php } else { ?>
							<span class="badge badge-default bg-grey-200">Class not started</span>
						<?php } ?>							
						<?php //echo anchor ('coaching/virtual_class/class_details/'.$coaching_id.'/'.$row['class_id'], $row['class_name']); ?>
						<div class="mt-2">
							<?php 
							if ($row['running'] == 'true') {
								echo anchor ('student/virtual_class/join_class/'.$coaching_id.'/'.$row['class_id'].'/'.$member_id, '<i class="fa fa-plus"></i> Join Class', ['class'=>'btn btn-success mr-1']);
							} else {
								echo anchor ('student/virtual_class/join_class/'.$coaching_id.'/'.$row['class_id'].'/'.$member_id, '<i class="fa fa-plus"></i> Join Class', ['class'=>'btn btn-default mr-1']);
							}
							?>
						</div>
					</div>

					<div class="media-right">
					</div>
				</li>
				<?php
				$i++;
				if ($i >= 3) {
					break;
				}
			}?>
		</ul>
		<?php
		} else {
        	?>
        <div class="card-body">
            <div class="alert alert-danger mb-0">
                You are not enroled in any class
            </div>
        </div>
            <?php
        }
		?>
	<div class="card-footer text-right">
		<?php echo anchor ('student/virtual_class/index/'.$coaching_id.'/'.$member_id, '<i class="fa fa-video"></i> All Classrooms', ['class'=>'btn btn-link mr-1']); ?>
	</div>
</div>

<div class="card mb-4 shadow-sm">
	<div class="card-header ">
		<h4 class="my-1">My Tests</h4>
	</div>
	<?php 
	$i=1;
	$now = time ();
    if (! empty ($enrolments)) {
        echo '<ul class="list-group list-group-flush ">';
            foreach ($enrolments as $row) {
                ?>
                <li class="list-group-item ">
                    <div class="media v-middle ">
                      <div class="media-left">
		                <?php if ( $now >= $row['start_date'] && $now <= $row['end_date']) { ?>
                            <span class="icon-block half bg-success rounded-circle ">
	                          <i class="fa fa-superscript"></i>
	                        </span>
	                	<?php } else if ($now < $row['start_date'] && $now < $row['end_date']) { ?>
                            <span class="icon-block half bg-warning rounded-circle ">
	                          <i class="fa fa-superscript"></i>
	                        </span>
                        <?php } else { ?>
                            <span class="icon-block half bg-grey-200 rounded-circle">
	                          <i class="fa fa-superscript"></i>
	                        </span>
	                    <?php } ?>
                      </div>
                      <div class="media-body">
                        <h4 class=""><?php echo $row['title']; ?></h4>
                        <div class="">
			                <?php if ( $now >= $row['start_date'] && $now <= $row['end_date']) { ?>
	                            <span class="badge badge-success">Ongoing Test</span>
		                	<?php } else if ($now < $row['start_date'] && $now < $row['end_date']) { ?>
	                            <span class="badge badge-warning">Upcoming Test</span>
	                        <?php } else { ?>
	                            <span class="badge badge-default bg-grey-200">Archived Test</span>
	                        <?php } ?>
	                        <div>
	                        	QUESTIONS: <?php echo $row['num_test_questions']; ?><br>
	                        	MM: <?php echo $row['test_marks']; ?>
	                        </div>

                            <div class="text-muted">
                            	Started On: <?php echo date ('d M, Y H:i A', $row['start_date']); ?><br>
                            	Ending On: <?php echo date ('d M, Y H:i A', $row['end_date']); ?>
                            </div>
                        </div>
                        <?php 
		                if ( ($now >= $row['start_date'] && $now <= $row['end_date']) && ($row['attempts'] == 0  || $row['num_attempts'] < $row['attempts']) ) {
		                	// Ongoing Tests
	                        echo anchor ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$row['test_id'], 'Take Test', ['class'=>'btn btn-success']);
		                } else if ($now < $row['start_date'] && $now < $row['end_date']) {
			                // Up coming tests
		                } else if ($row['release_result'] == RELEASE_EXAM_IMMEDIATELY) {
		                }
		                ?>
                      </div>
                     
                    </div>
                </li>
                
                <?php
       			$i++;
				if ($i >= 3) {
					break;
				}		
	        }
        echo '</ul>';
	} else {
        ?>
        <div class="card-body">
            <div class="alert alert-danger mb-0">
                No tests right now 
            </div>
        </div>
        <?php
    }
    ?>
	<div class="card-footer text-right">
		<?php echo anchor ('student/tests/index/'.$coaching_id.'/'.$member_id.'/'.TEST_TYPE_PRACTICE, '<i class="fa fa-superscript"></i> Practice Tests', ['class'=>'btn btn-primary mr-1']); ?>
		<?php echo anchor ('student/tests/index/'.$coaching_id.'/'.$member_id, '<i class="fa fa-superscript"></i> All Tests', ['class'=>'btn btn-link mr-1']); ?>
	</div>
</div>

<div class="row no-gutters mx-n2 flex-nowrap overflow-auto">
	<?php
		if (! empty ($dashboard_menu)) {
			foreach ($dashboard_menu as $i => $menu) {
				$link = $menu['controller_path'].'/'.$menu['controller_nm'].'/'.$menu['action_nm'].'/'.$coaching_id.'/'.$member_id;				
				?>
				<div class="col-7 col-sm px-2 mb-4">
					<div class="card primary-banner">
						<div class="card-body text-center <?php echo strtolower(str_replace(" ","-",$menu['menu_desc']));?>">
							<a href="<?php echo site_url ($link); ?>" class="text-white text-decoration-none stretched-link">
								<?php echo $menu['icon_img']; ?><br>
								<?php echo $menu['menu_desc'];?>
							</a>
						</div>
					</div>
				</div>	
				<?php
			}
		}
	?>
</div>