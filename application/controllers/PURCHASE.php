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
		return $this->load->view('main100', $data);
	}

	public function ajax_matorder()
	{
		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['SDATE'] = '';
		$params['EDATE'] = '';
		$params['END_CHK'] = '';

		$data['qstr'] = "?P";

		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}
		
		
		
		//모델
		$data['list']=$this->pur_model->component_list($params);
		$data['cocd']= $this->sys_model->get_selectInfo("tch.CODE","UNIT");

		//뷰
		$this->load->view('purchase/ajax_matorder', $data);
	}

	public function component_head_insert()
	{
		$params['ACT_DATE'] = $this->input->post("ADATE");
		$params['QTY'] = $this->input->post("QTY");
		$params['UNIT'] = $this->input->post("UNIT");
		$params['DEL_DATE'] = $this->input->post("DDATE");
		$params['REMARK'] = $this->input->post("REMARK");
			

		$num = $this->pur_model->component_head_insert($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "실적이 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function del_component()
	{
		$idx = $this->input->get("idx");
		$num = $this->pur_model->del_component($idx);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function end_component()
	{
		$params['QTY'] = $this->input->post("QTY");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IDX'] = $this->input->post("IDX");
			
		$data = $this->pur_model->end_component($params);

		echo json_encode($data);
	}





	// 	입고등록
	public function enter()
	{
		$data['title']='입고등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_enter()
	{
		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['SDATE'] = '';
		$params['EDATE'] = '';
		$params['END_CHK'] = 'N';

		$data['qstr'] = "?P";

		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}
		
		//모델
		$data['list']=$this->pur_model->component_list($params);

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
		$data['str'] = array(); //검색어관련

		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['SDATE'] = '';
		$params['EDATE'] = '';
		$params['END_CHK'] = '';

		$data['qstr'] = "?P";

		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=" . $data['str']['sdate'];
		}

		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=" . $data['str']['edate'];
		}

		
		//모델
		$data['list']=$this->pur_model->component_list($params);

		//뷰
		$this->load->view('purchase/ajax_orderenter', $data);
	}
	
}
