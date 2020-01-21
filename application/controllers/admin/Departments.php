<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Departments extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->table_name      = TBL."groups";
		$this->page_url        = 'departments';
		$this->toast_msg_title = "Department";
	}
	public function index(){
		$this->data['page_heading'] = "Departments";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->load->view('admin/departments/list_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function edit($edit = ""){
		$this->data['page_heading'] = "Edit Departments";
		$this->data['action_url'] = "{$this->page_url}/edit/{$edit}";
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$edit
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('name', 'Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('description', 'Description', "trim|required|xss_clean");
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$update_data = [
				"name"             => $this->input->post("name", true),
				"description"      => $this->input->post("description", true),	
			];
			$this->common_model->update_data($this->table_name, $update_data, "id = {$edit}");
			 $this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully updated."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm($exist_details);
		$this->load->view('admin/departments/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function add(){
		$this->data['page_heading'] = "Add Departments";
		$this->data['action_url'] = base_url($this->page_url)."/add";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('name', 'Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('description', 'Description', "trim|required|xss_clean");
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$insert_data = [
				"name"             => $this->input->post("name", true),
				"description"      => $this->input->post("description", true),
			];
			$this->common_model->insert_data($this->table_name, $insert_data);
			 $this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully created."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm();
		$this->load->view('admin/departments/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function buildForm($exist_details = []){
		$this->data['name'] = [
			'name'     => 'name',
			'id'       => 'name',
			'class'    => 'validate',
			'required' => true,
			'data-length' => '20',
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('name') ?: (!count($_POST) && isset($exist_details->name)? $exist_details->name : '') ) 
		];
		$this->data['description'] = [
			'name'        => 'description',
			'id'          => 'description',
			'class'       => 'materialize-textarea validate',
			'required'    => true,
			'data-length' => '50',
			'value'       => ( $this->form_validation->set_value('description') ?: (!count($_POST) && isset($exist_details->description)? $exist_details->description : '') ) 
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
		$this->common_model->delete_data($this->table_name, "id = {$id} AND id > 3");
		$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully deleted."));
		redirect($this->page_url, 'refresh');
	}
	public function ajax_table(){
		## Read value
		$datatable = $this->getDatatablePost();
		$condition 			 = null;
		if($datatable->searchValue != ''){
		  $condition = "name like '%".$datatable->searchValue."%' OR description like '%".$datatable->searchValue."%' OR id like '%".$datatable->searchValue."%'";
		}		
		$order_by = "{$datatable->columnName} {$datatable->columnSortOrder}";
		$result        = $this->common_model->fetAllResults("*", $this->table_name, $condition, $order_by, $datatable->row, $datatable->rowperpage);
		$total_records = $this->common_model->getCountOfAllResult($this->table_name);
		$filter_records = count($this->common_model->fetAllResults("id", $this->table_name, $condition));
		$data = [];
		foreach ($result as $key => $value) {
			$actions = sprintf('<a href="%s" title="Edit"> <i class="material-icons">edit</i></a>', base_url($this->page_url."/edit/{$value->id}"));
			$actions .= sprintf('<a href="%s" title="Delete" data-id="%d" class="modal-trigger delete row-delete"> <i class="material-icons">delete</i></a>', "#deleteModal", $value->id);
			if($value->id <= 3){
				$actions = "No Action";
			}
			$data[] = [
				"id"          => "#Dept_{$value->id}",
				"name"        => ucfirst($value->name),
				"description" => $value->description,
				"actions"     => $actions,
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
