<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {	

	// Set config items. This will be a one time process
	public function load_defaults () {
		// get from database
		$this->load->dbutil();
		$dbs = $this->dbutil->list_databases();			
		$config = array ();			
		if ( ! empty($dbs)) {	// To check if a database exists
			$sql = $this->db->get ('sys_config');
			if ( $sql->num_rows() > 0 ) {
				$result = $sql->result_array ();
				foreach ( $result as $row ) {
					$config[$row['name']] = $row['value'];						
				}
			}
		}
		if ( empty ($config)) {
			// get from config file
			$config = $this->config->item ('general');
		}
		return $config;
	} 
	
	
	/*
		ACL MENU
	*/
	public function load_acl_menus ($role_id=0, $parent_id=0, $menu_type=MENUTYPE_SIDEMENU) {
		$this->db->where ('group_id', $role_id);
		$this->db->where ('parent_menu_id', $parent_id);
		$this->db->where ('menu_type', $menu_type);
		$this->db->where ('status', 1);
		$this->db->order_by ('menu_order', 'ASC');
		$sql = $this->db->get ('sys_menus');
		return $sql->result_array ();
	}

    public function get_tree_parameters ($type='') {
		switch ($type) {
			case SYS_TREE_TYPE_TEST:
				$sys_levels = SYS_TEST_LEVELS;
				$table = 'coaching_test_categories';
				$title = 'Test Categories';
			break;
			case SYS_TREE_TYPE_STUDENT:
				$sys_levels = SYS_STUDENT_LEVELS;
				$table = 'member_categories';
				$title = 'Sessions';
			break;
			case SYS_TREE_TYPE_QB:
				$sys_levels = SYS_QB_LEVELS;
				$table = 'question_categories';
				$title = 'Lessons';
			break;
			case SYS_TREE_TYPE_LESSON:
				$sys_levels = 0;
				$table = 'lesson_pages';
				$title = 'Lesson Contents';
			break;
			default:
				$sys_levels = '';
				$table = '';
				$title = '';
			break;			
		}
		$result = array (
						'table'=>$table,
						'sys_levels'=>$sys_levels,
						'title'=>$title,
						); 
		return $result ;
	}
	public function all_children ($node_id, $category) {
		$cat_params = $this->get_tree_parameters ($category);
		$table 	 = $cat_params['table']; 
		$this->child_nodes = array ();
		$top = $this->get_child_levels ($node_id, $table);
		if ( ! empty ($top)) {			
			foreach ($top as $results) {
				$node_id = $results['id'];
				$this->child_nodes[] = $node_id;
				$this->get_all_children ($node_id, $category);
			}
		}
		return $this->child_nodes;
	}
	public function get_top_levels ($last_level=[], $category=TREE_TYPE_TEST) {
	    $levels = [];
	    $level3 = [];
		$level2 = [];
		$level1 = [];
		$i=1;
		if (! empty ($last_level)) {

			$cat_params = $this->categroy_parameters ($category);
			$cat = $cat_params['cat'];

			// Last level details (Level 4)
			$last_level = array_unique ($last_level);
			foreach ($last_level as $id) {
				$node = $this->node_details ($id, $cat);
				$level3[] = $node['parent_id'];
				$levels[$node['level']][] = $node;
			}

			// Second Last level (Level 3)
			$level3 = array_unique ($level3);
			foreach ($level3 as $id) {
				$node = $this->node_details ($id, $cat);
				$level2[] = $node['parent_id'];
				$levels[$node['level']][] = $node;
			}
			
			// Third Last level (Level 2)
			$level2 = array_unique ($level2);
			foreach ($level2 as $id) {
				$node = $this->node_details ($id, $cat);
				$level1[] = $node['parent_id'];
				$levels[$node['level']][] = $node;
			}			

			// Fourth Last level (Level 1)
			$level1 = array_unique ($level1);
			foreach ($level1 as $id) {
				$node = $this->node_details ($id, $cat);
				$levels[$node['level']][] = $node;
			}			
		}

		return $levels;
	}
	public function get_all_children ($node_id, $category) {
		$cat_params = $this->get_tree_parameters ($category);
		$table 	 = $cat_params['table'];
		$top = $this->get_child_levels ($node_id, $table);
		if ( ! empty ($top)) {
			foreach ($top as $results) {
				$node_id = $results['id'];
				$this->child_nodes[] = $node_id;
				$this->get_all_children ($node_id, $category);
			}
		}
	}

    /* Dynamically load default resources */
	public function autoload_resources ($config=[], $models=[]) {
		if ( ! empty ($config)) {
			foreach ($config as $file) {
				// Load Config Files
				$this->load->config ($file);
			}
		}

		if ( ! empty ($models)) {
			foreach ($models as $file) {
				// Load Config Files
				$this->load->model ($file);
			}
		}
	}

	public function get_child_levels ($id="", $table="", $status='') {
		if ( $table != "" ) {
			if ($status != '') {
				$this->db->where ('status', $status);				
			}
			if ($table == 'coaching_test_categories') {
				if ($this->session->userdata('coaching_id')) {
					$this->db->where ('coaching_id', $this->session->userdata('coaching_id'));
				}
				if ($this->session->userdata('plan_id')) {
					//$this->db->where ('plan_id', $this->session->userdata('plan_id'));
				}
			}
			$this->db->where ('parent_id', $id);
			$this->db->order_by ('title', 'ASC');
			$sql = $this->db->get ($table);
			if ($sql->num_rows () > 0 ) {
				$result = $sql->result_array ();
				return $result;
			} else { 
				return false;
			}
		} else {
			return false;
		}
	}

	public function node_details ($id="", $type="") {
		if ($type != "" && $id != "") {
			$tree_param = $this->get_tree_parameters ($type);
			$table = $tree_param['table'];
			$this->db->where ('id', $id);
			$sql = $this->db->get ($table);
			if ($sql->num_rows () > 0 ) {
				$row = $sql->row_array ();
				return $row;
			} else { 
				return false;
			}
		} else {
			return false;
		}
	}

	/*
		GET SYSTEM PARAMETERS
		-------============-------
	*/
	public function get_sys_parameters ($category='') {
		$result = false;
		if ($category != '') {
			$this->db->where ('category', $category);
			$this->db->where ('status', 1);
			$this->db->order_by ('paramorder', 'ASC');
			$sql = $this->db->get ('sys_parameters');
			if ($sql->num_rows () > 0 ) {
				$result = $sql->result_array ();
			}
		}
		return $result;
	}
	
	
	public function sys_parameter_name ($category='', $param=0) {
		$result = false;
		$this->db->where ('category', $category);
		$this->db->where ('status', 1);
		$this->db->where ('paramkey', $param);
		$this->db->or_where ('paramval', $param);
		$sql = $this->db->get ('sys_parameters');
		$result = $sql->row_array ();
		return $result; 
	}

	public function generate_token ($member_id=0) {

		$this->load->library('encryption');

		$this->db->select ('login');
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('members');
		if ($sql->num_rows() > 0 ) {
			$row = $sql->row_array ();
			$login = $row['login']; 
			$cipher_token = $this->encryption->encrypt ($login);
			return $cipher_token; 
		} else {
			return false;
		}
	}
	
	public function get_token ($member_id=0) {
		$this->db->select ('token');
		$this->db->where ('member_id', $member_id);
		$sql = $this->db->get ('members');
		$row = $sql->row_array ();
		$token = $row['token']; 
		return $token; 
	}
	
	/* Send Email Function */
	public function send_email ($send_to='', $subject=SITE_TITLE, $message='', $from=CONTACT_EMAIL, $title=SITE_TITLE) {
		/*
		$this->load->library ('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from( $from, $title);
		$this->email->to ($send_to);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
		*/
	}

	public function categroy_parameters ($category='') {
		switch ($category) {
			case TREE_TYPE_TEST:
				$sys_cat = SYS_TEST_LEVELS;
				$table = 'coaching_test_categories';
				$cat = SYS_TREE_TYPE_TEST;
			break;
			case TREE_TYPE_STUDENT:
				$sys_cat = SYS_STUDENT_LEVELS;
				$table = 'member_categories';
				$cat = SYS_TREE_TYPE_STUDENT;
			break;
			default:
				$sys_cat = SYS_QB_LEVELS;
				$table = 'question_categories';
				$cat = SYS_TREE_TYPE_QB;
			break;			
		}
		$result = array (
						'table'=>$table,
						'sys_cat'=>$sys_cat,
						'cat'=>$cat,
						); 
		return $result ;
	}

	public function generate_coaching_id ($coaching_id=0) {
		
		$id = 0;
		if ($coaching_id > 0) {
			// This means coaching record is already inserted and the primary key is passed as coaching_id
			$num = $coaching_id;
		} else {
			// Coaching record is not yet inserted, show a 
			$this->db->select_max ('id');
			$sql = $this->db->get ('coachings');
			if ($sql->num_rows () > 0) {
				$row = $sql->row_array ();
				$id = $row['id'];
			} else { 
				$id = 0;
			}
			$num = $id + COACHING_ID_INCREMENT;
		}
		$suffix = str_pad ($num, 5, "0", STR_PAD_LEFT);
		$ref_id = COACHING_ID_PREFIX1 . COACHING_ID_PREFIX2 . $suffix;
		return $ref_id;
	}

}