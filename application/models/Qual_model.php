<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qual_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}
	public function head_qexam($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT 
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ B ON ACT.BIZ_IDX = B.IDX
		WHERE
			O.PPINPUT_YN = 'Y'
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}
	public function head_qexam_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT 
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ B ON ACT.BIZ_IDX = B.IDX
		WHERE
			O.PPINPUT_YN = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function detail_qexam($params)
	{
		if (!empty($params['IDX']) && !empty($params['ACT_IDX'])) {
			$sql = <<<SQL
			SELECT
				O.IDX,
				O.ACT_IDX,
				O.ORDER_DATE,
				A.ACT_NAME,
				B.CUST_NM,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY,
				O.START_DATE,
				O.END_DATE,
				O.QEXAM_YN,
				O.DEFECT_YN,
				O.DEFECT_REMARK,
				O.DEFECT_QTY
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
				LEFT JOIN T_BIZ B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
			AND O.IDX = {$params['IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		} else if (empty($params['IDX'])) {
			$sql = <<<SQL
			SELECT
				A.ACT_NAME,
				B.CUST_NM,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY
			FROM
				T_ACT A 
				LEFT JOIN T_BIZ B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function update_qexam($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET QEXAM_YN = "Y",DEFECT_YN="{$params['DEFECT_YN']}",DEFECT_REMARK="{$params['DEFECT_REMARK']}",DEFECT_QTY="{$params['DEFECT_QTY']}",PHINPUT_YN="Y"
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}


	public function ajax_perfpoor($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT,
			O.QEXAM_YN,
			O.DEFECT_YN,
			O.DEFECT_REMARK,
			O.DEFECT_QTY,
			ROUND((O.DEFECT_QTY / O.PPLI2CO3_AFTER_INPUT)*100,2) AS DEFECT_RATE
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			O.DEFECT_YN = 'Y'
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function ajax_perfpoor_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT,
			O.QEXAM_YN,
			O.DEFECT_YN,
			O.DEFECT_REMARK,
			O.DEFECT_QTY,
			ROUND((O.DEFECT_QTY / O.PPLI2CO3_AFTER_INPUT)*100,2) AS DEFECT_RATE
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			O.PPINPUT_YN = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->num_rows();
	}
	public function ajax_qualitycur($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT,
			O.QEXAM_YN,
			O.DEFECT_YN,
			O.DEFECT_REMARK,
			O.DEFECT_QTY,
			ROUND((O.DEFECT_QTY / O.PPLI2CO3_AFTER_INPUT)*100,2) AS DEFECT_RATE
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			O.PPINPUT_YN = 'Y'
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function ajax_qualitycur_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT,
			O.QEXAM_YN,
			O.DEFECT_YN,
			O.DEFECT_REMARK,
			O.DEFECT_QTY,
			ROUND((O.DEFECT_QTY / O.PPLI2CO3_AFTER_INPUT)*100,2) AS DEFECT_RATE
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			O.PPINPUT_YN = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->num_rows();
	}
	public function ajax_pooranal($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT,
			O.QEXAM_YN,
			O.DEFECT_YN,
			O.DEFECT_REMARK,
			O.DEFECT_QTY,
			ROUND((O.DEFECT_QTY / O.PPLI2CO3_AFTER_INPUT)*100,2) AS DEFECT_RATE
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			O.DEFECT_YN = 'Y'
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->result();
	}
	public function ajax_pooranal_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.END_DATE,
			ACT.QTY,
			O.PPLI2CO3_AFTER_INPUT,
			O.QEXAM_YN,
			O.DEFECT_YN,
			O.DEFECT_REMARK,
			O.DEFECT_QTY,
			ROUND((O.DEFECT_QTY / O.PPLI2CO3_AFTER_INPUT)*100,2) AS DEFECT_RATE
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			O.DEFECT_YN = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function ajax_pooranal_form($params)
	{
		$sql = <<<SQL
			SELECT
				O.IDX,
				O.ACT_IDX,
				O.DEFECT_REMARK,
				O.DEFECT_REMARK_DATE
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
			WHERE
			A.IDX ={$params['ACT_IDX']}
			AND O.IDX = {$params['IDX']}
SQL;

		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->row();
	}

	public function update_pooranal_form($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET DEFECT_REMARK = "{$params['DEFECT_REMARK']}",
			DEFECT_REMARK_DATE = NOW()
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}
}
