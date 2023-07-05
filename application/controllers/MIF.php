<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MIF extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->helper('test');
		$this->load->model(array('mif_model', 'sys_model'));

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

	//공지사항내역
	public function notice()
	{
		$data['title'] = "공지사항내역";
		return $this->load->view('main100', $data);
	}

	//공지사항내역 표시
	public function ajax_notice()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['title'] = trim((string)$this->input->post('title'));
		$data['str']['cont'] = trim((string)$this->input->post('cont'));
		$data['str']['enddate'] = trim((string)$this->input->post('enddate'));

		$params['TITLE'] = "";
		$params['CONTENT'] = "";
		$params['END_DATE'] = "";


		if (!empty($data['str']['title']))
			$params['TITLE'] = $data['str']['title'];

		if (!empty($data['str']['cont']))
			$params['CONTENT'] = $data['str']['cont'];

		if (!empty($data['str']['enddate']))
			$params['END_DATE'] = $data['str']['enddate'];

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


		$data['List'] = $this->mif_model->ajax_notice_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->mif_model->ajax_notice_cut($params);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		return $this->load->view('mif/ajax_mif', $data);
	}

	public function notice_form()
	{
		$data['title'] = "공지사항 등록";
		return $this->load->view('mif/mif_form', $data);
	}
	public function notice_ins()
	{
		$params['TITLE'] = $this->input->post('title_form');
		$params['CONTENT'] = $this->input->post('cont_form');
		$params['END_DATE'] = $this->input->post('enddate_form');
		$params['INSERT_ID'] = $this->session->userdata('user_name');


		$this->mif_model->notice_ins($params);	//echo $query하면서 ajax성공됨
	}
}
