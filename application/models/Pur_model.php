<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pur_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}


	public function ajax_matorder()
	{
		$sql=<<<SQL
			SELECT 
				'1' AS COL1,
				'2' AS COL2,
				'3' AS COL3
			FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		//echo var_dump($query->result());
		return $query->result();
	}

	public function ajax_enter()
	{
		$sql=<<<SQL
			SELECT 
				'1' AS COL1,
				'2' AS COL2,
				'3' AS COL3
			FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		//echo var_dump($query->result());
		return $query->result();
	}
	
	public function ajax_orderenter()
	{
		$sql=<<<SQL
			SELECT 
				'1' AS COL1,
				'2' AS COL2,
				'3' AS COL3
			FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		//echo var_dump($query->result());
		return $query->result();
	}

	public function ajax_denter()
	{
		$sql=<<<SQL
			SELECT 
				'1' AS COL1,
				'2' AS COL2,
				'3' AS COL3
			FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		//echo var_dump($query->result());
		return $query->result();
	}
}
