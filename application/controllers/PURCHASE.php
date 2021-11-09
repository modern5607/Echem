<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PURCHASE extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);

		$this->load->helper('test');
		$this->load->model(array('mif_model', 'sys_model','pur_model'));

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

	// 원자재 발주등록
	public function matorder()
	{
		$data['title']='원자재 발주등록';
		return $this->load->view('main50', $data);
	}

	public function head_matorder()
	{
		//모델
		$data['list']=$this->pur_model->head_matorder();

		//뷰
		$this->load->view('purchase/head_matorder', $data);
	}
	public function detail_matorder()
	{
		//모델
		$data['list']='';//$this->pur_model->detail_matorder();

		//뷰
		$this->load->view('purchase/detail_matorder', $data);
	}


	// 	입고등록
	public function enter()
	{
		$data['title']='입고등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_enter()
	{
		//모델
		$data['list']=$this->pur_model->ajax_enter();

		//뷰
		$this->load->view('purchase/ajax_enter', $data);
	}

	// 발주대비 입고현황
	public function orderenter()
	{
		$data['title']='발주대비 입고현황';
		return $this->load->view('main100', $data);
	}

	public function ajax_orderenter()
	{
		//모델
		$data['list']=$this->pur_model->ajax_orderenter();

		//뷰
		$this->load->view('purchase/ajax_orderenter', $data);
	}

	// 기간별 발주현황
	public function denter()
	{
		$data['title']='기간별 발주현황';
		return $this->load->view('main100', $data);
	}
	public function ajax_denter()
	{
		//모델
		$data['list']=$this->pur_model->ajax_denter();

		//뷰
		$this->load->view('purchase/ajax_denter', $data);
	}
	
}
