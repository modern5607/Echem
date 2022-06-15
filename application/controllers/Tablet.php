<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tablet extends CI_Controller
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
		$this->load->model(array('tablet_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');
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
				$this->data['member_name'] = $this->session->userdata('user_name');

				if (isset($user_id) && $user_id != "") {

					// $this->load->view('/layout/header', $this->data);
					$this->load->view('/layout/m_header', $this->data);
					call_user_func_array(array($this, $method), $params);
					$this->load->view('/layout/tail');
				} else {
					alert('로그인이 필요합니다.', base_url('register/m_login'));
				}
			} else {
				show_404();
			}
		}
	}

	public function index()
	{
		// $data['timer'] = $this->main_model->get_selectInfo_remark("tch.CODE","TIMER","Second");
		$data['member_name'] = $this->session->userdata('user_name');

		$this->load->view('/tablet/index',$data);
	}
	public function timer_update()
	{
		$param['time'] = $this->input->post('time');

		$this->tablet_model->timer_update($param);
	}

	//원자재
	public function raw($date = '')
	{
		$this->data['siteTitle'] = "원재료 투입";
		$data['title'] = '원재료 투입';
		$date = date("Y-m-d");
		$data['NDATE'] = $date;
		$data['qstr'] = "?P";
	

		$data['List'] = $this->tablet_model->dual($date);
		
		// $data['timer'] = $this->main_model->get_selectInfo_remark("tch.CODE","TIMER","Second");
		// echo var_dump(($data['List']));

		$this->load->view('/tablet/raw', $data);
	}

	//원자재
	public function ajax_raw_form()
	{
		$data['title'] = '원재료 투입';
		$data['idx'] = $this->input->post('idx');
		$data['date'] = $this->input->post('date');

		$params['IDX'] = $data['idx'];

		$data['info'] = $this->tablet_model->dual($params);
		return $this->load->view('tablet/ajax_raw', $data);
	}

	//완제품
	public function prod($date = '')
	{
		$this->data['siteTitle'] = "완제품 투입";
		$data['title'] = '완제품 투입';
		$date = date("Y-m-d");
		$data['NDATE'] = $date;
		$data['qstr'] = "?P";
	

		$data['List'] = $this->tablet_model->dual($date);
		
		// $data['timer'] = $this->main_model->get_selectInfo_remark("tch.CODE","TIMER","Second");
		// echo var_dump(($data['List']));

		$this->load->view('/tablet/prod', $data);
	}

	//완제품
	public function ajax_prod_form()
	{
		$data['title'] = '완제품 투입';
		$data['idx'] = $this->input->post('idx');
		$data['date'] = $this->input->post('date');

		$params['IDX'] = $data['idx'];

		$data['info'] = $this->tablet_model->dual($params);
		return $this->load->view('tablet/ajax_prod', $data);
	}

	//성형 입력 Update
	public function add_sh_order()
	{
		$data['idx'] = $this->input->post('idx');
		$data['qty'] = $this->input->post('FNSH_QTY')*1;
		$data['bqty'] = ($this->input->post('BQTY'))?$this->input->post('BQTY')*1:0;
		$data['gb'] = $this->input->post('GB');

		$params['IDX'] = $data['idx'];
		$params['F_QTY'] = $data['qty'];
		$params['BQTY'] = $data['bqty'];
		$params['GB'] = $data['gb'];

		$result = $this->tablet_model->update_sh_order($params);

		if ($result > 0) {
			$data['status'] = "ok";
			$data['msg'] = "작업실적이 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "작업실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}

}
