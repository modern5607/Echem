<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Echem_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}




	// 구매관리 - 원자재 리스트
	public function component_list($params,$start,$limit)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("COM.IDX,ACT_DATE,BIZ_IDX,CUST_NM,QTY,UNIT,DEL_DATE,COM.REMARK,END_YN,END_DATE,QTY2,REMARK2");
		$this->db->join("T_BIZ AS B", "B.IDX = COM.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_COMPONENT AS COM");
		// echo $this->db->last_query();
		return $query->result();
	}
	public function component_list_cnt($params)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("COM.IDX,ACT_DATE,BIZ_IDX,CUST_NM,QTY,UNIT,DEL_DATE,COM.REMARK,END_YN,END_DATE,QTY2,REMARK2");
		$this->db->join("T_BIZ AS B", "B.IDX = COM.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$query = $this->db->get("T_COMPONENT AS COM");
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	// 구매관리 - 원자재 발주등록
	public function component_head_insert($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
		INSERT T_COMPONENT
			SET
			ACT_DATE   	= '{$params['ACT_DATE']}',
			BIZ_IDX 	= '{$params['BIZ_IDX']}',
			QTY 		= '{$params['QTY']}',
			UNIT     	= '{$params['UNIT']}',
			DEL_DATE    = '{$params['DEL_DATE']}',	
			REMARK    	= '{$params['REMARK']}',
			END_YN    	= 'N',
			INSERT_ID   = '{$username}',
			INSERT_DATE = '{$datetime}'
SQL;
		$this->db->query($sql);
		
		return $this->db->affected_rows();
	}

	// 구매관리 - 원자재 발주 삭제
	public function del_component($idx)
	{
		$this->db->trans_start();
			$this->db->where("IDX", $idx);
			$this->db->delete("T_COMPONENT");
		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	// 구매관리 - 원자재 입고
	public function end_component($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$this->db->trans_start();
			$this->db->set("END_YN", "Y");
			$this->db->set("END_DATE", $datetime);
			$this->db->set("UPDATE_DATE", $datetime);
			$this->db->set("UPDATE_ID", $username);
			$this->db->set("QTY2", $params['QTY']);
			$this->db->set("REMARK2", $params['REMARK']);
			$this->db->where("IDX", $params['IDX']);
			$this->db->update("T_COMPONENT");
		$this->db->trans_complete();
// echo $this->db->last_query();
		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}


	// 원자재 신규등록
	public function component_list1($params,$start,$limit)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("COM.IDX,ACT_DATE,BIZ_IDX,CUST_NM,QTY,UNIT,DEL_DATE,COM.REMARK,END_YN,END_DATE,QTY2,REMARK2,IGTYPE");
		$this->db->join("T_BIZ AS B", "B.IDX = COM.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_COMPONENT AS COM");
		 //echo $this->db->last_query();
		return $query->result();
	}
	public function component_list_cnt1($params)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("COM.IDX,ACT_DATE,BIZ_IDX,CUST_NM,QTY,UNIT,DEL_DATE,COM.REMARK,END_YN,END_DATE,QTY2,REMARK2,IGTYPE");
		$this->db->join("T_BIZ AS B", "B.IDX = COM.BIZ_IDX","LEFT");
		$this->db->order_by('ACT_DATE', 'DESC');
		$query = $this->db->get("T_COMPONENT AS COM");
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	// 구매관리 - 원자재 발주등록
	public function component_head_insert1($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
		INSERT T_COMPONENT
			SET
			ACT_DATE   	= '{$params['ACT_DATE']}',
			BIZ_IDX 	= '{$params['BIZ_IDX']}',
			QTY 		= '{$params['QTY']}',
			UNIT     	= '{$params['UNIT']}',
			DEL_DATE    = '{$params['DEL_DATE']}',	
			REMARK    	= '{$params['REMARK']}',
			IGTYPE    	= '{$params['IGTYPE']}',
			END_YN    	= 'N',
			INSERT_ID   = '{$username}',
			INSERT_DATE = '{$datetime}'
SQL;
		$this->db->query($sql);

		$sql1 = <<<SQL
		UPDATE T_BIZ
			SET			
			STOCK 		= STOCK + '{$params['QTY']}'
			WHERE IDX 	= '{$params['BIZ_IDX']}'
SQL;
		$this->db->query($sql1);
		
		return $this->db->affected_rows();
	}

	// 구매관리 - 원자재 발주 삭제
	public function del_component1($idx)
	{
		$this->db->trans_start();
			$this->db->where("IDX", $idx);
			$this->db->delete("T_COMPONENT");
		$this->db->trans_complete();

		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	public function equip_chart($params,$start=0,$limit=20) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		if(!empty($params['CHART'])){
			$this->db->order_by("INSERT_DATE","ASC");
		}else{
			$this->db->order_by("INSERT_DATE","DESC");
		}
		
		$toDate = date("Y-m-d",time());
		//$toDate = "2020-09-11";
		$this->db->SELECT("*,450 AS ACT, SUBSTR(INSERT_DATE,1,4) AS YE, SUBSTR(INSERT_DATE,6,2) AS MO , SUBSTR(INSERT_DATE,9,2) AS DA");
		$this->db->limit($limit,$start);

		$query = $this->db->get('T_ECHEM');
		 //echo  $this->db->last_query();
		return $query->result();
	}
	

	public function equip_cut($params) 
	{
		if($params['SDATE'] != "" && $params['EDATE'] != ""){
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}else{
			$this->db->where("INSERT_DATE > DATE_ADD(now(), INTERVAL -1 month)");
		}
		
		
		$this->db->select("COUNT(*) AS CUT");
		$this->db->from("T_ECHEM");

		$query = $this->db->get();
		return $query->row()->CUT;
	}

	public function equip_mean($params) 
	{
		// if($params['SDATE'] != "" && $params['EDATE'] != ""){
		// 	$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		// }
		
		$this->db->select("'전체 평균' AS TEXT, SUM(OT_OUT) / COUNT(*) AS AV_CNT");
		$this->db->from("T_ECHEM");

		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result();
	}

	// 제품 생산 내역
	public function component_search($params,$start,$limit)
	{
		
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}

		$this->db->select("*");
		$this->db->order_by('INSERT_DATE', 'DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_ECHEM");
		//echo $this->db->last_query();
		return $query->result();
	}
	public function component_search_cnt($params)
	{
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'");
		}
		$this->db->select("*");
		$this->db->order_by('INSERT_DATE', 'DESC');
		$query = $this->db->get("T_ECHEM");
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	// 원자재 신규등록
	public function component_list2($params,$start,$limit)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['INSERT_DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("*");
		$this->db->order_by('INSERT_DATE', 'DESC');
		$this->db->limit($limit,$start);
		$query = $this->db->get("T_EC_TRANS");
		 //echo $this->db->last_query();
		return $query->result();
	}
	public function component_list_cnt2($params)
	{
		if (!empty($params['END_CHK']) && $params['END_CHK'] != "") {
			$this->db->where("END_YN", $params['END_CHK']);
		}
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$this->db->where("{$params['INSERT_DATE']} BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'");
		}
		$this->db->select("*");
		$this->db->order_by('INSERT_DATE', 'DESC');
		$query = $this->db->get("T_EC_TRANS");
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	// 구매관리 - 원자재 발주등록
	public function component_head_insert2($params)
	{
		$datetime = date("Y-m-d H:i:s", time());
		$username = $this->session->userdata('user_name');

		$sql = <<<SQL
		INSERT T_EC_TRANS
			SET
			INSERT_DATE   	= '{$params['INSERT_DATE']}',
			IGGBN 	= '{$params['IGGBN']}',
			STOCK 		= '{$params['STOCK']}',
			REMARK     	= '{$params['REMARK']}',
			CUST    = '{$params['CUST']}',	
			UNIT    = '{$params['UNIT']}',	
			COL1    	= '{$params['COL1']}'
SQL;
		$this->db->query($sql);

		$sql1 = <<<SQL
		UPDATE T_STOCK
			SET			
			STOCK 		= STOCK - '{$params['STOCK']}' 
			WHERE IDX 	= '1'
SQL;
		$this->db->query($sql1);
		
		return $this->db->affected_rows();
	}

	// 구매관리 - 원자재 발주 삭제
	public function del_component2($idx,$stock)
	{
		$this->db->trans_start();
			$this->db->where("IDX", $idx);
			$this->db->delete("T_EC_TRANS");
		$this->db->trans_complete();


		/*$this->db->trans_start();
			$this->db->set("STOCK", "STOCK" + '$stock');
			$this->db->where("IDX", '1');
			$this->db->update("T_STOCK");
		$this->db->trans_complete();*/


		$data = 0;
		if ($this->db->trans_status() !== FALSE) {
			$data = 1;
		}

		return $data;
	}

	public function ajax_tapact()
	{
		$sql = <<<SQL
		select case 
			when ROUND((MA_LEVEL - 157)*0.625,0) < 0 then 0
			when ROUND((MA_LEVEL - 157)*0.625,0) between 60 and 63 then 60
			when ROUND((MA_LEVEL - 157)*0.625,0) between 64 and 67 then 63
			when ROUND((MA_LEVEL - 157)*0.625,0) between 68 and 70 then 65
			when ROUND((MA_LEVEL - 157)*0.625,0) between 71 and 74 then 65
			when ROUND((MA_LEVEL - 157)*0.625,0) > 75 then 75
		else ROUND((MA_LEVEL - 157)*0.625,0) END AS LEVEL,
			SLAVE AS TANK,MA_TEMP as TEMP,MA_PH as PH,MA_EC as CL,MA_PRESS as PRESS,INSERT_DATE
		from T_MODBUS where TANK_NO = 1 and MA_LEVEL > 0  AND INSERT_DATE = (SELECT MAX(INSERT_DATE) FROM T_MODBUS WHERE TANK_NO = 1 and MA_LEVEL > 0)
		UNION ALL
		select case 
			when (MA_LEVEL * 3) < 60 then (MA_LEVEL * 3)
			when (MA_LEVEL * 3) > 60 then 60
			when (MA_LEVEL * 3) < 0 then 0
			else (MA_LEVEL * 3) END AS LEVEL,SLAVE AS TANK,MA_TEMP as TEMP,MA_PH as PH,MA_EC as CL,MA_PRESS as PRESS,INSERT_DATE
			from T_MODBUS where TANK_NO = 2 AND MA_LEVEL > 0  AND INSERT_DATE = (SELECT MAX(INSERT_DATE) FROM T_MODBUS WHERE TANK_NO = 2 and MA_LEVEL > 0)
			UNION ALL
			select CASE 
				when MA_LEVEL < 429 Then  0
				when MA_LEVEL < 11500 Then  ROUND((MA_LEVEL - 4123)*0.001970443,0)
				when MA_LEVEL < 25630 Then  ROUND((MA_LEVEL + 2835)*0.001970443,0)
				when MA_LEVEL > 25630 Then  65
				else 0004662005	end  AS LEVEL,SLAVE AS TANK,MA_TEMP as TEMP,MA_PH as PH,MA_EC as CL,MA_PRESS as PRESS,INSERT_DATE
				from T_MODBUS where TANK_NO = 3 AND INSERT_DATE = (SELECT MAX(INSERT_DATE) FROM T_MODBUS WHERE TANK_NO = 3 )
			UNION ALL
			select CASE 
				WHEN MA_LEVEL > 300 then 90
				when MA_LEVEL < 300 then ROUND((MA_LEVEL-100)*0.454545455 ,0)
				when MA_LEVEL < 100 then 0
				when MA_LEVEL = 0 then 5
				else 0 end  AS LEVEL,SLAVE AS TANK,MA_TEMP as TEMP,MA_PH as PH,MA_EC as CL,MA_PRESS as PRESS,INSERT_DATE
				from T_MODBUS where TANK_NO = 4 AND INSERT_DATE = (SELECT MAX(INSERT_DATE) FROM T_MODBUS WHERE TANK_NO = 4)
			UNION ALL
			select ROUND((MA_LEVEL-277)*0.004975,0) AS LEVEL,SLAVE AS TANK,MA_TEMP as TEMP,MA_PH as PH,MA_EC as CL,MA_PRESS as PRESS,INSERT_DATE
			from T_MODBUS where TANK_NO = 5   AND INSERT_DATE = (SELECT MAX(INSERT_DATE) FROM T_MODBUS WHERE TANK_NO = 5)

SQL;
		$query = $this->db->query($sql);
		 //echo $this->db->last_query();
		return $query->result();
	}	

}
