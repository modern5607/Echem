<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prod_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}

	public function head_workorder($params)
	{
		$where='';
		if($params['SDATE']!="" && $params['EDATE']!="")
			$where.="AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			ACT.ACT_DATE,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.START_DATE,
			O.END_DATE 
		FROM
			T_ACT AS ACT
			LEFT JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
		1
		{$where}
SQL;
		$query = $this->db->query($sql);
		echo $this->db->Last_query();
		return $query->result();
	}

	public function detail_workorder($params)
	{
		$where = '';
		if (!empty($params['IDX']) && !empty($params['ACT_IDX'])) 
		{
			$sql = <<<SQL
			SELECT
				O.IDX,
				O.ACT_IDX,
				O.ORDER_DATE,
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY,
				O.START_DATE,
				O.END_DATE,
				O.REMARK 
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
			WHERE
			A.IDX ={$params['ACT_IDX']}
			AND O.IDX = {$params['IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();

		} else if(empty($params['IDX']))
		{
			$sql = <<<SQL
			SELECT
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY
			FROM
				T_ACT A 
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
		$sql=<<<SQL
			INSERT INTO T_ORDER(ACT_IDX,ORDER_DATE,START_DATE,END_DATE,REMARK,INSERT_ID,INSERT_DATE)
			VALUE("{$params['HIDX']}",NOW(),"{$params['START_DATE']}","{$params['END_DATE']}","{$params['REMARK']}","{$params['INSERT_ID']}",NOW())
SQL;
		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function update_workorder($params)
	{
		$sql=<<<SQL
			UPDATE T_ORDER
			SET START_DATE = "{$params['START_DATE']}",END_DATE = "{$params['END_DATE']}", REMARK="{$params['REMARK']}",EACHORDER="N"
			WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;
		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function head_pworkorder($params)
	{
		$where='';
		if($params['SDATE']!="" && $params['EDATE']!="")
			$where.="AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
			

		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.START_DATE,
			O.END_DATE,
			EACHORDER
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
		1
		{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function detail_pworkorder($params)
	{
		if (!empty($params['IDX']) && !empty($params['ACT_IDX'])) 
		{
			$sql = <<<SQL
			SELECT
				O.IDX,
				O.ACT_IDX,
				O.ORDER_DATE,
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY,
				O.START_DATE,
				O.END_DATE,
				O.RAW_DATE,
				O.NA2CO3_DATE,
				O.MIX_DATE,
				O.WASH_DATE,
				O.DRY_DATE,
				O.REMARK,
				O.EACHORDER
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
			WHERE
			A.IDX ={$params['ACT_IDX']}
			AND O.IDX = {$params['IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();

		} else if(empty($params['IDX']))
		{
			$sql = <<<SQL
			SELECT
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY
			FROM
				T_ACT A 
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
		$sql=<<<SQL
		UPDATE T_ORDER
		SET RAW_DATE = "{$params['RAW_DATE']}",NA2CO3_DATE="{$params['NA2CO3_DATE']}",MIX_DATE="{$params['MIX_DATE']}",WASH_DATE="{$params['WASH_DATE']}",DRY_DATE = "{$params['DRY_DATE']}",REMARK="{$params['REMARK']}",EACHORDER="Y"
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

	public function head_matinput($params)
	{
		$where='';
		if($params['SDATE']!="" && $params['EDATE']!="")
			$where.="AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
			
		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.START_DATE,
			O.END_DATE,
			EACHORDER,
			RAWINPUT
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			EACHORDER = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function detail_matinput($params)
	{
		if (!empty($params['IDX']) && !empty($params['ACT_IDX'])) 
		{
			$sql = <<<SQL
			SELECT
				O.IDX,
				O.ACT_IDX,
				O.ORDER_DATE,
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY,
				O.START_DATE,
				O.END_DATE,
				O.RAW,
				O.NA2CO3,
				O.LICL,
				O.NACL,
				O.REMARK,
				O.EACHORDER
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
			WHERE
			A.IDX ={$params['ACT_IDX']}
			AND O.IDX = {$params['IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();

		} else if(empty($params['IDX']))
		{
			$sql = <<<SQL
			SELECT
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY
			FROM
				T_ACT A 
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
		$sql=<<<SQL
		UPDATE T_ORDER
		SET RAW = "{$params['RAW']}",NA2CO3="{$params['NA2CO3']}",LICL="{$params['LICL']}",NACL="{$params['NACL']}",REMARK="{$params['REMARK']}",RAWINPUT="Y"
		WHERE IDX = "{$params['IDX']}" AND ACT_IDX = "{$params['HIDX']}"
SQL;
		
		$this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();

	}

	public function head_pharvest($params)
	{
		$where='';
		if($params['SDATE']!="" && $params['EDATE']!="")
			$where.="AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
			
		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.START_DATE,
			O.END_DATE,
			EACHORDER,
			RAWINPUT
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			EACHORDER = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function detail_pharvest($params)
	{
		if (!empty($params['IDX']) && !empty($params['ACT_IDX'])) 
		{
			$sql = <<<SQL
			SELECT
				O.IDX,
				O.ACT_IDX,
				O.ORDER_DATE,
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY,
				O.START_DATE,
				O.END_DATE,
				O.RAW,
				O.NA2CO3,
				O.LICL,
				O.NACL,
				O.REMARK,
				O.EACHORDER
			FROM
				T_ORDER O
				LEFT JOIN T_ACT A ON A.IDX = O.ACT_IDX 
			WHERE
			A.IDX ={$params['ACT_IDX']}
			AND O.IDX = {$params['IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();

		} else if(empty($params['IDX']))
		{
			$sql = <<<SQL
			SELECT
				A.ACT_NAME,
				A.BIZ_NAME,
				A.ACT_DATE,
				A.DEL_DATE,
				A.QTY
			FROM
				T_ACT A 
			WHERE
			A.IDX ={$params['ACT_IDX']}
SQL;

			$query = $this->db->query($sql);
			// echo $this->db->last_query();
			return $query->row();
		}
	}

	public function head_pprodcur($params)
	{
		$where='';
		if($params['SDATE']!="" && $params['EDATE']!="")
			$where.="AND ACT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
			
		$sql = <<<SQL
		SELECT
			ACT.IDX ACT_IDX,
			O.IDX,
			O.ORDER_DATE,
			ACT.ACT_NAME,
			ACT.BIZ_IDX,
			ACT.BIZ_NAME,
			O.START_DATE,
			O.END_DATE,
			EACHORDER,
			RAWINPUT
		FROM
			T_ACT AS ACT
			JOIN T_ORDER AS O ON O.ACT_IDX = ACT.IDX
		WHERE
			EACHORDER = 'Y'
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
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
