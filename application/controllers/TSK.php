<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TSK extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->helper('test');
		$this->load->model(array('tsk_model', 'sys_model','mdm_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');
		$this->data['menuLevel'] = $this->sys_model->menu_level();

	}

	public function _remap($method, $param = array())
	{
		if ($this->input->is_ajax_request()) {
			if (method_exists($this, $method)) {
				call_user_func_array(array($this, $method), $param);
			}
		} else { //ajax가 아니면

			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				if (isset($user_id) && $user_id != "") {

					$this->load->view('/layout/header', $this->data);
					call_user_func_array(array($this, $method), $param);
					$this->load->view('/layout/tail');
				} else {

					alert('로그인이 필요합니다.', base_url('REG/login'));
				}
			} else {
				show_404();
			}
		}
	}

	//작업지시등록(납기별)
	public function dateo()
	{
		$data['title'] = "작업지시등록(납기별)";
		$this->load->view('/main100', $data);
	}
	//작업지시등록(납기별) 리스트
	public function ajax_dateo()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = (string)$this->input->post('sdate');
		$data['str']['edate'] = (string)$this->input->post('edate');
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['worker'] = ($this->input->post('worker') != true)?$this->session->userdata('user_name'):(($this->input->post('worker') == 'allworkerselect')?'':$this->input->post('worker'));

		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";
		$param['WORKER'] = "";

		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) {
			$param['PORNO'] = $data['str']['porno'];
		}
		if (!empty($data['str']['worker'])) {
			$param['WORKER'] = $data['str']['worker'];
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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->dateo_list($param, $start, $config['per_page']); 
		$data['Member']   = $this->sys_model->member_list($param); 
		$this->data['cnt'] = $this->tsk_model->dateo_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/ajax_dateo',$data);
	}
	public function dateo_form()
	{
		$param['PORNO'] = $this->input->post('por');
		$param['SEQ'] = $this->input->post('seq');
		$param['GJ'] = $this->input->post('gj');

		$data['NAME'] = $this->input->post('name');
		$data['List'] = $this->tsk_model->dateo_form($param); 
		$data['Member'] = $this->tsk_model->member_list($param); 
		$data['Gj'] = $this->sys_model->get_selectInfo("tcd.CODE",$param['GJ']); 

		$this->load->view('/tsk/dateo_form',$data);
	}
	// 작업지시 수정
	public function order_up()
	{
		$dateTime = date("Y-m-d H:i:s", time());
		$param = array(
			'DATE'     		=> trim($this->input->post("date")),	//부서
			'PORNO'     	=> $this->input->post("por"),			//이름
			'SEQ'    		=> $this->input->post("seq"),			//권한
			'GJ'    		=> $this->input->post("gj"),
			'NAME'    		=> $this->input->post("name")
		);
		
		$data = $this->tsk_model->order_up($param);

		if ($data != "") {
			$ins = array(
				'status' => 'ok',
				'msg'    => '작업지시가 수정 되었습니다.'
			);
			echo json_encode($ins);
		}
	}

	//작업지시등록(POR별)
	public function poro()
	{
		$data['title'] = "작업지시등록(POR별)";
		$this->load->view('/main100', $data);
	}
	//작업지시등록(POR별) 리스트
	public function ajax_poro()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = (string)$this->input->post('sdate');
		$data['str']['edate'] = (string)$this->input->post('edate');
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		
		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";
		
		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->poro_list($param, $start, $config['per_page']); 
		$this->data['cnt'] = $this->tsk_model->poro_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/ajax_poro',$data);
	}
	public function poro_form()
	{
		$param['PORNO'] = $this->input->post('por');
		$param['GJ'] = $this->input->post('gj');

		$data['NAME'] = $this->input->post('name');
		$data['List'] = $this->tsk_model->poro_form($param); 
		$data['Member'] = $this->tsk_model->member_list($param); 
		$data['Gj'] = $this->sys_model->get_selectInfo("tcd.CODE",$param['GJ']); 

		$this->load->view('/tsk/poro_form',$data);
	}

	//작업지시서
	public function order()
	{
		$data['title'] = "작업지시서";
		$this->load->view('/main100', $data);
	}
	//작업지시서 리스트
	public function ajax_order()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['date'] = empty($this->input->post('date'))? date("Y-m-d") :trim((string)$this->input->post('date'));
		$data['str']['worker'] = trim((string)$this->input->post('worker'));

		$params['DATE'] = "";
		$params['WORKER'] = "";

		if (!empty($data['str']['date'])) 
			$params['DATE'] = $data['str']['date'];
		
		if (!empty($data['str']['worker'])) 
			$params['WORKER'] = $data['str']['worker'];
		
		// $data['Member'] = $this->mdm_model->member_list2();
		$data['List'] = $this->tsk_model->order_list($params);
		// echo var_dump($data['List']);

		// echo var_dump($data['Member']);

		$this->load->view('/tsk/ajax_order',$data);
	}

	public function ajax_memberlist()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['date'] = trim($this->input->post('date'));

		$params['DATE'] = $data['str']['date'];
		$data['Member'] = $this->tsk_model->order_member_list($params);
		// echo var_dump($data['Member']);
		
        echo json_encode($data['Member']);
		
	}


	//공정별실적등록
	public function workr()
	{
		$data['title'] = "공정별실적등록";
		$this->load->view('/main100', $data);
	}
	//공정별실적등록 리스트
	public function ajax_workr()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = trim((string)$this->input->post('sdate'));
		$data['str']['edate'] = trim((string)$this->input->post('edate'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['worker'] = ($this->input->post('worker') != true)?$this->session->userdata('user_name'):(($this->input->post('worker') == 'allworkerselect')?"":$this->input->post('worker'));
		$data['str']['gj'] = trim((string)$this->input->post('gj'));

		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";
		$param['WORKER'] = "";
		$param['GJ'] = "";

		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }
		if (!empty($data['str']['worker'])) { $param['WORKER'] = $data['str']['worker']; }
		if (!empty($data['str']['gj'])) { $param['GJ'] = $data['str']['gj']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->workr_list($param, $start, $config['per_page']); 
		$data['Member']   = $this->sys_model->member_list($param); 
		$this->data['cnt'] = $this->tsk_model->workr_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/ajax_workr',$data);
	}
	public function workr_form()
	{
		if($this->input->post('state') == "update"){
			foreach ($this->input->post("porno") as $key => $porno) {
				$param['PORNO'][$key] = $porno;
				$param['SEQ'][$key] = $this->input->post("seq")[$key];
				$param['GJ'][$key] = $this->input->post("gj")[$key];
			}
			$param['DATE'] = $this->input->post("date");

			$data = $this->tsk_model->workr_up($param);

			if ($data != "") {
				$ins = array(
					'status' => 'ok',
					'msg'    => '실적등록이 완료 되었습니다.'
				);
				echo json_encode($ins);
			}
		}else{
			foreach ($this->input->post("porno") as $key => $porno) {
				$param['PORNO'][$key] = $porno;
				$param['SEQ'][$key] = $this->input->post("seq")[$key];
				$param['QTY'][$key] = $this->input->post("qty")[$key];
				$param['WORKER'][$key] = $this->input->post("worker")[$key];
				$param['GJ'][$key] = $this->input->post("gj")[$key];
			}

			$this->load->view('/tsk/workr_form', $param);
		}
	}

	//POR별실적등록
	public function porr()
	{
		$data['title'] = "POR별실적등록";
		$this->load->view('/main100', $data);
	}
	//POR별실적등록 리스트
	public function ajax_porr()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = trim((string)$this->input->post('sdate'));
		$data['str']['edate'] = trim((string)$this->input->post('edate'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['gj'] = trim((string)$this->input->post('gj'));

		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";
		$param['GJ'] = "";

		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }
		if (!empty($data['str']['gj'])) { $param['GJ'] = $data['str']['gj']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->porr_list($param, $start, $config['per_page']); 
		$this->data['cnt'] = $this->tsk_model->porr_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/ajax_porr',$data);
	}
	public function porr_form()
	{
		if($this->input->post('state') == "update"){
			//업데이트
			foreach ($this->input->post("porno") as $key => $porno) {
				$param['PORNO'][$key] = $porno;
				$param['GJ'][$key] = $this->input->post("gj")[$key];
			}
			$param['DATE'] = $this->input->post("date");

			$data = $this->tsk_model->porr_up($param);

			if ($data != "") {
				$ins = array(
					'status' => 'ok',
					'msg'    => '실적등록이 완료 되었습니다.'
				);
				echo json_encode($ins);
			}
		}else{
			//form에 띄워줄 내용
			foreach ($this->input->post("porno") as $key => $porno) {
				$param['PORNO'][$key] = $porno;
				$param['CNT'][$key] = $this->input->post("cnt")[$key];
				$param['QTY'][$key] = $this->input->post("qty")[$key];
				$param['GJ'][$key] = $this->input->post("gj")[$key];
			}

			$this->load->view('/tsk/porr_form', $param);
		}
	}

	//검사신청등록
	public function test()
	{
		$data['title'] = "검사신청등록";
		$this->load->view('/main50', $data);
	}
	//검사신청등록 리스트
	public function head_test()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['date'] = trim((string)$this->input->post('date'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));

		$param['DATE'] = "";
		$param['PORNO'] = "";

		if (!empty($data['str']['date'])) { $param['DATE'] = $data['str']['date']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }


		$data['List']   = $this->tsk_model->head_test_list($param); 
		$this->data['cnt'] = $this->tsk_model->head_test_cut($param);


		$this->load->view('/tsk/head_test',$data);
	}
	//검사신청등록 리스트
	public function detail_test()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['date'] = trim((string)$this->input->post('date'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));

		$param['DATE'] = "";
		$param['PORNO'] = "";

		if (!empty($data['str']['date'])) { $param['DATE'] = $data['str']['date']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }


		$data['List']   = $this->tsk_model->detail_test_list($param); 
		$this->data['cnt'] = $this->tsk_model->detail_test_cut($param);


		$this->load->view('/tsk/detail_test',$data);
	}
	// 검사신청 추가
	public function test_ins()
	{
		foreach ($this->input->post("porno") as $key => $porno) {
			$param['PORNO'][$key] = $porno;
			$param['SEQ'][$key] = $this->input->post("seq")[$key];
		}
		$param['DATE'] = $this->input->post("date");
		
		$data = $this->tsk_model->test_ins($param);

		if ($data != "") {
			$ins = array(
				'status' => 'ok',
				'msg'    => '검사신청이 등록 되었습니다.'
			);
			echo json_encode($ins);
		}
	}
	// 검사신청 삭제
	public function test_del()
	{
		foreach ($this->input->post("porno") as $key => $porno) {
			$param['PORNO'][$key] = $porno;
			$param['SEQ'][$key] = $this->input->post("seq")[$key];
		}
		
		$data = $this->tsk_model->test_del($param);

		if ($data != "") {
			$ins = array(
				'status' => 'ok',
				'msg'    => '검사신청이 취소 되었습니다.'
			);
			echo json_encode($ins);
		}
	}


	//검사등록
	public function check()
	{
		$data['title'] = "검사등록";
		$this->load->view('/main100', $data);
	}
	//검사등록 리스트
	public function ajax_check()
	{
		$Date = date("Y-m-d");
		$YY = date("Y", strtotime($Date));
		$MM = date("m", strtotime($Date));
		$DD = date("d", strtotime($Date));
		$Day = date("w", strtotime($Date));

		$data['str'] = array(); //검색어관련
		$data['str']['sweek'] = date("Y-m-d", strtotime($YY."-".$MM."-".$DD." -".$Day." day"));
		$data['str']['eweek'] = date("Y-m-d", strtotime($data['str']['sweek']." +6 day"));

		$data['str']['sdate'] = trim((string)$this->input->post('sdate'));
		$data['str']['edate'] = trim((string)$this->input->post('edate'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));

		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";

		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->check_list($param, $start, $config['per_page']); 
		$this->data['cnt'] = $this->tsk_model->check_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/ajax_check',$data);
	}
	public function check_up()
	{
		$param['PORNO'] = $this->input->post("porno");
		$param['SEQ'] = $this->input->post("seq");
		$param['YN'] = $this->input->post("yn");
		
		$data = $this->tsk_model->check_up($param);
	}



	//후처리(도장,도금)의뢰
	public function after()
	{
		$data['title'] = "후처리(도장,도금)의뢰";
		$this->load->view('/main70', $data);
	}
	//후처리(도장,도금)의뢰 리스트
	public function head_after()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = trim((string)$this->input->post('sdate'));
		$data['str']['edate'] = trim((string)$this->input->post('edate'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['yn'] = $this->input->post('yn');
		$data['str']['chk'] = $this->input->post('chk');

		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";
		$param['AYN'] = "";
		$param['HEAD'] = "YES";
		$param['IDX'] = '';

		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }
		if (!empty($data['str']['yn'])) { $param['AYN'] = $data['str']['yn']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->after_list($param, $start, $config['per_page']); 
		$this->data['cnt'] = $this->tsk_model->after_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/head_after',$data);
	}
	public function detail_after()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['seq'] = ($this->input->post('seq'))?trim((string)$this->input->post('seq')):'00';
		$data['str']['idx'] = $this->input->post('idx');

		$param['PORNO'] = "";
		$param['SEQ'] = "";
		$param['HEAD'] = "";
		$param['IDX'] = '';

		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }
		if (!empty($data['str']['seq'])) { $param['SEQ'] = $data['str']['seq']; }
		if (!empty($data['str']['idx'])) { $param['IDX'] = $data['str']['idx']; }

		$data['List']   = $this->tsk_model->after_list($param,0,1); 
		$data['Biz']   	= $this->sys_model->biz_list('후처리'); 

		$this->load->view('/tsk/detail_after',$data);
	}
	public function after_up()
	{
		$param['ENDDATE'] = $this->input->post("enddate");
		$param['IDX'] = $this->input->post("idx");
		$param['DEPT'] = $this->input->post("dept");
		$param['DATE'] = $this->input->post("date");
		$param['PORNO'] = $this->input->post("porno");
		$param['SEQ'] = $this->input->post("seq");
		$param['QTY'] = $this->input->post("qty");
		$param['WEIGHT'] = $this->input->post("weight");
		$param['YN'] = $this->input->post("yn");

		$data = $this->tsk_model->after_up($param);
	}



	//배송(납품)등록
	public function ship()
	{
		$data['title'] = "배송(납품)등록";
		$this->load->view('/main70', $data);
	}
	//배송(납품)등록 리스트
	public function head_ship()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = trim((string)$this->input->post('sdate'));
		$data['str']['edate'] = trim((string)$this->input->post('edate'));
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['yn'] = $this->input->post('yn');
		$data['str']['chk'] = $this->input->post('chk');

		$param['SDATE'] = "";
		$param['EDATE'] = "";
		$param['PORNO'] = "";
		$param['SYN'] = "";
		$param['HEAD'] = "YES";
		$param['IDX'] = '';
		
		if (!empty($data['str']['sdate'])) { $param['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $param['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }
		if (!empty($data['str']['yn'])) { $param['SYN'] = $data['str']['yn']; }



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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['List']   = $this->tsk_model->ship_list($param, $start, $config['per_page']); 
		$this->data['cnt'] = $this->tsk_model->ship_cut($param);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/tsk/head_ship',$data);
	}
	public function detail_ship()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['porno'] = trim((string)$this->input->post('porno'));
		$data['str']['seq'] = ($this->input->post('seq'))?trim((string)$this->input->post('seq')):'00';
		$data['str']['idx'] = $this->input->post('idx');

		$param['PORNO'] = "";
		$param['SEQ'] = "";
		$param['HEAD'] = "";
		$param['IDX'] = '';

		if (!empty($data['str']['porno'])) { $param['PORNO'] = $data['str']['porno']; }
		if (!empty($data['str']['seq'])) { $param['SEQ'] = $data['str']['seq']; }
		if (!empty($data['str']['idx'])) { $param['IDX'] = $data['str']['idx']; }

		$data['List']   = $this->tsk_model->ship_list($param,0,1); 
		$this->data['cnt'] = $this->tsk_model->ship_cut($param);

		$this->load->view('/tsk/detail_ship',$data);
	}
	public function ship_up()
	{
		$param['ENDDATE'] = $this->input->post("enddate");
		$param['IDX'] = $this->input->post("idx");
		$param['TR'] = $this->input->post("trgb");
		$param['DATE'] = $this->input->post("date");
		$param['PORNO'] = $this->input->post("porno");
		$param['SEQ'] = $this->input->post("seq");
		$param['QTY'] = $this->input->post("qty");
		$param['WEIGHT'] = $this->input->post("weight");
		$param['YN'] = $this->input->post("yn");
		
		$data = $this->tsk_model->ship_up($param);
	}


	//월간생산실적
	public function result()
	{
		$data['title'] = "월간생산실적";
		$this->load->view('/main30', $data);
	}
	//월간생산실적 리스트
	public function head_result()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['date'] = ($this->input->post('date'))?trim((string)$this->input->post('date')):date("Y-m");

		$param['DATE'] = "";

		if (!empty($data['str']['date'])) { $param['DATE'] = $data['str']['date']; }


		$data['List']   = $this->tsk_model->head_result_list($param); 
		$data['List2']   = $this->tsk_model->head_result_list2($param); 


		$this->load->view('/tsk/head_result',$data);
	}
	public function detail_result()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['desc'] = trim((string)$this->input->post('desc'));
		$data['str']['date'] = ($this->input->post('date'))?trim((string)$this->input->post('date')):date("Y-m");
		$data['str']['gbn'] = trim((string)$this->input->post('gbn'));
		
		$param['DATE'] = "";
		$param['DESC'] = "";		
		$param['GBN'] = "";		

		if (!empty($data['str']['date'])) { $param['DATE'] = $data['str']['date']; }
		if (!empty($data['str']['desc'])) { $param['DESC'] = $data['str']['desc']; }
		if (!empty($data['str']['gbn'])) { $param['GBN'] = $data['str']['gbn']; }


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
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');


		$data['List']   = $this->tsk_model->detail_result_list($param, $start, $config['per_page']); 
		$this->data['cnt'] = $this->tsk_model->detail_result_cut($param);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/tsk/detail_result',$data);
	}
}
