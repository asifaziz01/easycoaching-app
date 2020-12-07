<div class="cards">
    <?php if ($test_type == TEST_TYPE_REGULAR) { ?>
      <ul class="list-group ">
        <li class="list-group-item">On Going Tests</li>
        <?php
        $i = 1;
        if (! empty ($tests['ongoing'])) {
          foreach ($tests['ongoing'] as $row) { 
            ?>
            <li class="list-group-item media -v-middle">
              <div class="media-left">
                <?php echo $i; ?>
              </div>
              <div class="media-body">
                <h4 class=""><?php echo $row['title']; ?></h4>
                <div class="">
                    <span class="badge badge-success">Ongoing Test</span>
                    <div>
                      QUESTIONS: <?php echo $row['num_test_questions']; ?><br>
                      MM: <?php echo $row['test_marks']; ?>
                    </div>
                    <p class="text-muted">
                      Started On: <?php echo date ('d M, Y H:i A', $row['start_date']); ?><br>
                      Ending On: <?php echo date ('d M, Y H:i A', $row['end_date']); ?>
                    </p>
                </div>              
                <?php 
                if ($row['attempts'] == 0  || $row['num_attempts'] < $row['attempts'] ) {
                  echo anchor ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$row['test_id'], 'Take Test', ['class'=>'btn btn-success']); 
                } else {
                  echo '<span class="badge badge-danger">All attempts taken</span>';
                }
                ?>
              </div>
            </li>
            <?php
            $i++;
          }
        } else {
          ?>
          <li class="list-group-item text-danger">
              No tests right now 
          </li>
        <?php } ?>
      </ul>


      <ul class="list-group mt-4">
        <li class="list-group-item">Up-coming Tests</li>
          <?php if (! empty ($tests['upcoming'])) { ?>
            <?php foreach ($tests['upcoming'] as $row) { ?>
              <li class="list-group-item">
                  <div class="media v-middle">
                    <div class="media-left">
                      <span class="icon-block half bg-warning rounded-circle ">
                        <i class="fa fa-superscript"></i>
                      </span>
                    </div>
                    <div class="media-body">
                    <h4 class="">
                      <?php echo anchor ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$row['test_id'], $row['title'], ['class'=>'link-text-color']); ?>                      
                    </h4>
                    <div class="">
                        <span class="badge badge-warning">Upcoming Test</span>
                        <div>
                          QUESTIONS: <?php echo $row['num_test_questions']; ?><br>
                          MM: <?php echo $row['test_marks']; ?>
                        </div>

                        <div class="text-muted">
                          Started On: <?php echo date ('d M, Y H:i A', $row['start_date']); ?><br>
                          Ending On: <?php echo date ('d M, Y H:i A', $row['end_date']); ?>
                        </div>
                    </div>
                  </div>
              </li>
            <?php } ?>
          <?php } else { ?>
            <li class="list-group-item text-danger ">
                No tests right now
            </li>
          <?php } ?>
        </ul>


      <ul class="list-group mt-4">
        <li class="list-group-item">Archived Tests</li>
        <?php if (! empty ($tests['archived'])) { ?>
          <?php foreach ($tests['archived'] as $row) { ?>
            <li class="list-group-item media -v-middle">
              <div class="media-body">
                <h4 class=""><?php echo $row['title']; ?></h4>
                <div class="">
                    <span class="badge badge-default bg-grey-200">Archived Test</span>
                    <div>
                      QUESTIONS: <?php echo $row['num_test_questions']; ?><br>
                      MM: <?php echo $row['test_marks']; ?>
                    </div>

                    <div class="text-muted">
                      Started On: <?php echo date ('d M, Y H:i A', $row['start_date']); ?><br>
                      Ended On: <?php echo date ('d M, Y H:i A', $row['end_date']); ?>
                    </div>
                </div>
              </div>              
            </li>
          <?php } ?>
        <?php } else { ?>
          <li class="list-group-item text-danger">
              No tests right now 
          </li>
        <?php } ?>
      </ul>

    <?php } else { ?>
      <ul class="list-group list-group-flush ">
        <?php 
        if (! empty ($tests)) {
          foreach ($tests as $row) {
            ?>
            <li class="list-group-item">
                <div class="media v-middle">
                  <div class="media-left">
                    <div class="icon-block s30 bg-red-400 text-white" title="Report">
                      <i class="fa fa-file"></i>
                    </div>
                  </div>
                  <div class="media-body">
                    <?php echo anchor ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$row['test_id'], $row['title'], ['class'=>'link-text-color']); ?>
                    <div>
                      QUESTIONS: <?php echo $row['num_test_questions']; ?><br>
                      MM: <?php echo $row['test_marks']; ?>
                    </div>
                  </div>
                  <div class="media-right">
                    <?php
                    echo anchor ('student/tests/test_instructions/'.$coaching_id.'/'.$member_id.'/'.$row['test_id'], 'Take Test', ['class'=>'btn btn-success']); 
                    ?>
                  </div>
                </div>
            </li>
            <?php
          }
        } else {
          ?>
          <li class="list-group-item">
                No tests right now
          </li>
          <?php
        }
        ?>
    </ul>
  <?php } ?>
</div>