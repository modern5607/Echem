<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}
	
	public function ajax_package()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_stockcur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_stockchange()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_release()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_dbrelease()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_claim()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}
	public function ajax_claimcur()
	{
		$sql=<<<SQL
			SELECT '1' AS COL1,'2' AS COL2, '3' AS COL3  FROM DUAL;
SQL;		
		$query = $this->db->query($sql);
		return $query->result();
	}


}
