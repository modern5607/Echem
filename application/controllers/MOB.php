<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MOB extends CI_Controller {

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
        $this->data['subpos'] = $this->uri->segment(2);
		$this->data['ssubpos'] = $this->uri->segment(3);

		
		$this->load->model(array('bom_model','main_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');

		

	}

	public function _remap($method, $params = array())
	{
		if($this->input->is_ajax_request()){
            if( method_exists($this, $method) ){
                call_user_func_array(array($this,$method), $params);
            }
        }else{ //ajax가 아니면
			
			if (method_exists($this, $method)) {

				$user_id = $this->session->userdata('user_id');
				//if(isset($user_id) && $user_id != ""){
					
				$this->load->view('/layout/m_header',$this->data);
				call_user_func_array(array($this,$method), $params);
				$this->load->view('/layout/m_tail');

				//}else{

				//	alert('로그인이 필요합니다.',base_url('REG/login'));

				//}

            } else {
                show_404();
            }

        }
		
	}

	public function index(){
		$data['title'] = "DIGITAL";
		$this->load->view('/mobile/index',$data);
	}
}