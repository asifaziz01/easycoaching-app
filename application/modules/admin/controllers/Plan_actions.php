<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_actions extends MX_Controller {

    public function __construct () {
	    $config = ['admin/config_admin'];
	    $models = ['admin/plans_model'];
	    $this->common_model->autoload_resources ($config, $models);
	}
	
	
	/*-----===== Test Plans =====-----*/
	public function create_category ($category_id=0) {

		$this->form_validation->set_rules ('title', 'Title', 'required');

		if ($this->form_validation->run () == true) {
			$id = $this->plans_model->create_category ($category_id);
			if ($category_id > 0) {
				$message = 'Category updated successfully';
				$redirect = 'admin/plans/plan_categories';
			} else {
				$message = 'Category created successfully';
				$redirect = 'admin/plans/plan_categories';				
			}
			$this->message->set ($message, 'success', true);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>site_url ($redirect) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}	    
	}
	
	// Delete Test Plan
	public function delete_category ($category_id=0) {
		
		// Check if this plan is given to any coaching
		$this->plans_model->remove_category ($category_id);
		$this->message->set ('Category deleted successfully', 'success', true);
		redirect ('admin/plans/plan_categories');
	}
	
	// Create Test Plan
	public function create_plan ($plan_id=0) {
		
		$this->form_validation->set_rules ('title', 'Title', 'required');
		$this->form_validation->set_rules ('price', 'Price', 'required|numeric');
		
		if ($this->form_validation->run () == true) {
			$id = $this->plans_model->create_plan ($plan_id);
			if ($plan_id > 0) {
				$message = 'Test plan updated successfully';
				$redirect = 'admin/plans/index';
			} else {
				$message = 'Test plan created successfully';
				$redirect = 'admin/plans/tests_in_plan/'.$id;				
			}
			$this->message->set ($message, 'success', true);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>site_url ($redirect) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}	
	
	// Delete Test Plan
	public function delete_plan ($plan_id=0) {
		
		// Check if this plan is given to any coaching
		$this->plans_model->remove_plan ($plan_id);
		$this->message->set ('Plan deleted successfully', 'success', true);
		redirect ('admin/plans/index');
	}

	// Add/Edit ITS Categories to a plan
	public function remove_tests ($plan_id=0) {
		
		$this->form_validation->set_rules ('tests[]', 'Tests', 'required');
		
		if ($this->form_validation->run () == true) {
			$this->plans_model->remove_tests ($plan_id);
			$this->message->set ("Test(s) removed from plan", 'success', true);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Test(s) removed from plan', 'redirect'=>site_url('admin/plans/tests_in_plan/'.$plan_id) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>'Select tests to remove' )));
		}
	}
	
	// Add/Edit ITS Categories to a plan
	public function add_tests ($plan_id=0, $category_id=0) {
		
		$this->form_validation->set_rules ('tests[]', 'Tests', 'required');
		
		if ($this->form_validation->run () == true) {
			$this->plans_model->add_tests ($plan_id);
			$this->message->set ("Test(s) added to plan", 'success', true);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>'Test(s) added to plan', 'redirect'=>site_url('admin/plans/add_tests/'.$plan_id.'/'.$category_id) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>'Select tests to add' )));
		}
	}
	
	// Remove ITS Categories from a plan
	public function remove_its_cat ($plan_id=0, $cat_id=0) {
		$this->tests_model->remove_its_cat ($plan_id, $cat_id);
		$this->message->set ('Category removed from plan', 'success', true);
		redirect ('admin/plans/plan_categories/'.$plan_id);
	}
	
	public function its_import_category ($category_id=0) {
		$this->plans_model->its_import_category ($category_id);
		$this->message->set ('Plans imported successfully', 'success', true);
		redirect ('admin/plans/its_test_plans');
	}
}