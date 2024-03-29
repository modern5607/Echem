<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ORDPLN extends CI_Controller
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
		$this->load->model(array('mif_model', 'sys_model', 'ordpln_model','offday_model'));

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

	//---------------------------------------------------------//
	//---------------------------------------------------------//
	

	//일별 근태 관리

public function month()
	{
		$data['title']='일별 근태 관리';
		return $this->load->view('main50', $data);
	}

	public function head_month()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자

		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }




		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->offday_model->head_month($params, $pageNum, $config['per_page']);

		// echo var_dump($data['list']);
		$this->data['cnt'] = 0;//$this->ordpln_model->head__cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/ordpln/head_month', $data);
	}
//------------------------------------------------------------------//
//------------------------디테일 ----------------------------------//

////----------------------등록---------------------------------//
	public function detail_month()
	{
		$data['str'] = array(); //검색어관련
		// $data['str']['vdate'] = $this->input->post('vdate'); //월차등록일자
		$data['str']['idx'] = $this->input->post('idx'); //끝


		// echo $data['str']['idx'];

		$params['MEMBER_IDX'] = $data['str']['idx']; 

		// if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		// if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		// if (!empty($data['str']['idx'])) { $params['IDX'] = $data['str']['idx']; }

		$data['list']=$this->offday_model->detail_month($params);
		// echo var_dump($data['list']);
		$data['member'] = $this->offday_model->get_member();
		// echo var_dump($data['member']);
		// $data['BIZ']=$this->sys_model->biz_list('수출');
		// $data['check']=$this->ordpln_model->act_check($params);

		return $this->load->view('/ordpln/detail_month', $data);
	}
	public function offday_insert()
	{

		$params['VACATION_DATE'] = $this->input->post("VACATION_DATE");
		$params['MEMBER_IDX'] = $this->input->post("MEMBER_IDX");
		$params['REMARK'] = $this->input->post("REMARK");

		// echo var_dump($params);
    
		
		$num = $this->offday_model->offday_insert($params);

		//echo $num;
		// if ($num > 0) {
		// 	echo 1;
		// }
		// else
		// 	echo 0;

		// echo json_encode($params);
	}

	//------------------삭제------------///
	public function offday_del()
	{
		$params = $this->input->post("idx");
		$num = $this->offday_model->offday_del($params);

		if ($num > 0) {
			$params['status'] = "ok";
			$params['msg'] = "삭제되었습니다.";
		} else {
			$params['status'] = "no";
			$params['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($params);
	  
	}

	//--------------------------------------------수정------------------------//
	
	public function update_offday()
	{
		
		$params['VACATION_DATE'] = $this->input->post("VACATION_DATE");
		$params['MEMBER_IDX'] = $this->input->post("MEMBER_IDX");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IDX'] = $this->input->post("IDX");	
		//echo var_dump($params);
		$data = $this->offday_model->update_offday($params);
		
		
		// echo json_encode($params);	//json
	}

	//---------------------------------------일별근태조회 달력---------------------------------------------------------//
	
	public function monthpln()
	{
		$data['title']='일별 근태 조회';
		return $this->load->view('main100', $data);
	}
	public function ajax_monthpln()
	{
		$prefs = array(
			'start_day'    => 'sunday',
			'month_type'   => 'short',
			'day_type'     => 'short',
			'show_next_prev'  => true,
			'show_other_days' => false,
			'next_prev_url'   => base_url('ORDPLN/ajax_monthpln/')
		);

		// $year = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : date("Y", time());
		// $month = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : date("m", time());

		$year = empty($this->input->post("year")) ? date("Y", time()) : $this->input->post("year");
		$month = empty($this->input->post("month")) ? date("m", time()) : $this->input->post("month");

		$prev_month = date("Y-m", strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
		$next_month = date("Y-m", strtotime('+1 month', strtotime($year . '-' . $month . '-01')));


		$prefs['template'] = '

			{table_open}<table border="0" cellpadding="0" cellspacing="3" id="calendar">{/table_open}

			{heading_row_start}<tr class="headset">{/heading_row_start}

			{heading_previous_cell}
			<th>
				<a href="#" data-date="' . $prev_month . '" class="moveBtn btn">이전달</a>
			</th>
			{/heading_previous_cell}

			{heading_title_cell}<th colspan="{colspan}" style="font-size:18px">' . $year . "년 - " . $month . "월" . '</th>{/heading_title_cell}
			
			{heading_next_cell}
			<th>
				<a href="#" data-date="' . $next_month . '" class="moveBtn btn">다음달</a>
			</th>
			{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr class="week">{/week_row_start}
			{week_row_class_start}<td class="{week_class}">{/week_row_class_start}
			{week_day_cell}{week_day}{/week_day_cell}
			{week_row_class_end}</td>{/week_row_class_end}
			{week_row_end}</tr>{/week_row_end}

			{cal_row_start}<tr>{/cal_row_start}
			{cal_cell_start}<td>{/cal_cell_start}
			{cal_cell_start_today}<td>{/cal_cell_start_today}
			{cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

			{cal_cell_content}
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div class="cont">{content}</div>
				</div>
			{/cal_cell_content}

			{cal_cell_content_today}
				<div class="xday"  data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div class="cont">{content}</div>
				</div>
			{/cal_cell_content_today}

			{cal_cell_no_content}
			
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div id="{day}"></div>
				</div>
			
			{/cal_cell_no_content}

			{cal_cell_no_content_today}
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}"><p class="calendarText">{day}</p></div>
			{/cal_cell_no_content_today}

			{cal_cell_blank}&nbsp;{/cal_cell_blank}

			{cal_cell_other}{day}{cal_cel_other}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_cell_end_today}</td>{/cal_cell_end_today}
			{cal_cell_end_other}</td>{/cal_cell_end_other}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</table>{/table_close}
	';

		$this->load->library('calendar', $prefs);
		

		// 캘린더 내용

    
		$List = $this->offday_model->offCalendarInfo_list($year, $month);
		// echo var_dump($List);
		$contArray = array();
		if (!empty($List)) { 
			foreach ($List as $i => $row) {
				if(isset($contArray[$row->DAY]))
					$contArray[$row->DAY] .= '연차 : '.($row->NAME). '<br>';
				else
					$contArray[$row->DAY] = '연차 : '.($row->NAME). '<br>';
			}
		}else{
			$contArray='';
		}


		$data['calendar'] = $this->calendar->generate($year, $month, $contArray);

		return $this->load->view('ordpln/ajax_monthpln', $data);
	}
	public function month_form()
	{
		$data['title'] = "연차일정";
		$data['setDate'] = $this->input->post("xdate");
		$year = substr($data['setDate'], 0, 4);
		$month = substr($data['setDate'], 5, 2);
		$day = substr($data['setDate'], 8, 2);

		$data['List'] = $this->offday_model->offCalendarInfo_list($year,$month,$day);
		// echo var_dump($data['List']);

		$this->load->view('ordpln/month_form', $data);
	}

	// public function month_update()
	// {
	// 	$params['VACATION_DATE'] = $this->input->post("VACATION_DATE");
	// 	$params['MEMBER_IDX'] = $this->input->post("MEMBER_IDX");
	// 	$params['REMARK'] = $this->input->post("REMARK");
		
			
	// 	$data = $this->offday_model->calendar_up($params);

	// 	echo json_encode($data);
	// }




	


	


 	//-----------------------------------------------------------------------------------------------------------//
	//-----------------------------------------------------------------------------------------------------------//



	// 	주문등록
	public function order()
	{
		$data['title']='주문등록';
		return $this->load->view('main50', $data);
	}
	
	public function head_order()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actnm'] = trim($this->input->post('actnm')); //시작일자
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = "";
		$params['ACT_NAME'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['biz']))  $params['BIZ_IDX'] = $data['str']['biz']; 
		if (!empty($data['str']['actnm']))  $params['ACT_NAME'] = $data['str']['actnm'];
		if (!empty($data['str']['sdate']))  $params['SDATE'] = $data['str']['sdate'];
		if (!empty($data['str']['edate']))  $params['EDATE'] = $data['str']['edate'];


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->ordpln_model->head_order($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->ordpln_model->head_order_cut($params);
		$data['BIZ']=$this->sys_model->biz_list();


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/ordpln/head_order', $data);
	}


	public function detail_order()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['idx'] = $this->input->post('idx'); //끝일자

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['IDX'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['idx'])) { $params['IDX'] = $data['str']['idx']; }

		$data['list']=$this->ordpln_model->head_order($params);
		$data['BIZ']=$this->sys_model->biz_list('수출');
		$data['check']=$this->ordpln_model->act_check($params);

		$this->load->view('/ordpln/detail_order', $data);
	}

	public function order_insert()
	{
		$params['ACT_NAME'] = $this->input->post("ACTNM");
		$params['ACT_DATE'] = $this->input->post("ADATE");
		$params['QTY'] = $this->input->post("QTY");
		$params['DEL_DATE'] = $this->input->post("DDATE");
		$params['REMARK'] = $this->input->post("REMARK");
		// $params['BIZ_NAME'] = $this->input->post("BIZNM");
		$params['BIZ_IDX'] = $this->input->post("BIZ");


		$num = $this->ordpln_model->order_insert($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "주문이 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "주문 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function del_order()
	{
		$idx = $this->input->get("idx");
		$num = $this->ordpln_model->del_order($idx);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function update_order()
	{
		$params['ACT_NAME'] = $this->input->post("ACTNM");
		$params['ACT_DATE'] = $this->input->post("ADATE");
		$params['QTY'] = $this->input->post("QTY");
		$params['DEL_DATE'] = $this->input->post("DDATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['BIZ_NAME'] = $this->input->post("BIZNM");
		$params['BIZ_IDX'] = $this->input->post("BIZ");
		$params['IDX'] = $this->input->post("IDX");
			
		$data = $this->ordpln_model->update_order($params);

		echo json_encode($data);
	}

	// 주문현황
	public function ordercur()
	{
		$data['title']='주문현황';
		return $this->load->view('main100', $data);
	}
	public function ajax_ordercur()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처


		$params['ACT_NAME'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['BIZ_IDX'] = "";

		if (!empty($data['str']['actnm']))  $params['ACT_NAME'] = $data['str']['actnm'];
		if (!empty($data['str']['sdate']))  $params['SDATE'] = $data['str']['sdate']; 
		if (!empty($data['str']['edate']))  $params['EDATE'] = $data['str']['edate']; 
		if (!empty($data['str']['biz']))  $params['BIZ_IDX'] = $data['str']['biz']; 


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->ordpln_model->head_order($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->ordpln_model->head_order_cut($params);
		$data['BIZ']=$this->sys_model->biz_list();


		// echo var_dump($data['list']);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('ordpln/ajax_ordercur', $data);
	}


	// 주문대비 진행현황
	public function orderprocess()
	{
		$data['title']='주문대비 진행현황';
		return $this->load->view('main100', $data);
	}
	public function ajax_orderprocess()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = "";
		$params['ACT_NAME'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['biz']))  $params['BIZ_IDX'] = $data['str']['biz']; 
		if (!empty($data['str']['actnm']))  $params['ACT_NAME'] = $data['str']['actnm'];
		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->ordpln_model->head_order($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->ordpln_model->head_order_cut($params);
		$data['BIZ']=$this->sys_model->biz_list();


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('ordpln/ajax_orderprocess', $data);
	}



	// 생산계획 등록
	public function prodpln()
	{
		$data['title']='생산계획 등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_prodpln()
	{
		$prefs = array(
			'start_day'    => 'sunday',
			'month_type'   => 'short',
			'day_type'     => 'short',
			'show_next_prev'  => true,
			'show_other_days' => false,
			'next_prev_url'   => base_url('ORDPLN/ajax_prodpln/')
		);

		// $year = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : date("Y", time());
		// $month = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : date("m", time());

		$year = empty($this->input->post("year")) ? date("Y", time()) : $this->input->post("year");
		$month = empty($this->input->post("month")) ? date("m", time()) : $this->input->post("month");

		$prev_month = date("Y-m", strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
		$next_month = date("Y-m", strtotime('+1 month', strtotime($year . '-' . $month . '-01')));


		$prefs['template'] = '

			{table_open}<table border="0" cellpadding="0" cellspacing="3" id="calendar">{/table_open}

			{heading_row_start}<tr class="headset">{/heading_row_start}

			{heading_previous_cell}
			<th>
				<a href="#" data-date="' . $prev_month . '" class="moveBtn btn">이전달</a>
			</th>
			{/heading_previous_cell}

			{heading_title_cell}<th colspan="{colspan}" style="font-size:18px">' . $year . "년 - " . $month . "월" . '</th>{/heading_title_cell}
			
			{heading_next_cell}
			<th>
				<a href="#" data-date="' . $next_month . '" class="moveBtn btn">다음달</a>
			</th>
			{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr class="week">{/week_row_start}
			{week_row_class_start}<td class="{week_class}">{/week_row_class_start}
			{week_day_cell}{week_day}{/week_day_cell}
			{week_row_class_end}</td>{/week_row_class_end}
			{week_row_end}</tr>{/week_row_end}

			{cal_row_start}<tr>{/cal_row_start}
			{cal_cell_start}<td>{/cal_cell_start}
			{cal_cell_start_today}<td>{/cal_cell_start_today}
			{cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

			{cal_cell_content}
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div class="cont">{content}</div>
				</div>
			{/cal_cell_content}

			{cal_cell_content_today}
				<div class="xday"  data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div class="cont">{content}</div>
				</div>
			{/cal_cell_content_today}

			{cal_cell_no_content}
			
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div id="{day}"></div>
				</div>
			
			{/cal_cell_no_content}

			{cal_cell_no_content_today}
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}"><p class="calendarText">{day}</p></div>
			{/cal_cell_no_content_today}

			{cal_cell_blank}&nbsp;{/cal_cell_blank}

			{cal_cell_other}{day}{cal_cel_other}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_cell_end_today}</td>{/cal_cell_end_today}
			{cal_cell_end_other}</td>{/cal_cell_end_other}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</table>{/table_close}
	';

		$this->load->library('calendar', $prefs);
		

		// 캘린더 내용
		$List = $this->ordpln_model->calendarInfo_list($year, $month);
		if (!empty($List)) { 
			foreach ($List as $i => $row) {
				$contArray[$row->DAY] = '생산량 : '.round($row->QTY,2) . ' (T)<br> 원료 : ' . $row->REMARK  . ' <br> 제품색상 : ' . $row->REMARK1;
			}
		}else{
			$contArray='';
		}


		$data['calendar'] = $this->calendar->generate($year, $month, $contArray);

		return $this->load->view('ordpln/ajax_prodpln', $data);
	}
	public function calendar_form()
	{
		$data['title'] = "생산계획";
		$data['setDate'] = $this->input->post("xdate");
		$year = substr($data['setDate'], 0, 4);
		$month = substr($data['setDate'], 5, 2);
		$day = substr($data['setDate'], 8, 2);

		$data['List'] = $this->ordpln_model->calendarInfo_list($year,$month,$day);
		// echo var_dump($data['List']);

		$this->load->view('ordpln/calendar_form', $data);
	}

	public function calendar_update()
	{
		$params['DATE'] = $this->input->post("date");
		$params['QTY'] = $this->input->post("qty");
		$params['REMARK'] = $this->input->post("remark");
		$params['REMARK1'] = $this->input->post("remark1");
		$params['GB'] = $this->input->post("gb");
			
		$data = $this->ordpln_model->calendar_update($params);

		echo json_encode($data);
	}




	// 생산계획 조회 삭제?
	public function prodplncur()
	{
		$data['title']='생산계획 조회';
		return $this->load->view('main100', $data);
	}
	public function ajax_prodplncur()
	{
		//모델
		$data['list']=$this->ordpln_model->ajax_prodplncur();

		//뷰
		$this->load->view('ordpln/ajax_prodplncur', $data);
	}


	public function vacation()
	{
		$data['title']='근태관리';
		return $this->load->view('main100', $data);
	}

	public function ajax_vacation()
	{
		$prefs = array(
			'start_day'    => 'sunday',
			'month_type'   => 'short',
			'day_type'     => 'short',
			'show_next_prev'  => true,
			'show_other_days' => false,
			'next_prev_url'   => base_url('ORDPLN/ajax_vacation/')
		);

		// $year = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : date("Y", time());
		// $month = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : date("m", time());

		$year = empty($this->input->post("year")) ? date("Y", time()) : $this->input->post("year");
		$month = empty($this->input->post("month")) ? date("m", time()) : $this->input->post("month");

		$prev_month = date("Y-m", strtotime('-1 month', strtotime($year . '-' . $month . '-01')));
		$next_month = date("Y-m", strtotime('+1 month', strtotime($year . '-' . $month . '-01')));


		$prefs['template'] = '

			{table_open}<table border="0" cellpadding="0" cellspacing="3" id="calendar">{/table_open}

			{heading_row_start}<tr class="headset">{/heading_row_start}

			{heading_previous_cell}
			<th>
				<a href="#" data-date="' . $prev_month . '" class="moveBtn btn">이전달</a>
			</th>
			{/heading_previous_cell}

			{heading_title_cell}<th colspan="{colspan}" style="font-size:18px">' . $year . "년 - " . $month . "월" . '</th>{/heading_title_cell}
			
			{heading_next_cell}
			<th>
				<a href="#" data-date="' . $next_month . '" class="moveBtn btn">다음달</a>
			</th>
			{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr class="week">{/week_row_start}
			{week_row_class_start}<td class="{week_class}">{/week_row_class_start}
			{week_day_cell}{week_day}{/week_day_cell}
			{week_row_class_end}</td>{/week_row_class_end}
			{week_row_end}</tr>{/week_row_end}

			{cal_row_start}<tr>{/cal_row_start}
			{cal_cell_start}<td>{/cal_cell_start}
			{cal_cell_start_today}<td>{/cal_cell_start_today}
			{cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

			{cal_cell_content}
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div class="cont">{content}</div>
				</div>
			{/cal_cell_content}

			{cal_cell_content_today}
				<div class="xday"  data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div class="cont">{content}</div>
				</div>
			{/cal_cell_content_today}

			{cal_cell_no_content}
			
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}">
					<p class="calendarText">{day}</p>
					<div id="{day}"></div>
				</div>
			
			{/cal_cell_no_content}

			{cal_cell_no_content_today}
				<div class="xday" data-date="' . $year . '-' . $month . '-{day}"><p class="calendarText">{day}</p></div>
			{/cal_cell_no_content_today}

			{cal_cell_blank}&nbsp;{/cal_cell_blank}

			{cal_cell_other}{day}{cal_cel_other}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_cell_end_today}</td>{/cal_cell_end_today}
			{cal_cell_end_other}</td>{/cal_cell_end_other}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</table>{/table_close}
	';

		$this->load->library('calendar', $prefs);
		
		$List = $this->ordpln_model->ajax_vacation($year, $month);

		foreach($List as $row)
		{
			$contArray[$row->DAY] = '';
		}

		// echo var_dump($List);
		if (!empty($List)) { 
			foreach ($List as $i => $row) {
				$contArray[$row->DAY] .= $row->NAME.' : '. $row->REMARK . '<br>';
			}
		}else{
			$contArray='';
		}

		// echo var_dump($contArray);

		$data['calendar'] = $this->calendar->generate($year, $month, $contArray);

		return $this->load->view('ordpln/ajax_vacation', $data);

	}

	public function vacation_form()
	{
		$data['setDate'] = $this->input->post("xdate");
		$data['title'] = "근태관리 - ".$data['setDate'];

		$year = substr($data['setDate'], 0, 4);
		$month = substr($data['setDate'], 5, 2);
		$day = substr($data['setDate'], 8, 2);

		$data['List'] = $this->ordpln_model->ajax_vacation($year,$month,$day);
		$data['Member'] = $this->ordpln_model->get_member();

		// echo var_dump($data['List']);
		// echo var_dump($data['Member']);

		$this->load->view('ordpln/vacation_form', $data);
	}

	public function vacation_insert()
	{
		$params['MEMBER_IDX'] = $this->input->post("MEMBER_IDX");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['DATE'] = $this->input->post("DATE");
		$result = $this->ordpln_model->vacation_insert($params);

		echo $result;
	}

	public function vacation_update()
	{
		$params['IDX'] = $this->input->post("IDX");
		$params['MEMBER_IDX'] = $this->input->post("MEMBER_IDX");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['DATE'] = $this->input->post("DATE");
		$result = $this->ordpln_model->vacation_update($params);

		echo $result;
	}
	public function vacation_delete()
	{
		$params['IDX'] = $this->input->post("IDX");
		$result = $this->ordpln_model->vacation_delete($params);

		echo $result;
	}


//-------------------------------------------------------------주문등록 복사본--

	// 	주문등록
	public function order1()
	{
		$data['title']='생산등록';
		return $this->load->view('main50', $data);
	}
	
	public function head_order1()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['remark'] = trim($this->input->post('remark')); //시작일자
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['biz'] = trim($this->input->post('biz')); //거래처

		$params['BIZ_IDX'] = "";
		$params['REMARK'] = "";
		$params['SDATE'] = "";
		$params['EDATE'] = "";

		if (!empty($data['str']['biz']))  $params['BIZ_IDX'] = $data['str']['biz']; 
		if (!empty($data['str']['remark']))  $params['REMARK'] = $data['str']['remark'];
		if (!empty($data['str']['sdate']))  $params['SDATE'] = $data['str']['sdate'];
		if (!empty($data['str']['edate']))  $params['EDATE'] = $data['str']['edate'];


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->ordpln_model->head_order1($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->ordpln_model->head_order_cut1($params);
		$data['BIZ']=$this->sys_model->biz_list1();


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();


		$this->load->view('/ordpln/head_order1', $data);
	}


	public function detail_order1()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['idx'] = $this->input->post('idx'); //끝일자

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['IDX'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['idx'])) { $params['IDX'] = $data['str']['idx']; }

		$data['list']=$this->ordpln_model->head_order1($params);
		$data['BIZ']=$this->sys_model->biz_list1('수출');
		$data['check']=$this->ordpln_model->act_check1($params);

		$this->load->view('/ordpln/detail_order1', $data);
	}

	public function order_insert1()
	{
		$params['INSERT_DATE'] = $this->input->post("INSERT_DATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['BIZ_IDX'] = $this->input->post("BIZ");
		$params['T_TANK'] = $this->input->post("T_TANK");
		$params['T_SU'] = $this->input->post("T_SU");
		$params['T_JD'] = $this->input->post("T_JD");
		$params['NA2CO3'] = $this->input->post("NA2CO3");
		$params['NA2CO3_IN'] = $this->input->post("NA2CO3_IN");
		$params['PS_1'] = $this->input->post("PS_1");
		$params['PS_2'] = $this->input->post("PS_2");
		$params['PS_3'] = $this->input->post("PS_3");
		$params['OT_OUT'] = $this->input->post("OT_OUT");
		$params['OT_COL'] = $this->input->post("OT_COL");
		$params['USE_WL'] = $this->input->post("USE_WL");
		$params['REMARK1'] = $this->input->post("REMARK1");




		$num = $this->ordpln_model->order_insert1($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "주문이 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "주문 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function del_order1()
	{
		$idx = $this->input->get("idx");
		$num = $this->ordpln_model->del_order1($idx);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function update_order1()
	{
		$params['INSERT_DATE'] = $this->input->post("INSERT_DATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['BIZ_IDX'] = $this->input->post("BIZ");
		$params['T_TANK'] = $this->input->post("T_TANK");
		$params['T_SU'] = $this->input->post("T_SU");
		$params['T_JD'] = $this->input->post("T_JD");
		$params['NA2CO3'] = $this->input->post("NA2CO3");
		$params['NA2CO3_IN'] = $this->input->post("NA2CO3_IN");
		$params['PS_1'] = $this->input->post("PS_1");
		$params['PS_2'] = $this->input->post("PS_2");
		$params['PS_3'] = $this->input->post("PS_3");
		$params['OT_OUT'] = $this->input->post("OT_OUT");
		$params['OT_COL'] = $this->input->post("OT_COL");
		$params['USE_WL'] = $this->input->post("USE_WL");
		$params['REMARK1'] = $this->input->post("REMARK1");
		$params['IDX'] = $this->input->post("IDX");
			
		$data = $this->ordpln_model->update_order1($params);

		echo json_encode($data);
	}

}
