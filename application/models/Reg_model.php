<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_model extends CI_Model {

	public function __construct()
	{
			parent::__construct();
	}


	public function set_log_end($idx)
	{
		date_default_timezone_set('Asia/Seoul');
		$datetime = date("Y-m-d H:i:s",time());
		$this->db->set("EDATE",$datetime);
		$this->db->set("STATUS","off");
		$this->db->where("IDX",$idx);
		$this->db->update("T_LOG");
	}
	
		/* member 정보호출 */
		public function get_userchk($obj,$val)
		{
			$this->db->where($obj,$val);
			$res = $this->db->get("T_MEMBER");
	
			return $res->row();
	
		}

		// 접속 로그 입력
		public function set_log_insert($params)
		{
			$this->db->insert("T_LOG",$params);
			return $this->db->insert_id();
		}
}