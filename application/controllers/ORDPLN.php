<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ORDPLN extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->helper('test');
		$this->load->model(array('mif_model', 'sys_model', 'ordpln_model'));

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

	// 	주문등록
	public function order()
	{
		$data['title']='주문등록';
		return $this->load->view('main30', $data);
	}
	public function ajax_order()
	{
		//모델
		$data['list']=$this->ordpln_model->ordpln_dual();

		//뷰
		$this->load->view('ordpln/ajax_order', $data);
	}
	public function head_order()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자

		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->ordpln_model->ordpln_dual($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->ordpln_model->ordpln_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/ordpln/head_order', $data);
	}
	public function detail_order()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자

		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->ordpln_model->ordpln_dual($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->ordpln_model->ordpln_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/ordpln/detail_order', $data);
	}


	// 주문현황
	public function ordercur()
	{
		$data['title']='주문현황';
		return $this->load->view('main100', $data);
	}
	public function ajax_ordercur()
	{
		//모델
		$data['list']=$this->ordpln_model->ajax_ordercur();

		//뷰
		$this->load->view('ordpln/ajax_ordercur', $data);
	}
	// 주문대비 진행현황
	public function orderprocess()
	{
		$data['title']='주문대비 진행현황';
		return $this->load->view('main100', $data);
	}
	public function ajax_orderprocess()
	{
		//모델
		$data['list']=$this->ordpln_model->ajax_orderprocess();

		//뷰
		$this->load->view('ordpln/ajax_orderprocess', $data);
	}



	// 생산계획 등록
	public function prodpln()
	{
		$data['title']='생산계획 등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_prodpln()
	{
		//모델
		$data['list']=$this->ordpln_model->ajax_prodpln();

		//뷰
		$this->load->view('mdm/ajax_prodpln', $data);
	}



	// 생산계획 조회
	public function prodplncur()
	{
		$data['title']='생산계획 조회';
		return $this->load->view('main100', $data);
	}
	public function ajax_prodplncur()
	{
		//모델
		$data['list']=$this->ordpln_model->ajax_prodplncur();

		//뷰
		$this->load->view('ordpln/ajax_prodplncur', $data);
	}
}
