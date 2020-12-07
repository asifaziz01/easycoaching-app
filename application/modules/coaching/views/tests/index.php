<div class="card mb-2"> 
	<div class="card-body ">
		<strong>Search</strong>
		<?php echo form_open('coaching/tests_actions/search_tests/'.$coaching_id, array('class'=>"", 'id'=>'search-form')); ?>
			<div class="form-group row mb-2">
				<div class="col-md-3 mb-2">
					<select name="category" class="form-control" id="search-category" >
						<option value="0">All Categories</option>
			            <?php
			              if (! empty($categories)) {
			                foreach ($categories as $cat) {
			                 ?>
							 <option value="<?php echo $cat['id']; ?>" <?php if ($category_id == $cat['id']) echo 'selected="selected"'; ?>><?php echo $cat['title']; ?></option>
			                <?php
			                }
			              }
			            ?>
			        </select>
				    <small><a class="" href="<?php echo site_url ('coaching/tests/categories/'.$coaching_id); ?>">Edit</a></small>
					
				</div>

				<div class="col-md-3 mb-2">
					<select name="status" class="form-control" id="search-status" >
						<option value="-1">All Status</option>
						<option value="<?php echo TEST_STATUS_PUBLISHED; ?>" <?php if ($status == TEST_STATUS_PUBLISHED) echo 'selected="selected"'; ?> >Published</option>
						<option value="<?php echo TEST_STATUS_UNPUBLISHED; ?>"  <?php if ($status == TEST_STATUS_UNPUBLISHED) echo 'selected="selected"'; ?>>Unpublished</option>
					</select>
				</div>

				<div class="col-md-3 mb-2">
					<select name="type" class="form-control" id="search-type" >
						<option value="0">Test Type</option>
						<option value="<?php echo TEST_TYPE_REGULAR; ?>" <?php if ($type == TEST_TYPE_REGULAR) echo 'selected="selected"'; ?> >Regular Tests</option>
						<option value="<?php echo TEST_TYPE_PRACTICE; ?>"  <?php if ($type == TEST_TYPE_PRACTICE) echo 'selected="selected"'; ?>>Practice Tests</option>
					</select>
				</div>

				<div class="col-md-3">
					<div class="input-group ">
						<input name="search_text" class="form-control " type="search" placeholder="Search" aria-label="Search Test" aria-describedby="search-button">
						<div class="input-group-append">
							<button class="btn btn-sm btn-primary " type="submit" id="search-button"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>

			</div>
		</form>
	</div>
</div>

<div id="test-list">
	<?php $this->load->view ('tests/inc/index', $data); ?>
</div>

<div class="row">

	<div class="col-lg-3">
        <?php
          if (!empty($plans)) {
            foreach ($plans as $plan) {
             ?>
             <div class="card card-default mb-2">
                <div class="card-header d-flex justify-content-between">
                  <h4 class="card-title"><?php echo $plan['title']; ?></h4>
                </div>
                <div style="height:200px; overflow-y:auto">
                  <ul class="list-group list-group-menu">
                    <?php
     		          $cats = $this->test_plans_model->categories_in_plan ($coaching_id, $plan['plan_id']);
                      if (!empty($cats)) {
                        foreach ($cats as $cat) {
                          ?>
                	      <li class="list-group-item <?php if ($category_id == $cat['id']) echo 'active'; ?>">
                            <a href="<?php echo site_url ('coaching/tests/index/'.$coaching_id.'/'.$cat['id']); ?>"><i class="fa fa-chevron-right fa-fw"></i> <?php echo $cat['title']; ?> </a>
                          </li>
                          <?php
                        }
                      }
                    ?>
                  </ul>
                </div>
             </div>
            <?php
            }
          }
        ?>
    </div>

	<div class="col-lg-9">
	</div>
	
</div>