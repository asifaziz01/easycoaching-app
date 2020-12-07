<div class="card mb-2 d-none">
	<div class="card-body">
		<?php echo form_open ('admin/coaching_actions/search_coaching', ['id'=>'search-form']); ?>
			<div class="row">
				<div class="col-md-6">
					<input type="text" name="search_text">
					<input type="submit" name="search" value="Search">
				</div>
				
			</div>
		</form>
	</div>
</div>

<div id="coaching-list">
	<?php echo $this->load->view ('coaching/inc/index', $data); ?>
</div>