<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prod_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}

	public function ajax_workorder()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->Last_query();
		return $query->result();
	}

	public function ajax_pworkorder()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_matinput()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();	
	}

	public function ajax_pharvest()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_pprodcur()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_dprodperf()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function ajax_prodmonitor()
	{
		$sql = <<<SQL
		SELECT
			'1' AS COL1,
			'2' AS COL2,
			'2' AS COL3 
		FROM DUAL;
SQL;
		$query = $this->db->query($sql);
		return $query->result();	
	}
}