<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Eqp_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}


	public function eqp_list($params,$start,$limit)
	{
		$where ='';
		if(!empty($params['EQGB']))
			$where .="AND EQPGB = '{$params['EQGB']}'";
		if(!empty($params['EQNAME']))
			$where .="AND NAME LIKE '%{$params['EQNAME']}%'";

		$sql=<<<SQL
			SELECT * FROM T_MASTER
			WHERE 1
			{$where}
			LIMIT {$start},{$limit}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query($sql); 
		return $query->result();
	}

	public function eqp_list_cut($params)
	{
		$where ='';
		if(!empty($params['EQGB']))
			$where .="AND EQPGB = '{$params['EQGB']}'";
		if(!empty($params['EQNAME']))
			$where .="AND NAME LIKE '%{$params['EQNAME']}%'";

		$sql=<<<SQL
			SELECT * FROM T_MASTER
			WHERE 1
			{$where}
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query($sql);
		return $query->num_rows();
	}

	public function detail_eqpins_list($params)
	{
		$sql=<<<SQL
			SELECT * FROM T_MASTER
			WHERE IDX = '{$params['IDX']}'
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query($sql);
		return $query->row();
	}

	//장비생산등록 Insert
	public function eqpins_ins($params)
	{
		$sql=<<<SQL
			INSERT INTO T_MASTER (MNGNUM,NAME,EQPGB,STANDARD,IDENNUM,BUYDATE,EQPSTATUS,ASSETGB,MAKE,CORCYCLE,REMARK)
			VALUES('{$params['MNGNUM']}','{$params['NAME']}','{$params['EQPGB']}','{$params['STANDARD']}','{$params['IDENNUM']}','{$params['BUYDATE']}','{$params['EQPSTATUS']}','{$params['ASSETGB']}','{$params['MAKE']}','{$params['CORCYCLE']}','{$params['REMARK']}')
SQL;

		$query = $this->db->query($sql);
		return $query;
	}

	//장비생산등록 Update
	public function eqpins_up($params)
	{
		$sql=<<<SQL
		UPDATE T_MASTER
		SET MNGNUM = '{$params['MNGNUM']}',
			NAME = '{$params['NAME']}',
			EQPGB = '{$params['EQPGB']}',
			STANDARD = '{$params['STANDARD']}',
			IDENNUM = '{$params['IDENNUM']}',
			BUYDATE = '{$params['BUYDATE']}',
			EQPSTATUS = '{$params['EQPSTATUS']}',
			ASSETGB = '{$params['ASSETGB']}',
			MAKE = '{$params['MAKE']}',
			CORCYCLE = '{$params['CORCYCLE']}',
			REMARK = '{$params['REMARK']}'
			WHERE IDX = '{$params['IDX']}'
SQL;

		$query = $this->db->query($sql);
		return $query;
	}

	public function eqpma_ins($params)
	{
		$sql=<<<SQL
			INSERT INTO T_CORRECT (EQP_IDX,CORDATE,WRITER,REVIEWER,APPROVER,REMARK,INSERT_DATE)
			VALUES('{$params['IDX']}','{$params['CORDATE']}','{$params['WRITER']}','{$params['REVIEWER']}','{$params['APPROVER']}','{$params['REMARK']}',NOW())
SQL;
		$query = $this->db->query($sql);

		if($query==1)
		{
			$sql=<<<SQL
				UPDATE T_MASTER
				SET CORDATE = '{$params['CORDATE']}',
					NEXTCORDATE = '{$params['NEXTCORDATE']}'
				WHERE IDX = '{$params['IDX']}'
SQL;
			$query = $this->db->query($sql);
		}
		return $query;
	}

	public function detail_eqphis_list($params)
	{
		$sql=<<<SQL
		SELECT INSERT_DATE,CORDATE,WRITER,REVIEWER,APPROVER,REMARK
		FROM T_CORRECT
		WHERE EQP_IDX = '{$params['IDX']}'
		ORDER BY INSERT_DATE DESC
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query($sql);
		return $query->result();
	}

	public function eqpled_list($params)
	{
		$sql=<<<SQL
			SELECT * FROM T_CORRECT WHERE EQP_IDX = '{$params['IDX']}'
SQL;

		$query = $this->db->query($sql);
		return $query->result();
	}

}
