<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->table_name      = TBL."users";
		$this->tbl_user_groups = TBL."users_groups";
		$this->page_url        = 'users';
		$this->toast_msg_title = "users";
	}
	public function index(){
		$this->data['page_heading'] = "Users";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->load->view('admin/users/list_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function edit($edit = ""){
		$this->data['page_heading'] = "Edit Users";
		$this->data['action_url'] = "{$this->page_url}/edit/{$edit}";
		$condition = "id = ".(int)$edit;
		if($edit == '' || !$this->ion_auth->is_admin()){
			$edit = $this->user->id;
			$this->data['action_url'] = "edit-profile";
		}
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$edit
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$user = $this->ion_auth->user($edit)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($edit)->result();


		$this->form_validation->set_rules('first_name', 'First Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('last_name', 'Last Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
		if ($this->input->post('password')){
			$this->form_validation->set_rules('password', 'Password', "trim|required|xss_clean");
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', "trim|required|matches[password]|xss_clean");
		}
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$data = [
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'phone'      => $this->input->post('phone'),
				'active'     =>  $this->input->post("active")??'0'	
			];
			// update the password if it was posted
			if ($this->input->post('password')){
				$data['password'] = $this->input->post('password');
			}
			// Only allow updating groups if user is admin
			if ($this->ion_auth->is_admin()){
				// Update the groups user belongs to
				$this->ion_auth->remove_from_group('', $edit);				
				$groupData = $this->input->post('groups');
				if (isset($groupData) && !empty($groupData)){
					foreach ($groupData as $grp){
						$this->ion_auth->add_to_group($grp, $edit);
					}
				}
			}
			// check to see if we are updating the user
			if ($this->ion_auth->update($user->id, $data)){
				// redirect them back to the admin page if admin, or to the base url if non admin
				$this->session->set_flashdata('toast_message', sprintf('%s', $this->ion_auth->messages()));
				if($this->data['action_url'] == 'edit-profile'){
					redirect(base_url($this->data['action_url']), 'refresh');
					exit();
				}
				redirect($this->page_url, 'refresh');

			}
			else{
				// redirect them back to the admin page if admin, or to the base url if non admin
				$this->data['message'] = $this->ion_auth->errors();

			}
			// $this->common_model->update_data($this->table_name, $update_data, "id = {$edit}");
			
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;
		$this->data['edit'] = $edit;
		$this->buildForm($exist_details);
		$this->load->view('admin/users/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function add(){
		$this->data['page_heading'] = "Add Users";
		$this->data['action_url'] = base_url($this->page_url)."/add";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;
		$this->form_validation->set_rules('username', 'Username', "trim|required|xss_clean");
		if ($identity_column !== 'email'){
			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
		}
		$this->form_validation->set_rules('first_name', 'First Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('last_name', 'Last Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('email', 'Email', "trim|valid_email|required|xss_clean");
		if ($identity_column === 'email'){
			$this->form_validation->set_rules('email', "Email", 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', 'Phone', "trim|required|xss_clean");
		$this->form_validation->set_rules('password', 'Password', "trim|required|xss_clean");
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', "trim|required|matches[password]|xss_clean");
		
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('username');
			$password = $this->input->post('password');
			$additional_data = [
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'phone'      => $this->input->post('phone'),
				'active'     =>  $this->input->post("active")??'0'
			];
			if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data)){
				// check to see if we are creating the user
				// redirect them back to the admin page
				$this->session->set_flashdata('toast_message', $this->ion_auth->messages());
				redirect($this->page_url, 'refresh');
			}
			else{
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			}
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm();
		$this->load->view('admin/users/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function buildForm($exist_details = []){
		$this->data['username'] = [
			'name'     => 'username',
			'id'       => 'username',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('username') ?: (!count($_POST) && isset($exist_details->username)? $exist_details->username : '') ) 
		];
		$this->data['first_name'] = [
			'name'     => 'first_name',
			'id'       => 'first_name',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('first_name') ?: (!count($_POST) && isset($exist_details->first_name)? $exist_details->first_name : '') ) 
		];
		$this->data['last_name'] = [
			'name'     => 'last_name',
			'id'       => 'last_name',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('last_name') ?: (!count($_POST) && isset($exist_details->last_name)? $exist_details->last_name : '') ) 
		];
		$this->data['email'] = [
			'name'     => 'email',
			'id'       => 'email',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'email',
			'value'    => ( $this->form_validation->set_value('email') ?: (!count($_POST) && isset($exist_details->email)? $exist_details->email : '') ) 
		];
		if($exist_details && $exist_details->username && $exist_details->email){
			$this->data['email']['disabled'] = true;
			$this->data['username']['disabled'] = true;
		}
		else{
			$this->data['password']['required'] = true;
			$this->data['password']['class'] = 'validate';
		}
		$this->data['password'] = [
			'name'     => 'password',
			'id'       => 'password',
			'type'     => 'password',
			
		];
		$this->data['confirm_password'] = [
			'name'     => 'confirm_password',
			'id'       => 'confirm_password',
			'type'     => 'password',
			
		];
		$this->data['phone'] = [
			'name'     => 'phone',
			'id'       => 'phone',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('phone') ?: (!count($_POST) && isset($exist_details->phone)? $exist_details->phone : '') ) 
		];
		$this->data['active'] = [
			'name'     => 'active',
			'id'       => 'active',
			'checked' => ($this->form_validation->set_value('active') ?: (!count($_POST) && isset($exist_details->active) ) ? $exist_details->active : '1'),
			'value'    => 1
		];
		$this->data['csrf'] = $this->_get_csrf_nonce();
		$this->data['submit'] = [
			'name'    => 'submit',
			'value'   => 'submit',
			'type'    => 'submit',
			'content' => 'Save<i class="material-icons right">send</i>',
			'class'   => "waves-effect waves-light btn deep-purple darken-2",
		];
	}
	public function delete($id = ''){
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$id
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->common_model->delete_data($this->table_name, "id = {$id} AND id > 1");
		$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully deleted."));
		redirect($this->page_url, 'refresh');
	}
	public function ajax_table(){
		## Read value
		$datatable = $this->getDatatablePost();
		$condition 			 = null;
		if($datatable->searchValue != ''){
		  $condition = "id like '%".$datatable->searchValue."%' OR first_name like '%".$datatable->searchValue."%' OR last_name like '%".$datatable->searchValue."%' OR email like '%".$datatable->searchValue."%' OR phone like '%".$datatable->searchValue."%'";
		}		
		$order_by = "{$datatable->columnName} {$datatable->columnSortOrder}";
		$result        = $this->common_model->fetAllResults("*", $this->table_name, $condition, $order_by, $datatable->row, $datatable->rowperpage);
		$total_records = $this->common_model->getCountOfAllResult($this->table_name);
		$filter_records = count($this->common_model->fetAllResults("id", $this->table_name, $condition));
		$data = [];
		foreach ($result as $key => $value) {
			$actions = sprintf('<a href="%s" title="Edit"> <i class="material-icons">edit</i></a>', base_url($this->page_url."/edit/{$value->id}"));
			$actions .= sprintf('<a href="%s" title="Delete" data-id="%d" class="modal-trigger delete row-delete"> <i class="material-icons">delete</i></a>', "#deleteModal", $value->id);
			if($value->id <=1){
				$actions = "No Action";
			}
			$data[] = [
				"id"         => "#Staff_{$value->id}",
				"first_name" => ucfirst($value->first_name),
				"last_name"  => ucfirst($value->last_name),
				"email"      => $value->email,
				"phone"      => $value->phone,
				"status"     => $value->active? "Active" : "Inactive",
				"actions"    => $actions,
			];
		}
		## Response
		$response = array(
			"draw"                 => intval($datatable->draw),
			"iTotalRecords"        => $total_records,
			"iTotalDisplayRecords" => $filter_records,
			"aaData"               => $data
		);
		echo json_encode($response);
	}
}
