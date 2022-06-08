<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QUAL extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->helper('test');
		$this->load->model(array('mif_model', 'sys_model', 'qual_model'));

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

	// 품질검사 등록
	public function qexam()
	{
		$data['title'] = '품질검사 등록';
		return $this->load->view('main50', $data);
	}
	public function head_qexam()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

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

		$data['list'] = $this->qual_model->head_qexam($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->qual_model->ajax_qexam_cut($params);

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
		$this->load->view('qual/head_qexam', $data);
	}

	public function detail_qexam()
	{
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['str']['mode'] = 'empty';

		if (!empty($data['idx']) && !empty($data['hidx'])) {
			$data['info'] = $this->qual_model->detail_qexam($params);

			// echo var_dump($data['info']);

			if ($data['info']->QEXAM_YN == "Y")
				$data['str']['mode'] = "mod";
			else
				$data['str']['mode'] = "new";
		}

		// echo $data['str']['mode'];
		$this->load->view('prod/detail_qexam', $data);
	}

	public function update_qexam()
	{
		$data['IDX'] =			 $this->input->post('idx');
		$data['HIDX'] =			 $this->input->post('hidx');
		$data['DEFECT_YN'] = 	$this->input->post('DEFECT_YN');
		$data['DEFECT_REMARK'] = $this->input->post('DEFECT_REMARK');
		$data['DEFECT_QTY'] = $this->input->post('DEFECT_QTY');

		$params['IDX'] = $data['IDX'];
		$params['HIDX'] = $data['HIDX'];
		$params['DEFECT_YN'] = $data['DEFECT_YN'];
		$params['DEFECT_REMARK'] = $data['DEFECT_REMARK'];
		$params['DEFECT_QTY'] = $data['DEFECT_QTY'];

		$data['result'] = $this->qual_model->update_qexam($params);
		echo $data['result'];
	}


	// 실적대비 불량률
	public function perfpoor()
	{
		$data['title'] = '실적대비 불량률';
		return $this->load->view('main100', $data);
	}
	public function ajax_perfpoor()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

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

		$data['list'] = $this->qual_model->ajax_perfpoor($params, $pageNum, $config['per_page']);
		// echo var_dump($data['list']);
		$this->data['cnt'] = $this->qual_model->ajax_perfpoor_cut($params);

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
		$this->load->view('qual/ajax_perfpoor', $data);
	}


	// 품질이력
	public function qualitycur()
	{
		$data['title'] = '품질이력';
		return $this->load->view('main100', $data);
	}
	public function ajax_qualitycur()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

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

		$data['list'] = $this->qual_model->ajax_qualitycur($params, $pageNum, $config['per_page']);
		// echo var_dump($data['list']);
		$this->data['cnt'] = $this->qual_model->ajax_qualitycur_cut($params);

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
		$this->load->view('qual/ajax_qualitycur', $data);
	}


	// 불량분석
	public function pooranal()
	{
		$data['title'] = '불량분석 - 사유등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_pooranal()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");

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

		$data['list'] = $this->qual_model->ajax_pooranal($params, $pageNum, $config['per_page']);
		// echo var_dump($data['list']);
		$this->data['cnt'] = $this->qual_model->ajax_pooranal_cut($params);

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
		$this->load->view('qual/ajax_pooranal', $data);
	}

	public function ajax_pooranal_form()
	{
		$data['title'] = "불량 사유 및 REMARK 등록";
		$data['idx'] = $this->input->post("idx");
		$data['hidx'] = $this->input->post("hidx");

		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['ACT_IDX'] = isset($data['hidx']) ? $data['hidx'] : "";

		$data['info'] = $this->qual_model->ajax_pooranal_form($params);
		return $this->load->view('qual/ajax_pooranal_form', $data);
	}

	public function update_pooranal_form()
	{
		$data['hidx'] = $this->input->post("hidx");
		$data['idx'] = $this->input->post("idx");
		$data['remark'] = $this->input->post("DEFECT_REMARK");

		$params['HIDX'] = isset($data['hidx']) ? $data['hidx'] : "";
		$params['IDX'] = isset($data['idx']) ? $data['idx'] : "";
		$params['DEFECT_REMARK'] = isset($data['remark']) ? $data['remark'] : "";

		$data['result'] = $this->qual_model->update_pooranal_form($params);
		echo $data['result'];
	}
}
