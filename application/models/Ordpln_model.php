<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ordpln_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}

	// head 주문등록
	public function head_order($params,$start=0,$limit=20)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "") {
			$this->db->like("A.ACT_NAME", $params['ACT_NAME']);
		}
		if (!empty($params['IDX']) && $params['IDX'] != "") {
			$this->db->where("A.IDX", $params['IDX']);
		}
		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "") {
			$this->db->where("A.BIZ_IDX", $params['BIZ_IDX']);
		}


		$this->db->select("( SELECT START_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS SDATE,( SELECT END_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS EDATE, A.*, B.CUST_NM");
		$this->db->join("T_BIZ as B", "B.IDX = A.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$this->db->order_by('INSERT_DATE', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get("T_ACT as A");
		//echo $this->db->last_query();
		return $query->result();
	}

	public function head_order_cut($params)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("ACT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		if (!empty($params['ACT_NAME']) && $params['ACT_NAME'] != "") {
			$this->db->like("A.ACT_NAME", $params['ACT_NAME']);
		}
		if (!empty($params['IDX']) && $params['IDX'] != "") {
			$this->db->where("A.IDX", $params['IDX']);
		}
		if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "") {
			$this->db->where("A.BIZ_IDX", $params['BIZ_IDX']);
		}


		$this->db->select("( SELECT START_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS SDATE,( SELECT END_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS EDATE, A.*, B.CUST_NM");
		$this->db->join("T_BIZ as B", "B.IDX = A.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$this->db->order_by('INSERT_DATE', 'DESC');
		$query = $this->db->get("T_ACT as A");
		// echo $this->db->last_query();
		return $query->num_rows();
	}
	public function act_check($params)
	{
		$this->db->select("COUNT(*) as CUT");
		$this->db->where("ACT_IDX", $params['IDX']);
		$query = $this->db->get("T_ORDER");
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
			-- BIZ_NAME    = '{$params['BIZ_NAME']}',
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

//---------------------------------------------주문등록 복사----------------------------
// head 주문등록
public function head_order1($params,$start=0,$limit=20)
{
	if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
		$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
	}
	if (!empty($params['REMARK']) && $params['REMARK'] != "") {
		$this->db->like("A.REMARK", $params['REMARK']);
	}
	if (!empty($params['IDX']) && $params['IDX'] != "") {
		$this->db->where("A.IDX", $params['IDX']);
	}
	if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "") {
		$this->db->where("A.BIZ_IDX", $params['BIZ_IDX']);
	}


	$this->db->select("( SELECT START_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS SDATE,( SELECT END_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS EDATE, A.*, B.CUST_NM");
	$this->db->join("T_BIZ as B", "B.IDX = A.BIZ_IDX","LEFT");
	$this->db->order_by('INSERT_DATE', 'DESC');
	$this->db->limit($limit, $start);
	$query = $this->db->get("T_ECHEM as A");
	//echo $this->db->last_query();
	return $query->result();
}

public function head_order_cut1($params)
{
	if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
		$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
	}
	if (!empty($params['REMARK']) && $params['REMARK'] != "") {
		$this->db->like("A.REMARK", $params['REMARK']);
	}
	if (!empty($params['IDX']) && $params['IDX'] != "") {
		$this->db->where("A.IDX", $params['IDX']);
	}
	if (!empty($params['BIZ_IDX']) && $params['BIZ_IDX'] != "") {
		$this->db->where("A.BIZ_IDX", $params['BIZ_IDX']);
	}


	$this->db->select("( SELECT START_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS SDATE,( SELECT END_DATE FROM T_ORDER AS O WHERE O.ACT_IDX = A.IDX ) AS EDATE, A.*, B.CUST_NM");
	$this->db->join("T_BIZ as B", "B.IDX = A.BIZ_IDX","LEFT");
	$this->db->order_by('INSERT_DATE', 'DESC');
	$query = $this->db->get("T_ECHEM as A");
	 //echo $this->db->last_query();
	return $query->num_rows();
}
public function act_check1($params)
{
	$this->db->select("COUNT(*) as CUT");
	$this->db->where("ACT_IDX", $params['IDX']);
	$query = $this->db->get("T_ORDER");
	return $query->row()->CUT;
}

// 주문/계획 - 주문등록 detail insert
public function order_insert1($params)
{
	$datetime = date("Y-m-d H:i:s", time());
	$username = $this->session->userdata('user_name');


	$sql = <<<SQL
	INSERT T_ECHEM
		SET
		INSERT_DATE	= '{$params['INSERT_DATE']}',
		T_TANK   	= '{$params['T_TANK']}',
		T_SU   		= '{$params['T_SU']}',
		T_JD   		= '{$params['T_JD']}',
		NA2CO3_IN   = '{$params['NA2CO3_IN']}',
		NA2CO3   	= '{$params['NA2CO3']}',
		OT_OUT   	= '{$params['OT_OUT']}',
		OT_COL   	= '{$params['OT_COL']}',
		REMARK   	= '{$params['INSERT_DATE']} 생산건' ,
		USE_WL   	= '{$params['USE_WL']}',
		REMARK1   	= '{$params['INSERT_DATE']} 생산건' ,
		REMARK01   	= '{$params['REMARK01']}',
		OT_NA   	= '{$params['OT_NA']}',
		OT_WT   	= '{$params['OT_WT']}',
		L_WL    	= '{$params['L_WL']}',
		L_JD    	= '{$params['L_JD']}',
		L_SU		= '{$params['L_SU']}',
		U_WL   		= '{$params['U_WL']}',
		U_JD   		= '{$params['U_JD']}',
		D_WL   		= '{$params['D_WL']}',
		U_SU   		= '{$params['U_SU']}',
		D_JD   		= '{$params['D_JD']}',
		D_SU   		= '{$params['D_SU']}',
		Z_WL   		= '{$params['Z_WL']}',
		Z_JD   		= '{$params['Z_JD']}',
		Z_SU   		= '{$params['Z_SU']}',
		PS_1   		= '{$params['PS_1']}',
		PS_2   		= '{$params['PS_2']}',
		PS_3   		= '{$params['PS_3']}'
SQL;
	$this->db->query($sql);

	/*$sql1 = <<<SQL
	UPDATE T_STOCK
		SET
		STOCK   	= STOCK + '{$params['OT_OUT']}'
	WHERE IDX = '1'
SQL;
	$this->db->query($sql1);*/
	
	return $this->db->affected_rows();
}
// 주문/계획 - 주문등록 삭제
public function del_order1($idx)
{
	$this->db->trans_start();
		$this->db->where("IDX", $idx);
		$this->db->delete("T_ECHEM");
	$this->db->trans_complete();
	// echo $this->db->last_query();

	$data = 0;
	if ($this->db->trans_status() !== FALSE) {
		$data = 1;
	}

	return $data;
}
// 주문/계획 - 주문등록 수정
public function update_order1($params)
{

	$datetime = date("Y-m-d H:i:s", time());
	$username = $this->session->userdata('user_name');


	$sql = <<<SQL
	UPDATE T_ECHEM
		SET
		INSERT_DATE	= '{$params['INSERT_DATE']}',
		T_TANK   	= '{$params['T_TANK']}',
		T_SU   		= '{$params['T_SU']}',
		T_JD   		= '{$params['T_JD']}',
		NA2CO3_IN   = '{$params['NA2CO3_IN']}',
		NA2CO3   	= '{$params['NA2CO3']}',
		OT_OUT   	= '{$params['OT_OUT']}',
		OT_COL   	= '{$params['OT_COL']}',
		REMARK   	= '{$params['INSERT_DATE']} 생산건' ,
		USE_WL   	= '{$params['USE_WL']}',
		REMARK1   	= '{$params['INSERT_DATE']} 생산건' ,
		REMARK01   	= '{$params['REMARK01']}',
		OT_NA   	= '{$params['OT_NA']}',
		OT_WT   	= '{$params['OT_WT']}',
		L_WL    	= '{$params['L_WL']}',
		L_JD    	= '{$params['L_JD']}',
		L_SU		= '{$params['L_SU']}',
		U_WL   		= '{$params['U_WL']}',
		U_JD   		= '{$params['U_JD']}',
		D_WL   		= '{$params['D_WL']}',
		U_SU   		= '{$params['U_SU']}',
		D_JD   		= '{$params['D_JD']}',
		D_SU   		= '{$params['D_SU']}',
		Z_WL   		= '{$params['Z_WL']}',
		Z_JD   		= '{$params['Z_JD']}',
		Z_SU   		= '{$params['Z_SU']}',
		PS_1   		= '{$params['PS_1']}',
		PS_2   		= '{$params['PS_2']}',
		PS_3   		= '{$params['PS_3']}'
	WHERE
		IDX 		= "{$params['IDX']}"
SQL;
	$this->db->query($sql);

	
	return $this->db->affected_rows();
}

//----------------------------------------------주문등록 복사한거 끝-----------------------------------------
	//캘린더 세부
	public function calendarInfo_list($YEAR, $MONTH, $DAY = '')
	{
		$where = "";
		if (!empty($DAY) && $DAY != "") {
			$where = " AND SUBSTRING(WORK_DATE, 9, 2) = '{$DAY}'";
		}
		$sql=<<<SQL
			SELECT SUBSTRING(WORK_DATE, 9, 2) AS DAY, REMARK, REMARK1,QTY
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
			$this->db->set("REMARK1", $params['REMARK1']);
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
			$this->db->set("REMARK1", $params['REMARK1']);
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

	public function get_member()
	{
		$sql=<<<SQL
			SELECT 
				IDX,NAME
			FROM
				T_MEMBER
SQL;
		return $this->db->query($sql)->result();
	}
	public function ajax_vacation($YEAR, $MONTH, $DAY = '')
	{
		$where = "";
		if (!empty($DAY) && $DAY != "") {
			$where = " AND SUBSTRING(VACATION_DATE, 9, 2) = '{$DAY}'";
		}
		$sql=<<<SQL
			SELECT 
				V.IDX,
				SUBSTRING( VACATION_DATE, 9, 2 ) AS DAY,
				VACATION_DATE,
				MEMBER_IDX,
				M.NAME,
				REMARK
			FROM 
				T_VACATION2 V
				LEFT JOIN T_MEMBER AS M ON M.IDX = V.MEMBER_IDX
			WHERE
				SUBSTRING(VACATION_DATE, 1, 4) = '{$YEAR}' 
				AND SUBSTRING(VACATION_DATE, 6, 2) = '{$MONTH}'
				{$where}
SQL;
		$query=$this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();

	}

	public function vacation_insert($params)
	{
		$username = $this->session->userdata('user_name');
		$sql=<<<SQL
			INSERT INTO T_VACATION2 (VACATION_DATE,REMARK,INSERT_ID,INSERT_DATE,MEMBER_IDX)
			VALUES ('{$params['DATE']}','{$params['REMARK']}','{$username}',NOW(),'{$params['MEMBER_IDX']}')
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $this->db->affected_rows();
	}

	public function vacation_update($params)
	{
		$sql=<<<SQL
			UPDATE T_VACATION2
			SET VACATION_DATE= '{$params['DATE']}',REMARK = '{$params['REMARK']}',MEMBER_IDX = '{$params['MEMBER_IDX']}'
			WHERE IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	public function vacation_delete($params)
	{
		$sql=<<<SQL
			DELETE FROM T_VACATION2
			WHERE IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);
		return $this->db->affected_rows();
	}
}
