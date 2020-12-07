<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plans extends MX_Controller {
    
    var $toolbar_buttons = [];
    
    public function __construct () {
	    $config = ['admin/config_admin'];
	    $models = ['admin/plans_model'];
	    $this->common_model->autoload_resources ($config, $models);
	    
	    // Toolbar Buttons
        $this->toolbar_buttons['<i class="fa fa-download"></i> Import From ITS']= 'admin/plans/its_test_plans';
        //$this->toolbar_buttons['<i class="fa fa-plus-circle"></i> New Plan']= 'admin/plans/create_plan';
        //$this->toolbar_buttons['<i class="fa fa-list"></i> Categories']= 'admin/plans/plan_categories';
        //$this->toolbar_buttons['<i class="fa fa-plus"></i> Create Category']= 'admin/plans/create_category';
	}	
	
	
	/*-----===== Test Plans =====-----*/	
	// Categories
	public function plan_categories () {
		/* Breadcrumbs */ 
		$data['bc'] = array ('Dashboard'=>'home/admin/dashboard');
		$data['toolbar_buttons'] = array (
		    '<i class="fa fa-plus-circle"></i> New Category'=>'plans/admin/create_category',
		    '<i class="fa fa-list"></i> Plans'=>'plans/admin/plans'
		    );
		    
		$data['page_title'] = 'Plan Categories';
		
		// Get all test categories from MASTER database
		$result = [];
		$data['categories'] = $this->plans_model->test_plan_categories ();

		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('test_plan_categories', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	    
	}
	
	// Create/Edit Category
	public function create_category ($category_id=0) {
		/* Breadcrumbs */ 
		$data['bc'] = array ('Categories'=>'plans/admin/plan_categories');
		$data['toolbar_buttons'] = array (
		    '<i class="fa fa-plus-circle"></i> New Category'=>'plans/admin/create_category',
		    '<i class="fa fa-list"></i> Categories'=>'plans/admin/plan_categories');
		$data['page_title'] = 'Plan Category';
		$data['category_id'] = $category_id;
		$data['category'] = $this->plans_model->get_category ($category_id);

		$data['script'] = $this->load->view(SCRIPT_PATH  . 'plans/create_category', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('create_category', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);	    
	}
	
	// List All Plans
	public function index ($category_id=0) {

		/* Breadcrumbs */ 
		$data['bc'] = array ('Dashboard'=>'admin/home/dashboard');
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['page_title'] = 'Test Plans';
		$data['sub_title'] = 'All Test Plans';
		
		// Get all test categories from MASTER database
		$result = [];
		$plans = $this->plans_model->test_plans ($category_id);
		if (! empty ($plans)) {
			foreach ($plans as $p) {
				$tests = $this->plans_model->tests_in_plan ($p['plan_id']); 
				if (!empty($tests)) {
					$num_tests = count ($tests);
				} else {
					$num_tests = 0;
				}
				$p['active_subscribers'] = $this->plans_model->active_subscribers($p['plan_id']);
				$p['tests_in_plan'] = $num_tests;
				$p['tests'] = $tests;
				$result[] = $p;
			}
		}
		$data['plans'] = $result;	
		$data['category_id'] = $category_id;	
		$data['categories'] = $this->plans_model->test_plan_categories ();
		$category = $this->plans_model->get_category ($category_id);
		if ($category) {
			$data['sub_title'] = $category['title'] . ' Test Plans';
		}
		
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('plans/index', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}	
	
	// Create Test Plan
	public function create_plan ($plan_id=0) {	
		
		$data['plan_id'] = $plan_id;

		/* Breadcrumbs */ 
		$data['bc'] = array ('Plans'=>'admin/plans/index');
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['page_title'] = 'Test Plans';
		$data['sub_title'] = 'Create Test Plans';
		
		
		$data['categories'] = $this->plans_model->test_plan_categories ();
		$data['plan'] = $this->plans_model->get_plan ($plan_id);
		//$data['toolbar_buttons']['buttons'] = array ('<a data-toggle="modal" href="#add_plan" class=""><i class="fa fa-plus"></i> Add Plan</a>');
		
		//$data['script'] = $this->load->view(SCRIPT_PATH  . 'plans/create_category', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('plans/create_plan', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}	
	
	// Tests in Test-Plan
	public function tests_in_plan ($plan_id=0) {
		
		$data['plan_id'] = $plan_id;

		/* Breadcrumbs */ 
		$data['bc'] = array ('Test Plans'=>'admin/plans/index');
		$data['toolbar_buttons'] = array ();
		
		// Get all test categories from MASTER database
		$data['results'] = $this->plans_model->tests_in_plan ($plan_id);
		
		// Get added test categories
		$data['plan'] = $plan = $this->plans_model->get_plan ($plan_id);

		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['page_title'] = 'Test Plans';
		$data['sub_title'] = 'Tests Available In Plan';
		
		//$data['script'] = $this->load->view('admin/scripts/tests_in_plan', $data, true);

		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('plans/tests_in_plan', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}	

	// Tests in Test-Plan
	public function add_tests ($plan_id=0, $category_id=0, $offset=0) {		
		
	    $data['cat'] = $cat = $this->common_model->node_details ($category_id, SYS_TREE_TYPE_TEST);
		if ($cat['level'] == TEST_LEVEL_YEAR) {
			$cid = $cat['parent_id'];
		} else {
			$cid = $category_id;
		}
		$data['categories'] = $categories = $this->common_model->get_child_levels ($cid, "test_categories");

	    $data['breadcrumbs'] = $this->common_model->node_name_bc ($category_id, SYS_TREE_TYPE_TEST);
	
		// Get all tests not added in this plan
		$data['tests'] = $tests = $this->plans_model->tests_not_in_plan ($plan_id, $category_id, $offset);
		
		// Get added test categories
		$data['plan'] = $plan = $this->plans_model->get_plan ($plan_id);

		/* Breadcrumbs */ 
	    $data['cat'] = $cat = $this->common_model->node_details ($category_id, SYS_TREE_TYPE_TEST);
		if ($category_id > 0) {
    		$data['bc'] = array ('One Level Up'=>'plans/admin/add_tests/'.$plan_id.'/'.$cat['parent_id']);    		
		} else {
			$data['bc'] = array ($plan['title']=>'plans/admin/tests_in_plan/'.$plan_id);
		}

		$all_tests = $this->plans_model->tests_not_in_plan ($plan_id, $category_id, $offset, true);
		if (! empty ($all_tests)) {
			$count_tests = count ($all_tests);
		} else {
			$count_tests = 0;
		}
		$data['count_tests'] = $count_tests;

		/* Pagination */
		$this->load->library('pagination');
		$config['base_url'] = site_url('plans/admin/add_tests/'.$plan_id.'/'.$category_id);
		$config['total_rows'] = $count_tests;
		$config['per_page'] = RECORDS_PER_PAGE; 
		$data['offset'] = $offset;

		$this->pagination->initialize($config);

		$data['page_title']  = 'Add Tests In Plan';
		$data['sub_title']  = $plan['title'];
		$data['category_id'] = $category_id;
		$data['plan_id'] 	 = $plan_id;
		
		$data['script'] = $this->load->view(SCRIPT_PATH  . 'tests/tests_in_plan', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('add_tests', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}	


	/*-----===== Import From ITS =====-----*/
	public function its_test_plans () {
		/* Breadcrumbs */ 
		$data['bc'] = array ('Test Plans'=>'admin/plans/index');
		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['page_title'] = 'Test Plans';
		$data['sub_title'] = 'Import Plans From ITS';
		
		// Get all test categories from MASTER database
		$data['categories'] = $this->plans_model->its_test_plan_categories ();

		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('plans/its_test_plans', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	    
	}


}