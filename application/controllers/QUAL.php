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
		$this->load->model(array('mif_model', 'sys_model','qual_model'));

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
		$data['title']='품질검사 등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_qexam()
	{
		//모델
		$data['list']=$this->qual_model->ajax_qexam();

		//뷰
		$this->load->view('qual/ajax_qexam', $data);
	}


	// 실적대비 불량률
	public function perfpoor()
	{
		$data['title']='실적대비 불량률';
		return $this->load->view('main100', $data);
	}
	public function ajax_perfpoor()
	{
		//모델
		$data['list']=$this->qual_model->ajax_perfpoor();

		//뷰
		$this->load->view('qual/ajax_perfpoor', $data);
	}


	// 품질이력
	public function qualitycur()
	{
		$data['title']='품질이력';
		return $this->load->view('main100', $data);
	}
	public function ajax_qualitycur()
	{
		//모델
		$data['list']=$this->qual_model->ajax_qualitycur();

		//뷰
		$this->load->view('qual/ajax_qualitycur', $data);
	}

	
	// 불량분석
	public function pooranal()
	{
		$data['title']='불량분석';
		return $this->load->view('main100', $data);
	}
	public function ajax_pooranal()
	{
		//모델
		$data['list']=$this->qual_model->ajax_pooranal();

		//뷰
		$this->load->view('qual/ajax_pooranal', $data);
	}
}
