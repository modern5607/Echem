<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pur_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}




	// 구매관리 - 원자재 리스트
	public function component_list($params,$start,$limit)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("COM.IDX,ACT_DATE,BIZ_IDX,CUST_NM,QTY,UNIT,DEL_DATE,COM.REMARK,END_YN,END_DATE,QTY2,REMARK2");
		$this->db->join("T_BIZ AS B", "B.IDX = COM.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_COMPONENT AS COM");
		// echo $this->db->last_query();
		return $query->result();
	}
	public function component_list_cnt($params)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("COM.IDX,ACT_DATE,BIZ_IDX,CUST_NM,QTY,UNIT,DEL_DATE,COM.REMARK,END_YN,END_DATE,QTY2,REMARK2");
		$this->db->join("T_BIZ AS B", "B.IDX = COM.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$query = $this->db->get("T_COMPONENT AS COM");
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	// 구매관리 - 원자재 발주등록
	public function component_head_insert($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
		INSERT T_COMPONENT
			SET
			ACT_DATE   	= '{$params['ACT_DATE']}',
			BIZ_IDX 	= '{$params['BIZ_IDX']}',
			QTY 		= '{$params['QTY']}',
			UNIT     	= '{$params['UNIT']}',
			DEL_DATE    = '{$params['DEL_DATE']}',	
			REMARK    	= '{$params['REMARK']}',
			END_YN    	= 'N',
			INSERT_ID   = '{$username}',
			INSERT_DATE = '{$datetime}'
SQL;
		$this->db->query($sql);
		
		return $this->db->affected_rows();
	}

	// 구매관리 - 원자재 발주 삭제
	public function del_component($idx)
	{
		$this->db->trans_start();
			$this->db->where("IDX", $idx);
			$this->db->delete("T_COMPONENT");
		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	// 구매관리 - 원자재 입고
	public function end_component($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$this->db->trans_start();
			$this->db->set("END_YN", "Y");
			$this->db->set("END_DATE", $datetime);
			$this->db->set("UPDATE_DATE", $datetime);
			$this->db->set("UPDATE_ID", $username);
			$this->db->set("QTY2", $params['QTY']);
			$this->db->set("REMARK2", $params['REMARK']);
			$this->db->where("IDX", $params['IDX']);
			$this->db->update("T_COMPONENT");
		$this->db->trans_complete();
// echo $this->db->last_query();
		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

}
