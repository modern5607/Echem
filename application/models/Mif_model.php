<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mif_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}



	public function ajax_notice_list($params,$start=0,$limit=20)
	{
		$where = '';
		if (!empty($params['TITLE']) || $params['TITLE'] != '')
			$where .="AND TITLE LIKE '%{$params['TITLE']}%'";
		if (!empty($params['CONTENT']) || $params['CONTENT'] != '')
			$where .="AND CONTENT LIKE '%{$params['CONTENT']}%'";
		if (!empty($params['END_DATE']) || $params['END_DATE'] != '')
			$where .="AND END_DATE LIKE '%{$params['END_DATE']}%'";

		$where.="AND END_DATE > DATE_FORMAT(NOW(),'%Y-%m-%d')";

			$sql = <<<SQL
			SELECT * 
			FROM T_NOTICE
			WHERE
			1
			{$where}
			ORDER BY END_DATE ASC
			LIMIT $start,$limit
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		
			return $query->result();
	}
	public function ajax_notice_cut($params)
	{
		$where = '';
		if (!empty($params['TITLE']) || $params['TITLE'] != '')
			$where .="AND TITLE LIKE '%{$params['TITLE']}%'";
		if (!empty($params['CONTENT']) || $params['CONTENT'] != '')
			$where .="AND CONTENT LIKE '%{$params['CONTENT']}%'";
		if (!empty($params['END_DATE']) || $params['END_DATE'] != '')
			$where .="AND END_DATE LIKE '%{$params['END_DATE']}%'";

		$where.="AND END_DATE > DATE_FORMAT(NOW(),'%Y-%m-%d')";
			$sql = <<<SQL
			SELECT * 
			FROM T_NOTICE
			WHERE
			1
			{$where}
SQL;

		$query = $this->db->query($sql);
		
		return $query->num_rows();
	}

	public function notice_ins($params)
	{
	

		$sql=<<<SQL
			INSERT INTO T_NOTICE(TITLE,CONTENT,END_DATE,INSERT_DATE,INSERT_ID)
			VALUES('{$params['TITLE']}','{$params['CONTENT']}','{$params['END_DATE']}',NOW(),'{$params['INSERT_ID']}')
SQL;

		$query = $this->db->query($sql);
		echo $query;
	}
}
