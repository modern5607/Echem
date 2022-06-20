<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prod_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}

	public function head_workorder($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";

		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			ACT.ACT_DATE,
			O.START_DATE,
			O.END_DATE
		FROM
			T_ACT AS ACT
			LEFT JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
		1
		{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}
	public function head_workorder_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";

		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			ACT.ACT_DATE,
			O.START_DATE,
			O.END_DATE
		FROM
			T_ACT AS ACT
			LEFT JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
		1
		{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->num_rows();
	}



	public function detail_workorder($params)
	{
		$where = '';
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
				A.SHIP_WAY,
				A.SHIP_REMARK,
				A.QTY,
				O.START_DATE,
				A.END_YN,
				O.END_DATE,
				O.REMARK,
				O.PHINPUT_YN,
				O.INSERT_DATE,
				O.INSERT_ID
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
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
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function insert_workorder($params)
	{
		$sql = <<<SQL
			INSERT INTO T_ORDER(ACT_IDX,ORDER_DATE,START_DATE,END_DATE,REMARK,INSERT_ID,INSERT_DATE)
			VALUE("{$params['HIDX']}",NOW(),"{$params['START_DATE']}","{$params['END_DATE']}","{$params['REMARK']}","{$params['INSERT_ID']}",NOW())
SQL;
		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function update_workorder($params)
	{
		$sql = <<<SQL
			UPDATE T_ORDER
			SET START_DATE = "{$params['START_DATE']}",END_DATE = "{$params['END_DATE']}", REMARK="{$params['REMARK']}",EACHORDER="N"
			WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;
		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function del_workorder($params)
	{
		$sql = <<<SQL
		DELETE FROM T_ORDER
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;
		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function head_pworkorder($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";

		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			EACHORDER
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
		1
		{$where}
		LIMIT
		{$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}
	public function head_pworkorder_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";

		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			EACHORDER
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
		1
		{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->num_rows();
	}

	public function detail_pworkorder($params)
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
				O.RAW_DATE,
				O.NA2CO3_DATE,
				O.MIX_DATE,
				O.WASH_DATE,
				O.DRYPHASE_DATE,
				O.REMARK,
				O.EACHORDER
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX

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
				A.BIZ_NAME,
				B.CUST_NM,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY
			FROM
				T_ACT A
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function update_pworkorder($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET RAW_DATE = "{$params['RAW_DATE']}",
			NA2CO3_DATE="{$params['NA2CO3_DATE']}",
			MIX_DATE="{$params['MIX_DATE']}",
			WASH_DATE="{$params['WASH_DATE']}",
			DRYPHASE_DATE = "{$params['DRYPHASE_DATE']}",
			REMARK="{$params['REMARK']}",
			EACHORDER="Y"
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}
	public function del_pworkorder($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET RAW_DATE = NULL,
			NA2CO3_DATE=NULL,
			MIX_DATE=NULL,
			WASH_DATE=NULL,
			DRYPHASE_DATE = NULL,
			REMARK=NULL,
			EACHORDER=NULL
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function get_act($params)
	{
		$where = '';
		if ($params['END_YN'] != '' && isset($params['END_YN'])) {
			$where .= "AND END_YN = '{$params['END_YN']}'";
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
		$sql = <<<SQL
			SELECT * FROM T_COMPONENT
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function add_order($params)
	{
		$sql = <<<SQL
			INSERT INTO T_ORDER(ACT_IDX,ORDER_DATE,COMPONENT_IDX,ORDER_QTY,INSERT_ID,INSERT_DATE)
			VALUE ("{$params['ACT_IDX']}","{$params['ORDER_DATE']}","{$params['COMPONENT_IDX']}","{$params['ORDER_QTY']}","{$params['INSERT_ID']}",NOW());

SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function head_matinput($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";
		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			EACHORDER,
			RAWINPUT_YN
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
			1
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}
	public function head_matinput_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";
		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			EACHORDER,
			RAWINPUT_YN
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
			1
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->num_rows();
	}

	public function detail_matinput($params)
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
				O.RAW_INPUT,
				O.NA2CO3_INPUT,
				O.LICL_INPUT,
				O.NACL_INPUT,
				O.REMARK,
				O.RAWINPUT_YN
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
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
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function update_matinput($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET RAW_INPUT = "{$params['RAW_INPUT']}",NA2CO3_INPUT="{$params['NA2CO3_INPUT']}",LICL_INPUT="{$params['LICL_INPUT']}",NACL_INPUT="{$params['NACL_INPUT']}",REMARK="{$params['REMARK']}",RAWINPUT_YN="Y"
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}
	public function del_matinput($params)
	{
		$sql = <<<SQL
			UPDATE T_ORDER
			SET RAW_INPUT = NULL,
			NA2CO3_INPUT=NULL,
			LICL_INPUT=NULL,
			NACL_INPUT=NULL,
			REMARK = NULL,
			RAWINPUT_YN=NULL
			WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function head_pharvest($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";

		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			O.PHINPUT_YN
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
			1
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}
	public function head_pharvest_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";

		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			O.PHINPUT_YN
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
			1
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->num_rows();
	}

	public function detail_pharvest($params)
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
				O.PHRAW_INPUT,
				O.PHLICL_AFTER_INPUT,
				O.PHNA2CO3_INPUT,
				O.PHH2O_INPUT,
				O.PHINPUT_YN,
				O.PPLI2CO3_AFTER_INPUT
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
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
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function update_pharvest($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET PHRAW_INPUT = "{$params['PHRAW_INPUT']}",PHLICL_AFTER_INPUT="{$params['PHLICL_AFTER_INPUT']}",PHNA2CO3_INPUT="{$params['PHNA2CO3_INPUT']}",PHH2O_INPUT="{$params['PHH2O_INPUT']}",PHINPUT_YN="Y"
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}
	public function del_pharvest($params)
	{
		$sql = <<<SQL
			UPDATE T_ORDER
			SET PHRAW_INPUT = NULL,
			PHLICL_AFTER_INPUT=NULL,
			PHNA2CO3_INPUT=NULL,
			PHH2O_INPUT=NULL,
			PHINPUT_YN=NULL
			WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function head_pprodcur($params, $start, $limit)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";
		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			PPINPUT_YN
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
			1
			{$where}
		LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}
	public function head_pprodcur_cut($params)
	{
		$where = '';
		if ($params['SDATE'] != "" && $params['EDATE'] != "")
			$where .= "AND {$params['DATE']} BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "")
			$where .= "AND ACT.ACT_NAME LIKE '%{$params['ACT_NAME']}%'";
		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "")
			$where .= "AND B.IDX = '{$params['BIZ_IDX']}'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			B.CUST_NM,
			O.START_DATE,
			O.END_DATE,
			PPINPUT_YN
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
			LEFT JOIN T_BIZ AS B ON ACT.BIZ_IDX = B.IDX
		WHERE
			1
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->num_rows();
	}

	public function detail_pprodcur($params)
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
				O.PPRAW_INPUT,
				O.PPLICL_INPUT,
				O.PPLICL_AFTER_INPUT,
				O.PPNA2CO3_INPUT,
				O.PPH2O_INPUT,
				O.PPNACL_AFTER_INPUT,
				O.PPLI2CO3_INPUT,
				O.PPNACL_INPUT,
				O.PPLI2CO3_AFTER_INPUT,
				O.PPINPUT_YN
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
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
				LEFT JOIN T_BIZ AS B ON A.BIZ_IDX = B.IDX
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function update_pprodcur($params)
	{
		$sql = <<<SQL
		UPDATE T_ORDER
		SET PPRAW_INPUT = "{$params['PPRAW_INPUT']}",
			PPLICL_INPUT = "{$params['PPLICL_INPUT']}",
			PPLICL_AFTER_INPUT = "{$params['PPLICL_AFTER_INPUT']}",
			PPNA2CO3_INPUT = "{$params['PPNA2CO3_INPUT']}",
			PPH2O_INPUT = "{$params['PPH2O_INPUT']}",
			PPNACL_AFTER_INPUT = "{$params['PPNACL_AFTER_INPUT']}",
			PPLI2CO3_INPUT = "{$params['PPLI2CO3_INPUT']}",
			PPNACL_INPUT = "{$params['PPNACL_INPUT']}",
			PPLI2CO3_AFTER_INPUT = "{$params['PPLI2CO3_AFTER_INPUT']}",
			PPINPUT_YN="Y"
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function del_pprodcur($params)
	{
		$sql = <<<SQL
			UPDATE T_ORDER
			SET PPRAW_INPUT = NULL,
			PPLICL_INPUT=NULL,
			PPLICL_AFTER_INPUT=NULL,
			PPNA2CO3_INPUT=NULL,
			PPH2O_INPUT = NULL,
			PPNACL_AFTER_INPUT=NULL,
			PPLI2CO3_INPUT=NULL,
			PPNACL_INPUT=NULL,
			PPLI2CO3_AFTER_INPUT=NULL,
			PPINPUT_YN=NULL
			WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;

		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
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
	public function ajax_prodmonitor2()
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
