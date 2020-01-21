<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	public $user, $meta = [], $site_name;
	function __construct(){
		parent::__construct();
		// $this->load_default();
		$this->user = $this->ion_auth->user()->row();
	}
	public function index(){
		
	}
	public function send_mail($args = []){
		if(is_array($args) && count($args) > 0){
			$args = (object) $args;
			$this->load->library('email');
			$settings = $this->_settings(['smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'site_name']);		
			$config['protocol']     = 'smtp';
			$config['smtp_host']    = "ssl://{$settings->smtp_host}";
			$config['smtp_port']    = $settings->smtp_port;
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = $settings->smtp_user;
			$config['smtp_pass']    = $settings->smtp_password;
			$config['charset']      = 'iso-8859-1';
			$config['newline']      = "\r\n";
			$config['mailtype']     = 'html'; // or html
			$config['validation']   = TRUE; // bool whether to validate email or not
			if(isset($args->config) && is_array($args->config)){
				$config = array_merge($config, $args->config);
			}
	    $this->email->initialize($config);
	    $from  = 'no-replay';
	    if(isset($args->from) && is_array($args->from)){
	    	$from = implode(",", $args->from);
	    }
	    else if(isset($args->from)){
	    	$from = $args->from;
	    }
	    $this->email->from($from);
			$this->email->subject(($args->subject??""));
			$this->email->message(($args->message??""));
			if($args->to){
				$this->email->to($args->to);
				return $this->email->send();
			}
		}
	}
	public function pageAccess($args = ''){
		if($this->ion_auth->logged_in()){
			$group = $this->ion_auth->get_users_groups($this->user->id);
			if(!empty($args) && !$this->ion_auth->in_group($args)){
				if(!$this->ion_auth->is_admin()){
					redirect(base_url('access-denied'), 'refresh');
				}
			}
		}
		else{
			redirect(base_url('login'), 'refresh');
		}
	}
	public function load_default(){		
		$settings        = $this->_settings(array('site_logo', 'site_name'));
		// echo $this->_settings('site_name');
		$this->site_name = $settings->site_name;
		$this->site_logo = "application/assets/uploads/images/".$settings->site_logo;
		// $thumbnail = [ 'width' => 400, 'height' => 400 ];
		// $this->config->thumbnail  = (object) $thumbnail;
	}
	public function _settings($field_name = []){
		if(!is_array($field_name) && $field_name != ''){
			$field_name = array($field_name);
		}
		$condition = null;
		if(count($field_name) > 0){
			$condition = $field_name;
		}
		$data = $this->common_model->get_settings("settings", $condition);
		if(count($data) == 1){			
			return array_values($data)[0];
		}
		return (object) $data;
	}
	public function getDatatablePost(){
		$response 			 = [];
		$response['draw']            = $_POST['draw'];
		$response['row']             = $_POST['start'];
		$response['rowperpage']      = ($_POST['length'] >= 0)? $_POST['length'] : NULL; // Rows display per page
		$response['columnIndex']     = $_POST['order'][0]['column']; // Column index
		$response['columnName']      = $_POST['columns'][$response['columnIndex']]['data']; // Column name
		$response['columnSortOrder'] = $_POST['order'][0]['dir']; // asc or desc
		$response['searchValue']     = $_POST['search']['value']; // Search value
		return (object)$response;
	}
	protected function _get_csrf_nonce(){
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);
		return [$key => $value];
	}
	protected function _createThumbnail($con = []){
		$config      = [];
		$config      = array_merge($config, $con);
		$config['image_library']  = 'gd2';
	  $config['maintain_ratio'] = FALSE;
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();
	}
	protected function _do_upload($file_name, $upload_path = 'application/assets/uploads/', $con = [] ){
		$config      = [];
		$upload_path = FCPATH.$upload_path;
		$config      = array_merge($config, $con);
		
		if(!is_dir($upload_path)) {
			@mkdir($upload_path, 0777, TRUE);
			@mkdir("{$upload_path}thumbnail/", 0777, TRUE);
		}
		$config['upload_path']          = $upload_path;
    $config['allowed_types']        = 'gif|jpg|png';
    $config['max_size']             = 1024;
    $this->load->library('upload', $config);
    if ( ! $this->upload->do_upload($file_name)){
      $response = [
      	'status' => FALSE,
      	"error"  => $this->upload->display_errors()
      ];
    }
    else{
    	$response = [
      	'status' => TRUE,
      	"data"  => $this->upload->data()
      ];
    }
    return (object) $response;
	}
	protected function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')){
			return TRUE;
		}
		return FALSE;
	}
	public function editPageValidate($edit){
		$exist_details = $this->common_model->fetchExistingDetails("*", $edit->table_name, $edit->condition);
		if(!$exist_details){
			show_404();
			exit();
		}
		else{
			return $exist_details;
		}
	}
	public function setMeta($meta = []){		
		$this->meta = (object)$meta;
	}
	
}