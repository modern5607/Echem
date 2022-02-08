<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ordpln_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}

	// 기초 세팅 dual 호출
	public function ordpln_dual($params,$start=0,$limit=20)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		if (!empty($params['IDX']) && $params['IDX'] != "") {
			$this->db->where("IDX", $params['IDX']);
		}


		$this->db->limit($limit, $start);
		$this->db->order_by('ACT_DATE', 'DESC');
		$query = $this->db->get("T_ACT");
// echo $this->db->last_query();
		return $query->result();
	}

	public function ordpln_cut($params)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->from("T_ACT");
		$query = $this->db->get();
		return $query->row()->CUT;
	}




	// 주문/계획 - 주문등록 detail insert
	public function order_insert($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');


		$sql = <<<SQL
		INSERT T_ACT
			SET
			ACT_NAME   	= '{$params['ACT_NAME']}',
			ACT_DATE   	= '{$params['ACT_DATE']}',
			QTY 		= '{$params['QTY']}',
			DEL_DATE    = '{$params['DEL_DATE']}',	
			REMARK    	= '{$params['REMARK']}',
			BIZ_NAME    = '{$params['BIZ_NAME']}',
			BIZ_IDX    	= '{$params['BIZ_IDX']}',
			END_YN    	= 'N',
			INSERT_ID   = '{$username}',
			INSERT_DATE = '{$datetime}'
SQL;
		$this->db->query($sql);
		
		return $this->db->affected_rows();
	}

	// 주문/계획 - 주문등록 삭제
	public function del_order($idx)
	{
		$this->db->trans_start();
			$this->db->where("IDX", $idx);
			$this->db->delete("T_ACT");
		$this->db->trans_complete();
		// echo $this->db->last_query();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	// 주문/계획 - 주문등록 수정
	public function update_order($params)
	{

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');


		$sql = <<<SQL
		UPDATE T_ACT
			SET
			ACT_NAME   	= '{$params['ACT_NAME']}',
			ACT_DATE   	= '{$params['ACT_DATE']}',
			QTY 		= '{$params['QTY']}',
			DEL_DATE    = '{$params['DEL_DATE']}',	
			REMARK    	= '{$params['REMARK']}',
			BIZ_NAME    = '{$params['BIZ_NAME']}',
			BIZ_IDX    	= '{$params['BIZ_IDX']}',
			END_YN    	= 'N',
			UPDATE_ID   = '{$username}',
			UPDATE_DATE = '{$datetime}'
		WHERE
			IDX 		= "{$params['IDX']}"
SQL;
		$this->db->query($sql);
		
		return $this->db->affected_rows();
	}











	public function calendar_list($year, $month)
	{
		$sql=<<<SQL
			SELECT
			POR_NO,SUM(COUNT) AS COUNT,PLN_DATE
			FROM
			(
			SELECT *,COUNT(*) AS COUNT,PROC_PLN AS PLN_DATE,"PROC" AS GJ FROM T_ACTPLN WHERE PROC_PLN BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31'  GROUP BY POR_NO,PROC_PLN
			UNION
			SELECT *,COUNT(*),ASSE_PLN,"ASSE" FROM T_ACTPLN WHERE ASSE_PLN BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31' GROUP BY POR_NO,ASSE_PLN
			UNION
			SELECT *,COUNT(*),WELD_PLN,"WELD" FROM T_ACTPLN WHERE WELD_PLN BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31' GROUP BY POR_NO,WELD_PLN
			UNION
			SELECT *,COUNT(*),MRO_PLN,"MRO" FROM T_ACTPLN WHERE MRO_PLN BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31' GROUP BY POR_NO,MRO_PLN
			UNION
			SELECT *,COUNT(*),INRQDA,"INRQDA" FROM T_ACTPLN WHERE INRQDA BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31' GROUP BY POR_NO,INRQDA
			UNION
			SELECT *,COUNT(*),PKQDA,"PKQDA" FROM T_ACTPLN WHERE PKQDA BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31' GROUP BY POR_NO,PKQDA
			UNION
			SELECT *,COUNT(*),TRNDDA,"TRNDDA" FROM T_ACTPLN WHERE TRNDDA BETWEEN '{$year}-{$month}-01' AND '{$year}-{$month}-31' GROUP BY POR_NO,TRNDDA
			) AS A
			GROUP BY POR_NO,PLN_DATE
			ORDER BY PLN_DATE,POR_NO
SQL;
		
		$query=$this->db->query($sql);
		// echo nl2br($this->db->last_query());
		// echo var_dump($query->result());
		return $query->result();

		// $query = $this->db->like("WORK_DATE", $year . "-" . $month)
		// 	->get("T_WORKCAL");
		// return $query->result();
	}

	//캘린더 세부
	public function calendarInfo_list($YEAR, $MONTH, $DAY = '')
	{
		$where = "";
		if (!empty($DAY) && $DAY != "") {
			$where = " AND SUBSTRING(WORK_DATE, 9, 2) = '{$DAY}'";
		}
		$sql=<<<SQL
			SELECT SUBSTRING(WORK_DATE, 9, 2) AS DAY, REMARK, QTY
			FROM T_WORKCAL
			WHERE
				SUBSTRING(WORK_DATE, 1, 4) = '{$YEAR}' 
				AND SUBSTRING(WORK_DATE, 6, 2) = '{$MONTH}'
				{$where}
SQL;
		$query=$this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	

	public function calendar_up($params)
	{
		$query = $this->db->where("WORK_DATE", $params['WORK_DATE'])
			->get("T_WORKCAL");
		$chk = $query->row();
		$data = array(
			"status" => "",
			"msg"    => ""
		);
		if (!empty($chk)) {
			$this->db->set("REMARK", $params['REMARK']);
			$this->db->where("WORK_DATE", $chk->WORK_DATE);
			$this->db->update("T_WORKCAL");
			if ($this->db->affected_rows()) {
				$data['status'] = "ok";
				$data['msg'] = "수정되었습니다.";
			}
		} else {

			$datetime = date("Y-m-d H:i:s", time());
			$username = $this->session->userdata('user_name');

			$this->db->set("REMARK", $params['REMARK']);
			$this->db->set("WORK_DATE", $params['WORK_DATE']);
			$this->db->set("INSERT_DATE", $datetime);
			$this->db->set("INSERT_ID", $username);
			$this->db->insert("T_WORKCAL");

			if ($this->db->affected_rows()) {
				$data['status'] = "ok";
				$data['msg'] = "등록되었습니다.";
			}
		}

		return $data;
	}
	

	public function calendar_update($params)
	{
		$query = $this->db->where("WORK_DATE", $params['DATE'])
			->where("GB", $params['GB'])
			->get("T_WORKCAL");
		$chk = $query->row();
		$data = array(
			"status" => "",
			"msg"    => ""
		);

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		if (!empty($chk)) {
			$this->db->set("REMARK", $params['REMARK']);
			$this->db->set("QTY", $params['QTY']);
			$this->db->set("UPDATE_DATE", $datetime);
			$this->db->set("UPDATE_ID", $username);
			$this->db->where("WORK_DATE", $chk->WORK_DATE);
			$this->db->where("GB", $params['GB']);
			$this->db->update("T_WORKCAL");
			if ($this->db->affected_rows()) {
				$data['status'] = "ok";
				$data['msg'] = "수정되었습니다.";
			}
		} else {

			$this->db->set("REMARK", $params['REMARK']);
			$this->db->set("WORK_DATE", $params['DATE']);
			$this->db->set("QTY", $params['QTY']);
			$this->db->set("GB", $params['GB']);
			$this->db->set("INSERT_DATE", $datetime);
			$this->db->set("INSERT_ID", $username);
			$this->db->insert("T_WORKCAL");

			if ($this->db->affected_rows()) {
				$data['status'] = "ok";
				$data['msg'] = "등록되었습니다.";
			}
		}

		return $data;
	}
}
