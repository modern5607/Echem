<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PLN extends CI_Controller
{

	public $data;

	public function __construct()
	{
		parent::__construct();

		$this->data['pos'] = $this->uri->segment(1);
		$this->data['subpos'] = $this->uri->segment(2);
		$this->data['userLevel'] = $this->session->userdata('user_level');

		$this->load->model(array('sys_model', 'pln_model','mdm_model'));

		$this->data['siteTitle'] = $this->config->item('site_title');
		$this->data['menuLevel'] = $this->sys_model->menu_level();
		$user_id = $this->session->userdata('user_id');
		$this->data['userName'] = $this->session->userdata('user_name');
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

	//수주정보등록 빈창
	public function order($idx = "")
	{
		$data['title'] = "수주정보등록";
		return $this->load->view('main100', $data);
	}

	//수주정보등록 내용
	public function ajax_order()
	{
		$data['str'] = array();
		$data['str']['spodate'] = $this->input->post('spodate');
		$data['str']['epodate'] = $this->input->post('epodate');

		$params['SPODATE'] = "";
		$params['EPODATE'] = "";

		if (!empty($data['str']['spodate'])) 
			$params['SPODATE'] = $data['str']['spodate'];
		if (!empty($data['str']['epodate'])) 
			$params['EPODATE'] = $data['str']['epodate'];
		
		
		
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

		$data['List'] = $this->pln_model->ajax_act_list($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->pln_model->ajax_act_cut($params);
		
		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();
		
		return $this->load->view('pln/ajax_order',$data);
	}

	//수주정보등록 파일등록
	public function order_form()
	{
		return $this->load->view('pln/order_form');
	}

	public function order_exUp()
	{

		$this->load->library('PHPExcel');
		$objPHPExcel = new PHPExcel();

		$filepath = $_FILES['xfile']['tmp_name'];
		$startRow = $this->input->post("rownum");
		$table    = "T_ACTTEMP";

		$data['table'] = $table;
		$data['filename'] = $_FILES['xfile']['name'];

		$this->load->dbforge();


		$filetype = PHPExcel_IOFactory::identify($filepath);
		$reader = PHPExcel_IOFactory::createReader($filetype);
		$php_excel = $reader->load($filepath);

		$sheet = $php_excel->getSheet(0);           // 첫번째 시트
		$maxRow = $sheet->getHighestRow();          // 마지막 라인
		$maxColumn = $sheet->getHighestColumn();    // 마지막 칼럼

		// echo "마지막 라인: ".$maxRow;

		$maxCol_num = PHPExcel_Cell::columnIndexFromString($maxColumn);

		$target = "A" . "{$startRow}" . ":" . "$maxColumn" . "$maxRow";

		$lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);

		

		$this->pln_model->acttemp_del();

		// 라인수 만큼 루프
		foreach ($lines as $key => $line) {


			if(empty($line[0])&&empty($line[1])&&empty($line[2]))
				continue;

			$col = 0;

			for ($i = 0; $i < count($line); $i++) {


				//날짜형 cell은 변환해서 업로드
				if ($i == 15 || $i == 16 || $i == 17 || $i == 18 || $i == 19 || $i == 21 || $i == 22 || $i == 24 || $i == 25 || $i == 26 || $i == 27 || $i == 29 || $i == 30 || $i == 31 || $i == 32 || $i == 33 || $i == 34) 
				{
					echo "i : " . $i . " date : " . (empty($line[$col]) || $line[$col] == '') ? '' : $line[$col] . " 변환후 날짜 : " . date("Y-m-d", ($line[$col] - 25569) * 86400) . "<br>";
					$item[$i] = (empty($line[$col]) || $line[$col] == '') ? NULL : date("Y-m-d", ($line[$col] - 25569) * 86400);
					$col++;
				} 
				else if ($i == 39)	//전화번호 정규식
				{
					$tel = preg_replace("/[^0-9]*/s", "", $line[$col]); //숫자이외 제거

					if (substr($tel,0,2) == '02')
						$item[$i] = preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
					else if (substr($tel, 0, 2) == '8' && substr($tel, 0, 2) == '15' || substr($tel, 0, 2) == '16' ||  substr($tel, 0, 2) == '18')
						$item[$i] = preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $tel);
					//지능망 번호이면
					else
						$item[$i] = preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
					//핸드폰번호만 이용한다면 이것만잇어도됨

					$col++;
				}
				else
				{
					$trim_string = trim($line[$col]);

					$item[$i] = ($trim_string == '-') ? '' : $trim_string;
					$col++;
				}
			}

			$data['item'] = $item;
			// echo var_dump($data['item']);
			$this->pln_model->set_temp_data($data);
		}
		redirect(base_url('PLN/acttemp'));
	}

	//수주업로드 후 임시데이터 페이지
	public function acttemp()
	{
		$data['title'] = "수주등록 등록완료";
		$data['List'] = $this->pln_model->acttemp_list();
		// echo var_dump($data['List']);
		$this->load->view('/pln/temp', $data);
	}

	//실행계획 등록 빈창
	public function plan($idx = "")
	{
		$data['title'] = "생산계획등록";
		return $this->load->view('main100', $data);
	}

	//실행계획등록 내용
	public function ajax_plan()
	{
		$data['str'] = array();
		$data['str']['mpdate'] = $this->input->post('mpdate');


		if (!empty($data['str']['mpdate'])) 
			$params['PLAN_DA'] = $data['str']['mpdate'];
		else
			$params['PLAN_DA']= date("Y-m-d",time());


		$data['List'] = $this->pln_model->ajax_plan_list($params);

		$data['SJGB']   = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");
		// echo var_dump($data['SJGB']);
		// echo var_dump($data['List']);
		return $this->load->view('pln/ajax_plan',$data);
	}

	public function plan_form()
	{
		$data['title'] = '생산계획등록';
		$data['str'] = array();
		$data['str']['type'] = $this->input->post('type');
		$data['str']['date'] = $this->input->post('date');
		$data['str']['desc'] = $this->input->post('desc');

		$params['TYPE']='';
		$params['DATE']='';
		$params['DESC']='';

		if (!empty($data['str']['date'])) 
			$params['DATE'] = $data['str']['date'];
		if (!empty($data['str']['desc'])) 
			$params['DESC'] = $data['str']['desc'];

		$params['TYPE'] = $data['str']['type'];


		$data['List'] = $this->pln_model->plan_form_list($params);
		// echo var_dump($data['List']);

		return $this->load->view('pln/plan_form',$data);
	}

	public function plan_form2()
	{
		$year = $this->input->post("year");
		$week = $this->input->post("week");


		$dto = new DateTime();
		$dto->setISODate($year, $week,0);
		$ret['week_start'] = $dto->format('Y-m-d');
		$dto->modify('+6 days');
		$ret['week_end'] = $dto->format('Y-m-d');
		// echo $ret['week_start']." ~ ".$ret['week_end'];

		$params['SDATE'] = $ret['week_start'];
		$params['EDATE'] = $ret['week_end'];

		$data['List'] = $this->pln_model->plan_form_list2($params);
		
		echo json_encode($data['List']);

	}
	
	public function plan_up()
	{
		//클라이언트에서 String 유형으로 받았지만 내부구조는 JSON이다.
        $data = $this->input->post('data');

        //JSON 문자열을 받아서 PHP OBJECT 또는 연관 배열로 변환 한다.
        //두번째 인자가 TRUE이면 연관 배열로 변환한다.
        $json_object = json_decode( $data, false );
        // echo print_r( $json_object);
		for($i=0;$i<count($json_object);$i++)
		{
			$params['POR_NO'] = $json_object[$i]->porno;
			$params['POR_SEQ'] = $json_object[$i]->seq;
			$params['DATE'] = $json_object[$i]->date;

			$this->pln_model->plan_up($params);
		}
	}


	//수주 현황
	public function ordc($idx = "")
	{
		$data['title'] = "수주 현황";
		return $this->load->view('main100', $data);
	}

	public function ajax_ordc()
	{
		$data['str'] = array();
		$data['str']['sjgb'] = $this->input->post('sjgb');
		$data['str']['porno'] = $this->input->post('porno');
		$data['str']['sdate'] = empty($this->input->post('sdate')) ? date("Y-m-d", strtotime("-1 month")) : $this->input->post('sdate');
		$data['str']['edate'] = empty($this->input->post('edate')) ? date("Y-m-d") : $this->input->post('edate');

		$params['PORNO'] = "";
		$params['SJGB'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['porno'])) {
			$params['PORNO'] = $data['str']['porno'];
		}
		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}

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

		$data['SJ_GB'] = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");


		$data['List']   = $this->pln_model->ordc_list($params, $start, $config['per_page']);
		// echo var_dump($data['List']);
		$this->data['cnt'] = $this->pln_model->ordc_cut($params);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/pln/ajax_ordc', $data);
	}

	public function ordw($idx = "")
	{
		$data['title'] = "납기별 수주현황(주간)";
		return $this->load->view('main100', $data);
	}

	public function ajax_ordw()
	{
		$data['str'] = array(); 
		$data['str']['sjgb'] = $this->input->post('sjgb'); 
		$data['str']['week'] = empty($this->input->post('week'))?date("Y-m-d"):$this->input->post('week'); 
	
		$splits = explode('~',$data['str']['week']);

		$data['str']['sdate'] = $splits[0];
		$data['str']['edate'] = (!empty($splits[1]))?$splits[1]:'';

		$params['SJGB'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['WEEK'] = "";

		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}
		if (!empty($data['str']['week'])) {
			$params['WEEK'] = $data['str']['week'];
		}

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

		$data['List']   = $this->pln_model->ordw_list($params, $start, $config['per_page']); 
		$data['SJ_GB'] = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");
		
		$this->data['cnt'] = $this->pln_model->ordw_cut($params);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/pln/ajax_ordw',$data);
	}
	//납기별 수주 현황(월간)
	public function ordm($idx="")
	{
		$data['title'] = "납기별 수주현황(월간)";
		return $this->load->view('main100', $data);
	}

	public function ajax_ordm()
	{
		$data['str'] = array(); 
		$data['str']['sjgb'] = $this->input->post('sjgb'); 
		$data['str']['month'] = empty($this->input->post('month'))?date("Y-m"):$this->input->post('month'); 

		$params['SJGB'] = "";
		$params['MONTH'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['month'])) {
			$params['MONTH'] = $data['str']['month'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}
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


		$data['List']   = $this->pln_model->ordm_list($params, $start, $config['per_page']);
		$data['SJ_GB'] = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");


		$this->data['cnt'] = $this->pln_model->ordm_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		$this->load->view('/pln/ajax_ordm', $data);
	}



	public function delay($idx = "")
	{
		$data['title'] = "납기 지연 LIST";
		return $this->load->view('main100', $data);
	}
	public function ajax_delay()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['porno'] = $this->input->post('porno'); //por_no
		$data['str']['sjgb'] = $this->input->post('sjgb'); //수주구분
		$data['str']['sdate'] = empty($this->input->post('sdate')) ? date("Y-01-01") : $this->input->post('sdate'); //납기 시작
		$data['str']['edate'] = empty($this->input->post('edate')) ? date("Y-m-d") : $this->input->post('edate'); //납기 끝

		$params['PORNO'] = "";
		$params['SJGB'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['porno'])) {
			$params['PORNO'] = $data['str']['porno'];
		}
		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['List1'] = $this->pln_model->delay_list1($params);
		$data['List2'] = $this->pln_model->delay_list2($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->pln_model->delay_cut($params);
		
		$data['SJGB']   = $this->mdm_model->get_selectInfo("tch.CODE","SJGB");


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/pln/ajax_delay', $data);
	}

	public function change($idx = "")
	{
		$data['title'] = "납기 변경 이력 현황";
		return $this->load->view('main100', $data);
	}
	public function ajax_change()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['porno'] = $this->input->post('porno'); //por_no
		$data['str']['sjgb'] = $this->input->post('sjgb'); //수주구분
		$data['str']['sdate'] = $this->input->post('sdate'); //month
		$data['str']['edate'] = $this->input->post('edate'); //month

		$params['PORNO'] = "";
		$params['SJGB'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['porno'])) {
			$params['PORNO'] = $data['str']['porno'];
		}
		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['List'] = $this->pln_model->change_list($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->pln_model->change_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/pln/ajax_change', $data);
	}


	public function alter($idx = "")
	{
		$data['title'] = "납기 변경 내역 조회";
		return $this->load->view('main30', $data);
	}
	public function head_alter()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sjgb'] = $this->input->post('sjgb'); //수주구분
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자

		$params['SJGB'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['List'] = $this->pln_model->alter_list($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->pln_model->alter_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/pln/head_alter', $data);
	}
	public function detail_alter()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sjgb'] = $this->input->post('sjgb'); //수주구분
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['idx'] = $this->input->post('idx'); //HEAD IDX

		$params['SJGB'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['IDX'] = "undefined";

		if (!empty($data['str']['sjgb'])) {
			$params['SJGB'] = $data['str']['sjgb'];
		}
		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}
		if (!empty($data['str']['idx'])) {
			$params['IDX'] = $data['str']['idx'];
		}


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['List'] = $this->pln_model->alter_list($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->pln_model->alter_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/pln/detail_alter', $data);
	}


	public function action($idx = "")
	{
		$data['title'] = "월간 실행 계획 현황";
		return $this->load->view('main100', $data);
	}
	public function ajax_action()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = empty($this->input->post('sdate'))? date("Y-01") :$this->input->post('sdate'); 
		$data['str']['edate'] = empty($this->input->post('edate'))? date("Y-m") : $this->input->post('edate'); 
		$data['str']['type'] = empty($this->input->post('type'))?"num":$this->input->post('type'); 

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['TYPE'] = "";

		if (!empty($data['str']['sdate'])) {
			$params['SDATE'] = $data['str']['sdate'];
		}
		if (!empty($data['str']['edate'])) {
			$params['EDATE'] = $data['str']['edate'];
		}
		if (!empty($data['str']['type'])) {
			$params['TYPE'] = $data['str']['type'];
		}


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['List'] = $this->pln_model->action_list($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->pln_model->action_cut($params);

		// echo var_dump($data['List']);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/pln/ajax_action', $data);
	}


	public function work($idx = "")
	{
		$data['title'] = "월간 작업 진행 현황";
		return $this->load->view('main100', $data);
	}
	public function ajax_work()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = empty($this->input->post('sdate'))?date("Y-m"):$this->input->post('sdate'); //month

		$params['DATE'] = "";

		if (!empty($data['str']['sdate'])) {
			$params['DATE'] = $data['str']['sdate'];
		}

		$data['List1'] = $this->pln_model->work_list1($params);
		$data['List2'] = $this->pln_model->work_list2($params);

		$this->load->view('/pln/ajax_work', $data);
	}


	public function result($idx = "")
	{
		$data['title'] = "월간 계획 대비 실적";
		return $this->load->view('main100', $data);
	}
	public function ajax_result()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = empty($this->input->post('sdate'))?date("Y-m"):$this->input->post('sdate'); //month

		$params['DATE'] = "";

		if (!empty($data['str']['sdate'])) {
			$params['DATE'] = $data['str']['sdate'];
		}

		//list
		$data['List1'] = $this->pln_model->result_list1($params);
		$data['List2'] = $this->pln_model->result_list2($params);
		
		$this->load->view('/pln/ajax_result', $data);
	}
}
