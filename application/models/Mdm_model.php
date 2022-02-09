<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mdm_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();

		// $this->load->model(array(''));
	}


	/* 공통코드 HEAD 리스트 */
	public function get_cocdHead_list($params)
	{
		if (!empty($params['CODE']) && $params['CODE'] != "") {
			$this->db->like("CODE", $params['CODE']);
		}
		if (!empty($params['NAME']) && $params['NAME'] != "") {
			$this->db->like("NAME", $params['NAME']);
		}
		if (!empty($params['USE']) && $params['USE'] != "") {
			$this->db->where("USE_YN", $params['USE']);
		}

		$this->db->order_by("IDX");

		$res = $this->db->get("T_COCD_H");
		// echo $this->db->last_query();

		return $res->result();
	}

	/* 공통코드 Detail 리스트 */
	public function get_cocdDetail_list($hid = "", $params)
	{
		$data = array();


		if ($hid) {

			$this->db->select("D.*,H.CODE as H_CODE");
			$this->db->from("T_COCD_D as D");
			$this->db->join("T_COCD_H as H", "H.IDX = D.H_IDX");

			$this->db->where("D.H_IDX", $hid);

			if (!empty($params['D_CODE']) && $params['D_CODE'] != " ") {
				$this->db->like("D.CODE", $params['D_CODE']);
			}
			if (!empty($params['D_NAME']) && $params['D_NAME'] != " ") {
				$this->db->like("D.NAME", $params['D_NAME']);
			}
			if (!empty($params['D_USE']) && $params['D_USE'] != " ") {
				$this->db->where("D.USE_YN", $params['D_USE']);
			}

			$this->db->order_by("S_NO", "ASC");
			$res = $this->db->get();

			// echo $this->db->last_query();

			$data = $res->result();
		}

		return $data;
	}


	
	
	// 특정 공통코드의 디테일리스트를 호출
	public function get_selectInfo($fild,$set)
	{
		$where[$fild] = $set;
		$this->db->select("tch.IDX, tch.CODE, tch.NAME, tcd.CODE as D_CODE, tcd.NAME as D_NAME");
		$this->db->from("T_COCD_D as tcd");
		$this->db->join("T_COCD_H as tch","tch.IDX = tcd.H_IDX");
		
		$this->db->where("tcd.USE_YN","Y");
		$this->db->where("tch.USE_YN","Y");

		$this->db->where($where);
		$this->db->order_by("S_NO","ASC");
		$query = $this->db->get();
		//echo $this->db->last_query();

		return $query->result();		
	}

	public function ajax_items($params,$start=0,$limit=15)
	{
		$where='';

		if($params['ITEM_NAME']!="" && isset($params['ITEM_NAME']))
			$where.="AND ITEM_NAME LIKE '%{$params['ITEM_NAME']}%'";

		if($params['USEYN']!="" && isset($params['USEYN']))
			$where.="AND USE_YN ='{$params['USEYN']}'";

		$sql=<<<SQL
			SELECT *
			FROM T_ITEMS
			WHERE
			1
			{$where}
			LIMIT {$start},{$limit}

SQL;		
		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->result();
	}

	public function ajax_items_cut($params)
	{
		$where='';

		if($params['ITEM_NAME']!="" && isset($params['ITEM_NAME']))
			$where.="AND ITEM_NAME = '{$params['ITEM_NAME']}'";

		$sql=<<<SQL
			SELECT *
			FROM T_ITEMS
			WHERE
			1
			{$where}

SQL;		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	public function get_items($idx)
	{
		$sql=<<<SQL
			SELECT * FROM T_ITEMS WHERE IDX = '{$idx}'

SQL;
$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row();
	}

	public function set_item($params)
	{
		$sql = <<<SQL
			INSERT INTO T_ITEMS (ITEM_NAME,UNIT,USE_YN,INSERT_ID,INSERT_DATE) 
			VALUES('{$params['ITEM_NAME']}','{$params['UNIT']}','{$params['USE_YN']}','{$params['ID']}',now());
SQL;

		$query = $this->db->query($sql);

		return $this->db->affected_rows();
	}

	public function update_item($params)
	{
		$sql=<<<SQL
			UPDATE T_ITEMS
			SET 
			ITEM_NAME = "{$params['ITEM_NAME']}",
			UNIT = "{$params['UNIT']}",
			USE_YN = "{$params['USE_YN']}"
			WHERE IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	//업체관리 리스트
	public function biz_list($params,$start=0,$limit=20)
	{
		if (!empty($params['CUST_NM']) && $params['CUST_NM'] != "") {
			$this->db->like("CUST_NM", $params['CUST_NM']);
		}
		if (!empty($params['ADDRESS']) && $params['ADDRESS'] != "") {
			$this->db->like("ADDRESS", $params['ADDRESS']);
		}
		if (!empty($params['USEYN']) && $params['USEYN'] != "A") {
			$this->db->WHERE("USE_YN", $params['USEYN']);
		}

		$this->db->limit($limit,$start);
		$res = $this->db->get("T_BIZ");
		echo $this->db->last_query();
		return $res->result();
	}
	public function biz_cut($params)
	{
		$this->db->select("COUNT(*) as CUT");
		if(!empty($params['CUST_NM']) && $params['CUST_NM'] != ""){
			$this->db->like("CUST_NM",$params['CUST_NM']);
		}
		if(!empty($params['ADDRESS']) && $params['ADDRESS'] != "") {
			$this->db->like("ADDRESS",$params['ADDRESS']);
		}
		if (!empty($params['USEYN']) && $params['USEYN'] != "A") {
			$this->db->WHERE("USE_YN", $params['USEYN']);
		}
		$res = $this->db->get("T_BIZ");
		return $res->row()->CUT;
	}
	//업체관리 폼 리스트
	public function biz_form_list($idx)
	{
		$res = $this->db->where("IDX", $idx)
			->get("T_BIZ");
			echo $this->db->last_query();
		return $res->row();
	}
	//아이디 생성 중복 체크
	public function biz_nameChk($name)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("CUST_NM",$name);
		$query = $this->db->get("T_BIZ");
		$data['msg'] = "";
		$data['state'] = 1;
		if($query->row()->cnt > 0){
			$data['msg'] = "이미 등록된 업체입니다.";
			$data['state'] = 2;
		}
		return $data;
	}
	//업체 리스트 업데이트(추가,변경)
	public function biz_ins_up($param)
	{
		$dateTime = date("Y-m-d H:i:s", time());
		if ($param['mod'] == 1) {
			$data = array(
				'CUST_NM'		=> $param['CUST_NM'],
				'ADDRESS'		=> $param['ADDRESS'],
				'TEL'			=> $param['TEL'],
				'CUST_NAME'		=> $param['CUST_NAME'],
				'CUST_TYPE'		=> $param['CUST_TYPE'],
				'ITEM'			=> $param['ITEM'],
				'REMARK'		=> $param['REMARK'],
				'USE_YN'		=> $param['USE_YN'],
				'UPDATE_ID'		=> $param['INSERT_ID'],
				'UPDATE_DATE'	=> $dateTime,
				'COL1'			=> '',
				'COL2'			=> ''
			);

			$this->db->update("T_BIZ", $data, array("IDX" => $param['IDX']));
			return $param['IDX'];
		} else {
			$data = array(
				'CUST_NM'		=> $param['CUST_NM'],
				'ADDRESS'		=> $param['ADDRESS'],
				'TEL'			=> $param['TEL'],
				'CUST_NAME'		=> $param['CUST_NAME'],
				'CUST_TYPE'		=> $param['CUST_TYPE'],
				'ITEM'			=> $param['ITEM'],
				'REMARK'		=> $param['REMARK'],
				'USE_YN'		=> $param['USE_YN'],
				'INSERT_ID'		=> $param['INSERT_ID'],
				'INSERT_DATE'	=> $dateTime,
				'COL1'			=> '',
				'COL2'			=> ''
			);

			$this->db->insert("T_BIZ", $data);
			return $this->db->insert_id();
		}
	}


	/* 공통코드 HEAD 상세정보 */
	public function get_cocdHead_info($idx)
	{
		$res = $this->db->where("IDX", $idx)
			->get("T_COCD_H");
		return $res->row();
	}

	/* 공통코드 HEAD 등록 */
	public function codeHead_update($param)
	{

		if ($param['mod'] == 1) {

			$dateTime = date("Y-m-d H:i:s", time());

			$data = array(
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'USE_YN'      => $param['USE_YN'],
				'UPDATE_ID'   => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_COCD_H", $data, array("IDX" => $param['IDX']));
			return $param['IDX'];
		} else {

			$dateTime = date("Y-m-d H:i:s", time());

			$data = array(
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'REMARK'      => $param['REMARK'],
				'USE_YN'      => $param['USE_YN'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->insert("T_COCD_H", $data);

			return $this->db->insert_id();
		}
	}



	/* 공통코드 Detail 등록 */
	public function codeDetail_update($param)
	{

		if ($param['mod'] == 1) {

			$dateTime = date("Y-m-d H:i:s", time());


			$data = array(
				'H_IDX'           => $param['H_IDX'],
				'S_NO'            => $param['S_NO'],
				'CODE'           => $param['CODE'],
				'NAME'           => $param['NAME'],
				'USE_YN'      => $param['USE_YN'],
				'REMARK'        => $param['REMARK'],
				'UPDATE_ID'    => $param['INSERT_ID'],
				'UPDATE_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);
			$this->db->update("T_COCD_D", $data, array("IDX" => $param['IDX']));
			return $param['IDX'];
		} else {

			$dateTime = date("Y-m-d H:i:s", time());

			$data = array(
				'H_IDX'       => $param['H_IDX'],
				'S_NO'        => $param['S_NO'],
				'CODE'        => $param['CODE'],
				'NAME'        => $param['NAME'],
				'USE_YN'      => $param['USE_YN'],
				'REMARK'      => $param['REMARK'],
				'INSERT_ID'   => $param['INSERT_ID'],
				'INSERT_DATE' => $dateTime,
				'COL1'        => '',
				'COL2'        => ''
			);

			$this->db->insert("T_COCD_D", $data);

			return $this->db->insert_id();
		}
	}
	/* 공통코드 Detail 상세정보 */
	public function get_cocdDetail_info($idx)
	{
		$res = $this->db->where("IDX", $idx)
			->get("T_COCD_D");
		return $res->row();
	}

	//단순 멤버
	public function member_list2()
	{
		$sql=<<<SQL
			SELECT IDX,ID,NAME FROM T_MEMBER
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	//작업자 등록
	public function member_list($param,$start=0,$limit=20)
	{
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->like("ID",$param['ID']);
		}
		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->like("NAME",$param['NAME']);
		}
		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		if (!empty($param['USEYN']) && $param['USEYN'] != "A") {
			$this->db->where("STATE", $param['USEYN']);
		}

		$this->db->limit($limit,$start);
		$res = $this->db->get("T_MEMBER");
		return $res->result();
	}
	//작업자 등록 페이지 카운트
	public function member_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if(!empty($param['ID']) && $param['ID'] != ""){
			$this->db->where("ID",$param['ID']);
		}
		if(!empty($param['NAME']) && $param['NAME'] != ""){
			$this->db->where("NAME",$param['NAME']);
		}
		if(!empty($param['LEVEL']) && $param['LEVEL'] != ""){
			$this->db->where("LEVEL",$param['LEVEL']);
		}
		if (!empty($param['USEYN']) && $param['USEYN'] != "A") {
			$this->db->where("STATE", $param['USEYN']);
		}

		$res = $this->db->get("T_MEMBER");
		return $res->row()->CUT;
	}

	

	//작업자 팝업
	public function member_form($idx)
	{
		$data = $this->db->where(array('IDX'=>$idx))
						->get("T_MEMBER");
		return $data->row();
	}
	// 작업자 추가 또는 수정
	public function member_ins_up($params,$idx)
	{
		if(!empty($idx)){
			$this->db->update("T_MEMBER",$params,array("IDX"=>$idx));
		}else{
			$this->db->insert("T_MEMBER",$params);
			$idx = $this->db->insert_id();
		}
		return $idx;
	}
	//아이디 생성 중복 체크
	public function member_idChk($id)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("ID",$id);
		$query = $this->db->get("T_MEMBER");
		$data['msg'] = "사용가능한 아이디입니다.";
		$data['state'] = 1;
		if($query->row()->cnt > 0){
			$data['msg'] = "사용중인 아이디입니다.";
			$data['state'] = 2;
		}
		return $data;
	}
	//아이디 생성 중복 체크
	public function member_nameChk($name)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("SUBSTRING_INDEX(NAME,'(',1)",$name);
		$query = $this->db->get("T_MEMBER");
		// echo $this->db->last_query();
		$data['state'] = 1;
		if($query->row()->cnt > 0){
			$data['state'] = 2;
			$data['cnt'] = $query->row()->cnt;
		}
		return $data;
	}


	//캘린더 전체
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

	/* 코드중복검사 */
	public function ajax_cocdHaedchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_COCD_H');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}

	/* 코드중복검사 */
	public function ajax_cocdDetailchk($object,$code)
	{
		$this->db->where($object,$code);
        $query = $this->db->get('T_COCD_D');
         
        if($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
	}
	public function ajax_bizcur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function ajax_person()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_personcur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
}
