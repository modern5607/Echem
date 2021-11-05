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

}
