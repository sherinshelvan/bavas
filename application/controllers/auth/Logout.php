<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Logout extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();		
	}
	public function index(){
		$this->ion_auth->logout();
		redirect(base_url('login'), 'refresh');
	}
}
