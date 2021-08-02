<?php
defined('BASEPATH') or exit('No direct script access allowed');

class REG extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		$this->data['userLevel'] = $this->session->userdata('user_level');

		$this->load->model('reg_model');

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
				$this->load->view('/layout/header', $this->data);
				call_user_func_array(array($this, $method), $params);
				$this->load->view('/layout/tail');
			} else {
				show_404();
			}
		}
	}




	/* 로그인 */
	public function login()
	{
		$user_id = $this->session->userdata('user_id');
		if ($user_id) {
			redirect(base_url('SYS'));
		}
		$data = "";
		$this->load->view('login', $data);
	}


	/* 로그아웃 */
	public function logout()
	{
		$user_id = $this->session->userdata('user_id');

		if (!$user_id) { //비로그인 시
			//alert(base_url('register/login'));
		} else {

			$IDX = $this->session->userdata("user_logid");
			$this->reg_model->set_log_end($IDX);

			$this->session->sess_destroy();
		}

		redirect(base_url('REG/login'));
	}

	public function pageexit()
	{
		$user_id = $this->session->userdata('user_id');
		$ip = $_SERVER['REMOTE_ADDR'];
		$state = 'pageExit';

		//$this->session->sess_destroy();
		$this->reg_model->get_log_update($user_id, $ip, $state);
	}




	/* 로그인폼 세션생성 */
	public function login_exec()
	{
		$res = array();
		$res['is_login'] = 'N';
		$res['msg'] = "";
		$res['url'] = "";

		$mid = $this->input->post('ID');
		$pw = strip_tags($this->input->post('PWD'));

		$userinfo = $this->reg_model->get_userchk('ID', $mid);


		if ($userinfo->STATE != 'Y') {
			alert("비활성화된 계정입니다.", base_url('REG/login'));
		}
		if ($userinfo) {

			if (password_verify($pw, $userinfo->PWD)) {

				$user['user_id']        = $userinfo->ID;
				$user['user_name']      = $userinfo->NAME;
				$user['user_level']     = $userinfo->LEVEL;



				$userAgent = $_SERVER["HTTP_USER_AGENT"];
				$os = $this->getOsInfo($userAgent);
				$borwser = $this->getBrowserInfo($userAgent);

				date_default_timezone_set('Asia/Seoul');
				$params = array(
					"SDATE"   => date("Y-m-d H:i:s", time()),
					"IP"      => $_SERVER["REMOTE_ADDR"],
					"MID"     => $userinfo->ID,
					"OS"      => $os,
					"BROWSER" => $borwser,
					"STATUS"  => "on"
				);

				$logid = $this->reg_model->set_log_insert($params);

				$user['user_logid']     = $logid;

				$this->session->set_userdata($user);
			} 
			else 
				alert("비밀번호를 확인해 주세요.", base_url('REG/login'));
			
		} 
		else 
			alert("회원정보가 존재하지 않습니다.", base_url('REG/login'));
		

			redirect(base_url(''));
	}

	public function ajax_savelevel_update()
	{
		$param['idx'] = $this->input->post('idx');
		$param['level'] = $this->input->post('sqty');

		$data = $this->reg_model->ajax_savelevel_update($param);
		echo $data;
	}

	
	/* 닉네임 중복 체크 */
    public function ajax_nickchk()
    {
        $this->load->helper('aes_helper');
        $data = array();

        //중복검사
        $parem = $this->input->post("nickname");
        $chk = $this->reg_model->ajax_dupl_chk('nickname',$parem);
        if ($chk > 0) {
            $data['state'] = "N";
            $data['msg'] = "";
        } else {
            $data['state'] = "Y";
            $data['msg'] = "";
        }

        echo json_encode($data);

    }


	/* 회원가입 완료 페이지 */
    public function reg_message()
    {
        if($this->session->userdata('user_id') && $this->session->flashdata('reg_set')){
            $this->load->view("REG/mobile/reg_message");
            $this->session->keep_flashdata('reg_set');
        } else {
            redirect('/acon');
        }
    }

	public function getBrowserInfo($userAgent)
	{
		if (preg_match('/MSIE/i', $userAgent)) {
			$browser = 'Internet Explorer';
		} else if (preg_match('/Firefox/i', $userAgent)) {
			$browser = 'Mozilla Firefox';
		} else if (preg_match('/Chrome/i', $userAgent)) {
			$browser = 'Google Chrome';
		} else if (preg_match('/Safari/i', $userAgent)) {
			$browser = 'Apple Safari';
		} elseif (preg_match('/Opera/i', $userAgent)) {
			$browser = 'Opera';
		} elseif (preg_match('/Netscape/i', $userAgent)) {
			$browser = 'Netscape';
		} else {
			$browser = "Other";
		}
		return $browser;
	}

	public function getOsInfo($userAgent)
	{
		if (preg_match('/linux/i', $userAgent)) {
			$os = 'linux';
		} elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
			$os = 'mac';
		} elseif (preg_match('/windows|win32/i', $userAgent)) {
			$os = 'windows';
		} else {
			$os = 'Other';
		}
		return $os;
	}



	
	public function ajax_set_memberinfo()
	{
		$mode = $this->input->post("mode");
		$idx  = $this->input->post("idx");

		$data = array();
		if (!empty($idx)) {
			$data['memInfo'] = $this->reg_model->get_member_info($idx);
		}

		$this->load->view('/register/memberform', $data);
	}



	public function member_formUpdate_info()
	{
		$params = array(
			"NO" => $this->input->post("NO"),
			"FIRSTDAY" => $this->input->post("FIRSTDAY"),
			"PART" => $this->input->post("PART"),
			"GRADE" => $this->input->post("GRADE"),
			"WORKKIND" => $this->input->post("WORKKIND"),
			"BANKNAME" => $this->input->post("BANKNAME"),
			"BANKNUM" => $this->input->post("BANKNUM"),
			"BANKOWN" => $this->input->post("BANKOWN"),
		);
		$idx = $this->input->post("IDX");
		$data = $this->reg_model->member_formUpdate_info($params, $idx);
		if ($data > 0) {
			alert('수정 되었습니다.', base_url('SYS/infoform'));
		} else {
			alert('변경값이 없습니다.', base_url('SYS/infoform'));
		};
	}
}
