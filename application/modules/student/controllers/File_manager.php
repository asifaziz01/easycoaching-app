<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class File_manager extends MX_Controller{
    public function __construct(){
        // Load Config and Model files required throughout Users sub-module
        $config = ['student/config_student'];
        $models = ['files_model'];
        $this->common_model->autoload_resources($config, $models);

        $cid = $this->uri->segment (4);        
        
        // Security step to prevent unauthorized access through url
        if ($this->session->userdata ('is_admin') == TRUE) {
        } else {
            if ($cid == true && $this->session->userdata ('coaching_id') <> $cid) {
                $this->message->set ('Direct url access not allowed', 'danger', true);
                redirect ('student/home/dashboard');
            }
        }
    }


    public function index($coaching_id=0, $member_id=0){
        if($member_id == 0){
            $member_id = $this->session->userdata('member_id');
        }
        if($coaching_id == 0){
            $coaching_id = intval($this->session->userdata('coaching_id'));
        }
        $data['bc'] = array ('Browse Plans'=>'student/home/dashboard/');
        //echo MAX_STORAGE; echo MAX_FILE_SIZE;
        $used_storage = $this->files_model->get_used_storage($coaching_id, $member_id);
        $fromat_max = $this->files_model->formatSizeUnits(MAX_STORAGE);
        $fromat_used = $this->files_model->formatSizeUnits($used_storage);
        $data['upload_url'] = 'student/file_manager/upload/'.$coaching_id.'/'.$member_id;
        $data['storage_max'] = $this->files_model->formatSizeUnits(MAX_STORAGE, false);
        $data['storage_used'] = $this->files_model->formatSizeUnits($used_storage, false);
        $data['storage_free'] = MAX_STORAGE - $used_storage;
        $data['free_percent'] = number_format(($data['storage_free']/MAX_STORAGE)*100, 2);
        if($data['free_percent']>60){
            $data['free_bar_color'] = "bg-success";
        }else if($data['free_percent']>30 && $data['free_percent']<=60){
            $data['free_bar_color'] = "bg-warning";
        }else{
            $data['free_bar_color'] = "bg-danger";
        }
        $data['storage_percent'] = number_format(($used_storage/MAX_STORAGE)*100, 2);
        $data['storage_label'] = "Used $fromat_used of $fromat_max";
        $files_data = $this->files_model->get_uploaded_files($coaching_id, $member_id);
        $data['results'] = $files_data['result'];
        $data['total_files'] = ($files_data['total'])?$files_data['total']:0;
        $upload_dir = $this->config->item ('upload_dir').'filemanager/'.$coaching_id.'/'.$member_id.'/';
        if (!is_dir($upload_dir)) {
            @mkdir ($upload_dir, 0755, true);
        }
        /*
        $data['upload_dir'] = $upload_dir;
        $allFiles = scandir($upload_dir);
        $data['my_uploaded_files'] = array_values(array_diff($allFiles, array('.', '..')));
        */
        $data['shared_files'] = $this->files_model->get_shared_files($member_id);
        $data['coaching_id'] = $coaching_id;
        $data['member_id'] = $member_id;
        $data['page_title']  = 'Files';
        $data['script'] = $this->load->view ('file_manager/scripts/index', $data, true);

        $this->load->view(INCLUDE_PATH  . 'header', $data);
        $this->load->view('file_manager/index', $data);
        $this->load->view(INCLUDE_PATH  . 'footer', $data);
    }
    public function upload($coaching_id=0,$member_id=0){
        if($coaching_id == 0){
            $coaching_id = intval($this->session->userdata('coaching_id'));
        }
        if($member_id == 0){
            $member_id = $this->session->userdata('member_id');
        }
        $data['bc'] = array ('File Manager'=> '/student/file_manager/index/'.$coaching_id.'/'.$member_id);

        $data['coaching_id'] = $coaching_id;
        $data['member_id'] = $member_id;
        $data['page_title']  = 'Upload File';
        $data['script'] = $this->load->view ('file_manager/scripts/upload', $data, true);

        $this->load->view(INCLUDE_PATH  . 'header', $data);
        $this->load->view('file_manager/upload', $data);
        $this->load->view(INCLUDE_PATH  . 'footer', $data);
    }
    public function share($coaching_id=0, $member_id=0, $file_id=0){
        if($coaching_id == 0){
            $coaching_id = intval($this->session->userdata('coaching_id'));
        }
        if($member_id == 0){
            $member_id = intval($this->session->userdata('member_id'));
        }
        if($file_id == 0){
            $file_id = intval($this->input->post('file_id'));
        }
        $data['bc'] = array ('File Manager'=> '/student/file_manager/index/'.$coaching_id.'/'.$member_id);
        $data['fileinfo'] = $this->files_model->get_uploaded_file($file_id, $member_id);
        $data['filesize'] = $this->files_model->formatSizeUnits($data['fileinfo']['size']);
        $data['share_count'] = $this->files_model->share_count($file_id);
        $data['shared_users'] = $this->files_model->shared_users($file_id);
        $data['users']    = $this->files_model->not_shared_users($coaching_id, $file_id);
        $data['page_title']  = 'Share File';
        $data['coaching_id'] = $coaching_id;
        $data['member_id'] = $member_id;
        $data['file_id'] = $file_id;
        $data['script'] = $this->load->view ('file_manager/scripts/share', $data, true);

        $this->load->view(INCLUDE_PATH  . 'header', $data);
        $this->load->view('file_manager/share', $data);
        $this->load->view(INCLUDE_PATH  . 'footer', $data);
    }
}
