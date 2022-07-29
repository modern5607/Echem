<?php
defined('BASEPATH') or exit('No direct script access allowed');

class _INTERFACE extends CI_Controller
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
		$this->load->model(array('interface_model', 'sys_model'));

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

	public function interface($tank="")
	{
		$title[0] = "온수탱크";
		$title[1] = "탄산나트륨탱크";
		$title[2] = "세척탱크";
		$title[3] = "반응탱크";
		$title[4] = "교반탱크1";
		$title[5] = "교반탱크2";
		$title[6] = "교반탱크3";
		$data['title'] = $title[$tank]." INTERFACE";
		$data['URI'] = $tank;
		$this->load->view('/main50', $data);
	}

	public function head_interface($tank="")
	{
		$data = array();
		$data['str']['tank'] = $this->input->post("tank");
		$params['DATE'] = $this->input->post("date");

		$data['str']['sdate'] = $this->input->post("sdate");
		$data['str']['edate'] = $this->input->post("edate");
		$data['str']['tank'] = $tank;
		// echo $data['str']['tank'];
		$this->data['URI'] = $data['str']['tank'];

		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : date("Y-m-d", time());
		$params['TANK'] = $data['str']['tank'];

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		//=====================================

		$data['list'] = $this->interface_model->head_interface($params,$pageNum,$config['per_page']);
		// echo var_dump($data['list']);
		$this->data['cnt'] = 0;//$this->interface_model->head_interface_cut($params);

		//=====================================
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();
			$params['DATE'] = $this->input->post("date");
		//뷰
		$this->load->view('interface_test/head_interface_select', $data);
	}

	// public function head_interface_select()
	// {
	// 	$data = array();
	// 	$data['str']['tank'] = $this->input->post("tank");
	// 	$params['DATE'] = $this->input->post("date");

	// 	$data['selectinfo'] = $this->interface_model->head_interface_select($params);

	// 	return $this->load->view('interface/head_interface_select', $data);
	// }


	public function detail_interface()
	{
		$data = array();
		$sdate = $this->input->post("sdate");
		$edate = $this->input->post("edate");
		$tank = $this->input->post("tank");
		if($tank>=1)
			$tank++;
		$params['SDATE'] = $sdate;
		$params['EDATE'] = $edate;
		$params['TANK'] = $tank;
		// echo $params['TANK'];
		
		$data['info'] = $this->interface_model->detail_interface($params);
		if (!isset($params['SDATE'])) {
		} else {
		}
		// echo var_dump($data['info']);
		return $this->load->view('interface_test/detail_interface', $data);
	}
	
}
