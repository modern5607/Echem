<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PRODMON extends CI_Controller
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
		$this->load->model(array('mif_model', 'sys_model','stock_model'));

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
	// 	생산현황 모니터 - 사무동
	public function prodmonoff()
	{
		$data['title']='생산현황 모니터 - 사무동';	
		return $this->load->view('main100', $data);
	}	
	public function ajax_prodmonoff()
	{
		//모델
		$data['list']=$this->prod_model->ajax_prodmonoff();

		//뷰
		$this->load->view('prodmon/ajax_prodmonoff', $data);
	}

	// 	생산현황 모니터 - 공장동
	public function prodmonfac()
	{
		$data['title']='생산현황 모니터 - 공장동';	
		return $this->load->view('main100', $data);
	}	
	public function ajax_prodmonfac()
	{
		//모델
		$data['list']=$this->prod_model->ajax_prodmonfac();

		//뷰
		$this->load->view('prodmon/ajax_prodmonfac', $data);
	}


}
?>