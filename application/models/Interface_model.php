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
			$where .= "AND INSERT_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
		}

		$sql = <<<SQL
				SELECT
					DATE_FORMAT( INSERT_DATE, '%Y-%m-%d' ) AS DATE,
					COUNT(*) AS CNT
				FROM
					T_PARSING
				WHERE
					TANK = '{$params['TANK']}'
					{$where}
				GROUP BY
					DATE
				ORDER BY 
					DATE DESC
				LIMIT {$start},{$limit}
	SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function head_interface_select($params)
	{
		$sql=<<<SQL
			SELECT
				IDX,
				TANK,
				DATE_FORMAT(INSERT_DATE,"%H:%i:%s") AS INSERT_DATE
			FROM
				T_PARSING 
			WHERE
				TANK = '{$params['TANK']}'
				AND INSERT_DATE BETWEEN '{$params['INSERT_DATE']} 00:00:00' AND '{$params['INSERT_DATE']} 23:59:59'
			ORDER BY
				INSERT_DATE DESC
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function detail_interface($params)
	{
		$sql=<<<SQL
			SELECT * FROM T_PARSING WHERE IDX='{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->row();
	}
}
