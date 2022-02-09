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
		$this->load->model(array('mif_model', 'sys_model','prod_model'));

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
		$data['title']='작업지시등록';	
		return $this->load->view('main100', $data);
	}	
	public function ajax_workorder()
	{
		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");


		//모델
		$data['list']=$this->prod_model->ajax_workorder();

		//뷰
		$this->load->view('prod/ajax_workorder', $data);
	}

	public function order_form()
	{
		
		$params1['END_YN'] = 'N';
		$data['ACT'] = $this->prod_model->get_act($params1);
		// echo var_dump($data['ACT']);

		$data['COMPONENT'] = $this->prod_model->get_component();

		return $this->load->view('prod/order_form',$data);
	}

	// 공정별 작업지시
	public function pworkorder()
	{
		$data['title']='공정별 작업지시';	
		return $this->load->view('main100', $data);
	}

	public function act_idx()
	{
		$idx = $this->input->post("idx");
		$data['info'] = $this->prod_model->act_idx($idx);
		$data['status'] = "ok";
		// echo var_dump($data);
		echo json_encode($data);

	}

	public function add_order()
	{
		$data['ACT_IDX']=$this->input->post("ACT");
		$data['ORDER_DATE']=$this->input->post("ORDER_DATE");
		$data['COMPONENT_IDX']=$this->input->post("COMPONENT");
		$data['ORDER_QTY']=$this->input->post("ORDER_QTY");

		$params['ACT_IDX'] = 		$data['ACT_IDX'];
		$params['ORDER_DATE'] = 	$data['ORDER_DATE'];
		$params['COMPONENT_IDX'] = 	$data['COMPONENT_IDX'];
		$params['ORDER_QTY'] = 		$data['ORDER_QTY'];
		$params['INSERT_ID'] = 		$this->session->userdata('user_id');


		echo var_dump($data);
		$data['result'] = $this->prod_model->add_order($params);
	}

	public function ajax_pworkorder()
	{
		//모델
		$data['list']=$this->prod_model->ajax_pworkorder();

		//뷰
		$this->load->view('prod/ajax_pworkorder', $data);
	}

	// 원재료 투입 입력
	public function matinput()
	{
		$data['title']='원재료 투입 입력';	
		return $this->load->view('main100', $data);
	}
	public function ajax_matinput()
	{
		//모델
		$data['list']=$this->prod_model->ajax_matinput();

		//뷰
		$this->load->view('prod/ajax_matinput', $data);
	}

	// 공정별 수율정보
	public function pharvest()
	{
		$data['title']='공정별 수율정보';	
		return $this->load->view('main100', $data);
	}
	public function ajax_pharvest()
	{
		//모델
		$data['list']=$this->prod_model->ajax_pharvest();

		//뷰
		$this->load->view('prod/ajax_pharvest', $data);
	}

	// 공정별 생산내역
	public function pprodcur()
	{
		$data['title']='공정별 생산내역';	
		return $this->load->view('main100', $data);
	}
	public function ajax_pprodcur()
	{
		//모델
		$data['list']=$this->prod_model->ajax_pprodcur();

		//뷰
		$this->load->view('prod/ajax_pprodcur', $data);
	}

	// 기간별 생산실적
	public function dprodperf()
	{
		$data['title']='기간별 생산실적';	
		return $this->load->view('main100', $data);
	}
	public function ajax_dprodperf()
	{
		//모델
		$data['list']=$this->prod_model->ajax_dprodperf();

		//뷰
		$this->load->view('prod/ajax_dprodperf', $data);
	}

	// 생산 모니터링
	public function prodmonitor()
	{
		$data['title']='생산 모니터링';	
		return $this->load->view('main100', $data);
	}
	public function ajax_prodmonitor()
	{
		//모델
		$data['list']=$this->prod_model->ajax_prodmonitor();

		//뷰
		$this->load->view('prod/ajax_prodmonitor', $data);
	}

}
