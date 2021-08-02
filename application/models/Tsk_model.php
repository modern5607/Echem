<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tsk_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();

		// $this->load->model(array(''));
	}


	//납기별 작업지시등록 리스트
	public function dateo_list($param,$start=0,$limit=20)
	{
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("WRK.PORRQDA BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("WRK.POR_NO",$param['PORNO']);
		}
		if (!empty($param['WORKER']) && $param['WORKER'] != "") {
			$this->db->group_start()
				->where('PROC_MAN',$param['WORKER'])
				->or_where('ASSE_MAN',$param['WORKER'])
				->or_where('WELD_MAN',$param['WORKER'])
				->or_where('MRO_MAN',$param['WORKER'])
			->group_end();
		}
		
		$this->db->join("T_ACT AS ACT","(WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)","LEFT");
		$this->db->where("WRK.END_YN != 'Y'");
		$this->db->where("ACT.PROC_GBN < 400");
		$this->db->order_by("WRK.PORRQDA DESC, WRK.POR_NO, WRK.POR_SEQ");

		$this->db->limit($limit,$start);
		$res = $this->db->get("T_WRK AS WRK");
		// echo $this->db->last_query();
		return $res->result();
	}
	public function dateo_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");

		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("WRK.PORRQDA BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("WRK.POR_NO",$param['PORNO']);
		}
		if (!empty($param['WORKER']) && $param['WORKER'] != "") {
			$this->db->group_start()
				->where('PROC_MAN',$param['WORKER'])
				->or_where('ASSE_MAN',$param['WORKER'])
				->or_where('WELD_MAN',$param['WORKER'])
				->or_where('MRO_MAN',$param['WORKER'])
			->group_end();
		}
		$this->db->join("T_ACT AS ACT","(WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)","LEFT");
		$this->db->where("WRK.END_YN != 'Y'");
		$this->db->where("ACT.PROC_GBN < 400");

		$res = $this->db->get("T_WRK AS WRK");
		return $res->row()->CUT;
	}
	//POR별 작업지시등록 리스트
	public function poro_list($param,$start=0,$limit=20)
	{
		$where = " AND END_YN != 'Y' AND WRK.POR_NO = POR_NO";
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
			$this->db->where("PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("PORRQDA BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("POR_NO",$param['PORNO']);
			$where .= " AND POR_NO like '%{$param['PORNO']}%'";
		}

		$this->db->select("
			WRK.POR_NO, SUM(WRK.PO_QTY) as PO_QTY, SUM(WRK.WEIGHT * WRK.PO_QTY) as WEIGHT, WRK.PORRQDA, 
			PROC_PLN, ASSE_PLN, WELD_PLN, MRO_PLN, PROC_ACT, ASSE_ACT, WELD_ACT, MRO_ACT,
			(SELECT COUNT(*) FROM T_WRK WHERE PROC_YN='N' {$where}) AS PROC_YN,
			(SELECT COUNT(*) FROM T_WRK WHERE ASSE_YN='N' {$where}) AS ASSE_YN,
			(SELECT COUNT(*) FROM T_WRK WHERE WELD_YN='N' {$where}) AS WELD_YN,
			(SELECT COUNT(*) FROM T_WRK WHERE MRO_YN='N' {$where}) AS MRO_YN ");
		$this->db->where("END_YN != 'Y'");
		$this->db->where("ACT.PROC_GBN < 400");
		$this->db->join("T_ACT AS ACT","(WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)","LEFT");
		$this->db->group_by("POR_NO");
		$this->db->order_by("PORRQDA DESC, POR_NO");

		$this->db->limit($limit,$start);
		$res = $this->db->get("T_WRK AS WRK");
		// ECHO $this->db->last_query();
		return $res->result();
	}
	public function poro_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$where .= " AND POR_NO LIKE '%{$param['PORNO']}%'";
		}

		$sql = <<<SQL
		SELECT 
			ifnull(COUNT(*), 0) as cnt 
		FROM( 
			SELECT WRK.POR_NO
			FROM `T_WRK` AS WRK
			left join T_ACT AS ACT ON (WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)
			WHERE WRK.END_YN != 'Y' AND ACT.PROC_GBN < 400 {$where}
			GROUP BY `POR_NO`
		) AS AA
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->cnt;
	}
	
	//지시서 
	public function order_list($params)
	{
		$sql=<<<SQL
		SELECT * FROM
		(
			SELECT A.POR_NO,A.POR_SEQ, A.WEIGHT,A.PO_QTY,A.MCCSDESC,B.INRQDA, "절단" AS GB,A.WEIGHT WEIGHT2,A.PROC_YN END
			FROM T_WRK A LEFT JOIN T_ACT B ON A.POR_SEQ = B.POR_SEQ AND A.POR_NO = B.POR_NO 
			WHERE PROC_PLN = '{$params['DATE']}' AND PROC_MAN = '{$params['WORKER']}'
			UNION
			SELECT A.POR_NO,A.POR_SEQ, A.WEIGHT,A.PO_QTY,A.MCCSDESC,B.INRQDA, "취부" AS GB,A.WEIGHT WEIGHT2,A.PROC_YN END
			FROM T_WRK A LEFT JOIN T_ACT B ON A.POR_SEQ = B.POR_SEQ AND A.POR_NO = B.POR_NO 
			WHERE ASSE_PLN = '{$params['DATE']}' AND ASSE_MAN = '{$params['WORKER']}'
			UNION
			SELECT A.POR_NO,A.POR_SEQ, A.WEIGHT,A.PO_QTY,A.MCCSDESC,B.INRQDA, "용접" AS GB,A.WEIGHT WEIGHT2,A.PROC_YN END
			FROM T_WRK A LEFT JOIN T_ACT B ON A.POR_SEQ = B.POR_SEQ AND A.POR_NO = B.POR_NO 
			WHERE WELD_PLN = '{$params['DATE']}' AND WELD_MAN = '{$params['WORKER']}'
			UNION
			SELECT A.POR_NO,A.POR_SEQ, A.WEIGHT,A.PO_QTY,A.MCCSDESC,B.INRQDA, "사상" AS GB,A.WEIGHT WEIGHT2,A.PROC_YN END
			FROM T_WRK A LEFT JOIN T_ACT B ON A.POR_SEQ = B.POR_SEQ AND A.POR_NO = B.POR_NO 
			WHERE MRO_PLN = '{$params['DATE']}' AND MRO_MAN = '{$params['WORKER']}'
		) AS A
		ORDER BY POR_NO
SQL;

		$query = $this->db->query($sql);
		// echo nl2br($this->db->last_query());
		
		return $query->result();
	}

	public function order_member_list($params)
	{
		$sql=<<<SQL
			SELECT PROC_MAN AS MAN FROM T_WRK WHERE PROC_PLN = '{$params['DATE']}' GROUP BY PROC_MAN
			UNION
			SELECT ASSE_MAN FROM T_WRK WHERE ASSE_PLN = '{$params['DATE']}' GROUP BY ASSE_MAN
			UNION
			SELECT WELD_MAN FROM T_WRK WHERE WELD_PLN = '{$params['DATE']}' GROUP BY WELD_MAN
			UNION
			SELECT MRO_MAN FROM T_WRK WHERE MRO_PLN = '{$params['DATE']}' GROUP BY MRO_MAN
SQL;

		$query = $this->db->query($sql);
		// echo var_dump($query->result());
		return $query->result();
	}

	// 작업관리 작업지시 폼
	public function dateo_form($param)
	{
		$this->db->select("POR_NO, POR_SEQ, MCCSDESC, {$param['GJ']}_PLN, PO_QTY, WEIGHT");
		$this->db->where("POR_NO",$param['PORNO']);
		$this->db->where("POR_SEQ",$param['SEQ']);
		
		$res = $this->db->get("T_WRK");
		return $res->result();
	}
	// 작업관리 작업지시 폼
	public function poro_form($param)
	{
		$this->db->select("POR_NO, {$param['GJ']}_PLN, SUM(PO_QTY) as PO_QTY, SUM(WEIGHT*PO_QTY) as WEIGHT");
		$this->db->where("POR_NO",$param['PORNO']);
		
		$res = $this->db->get("T_WRK");
		return $res->result();
	}
	// 작업지시용 멤버 리스트 (작업자에게 내려진 지시 총 수량, 총 중량 출력)
	public function member_list($param)
	{
		$where ='';
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND POR_NO != '{$param['PORNO']}'";
		}

		$sql=<<<SQL
		SELECT
			NAME,
			(SELECT SUM(PO_QTY) FROM T_WRK WHERE PROC_MAN=MEM.NAME AND PROC_YN != "Y" {$where}) AS PROCQ,
			(SELECT SUM(PO_QTY*WEIGHT) FROM T_WRK WHERE PROC_MAN=MEM.NAME AND PROC_YN != "Y" {$where}) AS PROCW,
			(SELECT SUM(PO_QTY) FROM T_WRK WHERE ASSE_MAN=MEM.NAME AND ASSE_YN != "Y" {$where}) AS ASSEQ,
			(SELECT SUM(PO_QTY*WEIGHT) FROM T_WRK WHERE ASSE_MAN=MEM.NAME AND ASSE_YN != "Y" {$where}) AS ASSEW,
			(SELECT SUM(PO_QTY) FROM T_WRK WHERE WELD_MAN=MEM.NAME AND WELD_YN != "Y" {$where}) AS WELDQ,
			(SELECT SUM(PO_QTY*WEIGHT) FROM T_WRK WHERE WELD_MAN=MEM.NAME AND WELD_YN != "Y" {$where}) AS WELDW,
			(SELECT SUM(PO_QTY) FROM T_WRK WHERE MRO_MAN=MEM.NAME AND MRO_YN != "Y" {$where}) AS MROQ,
			(SELECT SUM(PO_QTY*WEIGHT) FROM T_WRK WHERE MRO_MAN=MEM.NAME AND MRO_YN != "Y" {$where}) AS MROW
		FROM
			T_MEMBER AS MEM
		WHERE
			STATE = 'Y'
SQL;
	$query = $this->db->query($sql);
	// echo $this->db->last_query();
	return $query->result();
	}
	// 작업지시 업데이트
	public function order_up($param)
	{
		if($param['SEQ'] != "por_up"){
			$this->db->where('POR_SEQ', $param['SEQ']);
		}
		
		$this->db->set($param['GJ']."_MAN", $param['NAME']);
		$this->db->set($param['GJ']."_PLN", $param['DATE']);
		$this->db->where('POR_NO', $param['PORNO']);
		$this->db->where($param['GJ']."_YN", "N");
		$data = $this->db->update('T_WRK');

		return $data;
	}



	public function workr_list($param,$start=0,$limit=20)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND WRK.PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND WRK.POR_NO LIKE '%{$param['PORNO']}%'";
		}
		if (!empty($param['GJ']) && $param['GJ'] != "") {
			if(!empty($param['WORKER']) && $param['WORKER'] != ""){
				$where .= " AND {$param['GJ']}_MAN = '{$param['WORKER']}'";
			}
			$where .= " AND {$param['GJ']}_YN != 'Y'";
		}
		if (!empty($param['WORKER']) && $param['WORKER'] != "" && empty($param['GJ'])) {
			$where .= " AND ((PROC_MAN = '{$param['WORKER']}' AND PROC_YN !='Y') 
			OR (ASSE_MAN = '{$param['WORKER']}' AND ASSE_YN !='Y') 
			OR (WELD_MAN = '{$param['WORKER']}' AND WELD_YN !='Y') 
			OR (MRO_MAN = '{$param['WORKER']}' AND MRO_YN !='Y'))";
		}
		
		$sql = <<<SQL
		SELECT AA.* FROM (
				SELECT WRK.POR_NO, WRK.POR_SEQ, WRK.MCCSDESC, WRK.PO_QTY, WRK.WEIGHT, PROC_MAN, ASSE_MAN, WELD_MAN, MRO_MAN, PROC_YN, ASSE_YN, WELD_YN, MRO_YN, WRK.PORRQDA
				FROM T_WRK as WRK
				LEFT JOIN T_ACT AS ACT ON (WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)
				WHERE WRK.END_YN != 'Y'
				AND ACT.PROC_GBN < 400
				{$where}
				ORDER BY PORRQDA DESC, POR_NO, POR_SEQ
				limit {$start},{$limit}
			) as AA 
		UNION ALL
		SELECT '합계','','', SUM(WRK.PO_QTY) as QTY, SUM((WRK.WEIGHT * WRK.PO_QTY)) AS WEIGHT,'','','','','','','','',''
		FROM T_WRK as WRK
		LEFT JOIN T_ACT AS ACT ON (WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)
		WHERE WRK.END_YN != 'Y'
		AND ACT.PROC_GBN < 400
		{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function workr_cut($param)
	{
		$where = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND WRK.PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND WRK.POR_NO LIKE '%{$param['PORNO']}%'";
		}
		if (!empty($param['GJ']) && $param['GJ'] != "") {
			if(!empty($param['WORKER']) && $param['WORKER'] != ""){
				$where .= " AND {$param['GJ']}_MAN = '{$param['WORKER']}'";
			}
			$where .= " AND {$param['GJ']}_YN != 'Y'";
		}
		if (!empty($param['WORKER']) && $param['WORKER'] != "" && empty($param['GJ'])) {
			$where .= " AND ((PROC_MAN = '{$param['WORKER']}' AND PROC_YN !='Y') 
			OR (ASSE_MAN = '{$param['WORKER']}' AND ASSE_YN !='Y') 
			OR (WELD_MAN = '{$param['WORKER']}' AND WELD_YN !='Y') 
			OR (MRO_MAN = '{$param['WORKER']}' AND MRO_YN !='Y'))";
		}

		$sql = <<<SQL
		SELECT count(*) as cnt 
		FROM T_WRK as WRK
		LEFT JOIN T_ACT AS ACT ON (WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)
		WHERE WRK.END_YN != 'Y'   
		AND ACT.PROC_GBN < 400
		{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->cnt;
	}
	public function workr_up($param)
	{
		foreach ($param['PORNO'] as $k => $porno) {
			if($param['GJ'][$k] == "MRO"){
				// 여기
				$this->db->set("PROC_GBN", "340");
				$this->db->where('POR_NO', $porno);
				$this->db->where('POR_SEQ', $param['SEQ'][$k]);
				$this->db->where("PROC_GBN < 340");
				$data = $this->db->update('T_ACT');

				$this->db->set("END_YN", "Y");
			}
			$this->db->set($param['GJ'][$k]."_ACT", $param['DATE']);
			$this->db->set($param['GJ'][$k]."_YN", "Y");
			$this->db->where('POR_NO', $porno);
			$this->db->where('POR_SEQ', $param['SEQ'][$k]);
			$data = $this->db->update('T_WRK');
			// echo $this->db->last_query();
		}
		return $data;
	}
	public function porr_list($param,$start=0,$limit=20)
	{
		$where = '';
		$order = 'PORRQDA DESC, POR_NO';
		$group = ' , PROC_YN, ASSE_YN, WELD_YN, MRO_YN';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND WRK.PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND WRK.POR_NO LIKE '%{$param['PORNO']}%'";
		}
		if (!empty($param['GJ']) && $param['GJ'] != "") {
			$where .= " AND {$param['GJ']}_YN != 'Y'";
			$order = "FIELD(PROC_YN, 'Y', 'N'), PORRQDA DESC, POR_NO";
		}
		if ($param['GJ'] == "WELD") {
			$group = " , ASSE_YN, WELD_YN, MRO_YN";
			$order = "FIELD(ASSE_YN, 'Y', 'N'), PORRQDA DESC, POR_NO";
		}
		if ($param['GJ'] == "MRO") {
			$group = " , WELD_YN, MRO_YN";
			$order = "FIELD(WELD_YN, 'Y', 'N'), PORRQDA DESC, POR_NO";
		}

		
		$sql = <<<SQL
		SELECT AA.* FROM (
				SELECT WRK.POR_NO, sum(WRK.PO_QTY) as PO_QTY, sum(WRK.WEIGHT * WRK.PO_QTY) as WEIGHT, count(WRK.POR_NO) AS CNT, PROC_YN, ASSE_YN, WELD_YN, MRO_YN, WRK.PORRQDA
				FROM T_WRK AS WRK
				LEFT JOIN T_ACT AS ACT ON (WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)
				WHERE WRK.END_YN != 'Y'   
				AND ACT.PROC_GBN < 400 
					{$where}
				GROUP BY POR_NO {$group}
				ORDER BY {$order}
				limit {$start},{$limit}
			) as AA 
		UNION ALL
		SELECT '합계', SUM(WRK.PO_QTY) as PO_QTY, SUM((WRK.WEIGHT * WRK.PO_QTY)) AS WEIGHT,'','','','','',''
		FROM T_WRK AS WRK
		WHERE END_YN != 'Y' 
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function porr_cut($param)
	{
		$where = '';
		$group = ' , PROC_YN, ASSE_YN, WELD_YN, MRO_YN';

		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$where .= " AND WRK.PORRQDA BETWEEN '{$param['DATE']}' AND '{$param['DATE']}'";
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$where .= " AND WRK.POR_NO LIKE '%{$param['PORNO']}%'";
		}

		$sql = <<<SQL
		SELECT 
			ifnull(COUNT(*), 0) as cnt 
		FROM( 
			SELECT WRK.POR_NO
			FROM T_WRK AS WRK
				LEFT JOIN T_ACT AS ACT ON (WRK.POR_NO = ACT.POR_NO AND WRK.POR_SEQ = ACT.POR_SEQ)
				WHERE WRK.END_YN != 'Y'   
				AND ACT.PROC_GBN < 400  {$where}
			GROUP BY `POR_NO` {$group}
		) AS AA
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->cnt;
	}
	public function porr_up($param)
	{
		foreach ($param['PORNO'] as $k => $porno) {
			if($param['GJ'][$k] == "MRO"){
				// 여기
				$this->db->set("PROC_GBN", "340");
				$this->db->where('POR_NO', $porno);
				$this->db->where("PROC_GBN < 340");
				$data = $this->db->update('T_ACT');

				$this->db->set("END_YN", "Y");
			}
			$this->db->set($param['GJ'][$k]."_ACT", $param['DATE']);
			$this->db->set($param['GJ'][$k]."_YN", "Y");
			$this->db->where('POR_NO', $porno);
			$this->db->where($param['GJ'][$k]."_YN", 'N');
			$data = $this->db->update('T_WRK');
			// echo $this->db->last_query();
		}
		return $data;
	}



	//검사신청등록 리스트
	public function head_test_list($param)
	{
		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$this->db->where("ACT.PORRQDA BETWEEN '{$param['DATE']} 00:00:00' AND '{$param['DATE']} 23:59:59'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("ACT.POR_NO",$param['PORNO']);
		}
		
		$this->db->select("ACT.*");
		$this->db->from("T_ACT AS ACT");
		$this->db->join("T_WRKAFT AS AFT","(ACT.POR_NO = AFT.POR_NO AND ACT.POR_SEQ = AFT.POR_SEQ)","LEFT");
		$this->db->where("AFT.POR_NO IS NULL");
		$this->db->where("ACT.PROC_GBN = 340");
		$this->db->order_by("PORRQDA DESC, POR_NO, POR_SEQ");

		$res = $this->db->get();
		return $res->result();
	}
	public function head_test_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");

		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$this->db->where("ACT.PORRQDA BETWEEN '{$param['DATE']} 00:00:00' AND '{$param['DATE']} 23:59:59'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("ACT.POR_NO",$param['PORNO']);
		}
		$this->db->from("T_ACT AS ACT");
		$this->db->join("T_WRKAFT AS AFT","(ACT.POR_NO = AFT.POR_NO AND ACT.POR_SEQ = AFT.POR_SEQ)","LEFT");
		$this->db->where("AFT.POR_NO IS NULL");
		$this->db->where("ACT.PROC_GBN = 340");

		$res = $this->db->get();
		// echo $this->db->last_query();
		return $res->row()->CUT;
	}
	public function detail_test_list($param)
	{
		$this->db->where("FB_YN",'N');
		$this->db->where("END_YN","N");
		$this->db->order_by("CHKDATE DESC, POR_NO, POR_SEQ");
		$res = $this->db->get("T_WRKAFT");
		// echo $this->db->last_query();
		return $res->result();
	}
	public function detail_test_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		$this->db->where("FB_YN",'N');
		$this->db->where("END_YN","N");
		$res = $this->db->get("T_WRKAFT");
		return $res->row()->CUT;
	}
	public function test_ins($param)
	{
		foreach ($param['PORNO'] as $k => $porno) {
			$this->db->where('POR_NO', $porno);
			$this->db->where('POR_SEQ', $param['SEQ'][$k]);
			$que = $this->db->get("T_ACT");
			$act = $que->row();
			
			$this->db->set('POR_NO', $porno);
			$this->db->set('POR_SEQ', $param['SEQ'][$k]);	
			$this->db->set('PO_QTY', $act->PO_QTY);
			$this->db->set('WEIGHT', $act->WEIGHT);
			$this->db->set('MCCSDESC', $act->MCCSDESC);		//품명
			$this->db->set('PORRQDA', $act->PORRQDA);		//mp날자
			if($act->AVCODE != ""){
				$this->db->set('AFT_QTY', $act->PO_QTY);		//후처리 수량
				$this->db->set('AFT_WEIGHT', $act->WEIGHT);		//후처리 중량
				$this->db->set('AFT_DATE', $act->POSTRQ);		//후처리 날자
				$this->db->set('AFT_DEPT', $act->AVCODE);		//후처리 업체
			}
			if($act->NEW_MPDA != ""){
				$this->db->set('TR_QTY', $act->PO_QTY);			//배송 수량
				$this->db->set('TR_WEIGHT', $act->WEIGHT);		//배송 중량
				$this->db->set('TR_GB', $act->TRND_GBN);		//배송 구분
				$this->db->set('TR_DATE', $act->NEW_MPDA);		//배송 날자
			}
			$this->db->set('FB_YN', "N");
			$this->db->set('PK_YN', "N");
			$this->db->set('AFT_YN', "N");
			$this->db->set('TR_YN', "N");
			$this->db->set('END_YN', "N");
			$this->db->set('CHKDATE', $param['DATE']);
			$data = $this->db->insert('T_WRKAFT');
		}
		return $data;
	}
	public function test_del($param)
	{
		foreach ($param['PORNO'] as $k => $porno) {
			$this->db->where('POR_NO', $porno);
			$this->db->where('POR_SEQ', $param['SEQ'][$k]);
			$data = $this->db->delete('T_WRKAFT');
		}
		return $data;
	}



	//검사등록 리스트
	public function check_list($param,$start=0,$limit=20)
	{
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("CHKDATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("POR_NO",$param['PORNO']);
		}
		

		$this->db->where("(PK_YN = 'N' OR FB_YN = 'N')");
		$this->db->order_by("CHKDATE, POR_NO, POR_SEQ");

		$this->db->limit($limit,$start);
		$res = $this->db->get("T_WRKAFT");
		return $res->result();
	}
	public function check_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("CHKDATE BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if ((!empty($param['PORNO']) && $param['PORNO'] != "")) {
			$this->db->like("POR_NO",$param['PORNO']);
		}
		
		// $this->db->where("END_YN != 'Y'");

		$res = $this->db->get("T_WRKAFT");
		return $res->row()->CUT;
	}
	public function check_up($param)
	{
		// 여기
		if($param['YN'] == "FB_YN"){
			$this->db->set("PROC_GBN", "400");
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$this->db->where("PROC_GBN < 400");
			$this->db->update('T_ACT');
		}
		if($param['YN'] == "PK_YN"){
			$this->db->set("PROC_GBN", "500");
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$this->db->where("PROC_GBN < 500");
			$this->db->update('T_ACT');
		}

		$this->db->set( $param['YN'], date("Y-m-d"));
		$this->db->where('POR_NO', $param['PORNO']);
		$this->db->where("POR_SEQ",$param['SEQ']);
		$data = $this->db->update('T_WRKAFT');
// echo $this->db->last_query();
		return $data;
	}


	//후처리(도장,도금)의뢰 리스트
	public function after_list($param,$start=0,$limit=20)
	{
		$where = '';
		$where2 = '';
		$where3 = '';
		$select = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND WRK.POR_NO = '{$param['PORNO']}'";
		}
		if ((!empty($param['SEQ']) && $param['SEQ'] != "")) {
			$where .= " AND WRK.POR_SEQ = '{$param['SEQ']}'";
		}
		if ((!empty($param['AYN']) && $param['AYN'] == "CHK")){
			$where2 .= " AND AFT_YN = 'X'";
			$where3 .= " AND TRS.END_YN = 'N'";
		}
		if ((!empty($param['AYN']) && $param['AYN'] == "N")){
			$where2 .= " AND AFT_YN = 'N'";
			$where3 .= " AND TRS.END_YN = 'X'";
		}
		if ((!empty($param['AYN']) && $param['AYN'] == "Y")){
			$where2 .= " AND AFT_YN = 'Y'";
			$where3 .= " AND TRS.END_YN = 'Y'";
		}
		if ($param['HEAD'] == 'YES' || $param['IDX'] == 'AF'){
			$select .= "SELECT AA.* 
						FROM (
							SELECT 
								'AF' AS IDX, 
								POR_NO, POR_SEQ, 
								PO_QTY, WEIGHT, 
								MCCSDESC, PORRQDA, 
								AFT_QTY AS TRS_QTY, 
								(AFT_QTY * WEIGHT) as TRS_WEIGHT, 
								'' AS TRS_DATE, 
								'' AS TRS_REMARK, 
								AFT_YN AS END_YN,
								'' AS ENDDATE
							FROM T_WRKAFT as WRK
							WHERE (PO_QTY > IFNULL(AFT_QTY,0)) {$where} {$where2}
						) as AA";
			if (!empty($param['HEAD'])){
				$select .= " UNION ALL";
			}
		}
		if ($param['IDX'] != 'AF'){
			if ($param['IDX'] != '' ){
				$where .= " AND IDX = '{$param['IDX']}'";
			}
			$select .= " SELECT TRS.IDX, TRS.POR_NO, TRS.POR_SEQ, WRK.PO_QTY, WRK.WEIGHT, WRK.MCCSDESC, WRK.PORRQDA, TRS_QTY, TRS_WEIGHT, TRS_DATE, TRS_REMARK, TRS.END_YN, ENDDATE
			FROM T_WRKTRANS AS TRS
			JOIN T_WRK AS WRK ON (WRK.POR_NO = TRS.POR_NO AND WRK.POR_SEQ = TRS.POR_SEQ)
			WHERE GJ_GB='AFT' {$where} {$where3}";
		}
		
		$sql = <<<SQL
		{$select}
		ORDER BY PORRQDA DESC, POR_NO, POR_SEQ
		limit {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function after_cut($param)
	{
		$where = '';
		$where2 = '';
		$where3 = '';
		$select = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND WRK.POR_NO = '{$param['PORNO']}'";
		}
		if ((!empty($param['SEQ']) && $param['SEQ'] != "")) {
			$where .= " AND WRK.POR_SEQ = '{$param['SEQ']}'";
		}
		if ((!empty($param['AYN']) && $param['AYN'] == "CHK")){
			$where2 .= " AND AFT_YN = 'X'";
			$where3 .= " AND TRS.END_YN = 'N'";
		}
		if ((!empty($param['AYN']) && $param['AYN'] == "N")){
			$where2 .= " AND AFT_YN = 'N'";
			$where3 .= " AND TRS.END_YN = 'X'";
		}
		if ((!empty($param['AYN']) && $param['AYN'] == "Y")){
			$where2 .= " AND AFT_YN = 'Y'";
			$where3 .= " AND TRS.END_YN = 'Y'";
		}
		if ($param['HEAD'] == 'YES' || $param['IDX'] == 'AF'){
			$select .= "SELECT AA.* 
						FROM (
							SELECT 
								COUNT(*) AS C
							FROM T_WRKAFT as WRK
							WHERE (PO_QTY > IFNULL(AFT_QTY,0)) {$where} {$where2}
						) as AA";
			if (!empty($param['HEAD'])){
				$select .= " UNION ALL";
			}
		}
		if ($param['IDX'] != 'AF'){
			if ($param['IDX'] != '' ){
				$where .= " AND IDX = '{$param['IDX']}'";
			}
			$select .= " SELECT COUNT(*) AS C
			FROM T_WRKTRANS AS TRS
			WHERE GJ_GB='AFT' {$where} {$where3}";
		}
		
		$sql = <<<SQL
		select SUM(C) as CUT from( {$select} )B
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->CUT;
	}
	public function after_up($param)
	{
		if ($param['IDX'] == "AF"){
			// $this->db->set("PROC_GBN", "600");
			// $this->db->where('POR_NO', $param['PORNO']);
			// $this->db->where("POR_SEQ",$param['SEQ']);
			// $this->db->where("PROC_GBN < 600");
			// $data = $this->db->update('T_ACT');

			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$query = $this->db->get('T_WRKAFT');
			$chk = $query->row()->AFT_QTY + $param['QTY'];

			$this->db->set("AFT_QTY", $chk);
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$data = $this->db->update('T_WRKAFT');

			$this->db->set('POR_NO', $param['PORNO']);
			$this->db->set("POR_SEQ",$param['SEQ']);
			$this->db->set("GJ_GB","AFT");
			$this->db->set("TRS_QTY", $param['QTY']);
			$this->db->set("TRS_WEIGHT", $param['WEIGHT']);
			$this->db->set("TRS_DATE", $param['DATE']);
			$this->db->set("TRS_REMARK", $param['DEPT']);
			$this->db->set("END_YN", $param['YN']);
			$data = $this->db->insert('T_WRKTRANS');
		}else{
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$query = $this->db->get('T_WRKAFT');
			$chk = $query->row();

			if($chk->AFT_END_QTY + $param['QTY'] >= $chk->PO_QTY){
				$this->db->set("AFT_YN", "Y");
			}
			$this->db->set("AFT_END_QTY", $chk->AFT_END_QTY + $param['QTY']);
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$data = $this->db->update('T_WRKAFT');

			$this->db->set("ENDDATE", $param['ENDDATE']);
			$this->db->set("END_YN", $param['YN']);
			$this->db->where('IDX', $param['IDX']);
			$data = $this->db->update('T_WRKTRANS');
		}
		// echo $this->db->last_query();
		return $data;
	}



	
	//배송 리스트
	public function ship_list($param,$start=0,$limit=20)
	{
		$where = '';
		$where2 = '';
		$where3 = '';
		$select = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND WRK.POR_NO = '{$param['PORNO']}'";
		}
		if ((!empty($param['SEQ']) && $param['SEQ'] != "")) {
			$where .= " AND WRK.POR_SEQ = '{$param['SEQ']}'";
		}
		if ((!empty($param['SYN']) && $param['SYN'] == "CHK")){
			$where2 .= " AND TR_YN = 'X'";
			$where3 .= " AND TRS.END_YN = 'N'";
		}
		if ((!empty($param['SYN']) && $param['SYN'] == "N")){
			$where2 .= " AND TR_YN = 'N'";
			$where3 .= " AND TRS.END_YN = 'X'";
		}
		if ((!empty($param['SYN']) && $param['SYN'] == "Y")){
			$where2 .= " AND TR_YN = 'Y'";
			$where3 .= " AND TRS.END_YN = 'Y'";
		}
		if ($param['HEAD'] == 'YES' || $param['IDX'] == 'AF'){
			$select .= "SELECT AA.* 
						FROM (
							SELECT 
								'AF' AS IDX, 
								POR_NO, POR_SEQ, 
								PO_QTY, WEIGHT, 
								MCCSDESC, PORRQDA, 
								TR_QTY AS TRS_QTY, 
								(TR_QTY * WEIGHT) as TRS_WEIGHT, 
								'' AS TRS_DATE, 
								'' AS TRS_REMARK, 
								TR_YN AS END_YN,
								'' AS ENDDATE
							FROM T_WRKAFT as WRK
							WHERE (PO_QTY > IFNULL(TR_QTY,0)) {$where} {$where2}
						) as AA";
			if (!empty($param['HEAD'])){
				$select .= " UNION ALL";
			}
		}
		if ($param['IDX'] != 'AF'){
			if ($param['IDX'] != '' ){
				$where .= " AND IDX = '{$param['IDX']}'";
			}
			$select .= " SELECT TRS.IDX, TRS.POR_NO, TRS.POR_SEQ, WRK.PO_QTY, WRK.WEIGHT, WRK.MCCSDESC, WRK.PORRQDA, TRS_QTY, TRS_WEIGHT, TRS_DATE, TRS_REMARK, TRS.END_YN,ENDDATE
			FROM T_WRKTRANS AS TRS
			JOIN T_WRK AS WRK ON (WRK.POR_NO = TRS.POR_NO AND WRK.POR_SEQ = TRS.POR_SEQ)
			WHERE GJ_GB='TR' {$where} {$where3}";
		}
		
		$sql = <<<SQL
		{$select}
		ORDER BY PORRQDA DESC, POR_NO, POR_SEQ
		limit {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	public function ship_cut($param)
	{
		$where = '';
		$where2 = '';
		$where3 = '';
		$select = '';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$where .= " AND POR_NO = '{$param['PORNO']}'";
		}
		if ((!empty($param['SEQ']) && $param['SEQ'] != "")) {
			$where .= " AND POR_SEQ = '{$param['SEQ']}'";
		}
		if ((!empty($param['SYN']) && $param['SYN'] == "CHK")){
			$where2 .= " AND TR_YN = 'X'";
			$where3 .= " AND END_YN = 'N'";
		}
		if ((!empty($param['SYN']) && $param['SYN'] == "N")){
			$where2 .= " AND TR_YN = 'N'";
			$where3 .= " AND END_YN = 'X'";
		}
		if ((!empty($param['SYN']) && $param['SYN'] == "Y")){
			$where2 .= " AND TR_YN = 'Y'";
			$where3 .= " AND END_YN = 'Y'";
		}
		if ($param['HEAD'] == 'YES' || $param['IDX'] == 'AF'){
			$select .= "SELECT AA.* 
						FROM (
							SELECT 
								COUNT(*) AS C
							FROM T_WRKAFT as WRK
							WHERE (PO_QTY > IFNULL(TR_QTY,0)) {$where} {$where2}
						) as AA";
			if (!empty($param['HEAD'])){
				$select .= " UNION ALL";
			}
		}
		if ($param['IDX'] != 'AF'){
			if ($param['IDX'] != '' ){
				$where .= " AND IDX = '{$param['IDX']}'";
			}
			$select .= " SELECT COUNT(*) AS C
			FROM T_WRKTRANS AS TRS
			WHERE GJ_GB='TR' {$where} {$where3}";
		}
		
		$sql = <<<SQL
		select SUM(C) as CUT from( {$select} )B
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->CUT;
	}
	public function ship_up($param)
	{
		if ($param['IDX'] == "AF"){
			// if($param['YN'] != "Y"){
			// 	$this->db->set("PROC_GBN", "700");
			// }else{
			// 	$this->db->set("PROC_GBN", "800");
			// }
			// $this->db->where('POR_NO', $param['PORNO']);
			// $this->db->where("POR_SEQ",$param['SEQ']);
			// $data = $this->db->update('T_ACT');

			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$query = $this->db->get('T_WRKAFT');
			$chk = $query->row()->TR_QTY + $param['QTY'];

			$this->db->set("TR_QTY", $chk);
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$data = $this->db->update('T_WRKAFT');

			$this->db->set('POR_NO', $param['PORNO']);
			$this->db->set("POR_SEQ",$param['SEQ']);
			$this->db->set("GJ_GB","TR");
			$this->db->set("TRS_QTY", $param['QTY']);
			$this->db->set("TRS_WEIGHT", $param['WEIGHT']);
			$this->db->set("TRS_DATE", $param['DATE']);
			$this->db->set("TRS_REMARK", $param['TR']);
			$this->db->set("END_YN", $param['YN']);
			$data = $this->db->insert('T_WRKTRANS');
		}else{
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$query = $this->db->get('T_WRKAFT');
			$chk = $query->row();

			if($chk->TR_END_QTY + $param['QTY'] >= $chk->PO_QTY){
				$this->db->set("TR_YN", "Y");
			}
			$this->db->set("TR_END_QTY", $chk->TR_END_QTY + $param['QTY']);
			$this->db->where('POR_NO', $param['PORNO']);
			$this->db->where("POR_SEQ",$param['SEQ']);
			$data = $this->db->update('T_WRKAFT');

			$this->db->set("ENDDATE",  $param['ENDDATE']);
			$this->db->set("END_YN", $param['YN']);
			$this->db->where('IDX', $param['IDX']);
			$data = $this->db->update('T_WRKTRANS');
		}
		// echo $this->last->query();
		return $data;
	}



	//월간생산실적 리스트
	public function head_result_list($param)
	{
		$select = "IFNULL(COUNT(PO_QTY),0) AS COUNT, IFNULL(SUM(PO_QTY),0) AS C_QTY, IFNULL(SUM(WEIGHT),0) AS C_WEIGHT";
		$where = ' AND PROC_GBN="800"';
		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['DATE']}-01' AND '{$param['DATE']}-31'";
		}

		
		$sql = <<<SQL
			  SELECT {$select}, "선체" AS GBN FROM T_ACT WHERE DESC_GBN="선체" {$where}
		UNION SELECT {$select}, "선장" AS GBN FROM T_ACT WHERE DESC_GBN="선장" {$where}
		UNION SELECT {$select}, "전장" AS GBN FROM T_ACT WHERE DESC_GBN="전장" {$where}
		UNION SELECT {$select}, "기장" AS GBN FROM T_ACT WHERE DESC_GBN="기장" {$where}
		UNION SELECT {$select}, "선실" AS GBN FROM T_ACT WHERE DESC_GBN="선실" {$where}
		UNION SELECT {$select}, "종합" AS GBN FROM T_ACT WHERE DESC_GBN="종합" {$where}
		UNION SELECT {$select}, "배관" AS GBN FROM T_ACT WHERE DESC_GBN="배관" {$where}
		UNION SELECT {$select}, "선각" AS GBN FROM T_ACT WHERE DESC_GBN="선각" {$where}
		UNION SELECT {$select}, "기타" AS GBN FROM T_ACT WHERE DESC_GBN="기타" {$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();

	}
	public function head_result_list2($param)
	{
		$select = "IFNULL(COUNT(PO_QTY),0) AS COUNT, IFNULL(SUM(PO_QTY),0) AS C_QTY, IFNULL(SUM(WEIGHT),0) AS C_WEIGHT";
		$where = '';
		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['DATE']}-01' AND '{$param['DATE']}-31'";
		}

		
		$sql = <<<SQL
		SELECT {$select}, "500" as PROC_GBN, "PK" AS GBN FROM T_ACT WHERE PROC_GBN="500" {$where}
		UNION SELECT {$select}, "600" as PROC_GBN, "후처리" AS GBN FROM T_ACT WHERE PROC_GBN="600" {$where}
		UNION SELECT {$select}, "700" as PROC_GBN, "배송" AS GBN FROM T_ACT WHERE PROC_GBN="700" {$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}	
	public function detail_result_list($param)
	{
		$where = "";
		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['DATE']}-01' AND '{$param['DATE']}-31'";
		}
		if ((!empty($param['GBN']) && $param['GBN'] != "")) {
			$where .= " AND PROC_GBN='{$param['GBN']}'";
		}else{
			$where .= " AND PROC_GBN='800'";
		}
		if ((!empty($param['DESC']) && $param['DESC'] != "")) {
			$where .= " AND DESC_GBN='{$param['DESC']}'";
		}

		
		$sql = <<<SQL
		SELECT POR_NO, COUNT(POR_SEQ) AS SEQ, PO_QTY, WEIGHT, MCCSDESC, PORRQDA
		FROM T_ACT WHERE 1 {$where}
		GROUP BY POR_NO
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();

	}
	public function detail_result_cut($param)
	{
		$where = "";
		if ((!empty($param['DATE']) && $param['DATE'] != "")) {
			$where .= " AND PORRQDA BETWEEN '{$param['DATE']}-01' AND '{$param['DATE']}-31'";
		}
		if ((!empty($param['GBN']) && $param['GBN'] != "")) {
			$where .= " AND PROC_GBN='{$param['GBN']}'";
		}
		if ((!empty($param['DESC']) && $param['DESC'] != "")) {
			$where .= " AND DESC_GBN='{$param['DESC']}'";
		}

		$sql = <<<SQL
		SELECT 
			ifnull(COUNT(*), 0) as cnt 
		FROM( 
			SELECT POR_NO
			FROM T_ACT WHERE 1 {$where}
			GROUP BY POR_NO
		) AS AA
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row()->cnt;
	}
}
