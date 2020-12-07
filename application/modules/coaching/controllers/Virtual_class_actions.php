 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Virtual_class_actions extends MX_Controller {	

	var $toolbar_buttons = [];

	public function __construct () {		
	    // Load Config and Model files required throughout Users sub-module
	    $config = ['config_coaching', 'config_virtual_class'];
	    $models = ['virtual_class_model', 'users_model', 'coaching_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    $this->load->helper ('string');

	    $cid = $this->uri->segment (4);
	}


	/*-----===== VC Categories =====-----*/
	public function add_category ($coaching_id=0, $category_id=0) {

		$this->form_validation->set_rules ('title', 'Title', 'required');

		if ($this->form_validation->run () == true) {
			$id = $this->virtual_class_model->create_category ($coaching_id, $category_id);
			if ($category_id > 0) {
				$message = 'Category updated successfully';
				$redirect = 'coaching/virtual_class/categories/'.$coaching_id;
			} else {
				$message = 'Category created successfully';
				$redirect = 'coaching/virtual_class/categories/'.$coaching_id;
			}
			$this->message->set ($message, 'success', true);
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>true, 'message'=>$message, 'redirect'=>site_url ($redirect) )));
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}	    
	}
	
	// Delete VC Category
	public function remove_category ($coaching_id=0, $category_id=0) {
		// Check if this plan is given to any coaching
		$this->virtual_class_model->remove_category ($coaching_id, $category_id);
		$this->message->set ('Category deleted successfully', 'success', true);
		redirect ('coaching/virtual_class/categories/'.$coaching_id);
	}

	/* Virtual Class */
	public function create_classroom ($coaching_id=0, $class_id=0) {
		$this->form_validation->set_rules ('class_name', 'Virtual Classroom Name', 'required|max_length[250]|trim', ['alpha_numeric'=>'Class name can contain only alphabets and numbers without spaces']);
		$this->form_validation->set_rules ('description', 'Description', 'max_length[500]|trim');
		$this->form_validation->set_rules ('welcome_message', 'Welcome Message', 'max_length[100]|trim');

		if ($this->form_validation->run () == true) {
			$id = $this->virtual_class_model->create_classroom ($coaching_id, $class_id);
			if ($id > 0) {
				if ($class_id > 0) {
					$message = 'Classroom updated successfully';
				} else {
					$message = 'Classroom created successfully';				
				}
				$this->message->set ($message, 'success', true);
				$this->output->set_content_type("application/json");
		        $this->output->set_output(json_encode(array('status'=>true, 'message'=>'Virtual classroom created successfully. Now add participants to this classroom', 'redirect'=>site_url ('coaching/virtual_class/add_participants/'.$coaching_id.'/'.$id ) )));
			} else {
				$this->output->set_content_type("application/json");
		        $this->output->set_output(json_encode(array('status'=>false, 'error'=>'Error creating classroom.' )));
			}
		} else {
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}

	public function delete_class ($coaching_id=0, $class_id=0) {
		$this->virtual_class_model->delete_class ($coaching_id, $class_id);
		$this->message->set ('Classroom deleted successfully', 'success', true);
		redirect ('coaching/virtual_class/index/'.$coaching_id.'/'.$class_id);
	}


	public function add_participants ($coaching_id=0, $class_id=0) {
		$this->form_validation->set_rules ('users[]', 'User', 'required', ['required'=>'You have not selected any %s']);

		if ($this->form_validation->run () == true) {
			$this->virtual_class_model->add_participants ($coaching_id, $class_id);
			$this->message->set ('Participants added successfully', 'success', true);
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>true, 'message'=>'Participants added successfully', 'redirect'=>site_url ('coaching/virtual_class/add_participants/'.$coaching_id.'/'.$class_id ) )));
		} else {
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}

	public function remove_participants ($coaching_id=0, $class_id=0) {
		$this->form_validation->set_rules ('users[]', 'User', 'required', ['required'=>'You have not selected any %s']);

		if ($this->form_validation->run () == true) {
			$this->virtual_class_model->remove_participants ($coaching_id, $class_id);
			$this->message->set ('Participants removed successfully', 'success', true);
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>true, 'message'=>'Participants removed successfully', 'redirect'=>site_url ('coaching/virtual_class/participants/'.$coaching_id.'/'.$class_id ) )));
		} else {
			$this->output->set_content_type("application/json");
	        $this->output->set_output(json_encode(array('status'=>false, 'error'=>validation_errors() )));
		}
	}

	public function get_running_meetings ($coaching_id=0) {
		$meetings = $this->virtual_class_model->get_running_meetings ($coaching_id);
		$this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array('status'=>true, 'data'=>$meetings )));
	}

}