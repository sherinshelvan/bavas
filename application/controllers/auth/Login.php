<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();		
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		if ($this->ion_auth->logged_in()){
			redirect(base_url('dashboard'), 'refresh');
		}
		$this->data['page_heading'] = "Login";
		$this->load->view('admin/includes/header');
		$this->form_validation->set_rules('username', 'Username', "trim|required|xss_clean");
		$this->form_validation->set_rules('password', 'Password', "trim|required|xss_clean");
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$remember = (bool)$this->input->post('remember');
			if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'), $remember)){
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('toast_message', $this->ion_auth->messages());
				redirect('/', 'refresh');
			}
			else{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('toast_message', $this->ion_auth->errors());
				redirect('/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		if($this->data['message']){
			$this->session->set_flashdata('toast_message', $this->data['message']);

		}
		$this->buildForm($exist_details = []);
		$this->load->view('auth/login_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function buildForm(){
		$this->data['username'] = [
			'name'     => 'username',
			'id'       => 'username',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('username') ?: (!count($_POST) && isset($exist_details->username)? $exist_details->username : '') ) 
		];
		$this->data['password'] = [
			'name'     => 'password',
			'id'       => 'password',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'password' 
		];
		$this->data['csrf'] = $this->_get_csrf_nonce();
		$this->data['submit'] = [
			'name'    => 'submit',
			'value'   => 'submit',
			'type'    => 'submit',
			'content' => 'Login<i class="material-icons right">send</i>',
			'class'   => "waves-effect waves-light btn deep-purple darken-2",
		];
	}
}
