<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}
	
	public function ajax_package($param)
	{
		$where ='';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .=" AND ORDER_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['IDX']) && $param['IDX'] != "") {
			$where .=" AND O.IDX = '{$param['IDX']}'";
		}
		if (!empty($param['PACKAGE']) && $param['PACKAGE'] != "") {
			if ($param['PACKAGE'] == "Y") {
				$where .=" AND PACKAGE_YN = '{$param['PACKAGE']}'";
			}else{
				$where .=" AND PACKAGE_YN is null";
			}
		}

		$sql=<<<SQL
			SELECT
				O.IDX, ORDER_DATE, START_DATE, O.END_DATE, ACT_NAME, CUST_NM, PACKAGE_YN, ACT_DATE, DEL_DATE, A.QTY, PPLI2CO3_AFTER_INPUT, DRY_DATE
			FROM
				`T_ORDER` as O
				left join T_ACT as A on A.IDX = O.ACT_IDX
				left join T_BIZ as B on A.BIZ_IDX = B.IDX
			WHERE
				PPLI2CO3_AFTER_INPUT is not null
				{$where}
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function package_cut($param)
	{
		$where ='';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .="AND ORDER_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PACKAGE']) && $param['PACKAGE'] != "") {
			$where .=" AND PACKAGE_YN = '{$param['PACKAGE']}'";
		}


		$sql=<<<SQL
			SELECT
				O.IDX
			FROM
				`T_ORDER` as O
				left join T_ACT as A on A.IDX = O.ACT_IDX
				left join T_BIZ as B on A.BIZ_IDX = B.IDX
			WHERE
				PPLI2CO3_AFTER_INPUT is not null
				{$where}
SQL;
		$res = $this->db->query($sql);
		// echo $this->db->last_query();
		return $res->num_rows();
	}
	public function update_package($params)
	{
		$sql=<<<SQL
			UPDATE T_ORDER
			SET PACKAGE_YN = "Y"
			WHERE IDX = "{$params['IDX']}"
SQL;
		$this->db->query($sql);
		echo $this->db->last_query();
		return $this->db->affected_rows();
	}




	public function ajax_stockcur($param)
	{
		$where='';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .=" AND ORDER_DATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['KIND']) && $param['KIND'] != "") {
			$where .=" AND T.KIND = '{$param['KIND']}'";
		}
		if (!empty($param['SPEC']) && $param['SPEC'] != "") {
			$where .=" AND I.SPEC = '{$param['SPEC']}'";
		}

		$sql=<<<SQL
			SELECT T.KIND, I.ITEM_NAME, I.SPEC, T.BIZ_NM, I.UNIT, T.QTY, T.KIND, T.TRANS_DATE, T.REMARK
			FROM T_ITEMS_TRANS AS T
			LEFT JOIN T_ITEMS AS I ON T.H_IDX = I.IDX
			WHERE 1 {$where}
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}

	//재고조정
	public function ajax_stockchange($param)
	{
		$where='';
		if (!empty($param['SPEC']) && $param['SPEC'] != "") {
			$where .=" AND SPEC = '{$param['SPEC']}'";
		}

		$sql=<<<SQL
			SELECT * FROM T_ITEMS
			where USE_YN = 'Y' {$where}
SQL;		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function stock_update($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$info = $this->db->query("SELECT * from T_ITEMS where IDX='{$params['IDX']}'")->row();
		if($params['KIND'] == "IN"){
			$sum = $info->STOCK + $params['QTY'];
		}else{
			$sum = $info->STOCK - $params['QTY'];
		}

		$sql = <<<SQL
		UPDATE T_ITEMS
		SET
			STOCK   	= '{$sum}',
			UPDATE_ID   = '{$username}',
			UPDATE_DATE = '{$datetime}'
		WHERE
			IDX 		= "{$params['IDX']}"
SQL;
		$this->db->query($sql);

		$sql2 = <<<SQL
		INSERT T_ITEMS_TRANS
		SET
			H_IDX		= '{$params['IDX']}',
			QTY   		= '{$params['QTY']}',
			KIND   		= '{$params['KIND']}',
			TRANS_DATE  = '{$params['DATE']}',
			REMARK   	= '{$params['REMARK']}',
			BIZ_NM   	= '{$params['BIZ']}',
			INSERT_ID   = '{$username}',
			INSERT_DATE = '{$datetime}'
SQL;
		$this->db->query($sql2);
		
		return $this->db->affected_rows();
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
		$this->db->join("T_ORDER as O", "O.ACT_IDX = A.IDX");
		$this->db->where("O.PACKAGE_YN", 'Y');
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
		$this->db->from("T_ACT AS A");
		$this->db->join("T_ORDER as O", "O.ACT_IDX = A.IDX");
		$this->db->where("O.PACKAGE_YN", 'Y');
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

	$sql2 = <<<SQL
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


}
