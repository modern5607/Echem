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
		$title[1] = "A 탱크(2Ton)";
		$title[2] = "B 탱크(2Ton)";
		$title[3] = "C 탱크(5Ton)";
		$title[4] = "SUS 탱크(1Ton)";
		$title[5] = "SUS 탱크(1Ton)";
		$title[6] = "교반탱크3";
		$data['title'] = $title[$tank]." INTERFACE";
		$data['URI'] = $tank;
		$this->load->view('/main50', $data);
	}

	public function head_interface($tank="")
	{
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

		//뷰
		$this->load->view('interface/head_interface', $data);
	}

	public function head_interface_select()
	{
		$data = array();
		$data['str']['tank'] = $this->input->post("tank");
		$params['DATE'] = $this->input->post("date");

		$data['selectinfo'] = $this->interface_model->head_interface_select($params);

		return $this->load->view('interface/head_interface_select', $data);
	}


	public function detail_interface()
	{
		$data = array();
		$sdate = $this->input->post("sdate");
		$edate = $this->input->post("edate");
		$tank = $this->input->post("tank");
		if($tank>=1)
			$tank++;
		$params['SDATE'] = $sdate;
		
		if(strlen($edate)>0){
			$params['EDATE'] = $edate;
		}else{
			$params['EDATE'] = date('Y-m-d H:i:s', time());
		}
		
		//$params['EDATE'] = $edate;
		$params['TANK'] = $tank;
		 
		//echo $params['TANK'];
		
		if($tank==2){
			$params['TANK'] = '1';
			$data['info'] = $this->interface_model->detail_interface($params);
		}else if($tank==3){
			$params['TANK'] = '2';
			$data['info'] = $this->interface_model->detail_interface2($params);
		}else if($tank==4){
			$params['TANK'] = '3';
			$data['info'] = $this->interface_model->detail_interface3($params);
		}else if($tank==5){
			$params['TANK'] = '4';
			$data['info'] = $this->interface_model->detail_interface4($params);
		}else if($tank==6){
			$params['TANK'] = '5';
			$data['info'] = $this->interface_model->detail_interface5($params);
		}

		//$data['info'] = $this->interface_model->detail_interface($params);
		if (!isset($params['SDATE'])) {
		} else {
		}
		// echo var_dump($data['info']);
		return $this->load->view('interface/detail_interface', $data);
	}


	// EC입력
	public function ec()
	{
		$data['title'] = "EC입력";

		$data['str']['slave'] = $this->input->get('slave');
		if (!empty($data['str']['slave'])){
			$params['SLAVE'] = $data['str']['slave'];
			$params['GUBUN'] = "EC";
			$this->interface_model->sensor_ins($params);
		}

		return $this->load->view('main100', $data);
	}

	// EC입력
	public function ajax_ec()
	{
		$data['str'] = array();
		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');
		$data['str']['slave'] = $this->input->post('slave');

		$params['SDATE'] = '';
		$params['EDATE'] = '';
		$params['SLAVE'] = '';
		$params['GUBUN'] = 'EC';
		if (!empty($data['str']['sdate']))
			$params['SDATE'] = $data['str']['sdate'];

		if (!empty($data['str']['edate']))
			$params['EDATE'] = $data['str']['edate'];

		if (!empty($data['str']['slave']))
			$params['SLAVE'] = $data['str']['slave'];

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


		$data['List'] = $this->interface_model->manual_list($params,$start,$config['per_page']);
		$this->data['cnt'] = $this->interface_model->manual_list_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		return $this->load->view('/interface/ajax_sensor', $data);
	}

	//
	public function detail_sensor()
	{
		$data['str'] = array();
		$data['str']['idx'] = $this->input->post('idx');
		$data['str']['slave'] = $this->input->post('slave');

		$data['idx'] = $data['str']['idx'];
		$data['slave'] = $data['str']['slave'];

		$params['IDX'] = '';
		$params['SLAVE'] = '';
		if (!empty($data['str']['idx']))
			$params['IDX'] = $data['str']['idx'];
		if (!empty($data['str']['slave']))
			$params['SLAVE'] = $data['str']['slave'];

		$data['List'] = $this->interface_model->manual_list2($params);

		return $this->load->view('/interface/detail_manual', $data);
	}
	//
	public function detail_sensor_chart()
	{
		$data['str'] = array();
		$data['str']['idx'] = $this->input->post('idx');
		$data['str']['slave'] = $this->input->post('slave');


		$data['idx'] = $data['str']['idx'];
		$data['slave'] = $data['str']['slave'];

		$params['IDX'] = '';
		$params['SLAVE'] = '';
		if (!empty($data['str']['idx']))
			$params['IDX'] = $data['str']['idx'];
		if (!empty($data['str']['slave']))
			$params['SLAVE'] = $data['str']['slave'];

		$data['info'] = $this->interface_model->manual_list2($params);

		return $this->load->view('/interface/manual_chart', $data);
	}

	
	public function ec_up()
	{
		$params['IDX'] = $this->input->post("IDX");

		$params['SENSORDATE1'] = $this->input->post("SENSORDATE1");
		$params['SENSOR1'] = $this->input->post("SENSOR1");
		$params['MANUAL1'] = $this->input->post("MANUAL1");

		$params['SENSORDATE2'] = $this->input->post("SENSORDATE2");
		$params['SENSOR2'] = $this->input->post("SENSOR2");
		$params['MANUAL2'] = $this->input->post("MANUAL2");

		$params['SENSORDATE3'] = $this->input->post("SENSORDATE3");
		$params['SENSOR3'] = $this->input->post("SENSOR3");
		$params['MANUAL3'] = $this->input->post("MANUAL3");

		$params['SENSORDATE4'] = $this->input->post("SENSORDATE4");
		$params['SENSOR4'] = $this->input->post("SENSOR4");
		$params['MANUAL4'] = $this->input->post("MANUAL4");

		$params['SENSORDATE5'] = $this->input->post("SENSORDATE5");
		$params['SENSOR5'] = $this->input->post("SENSOR5");
		$params['MANUAL5'] = $this->input->post("MANUAL5");
			
		$data['result'] = $this->interface_model->manual_up($params);
		echo $data['result'];

	}
}
