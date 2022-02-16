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
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;
		//=====================================

		//모델
		$data['list'] = $this->prod_model->head_workorder($params);
		$this->data['cut'] = 0; //$this->prod_model->ajax_workorder();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
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

		if (!empty($params['IDX'])&&!empty($params['ACT_IDX']))
		{
			$data['str']['mode'] = 'mod';
			$data['info'] = $this->prod_model->detail_workorder($params);
		}
		else if(empty($params['IDX'])&& !empty($params['ACT_IDX']))
		{
			$data['str']['mode'] = 'new';
			$data['info'] = $this->prod_model->detail_workorder($params);
		}
		
		// echo $data['str']['mode'];
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
		// $data['ACT_NAME'] =		 $this->input->post('ACT_NAME');
		// $data['DEL_DATE'] =		 $this->input->post('DEL_DATE');
		// $data['QTY'] =			 $this->input->post('QTY');
		$data['START_DATE'] =	 $this->input->post('START_DATE');
		$data['END_DATE'] =		 $this->input->post('END_DATE');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX']; 
		$params['HIDX'] = $data['HIDX']; 
		$params['ORDER_DATE'] = $data['ORDER_DATE']; 
		// $params['ACT_NAME'] = $data['ACT_NAME']; 
		// $params['DEL_DATE'] = $data['DEL_DATE']; 
		// $params['QTY'] = $data['QTY']; 
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
		// $data['ORDER_DATE'] =	 $this->input->post('ORDER_DATE');
		// $data['ACT_NAME'] =		 $this->input->post('ACT_NAME');
		// $data['DEL_DATE'] =		 $this->input->post('DEL_DATE');
		// $data['QTY'] =			 $this->input->post('QTY');
		$data['START_DATE'] =	 $this->input->post('START_DATE');
		$data['END_DATE'] =		 $this->input->post('END_DATE');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX']; 
		$params['HIDX'] = $data['HIDX']; 
		// $params['ORDER_DATE'] = $data['ORDER_DATE']; 
		// $params['ACT_NAME'] = $data['ACT_NAME']; 
		// $params['DEL_DATE'] = $data['DEL_DATE']; 
		// $params['QTY'] = $data['QTY']; 
		$params['START_DATE'] = $data['START_DATE']; 
		$params['END_DATE'] = $data['END_DATE']; 
		$params['REMARK'] = $data['REMARK']; 
		$params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->update_workorder($params);
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
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;
		//=====================================

		//모델
		$data['list'] = $this->prod_model->head_pworkorder($params);
		$this->data['cut'] = 0; //$this->prod_model->ajax_workorder();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('prod/head_pworkorder',$data);
	}



	public function detail_pworkorder()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if(!empty($data['idx'])&&!empty($data['hidx']))
		{
			$data['info'] = $this->prod_model->detail_pworkorder($params);

			if($data['info']->EACHORDER == "Y")
				$data['str']['mode']="mod";
			else
				$data['str']['mode']="new";
		}
		
		// echo $data['str']['mode'];
		$this->load->view('prod/detail_pworkorder', $data);
	}

	public function update_pworkorder()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['RAW_DATE'] =		 $this->input->post('RAW_DATE');
		$data['NA2CO3_DATE'] =		 $this->input->post('NA2CO3_DATE');
		$data['MIX_DATE'] =		 $this->input->post('MIX_DATE');
		$data['WASH_DATE'] =	 $this->input->post('WASH_DATE');
		$data['DRY_DATE'] =		 $this->input->post('DRY_DATE');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX']; 
		$params['HIDX'] = $data['HIDX']; 
		$params['RAW_DATE'] =$data['RAW_DATE'];
		$params['NA2CO3_DATE'] =$data['NA2CO3_DATE'];
		$params['MIX_DATE'] =$data['MIX_DATE'];
		$params['WASH_DATE'] =$data['WASH_DATE'];
		$params['DRY_DATE'] =$data['DRY_DATE'];
		$params['REMARK'] = $data['REMARK']; 
		$params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->update_pworkorder($params);
		echo $data['result'];
	}


	// public function act_idx()
	// {
	// 	$idx = $this->input->post("idx");
	// 	$data['info'] = $this->prod_model->act_idx($idx);
	// 	$data['status'] = "ok";
	// 	// echo var_dump($data);
	// 	echo json_encode($data);
	// }

	// public function add_order()
	// {
	// 	$data['ACT_IDX'] = $this->input->post("ACT");
	// 	$data['ORDER_DATE'] = $this->input->post("ORDER_DATE");
	// 	$data['COMPONENT_IDX'] = $this->input->post("COMPONENT");
	// 	$data['ORDER_QTY'] = $this->input->post("ORDER_QTY");

	// 	$params['ACT_IDX'] = 		$data['ACT_IDX'];
	// 	$params['ORDER_DATE'] = 	$data['ORDER_DATE'];
	// 	$params['COMPONENT_IDX'] = 	$data['COMPONENT_IDX'];
	// 	$params['ORDER_QTY'] = 		$data['ORDER_QTY'];
	// 	$params['INSERT_ID'] = 		$this->session->userdata('user_id');


	// 	echo var_dump($data);
	// 	$data['result'] = $this->prod_model->add_order($params);
	// }


	// 원재료 투입 입력
	public function matinput()
	{
		$data['title'] = '원재료 투입 입력';
		return $this->load->view('main50', $data);
	}
	public function head_matinput()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;
		//=====================================

		$data['list'] = $this->prod_model->head_matinput($params);
		$this->data['cut'] = 0; //$this->prod_model->ajax_workorder();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
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

		if(!empty($data['idx'])&&!empty($data['hidx']))
		{
			$data['info'] = $this->prod_model->detail_matinput($params);

			if($data['info']->EACHORDER == "Y")
				$data['str']['mode']="mod";
			else
				$data['str']['mode']="new";
		}
		
		// echo $data['str']['mode'];


		$this->load->view('prod/detail_matinput', $data);
	}

	public function update_matinput()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['RAW'] =		 $this->input->post('RAW');
		$data['NA2CO3'] =		 $this->input->post('NA2CO3');
		$data['LICL'] =		 $this->input->post('LICL');
		$data['NACL'] =	 $this->input->post('NACL');
		$data['REMARK'] =		 $this->input->post('REMARK');

		$params['IDX'] = $data['IDX']; 
		$params['HIDX'] = $data['HIDX']; 
		$params['RAW'] =$data['RAW'];
		$params['NA2CO3'] =$data['NA2CO3'];
		$params['LICL'] =$data['LICL'];
		$params['NACL'] =$data['NACL'];
		$params['REMARK'] = $data['REMARK']; 
		$params['INSERT_ID'] = $this->session->userdata('user_id');

		$data['result'] = $this->prod_model->update_matinput($params);
		echo $data['result'];
	}

	// 공정별 수율정보
	public function pharvest()
	{
		$data['title'] = '공정별 수율정보';
		return $this->load->view('main50', $data);
	}
	public function head_pharvest()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;
		//=====================================

		$data['list'] = $this->prod_model->head_pharvest($params);
		$this->data['cut'] = 0; //$this->prod_model->ajax_workorder();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
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

		if(!empty($data['idx'])&&!empty($data['hidx']))
		{
			$data['info'] = $this->prod_model->detail_pharvest($params);

			if($data['info']->EACHORDER == "Y")
				$data['str']['mode']="mod";
			else
				$data['str']['mode']="new";
		}
		
		$this->load->view('prod/detail_pharvest', $data);
	}

	// 공정별 생산내역
	public function pprodcur()
	{
		$data['title'] = '공정별 생산내역';
		return $this->load->view('main50', $data);
	}
	public function head_pprodcur()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;
		//=====================================

		$data['list'] = $this->prod_model->head_pprodcur($params);
		$this->data['cut'] = 0; //$this->prod_model->ajax_workorder();

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
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

		if(!empty($data['idx'])&&!empty($data['hidx']))
		{
			$data['info'] = $this->prod_model->detail_matinput($params);

			if($data['info']->EACHORDER == "Y")
				$data['str']['mode']="mod";
			else
				$data['str']['mode']="new";
		}
		
		// echo $data['str']['mode'];


		$this->load->view('prod/detail_pprodcur', $data);
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
}
