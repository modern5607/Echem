<?php
defined('BASEPATH') or exit('No direct script access allowed');

class STOCK extends CI_Controller
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
		$this->load->model(array('mif_model', 'sys_model','stock_model'));

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


	// 포장등록
	public function package()
	{
		$data['title']='포장등록';
		return $this->load->view('main50', $data);
	}
	public function head_package()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['package'] = $this->input->post('package'); //포장여부
		$data['str']['date'] = $this->input->post('date');

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['ACT_NAME'] = "";
		$params['PACKAGE'] = "";
		$params['DATE'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['actnm'])) { $params['ACT_NAME'] = $data['str']['actnm']; }
		if (!empty($data['str']['package'])) { $params['PACKAGE'] = $data['str']['package']; }
		if (!empty($data['str']['date'])) { $params['DATE'] = $data['str']['date']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->stock_model->ajax_package($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->stock_model->package_cut($params);


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('stock/head_package', $data);
	}
	public function detail_package()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['idx'] = $this->input->post('idx'); //끝일자

		$params['IDX'] = "R";

		if (!empty($data['str']['idx'])) { $params['IDX'] = $data['str']['idx']; }

		$data['list']=$this->stock_model->ajax_package($params);

		//뷰
		$this->load->view('stock/detail_package', $data);
	}
	public function update_package()
	{
		$params['IDX'] = $this->input->post('idx'); 
		$data['result'] = $this->stock_model->update_package($params);
		echo $data['result'];
	}


	// 재고내역(포장+탱크)
	public function stockcur()
	{
		$data['title']='재고내역(포장+탱크)';
		return $this->load->view('main100', $data);
	}
	public function ajax_stockcur()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['itemnm'] = $this->input->post('itemnm'); //품목명
		$data['str']['spec'] = $this->input->post('spec'); //구분
		$data['str']['kind'] = $this->input->post('kind'); //입출고

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['ITEM_NAME'] = "";
		$params['SPEC'] = "";
		$params['KIND'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['itemnm'])) { $params['ITEM_NAME'] = $data['str']['itemnm']; }
		if (!empty($data['str']['spec'])) { $params['SPEC'] = $data['str']['spec']; }
		if (!empty($data['str']['kind'])) { $params['KIND'] = $data['str']['kind']; }
		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//모델
		$data['list']=$this->stock_model->ajax_stockcur($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->stock_model->ajax_stockcur_cnt($params);

		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('stock/ajax_stockcur', $data);
	}



	// 재고조정
	public function stockchange()
	{
		$data['title']='재고조정';
		return $this->load->view('main100', $data);
	}
	public function ajax_stockchange()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['itemnm'] = $this->input->post('itemnm'); //품목명
		$data['str']['spec'] = $this->input->post('spec'); //구분

		$params['SPEC'] = "";
		$params['ITEM_NAME'] = "";

		if (!empty($data['str']['spec'])) { $params['SPEC'] = $data['str']['spec']; }
		if (!empty($data['str']['itemnm'])) { $params['ITEM_NAME'] = $data['str']['itemnm']; }

		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		
		//모델
		$data['list']=$this->stock_model->ajax_stockchange($params, $pageNum, $config['per_page']);
		$this->data['cnt']=$this->stock_model->ajax_stockchange_cnt($params);
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
		$this->load->view('stock/ajax_stockchange', $data);
	}
	public function stock_update()
	{
		$params['IDX'] = $this->input->post("IDX");
		$params['KIND'] = $this->input->post("KIND");
		$params['QTY'] = $this->input->post("QTY");
		$params['DATE'] = $this->input->post("DATE");
		$params['REMARK'] = $this->input->post("REMARK");

		if($this->input->post("BIZ") == ""){
			$params['BIZ'] = '재고조정';
		}else{
			$params['BIZ'] = $this->input->post("BIZ");
		}
			
		$data = $this->stock_model->stock_update($params);

		echo json_encode($data);
	}



	// 출고등록
	public function release()
	{
		$data['title']='출고등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_release()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['biz'] = $this->input->post('biz'); //거래처

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['ACT_NAME'] = "";
		$params['ENDYN'] = "N";
		$params['BIZ'] = "";
		$params['LIST'] = "Y";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['actnm'])) { $params['ACT_NAME'] = $data['str']['actnm']; }
		if (!empty($data['str']['biz'])) { $params['BIZ'] = $data['str']['biz']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->stock_model->ajax_act($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->stock_model->act_cut($params);
		$data['SHIP']=$this->sys_model->get_selectInfo("tch.CODE","SHIP");
		$data['BIZ']=$this->sys_model->biz_list('EXPORT');


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('stock/ajax_release', $data);
	}
	public function release_update()
	{
		$params['IDX'] = $this->input->post("IDX");
		$params['SHIP'] = $this->input->post("SHIP");
		$params['EDATE'] = $this->input->post("EDATE");
		$params['BQTY'] = $this->input->post("BQTY");
		$params['REMARK'] = $this->input->post("REMARK");
		$params['CLAIM'] = $this->input->post("CLAIM");
		$params['CDATE'] = $this->input->post("CDATE");
			
		$data = $this->stock_model->release_update($params);

		echo json_encode($data);
	}



	// 기간별/업체별 출고내역
	public function dbrelease()
	{
		$data['title']='기간별/업체별 출고내역';
		return $this->load->view('main100', $data);
	}
	public function ajax_dbrelease()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['biz'] = $this->input->post('biz'); //거래처

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['ACT_NAME'] = "";
		$params['ENDYN'] = "Y";
		$params['BIZ'] = "";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['actnm'])) { $params['ACT_NAME'] = $data['str']['actnm']; }
		if (!empty($data['str']['biz'])) { $params['BIZ'] = $data['str']['biz']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->stock_model->ajax_act($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->stock_model->act_cut($params);
		$data['SHIP']=$this->sys_model->get_selectInfo("tch.CODE","SHIP");
		$data['BIZ']=$this->sys_model->biz_list('EXPORT');


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('stock/ajax_dbrelease', $data);
	}



	// 클래임 등록
	public function claim()
	{
		$data['title']='클래임 등록';
		return $this->load->view('main100', $data);
	}
	public function ajax_claim()
	{
		$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['biz'] = $this->input->post('biz'); //거래처

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['ACT_NAME'] = "";
		$params['ENDYN'] = "Y";
		$params['BIZ'] = "";
		$params['CLAIM'] = "1";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['actnm'])) { $params['ACT_NAME'] = $data['str']['actnm']; }
		if (!empty($data['str']['biz'])) { $params['BIZ'] = $data['str']['biz']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 15;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->stock_model->ajax_act($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->stock_model->act_cut($params);
		$data['SHIP']=$this->sys_model->get_selectInfo("tch.CODE","SHIP");
		$data['BIZ']=$this->sys_model->biz_list('EXPORT');


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('stock/ajax_claim', $data);
	}



	// 클래임 내역 조회
	public function claimcur()
	{
		$data['title']='클래임 내역 조회';
		return $this->load->view('main100', $data);
	}
	public function ajax_claimcur()
	{
				$data['str'] = array(); //검색어관련
		$data['str']['sdate'] = $this->input->post('sdate'); //시작일자
		$data['str']['edate'] = $this->input->post('edate'); //끝일자
		$data['str']['actnm'] = trim($this->input->post('actnm')); //수주명
		$data['str']['biz'] = $this->input->post('biz'); //거래처

		$params['SDATE'] = "";
		$params['EDATE'] = "";
		$params['ACT_NAME'] = "";
		$params['ENDYN'] = "Y";
		$params['BIZ'] = "";
		$params['CLAIM'] = "2";

		if (!empty($data['str']['sdate'])) { $params['SDATE'] = $data['str']['sdate']; }
		if (!empty($data['str']['edate'])) { $params['EDATE'] = $data['str']['edate']; }
		if (!empty($data['str']['actnm'])) { $params['ACT_NAME'] = $data['str']['actnm']; }
		if (!empty($data['str']['biz'])) { $params['BIZ'] = $data['str']['biz']; }


		$data['perpage'] = ($this->input->post('perpage') != "") ? $this->input->post('perpage') : 20;
		//PAGINATION
		$config['per_page'] = $data['perpage'];
		$config['page_query_string'] = true;
		$config['query_string_segment'] = "pageNum";
		$config['reuse_query_string'] = TRUE;
		$pageNum = $this->input->post('pageNum') > '' ? $this->input->post('pageNum') : 0;
		$data['pageNum'] =  $pageNum;

		//list
		$data['list']=$this->stock_model->ajax_act($params, $pageNum, $config['per_page']);
		$this->data['cnt'] = $this->stock_model->act_cut($params);
		$data['SHIP']=$this->sys_model->get_selectInfo("tch.CODE","SHIP");
		$data['BIZ']=$this->sys_model->biz_list('EXPORT');


		/* pagenation start */
		$this->load->library("pagination");
		$config['base_url'] = base_url(uri_string());
		$config['total_rows'] = $this->data['cnt'];
		$config['full_tag_open'] = "<div>";
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config);
		$this->data['pagenation'] = $this->pagination->create_links();

		//뷰
		$this->load->view('stock/ajax_claimcur', $data);
	}
}
