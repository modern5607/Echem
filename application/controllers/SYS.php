<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SYS extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		$this->data['userLevel'] = $this->session->userdata('user_level');

		$this->load->model(array('sys_model', 'mdm_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');
		$this->data['menuLevel'] = $this->sys_model->menu_level();
	}

	public function _remap($method, $params = array())
	{
		if ($this->input->is_ajax_request()) {
			if (method_exists($this, $method)) {
				call_user_func_array(array($this, $method), $params);
			}
		} else { //ajax가 아니면

			if (method_exists($this, $method)) {
				$this->load->view('/layout/header', $this->data);
				call_user_func_array(array($this, $method), $params);
				$this->load->view('/layout/tail');
			} else {
				show_404();
			}
		}
	}
	/*시스템 관리*/
	public function version()
	{
		$data['title'] = "버전관리";
		$this->load->view('/main100', $data);
	}
	/*ajax 버전관리(테이블)*/
	public function ajax_version()
	{
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;

		$data['verList'] = $this->sys_model->ver_list();

		$this->data['cnt'] = $this->sys_model->ver_list_cnt();
		//echo $this->data['cnt'];

		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/sys/ajax_version', $data);
	}

	// 메뉴등록
	public function menu($idx = "")
	{
		$data['title'] = "메뉴등록";
		$this->load->view('/main100', $data);
	}
	//메뉴등록 리스트
	public function ajax_menu($idx = "")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['name'] = trim($this->input->post('name')); //MEMBER ID
		$data['str']['code'] = trim($this->input->post('code')); //MEMBER ID
		$data['str']['level'] = $this->input->post('level'); //LEVEL

		$params['NAME'] = "";
		$params['CODE'] = "";
		$params['LEVEL'] = "";

		if (!empty($data['str']['name'])) {
			$params['NAME'] = $data['str']['name'];
		}
		if (!empty($data['str']['code'])) {
			$params['CODE'] = $data['str']['code'];
		}
		if (!empty($data['str']['level'])) {
			$params['LEVEL'] = $data['str']['level'];
		}

		$data['menuList'] = $this->sys_model->menu_list($params);
		$data['menuLevel'] = $this->sys_model->menu_level();

		$this->load->view('/sys/ajax_menu', $data);
	}
	// munu 권한 업데이트
	public function menu_up()
	{
		$param['idx'] = $this->input->post('idx');
		$param['level'] = $this->input->post('sqty');

		$data = $this->sys_model->menu_up($param);
		echo $data;
	}


	//level empty
	public function level($idx = "")
	{
		$data['title'] = "사용자 권한등록";
		$this->load->view('main100', $data);
	}

	//level 영역
	public function ajax_level()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['mid'] = trim($this->input->post('mid')); //MEMBER ID
		$data['str']['mname'] = trim($this->input->post('mname')); //MEMBER ID
		$data['str']['level'] = $this->input->post('level'); //LEVEL

		$params['ID'] = "";
		$params['NAME'] = "";
		$params['LEVEL'] = "";

		if (!empty($data['str']['mid']))
			$params['ID'] = $data['str']['mid'];

		if (!empty($data['str']['mname']))
			$params['NAME'] = $data['str']['mname'];

		if (!empty($data['str']['level']))
			$params['LEVEL'] = $data['str']['level'];


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;

		$start = $pageNum;
		$data['pageNum'] = $start;

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['List'] = $this->mdm_model->member_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->mdm_model->member_cut($params);

		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/sys/ajax_level', $data);
	}

	//사용자 권한 등록 수치 변경
	public function level_up()
	{
		$param['idx'] = $this->input->post('idx');
		$param['level'] = $this->input->post('sqty');

		$data = $this->sys_model->level_up($param);
		echo $data;
	}

	/* 버전관리 (신규등록)*/
	public function version_form()
	{
		$data['title'] = "버전관리";

		$this->load->view('/sys/version_form', $data);
	}

	/* 버전관리 (신규등록)*/
	public function insert_ver_form()
	{

		$mode = $this->input->post("VER_NO");
		$remark  = $this->input->post("VER_REMARK");

		$params['NO'] = $mode;
		$params['REMARK'] = $remark;

		$data['List'] = $this->sys_model->insert_version($params);
		// echo $data['list'];

		$this->load->view('/register/version', $data);
	}

	/* 버전관리 (삭제)*/
	public function version_del()
	{
		$param['IDX'] = $this->input->post("IDX");
		$data = $this->sys_model->delete_version($param);
		echo $data;
	}

	/* 버전관리 (수정)*/
	public function version_modified()
	{
		$data['title'] = "버전관리";
		$param['IDX'] = $this->input->post("IDX");
		$data["List"] = $this->sys_model->modified_version($param);
		// echo var_dump($data['List']);
		// echo json_encode($data);
		$this->load->view('/sys/version_form', $data);
	}

	/* 버전관리 (업데이트)*/
	public function version_up()
	{
		$MIDX = $this->input->post("MIDX");
		$param['VERSION'] = $this->input->post("VER_NO");
		$param['REMARK'] = $this->input->post("VER_REMARK");
		$param['INSERT_ID'] = $this->session->userdata('user_id');
		$param['INSERT_DATE'] = date("Y-m-d H:i:s", time());

		$data = $this->sys_model->update_version($param, $MIDX);

		if ($data > 0) {
			alert('수정 되었습니다', base_url('register/version'));
		}
	}

	// 접속로그
	public function userlog($idx = "")
	{
		$data['title'] = "메뉴등록";
		$this->load->view('/main100', $data);
	}
	//메뉴등록 리스트
	public function ajax_userlog($idx = "")
	{
		$data['str'] = array(); //검색어관련
		$data['str']['login'] = $this->input->post('login');
		$data['str']['admin'] = $this->input->post('admin');
		$data['str']['id'] = $this->input->post('id');

		$params['LOGIN'] = "";
		$params['ADMIN'] = "";
		$params['ID'] = "";

		$data['qstr'] = "?P";
		if (!empty($data['str']['login'])) {
			$params['LOGIN'] = $data['str']['login'];
			$data['qstr'] .= "&login=" . $data['str']['login'];
		}
		if (!empty($data['str']['admin'])) {
			$params['ADMIN'] = $data['str']['admin'];
			$data['qstr'] .= "&admin=" . $data['str']['admin'];
		}
		if (!empty($data['str']['id'])) {
			$params['ID'] = $data['str']['id'];
			$data['qstr'] .= "&id=" . $data['str']['id'];
		}



		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;

		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;


		$data['title'] = "접속기록";
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['userlog'] = $this->sys_model->userlog_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->sys_model->userlog_cut($params);



		/* pagenation start */

		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();



		$this->load->view('/sys/ajax_userlog', $data);
	}

	public function version_Chk()
	{
		$data = $this->sys_model->version_Chk($this->input->post("val"));
		echo json_encode($data);
	}
	
}
