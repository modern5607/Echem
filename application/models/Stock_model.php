<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}
	
	public function ajax_package()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_stockcur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}

	//재고조정
	public function ajax_stockchange()
	{
		$sql=<<<SQL
			SELECT * FROM T_ITEMS
SQL;		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}


	public function ajax_act($params,$start=0,$limit=15)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		if (!empty($params['ENDYN']) && $params['ENDYN'] != "") {
			$this->db->where("A.END_YN", $params['ENDYN']);
		}
		if (!empty($params['BIZ']) && $params['BIZ'] != "") {
			$this->db->where("A.BIZ_IDX", $params['BIZ']);
		}
		if (!empty($params['CLAIM']) && $params['CLAIM'] == "1") {
			$this->db->where("CLAIM", NULL);
		}
		if (!empty($params['CLAIM']) && $params['CLAIM'] == "2") {
			$this->db->where('CLAIM is NOT NULL', NULL, FALSE);
		}
		if (!empty($params['LIST']) && $params['LIST'] == "Y") {
			$this->db->order_by('DEL_DATE', 'ACT_DATE');
		}else{
			$this->db->order_by('ACT_DATE', 'desc');
		}


		$this->db->select("A.*, B.CUST_NM");
		$this->db->join("T_BIZ as B", "B.IDX = A.BIZ_IDX");
		$this->db->limit($limit, $start);
		$query = $this->db->get("T_ACT as A");
// echo $this->db->last_query();
		return $query->result();
	}
	public function act_cut($params)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		if (!empty($params['ENDYN']) && $params['ENDYN'] != "") {
			$this->db->where("END_YN", $params['ENDYN']);
		}
		if (!empty($params['BIZ']) && $params['BIZ'] != "") {
			$this->db->where("BIZ_IDX", $params['BIZ']);
		}
		if (!empty($params['CLAIM']) && $params['CLAIM'] == "1") {
			$this->db->where("CLAIM", NULL);
		}
		if (!empty($params['CLAIM']) && $params['CLAIM'] == "2") {
			$this->db->where('CLAIM is NOT NULL', NULL, FALSE);
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->from("T_ACT");
		$query = $this->db->get();
		return $query->row()->CUT;
	}
	public function release_update($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

if(empty($params['CDATE'])){
	$sql = <<<SQL
		UPDATE T_ACT
			SET
			SHIP_WAY   	= '{$params['SHIP']}',
			END_DATE   	= '{$params['EDATE']}',
			BQTY 		= '{$params['BQTY']}',
			SHIP_REMARK = '{$params['REMARK']}',
			END_YN    	= 'Y',
			UPDATE_ID   = '{$username}',
			UPDATE_DATE = '{$datetime}'
		WHERE
			IDX 		= "{$params['IDX']}"
SQL;
}else{
	$sql = <<<SQL
		UPDATE T_ACT
			SET
			CLAIM 		= '{$params['CLAIM']}',
			CLAIM_DATE	= '{$params['CDATE']}',
			UPDATE_ID   = '{$username}',
			UPDATE_DATE = '{$datetime}'
		WHERE
			IDX 		= "{$params['IDX']}"
SQL;
}
		$this->db->query($sql);
		
		return $this->db->affected_rows();
	}


	public function ajax_dbrelease()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_claim()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_claimcur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}


}
