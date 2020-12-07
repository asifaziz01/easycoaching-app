<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class File_actions extends MX_Controller
{
    public function __construct(){
        // Load Config and Model files required throughout Users sub-module
        $config = ['student/config_student'];
        $models = ['files_model'];
        $this->common_model->autoload_resources($config, $models);
        $this->load->helper('download');
        $this->load->helper('directory');
        $this->load->helper('file');
    }
    public function do_file_upload($coaching_id, $member_id){
        if($coaching_id == 0){
            $coaching_id = intval($this->session->userdata('coaching_id'));
        }

        $upload_dir = $this->config->item ('upload_dir').'filemanager/'.$coaching_id.'/'.$member_id.'/';
        $file_name = $this->config->item ('coaching_logo');
        $file_path = $upload_dir . $file_name;
        
        $options['upload_path'] = $upload_dir;
        $options['allowed_types'] = 'gif|jpg|jpeg|png|txt|pdf|doc|docx|xls|ppt';
        $options['max_size']    = MAX_FILE_SIZE;
        // load upload library
        $this->load->library ('upload', $options);
        if ($this->upload->do_upload('file')){
            $file = array('info' => $this->upload->data());
            $data = array(
                'filename' => $file['info']['file_name'],
                'mime_type' => $file['info']['file_type'],
                'icon' => $this->files_model->get_file_icon($file['info']['file_type']),
                'size' => filesize($file['info']['full_path']),
                'coaching_id' => $coaching_id,
                'uploaded_by' => $member_id,
                'upload_on' => time(),
                'path' => $upload_dir
            );
            $resposne = $this->files_model->save_uploaded_file($data);
            $this->output->set_content_type("application/json");
            $this->output->set_output(json_encode(array('status'=>true, 'message'=>$resposne)));
        }
        else{
            $this->output->set_content_type("application/json");
            $this->output->set_output(json_encode(array('status'=>false, 'error'=> $this->upload->display_errors())));
        }
    }
    public function rename_file(){
        $this->output->set_content_type("application/json");
        if($this->input->method(true) === 'POST'){
            if(rename($this->input->post('old_path'),$this->input->post('new_path'))){
                if($this->files_model->rename_uploaded_file()){
                    $this->output->set_output(json_encode(array('status'=>true, 'message'=> "File renamed successfully.")));
                }else{
                    //undo rename
                    rename($this->input->post('new_path'),$this->input->post('old_path'));
                    $this->output->set_output(json_encode(array('status'=>false, 'error'=> "<strong>Connection Error!</strong> File Rename Failed.")));
                }
            }else{
                $this->output->set_output(json_encode(array('status'=>false, 'error'=> "File Rename Failed.")));
            }
        }else{
            $this->output->set_output(json_encode(array('status'=>false, 'error'=>"Request Method is not valid." )));
        }
    }
    public function delete_file(){
        $this->output->set_content_type("application/json");
        if($this->input->method(true) === 'POST'){
            if($this->files_model->delete_uploaded_file()){
                if(unlink($this->input->post('file_path'))){
                    $this->output->set_output(json_encode(array('status'=>true, 'message'=> "File deleted successfully.")));
                }
            }
        }else{
            $this->output->set_output(json_encode(array('status'=>false, 'error'=>"Request Method is not valid." )));
        }
    }
    public function share_file($coaching_id=0, $member_id=0, $file_id=0){
        $this->output->set_content_type("application/json");
        if($this->input->method(true) === 'POST'){
            $members = $this->input->post('members');
            if($this->files_model->share_file($coaching_id, $member_id, $file_id, $members)){
                $this->output->set_output(json_encode(array('status'=>true, 'message'=> "File shared successfully.")));
            }else{
                $this->output->set_output(json_encode(array('status'=>false, 'error'=> "<strong>Connection Error!</strong> File sharing failed.")));
            }
        }else{
            $this->output->set_output(json_encode(array('status'=>false, 'error'=>"Request Method is not valid." )));
        }
    }
    public function donwload_file($file_id){
        $member_id = $this->session->userdata('member_id');
        if($this->files_model->has_access($member_id, $file_id)){
            $file_path = $this->files_model->get_file_path($file_id);
            $file_name = $this->files_model->get_file_name($file_id);
            $data = file_get_contents($file_path); // Read the file's contents
            force_download($file_name, $data);
        }else{
            exit('Sorry your are not allowed access this File.');
        }
    }
    public function view_file($file_id){
        $member_id = $this->session->userdata('member_id');
        if($this->files_model->has_access($member_id, $file_id)){
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $file_path = $this->files_model->get_file_path($file_id);
            $file_contents = file_get_contents($file_path);
            $file_type = $file_info->buffer($file_contents);
            header("content-type: $file_type");
            echo $file_contents;
        }else{
            exit('Sorry your are not allowed access this File.');
        }
    }
}
