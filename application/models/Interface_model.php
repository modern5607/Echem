<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Interface_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}

	public function head_interface($params, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$where .= "AND START_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
		}

		$sql = <<<SQL
				SELECT
					IDX,FINISH_DATE,DATE_FORMAT(INSERT_DATE,"%Y-%m-%d") AS INSERT_DATE,DATE_FORMAT(START_DATE,"%Y-%m-%d %H:%i:%s") AS START_DATE
				FROM
					T_BATCH
				WHERE START_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'
				ORDER BY START_DATE DESC
				LIMIT {$start},{$limit}
	SQL;
		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->result();
	}

// 	public function head_interface_select($params)
// 	{
// 		$sql=<<<SQL
// 		SELECT IDX, START_DATE,FINISH_DATE
// 		FROM T_BATCH
// 		WHERE START_DATE BETWEEN '{$params['DATE']} 00:00:00' AND '{$params['DATE']} 23:59:59'
// 		ORDER BY START_DATE DESC
// SQL;
// 		$query = $this->db->query($sql);
// 		// echo $this->db->last_query();
// 		return $query->result();
// 	}

	public function detail_interface($params)
	{

		$sql=<<<SQL
			SELECT
				LEVEL,TEMP,PH,CL,PRESS,INSERT_DATE,
				DATE_FORMAT(INSERT_DATE,"%Y") AS Y,
				DATE_FORMAT(INSERT_DATE,"%m") AS M,
				DATE_FORMAT(INSERT_DATE,"%d") AS D,
				DATE_FORMAT(INSERT_DATE,"%h") AS H,
				DATE_FORMAT(INSERT_DATE,"%i") AS I,
				DATE_FORMAT(INSERT_DATE,"%s") AS S 
			FROM
				T_PARSING 
			WHERE
				TANK = '{$params['TANK']}' 
				AND INSERT_DATE BETWEEN "{$params['SDATE']} 00:00:00" 
				AND "{$params['EDATE']} 23:59:59" 
			ORDER BY
				INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);
		echo $this->db->last_query();
		return $query->result();
	}
}
