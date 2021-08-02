<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pln_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}

	public function ajax_act_list($params,$start=0,$limit=20)
	{
		$where = '';

		if($params['SPODATE']!='' && $params['EPODATE']!='')
			$where .= "AND POWRDA BETWEEN '{$params['SPODATE']}' AND '{$params['EPODATE']}'";

		$sql=<<<SQL
			SELECT *
			FROM T_ACT
			WHERE
			1
			{$where}
			ORDER BY PORRQDA DESC
			LIMIT $start,$limit
SQL;
		$query= $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}

	public function ajax_act_cut()
	{
		$sql=<<<SQL
			SELECT * FROM T_ACT
SQL;
		$query= $this->db->query($sql);
		return $query->num_rows();

	}

	//T_ACTTEMP 데이터 삭제
	public function acttemp_del()
	{
		$this->db->truncate("T_ACTTEMP");
	}
	public function set_temp_data($param)
	{
		$DESC_GBN ='';
		//문자 하나 추출
		$sub = substr($param['item'][1],5,1);

		// echo $sub."<br>";
		if($sub >='A' && $sub <='Z')
		{
			$sql = <<<SQL
				SELECT * FROM T_COCD_H WHERE CODE = '{$sub}'
SQL;
			$query = $this->db->query($sql);
			$DESC_GBN = $query->row()->NAME;
		}
		else	//숫자 3자리 추출
		{
			$sub = substr($param['item'][1],5,3);
			// echo $sub."<br>";
			$query = $this->db->query("SELECT MAX(CAST(NAME as UNSIGNED)) AS 'MAX'  FROM T_COCD_D"); 

			//품목코드 범위를 넘으면
			if($sub > $query->row()->MAX)
			{
				//"선체" 초기화
				$DESC_GBN = "선체";
			}
			else
			{
				$sql =<<<SQL
				SELECT IDX,CODE,NAME FROM T_COCD_H AS H WHERE (SELECT H_IDX FROM T_COCD_D AS D ORDER BY ABS(NAME- '{$sub}') LIMIT 1) = H.IDX
	SQL;
			$query = $this->db->query($sql);

			$DESC_GBN = $query->row()->NAME;
			}
		}
			
		// echo $DESC_GBN."<br>";
		
		$data = array(
			'PJT_NO'       	=> $param['item'][0],			//호선
			'PO_NO'       	=> $param['item'][1],			//계약번호
			'POR_NO'       	=> $param['item'][2],			//POR번호
			'POR_SEQ'       => $param['item'][3],			//SEQ
			'UNITW'       	=> $param['item'][4],			//단중
			'WEIGHT'       	=> $param['item'][5],			//총중량
			'PO_QTY'       	=> $param['item'][6],			//계약 수량
			'PO_AMT'       	=> $param['item'][7],			//계약금액
			'REC_QTY'       => $param['item'][8],			//입고량
			'MCCSDESC'      => $param['item'][9],			//품명/재질/규격
			'ACTIVITY'      => $param['item'][10],			//ACTIVITY
			'PORRQDT'       => $param['item'][11],			//소요부서
			// '??'       		=> $param['item'][12],		//제작업체
			// '??'       		=> $param['item'][13],		//후처리
			'AVCODE'       	=> $param['item'][14],			//후처리업체
			'PORWRDA'       => $param['item'][15],			//POR발행일
			'POWRDA'       	=> $param['item'][16],			//계약일
			'PORRQDA'       => $param['item'][17],			//MP납기
			'INRQDA'       	=> $param['item'][18],			//제작검사시한
			'INSDA'       	=> $param['item'][19],			//제작검사완료
			'INSDIF'       	=> $param['item'][20],			//제작검사차이
			'INSPAC'       	=> $param['item'][21],			//포장검사시한
			'INSPDA'       	=> $param['item'][22],			//포장검사완료
			'INSPDIF'       => $param['item'][23],			//포장검사차이
			// '??'       		=> $param['item'][24],		//제작관리예약
			'MANAGRQ'       => $param['item'][25],			//제작관리인계시한
			'MANAGDA'       => $param['item'][26],			//제작관리인계입고
			// '??'       		=> $param['item'][27],		//제작관리입고
			// '??'       		=> $param['item'][28],		//제작관리차이
			// '??'       		=> $param['item'][29],		//후처리예약
			'POSTRQ'       	=> $param['item'][30],			//후처리시한
			'POSTDA'       	=> $param['item'][31],			//후처리완료
			'POSTINDA'     	=> $param['item'][32],			//후처리입고
			'REGDAG'       	=> $param['item'][33],			//자재배송요청
			'TRNDAG'       	=> $param['item'][34],			//자재배송완료
			'TMDIF'       	=> $param['item'][35],			//자재배송차이
			'REQNO'       	=> $param['item'][36],			//배송요청번호
			'SHOP'       	=> $param['item'][37],			//배송장소
			'REQMPNO'       => $param['item'][38],			//배송요청자
			'TEL_NO'       	=> $param['item'][39],			//요청자연락처
			// '??'       		=> $param['item'][40],		//배송요청부서
			// '??'       		=> $param['item'][41],		//배송담당자
			// '??'       		=> $param['item'][42],		//사급요청번호
			// '??'       		=> $param['item'][43],		//사급요청일
			'VENDORCD'      => $param['item'][44],			//사급업체
			// '??'       		=> $param['item'][45],		//MPPL공사
			'MPPLNO'       	=> $param['item'][46],			//MPPLNO
			// '??'       		=> $param['item'][47],		//MPPLSEQ
			// '??'       		=> $param['item'][48],		//구매당당
			'EX_NAME'       => $param['filename'],	//등록 파일 이름
			// 'NEW_GBN'       => 'Y',				//신규구분
			'DESC_GBN'       => $DESC_GBN,				//품목구분
			'CHKDATE'  		=> date('Y-m-d H:i:s', time()),
			'CHKID'    		=> $this->session->userdata('user_name')
		);

		$this->db->insert("T_ACTTEMP", $data);
	}

	public function acttemp_list()
	{
		$query = $this->db->get("T_ACTTEMP");
		return $query->result();
	}

	//생산계획등록
	public function ajax_plan_list($params)
	{
		$sql=<<<SQL
			SELECT
				"기준월" AS NAME,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7) AS __1MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7) AS _0MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7) AS _1MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7) AS _2MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7) AS _3MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7) AS _4MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7) AS _5MONTH,
				LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7) AS _6MONTH,
				""AS DESC_GBN
			UNION
			SELECT
				"합계",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31')) THEN PO_QTY END),0) ,
				""
			FROM
				T_ACTPLN
			UNION
			SELECT
				"선장",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='선장' THEN PO_QTY END),0) ,
				"선장"
			FROM
				T_ACTPLN
				UNION
			SELECT
				"전장",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='전장' THEN PO_QTY END),0) ,
				"전장"
			FROM
				T_ACTPLN
				UNION
			SELECT
				"기장",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='기장' THEN PO_QTY END),0) ,
				"기장"
			FROM
				T_ACTPLN
				UNION
			SELECT
				"선실",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='선실' THEN PO_QTY END),0) ,
				"선실"
			FROM
				T_ACTPLN
				UNION
			SELECT
				"배관",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='배관' THEN PO_QTY END),0) ,
				"배관"
			FROM
				T_ACTPLN
				UNION
			SELECT
				"선체",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='선체' THEN PO_QTY END),0) ,
				"선체"
			FROM
				T_ACTPLN
				UNION
			SELECT
				"종합",
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(YEAR('{$params['PLAN_DA']}'),'-01-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL -1 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +0 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +1 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +2 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +3 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +4 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +5 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				TRUNCATE(SUM(CASE WHEN (PLAN_DA BETWEEN CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-01') AND CONCAT(LEFT(DATE_ADD('{$params['PLAN_DA']}',INTERVAL +6 MONTH),7),'-31'))AND DESC_GBN='종합' THEN PO_QTY END),0) ,
				"종합"
			FROM
				T_ACTPLN
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}

	public function plan_form_list($params)
	{
		$params['SDATE'] = $params['DATE'];
		if($params['TYPE']=="ER")
		{
			$params['SDATE'] = explode('-',$params['DATE'])[0]."-01";
		}
		

		$sql=<<<SQL
			SELECT
				WEEK( PLAN_DA,4 ) AS "WEEK",
				DESC_GBN AS "DESC",
				COUNT(*) AS COUNT,
				SUM( PO_QTY ) AS "AMOUNT",
				SUM( WEIGHT ) AS "WEIGHT" 
			FROM
				T_ACTPLN
			WHERE
				PLAN_DA BETWEEN '{$params['SDATE']}-01' 
				AND '{$params['DATE']}-31' 
				AND DESC_GBN = '{$params['DESC']}'
			GROUP BY
				WEEK( PLAN_DA,4 )
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $query->result();
	}

	public function plan_form_list2($params)
	{
		$sql=<<<SQL
			SELECT POR_NO,POR_SEQ,PLAN_DA,WEIGHT,PO_QTY
			FROM T_ACTPLN
			WHERE PLAN_DA BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function plan_up($params)
	{
		$sql=<<<SQL
			UPDATE T_ACTPLN
			SET PLAN_DA = '{$params['DATE']}',C_PLAN_DA = NOW()
			WHERE POR_NO='{$params['POR_NO']}' AND POR_SEQ = '{$params['POR_SEQ']}'
SQL;

		$query = $this->db->query($sql);
		echo $query;
	}



	//납기지연1
	public function delay_list1($params)
	{
		$where ='';
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$where .="AND PORRQDA BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";
		}
		if(!empty($params['SJGB'] || $params['SJGB']!=""))
			$where .="AND OUTB_GBN = '{$params['SJGB']}'";
		
		if (!empty($params['PORNO']) && $params['PORNO'] != "") {
			$where .="AND POR_NO LIKE '%{$params['PORNO']}%'";
		}

		$sql=<<<SQL
			SELECT
				"건수" GB,
				COUNT( CASE WHEN DESC_GBN = '선체' THEN 1 END ) _1,
				COUNT( CASE WHEN DESC_GBN = '선장' THEN 1 END ) _2,	
				COUNT( CASE WHEN DESC_GBN = '전장' THEN 1 END ) _3,	
				COUNT( CASE WHEN DESC_GBN = '기장' THEN 1 END ) _4,
				COUNT( CASE WHEN DESC_GBN = '선실' THEN 1 END ) _5,	
				COUNT( CASE WHEN DESC_GBN = '종합' THEN 1 END ) _6,	
				COUNT( CASE WHEN DESC_GBN = '배관' THEN 1 END ) _7,	
				COUNT( CASE WHEN DESC_GBN = '기타' THEN 1 END ) _8,	
				COUNT( * ) TOTAL
			FROM T_ACT WHERE PROC_GBN < '700' {$where}
			UNION
				SELECT
				"수량" GB,
				SUM( CASE WHEN DESC_GBN = '선체' THEN PO_QTY END ) _1,	
				SUM( CASE WHEN DESC_GBN = '선장' THEN PO_QTY END ) _2,	
				SUM( CASE WHEN DESC_GBN = '전장' THEN PO_QTY END ) _3,	
				SUM( CASE WHEN DESC_GBN = '기장' THEN PO_QTY END ) _4,
				SUM( CASE WHEN DESC_GBN = '선실' THEN PO_QTY END ) _5,	
				SUM( CASE WHEN DESC_GBN = '종합' THEN PO_QTY END ) _6,	
				SUM( CASE WHEN DESC_GBN = '배관' THEN PO_QTY END ) _7,	
				SUM( CASE WHEN DESC_GBN = '기타' THEN PO_QTY END ) _8,	
				SUM( PO_QTY ) TOTAL
			FROM T_ACT WHERE PROC_GBN < '700' {$where}
			UNION
				SELECT
				"중량" GB,
				SUM( CASE WHEN DESC_GBN = '선체' THEN WEIGHT END ) _1,	
				SUM( CASE WHEN DESC_GBN = '선장' THEN WEIGHT END ) _2,	
				SUM( CASE WHEN DESC_GBN = '전장' THEN WEIGHT END ) _3,	
				SUM( CASE WHEN DESC_GBN = '기장' THEN WEIGHT END ) _4,
				SUM( CASE WHEN DESC_GBN = '선실' THEN WEIGHT END ) _5,	
				SUM( CASE WHEN DESC_GBN = '종합' THEN WEIGHT END ) _6,	
				SUM( CASE WHEN DESC_GBN = '배관' THEN WEIGHT END ) _7,	
				SUM( CASE WHEN DESC_GBN = '기타' THEN WEIGHT END ) _8,	
				SUM( WEIGHT ) TOTAL
			FROM T_ACT WHERE PROC_GBN < '700' {$where}
SQL;


		$res = $this->db->query($sql);
		// echo $this->db->last_query();
		return $res->result();
	}
	//납기지연2
	public function delay_list2($param, $start = 0, $limit = 15)
	{
		$where ='';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .="AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if(!empty($param['SJGB'] || $param['SJGB']!=""))
			$where .="AND OUTB_GBN = '{$param['SJGB']}'";
		
		if (!empty($param['PORNO']) || $param['PORNO'] != "") {
			$where .="AND POR_NO LIKE '%{$param['PORNO']}%'";
		}

		$sql=<<<SQL
			SELECT
				POR_NO,
				POR_SEQ,
				PO_QTY,
				MCCSDESC,
				POWRDA,
				PORRQDA,
				DATEDIFF( PORRQDA, POWRDA ) AS CNT_DATE,
				NEW_MPDA,
				CHAG_MPDA,
				DATEDIFF( DATE_FORMAT( NOW( ), "%Y-%m-%d" ), PORRQDA ) AS DELAY_DATE 
			FROM
				T_ACT 
			WHERE
				PROC_GBN < '700' 
				{$where}
				LIMIT {$start},{$limit}
SQL;
		$res = $this->db->query($sql);
		// echo $this->db->last_query();
		return $res->result();
	}
	//납기지연 페이지 카운트
	public function delay_cut($param)
	{
		$where ='';
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$where .="AND PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'";
		}
		if(!empty($param['SJGB'] || $param['SJGB']!=""))
			$where .="AND OUTB_GBN = '{$param['SJGB']}'";
		
		if (!empty($param['PORNO']) || $param['PORNO'] != "") {
			$where .="AND POR_NO LIKE '%{$param['PORNO']}%'";
		}

		$sql=<<<SQL
			SELECT
				POR_NO,
				POR_SEQ,
				PO_QTY,
				MCCSDESC,
				POWRDA,
				PORRQDA,
				DATEDIFF( PORRQDA, POWRDA ) AS CNT_DATE,
				NEW_MPDA,
				CHAG_MPDA,
				DATEDIFF( DATE_FORMAT( NOW( ), "%Y-%m-%d" ), PORRQDA ) AS DELAY_DATE 
			FROM
				T_ACT 
			WHERE
				PROC_GBN < '700' 
				{$where}
SQL;
		$res = $this->db->query($sql);
		// echo $this->db->last_query();
		return $res->num_rows();
	}


	//납기 변경 이력 현황 
	public function change_list($param, $start = 0, $limit = 20)
	{
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if (!empty($param['SJGB']) && $param['SJGB'] != "") {
			$this->db->where("OUTB_GBN", $param['SJGB']);
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$this->db->where("POR_NO", $param['PORNO']);
		}

		$this->db->limit($limit, $start);
		$res = $this->db->get("T_ACT");
		// echo $this->db->last_query();
		return $res->result();
	}
	//납기 변경 이력 현황 페이지 카운트
	public function change_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if (!empty($param['SJGB']) && $param['SJGB'] != "") {
			$this->db->where("OUTB_GBN", $param['SJGB']);
		}
		if (!empty($param['PORNO']) && $param['PORNO'] != "") {
			$this->db->where("POR_NO", $param['PORNO']);
		}


		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}


	//납기변경내역조회 
	public function alter_list($param, $start = 0, $limit = 20)
	{
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if (!empty($param['SJGB']) && $param['SJGB'] != "") {
			$this->db->where("OUTB_GBN", $param['SJGB']);
		}
		if (!empty($param['IDX']) && $param['IDX'] != "") {
			$this->db->where("CHAG_MPDA", $param['IDX']);
		}

		$this->db->limit($limit, $start);
		$res = $this->db->get("T_ACT");
		// echo $this->db->last_query();
		return $res->result();
	}
	//납기변경내역조회 현황 페이지 카운트
	public function alter_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		if (!empty($param['SJGB']) && $param['SJGB'] != "") {
			$this->db->where("OUTB_GBN", $param['SJGB']);
		}
		if (!empty($param['IDX']) && $param['IDX'] != "") {
			$this->db->where("CHAG_MPDA", $param['IDX']);
		}
		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}


	//월간 실행 계획 현황
	public function action_list($params, $start = 0, $limit = 20)
	{
		if($params['TYPE'] == "num")
		{
			$sql=<<<SQL
			SELECT
				DATE_FORMAT( PLAN_DA, '%Y-%m' ) AS MONTH,
				WEEK ( PLAN_DA, 4 ) AS WEEK,
				COUNT(CASE WHEN DESC_GBN='선체' THEN 1 END) AS _1,
				COUNT(CASE WHEN DESC_GBN='선장' THEN 1 END) AS _2,
				COUNT(CASE WHEN DESC_GBN='전장' THEN 1 END) AS _3,
				COUNT(CASE WHEN DESC_GBN='기장' THEN 1 END) AS _4,
				COUNT(CASE WHEN DESC_GBN='선실' THEN 1 END) AS _5,
				COUNT(CASE WHEN DESC_GBN='종합' THEN 1 END) AS _6,
				COUNT(CASE WHEN DESC_GBN='배관' THEN 1 END) AS _7,
				COUNT(CASE WHEN DESC_GBN='기타' THEN 1 END) AS _8,
				COUNT(*) AS TOTAL
			FROM
				T_ACTPLN
			WHERE
				PLAN_DA BETWEEN '{$params['SDATE']}-01' AND '{$params['EDATE']}-31'
			GROUP BY
				WEEK
			ORDER BY
				PLAN_DA ASC
			LIMIT {$start},{$limit}
SQL;
		}
		else if($params['TYPE'] == "weight")
		{
			$sql=<<<SQL
			SELECT
				DATE_FORMAT( PLAN_DA, '%Y-%m' ) AS MONTH,
				WEEK ( PLAN_DA, 4 ) AS WEEK,
				SUM(CASE WHEN DESC_GBN='선체' THEN WEIGHT END) AS _1,
				SUM(CASE WHEN DESC_GBN='선장' THEN WEIGHT END) AS _2,
				SUM(CASE WHEN DESC_GBN='전장' THEN WEIGHT END) AS _3,
				SUM(CASE WHEN DESC_GBN='기장' THEN WEIGHT END) AS _4,
				SUM(CASE WHEN DESC_GBN='선실' THEN WEIGHT END) AS _5,
				SUM(CASE WHEN DESC_GBN='종합' THEN WEIGHT END) AS _6,
				SUM(CASE WHEN DESC_GBN='배관' THEN WEIGHT END) AS _7,
				SUM(CASE WHEN DESC_GBN='기타' THEN WEIGHT END) AS _8,
				SUM(WEIGHT) AS TOTAL
			FROM
				T_ACTPLN
			WHERE
				PLAN_DA BETWEEN '{$params['SDATE']}-01' AND '{$params['EDATE']}-31'
			GROUP BY
				WEEK
			ORDER BY
				PLAN_DA ASC
			LIMIT {$start},{$limit}
SQL;
		}
		
		
		

		$res = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $res->result();
	}
	//월간 실행 계획 현황 페이지 카운트
	public function action_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}

	public function work_list1($params)
	{
		$sql=<<<SQL
			SELECT
				"정규" AS 'GB',
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '선체' ) THEN 1 END ) AS "_1",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '선장' ) THEN 1 END ) AS "_2",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '전장' ) THEN 1 END ) AS "_3",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '기장' ) THEN 1 END ) AS "_4",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '선실' ) THEN 1 END ) AS "_5",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '종합' ) THEN 1 END ) AS "_6",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '배관' ) THEN 1 END ) AS "_7",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' AND DESC_GBN = '기타' ) THEN 1 END ) AS "_8",
				COUNT( CASE WHEN ( OUTB_GBN = 'R' ) THEN 1 END ) AS TOTAL 
			FROM
				T_ACT 
			WHERE
				PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' UNION
			SELECT
				"돌발",
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '선체' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '선장' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '전장' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '기장' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '선실' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '종합' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '배관' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' AND DESC_GBN = '기타' ) THEN 1 END ),
				COUNT( CASE WHEN ( OUTB_GBN = 'G' ) THEN 1 END ) 
			FROM
				T_ACT 
			WHERE
				PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' UNION
			SELECT
				"계",
				COUNT( CASE WHEN ( DESC_GBN = '선체' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '선장' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '전장' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '기장' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '선실' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '종합' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '배관' ) THEN 1 END ),
				COUNT( CASE WHEN ( DESC_GBN = '기타' ) THEN 1 END ),
				COUNT( CASE WHEN OUTB_GBN = 'R' OR OUTB_GBN = 'G' THEN 1 END ) 
			FROM
				T_ACT 
			WHERE
				PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' UNION
			SELECT
				"진행률",
				IFNULL( B._1 / A._1, 0 ) *100,
				IFNULL( B._2 / A._2, 0 ) *100,
				IFNULL( B._3 / A._3, 0 ) *100,
				IFNULL( B._4 / A._4, 0 ) *100,
				IFNULL( B._5 / A._5, 0 ) *100,
				IFNULL( B._6 / A._6, 0 ) *100,
				IFNULL( B._7 / A._7, 0 ) *100,
				IFNULL( B._8 / A._8, 0 ) *100,
				IFNULL( B.TOTAL / A.TOTAL, 0 ) *100
			FROM
				(
			SELECT
				COUNT( CASE WHEN ( DESC_GBN = '선체' ) THEN 1 END ) _1,
				COUNT( CASE WHEN ( DESC_GBN = '선장' ) THEN 1 END ) _2,
				COUNT( CASE WHEN ( DESC_GBN = '전장' ) THEN 1 END ) _3,
				COUNT( CASE WHEN ( DESC_GBN = '기장' ) THEN 1 END ) _4,
				COUNT( CASE WHEN ( DESC_GBN = '선실' ) THEN 1 END ) _5,
				COUNT( CASE WHEN ( DESC_GBN = '종합' ) THEN 1 END ) _6,
				COUNT( CASE WHEN ( DESC_GBN = '배관' ) THEN 1 END ) _7,
				COUNT( CASE WHEN ( DESC_GBN = '기타' ) THEN 1 END ) _8,
				COUNT( * ) TOTAL 
			FROM
				T_ACT 
			WHERE
				PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' 
				) AS A,
				(
			SELECT
				COUNT( CASE WHEN ( DESC_GBN = '선체' ) THEN 1 END ) _1,
				COUNT( CASE WHEN ( DESC_GBN = '선장' ) THEN 1 END ) _2,
				COUNT( CASE WHEN ( DESC_GBN = '전장' ) THEN 1 END ) _3,
				COUNT( CASE WHEN ( DESC_GBN = '기장' ) THEN 1 END ) _4,
				COUNT( CASE WHEN ( DESC_GBN = '선실' ) THEN 1 END ) _5,
				COUNT( CASE WHEN ( DESC_GBN = '종합' ) THEN 1 END ) _6,
				COUNT( CASE WHEN ( DESC_GBN = '배관' ) THEN 1 END ) _7,
				COUNT( CASE WHEN ( DESC_GBN = '기타' ) THEN 1 END ) _8,
				COUNT( * ) TOTAL 
			FROM
				T_ACT A,
				T_WRK B 
			WHERE
				A.POR_NO = B.POR_NO 
				AND A.POR_SEQ = B.POR_SEQ 
				AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' 
				AND B.END_YN = 'Y' 
				) AS B
SQL;

		$query=$this->db->query($sql);

		return $query->result();
	}

	//월간 계획 대비 실적
	public function work_list2($params, $start = 0, $limit = 20)
	{
		$sql=<<<SQL
			SELECT WEEK
				( PORRQDA, 4 ) WEEK,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '선체' ) THEN 1 END ) R1,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '선장' ) THEN 1 END ) R2,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '전장' ) THEN 1 END ) R3,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '기장' ) THEN 1 END ) R4,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '선실' ) THEN 1 END ) R5,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '종합' ) THEN 1 END ) R6,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '배관' ) THEN 1 END ) R7,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' AND C.DESC_GBN = '기타' ) THEN 1 END ) R8,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'R' ) THEN 1 END ) R_TOTAL,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '선체' ) THEN 1 END ) G1,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '선장' ) THEN 1 END ) G2,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '전장' ) THEN 1 END ) G3,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '기장' ) THEN 1 END ) G4,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '선실' ) THEN 1 END ) G5,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '종합' ) THEN 1 END ) G6,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '배관' ) THEN 1 END ) G7,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' AND C.DESC_GBN = '기타' ) THEN 1 END ) G8,
				COUNT( CASE WHEN ( C.OUTB_GBN = 'G' ) THEN 1 END ) G_TOTAL,
				COUNT( CASE WHEN ( C.DESC_GBN = '선체' ) THEN 1 END ) TOTAL1,
				COUNT( CASE WHEN ( C.DESC_GBN = '선장' ) THEN 1 END ) TOTAL2,
				COUNT( CASE WHEN ( C.DESC_GBN = '전장' ) THEN 1 END ) TOTAL3,
				COUNT( CASE WHEN ( C.DESC_GBN = '기장' ) THEN 1 END ) TOTAL4,
				COUNT( CASE WHEN ( C.DESC_GBN = '선실' ) THEN 1 END ) TOTAL5,
				COUNT( CASE WHEN ( C.DESC_GBN = '종합' ) THEN 1 END ) TOTAL6,
				COUNT( CASE WHEN ( C.DESC_GBN = '배관' ) THEN 1 END ) TOTAL7,
				COUNT( CASE WHEN ( C.DESC_GBN = '기타' ) THEN 1 END ) TOTAL8,
				COUNT( * ) TOTAL,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '선체' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '선체' ) THEN 1 END)) * 100 PROC1,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '선장' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '선장' ) THEN 1 END)) * 100 PROC2,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '전장' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '전장' ) THEN 1 END)) * 100 PROC3,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '기장' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '기장' ) THEN 1 END)) * 100 PROC4,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '선실' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '선실' ) THEN 1 END)) * 100 PROC5,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '종합' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '종합' ) THEN 1 END)) * 100 PROC6,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '배관' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '배관' ) THEN 1 END)) * 100 PROC7,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND DESC_GBN = '기타' AND END_YN = 'Y') / COUNT( CASE WHEN ( C.DESC_GBN = '기타' ) THEN 1 END)) * 100 PROC8,
				( (SELECT COUNT(*) FROM T_WRK  WHERE POR_NO = C.POR_NO AND POR_SEQ = C.POR_SEQ AND END_YN = 'Y') / COUNT( * ) ) * 100 PROC_TOTAL
			FROM
				T_ACT C
			WHERE 
				PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' 
			GROUP BY
				WEEK
SQL;
		$res = $this->db->query($sql);
		// echo $this->db->last_query();
		
		return $res->result();
	}
	//원간 계획 대비 실적 페이지 카운트
	public function work_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}

	public function result_list1($params)
	{
		$sql=<<<SQL
			SELECT A.GB,R_PLN,G_PLN,PLN_TOTAL,R_ACT,G_ACT,ACT_TOTAL,ROUND((B.ACT_TOTAL / A.PLN_TOTAL)*100,1) PROC
			FROM
			(
			(SELECT	"건수" GB,	COUNT( CASE WHEN OUTB_GBN = 'R' THEN 1 END ) R_PLN,	COUNT( CASE WHEN OUTB_GBN = 'G' THEN 1 END ) G_PLN,	COUNT( * ) PLN_TOTAL FROM T_ACTPLN WHERE	PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' 
			UNION 
			SELECT	"수량" GB,	SUM( CASE WHEN OUTB_GBN = 'R' THEN PO_QTY END ) R_PLN,	SUM( CASE WHEN OUTB_GBN = 'G' THEN PO_QTY END ) G_PLN,	SUM( PO_QTY ) PLN_TOTAL FROM T_ACTPLN WHERE	PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' 
			UNION
			SELECT	"중량" GB,	SUM( CASE WHEN OUTB_GBN = 'R' THEN WEIGHT END ) R_PLN,	SUM( CASE WHEN OUTB_GBN = 'G' THEN WEIGHT END ) G_PLN,	SUM( WEIGHT ) PLN_TOTAL FROM T_ACTPLN WHERE	PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31'
			) AS A,
			(
			SELECT "건수" GB, COUNT(CASE WHEN A.OUTB_GBN = 'R' THEN 1 END) R_ACT,COUNT( CASE WHEN A.OUTB_GBN = 'G' THEN 1 END ) G_ACT,	COUNT( * ) ACT_TOTAL 
			FROM T_ACT A,T_ACTPLN B WHERE	A.POR_NO = B.POR_NO AND A.POR_SEQ = B.POR_SEQ AND A.TRNDAG IS NOT NULL AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31'
			UNION
			SELECT "수량" GB,SUM(CASE WHEN A.OUTB_GBN = 'R' THEN A.PO_QTY END) R_ACT, SUM( CASE WHEN A.OUTB_GBN = 'G' THEN A.PO_QTY END ) G_ACT,	SUM( A.PO_QTY ) ACT_TOTAL 
			FROM T_ACT A,T_ACTPLN B WHERE	A.POR_NO = B.POR_NO AND A.POR_SEQ = B.POR_SEQ AND A.TRNDAG IS NOT NULL AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31'
			UNION
			SELECT "중량" GB,SUM(CASE WHEN A.OUTB_GBN = 'R' THEN A.WEIGHT END) R_ACT,SUM( CASE WHEN A.OUTB_GBN = 'G' THEN A.WEIGHT END ) G_ACT,	SUM( A.WEIGHT ) ACT_TOTAL 
			FROM T_ACT A,T_ACTPLN B WHERE	A.POR_NO = B.POR_NO AND A.POR_SEQ = B.POR_SEQ AND A.TRNDAG IS NOT NULL AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31'
			) AS B
			)
			WHERE A.GB = B.GB
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	//원간 계획 대비 실적
	public function result_list2($params, $start = 0, $limit = 20)
	{
		
		$sql = <<<SQL
			SELECT A.WEEK,A.GB,R_PLN,G_PLN,PLN_TOTAL,R_ACT,G_ACT,ACT_TOTAL,ROUND((B.ACT_TOTAL / A.PLN_TOTAL)*100,1) PROC
			FROM
			(( SELECT * FROM
			(SELECT	WEEK(PORRQDA) WEEK, "건수" GB,	COUNT( CASE WHEN OUTB_GBN = 'R' THEN 1 END ) R_PLN,	COUNT( CASE WHEN OUTB_GBN = 'G' THEN 1 END ) G_PLN,	COUNT( * ) PLN_TOTAL FROM T_ACTPLN WHERE	PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' GROUP BY WEEK
			UNION 
			SELECT	WEEK(PORRQDA) WEEK, "수량" GB,	SUM( CASE WHEN OUTB_GBN = 'R' THEN PO_QTY END ) R_PLN,	SUM( CASE WHEN OUTB_GBN = 'G' THEN PO_QTY END ) G_PLN,	SUM( PO_QTY ) PLN_TOTAL FROM T_ACTPLN WHERE	PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' GROUP BY WEEK
			UNION
			SELECT	WEEK(PORRQDA) WEEK, "중량" GB,	SUM( CASE WHEN OUTB_GBN = 'R' THEN WEIGHT END ) R_PLN,	SUM( CASE WHEN OUTB_GBN = 'G' THEN WEIGHT END ) G_PLN,	SUM( WEIGHT ) PLN_TOTAL FROM T_ACTPLN WHERE	PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' GROUP BY WEEK
			) AS A ORDER BY WEEK, GB) A,
			(SELECT * FROM
			(SELECT WEEK(A.PORRQDA) WEEK, "건수" GB, COUNT(CASE WHEN A.OUTB_GBN = 'R' THEN 1 END) R_ACT,COUNT( CASE WHEN A.OUTB_GBN = 'G' THEN 1 END ) G_ACT,	COUNT( * ) ACT_TOTAL 
			FROM T_ACT A,T_ACTPLN B WHERE	A.POR_NO = B.POR_NO AND A.POR_SEQ = B.POR_SEQ AND A.TRNDAG IS NOT NULL AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' GROUP BY WEEK
			UNION
			SELECT WEEK(A.PORRQDA) WEEK, "수량" GB,SUM(CASE WHEN A.OUTB_GBN = 'R' THEN A.PO_QTY END) R_ACT, SUM( CASE WHEN A.OUTB_GBN = 'G' THEN A.PO_QTY END ) G_ACT,	SUM( A.PO_QTY ) ACT_TOTAL 
			FROM T_ACT A,T_ACTPLN B WHERE	A.POR_NO = B.POR_NO AND A.POR_SEQ = B.POR_SEQ AND A.TRNDAG IS NOT NULL AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' GROUP BY WEEK
			UNION
			SELECT WEEK(A.PORRQDA) WEEK, "중량" GB,SUM(CASE WHEN A.OUTB_GBN = 'R' THEN A.WEIGHT END) R_ACT,SUM( CASE WHEN A.OUTB_GBN = 'G' THEN A.WEIGHT END ) G_ACT,	SUM( A.WEIGHT ) ACT_TOTAL 
			FROM T_ACT A,T_ACTPLN B WHERE	A.POR_NO = B.POR_NO AND A.POR_SEQ = B.POR_SEQ AND A.TRNDAG IS NOT NULL AND A.PORRQDA BETWEEN '{$params['DATE']}-01' AND '{$params['DATE']}-31' GROUP BY WEEK
			) AS B ORDER BY WEEK, GB ) B
			)
			WHERE A.WEEK = B.WEEK AND A.GB = B.GB
			ORDER BY A.WEEK, A.GB
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}
	//수주 현황
	public function ordc_list($param, $start = 0, $limit = 20)
	{
		$where ='';

		if(!empty($param['PORNO']) || $param['PORNO'] !='')
			$where .="AND POR_NO LIKE '%{$param['PORNO']}%'";

		if(!empty($param['SJGB']) || $param['SJGB'] !='')
			$where .="AND OUTB_GBN = '{$param['SJGB']}'";
			
		$sql = <<<SQL
			SELECT *
			FROM T_ACT
			WHERE POWRDA BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'
			{$where}
			ORDER BY PORRQDA DESC, POR_SEQ ASC
			limit {$start},{$limit}
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		// echo var_dump($query->result());
		return $query->result();
	}
	
	public function ordc_cut($param)
	{
		$this->db->select("COUNT(*) as CUT");
		if ((!empty($param['SDATE']) && $param['SDATE'] != "") && (!empty($param['EDATE']) && $param['EDATE'] != "")) {
			$this->db->where("POWRDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		}
		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}
	//납기별 수주 현황(주간) MP납기 기준
	public function ordw_list($param, $start = 0, $limit = 20)
	{
		$where ='';

		if(!empty($param['SJGB']) || $param['SJGB'] !='')
			$where .="AND OUTB_GBN = '{$param['SJGB']}'";
			
		$sql = <<<SQL
			SELECT *
			FROM T_ACT
			WHERE PORRQDA BETWEEN '{$param['SDATE']} 00:00:00' AND '{$param['EDATE']} 23:59:59'
			{$where}
			ORDER BY PORRQDA ASC
			limit {$start},{$limit}
SQL;

		$query = $this->db->query($sql);
		//echo $this->db->last_query();
		// echo var_dump($query->result());s
		return $query->result();
	}
	//납기별 수주 현황(주간) 페이지
	public function ordw_cut($param)
	{
		if(!empty($param['SJGB']) || $param['SJGB'] !=''){
			$this->db->where("OUTB_GBN",$param['SJGB']);
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->where("PORRQDA BETWEEN '{$param['SDATE']}' AND '{$param['EDATE']}'");
		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}
	//납기별 수주 현황(월간) MP납기 기준
	public function ordm_list($param, $start = 0, $limit = 20)
	{
		$where ='';

		if(!empty($param['SJGB']) || $param['SJGB'] !='')
			$where .="AND OUTB_GBN = '{$param['SJGB']}'";

		$sql = <<<SQL
			SELECT *
			FROM T_ACT
			WHERE PORRQDA BETWEEN '{$param['MONTH']}-01 00:00:00' AND '{$param['MONTH']}-31 23:59:59'
			{$where}
			ORDER BY PORRQDA ASC
			limit {$start},{$limit}
SQL;

		$query = $this->db->query($sql);
		//echo $this->db->last_query();
		return $query->result();
	}
	//납기별 수주 현황(월간) 페이지
	public function ordm_cut($param)
	{
		
		if(!empty($param['SJGB']) || $param['SJGB'] !=''){
			$this->db->where("OUTB_GBN",$param['SJGB']);
		}

		$this->db->select("COUNT(*) as CUT");
		$this->db->where("PORRQDA BETWEEN '{$param['MONTH']}-01' AND '{$param['MONTH']}-31'");
		$res = $this->db->get("T_ACT");
		return $res->row()->CUT;
	}
}
