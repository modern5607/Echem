<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Offday_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}

	// 기초 세팅 dual 호출
	public function head_month($params,$start=0,$limit=20)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("VACATION_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}

		$this->db->select("V.IDX,VACATION_DATE,NAME,REMARK");
		$this->db->join("T_MEMBER AS M", "M.IDX = V.MEMBER_IDX","LEFT");
		$this->db->order_by('VACATION_DATE', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get("T_VACATION as V");
		//echo $this->db->last_query();
		return $query->result();
	}

	public function head_month_cut($params)
	{
		if ((!empty($params['IDATE']) && $params['IDATE'] != "")) {
			$this->db->where("INSERT_DATE BETWEEN '{$params['IDATE']}'");
		}
		if (!empty($params['IDX']) && $params['IDX'] != "") {
			$this->db->where("V.IDX", $params['IDX']);
		}


		$this->db->select("( SELECT INSER_DATE FROM T_VACATION AS V WHERE V.IDX = V.IDX)");
		$this->db->join("T_BIZ as B", "B.IDX = A.BIZ_IDX","LEFT");
		$this->db->order_by('VACATION_DATE', 'DESC');
		$this->db->order_by('INSERT_DATE', 'DESC');
		$query = $this->db->get("T_VACATION as V");
		return $query->num_rows();
	}
	public function act_check($params)
	{
		$this->db->select("COUNT(*) as CUT");
		$this->db->where("INSERT_DATE", $params['IDX']);
		$query = $this->db->get("T_VACAVATION");
		return $query->row()->CUT;
	}




	// 연차 detail insert
	public function offday_insert($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
		INSERT T_VACATION
			SET
			VACATION_DATE   = '{$params['VACATION_DATE']}',
			MEMBER_IDX   		= '{$params['MEMBER_IDX']}',
			REMARK    			= '{$params['REMARK']}',
			INSERT_ID   		= '{$username}',
			INSERT_DATE 		= '{$datetime}'
SQL;
		$this->db->query($sql);
		
		return $this->db->affected_rows();
	}

	//연차 삭제
	public function offday_del($params)
	{
		$this->db->trans_start();
			$this->db->where("IDX", $params);
			$this->db->delete("T_VACATION");
		$this->db->trans_complete();
		//echo $this->db->last_query();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	// 연차 수정
	
	public function update_offday($params)
	{

		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');


		$sql = <<<SQL
		UPDATE T_VACATION
			SET
			INSERT_DATE   	= '{$datetime}',
			INSERT_ID   	= '{$username}',
			REMARK    	= '{$params['REMARK']}',
			VACATION_DATE = '{$params['VACATION_DATE']}'
		
		WHERE
			IDX 		= '{$params['IDX']}'
SQL;
		$this->db->query($sql);
		//echo $this->db->last_query();
		return $this->db->affected_rows();
	}








//----------------------------------캘린더-----------//
	//캘린더 세부
	public function offCalendarInfo_list($YEAR, $MONTH, $DAY = '')
	{
		$where = "";
		if (!empty($DAY) && $DAY != "") {
			$where = " AND SUBSTRING(VACATION_DATE, 9, 2) = '{$DAY}'";
		}
		$sql=<<<SQL
			SELECT SUBSTRING(VACATION_DATE, 9, 2) AS DAY, REMARK, MEMBER_IDX, M.NAME
			FROM T_VACATION, T_MEMBER M
			WHERE
				SUBSTRING(VACATION_DATE, 1, 4) = '{$YEAR}' 
				AND SUBSTRING(VACATION_DATE, 6, 2) = '{$MONTH}'
				AND MEMBER_IDX = M.IDX
				{$where}
SQL;
		$query=$this->db->query($sql);
		//echo $this->db->last_query();
		return $query->result();
	}
	

	// public function calendar_up($params)
	// {
	// 	$query = $this->db->where("VACATION_DATE", $params["VACATION_DATE"])
	// 		->get("T_VACATION");
	// 	$chk = $query->row();
	// 	$data = array(
	// 		"status" => "",
	// 		"msg"    => ""
	// 	);
	// 	if (!empty($chk)) {
	// 		$this->db->set("REMARK", $params["REMARK"]);
	// 		$this->db->set("MEMBER_IDX", $params["MEMBER_IDX"]);
	// 		$this->db->where("VACATION_DATE", $chk->VACATION_DATE);
			
	// 		$this->db->update("T_VACATION");

			
	// 		if ($this->db->affected_rows()) {
	// 			$data['status'] = "ok";
	// 			$data['msg'] = "수정되었습니다.";
	// 		}
	// 	} else {

	// 		$datetime = date("Y-m-d H:i:s", time());
	// 		$username = $this->session->userdata('user_name');

	// 		$this->db->set("REMARK", $params['REMARK']);
	// 		$this->db->set("VACATION_DATE", $params['VACATION_DATE']);
	// 		$this->db->set("MEMBER_IDX", $params["MEMBER_IDX"]);
	// 		$this->db->set("INSERT_DATE", $datetime);
	// 		$this->db->set("INSERT_ID", $username);
	// 		$this->db->insert("T_VACATION");

	// 		if ($this->db->affected_rows()) {
	// 			$data['status'] = "ok";
	// 			$data['msg'] = "등록되었습니다.";
	// 		}
	// 	}
		
	// 	return $data;
	// }
	

	// public function offdaycal_update($params)
	// {
	// 	$query = $this->db->where("VACATION_DATE", $params['DATE'])
	// 		->where("MEMBER_IDX", $params['MEMBER_IDX'])
	// 		->get("T_VACATION");
	// 	$chk = $query->row();
	// 	$data = array(
	// 		"status" => "",
	// 		"msg"    => ""
	// 	);

	// 	$datetime = date("Y-m-d H:i:s", time());
	// 	$username = $this->session->userdata('user_name');

	// 	if (!empty($chk)) {
	// 		$this->db->set("REMARK", $params['REMARK']);
	// 		$this->db->set("MEMBER_IDX", $params['MEMBER_IDX']);
	// 		$this->db->where("VACATION_DATE", $chk->VACATION_DATE);	
	// 		$this->db->update("T_VACTION");
	// 		if ($this->db->affected_rows()) {
	// 			$data['status'] = "ok";
	// 			$data['msg'] = "수정되었습니다.";
	// 		}
	// 	} else {

	// 		$this->db->set("REMARK", $params['REMARK']);
	// 		$this->db->set("WORK_DATE", $params['DATE']);
	// 		$this->db->set("QTY", $params['QTY']);
	// 		$this->db->set("GB", $params['GB']);
	// 		$this->db->set("INSERT_DATE", $datetime);
	// 		$this->db->set("INSERT_ID", $username);
	// 		$this->db->insert("T_VACTION");

	// 		if ($this->db->affected_rows()) {
	// 			$data['status'] = "ok";
	// 			$data['msg'] = "등록되었습니다.";
	// 		}
	// 	}
		
	// 	return $data;
	// }
	public function detail_month($params)
	{
		$sql=<<<SQL
		SELECT
			V.IDX,
			VACATION_DATE,
			NAME,
			REMARK 
		FROM
			T_VACATION AS V,
			T_MEMBER AS M 
		WHERE
			V.IDX = '{$params['MEMBER_IDX']}'
			AND M.IDX = V.MEMBER_IDX
SQL;

		$query = $this->db->query($sql);
		//echo $this->db->last_query();
		return $query->row();
	}

	public function get_member()
	{
		$sql=<<<SQL
			SELECT IDX,NAME FROM T_MEMBER
SQL;

	$query = $this->db->query($sql);
	//echo $this->db->last_query();
	return $query->result();
	}
}
