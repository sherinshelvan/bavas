<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends MY_Controller {
	public $data = [];
	public function __construct(){
		parent::__construct();	
		$this->pageAccess('administrator');	
		$this->tbl_sales = TBL."sales";
		$this->tbl_sales_details = TBL."sales_details";
	}
	public function index(){
		$this->data['page_heading'] = "Dashboard";
		$this->load->view('admin/includes/header');
		$this->load->view('admin/includes/side_navigation');
		$this->data['message'] = (validation_errors()) ? validation_errors() : ($this->session->flashdata('message')?: (isset($this->data['message'])? $this->data['message'] : '') );
		$current_date = date("Y-m-d");
		$this->data['today_sale'] = $this->common_model->fetchExistingDetails("SUM(sale_amount) as amount", $this->tbl_sales, "date = DATE('{$current_date}')");
		$this->data['today_expense'] = $this->common_model->fetchExistingDetails("SUM(total_expense_amount) as amount", $this->tbl_sales, "date = DATE('{$current_date}')");
		$this->data['today_profit'] = $this->common_model->fetchExistingDetails("SUM(loss_and_profit) as amount", $this->tbl_sales, "date = DATE('{$current_date}')");
		$this->generate_graph();
		$this->load->view('admin/dashboard/dashboard_view', $this->data);
		$this->load->view('admin/includes/footer');
	}
	private function generate_graph(){
		$sales_mans = $this->ion_auth->users(2)->result();
		// Sale Graph
		$sale   = array_fill(0, sizeof($sales_mans), '0');
		$damage = array_fill(0, sizeof($sales_mans), '0');
		$return = array_fill(0, sizeof($sales_mans), '0');
		$user_ids = array_column($sales_mans, "id");
		$current_date = date("Y-m-d");
		foreach ($sales_mans as $key => $value) {
			$data = $this->common_model->query("SELECT SUM(a.sale) as sale, SUM(a.return) as return_count, SUM(a.damage) as damage FROM {$this->tbl_sales_details} a LEFT JOIN  {$this->tbl_sales} b ON a.sale_id = b.id WHERE b.sales_man = {$value->id} AND date = DATE('{$current_date}') GROUP BY a.sale_id ", "row");
			if($data){
				$pos = array_search($value->id, $user_ids);
				$sale[$pos] = $data->sale;
				$damage[$pos] = $data->damage;
				$return[$pos] = $data->return_count;
			}
		}
		$sale_graph = [
			"labels" => array_column($sales_mans, "first_name"),
			"datasets" => [
				[
					"label"                => "Sale",
					"backgroundColor"      => "rgba(54, 162, 235, 0.2)",
					"borderColor"          => "rgba(54, 162, 235, 1)",
					"borderWidth"          => 2,
					"hoverBackgroundColor" => "rgba(54, 162, 235, 0.4)",
					"hoverBorderColor"     => "rgba(54, 162, 235, 1)",
					"data"                 => $sale,
					"maxBarThickness"      => 100
				],
				[
					"label"                => "Damage",
					"backgroundColor"      => "rgba(255,99,132, 0.2)",
					"borderColor"          => "rgba(255,99,132, 1)",
					"borderWidth"          => 2,
					"hoverBackgroundColor" => "rgba(255,99,132, 0.4)",
					"hoverBorderColor"     => "rgba(255,99,132, 1)",
					"data"                 => $damage,
					"maxBarThickness"      => 100
				],
				[
					"label"                => "Return",
					"backgroundColor"      => "rgba(201, 203, 207, 0.2)",
					"borderColor"          => "rgba(201, 203, 207, 1)",
					"borderWidth"          => 2,
					"hoverBackgroundColor" => "rgba(201, 203, 207, 0.4)",
					"hoverBorderColor"     => "rgba(201, 203, 207, 1)",
					"data"                 => $return,
					"maxBarThickness"      => 100
				]
			]
		];
		$this->data['sale_graph'] = $sale_graph;
		
		
	}
}
