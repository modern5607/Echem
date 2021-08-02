<?php
defined('BASEPATH') or exit('No direct script access allowed');

class STOCK extends CI_Controller
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


	// 포장등록
	public function package()
	{
		$data['title']='포장등록';
		return $this->load->view('main100', $data);
	}
	// 재고내역(포장+탱크)
	public function stockcur()
	{
		$data['title']='재고내역(포장+탱크)';
		return $this->load->view('main100', $data);
	}
	// 재고조정
	public function stockchange()
	{
		$data['title']='재고조정';
		return $this->load->view('main100', $data);
	}
	// 출고등록
	public function release()
	{
		$data['title']='출고등록';
		return $this->load->view('main100', $data);
	}
	// 기간별/업체별 출고내역
	public function dbrelease()
	{
		$data['title']='기간별/업체별 출고내역';
		return $this->load->view('main100', $data);
	}
	// 클래임 등록
	public function claim()
	{
		$data['title']='클래임 등록';
		return $this->load->view('main100', $data);
	}
	// 클래임 내역 조회
	public function claimcur()
	{
		$data['title']='클래임 내역 조회';
		return $this->load->view('main100', $data);
	}
}
