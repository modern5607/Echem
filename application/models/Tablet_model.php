<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tablet_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	// 기초 세팅 dual 호출
	public function dual($params)
	{
		$this->db->select("1 as col1, 2 as col2, 3 as col3, 4 as col4, 5 as col5, 6 as col6, 7 as col7, 8 as col8, 9 as col9");

		$sql=<<<SQL
			SELECT 1 as col1, 2 as col2, 3 as col3, 4 as col4, 5 as col5, 6 as col6, 7 as col7, 8 as col8, 9 as col9
			FROM DUAL
SQL;

	$query = $this->db->query($sql);
	return $query->result();
	}


	//성형 리스트
	public function get_sh_list($date = '', $param)
	{
		$where = '';

		if ($date != "") {
			$where .= " AND A.TRANS_DATE BETWEEN '1999-01-01' AND '{$date}'";
		}

		if (!empty($param['BK'])) {
			$GJGB = "JHBK";
		} else {
			$GJGB = $param['GJGB'];
		}

		$where .= " AND GJ_GB = '{$GJGB}'";

		$sql = <<<SQL
			SELECT
				A.IDX AS TRANS_IDX,
				H.SERIES_NM,
				B.ITEM_NAME,
				A.ORDER_QTY,
				A.REMARK,
				B.SH_QTY,
				ifnull(A.PROD_QTY,0 ) as PROD_QTY,
				END_YN
			FROM
				t_items_orders AS A
				JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
			WHERE
				1
				{$where}
				AND (END_YN IS NULL OR END_YN='')
SQL;


		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}


	public function tablet_order_numlist($param)
	{
		$where = '';
		if (!empty($param['idx']) && $param['idx'] != "") {
			$where .= " AND A.IDX = {$param['idx']}";
		}
		if (!empty($param['date']) && $param['date'] != "") {
			$where .= " AND A.TRANS_DATE BETWEEN '1999-01-01' AND '{$param['date']}'";
		}

		$sql = <<<SQL
			SELECT
				A.IDX AS TRANS_IDX,
				H.SERIES_NM,
				B.ITEM_NAME,
				C.COLOR,
				A.ORDER_QTY,
				A.REMARK,
				B.JH_QTY,
				ifnull(A.PROD_QTY,0) as PROD_QTY
			FROM
				t_inventory_orders AS A
				LEFT JOIN t_items AS B ON B.IDX = A.ITEMS_IDX
				LEFT JOIN t_series_d AS C ON C.IDX = A.SERIESD_IDX
				LEFT JOIN t_series_h AS H ON H.IDX = B.SERIES_IDX 
			WHERE
				(A.END_YN != 'Y'
				OR A.END_YN IS NULL)
				{$where}
			order by
				SERIES_NM, ITEM_NAME, COLOR
SQL;


		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	//성형 입력 ajax
	public function get_ajax_sh_info($params)
	{
		$sql = <<<SQL
			SELECT
				tio.IDX,
				tsh.SERIES_NM,
				ti.ITEM_NAME,
				tio.ORDER_QTY,
				IFNULL(tio.PROD_QTY,0) AS PROD_QTY
			FROM
				t_items_orders as tio,
				t_items as ti,
				t_series_h as tsh
			WHERE
				GJ_GB = 'SH' 
				AND tio.IDX = {$params['IDX']}
				AND tio.ITEMS_IDX = ti.IDX
				AND ti.SERIES_IDX = tsh.IDX
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();

		return $query->row();
	}

	//성형 Update
	public function update_sh_order($params)
	{
		//선택한 작업지시의 정보 가져오기
		$info = $this->db->query("SELECT * from t_items_orders where IDX='{$params['IDX']}'")->row();

		//끝나있으면 조기 리턴
		if ($info->END_YN == 'Y')
			return 0;

		//DB에 저장되어있던 PROD_QTY와 입력한 QTY를 서로 합함
		$sum = $info->PROD_QTY + $params['F_QTY'] + $params['BQTY'];
		$sumb = $info->BQTY + $params['BQTY'];

		//합해서 지시수량보다 같거나 많으면 END_YN을 Y처리, 그외 빈칸(null)
		if ($info->ORDER_QTY <= $sum)
			$endyn = 'Y';
		else
			$endyn = '';


		//해당 지시정보 업데이트
		$sql = <<<SQL
 			UPDATE
 				t_items_orders
 			SET 
				PROD_QTY = '{$sum}',END_YN = '{$endyn}',UPDATE_DATE = NOW(), BQTY = '{$sumb}'
			WHERE
				IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);

		$username = $this->session->userdata('user_name');
		$fqty = $params['F_QTY'] + $params['BQTY'];


		$info2 = $this->db->query("SELECT * from t_items_orders ORDER BY IDX DESC LIMIT 1")->row();
		$sql = <<<SQL
			INSERT INTO t_items_trans ( ITEMS_IDX, TRANS_DATE, KIND, IN_QTY, GJ_GB, INSERT_ID, INSERT_DATE, BQTY, REMARK, ORDER_IDX )
			VALUES('{$info->ITEMS_IDX}',NOW(),'IN','{$params['F_QTY']}','SH','{$username}',NOW(),'{$params['BQTY']}','{$params['GB']}', '{$info2->IDX}')
SQL;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}

	
	public function timer_update($params)
	{
		$this->db->set(array("REMARK" => "{$params["time"]}"));
		$this->db->where("CODE","Second");
		$this->db->update("t_cocd_d");

		return $this->db->affected_rows();
	}
}
