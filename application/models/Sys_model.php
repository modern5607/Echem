<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sys_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}

	//권한등록 리스트
	public function menu_list($params)
	{
		if (!empty($params['NAME']) && $params['NAME'] != "") {
			$this->db->like("MENU_NAME", $params['NAME']);
		}
		if (!empty($params['CODE']) && $params['CODE'] != "") {
			$this->db->like("MENU_CODE", $params['CODE']);
		}
		if (!empty($params['LEVEL']) && $params['LEVEL'] != "") {
			$this->db->like("MENU_LEVEL", $params['LEVEL']);
		}

		$res = $this->db->get("T_MENU");
		// echo $this->db->last_query();
		return $res->result();
	}
	// 메뉴등록 권한 업데이트
	public function menu_up($param)
	{

		$this->db->set("MENU_LEVEL", $param['level']);
		$this->db->where("IDX", $param['idx']);
		$this->db->update("T_MENU");

		return $this->db->affected_rows();
	}
	// 헤더에서 비교에 사용할 메뉴레벨
	public function menu_level()
	{
		$level = $this->db->get("T_MENU");
		return $level->result();
	}



	// 접속 로그 리스트
	public function userlog_list($param, $start = 0, $limit = 20)
	{
		if (!empty($param['LOGIN']) && $param['LOGIN'] != "") {
			$this->db->where("SDATE BETWEEN '{$param['LOGIN']} 00:00:00' AND '{$param['LOGIN']} 23:59:59'");
		}
		if (empty($param['ADMIN'])) {
			$this->db->where("MID !='admin'");
			$this->db->where("IP !='::1'");
		}
		if (!empty($param['ID']) && $param['ID'] != "") {
			$this->db->like("MID", $param['ID']);
		}


		$this->db->order_by("SDATE", "desc");
		$this->db->limit($limit, $start);
		$res = $this->db->get("T_LOG");
		return $res->result();
	}
	public function userlog_cut($param)
	{
		if (!empty($param['LOGIN']) && $param['LOGIN'] != "") {
			$this->db->where("SDATE BETWEEN '{$param['LOGIN']} 00:00:00' AND '{$param['LOGIN']} 23:59:59'");
		}
		if (empty($param['ADMIN'])) {
			$this->db->where("MID !='admin'");
			$this->db->where("IP !='::1'");
		}
		if (!empty($param['ID']) && $param['ID'] != "") {
			$this->db->like("MID", $param['ID']);
		}

		$this->db->select("COUNT(*) as CUT");

		$res = $this->db->get("T_LOG");
		return $res->row()->CUT;
	}


	public function register_list($param, $start = 0, $limit = 20)
	{
		$where = '';
		if (!empty($param['ID']) || $param['ID'] != '')
			$where .= "AND ID LIKE '%{$param['ID']}%'";
		if (!empty($param['NAME']) || $param['NAME'] != '')
			$where .= "AND NAME LIKE '%{$param['NAME']}%'";
		if (!empty($param['LEVEL']) || $param['LEVEL'] != '')
			$where .= "AND LEVEL = '{$param['LEVEL']}'";

		$sql = <<<SQL
			SELECT * FROM T_MEMBER
			WHERE
			1
			{$where}
			ORDER BY IDX,ID,NAME
			LIMIT {$start},{$limit}
			
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}


	public function register_cut($param)
	{
		$where = '';
		if (!empty($param['ID']) || $param['ID'] != '')
			$where .= "AND ID LIKE '%{$param['ID']}%'";
		if (!empty($param['NAME']) || $param['NAME'] != '')
			$where .= "AND NAME LIKE '%{$param['NAME']}%'";
		if (!empty($param['LEVEL']) || $param['LEVEL'] != '')
			$where .= "AND LEVEL = '{$param['LEVEL']}'";

		$sql = <<<SQL
			SELECT * FROM T_MEMBER
			WHERE
			1
			{$where}
SQL;

		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->num_rows();
	}


	//아이디 중복체크
	public function chk_memberid($id)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("ID",$id);
		$query = $this->db->get("T_MEMBER");
		// echo $this->db->last_query();
		$data['msg'] = "사용가능한 아이디입니다.";
		$data['state'] = 1;
		if($query->row()->cnt > 0){
			$data['msg'] = "사용중인 아이디입니다.";
			$data['state'] = 2;
		}
		return $data;
	}

	public function get_member_info($idx)
	{

		$data = $this->db->where(array('IDX'=>$idx))
						->get("T_MEMBER");
		return $data->row();
	}

	public function member_formupdate($params,$idx)
	{
		if(!empty($idx)){
			$this->db->update("T_MEMBER",$params,array("IDX"=>$idx));
		}else{
			$this->db->insert("T_MEMBER",$params);
			$idx = $this->db->insert_id();
		}
		return $idx;
	}

	//사용자 권한 등록에서 권한 수치 변경
	public function level_up($param)
	{
		$this->db->set("LEVEL", $param['level']);
		$this->db->where("IDX", $param['idx']);
		$this->db->update("T_MEMBER");

		return $this->db->affected_rows();
	}

	//버전관리
	public function ver_list()
	{
		// $this->db->limit($limit,$start);
		$query = $this->db->get("T_VERSION");
		//echo $this->db->last_query();


		return $query->result();
	}
	public function ver_list_cnt()
	{
		// $this->db->limit($limit,$start);
		$query = $this->db->get("T_VERSION");
		// echo $this->db->last_query();


		return $query->num_rows();
	}
	//신규등록
	public function insert_version($params)
	{
		// INSERT INTO T_VERSION (VERSION,REMARK,INSERT_DATE)
		// VALUES("00000.999","테스트 입니다.",NOW())

		$sql = "INSERT INTO T_VERSION(VERSION,REMARK,INSERT_DATE) VALUES('{$params['NO']}','{$params['REMARK']}',NOW())";

		$query = $this->db->query($sql);
		// echo $this->db->last_query();		
	}
	//버전 관리 삭제
	public function delete_version($param)
	{
		$this->db->where("IDX", $param['IDX']);
		$this->db->delete("T_VERSION");
		return $this->db->affected_rows();
	}

	public function modified_version($param)
	{
		$this->db->where("IDX", $param['IDX']);
		$query = $this->db->get("T_VERSION");
		// echo $this->db->last_query();
		return $query->row();
	}
	//버전 관리 수정
	public function update_version($param, $MIDX = "")
	{
		if ($MIDX == "") {
			$query = $this->db->insert("T_VERSION", $param);
			$xxx = $this->db->insert_id();
		} else {
			$this->db->where("IDX", $MIDX);
			$query = $this->db->update("T_VERSION", $param);
			$xxx = $MIDX;
		}
		return  $xxx;
	}

	//공통코드
	public function get_selectInfo($fild, $set)
	{
		$where[$fild] = $set;
		$this->db->select("tch.IDX, tch.CODE, tch.NAME, tcd.CODE as D_CODE, tcd.NAME as D_NAME");
		$this->db->from("T_COCD_D as tcd");
		$this->db->join("T_COCD_H as tch", "tch.IDX = tcd.H_IDX");

		$this->db->where("tcd.USE_YN", "Y");
		$this->db->where("tch.USE_YN", "Y");

		$this->db->where($where);
		$this->db->order_by("S_NO", "ASC");
		$query = $this->db->get();
		// echo $this->db->last_query();

		return $query->result();
	}


	//멤버리스트, 작업자리스트, 멤버 리스트, 작업자 리스트
	public function member_list()
	{
		$this->db->where("STATE", "Y");
		$query = $this->db->get("T_MEMBER");
		// echo $this->db->last_query();
		return $query->result();
	}
	//회사리스트, 업체리스트
	public function biz_list($type)
	{
		$this->db->where("USE_YN", "Y");
		$this->db->where("CUST_TYPE", $type);
		$query = $this->db->get("T_BIZ");
		// echo $this->db->last_query();
		return $query->result();
	}


	//페이지 생성 함수
	public function CreatePagination($pageNum, $cnt, $pageGroup)
	{
		$pagenation = '';

		$totalPage = ceil($cnt / 20);
		$curPage = ceil($pageNum / 20);
		$pageGroup = (int)((int)$curPage / 10);
		$startgroup = $pageGroup * 10;		// 0 10 20 30

		if ($totalPage - $startgroup >= 10)
			$endgroup = ($pageGroup + 1) * 10;
		else
			$endgroup = $totalPage;

		//데이터가 20개 이하로 되어있다면 조기리턴
		if ($endgroup == 1)
			return;

		// echo "pageGroup:" . $pageGroup . "\n";
		// echo "startgourp:" . $startgroup . "\n";
		// echo "totalPage:" . $totalPage . "\n";
		// echo "curPage:" . $curPage . "\n";
		// echo "endgroup:" . $endgroup . "\n";

		//이전 페이지 "<"
		if ($pageGroup >= 1)
			$pagenation .= "<li><a class='prev-link' href='#' data-pagegroup=" . ($pageGroup - 1) . " data-startpage=" . (($pageGroup - 1) * 20 + 180) . " ><</a></li>";

		//페이지네이션 넘버 "1 2 3 4 ..."
		for ($i = $startgroup; $i < $endgroup; $i++) {
			$active = ($i == $curPage) ? "active" : "";
			$pageNum = $i * 20;		//0 20 40 60 
			$pagenation .= "<li class=" . $active . "><a class='page-link' href='#' data-startpage=" . $pageNum . ">" . ($i + 1) . "</a></li>";
		}

		//다음 페이지 ">"
		if ($endgroup < $totalPage)
			$pagenation .= "<li><a class='next-link' href='#' data-pagegroup=" . ($pageGroup + 1) . " data-startpage=" . ($endgroup * 20) . ">></a></li>";

		return $pagenation;
	}


	public function version_Chk($val)
	{
		$this->db->select("COUNT(*) as cnt");
		$this->db->where("VERSION", $val);
		$query = $this->db->get("T_VERSION");
		$data['state'] = 1;
		if ($query->row()->cnt > 0) {
			$data['msg'] = "이미 사용중인 버전입니다.";
			$data['state'] = 2;
		}
		return $data;
	}
}
