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
		$this->load->model(array('mif_model', 'sys_model','ordpln_model'));

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
		return $this->load->view('main100', $data);
	}
	public function ajax_order()
	{
		//모델
		$data['list']=$this->ordpln_model->ajax_order();

		//뷰
		$this->load->view('mdm/ajax_order', $data);
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
		$this->load->view('mdm/ajax_ordercur', $data);
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
		$this->load->view('mdm/ajax_orderprocess', $data);
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
		$this->load->view('mdm/ajax_prodplncur', $data);
	}
}
