<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prod_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}

	public function ajax_workorder()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function ajax_pworkorder()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function get_act ($params)
	{
		$where='';
		if($params['END_YN']!=''&& isset($params['END_YN']))
		{
			$where .="AND END_YN = '{$params['END_YN']}'";
		}
		$sql = <<<SQL
			SELECT IDX,ACT_DATE,ACT_NAME,END_YN FROM T_ACT
			WHERE 
			1
			{$where}
			LIMIT 0,10
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();

	}

	//order_form에서 수주일자 선택시 해당 idx 조회해서 수주명 채워주는 함수
	public function act_idx($idx)
	{
		return $this->db->query("SELECT * FROM T_ACT WHERE IDX = '{$idx}'")->row();
	}

	public function get_component()
	{
		$sql=<<<SQL
			SELECT * FROM T_COMPONENT
SQL;

		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->result();
	}

	public function add_order($params)
	{
		$sql=<<<SQL
			INSERT INTO T_ORDER(ACT_IDX,ORDER_DATE,COMPONENT_IDX,ORDER_QTY,INSERT_ID,INSERT_DATE)
			VALUE ("{$params['ACT_IDX']}","{$params['ORDER_DATE']}","{$params['COMPONENT_IDX']}","{$params['ORDER_QTY']}","{$params['INSERT_ID']}",NOW());

SQL;
		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->result();
	}

	public function ajax_matinput()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();	
	}

	public function ajax_pharvest()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_pprodcur()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_dprodperf()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_prodmonitor()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();	
	}
}