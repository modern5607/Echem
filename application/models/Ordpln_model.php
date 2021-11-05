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
		$this->db->select("1 as col1, 2 as col2, 3 as col3, 4 as col4, 5 as col5, 6 as col6, 7 as col7, 8 as col8, 9 as col9");

		$sql=<<<SQL
			SELECT 1 as col1, 2 as col2, 3 as col3, 4 as col4, 5 as col5, 6 as col6, 7 as col7, 8 as col8, 9 as col9
			FROM DUAL
			LIMIT {$start},{$limit}
SQL;

	$query = $this->db->query($sql);
	return $query->result();
	}

	public function ordpln_cut($params)
	{
		$sql=<<<SQL
			SELECT count(1) as CUT 
			FROM DUAL
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query($sql);
		return $query->num_rows();
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
	public function calendarInfo_list($date)
	{
		$sql=<<<SQL
			SELECT POR_NO,POR_SEQ,MCCSDESC,GJ
			FROM
			(
				SELECT *,"절단" AS GJ FROM T_ACTPLN WHERE PROC_PLN='{$date}'
				UNION
				SELECT *,"취부" AS GJ FROM T_ACTPLN WHERE ASSE_PLN='{$date}'
				UNION
				SELECT *,"용접" AS GJ FROM T_ACTPLN WHERE WELD_PLN='{$date}'
				UNION
				SELECT *,"사상" AS GJ FROM T_ACTPLN WHERE MRO_PLN='{$date}'
				UNION
				SELECT *,"제작검사" AS GJ FROM T_ACTPLN WHERE INRQDA='{$date}'
				UNION
				SELECT *,"PK검사" AS GJ FROM T_ACTPLN WHERE PKQDA='{$date}'
				UNION
				SELECT *,"배송계획" AS GJ FROM T_ACTPLN WHERE TRNDDA='{$date}'
			) AS A
			ORDER BY POR_NO,POR_SEQ
SQL;
		$query=$this->db->query($sql);
		// echo nl2br($this->db->last_query());
		// echo var_dump($query->result());
		return $query->result();

		// $query = $this->db->where("WORK_DATE", $date)->get("T_WORKCAL");
		// return $query->row();
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
}
