<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Common_model extends CI_Model{
  const MAX_PASSWORD_SIZE_BYTES = 4096;
	function __construct(){
    parent::__construct();
  }
  public function query($sql_query, $type = "result"){
    $query = $this->db->query($sql_query);
    $result = $query->{$type}();
    return $result;
  }
  public function insert_data($table_name, $insert_data){
  	$this->db->insert($table_name, $insert_data);
  	$insert_id = $this->db->insert_id();
  	return $insert_id;
  }
  public function getJoinQueryResult($select="*", $table_name, $join_data = null, $where = null, $order_by = null, $start = null, $limit = null, $type = "result"){
    $this->db->select($select);
    $this->db->from($table_name);
    if($join_data !== null && is_array($join_data) && count($join_data) > 0){
      foreach ($join_data as $key => $value) {
        $this->db->join($value[0], $value[1], $value[2]);
      }      
    }
    if($where !== null){
      $this->db->where($where);
    }
    if($order_by !== null){
      $this->db->order_by($order_by);
    }
    if($start !== null && $limit !== null){
      $this->db->limit($limit, $start);
    }   
    $result = $this->db->get()->{$type}();
    return $result;
  }
  public function get_settings($table_name, $or_where = null){
    $this->db->select("*");
    if($or_where !== null){
      foreach ($or_where as $key => $value) {
        $this->db->or_where("field_name", $value);
      }      
    } 
    $records = $this->db->get($table_name)->result_array();
    $result = array_combine(array_column($records, 'field_name'), array_column($records, 'value'));
    return $result;
  }
  public function delete_data($table_name, $where){
  	$this->db->delete($table_name, $where);
  	return TRUE;
  }
  public function update_data($table_name, $update_data, $condition){
  	$this->db->update($table_name, $update_data, $condition);
  }
  public function update_batch($table_name, $update_data, $field){
    $this->db->update_batch($table_name, $update_data, $field);
  }
  public function getCountOfAllResult($table_name, $where = null){
		$this->db->from($table_name);
		if($where !== null){
			$this->db->where($where);
		}
		return $this->db->count_all_results();
  }
  public function fetchExistingDetails($select="*", $table_name, $where = null){
  	$this->db->select($select);
  	if($where !== null){
			$this->db->where($where);
		}
		return $this->db->get($table_name)->row();
  }
  public function fetAllResults($select="*", $table_name, $where = null, $order_by = null, $start = null, $limit = null){
		$this->db->select($select);
		if($where !== null){
			$this->db->where($where);
		}
		if($order_by !== null){
			$this->db->order_by($order_by);
		}
		if($start !== null && $limit !== null){
			$this->db->limit($limit, $start);
		}		
		$result = $this->db->get($table_name)->result();
		return $result;
  }
}