<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prod_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');

	}



}
