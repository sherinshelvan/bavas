<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->table_name      = TBL."sales";
		$this->tbl_sales_details = TBL."sales_details";
		$this->tbl_products    = TBL."products";
		$this->tbl_users    = TBL."users";
		$this->page_url        = 'sales';
		$this->toast_msg_title = "Sale";
	}
	public function index(){
		$this->data['page_heading'] = "Sales";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$options = ["" => "--Search by sales man--"];
		$sales_mans = $this->ion_auth->users(2)->result();
		foreach ($sales_mans as $key => $value) {
		 	$options[$value->id] = "{$value->first_name} {$value->last_name}";	
		} 
		$this->data['sales_man'] = [
			'name'     => 'sales_man',
			'id'       => 'sales_man',
			'class' => 'extra-filters',
			'options' => $options
		];
		$this->data['from_date'] = [
			'name'     => 'from_date',
			'id'       => 'from_date',
			'class' => 'extra-filters',
			'value' => "01-".date("m-Y")
		];
		$this->data['to_date'] = [
			'name'     => 'to_date',
			'id'       => 'to_date',
			'class' => 'extra-filters',
			'value' => date("t-m-Y")
		];
		$this->load->view('admin/sales/list_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function product_add(){
		$form_data = $this->input->post();
		if(!isset($form_data['sales_data'])){
			$form_data['sales_data'] = [];
		}
		$form_data['products_list'] = $this->common_model->fetAllResults("*", $this->tbl_products, "active = '1'", "name ASC");
		$form_data['product_ids'] = array_column($form_data['products_list'], 'id');
		$this->load->view('admin/sales/sales_sheet_view', $form_data);
		// echo json_encode($form_data);
	}
	public function detail($id = ""){
		$this->data['page_heading'] = "Sales Details";
		$this->data['action_url'] = "{$this->page_url}/edit/{$id}";
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$id
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('date', 'Sale Date', "trim|required|xss_clean");
		$this->form_validation->set_rules('sales_man', 'Sales Man', "trim|required|xss_clean");
		$exist_details->extra_expense = $exist_details->extra_expense ? json_decode($exist_details->extra_expense, true) : [];
		$exist_details->extra_expense = ($this->input->post("extra_expense"))? $this->input->post("extra_expense"): $exist_details->extra_expense ;
		$sales_data = $this->common_model->fetAllResults("*", $this->tbl_sales_details, "sale_id = {$id}");

		$exist_details->sales_data = $this->input->post("sales_data")?: json_decode(json_encode($sales_data), true);

		$this->data['products_list'] = $this->common_model->fetAllResults("*", $this->tbl_products, "active = '1'", "name ASC");
		$this->data['product_ids'] = array_column($this->data['products_list'], 'id');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm($exist_details);
		$this->data['result'] = $exist_details;
		$this->load->view('admin/sales/detail_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function edit($edit = ""){
		$this->data['page_heading'] = "Edit Sales";
		$this->data['action_url'] = "{$this->page_url}/edit/{$edit}";
		$edit_condition = [
			'table_name' => $this->table_name,
			'condition'  => "id = ".(int)$edit
		];
		$exist_details = $this->editPageValidate((object)$edit_condition);
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('date', 'Sale Date', "trim|required|xss_clean");
		$this->form_validation->set_rules('sales_man', 'Sales Man', "trim|required|xss_clean");
		$exist_details->extra_expense = $exist_details->extra_expense ? json_decode($exist_details->extra_expense, true) : [];
		$exist_details->extra_expense = ($this->input->post("extra_expense"))? $this->input->post("extra_expense"): $exist_details->extra_expense ;
		$sales_data = $this->common_model->fetAllResults("*", $this->tbl_sales_details, "sale_id = {$edit}");

		$exist_details->sales_data = $this->input->post("sales_data")?: json_decode(json_encode($sales_data), true);

		$this->data['products_list'] = $this->common_model->fetAllResults("*", $this->tbl_products, "active = '1'", "name ASC");
		$this->data['product_ids'] = array_column($this->data['products_list'], 'id');
		
		if ($this->form_validation->run() === TRUE /*&& $this->_valid_csrf_nonce() === TRUE*/){
			$exist_details->extra_expense = $this->input->post("extra_expense")?: [];
			$exist_details->sales_data = $this->input->post("sales_data")?: [];
			$update_data = [
				"date"                 => date("Y-m-d", strtotime($this->input->post("date", true))),
				"sales_man"            => $this->input->post("sales_man", true),
				"last_updated_user"    => $this->user->id,
				"price_type"           => $this->input->post("price_type", true),
				"credit_amount"        => $this->input->post("credit_amount", true),
				"last_update"          => date("Y-m-d H:i:s"),
				"extra_expense"        => json_encode($exist_details->extra_expense),
				"total_expense_amount" => array_sum(array_column($exist_details->extra_expense, "amount")),
			];
			$this->common_model->delete_data($this->tbl_sales_details, "sale_id = {$edit}");
			$sale_amount = 0;
			foreach ($exist_details->sales_data as $key => $value) {
				$pos  = array_search($value['product_id'], $this->data['product_ids']);
				$product_data      = $this->data['products_list'][$pos];
				$value['quantity'] = (int)$value['quantity']?:0;
				$value['return']   = (int)$value['return']?:0;
				$value['damage']   = (int)$value['damage']?:0;
				$value['price']    = (int)$value['price']?:0;
				$sale              = ($value['quantity'] - ($value['return'] + $value['damage']));
				$amount            = $sale * $product_data->{$update_data['price_type']};
				$sale_amount += $amount;
				$sales_details_insert = [
					"sale_id"      => $edit,
					"product_name" => $product_data->name,
					"product_id"   => $product_data->id,
					"quantity"     => $value['quantity'],
					"return"       => $value['return'],
					"damage"       => $value['damage'],
					"price"        => $product_data->{$update_data['price_type']},
					"amount"       => $amount,
					"sale"         => $sale
				];
				$this->common_model->insert_data($this->tbl_sales_details, $sales_details_insert);
			}
			$update_data["sale_amount"]     = $sale_amount;
			$update_data["loss_and_profit"] = $sale_amount - $update_data['total_expense_amount'] - $update_data['credit_amount'];
			$this->common_model->update_data($this->table_name, $update_data, "id = {$edit}");
			$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully updated."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm($exist_details);
		$this->data['exist_details'] = $exist_details;
		$this->load->view('admin/sales/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	public function add(){
		$this->data['page_heading'] = "Add Sales";
		$exist_details = [];
		$this->data['action_url'] = base_url($this->page_url)."/add";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->form_validation->set_rules('date', 'Sale Date', "trim|required|xss_clean");
		$this->form_validation->set_rules('sales_man', 'Sales Man', "trim|required|xss_clean");

		$exist_details['extra_expense'] = $this->input->post("extra_expense")? $this->input->post("extra_expense") : [] ;
		$sale_data = [];
		$active_products = $this->common_model->fetAllResults("*", $this->tbl_products, "active='1'");
		foreach ($active_products as $key => $value) {
			$sale_data[] = [
				"product_name" => $value->name,
				"product_id"   => $value->id,
				"quantity"     => 0,
				"return"       => 0,
				"damage"       => 0,
				"price"        => $value->company_price,
				"amount"       => 0,
				"sale"         => 0
			];
		}
		$exist_details['sales_data'] = $this->input->post("sales_data")? $this->input->post("sales_data") : $sale_data ;
		$exist_details['total_expense_amount'] = $exist_details['total_expense_amount']??0;
		$exist_details['sale_amount'] = $exist_details['sale_amount']??0;		
		$exist_details['credit_amount'] = $exist_details['credit_amount']??0;		
		$exist_details['credit_amount'] = $this->input->post("credit_amount")??$exist_details['credit_amount'];		
		$exist_details['price_type'] = $this->input->post('price_type')??"company_price";

		$this->data['products_list'] = $this->common_model->fetAllResults("*", $this->tbl_products, "active = '1'", "name ASC");
		$this->data['product_ids'] = array_column($this->data['products_list'], 'id');
		


		if ($this->form_validation->run() === TRUE /*&& $this->_valid_csrf_nonce() === TRUE*/){
			$insert_data = [
				"date"                 => date("Y-m-d", strtotime($this->input->post("date", true))),
				"sales_man"            => $this->input->post("sales_man", true),
				"created_user"         => $this->user->id,
				"last_updated_user"    => $this->user->id,
				"price_type"           => $this->input->post("price_type", true),
				"credit_amount"        => $this->input->post("credit_amount", true),
				"last_update"          => date("Y-m-d H:i:s"),
				"extra_expense"        => json_encode($exist_details['extra_expense']),
				"total_expense_amount" => array_sum(array_column($exist_details['extra_expense'], "amount")),
			];
			$insert_id = $this->common_model->insert_data($this->table_name, $insert_data);
			$sale_amount = 0;
			foreach ($exist_details['sales_data'] as $key => $value) {
				$pos  = array_search($value['product_id'], $this->data['product_ids']);
				$product_data      = $this->data['products_list'][$pos];
				$value['quantity'] = (int)$value['quantity']?:0;
				$value['return']   = (int)$value['return']?:0;
				$value['damage']   = (int)$value['damage']?:0;
				$value['price']    = (int)$value['price']?:0;
				$sale              = ($value['quantity'] - ($value['return'] + $value['damage']));
				$amount            = $sale * $product_data->{$insert_data['price_type']};
				$sale_amount += $amount;
				$sales_details_insert = [
					"sale_id"      => $insert_id,
					"product_name" => $product_data->name,
					"product_id"   => $product_data->id,
					"quantity"     => $value['quantity'],
					"return"       => $value['return'],
					"damage"       => $value['damage'],
					"price"        => $product_data->{$insert_data['price_type']},
					"amount"       => $amount,
					"sale"         => $sale
				];
				$this->common_model->insert_data($this->tbl_sales_details, $sales_details_insert);
			}
			$sales_update = [
				"sale_amount" => $sale_amount,
				"loss_and_profit" => $sale_amount - $insert_data['total_expense_amount'] - $insert_data['credit_amount']
			];
			$this->common_model->update_data($this->table_name, $sales_update, "id = {$insert_id}");
			$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully created."));
			redirect($this->page_url, 'refresh');
		}
		$this->data['exist_details'] = (object)$exist_details;
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$this->buildForm();
		$this->load->view('admin/sales/edit_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function buildForm($exist_details = []){
		$this->data['date'] = [
			'name'     => 'date',
			'id'       => 'date',
			'class'    => 'validate sale-date',
			'required' => true,
			'type'     => 'text',
			'value'    => ( $this->form_validation->set_value('date') ?: (!count($_POST) && isset($exist_details->date)? $exist_details->date : date("d-m-Y")) ) 
		];
		$sales_mans = $this->ion_auth->users(2)->result();
		$options = ["" => "--Select sales man--"];
		$this->data["selected_salesman"] = "";
		foreach ($sales_mans as $key => $value) {
		 	$options[$value->id] = "{$value->first_name} {$value->last_name}";
		 	$this->data["selected_salesman"] = (isset($exist_details) && isset($exist_details->sales_man) && $exist_details->sales_man == $value->id) ? "{$value->first_name} {$value->last_name}" : "";
		} 
		$this->data['sales_man'] = [
			'name'     => 'sales_man',
			'id'       => 'sales_man',
			'options'  => $options,
			'selected'       => ( $this->form_validation->set_value('sales_man') ?: (!count($_POST) && isset($exist_details->sales_man)? $exist_details->sales_man : '') )
		];
		$prdct = $this->common_model->fetAllResults("id, name", $this->tbl_products, "active = '1'", "name ASC");
		$products[] = "--Select Product--";
		$products += array_combine(array_column($prdct, 'id'), array_column($prdct, 'name'));
		$this->data['products'] = [
			'name'     => 'products',
			'id'       => 'products',
			'options'  => $products
		];
		$this->data['product_list'] = $prdct;
		$this->data['description'] = [
			'name'        => 'description',
			'id'          => 'description',
			'class'       => 'materialize-textarea validate',
			'required'    => true,
			'data-length' => '50',
			'value'       => ( $this->form_validation->set_value('description') ?: (!count($_POST) && isset($exist_details->description)? $exist_details->description : '') )
		];
		$this->data['price_type'] = [
			'name'        => 'price_type',
			'id'          => 'price_type',
			'options'     => array("company_price" => "Company Price", "commission_price" => "Commission Price"),
			'selected'       => ( $this->form_validation->set_value('price_type') ?: (!count($_POST) && isset($exist_details->price_type)? $exist_details->price_type : '') ) 
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
		$this->common_model->delete_data($this->tbl_sales_details, "sale_id = {$id}");
		$this->session->set_flashdata('toast_message', sprintf('%s', "{$this->toast_msg_title} successfully deleted."));
		redirect($this->page_url, 'refresh');
	}
	public function ajax_table(){
		## Read value
		$datatable = $this->getDatatablePost();
		$condition 			 = "1";
		$condition 			 .= sprintf(" AND a.date BETWEEN '%s' AND '%s'", date("Y-m-d", strtotime($this->input->post('from_date'))), date("Y-m-d", strtotime($this->input->post('to_date'))));
		if($this->input->post("sales_man")){
			$condition 			 .= " AND a.sales_man = '{$this->input->post('sales_man')}'";
		}
		if($datatable->searchValue != ''){
		  	$search_by = ["date", "sale_amount", "credit_amount", "loss_and_profit"];
			$condition .= "AND (id like '%{$datatable->searchValue}%'";
			foreach ($search_by as $key => $value) {
				$condition .= " OR {$value} like '%{$datatable->searchValue}%'";
			}
			$condition .= ")";
		}		
		$order_by = "{$datatable->columnName} {$datatable->columnSortOrder}";
		if($datatable->columnName == 'sales_man'){
			$order_by       = "b.first_name {$datatable->columnSortOrder}";
		}
		$join_array[]   = array("{$this->tbl_users} b", "a.sales_man = b.id", "left");
		$result         = $this->common_model->getJoinQueryResult("a.*, b.first_name, b.last_name", "$this->table_name a", $join_array, $condition, $order_by, $datatable->row, $datatable->rowperpage);
		// $result        = $this->common_model->fetAllResults("*", $this->table_name, $condition, $order_by, $datatable->row, $datatable->rowperpage);

		$total_records = $this->common_model->getCountOfAllResult($this->table_name);
		$filter_records = count($this->common_model->getJoinQueryResult("a.*", "$this->table_name a", $join_array, $condition));
		$data = [];
		foreach ($result as $key => $value) {
			$actions = sprintf('<a href="%s" title="Details"> <i class="material-icons">remove_red_eye</i></a>', base_url($this->page_url."/detail/{$value->id}"));
			$actions .= sprintf('<a href="%s" title="Edit"> <i class="material-icons">edit</i></a>', base_url($this->page_url."/edit/{$value->id}"));
			$actions .= sprintf('<a href="%s" title="Delete" data-id="%d" class="modal-trigger delete row-delete"> <i class="material-icons">delete</i></a>', "#deleteModal", $value->id);
			
			$data[] = [
				"id"                   => "#Sale_{$value->id}",
				"date"                 => date("d-m-Y", strtotime($value->date)),
				"sales_man"            => ucfirst($value->first_name)." ".ucfirst($value->last_name),
				"sale_amount"          => number_format($value->sale_amount, 2),
				"total_expense_amount" => number_format($value->total_expense_amount, 2),
				"credit_amount"        => number_format($value->credit_amount, 2),
				"loss_and_profit"      => number_format($value->loss_and_profit, 2),
				"actions"              => $actions,
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
