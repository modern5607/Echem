<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qual_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}
	public function ajax_qexam()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_perfpoor()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_qualitycur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_pooranal()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}


}
