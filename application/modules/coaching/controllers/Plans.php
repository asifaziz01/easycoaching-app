<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Plans extends MX_Controller {
	
    var $toolbar_buttons = []; 

    public function __construct () {
	    $config = ['config_coaching'];
	    $models = ['test_plans_model', 'plans_model'];
	    $this->common_model->autoload_resources ($config, $models);

        $cid = $this->uri->segment (4);

        // Security step to prevent unauthorized access through url
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
            if ($cid == true && $this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('coaching/home/dashboard');
            }
        }

	}
	
	
	// List All Plans
	public function index ($coaching_id=0, $category_id=0) {
		
		/* Breadcrumbs */ 
		if ($category_id > 0) {
			$data['bc'] = array ('Dashboard'=>'coaching/plans/index/'.$coaching_id);
		} else {
			$data['bc'] = array ('Dashboard'=>'coaching/home/dashboard/'.$coaching_id);
		}

		$data['toolbar_buttons'] = $this->toolbar_buttons;
		$data['page_title'] = 'Test Plans';
		$data['sub_title'] = 'All Test Plans';
		
		// Get all test categories from MASTER database
		$result = [];
		$plans = $this->plans_model->test_plans ($category_id);
		if (! empty ($plans)) {
			foreach ($plans as $p) {				
				$tests = $this->plans_model->tests_in_plan ($p['plan_id']); 
				if (! empty($tests)) {
					$num_tests = count ($tests);
					$p['tests_in_plan'] = $num_tests;
					$p['tests'] = $tests;
					$result[] = $p;
				}
			}
		}
		$data['plans'] = $result;	
		$data['coaching_id'] = $coaching_id;
		$data['category_id'] = $category_id;	
		$data['categories'] = $this->plans_model->test_plan_categories ();
		if ($category_id > 0) {
			$category = $this->plans_model->get_category ($category_id);
			$data['sub_title'] = $category['title'] . ' Test Plans';
		}
		
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('plans/index', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}
	
	
	// Tests in Test-Plan
	public function tests_in_plan ($coaching_id=0, $plan_id=0, $category_id=0, $offset=0) {
		
		$data['plan_id'] = $plan_id;
		$data['coaching_id'] = $coaching_id;
		$data['category_id'] = $category_id;
	    /* Breadcrumbs */ 
		$data['bc'] = array ('Test Plans'=>'coaching/plans/index/'.$coaching_id.'/'.$plan_id);
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

	public function my_cart () {
		$this->add_to_cart ();
	}
	
	public function add_to_cart ($coaching_id=0, $plan_id=0) {
		
		$this->load->model ('settings/settings_model');
		
		// Get items added in cart
		$cart = $this->session->userdata ('cart');
		
		if ($plan_id > 0) {
			// Add item to cart-array
			$cart[] = $plan_id;
			// Remove duplicate entries
			$cart = array_unique ($cart);
			// Update cart
			$this->session->set_userdata ('cart', $cart);
		}
		// Get cart items
		$cart = $this->session->userdata ('cart');		
		
		/* Breadcrumbs */ 
		$data['bc'] = array ('Test Plans'=>'frontend/tests/buy_tests');
		$data['toolbar_buttons'] = array ();		
		
		// Get added test categories
		$num = 1;
		if (! empty ($cart)) {
			foreach ($cart as $plan_id) {
				$plan = $this->plans_model->get_plan ($plan_id);
				$data['plans'][] = $plan;
				$num++;
			}
		}

		$data['discounts'] = $this->config->item ('plan_discount');

		$data['page_title'] 	= 'My Cart ';
		$data['member_id'] 		= $this->session->userdata ('member_id');
		$data['plan_id'] 		= $plan_id;
		$data['gst_slab'] 		= $this->settings_model->get_config('gst_slab');
		$data['duration_start'] = 3;
		$data['num'] 			= $num;
		
		$data['script'] = $this->load->view(SCRIPT_PATH  . 'frontend/add_to_cart', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('add_to_cart', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);
	}
	
	public function proceed_to_subscribe ($coaching_id=0, $plan_id=0, $member_id=0) {
		
		$this->load->config ('config_payments');
		
		$data['member_id'] 		= $member_id;
		$data['plan_id'] 		= $plan_id;
		$data['plan'] 			= $plan = $this->plans_model->get_plan ($plan_id);
		$data['user'] 			= $user = $this->users_model->get_user ($member_id);
		$data['duration'] 		= $duration = $this->input->post ('duration');
		$data['amount'] 		= $amount = $this->input->post ('final_amount');
		
		$data['key'] 			= $key = MERCHANT_KEY;
		$data['salt'] 			= $salt = MERCHANT_SALT;
		$data['firstname'] 		= $user['first_name'];
		$data['email'] 			= $user['email'];
		$data['phone'] 			= $user['primary_contact'];
		$data['txnid'] 			= $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
		$data['productinfo'] 	= $plan['short_description'];
		
		// Hash Sequence
		$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';
		foreach($hashVarsSeq as $hash_var) {
		  $hash_string .= isset($data[$hash_var]) ? $data[$hash_var] : '';
		  $hash_string .= '|';
		}

		$hash_string .= $salt;
		$hash = strtolower(hash('sha512', $hash_string));
		$action = PAYU_BASE_URL . '/_payment';	
		
		$data['hash_string'] = $hash_string;
		$data['hash'] = $hash;
		$data['action'] = $action;

		$data['surl'] = $surl = 'frontend/tests/subscribe_plan/'.$plan_id;
		$data['furl'] = $furl = 'frontend/tests/buy_tests';
		
		// Reset cart before proceeding
		$this->session->unset_userdata('cart');


		$data['script'] = $this->load->view(SCRIPT_PATH  . 'frontend/payment_form', $data, true);
		$this->load->view(INCLUDE_PATH  . 'header', $data);
		$this->load->view('proceed_to_subscribe', $data);
		$this->load->view(INCLUDE_PATH  . 'footer', $data);		
	}
	
	public function subscribe_plan ($coaching_id=0, $category_id=0, $plan_id=0) {		
		if ($plan_id > 0) {
			$member_id = $this->session->userdata ('member_id');
			$this->test_plans_model->subscribe_plan ($coaching_id, $plan_id, $member_id);
			$this->message->set ('Plan subscribed successfully', 'success', true);
			redirect ('coaching/plans/index/'.$coaching_id.'/'.$category_id);
		} else {
			$this->message->set ('Plan not found', 'danger', true);
			redirect ('coaching/plans/index/'.$coaching_id.'/'.$category_id);
		}
	}

}