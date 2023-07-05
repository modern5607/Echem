<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EQP extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		$this->data['userLevel'] = $this->session->userdata('user_level');

		$this->load->model(array('sys_model', 'eqp_model', 'mdm_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');
		$this->data['menuLevel'] = $this->sys_model->menu_level();
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
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

	//생산장비 등록
	public function eqpins()
	{
		$data['title'] = "생산장비 등록";

		return $this->load->view('main50', $data);
	}

	//생산장비 등록/ 왼쪽
	public function head_eqpins()
	{
		$data['str'] = array();
		$data['str']['eqgb'] = $this->input->post('eqgb');
		$data['str']['eqname'] = $this->input->post('eqname');

		$params['EQGB'] = '';
		$params['EQNAME'] = '';
		if (!empty($data['str']['eqgb']))
			$params['EQGB'] = $data['str']['eqgb'];

		if (!empty($data['str']['eqname']))
			$params['EQNAME'] = $data['str']['eqname'];

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


		$data['EQGB']   = $this->mdm_model->get_selectInfo("tch.CODE", "EQGB");
		// echo var_dump($data['EQGB']);

		$data['List'] = $this->eqp_model->eqp_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->eqp_model->eqp_list_cut($params);
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		return $this->load->view('/eqp/head_eqpins', $data);
	}

	//생산장비 등록 / 오른쪽
	public function detail_eqpins()
	{
		$data['str'] = array();
		$data['str']['idx'] = $this->input->post('idx');
		$data['str']['mode'] = empty($this->input->post('mode')) ? "new" : $this->input->post('mode');


		if ($data['str']['mode'] == 'modify')
			$data['mode_text'] = "수정";
		else if ($data['str']['mode'] == 'new')
			$data['mode_text'] = "신규등록";


		$data['mode'] = $data['str']['mode'];
		$data['idx'] = $data['str']['idx'];

		$params['IDX'] = '';
		if (!empty($data['str']['idx']))
			$params['IDX'] = $data['str']['idx'];

		$data['List'] = $this->eqp_model->detail_eqpins_list($params);
		$data['EQGB']   = $this->mdm_model->get_selectInfo("tch.CODE", "EQGB");
		// echo var_dump($data['List']);

		return $this->load->view('/eqp/detail_eqpins', $data);
	}

	//생산장비 등록 / 등록
	public function eqpins_ins()
	{
		$data['str'] = array();
		$data['mode'] 				= $this->input->post('mode');

		$data['str']['idx'] 		= $this->input->post('idx');
		$data['str']['mngnum'] 		= $this->input->post('mngnum');
		$data['str']['name'] 		= $this->input->post('name');
		$data['str']['eqpgb'] 		= $this->input->post('eqpgb');
		$data['str']['standard'] 	= $this->input->post('standard');
		$data['str']['idennum'] 	= $this->input->post('idennum');
		$data['str']['buydate'] 	= $this->input->post('buydate');
		$data['str']['eqpstatus'] 	= $this->input->post('eqpstatus');
		$data['str']['assetgb'] 	= $this->input->post('assetgb');
		$data['str']['make'] 		= $this->input->post('make');
		$data['str']['corcycle'] 	= $this->input->post('corcycle');
		// $data['str']['cordate'] 	= $this->input->post('cordate');
		// $data['str']['nextcordate'] = $this->input->post('nextcordate');
		$data['str']['remark'] 		= $this->input->post('remark');

		$params['IDX'] 				 = $data['str']['idx'];
		$params['MNGNUM']			 = $data['str']['mngnum'];
		$params['NAME']			 	 = $data['str']['name'];
		$params['EQPGB']			 = $data['str']['eqpgb'];
		$params['STANDARD']			 = $data['str']['standard'];
		$params['IDENNUM']			 = $data['str']['idennum'];
		$params['BUYDATE']			 = $data['str']['buydate'];
		$params['EQPSTATUS']		 = $data['str']['eqpstatus'];
		$params['ASSETGB']			 = $data['str']['assetgb'];
		$params['MAKE']			 	 = $data['str']['make'];
		$params['CORCYCLE']			 = $data['str']['corcycle'];
		// $params['CORDATE']			 = $data['str']['cordate'];	
		// $params['NEXTCORDATE']		 = $data['str']['nextcordate'];
		$params['REMARK']			 = $data['str']['remark'];

		if ($data['mode'] == 'new')
			$data['result'] = $this->eqp_model->eqp_list($params);
		else if ($data['mode'] == 'modify')
			$data['result'] = $this->eqp_model->eqpins_up($params);

		echo $data['result'];
	}

	//검교정관리
	public function eqpma()
	{
		$data['title'] = "검교정 관리";

		return $this->load->view('main50', $data);
	}

	//검교정관리 왼쪽
	public function head_eqpma()
	{
		$data['str'] = array();
		$data['str']['eqgb'] = $this->input->post('eqgb');
		$data['str']['eqname'] = $this->input->post('eqname');

		$params['EQGB'] = '';
		$params['EQNAME'] = '';
		if (!empty($data['str']['eqgb']))
			$params['EQGB'] = $data['str']['eqgb'];

		if (!empty($data['str']['eqname']))
			$params['EQNAME'] = $data['str']['eqname'];

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


		$data['EQGB']   = $this->mdm_model->get_selectInfo("tch.CODE", "EQGB");

		$data['List'] = $this->eqp_model->eqp_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->eqp_model->eqp_list_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();
		return $this->load->view('/eqp/head_eqpma', $data);
	}

	//검교정관리 오른쪽
	public function detail_eqpma()
	{
		$data['str'] = array();
		$data['str']['idx'] = $this->input->post('idx');
		$data['str']['mode'] = empty($this->input->post('mode')) ? "new" : $this->input->post('mode');


		if ($data['str']['mode'] == 'modify')
			$data['mode_text'] = "수정";
		else if ($data['str']['mode'] == 'new')
			$data['mode_text'] = "신규등록";


		$data['mode'] = $data['str']['mode'];
		$data['idx'] = $data['str']['idx'];

		$params['IDX'] = '';
		if (!empty($data['str']['idx']))
			$params['IDX'] = $data['str']['idx'];

		$data['List'] = $this->eqp_model->detail_eqpins_list($params);
		$data['member']   = $this->mdm_model->member_list2();
		// echo var_dump($data['member']);
		$data['userName'] = $this->session->userdata('user_name');
		// echo var_dump($data['List']);

		return $this->load->view('/eqp/detail_eqpma', $data);
	}

	//검교정관리 등록
	public function eqpma_ins()
	{
		$data['str'] = array();
		// $data['mode'] 				 = $this->input->post('mode');

		$data['str']['idx'] 		 = $this->input->post('idx');
		$data['str']['cordate'] 	 = $this->input->post('cordate');
		$data['str']['nextcordate']	 = $this->input->post('nextcordate');
		$data['str']['writer'] 		 = $this->input->post('writer');
		$data['str']['reviewer'] 	 = $this->input->post('reviewer');
		$data['str']['approver'] 	 = $this->input->post('approver');
		$data['str']['remark'] 		 = $this->input->post('remark');

		$params['IDX'] 				 = $data['str']['idx'];
		$params['CORDATE']			 = $data['str']['cordate'];
		$params['NEXTCORDATE']		 = $data['str']['nextcordate'];
		$params['WRITER']			 = $data['str']['writer'];
		$params['REVIEWER']			 = $data['str']['reviewer'];
		$params['APPROVER']			 = $data['str']['approver'];
		$params['REMARK']			 = $data['str']['remark'];


		$data['result'] = $this->eqp_model->eqpma_ins($params);

		echo $data['result'];
	}


	//검교정 이력현황
	public function eqphis()
	{
		$data['title'] = "검교정 이력현황";

		return $this->load->view('main50', $data);
	}

	//검교정 이력현황 왼쪽
	public function head_eqphis()
	{
		$data['str'] = array();
		$data['str']['eqgb'] = $this->input->post('eqgb');
		$data['str']['eqname'] = $this->input->post('eqname');


		$params['EQGB'] = '';
		$params['EQNAME'] = '';
		if (!empty($data['str']['eqgb']))
			$params['EQGB'] = $data['str']['eqgb'];

		if (!empty($data['str']['eqname']))
			$params['EQNAME'] = $data['str']['eqname'];

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

		$data['EQGB']   = $this->mdm_model->get_selectInfo("tch.CODE", "EQGB");
		// echo var_dump($data['EQGB']);

		$data['List'] = $this->eqp_model->eqp_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->eqp_model->eqp_list_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		return $this->load->view('/eqp/head_eqphis', $data);
	}

	//검교정 이력현황 오른쪽
	public function detail_eqphis()
	{
		$data['str'] = array();
		$data['str']['idx'] = $this->input->post('idx');
		$data['str']['MNGNUM'] = $this->input->post('mngnum');
		$data['str']['NAME'] = $this->input->post('name');
		// echo var_dump($data['str']);

		$data['idx'] = $data['str']['idx'];

		$params['IDX'] = '';
		if (!empty($data['str']['idx']))
			$params['IDX'] = $data['str']['idx'];

		$data['List'] = $this->eqp_model->detail_eqphis_list($params);
		// echo var_dump($data['List']);

		return $this->load->view('/eqp/detail_eqphis', $data);
	}

	// public function eqpled()
	// {
	// 	$data['title'] = "검교정 관리대장";
	// 	return $this->load->view('main50', $data);
	// }
	// public function head_eqpled()
	// {
	// 	$data['str'] = array();
	// 	$data['str']['eqgb'] = $this->input->post('eqgb');
	// 	$data['str']['eqname'] = $this->input->post('eqname');


	// 	$params['EQGB'] = '';
	// 	$params['EQNAME'] = '';
	// 	if (!empty($data['str']['eqgb']))
	// 		$params['EQGB'] = $data['str']['eqgb'];

	// 	if (!empty($data['str']['eqname']))
	// 		$params['EQNAME'] = $data['str']['eqname'];

	// 	$data['EQGB']   = $this->mdm_model->get_selectInfo("tch.CODE","EQGB");
	// 	$data['List'] = $this->eqp_model->eqp_list($params);

	// 	return $this->load->view('/eqp/head_eqpled', $data);
	// }
	// public function detail_eqpled()
	// {
	// 	$data['str'] = array();
	// 	$data['str']['idx'] = $this->input->post('idx');

	// 	$params['IDX'] = '';
	// 	if (!empty($data['str']['idx']))
	// 		$params['IDX'] = $data['str']['idx'];

	// 	$data['List'] = $this->eqp_model->eqpled_list($params);


	// 	return $this->load->view('/eqp/detail_eqpled', $data);

	// }

}
