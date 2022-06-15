<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MDM extends CI_Controller
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
		$this->load->model(array('mdm_model', 'sys_model'));

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


	//공통코드
	public function code()
	{
		$data['title'] = "공통코드등록";

		$this->load->view('/main50', $data);
	}

	public function head_code()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['code'] = $this->input->post('h_code');
		$data['str']['name'] = $this->input->post('h_name');
		$data['str']['use'] = $this->input->post('h_use');

		$params['CODE'] = "";
		$params['NAME'] = "";
		$params['USE'] = "";

		if (!empty($data['str']['code']))
			$params['CODE'] = $data['str']['code'];

		if (!empty($data['str']['name']))
			$params['NAME'] = $data['str']['name'];

		if (!empty($data['str']['use']))
			$params['USE'] = $data['str']['use'];

		$data['List']   = $this->mdm_model->get_cocdHead_list($params);


		$this->load->view('/mdm/head_code', $data);
	}

	public function detail_code()
	{
		$hidx = $this->input->post('hidx');

		$data['str'] = array(); //검색어관련
		$data['str']['code'] = $this->input->post('d_code');
		$data['str']['name'] = $this->input->post('d_name');
		$data['str']['use'] = $this->input->post('d_use');
		$data['str']['hidx'] = $hidx;
		$data['hidx'] = $hidx;

		$params['D_CODE'] = "";
		$params['D_NAME'] = "";
		$params['D_USE'] = "";

		$data['de_show_chk'] = ($hidx != "") ? true : false;


		if (!empty($data['str']['code']))
			$params['D_CODE'] = $data['str']['code'];

		if (!empty($data['str']['name']))
			$params['D_NAME'] = $data['str']['name'];

		if (!empty($data['str']['use']))
			$params['D_USE'] = $data['str']['use'];


		$data['List'] = $this->mdm_model->get_cocdDetail_list($hidx, $params);

		return $this->load->view('/mdm/detail_code', $data);
	}

	// 품목등록
	public function items()
	{
		$data['title'] = '품목등록';
		$this->load->view('main100', $data);
	}

	public function ajax_items()
	{
		$data['str']['ITEM_NAME'] = $this->input->post("ITEM_NAME");
		$data['str']['USEYN'] = $this->input->post("USEYN");

		$params['ITEM_NAME'] = isset($data['str']['ITEM_NAME'])?$data['str']['ITEM_NAME']:"";
		$params['USEYN'] = isset($data['str']['USEYN'])?$data['str']['USEYN']:"";


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;

		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		//$start = $config['per_page'] * ($pageNum - 1);

		$start = $pageNum;
		$data['pageNum'] = $start;

		//모델
		$data['list']=$this->mdm_model->ajax_items($params,$start,$config['per_page']);
		$this->data['cut']=$this->mdm_model->ajax_items_cut($params);
		// echo var_dump($this->data['cut']);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		//뷰
		$this->load->view('mdm/ajax_items', $data);
	}
	
	public function items_form()
	{
		$data['mode'] = $this->input->post("mode");
		$data['idx'] = $this->input->post("idx");
		
		
		$data['UNIT'] = $this->mdm_model->get_selectInfo("tch.CODE","UNIT");
		// echo var_dump($data['UNIT']);

		if($data['mode'] == "mod")
		{
			$data['List'] = $this->mdm_model->get_items($data['idx']);

			// echo var_dump($data['List']);
		}
		
		
		return $this->load->view('mdm/items_form', $data);
	}

	public function item_update()
	{
		$data['mode'] = $this->input->post("mod");
		$data['IDX'] = $this->input->post("idx");
		$data['ITEM_NAME'] = $this->input->post("ITEM_NAME");
		$data['UNIT'] = $this->input->post("UNIT");
		$data['USE_YN'] = $this->input->post("USE_YN");

		$params['IDX'] = $data['IDX'];
		$params['ITEM_NAME'] = $data['ITEM_NAME'];
		$params['UNIT'] = $data['UNIT'];
		$params['USE_YN'] = $data['USE_YN'];
		$params['ID'] = $this->session->userdata('user_id');



		if($data['mode'] == "add")
			$data['result'] = $this->mdm_model->set_item($params);
		else if($data['mode']=="mod")
			$data['result'] = $this->mdm_model->update_item($params);

		// echo var_dump($data);
		echo $data['result'];
	}

	//업체등록
	public function biz()
	{
		$data['title'] = "업체등록";
		$this->load->view('/main100', $data);
	}
	//업체등록 리스트
	public function ajax_biz()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['custnm'] = trim((string)$this->input->post('custnm'));
		$data['str']['address'] = trim((string)$this->input->post('address'));
		$data['str']['useyn'] = ($this->input->post('useyn'))?$this->input->post('useyn'):'Y' ;

		$params['CUST_NM'] = "";
		$params['ADDRESS'] = "";
		$params['USEYN'] = "";

		if (!empty($data['str']['custnm'])) { $params['CUST_NM'] = $data['str']['custnm']; }
		if (!empty($data['str']['address'])) { $params['ADDRESS'] = $data['str']['address']; }
		if (!empty($data['str']['useyn'])) { $params['USEYN'] = $data['str']['useyn']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		$data['bizList']   = $this->mdm_model->biz_list($params, $start, $config['per_page']);
		$this->data['cut'] = $this->mdm_model->biz_cut($params);

		// $data['SJGB']   = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");
		// echo var_dump($data['SJGB']);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/mdm/ajax_biz', $data);
	}
	/* 업체등록 호출 */
	public function biz_form()
	{
		$data['str']['title'] = "업체등록";
		$data['str']['mode'] = $this->input->post("mode");
		$data['str']['IDX'] = $this->input->post("IDX");


		$params['title'] = "업체등록";
		$params['mod'] = 0;

		$data['BIZGB'] = $this->sys_model->get_selectInfo('tch.CODE',"BizType"); 
		// echo var_dump($data['BIZGB']);
		if ($data['str']['mode'] == "mod") {
			$data['str']['title'] .= " - 수정";
			$data['info'] = $this->mdm_model->biz_form_list($data['str']['IDX']);
			// echo var_dump($data['info']);
			// $params['mod'] = 1;
		}

		// echo var_dump($data);

		return $this->load->view('/mdm/biz_form', $data);
	}
	/* 닉네임 중복 체크 */
	public function biz_nameChk()
	{
		$data = $this->mdm_model->biz_nameChk($this->input->post("name"));
		echo json_encode($data);
	}
	//업체 리스트 업데이트(추가,변경)
	public function biz_ins_up()
	{
		$params['mod']          = $this->input->post("mod");
		$params['IDX']          = $this->input->post("IDX");
		$params['CUST_NM']    	= $this->input->post("CUST_NM");
		$params['CUST_TYPE']    = $this->input->post("CUST_TYPE");
		$params['ADDRESS']    	= $this->input->post("ADDRESS");
		$params['TEL']          = $this->input->post("TEL");
		$params['CUST_NAME']	= $this->input->post("CUST_NAME");
		$params['USE_YN']    	= $this->input->post("USE_YN");
		$params['ITEM']         = $this->input->post("ITEM");
		$params['REMARK']    	= $this->input->post("REMARK");
		$params['INSERT_ID'] 	= $this->session->userdata('user_name');

		$ins = $this->mdm_model->biz_ins_up($params);

		$data['status'] = "";
		$data['msg']    = "";


		if ($ins != "") {

			$mTit = ($params['mod'] == 1) ? "수정" : "등록";
			$data = array(
				'status' => 'ok',
				'msg'    => '업체가 ' . $mTit . ' 되었습니다.'
			);
			echo json_encode($data);
		}
	}

	// 업체현황
	public function bizcur()
	{
		$data['title'] = '업체현황';
		$this->load->view('main100', $data);
	}
	public function ajax_bizcur()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['custnm'] = trim((string)$this->input->post('custnm'));
		$data['str']['address'] = trim((string)$this->input->post('address'));
		$data['str']['useyn'] = ($this->input->post('useyn'))?$this->input->post('useyn'):'Y' ;

		$params['CUST_NM'] = "";
		$params['ADDRESS'] = "";
		$params['USEYN'] = "";

		if (!empty($data['str']['custnm'])) { $params['CUST_NM'] = $data['str']['custnm']; }
		if (!empty($data['str']['address'])) { $params['ADDRESS'] = $data['str']['address']; }
		if (!empty($data['str']['useyn'])) { $params['USEYN'] = $data['str']['useyn']; }


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

		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');

		// $data['list']=$this->mdm_model->ajax_bizcur($params, $start, $config['per_page']);
		//모델
		$data['bizList']   = $this->mdm_model->biz_list($params, $start, $config['per_page']);
		$this->data['cut'] = $this->mdm_model->biz_cut($params);

		// $data['SJGB']   = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");
		// echo var_dump($data['SJGB']);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cut'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();
		
		

		//뷰
		$this->load->view('mdm/ajax_bizcur', $data);
	}

	// // 인사정보등록
	// public function person()
	// {
	// 	$data['title'] = '인사정보등록';
	// 	$this->load->view('main100', $data);
	// }
	// public function ajax_person()
	// {
	// 	//모델
	// 	$data['list']=$this->mdm_model->ajax_person();

	// 	//뷰
	// 	$this->load->view('mdm/ajax_person', $data);
	// }

	// // 인사정보현황
	// public function personcur()
	// {
	// 	$data['title'] = '인사정보현황';
	// 	$this->load->view('main100', $data);
	// }

	// // 인사정보현황 리스트
	// public function ajax_personcur()
	// {
	// 	//모델
	// 	$data['list']=$this->mdm_model->ajax_personcur();

	// 	//뷰
	// 	$this->load->view('mdm/ajax_personcur', $data);
	// }


	// //작업자 등록
	// public function member()
	// {
	// 	$data['title'] = "작업자등록";

	// 	$this->load->view('/main100', $data);
	// }

	// public function ajax_member()
	// {
	// 	$data['str'] = array(); //검색어관련
	// 	$data['str']['mid'] = trim($this->input->post('mid')); //MEMBER ID
	// 	$data['str']['mname'] = trim($this->input->post('mname')); //MEMBER ID
	// 	$data['str']['level'] = $this->input->post('level'); //LEVEL
	// 	$data['str']['useyn'] = ($this->input->post('useyn'))?$this->input->post('useyn'):'Y' ;

	// 	$params['ID'] = "";
	// 	$params['NAME'] = "";
	// 	$params['LEVEL'] = "";
	// 	$params['USEYN'] = "";

	// 	if (!empty($data['str']['mid'])) {
	// 		$params['ID'] = $data['str']['mid'];
	// 	}
	// 	if (!empty($data['str']['mname'])) {
	// 		$params['NAME'] = $data['str']['mname'];
	// 	}
	// 	if (!empty($data['str']['level'])) {
	// 		$params['LEVEL'] = $data['str']['level'];
	// 	}
	// 	if (!empty($data['str']['useyn'])) { $params['USEYN'] = $data['str']['useyn']; }

	// 	$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;

	// 	//PAGINATION
	// 	$config['per_page'] = $data['perpage'];
	// 	$config['page_query_string'] = true;
	// 	$config['query_string_segment'] = "pageNum";
	// 	$config['reuse_query_string'] = TRUE;

	// 	$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;

	// 	$start = $pageNum;
	// 	$data['pageNum'] = $start;

	// 	$user_id = $this->session->userdata('user_id');
	// 	$this->data['userName'] = $this->session->userdata('user_name');

	// 	$data['memberList'] = $this->mdm_model->member_list($params, $start, $config['per_page']);
	// 	$this->data['cnt'] = $this->mdm_model->member_cut($params);


	// 	/* pagenation start */
	// 	$this->load->library("pagination");
	// 	$config['base_url'] = base_url(uri_string());
	// 	$config['total_rows'] = $this->data['cnt'];
	// 	$config['full_tag_open'] = "<div>";
	// 	$config['full_tag_close'] = '</div>';
	// 	$this->pagination->initialize($config);
	// 	$this->data['pagenation'] = $this->pagination->create_links();


	// 	$this->load->view('/mdm/ajax_member', $data);
	// }
	// // 작업자 팝업
	// public function member_form()
	// {
	// 	$mode = $this->input->post("mode");
	// 	$idx  = $this->input->post("idx");

	// 	$data = array();
	// 	if (!empty($idx)) {
	// 		$data['memInfo'] = $this->mdm_model->member_form($idx);
	// 	}

	// 	$data['menuLevel'] = $this->sys_model->menu_level();

	// 	$this->load->view('/mdm/member_form', $data);
	// }
	
	/* 회원가입 */
	public function member_ins_up()
	{
		$IDX = "";
		$dateTime = date("Y-m-d H:i:s", time());
		$params = array(
			'NAME'     		=> trim($this->input->post("NAME")),			//이름
			'LEVEL'    		=> $this->input->post("LEVEL"),					//권한
			'STATE'    		=> $this->input->post("STATE"),					//상태(사용여부)
			'PART'     		=> trim($this->input->post("PART")),			//부서
			'TEL'      		=> trim($this->input->post("TEL")),				//연락처
			'EMAIL'      	=> trim($this->input->post("EMAIL")),			//이메일
			'HP'       		=> trim($this->input->post("HP")),				//휴대폰
		);

		if (!empty($this->input->post("mod"))) { //수정인경우
			if (!empty($this->input->post("PWD"))) {
				$params['PWD'] = password_hash(trim($this->input->post("PWD")), PASSWORD_BCRYPT);
			}
			$IDX = $this->input->post("IDX");
			$text = "수정";
		} else {
			$params['ID'] = trim($this->input->post("ID"));
			$params['PWD'] = password_hash(trim($this->input->post("PWD")), PASSWORD_BCRYPT);
			$text = "등록";
		}

		$data = $this->mdm_model->member_ins_up($params, $IDX);

		if ($data != "") {

			$ins = array(
				'status' => 'ok',
				'msg'    => '작업자정보가 ' . $text . ' 되었습니다.'
			);
			echo json_encode($ins);
		}
	}
	/* 닉네임 중복 체크 */
	public function member_idChk()
	{
		$data = $this->mdm_model->member_idChk($this->input->post("id"));
		echo json_encode($data);
	}
	/* 이름 중복 체크 */
	public function member_nameChk()
	{
		$data = $this->mdm_model->member_nameChk($this->input->post("name"));
		echo json_encode($data);
	}















	public function excelDown($hidx = "")
	{
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		ini_set('memory_limit', '-1');
		ini_set("max_execution_time", "0");
		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '&lt;br /&gt;');
		date_default_timezone_set('Asia/Seoul');

		$this->load->library('PHPExcel');

		$objPHPExcel = new PHPExcel();


		$objPHPExcel->getProperties()->setCreator('Aliseon')
			->setLastModifiedBy('Aliseon')
			->setTitle('Aliseon_SALE LIST')
			->setSubject('Aliseon_SALE LIST')
			->setDescription('Aliseon_SALE LIST');

		function column_char($i)
		{
			return chr(65 + $i);
		}


		$headers = array('HEAD-CODE', 'CODE', 'NAME', '사용유무', '비고');
		$last_char = column_char(count($headers) - 1);
		$widths = array(10, 30, 40, 50);

		$objPHPExcel->setActiveSheetIndex(0);
		/** 상단 스타일지정 **/
		$objPHPExcel->getActiveSheet()->getStyle('A1:' . $last_char . '1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:' . $last_char . '1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D9EDF7');
		$objPHPExcel->getActiveSheet()->getStyle('A1:' . $last_char . '1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:' . $last_char . '1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Nanum Gothic')->setSize(12);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

		$objPHPExcel->getActiveSheet()->getStyle('A:' . $last_char)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		foreach ($widths as $i => $w) {
			$objPHPExcel->getActiveSheet()->setCellValue(column_char($i) . '1', $headers[$i]);
			$objPHPExcel->setActiveSheetIndex()->getColumnDimension(column_char($i))->setWidth($w);
		}

		$data['str'] = array(); //검색어관련
		$data['str']['d_code'] = $this->input->get('d_code');
		$data['str']['d_name'] = $this->input->get('d_name');
		$data['str']['d_use'] = $this->input->get('d_use');

		$params1['D_CODE'] = "";
		$params1['D_NAME'] = "";
		$params1['D_USE'] = "";

		if (!empty($data['str']['d_code'])) {
			$params1['D_CODE'] = $data['str']['d_code'];
			$data['qstr'] .= "&d_code=" . $data['str']['d_code'];
		}
		if (!empty($data['str']['d_name'])) {
			$params1['D_NAME'] = $data['str']['d_name'];
			$data['qstr'] .= "&d_name=" . $data['str']['d_name'];
		}
		if (!empty($data['str']['d_use'])) {
			$params1['D_USE'] = $data['str']['d_use'];
			$data['qstr'] .= "&d_use=" . $data['str']['d_use'];
		}


		$this->data['cDetail_list'] = $this->mdm_model->get_cocdDetail_list($hidx, $params1);
		$nnn = array();
		if (count($this->data['cDetail_list']) > 0) {

			foreach ($this->data['cDetail_list'] as $k => $row) {
				$nnn[$k]['H_CODE'] = $row->H_CODE;
				$nnn[$k]['CODE'] = $row->CODE;
				$nnn[$k]['NAME'] = $row->NAME;
				$nnn[$k]['USE_YN'] = ($row->USE_YN == "Y") ? "사용" : "미사용";
				$nnn[$k]['REMARK'] = strip_tags($row->REMARK);
			}
		}



		$rows = $nnn;
		$data = array_merge(array($headers), $rows);

		$objPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A1');

		$fileName = iconv("UTF-8", "EUC-KR", "공통코드-디테일.xls");

		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		//header('Content-type: application/x-msexcel;charset=utf-8');
		//header("Content-Type:text/html;charset=ISO-8859-1");
		header('Content-Disposition: attachment;filename="' . $fileName . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}




	/* 공통코드 HEAD 폼 호출 */
	public function ajax_cocdHead_form()
	{
		$params['title'] = "공통코드-HEAD";
		$params['mod'] = 0;


		if ($_POST['mode'] == "mod") {
			$params['title'] .= " - 수정";
			$data = $this->mdm_model->get_cocdHead_info($_POST['IDX']);
			$params['data'] = $data;
			$params['mod'] = 1;
		}


		return $this->load->view('/ajax/cocd_head', $params);
	}


	public function ajax_cocdDetail_form()
	{
		$params['title'] = "공통코드-DETAIL";
		$params['mod'] = '';

		$params['hidx'] = $this->input->post("hidx");


		if ($_POST['mode'] == "mod") {
			$params['title'] .= " - 수정";
			$data = $this->mdm_model->get_cocdDetail_info($this->input->post("idx"));
			$params['data'] = $data;
			$params['mod'] = 1;
			$params['hidx'] = $data->H_IDX;
		}

		$params['headList']  = $this->mdm_model->get_cocdHead_info($params['hidx']);

		return $this->load->view('/ajax/cocd_detail', $params);
	}

	//공통코드 head update
	public function set_cocd_HeadUpdate()
	{
		$params['mod']      = $this->input->post("mod");
		$params['CODE']    = $this->input->post("CODE");
		$params['NAME']    = $this->input->post("NAME");
		$params['USE_YN']    = $this->input->post("USE_YN");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');

		$ins = $this->mdm_model->codeHead_update($params);

		$data['status'] = "";
		$data['msg']    = "";


		if ($ins != "") {
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}


	//공통코드 detail update
	public function set_cocd_DetailUpdate()
	{
		$params['mod']       = $this->input->post("mod");
		$params['H_IDX']     = $this->input->post("H_IDX");
		$params['S_NO']     = $this->input->post("S_NO");
		$params['CODE']     = $this->input->post("CODE");
		$params['NAME']     = $this->input->post("NAME");
		$params['USE_YN']    = $this->input->post("USE_YN");
		$params['REMARK']  = $this->input->post("REMARK");
		$params['IDX']        = $this->input->post("IDX");

		$params['INSERT_ID'] = $this->session->userdata('user_name');



		$ins = $this->mdm_model->codeDetail_update($params);

		$data['status'] = "";
		$data['msg']    = "";


		if ($ins != "") {
			$data = array(
				'status' => 'ok',
				'msg'    => '코드를 등록했습니다.'
			);
			echo json_encode($data);
		}
	}



	public function set_cocdHead_delete()
	{

		$del = $this->mdm_model->delete_cocdHead($_POST['code']);
		echo $del;
	}


	public function set_cocdDetail_delete()
	{

		$del = $this->mdm_model->delete_cocdDetail($_POST['idx']);
		echo $del;
	}


	/* head code 중복체크 */
	public function ajax_cocdHaedchk()
	{
		//중복검사
		$parem = $this->input->post("code");
		$chk = $this->mdm_model->ajax_cocdHaedchk('CODE', $parem);
		if ($chk > 0) {
			$data['state'] = "N";
			$data['msg'] = "중복된 코드입니다.";
		} else {
			$data['state'] = "Y";
			$data['msg'] = "";
		}

		echo json_encode($data);
	}


	/* head code 중복체크 */
	public function ajax_cocdDetailchk()
	{
		//중복검사
		$params['code'] = $this->input->post("code");
		$params['hidx'] = $this->input->post("hidx");
		$chk = $this->mdm_model->ajax_cocdDetailchk('CODE', $params);
		if ($chk > 0) {
			$data['state'] = "N";
			$data['msg'] = "중복된 코드입니다.";
		} else {
			$data['state'] = "Y";
			$data['msg'] = "";
		}

		echo json_encode($data);
	}
}
