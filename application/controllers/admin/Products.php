<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->table_name      = TBL."products";
		$this->page_url        = 'products';
		$this->toast_msg_title = "Product";
	}
	public function index(){
		$this->data['page_heading'] = "Products";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->load->view('admin/products/list_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function edit($edit = ""){
		$this->data['page_heading'] = "Edit Products";
		$this->data['action_url'] = "{$this->page_url}/edit/{$edit}";
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$edit
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('name', 'Product Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('description', 'Description', "trim|xss_clean");
		$this->form_validation->set_rules('company_price', 'Company Price', "trim|required|numeric|greater_than_equal_to[0]|xss_clean");
		$this->form_validation->set_rules('commission_price', 'Commission Price', "trim|numeric|greater_than_equal_to[0]|xss_clean");
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$update_data = [
				"name"             => $this->input->post("name", true),
				"description"      => $this->input->post("description", true),
				"company_price"    => $this->input->post("company_price", true),
				"commission_price" => $this->input->post("commission_price", true),
				"created_by"       => $this->user->id,
				"active"           => $this->input->post("active")??'0'		
			];
			$this->common_model->update_data($this->table_name, $update_data, "id = {$edit}");
			 $this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully updated."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm($exist_details);
		$this->load->view('admin/products/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function add(){
		$this->data['page_heading'] = "Add Products";
		$this->data['action_url'] = base_url($this->page_url)."/add";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('name', 'Product Name', "trim|required|xss_clean");
		$this->form_validation->set_rules('description', 'Description', "trim|xss_clean");
		$this->form_validation->set_rules('company_price', 'Company Price', "trim|required|numeric|greater_than_equal_to[0]|xss_clean");
		$this->form_validation->set_rules('commission_price', 'Commission Price', "trim|numeric|greater_than_equal_to[0]|xss_clean");
		if ($this->form_validation->run() === TRUE && $this->_valid_csrf_nonce() === TRUE){
			$insert_data = [
				"name"             => $this->input->post("name", true),
				"description"      => $this->input->post("description", true),
				"company_price"    => $this->input->post("company_price", true),
				"commission_price" => $this->input->post("commission_price", true),
				"created_by"       => $this->user->id,
				"active"           => $this->input->post("active")??'0'		
			];
			$this->common_model->insert_data($this->table_name, $insert_data);
			 $this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully created."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm();
		$this->load->view('admin/products/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function buildForm($exist_details = []){
		$this->data['name'] = [
			'name'     => 'name',
			'id'       => 'name',
			'class'    => 'validate',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('name') ?: (!count($_POST) && isset($exist_details->name)? $exist_details->name : '') ) 
		];
		$this->data['description'] = [
			'name'     => 'description',
			'id'       => 'description',
			'class'    => 'materialize-textarea',
			'style'    => 'min-height:150px;',
			'value'    => ( $this->form_validation->set_value('description') ?: (!count($_POST) && isset($exist_details->description)? $exist_details->description : '') ) 
		];
		$this->data['company_price'] = [
			'name'     => 'company_price',
			'id'       => 'company_price',
			'required' => true,
			'class'    => 'natural-no-validate validate',
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('company_price') ?: (!count($_POST) && isset($exist_details->company_price)? $exist_details->company_price : '0') ) 
		];
		$this->data['commission_price'] = [
			'name'     => 'commission_price',
			'id'       => 'commission_price',
			'class'    => 'natural-no-validate',
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('commission_price') ?: (!count($_POST) && isset($exist_details->commission_price)? $exist_details->commission_price : '0') ) 
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
		$this->common_model->delete_data($this->table_name, "id = {$id}");
		$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully deleted."));
		redirect($this->page_url, 'refresh');
	}
	public function ajax_table(){
		## Read value
		$datatable = $this->getDatatablePost();
		$condition 			 = null;
		if($datatable->searchValue != ''){
		  $condition = "name like '%".$datatable->searchValue."%' OR commission_price like '%".$datatable->searchValue."%' OR company_price like '%".$datatable->searchValue."%' OR id like '%".$datatable->searchValue."%'";
		}		
		$order_by = "{$datatable->columnName} {$datatable->columnSortOrder}";
		$result        = $this->common_model->fetAllResults("*", $this->table_name, $condition, $order_by, $datatable->row, $datatable->rowperpage);
		$total_records = $this->common_model->getCountOfAllResult($this->table_name);
		$filter_records = count($this->common_model->fetAllResults("id", $this->table_name, $condition));
		$data = [];
		foreach ($result as $key => $value) {
			$actions = sprintf('<a href="%s" title="Edit"> <i class="material-icons">edit</i></a>', base_url($this->page_url."/edit/{$value->id}"));
			$actions .= sprintf('<a href="%s" title="Delete" data-id="%d" class="modal-trigger delete row-delete"> <i class="material-icons">delete</i></a>', "#deleteModal", $value->id);
			$data[] = [
				"id"               => "#PR_{$value->id}",
				"name"             => ucfirst($value->name),
				"company_price"    => number_format($value->company_price, 2),
				"commission_price" => number_format($value->commission_price, 2),
				"active"           => $value->active? "Active" : "Inactive",
				"actions"          => $actions,
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
