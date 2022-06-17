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
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

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
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

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
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->prod_model->head_matinput($params,$pageNum,$config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_matinput_cut($params);

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
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->prod_model->head_pprodcur($params,$pageNum,$config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_pprodcur_cut($params);

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
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['ACT_NAME'] = (isset($data['str']['actnm'])) ? $data['str']['actnm'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->prod_model->head_pharvest($params,$pageNum,$config['per_page']);
		$this->data['cnt'] = $this->prod_model->head_pharvest_cut($params);

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
		$data['list'] = $this->prod_model->ajax_prodmonitor();

		//뷰
		$this->load->view('prod/ajax_prodmonitor', $data);
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
}
