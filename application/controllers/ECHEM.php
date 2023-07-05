<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ECHEM extends CI_Controller
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
		$this->load->model(array('mif_model', 'sys_model','pur_model','echem_model', 'ordpln_model','mdm_model'));

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

		$data['str']['date'] = $this->input->post('date');
		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : '';
		$params['END_CHK'] = (isset($data['str']['endyn'])) ? $data['str']['endyn'] : '';
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//모델
		$data['list']=$this->pur_model->component_list($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->pur_model->component_list_cnt($params);
		$data['bizType'] = $this->sys_model->biz_list(); 
		// echo var_dump($data['list']);
		$data['cocd']= $this->sys_model->get_selectInfo("tch.CODE","UNIT");

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('purchase/ajax_matorder', $data);
	}

	public function component_head_insert()
	{
		$params['ACT_DATE'] = $this->input->post("ADATE");
		$params['BIZ_IDX'] = $this->input->post("BIZTYPE");
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
		$data['str']['date'] = $this->input->post('date');
		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : '';
		$params['END_CHK'] = (isset($data['str']['endyn'])) ? $data['str']['endyn'] : '';
		
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//모델
		$data['list']=$this->pur_model->component_list($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->pur_model->component_list_cnt($params);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('echem/ajax_enter', $data);
	}

	// 발주대비 입고현황
	public function orderenter()
	{
		$data['title']='제품 생산 통계';
		return $this->load->view('main100', $data);
	}

	public function ajax_orderenter()
	{
		$data['str'] = array(); //검색어관련

		$data['str']['date'] = $this->input->post('date');
		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');
		
		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : '';

		
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;
		
		//모델
		$data['list']=$this->echem_model->component_search($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->echem_model->component_search_cnt($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();
		//뷰
		$this->load->view('echem/ajax_orderenter', $data);
	}
	



	/* 원자재 입고 정보 등록 */
	// 원자재 발주등록
	public function matorder1()
	{
		$data['title']='원자재 발주등록';
		return $this->load->view('main100', $data);
	}

	public function ajax_matorder1()
	{
		$data['str'] = array(); //검색어관련

		$data['str']['date'] = $this->input->post('date');
		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : '';
		$params['END_CHK'] = (isset($data['str']['endyn'])) ? $data['str']['endyn'] : '';
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//모델
		$data['list']=$this->pur_model->component_list1($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->pur_model->component_list_cnt1($params);
		$data['bizType'] = $this->sys_model->biz_list2(); 
		$data['igType']= $this->sys_model->get_selectInfo1("tch.CODE","IGTYPE");
		// echo var_dump($data['list']);
		$data['cocd']= $this->sys_model->get_selectInfo2("tch.CODE","UNIT");

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('echem/ajax_matorder1', $data);
	}

	public function component_head_insert1()
	{
		$params['ACT_DATE'] = $this->input->post("ADATE");
		$params['BIZ_IDX'] = $this->input->post("ITEM_NAME");
		$params['QTY'] = $this->input->post("QTY");
		$params['UNIT'] = $this->input->post("UNIT");
		$params['DEL_DATE'] = $this->input->post("DDATE");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['IGTYPE'] = $this->input->post("IGTYPE");

		$num = $this->pur_model->component_head_insert1($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "실적이 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function del_component1()
	{
		$idx = $this->input->get("idx");
		$num = $this->pur_model->del_component1($idx);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	// 자재 현황[신규 등록건 기준]
	public function orderenter1()
	{
		$data['title']='발주대비 입고현황';
		return $this->load->view('main100', $data);
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
		$params['INSERT_DATE']  = $this->input->post("INSERT_DATE");
		$params['T_TANK']		= $this->input->post("T_TANK");
		$params['T_SU'] 		= $this->input->post("T_SU");
		$params['T_JD'] 		= $this->input->post("T_JD");
		$params['NA2CO3_IN']	= $this->input->post("NA2CO3_IN");
		$params['NA2CO3'] 		= $this->input->post("NA2CO3");
		$params['OT_OUT'] 		= $this->input->post("OT_OUT");
		$params['OT_COL'] 		= $this->input->post("OT_COL");
		$params['REMARK'] 		= $this->input->post("REMARK");
		$params['USE_WL'] 		= $this->input->post("USE_WL");
		$params['REMARK1'] 		= $this->input->post("REMARK1");
		$params['REMARK01'] 	= $this->input->post("REMARK01");
		$params['BIZ'] 			= $this->input->post("BIZ");
		$params['OT_NA'] 		= $this->input->post("OT_NA");
		$params['OT_WT'] 		= $this->input->post("OT_WT");
		$params['L_WL'] 		= $this->input->post("L_WL");
		$params['L_JD'] 		= $this->input->post("L_JD");
		$params['L_SU'] 		= $this->input->post("L_SU");
		$params['U_WL'] 		= $this->input->post("U_WL");
		$params['U_JD'] 		= $this->input->post("U_JD");
		$params['D_WL'] 		= $this->input->post("D_WL");
		$params['U_SU'] 		= $this->input->post("U_SU");
		$params['D_JD'] 		= $this->input->post("D_JD");
		$params['D_SU'] 		= $this->input->post("D_SU");
		$params['Z_WL'] 		= $this->input->post("Z_WL");
		$params['Z_JD'] 		= $this->input->post("Z_JD");
		$params['Z_SU'] 		= $this->input->post("Z_SU");
		$params['PS_1'] 		= $this->input->post("PS_1");
		$params['PS_2'] 		= $this->input->post("PS_2");
		$params['PS_3'] 		= $this->input->post("PS_3");


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
		
		$params['INSERT_DATE']  = $this->input->post("INSERT_DATE");
		$params['T_TANK']		= $this->input->post("T_TANK");
		$params['T_SU'] 		= $this->input->post("T_SU");
		$params['T_JD'] 		= $this->input->post("T_JD");
		$params['NA2CO3_IN']	= $this->input->post("NA2CO3_IN");
		$params['NA2CO3'] 		= $this->input->post("NA2CO3");
		$params['OT_OUT'] 		= $this->input->post("OT_OUT");
		$params['OT_COL'] 		= $this->input->post("OT_COL");
		$params['REMARK'] 		= $this->input->post("REMARK");
		$params['USE_WL'] 		= $this->input->post("USE_WL");
		$params['REMARK1'] 		= $this->input->post("REMARK1");
		$params['REMARK01'] 	= $this->input->post("REMARK01");
		$params['OT_NA'] 		= $this->input->post("OT_NA");
		$params['OT_WT'] 		= $this->input->post("OT_WT");
		$params['L_WL'] 		= $this->input->post("L_WL");
		$params['L_JD'] 		= $this->input->post("L_JD");
		$params['L_SU'] 		= $this->input->post("L_SU");
		$params['U_WL'] 		= $this->input->post("U_WL");
		$params['U_JD'] 		= $this->input->post("U_JD");
		$params['D_WL'] 		= $this->input->post("D_WL");
		$params['U_SU'] 		= $this->input->post("U_SU");
		$params['D_JD'] 		= $this->input->post("D_JD");
		$params['D_SU'] 		= $this->input->post("D_SU");
		$params['Z_WL'] 		= $this->input->post("Z_WL");
		$params['Z_JD'] 		= $this->input->post("Z_JD");
		$params['Z_SU'] 		= $this->input->post("Z_SU");
		$params['PS_1'] 		= $this->input->post("PS_1");
		$params['PS_2'] 		= $this->input->post("PS_2");
		$params['PS_3'] 		= $this->input->post("PS_3");
		$params['IDX'] 		= $this->input->post("IDX");
			 
		
		$data = $this->ordpln_model->update_order1($params);

		echo json_encode($data);
	}


	// 생산계획 등록
	public function prodpln()
	{
		$data['title']='월별 생산일보';
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
				$contArray[$row->DAY] = '생산량 : '.round($row->QTY,2) . '(Kg)<br> 원료 : ' . $row->REMARK  . ' <br> 제품색상 : ' . $row->REMARK1;
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


	// 품목등록
	public function items()
	{
		$data['title'] = '자재 재고 현황';
		$this->load->view('main100', $data);
	}

	public function ajax_items()
	{
		$data['str']['ITEM_NAME'] = $this->input->post("ITEM_NAME");
		$data['str']['USEYN'] = $this->input->post("USEYN");

		$params['ITEM_NAME'] = isset($data['str']['ITEM_NAME']) ? $data['str']['ITEM_NAME'] : "";
		$params['USEYN'] = isset($data['str']['USEYN']) ? $data['str']['USEYN'] : "";


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
		$data['list'] = $this->mdm_model->ajax_items($params, $start, $config['per_page']);
		$this->data['cut'] = $this->mdm_model->ajax_items_cut($params);
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
		$this->load->view('echem/ajax_items', $data);
	}

	public function items_form()
	{
		$data['mode'] = $this->input->post("mode");
		$data['idx'] = $this->input->post("idx");


		$data['UNIT'] = $this->mdm_model->get_selectInfo("tch.CODE", "UNIT");
		// echo var_dump($data['UNIT']);

		if ($data['mode'] == "mod") {
			$data['List'] = $this->mdm_model->get_items($data['idx']);

			// echo var_dump($data['List']);
		}


		return $this->load->view('echem/items_form', $data);
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



		if ($data['mode'] == "add")
			$data['result'] = $this->mdm_model->set_item($params);
		else if ($data['mode'] == "mod")
			$data['result'] = $this->mdm_model->update_item($params);

		// echo var_dump($data);
		echo $data['result'];
	}

	/*공백*/
	public function chart1()
	{
		$data['str']['sdate'] = $this->input->get('sdate');
		$data['str']['edate'] = $this->input->get('edate');

		$params['SDATE'] = date("Y-m-d", strtotime("-1 month", time()));
		$params['EDATE'] = date("Y-m-d");
		$params['CHART'] = 1;

		$data['qstr'] = "?P";
		if(!empty($data['str']['sdate'])){
			$params['SDATE'] = $data['str']['sdate'];
			$data['qstr'] .= "&sdate=".$data['str']['sdate'];

		}
		if(!empty($data['str']['edate'])){
			$params['EDATE'] = $data['str']['edate'];
			$data['qstr'] .= "&edate=".$data['str']['edate'];

		}
		$start = 0;
		$config['per_page'] = 9999;

		$data['title'] = "기간별 생산 현황";
		$data['List']   = $this->echem_model->equip_chart($params, $start, $config['per_page']);
		$this->data['cnt'] = $this->echem_model->equip_cut($params);
		$data['mean'] = $this->echem_model->equip_mean($params);
		
		$this->load->view('/echem/chart1',$data);
	}


	/* 원자재 입고 정보 등록 */
	// 원자재 발주등록
	public function matorder2()
	{
		$data['title']='제품 출고정보';
		return $this->load->view('main100', $data);
	}

	public function ajax_matorder2()
	{
		$data['str'] = array(); //검색어관련

		$data['str']['date'] = $this->input->post('date');
		$data['str']['sdate'] = $this->input->post('sdate');
		$data['str']['edate'] = $this->input->post('edate');

		$params['DATE'] = (isset($data['str']['date'])) ? $data['str']['date'] : '';
		$params['SDATE'] = (isset($data['str']['sdate'])) ? $data['str']['sdate'] : '';
		$params['EDATE'] = (isset($data['str']['edate'])) ? $data['str']['edate'] : '';
		$params['END_CHK'] = (isset($data['str']['endyn'])) ? $data['str']['endyn'] : '';
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//모델
		$data['list']=$this->echem_model->component_list2($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->echem_model->component_list_cnt2($params);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('echem/ajax_matorder2', $data);
	}


	public function component_head_insert2()
	{
		$params['INSERT_DATE'] = $this->input->post("INSERT_DATE");
		$params['IGGBN'] = $this->input->post("IGGBN");
		$params['STOCK'] = $this->input->post("STOCK");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['CUST'] = $this->input->post("CUST");
		$params['COL1'] = $this->input->post("COL1");
		$params['UNIT'] = $this->input->post("UNIT");

		$num = $this->echem_model->component_head_insert2($params);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "실적이 등록되었습니다.";
		} else {
			$data['status'] = "";
			$data['msg'] = "실적 등록에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}
	public function del_component2()
	{
		
		$idx = $this->input->get("idx");
		$stock = $this->input->get("stock");
		
		$num = $this->echem_model->del_component2($idx,$stock);

		if ($num > 0) {
			$data['status'] = "ok";
			$data['msg'] = "삭제되었습니다.";
		} else {
			$data['status'] = "no";
			$data['msg'] = "삭제에 실패했습니다. 관리자에게 문의하세요";
		}

		echo json_encode($data);
	}


	// 생산 모니터링 - 태블릿
	public function tapact()
	{
		$data['title']='생산등록';
		return $this->load->view('main30', $data);
	}
	public function head_tapact()
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
		
		//모델
		//$data['list']=$this->ordpln_model->head_order1($params);
		//$data['list'] = $this->echem_model->ajax_tapact();

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('echem/ajax_tapact', $data);
	}

	
	public function detail_tapact()
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

}
