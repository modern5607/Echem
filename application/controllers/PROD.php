<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PROD extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		$this->data['ssubpos'] = $this->uri->segment(3);


		$this->load->helper('test');
		$this->load->model(array('mif_model', 'sys_model', 'prod_model'));

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

				$user_id = $this->session->userdata('user_id');
				if (isset($user_id) && $user_id != "") {

					$this->load->view('/layout/header', $this->data);
					call_user_func_array(array($this, $method), $params);
					$this->load->view('/layout/tail');
				} else {

					alert('로그인이 필요합니다.', base_url('REG/login'));
				}
			} else {
				show_404();
			}
		}
	}

	// 	작업지시등록
	public function workorder()
	{
		$data['title'] = '작업지시등록';
		return $this->load->view('main50', $data);
	}
	public function head_workorder()
	{
		$data['str']['date'] = $this->input->post('date');
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = (isset($data['str']['biz'])) ? $data['str']['biz'] : '';
		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());
		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		//모델
		$data['list'] = $this->prod_model->head_workorder($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_workorder_cut($params);
		$data['BIZ'] = $this->sys_model->biz_list();

		// echo $this->data['cnt'];
		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('prod/head_workorder', $data);
	}

	public function detail_workorder()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if (!empty($params['IDX']) && !empty($params['ACT_IDX'])) {
			$data['str']['mode'] = 'mod';
			$data['info'] = $this->prod_model->detail_workorder($params);
		} else if (empty($params['IDX']) && !empty($params['ACT_IDX'])) {
			$data['str']['mode'] = 'new';
			$data['info'] = $this->prod_model->detail_workorder($params);
		}

		// echo var_dump($data['info']);
		$this->load->view('prod/detail_workorder', $data);
	}

	public function order_form()
	{

		$params1['END_YN'] = 'N';
		$data['ACT'] = $this->prod_model->get_act($params1);
		// echo var_dump($data['ACT']);

		$data['COMPONENT'] = $this->prod_model->get_component();

		return $this->load->view('prod/order_form', $data);
	}

	public function insert_workorder()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['ORDER_DATE'] =	 $this->input->post('ORDER_DATE');
		$data['START_DATE'] =	 $this->input->post('START_DATE');
		$data['END_DATE'] =		 $this->input->post('END_DATE');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['ORDER_DATE'] = $data['ORDER_DATE'];
		$params['START_DATE'] = $data['START_DATE'];
		$params['END_DATE'] = $data['END_DATE'];
		$params['REMARK'] = $data['REMARK'];
		$params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->insert_workorder($params);
		echo $data['result'];
	}

	public function update_workorder()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['START_DATE'] =	 $this->input->post('START_DATE');
		$data['END_DATE'] =		 $this->input->post('END_DATE');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['START_DATE'] = $data['START_DATE'];
		$params['END_DATE'] = $data['END_DATE'];
		$params['REMARK'] = $data['REMARK'];
		$params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->update_workorder($params);
		echo $data['result'];
	}
	public function del_workorder()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];

		$data['result'] = $this->prod_model->del_workorder($params);
		echo $data['result'];
	}

	// 공정별 작업지시
	public function pworkorder()
	{
		$data['title'] = '공정별 작업지시';
		return $this->load->view('main50', $data);
	}

	public function head_pworkorder()
	{
		$data['str']['date'] = $this->input->post('date');
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = (isset($data['str']['biz'])) ? $data['str']['biz'] : '';
		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());
		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		//모델
		$data['list'] = $this->prod_model->head_pworkorder($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_pworkorder_cut($params);
		$data['BIZ'] = $this->sys_model->biz_list();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('prod/head_pworkorder', $data);
	}

	public function detail_pworkorder()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if (!empty($data['idx']) && !empty($data['hidx'])) {
			$data['info'] = $this->prod_model->detail_pworkorder($params);

			if ($data['info']->EACHORDER == "Y")
				$data['str']['mode'] = "mod";
			else
				$data['str']['mode'] = "new";
		}

		// echo $data['str']['mode'];
		$this->load->view('prod/detail_pworkorder', $data);
	}

	public function update_pworkorder()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['RAW_DATE'] =		 $this->input->post('RAW_DATE');
		$data['NA2CO3_DATE'] = 	 $this->input->post('NA2CO3_DATE');
		$data['MIX_DATE'] =		 $this->input->post('MIX_DATE');
		$data['WASH_DATE'] =	 $this->input->post('WASH_DATE');
		$data['DRYPHASE_DATE'] = $this->input->post('DRYPHASE_DATE');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['RAW_DATE'] = $data['RAW_DATE'];
		$params['NA2CO3_DATE'] = $data['NA2CO3_DATE'];
		$params['MIX_DATE'] = $data['MIX_DATE'];
		$params['WASH_DATE'] = $data['WASH_DATE'];
		$params['DRYPHASE_DATE'] = $data['DRYPHASE_DATE'];
		$params['REMARK'] = $data['REMARK'];
		// $params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->update_pworkorder($params);
		echo $data['result'];
	}
	public function del_pworkorder()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];

		$data['result'] = $this->prod_model->del_pworkorder($params);
		echo $data['result'];
	}

	// 원재료 투입 입력
	public function matinput()
	{
		$data['title'] = '원재료 투입 입력';
		return $this->load->view('main50', $data);
	}
	public function head_matinput()
	{
		$data['str']['date'] = $this->input->post('date');
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = (isset($data['str']['biz'])) ? $data['str']['biz'] : '';
		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());
		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->prod_model->head_matinput($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_matinput_cut($params);
		$data['BIZ'] = $this->sys_model->biz_list();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('prod/head_matinput', $data);
	}

	public function detail_matinput()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if (!empty($data['idx']) && !empty($data['hidx'])) {
			$data['info'] = $this->prod_model->detail_matinput($params);

			if ($data['info']->RAWINPUT_YN == "Y")
				$data['str']['mode'] = "mod";
			else
				$data['str']['mode'] = "new";
		}

		// echo $data['str']['mode'];


		$this->load->view('prod/detail_matinput', $data);
	}

	public function del_matinput()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];

		$data['result'] = $this->prod_model->del_matinput($params);
		echo $data['result'];
	}

	public function update_matinput()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['RAW_INPUT'] = 	$this->input->post("RAW_INPUT");
		$data['NA2CO3_INPUT'] = $this->input->post("NA2CO3_INPUT");
		$data['LICL_INPUT'] = $this->input->post("LICL_INPUT");
		$data['NACL_INPUT'] = $this->input->post("NACL_INPUT");
		$data['REMARK'] = $this->input->post("REMARK");

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['RAW_INPUT'] = $data['RAW_INPUT'];
		$params['NA2CO3_INPUT'] = $data['NA2CO3_INPUT'];
		$params['LICL_INPUT'] = $data['LICL_INPUT'];
		$params['NACL_INPUT'] = $data['NACL_INPUT'];
		$params['REMARK'] = $data['REMARK'];
		// $params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->update_matinput($params);
		echo $data['result'];
	}

	// 공정별 생산내역
	public function pprodcur()
	{
		$data['title'] = '공정별 생산내역';
		return $this->load->view('main50', $data);
	}
	public function head_pprodcur()
	{
		$data['str']['date'] = $this->input->post('date');
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = (isset($data['str']['biz'])) ? $data['str']['biz'] : '';
		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());
		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->prod_model->head_pprodcur($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_pprodcur_cut($params);
		$data['BIZ']=$this->sys_model->biz_list();

		//=====================================

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('prod/head_pprodcur', $data);
	}

	public function detail_pprodcur()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if (!empty($data['idx']) && !empty($data['hidx'])) {
			$data['info'] = $this->prod_model->detail_pprodcur($params);

			if ($data['info']->PPINPUT_YN == "Y")
				$data['str']['mode'] = "mod";
			else
				$data['str']['mode'] = "new";
		}

		// echo $data['str']['mode'];


		$this->load->view('prod/detail_pprodcur', $data);
	}

	public function update_pprodcur()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['PPRAW_INPUT'] = 	$this->input->post("PPRAW_INPUT");
		$data['PPLICL_INPUT'] = $this->input->post("PPLICL_INPUT");
		$data['PPLICL_AFTER_INPUT'] = $this->input->post("PPLICL_AFTER_INPUT");
		$data['PPNA2CO3_INPUT'] = $this->input->post("PPNA2CO3_INPUT");
		$data['PPH2O_INPUT'] = $this->input->post("PPH2O_INPUT");
		$data['PPNACL_AFTER_INPUT'] = $this->input->post("PPNACL_AFTER_INPUT");
		$data['PPLI2CO3_INPUT'] = $this->input->post("PPLI2CO3_INPUT");
		$data['PPNACL_INPUT'] = $this->input->post("PPNACL_INPUT");
		$data['PPLI2CO3_AFTER_INPUT'] = $this->input->post("PPLI2CO3_AFTER_INPUT");

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['PPRAW_INPUT'] = $data['PPRAW_INPUT'];
		$params['PPLICL_INPUT'] = $data['PPLICL_INPUT'];
		$params['PPLICL_AFTER_INPUT'] = $data['PPLICL_AFTER_INPUT'];
		$params['PPNA2CO3_INPUT'] = $data['PPNA2CO3_INPUT'];
		$params['PPH2O_INPUT'] = $data['PPH2O_INPUT'];
		$params['PPNACL_AFTER_INPUT'] = $data['PPNACL_AFTER_INPUT'];
		$params['PPLI2CO3_INPUT'] = $data['PPLI2CO3_INPUT'];
		$params['PPNACL_INPUT'] = $data['PPNACL_INPUT'];
		$params['PPLI2CO3_AFTER_INPUT'] = $data['PPLI2CO3_AFTER_INPUT'];


		$data['result'] = $this->prod_model->update_pprodcur($params);
		echo $data['result'];
	}

	public function del_pprodcur()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];

		$data['result'] = $this->prod_model->del_pprodcur($params);
		echo $data['result'];
	}

	// 공정별 수율정보
	public function pharvest()
	{
		$data['title'] = '공정별 수율';
		return $this->load->view('main50', $data);
	}
	public function head_pharvest()
	{
		$data['str']['date'] = $this->input->post('date');
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = (isset($data['str']['biz'])) ? $data['str']['biz'] : '';
		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());
		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->prod_model->head_pharvest($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_pharvest_cut($params);
		$data['BIZ']=$this->sys_model->biz_list();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('prod/head_pharvest', $data);
	}

	public function detail_pharvest()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if (!empty($data['idx']) && !empty($data['hidx'])) {
			$data['info'] = $this->prod_model->detail_pharvest($params);

			if ($data['info']->PHINPUT_YN == "Y")
				$data['str']['mode'] = "mod";
			else
				$data['str']['mode'] = "new";
		}

		// echo $data['str']['mode'];
		$this->load->view('prod/detail_pharvest', $data);
	}

	public function update_pharvest()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['PHRAW_INPUT'] = 	$this->input->post('PHRAW_INPUT');
		$data['PHLICL_AFTER_INPUT'] = $this->input->post('PHLICL_AFTER_INPUT');
		$data['PHNA2CO3_INPUT'] = $this->input->post('PHNA2CO3_INPUT');
		$data['PHH2O_INPUT'] = $this->input->post('PHH2O_INPUT');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['PHRAW_INPUT'] = $data['PHRAW_INPUT'];
		$params['PHLICL_AFTER_INPUT'] = $data['PHLICL_AFTER_INPUT'];
		$params['PHNA2CO3_INPUT'] = $data['PHNA2CO3_INPUT'];
		$params['PHH2O_INPUT'] = $data['PHH2O_INPUT'];

		$data['result'] = $this->prod_model->update_pharvest($params);
		echo $data['result'];
	}

	public function del_pharvest()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];

		$data['result'] = $this->prod_model->del_pharvest($params);
		echo $data['result'];
	}

	// 기간별 생산실적
	public function dprodperf()
	{
		$data['title'] = '기간별 생산실적';
		return $this->load->view('main100', $data);
	}
	public function ajax_dprodperf()
	{
		//모델
		$data['list'] = $this->prod_model->ajax_dprodperf();

		//뷰
		$this->load->view('prod/ajax_dprodperf', $data);
	}

	// 생산 모니터링
	public function prodmonitor()
	{
		$data['title'] = '생산 모니터링';
		return $this->load->view('main100', $data);
	}
	public function ajax_prodmonitor()
	{
		//모델
		// $data['list'] = $this->prod_model->ajax_prodmonitor();
		
		// foreach($data['list'] as $i)
		// {
			
		// }
		// $data['level']=0.3;
		// echo var_dump($data['list'][0]);
		// echo var_dump($data['list']);
		//뷰
		$this->load->view('prod/ajax_prodmonitor3');
	}

	// 생산 모니터링 - 태블릿
	public function prodmonitor2()
	{
		$data['title'] = '생산 모니터링';
		return $this->load->view('main100', $data);
	}
	public function ajax_prodmonitor2()
	{
		//모델
		$data['list'] = $this->prod_model->ajax_prodmonitor2();

		//뷰
		$this->load->view('prod/ajax_prodmonitor2', $data);
	}

	public function save_monitor_setting()
	{
		$json = $this->input->post('json');
		$this->db->query("INSERT INTO T_JSON(JSON) VALUES('{$json}')");
		$res = $this->db->affected_rows();
		echo $res;
	}
	public function load_monitor_setting()
	{
		$data['list'] = $this->prod_model->ajax_prodmonitor();
		$res = $this->db->query("SELECT JSON FROM T_JSON ORDER BY IDX DESC LIMIT 1");
		$json= json_decode($res->row()->JSON);
		// echo var_dump($data['list']);


		$batch_info = $this->prod_model->get_batch_recent();
		// echo var_dump($batch_info);

		if(isset($batch_info) && $batch_info->STATUS == "STOP")
		{
			$json->animation[0]="";
		}
		else if(isset($batch_info) && $batch_info->STATUS == "WATER")
		{
			$json->animation[0]="W1T1";
			$json->animation[1]="W2T1";
		}
		else if(isset($batch_info) && $batch_info->STATUS == "TOMIX")
		{
			$json->animation[0]="T1T3";
			$json->animation[1]="M1T3";
			$json->animation[2]="M2T3";
		}
		else if(isset($batch_info) && $batch_info->STATUS == "TOWASH")
		{
			$json->animation[0]="T3T2";
		}
		else if(isset($batch_info) && $batch_info->STATUS == "TOLI2CO3")
		{
			$json->animation[0]="T2B1";
		}
		
		//수위 계산 0~1
		//T1,2,3
		$json->nodeDataArray[0]->level = round($data['list'][2]->LEVEL/100,2);
		$json->nodeDataArray[1]->level = round($data['list'][3]->LEVEL/100,2);
		$json->nodeDataArray[2]->level = round($data['list'][4]->LEVEL/100,2);

		//온수탱크
		$json->nodeDataArray[3]->level = round($data['list'][0]->LEVEL/100,2);
		$json->nodeDataArray[4]->level = round($data['list'][1]->LEVEL/100,2);

		//배합탱크
		$json->nodeDataArray[5]->level = round($data['list'][5]->LEVEL/100,2);
		$json->nodeDataArray[6]->level = round($data['list'][6]->LEVEL/100,2);
		
		//온수 탱크
		$json->nodeDataArray[8]->text1 = $data['list'][0]->LEVEL;
		$json->nodeDataArray[8]->text2 = $data['list'][0]->TEMP;
		$json->nodeDataArray[9]->text1 = $data['list'][1]->LEVEL;
		$json->nodeDataArray[9]->text2 = $data['list'][1]->TEMP;

		//주 탱크
		//T1
		$json->nodeDataArray[10]->text1 = $data['list'][2]->PH;
		$json->nodeDataArray[10]->text2 = $data['list'][2]->CL;
		$json->nodeDataArray[10]->text3 = $data['list'][2]->TEMP;
		$json->nodeDataArray[10]->text4 = $data['list'][2]->PRESS;
		$json->nodeDataArray[10]->text5 = $data['list'][2]->LEVEL;
		//T2
		$json->nodeDataArray[11]->text1 = $data['list'][3]->PH;
		$json->nodeDataArray[11]->text2 = $data['list'][3]->CL;
		$json->nodeDataArray[11]->text3 = $data['list'][3]->TEMP;
		$json->nodeDataArray[11]->text4 = $data['list'][3]->PRESS;
		$json->nodeDataArray[11]->text5 = $data['list'][3]->LEVEL;
		//T3
		$json->nodeDataArray[12]->text1 = $data['list'][4]->PH;
		$json->nodeDataArray[12]->text2 = $data['list'][4]->CL;
		$json->nodeDataArray[12]->text3 = $data['list'][4]->TEMP;
		$json->nodeDataArray[12]->text4 = $data['list'][4]->PRESS;
		$json->nodeDataArray[12]->text5 = $data['list'][4]->LEVEL;

		//Licl탱크
		//배합탱크1
		$json->nodeDataArray[13]->text1 = $data['list'][5]->PH;
		$json->nodeDataArray[13]->text2 = $data['list'][5]->CL;
		$json->nodeDataArray[13]->text3 = $data['list'][5]->TEMP;
		$json->nodeDataArray[13]->text4 = $data['list'][5]->PRESS;
		$json->nodeDataArray[13]->text5 = $data['list'][5]->LEVEL;

		//배합탱크2
		$json->nodeDataArray[14]->text1 = $data['list'][6]->PH;
		$json->nodeDataArray[14]->text2 = $data['list'][6]->CL;
		$json->nodeDataArray[14]->text3 = $data['list'][6]->TEMP;
		$json->nodeDataArray[14]->text4 = $data['list'][6]->PRESS;
		$json->nodeDataArray[14]->text5 = $data['list'][6]->LEVEL;
		
		// echo var_dump($json);

		echo json_encode($json);
	}

	public function batch()
	{

		$data['title'] = '배치시작';
		return $this->load->view('main100', $data);
	}

	public function ajax_batch()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		//모델
		$data['list'] = $this->prod_model->ajax_batch();
		$this->load->view('prod/ajax_batch',$data);

	}

	public function batch_start()
	{
		$result = $this->prod_model->batch_start();
		echo $result;

	}

	public function batch_end()
	{
		$idx = $this->input->get("idx");
		$num = $this->prod_model->batch_end($idx);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
}
