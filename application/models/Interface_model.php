<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Interface_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Seoul');
	}

	public function head_interface($params, $start = 0, $limit = 20)
	{
		$where = '';
		if ((!empty($params['SDATE']) && $params['SDATE'] != "") && (!empty($params['EDATE']) && $params['EDATE'] != "")) {
			$where .= "AND START_DATE BETWEEN '{$params['SDATE']} 00:00:00' AND '{$params['EDATE']} 23:59:59'";
		}

		$sql = <<<SQL
				SELECT
					IDX,DATE_FORMAT(START_DATE,"%Y-%m-%d") AS START_DATE,COUNT(*) AS BATCH_COUNT
				FROM
					T_BATCH
				WHERE
				1
				{$where}
				GROUP BY DATE_FORMAT(START_DATE,"%Y-%m-%d")
				ORDER BY START_DATE DESC
				LIMIT {$start},{$limit}
	SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function head_interface_select($params)
	{
		$sql=<<<SQL
		SELECT IDX, START_DATE,FINISH_DATE
		FROM T_BATCH
		WHERE START_DATE BETWEEN '{$params['DATE']} 00:00:00' AND '{$params['DATE']} 23:59:59'
		ORDER BY START_DATE DESC
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function detail_interface($params)
	{

		$sql=<<<SQL
			SELECT
			case 
			when ROUND((AVG(MA_LEVEL) - 157)*0.625,0) < 0 then 0
			when ROUND((AVG(MA_LEVEL) - 157)*0.625,0) between 60 and 63 then 60
			when ROUND((AVG(MA_LEVEL) - 157)*0.625,0) between 64 and 67 then 63
			when ROUND((AVG(MA_LEVEL) - 157)*0.625,0) between 68 and 70 then 65
			when ROUND((AVG(MA_LEVEL) - 157)*0.625,0) between 71 and 74 then 65
			when ROUND((AVG(MA_LEVEL) - 157)*0.625,0) > 75 then 75
        	else ROUND((AVG(MA_LEVEL) - 157)*0.625,0)
        		END AS LEVEL,AVG(MA_TEMP) AS TEMP,AVG(MA_PH) AS PH, AVG(MA_EC) AS CL,AVG(MA_PRESS) AS PRESS,INSERT_DATE,
				DATE_FORMAT(MAX(INSERT_DATE),"%Y") AS Y,
				DATE_FORMAT(MAX(INSERT_DATE),"%m") AS M,
				DATE_FORMAT(MAX(INSERT_DATE),"%d") AS D,
				DATE_FORMAT(MAX(INSERT_DATE),"%H") AS H,
				DATE_FORMAT(MAX(INSERT_DATE),"%i") AS I,
				DATE_FORMAT(MAX(INSERT_DATE),"%s") AS S 
			FROM
				T_MODBUS
			WHERE
				SLAVE = '{$params['TANK']}' 
				AND INSERT_DATE BETWEEN "{$params['SDATE']}" 
				AND "{$params['EDATE']}"
				GROUP BY SUBSTR(INSERT_DATE,1,15)
			ORDER BY
				INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}

	public function detail_interface2($params)
	{

		$sql=<<<SQL
			SELECT
			case 
        when (AVG(MA_LEVEL) * 3) < 60 then (AVG(MA_LEVEL) * 3)
        when (AVG(MA_LEVEL) * 3) > 60 then 60
        when (AVG(MA_LEVEL) * 3) < 0 then 0
        else (AVG(MA_LEVEL) * 3)  END  AS LEVEL,
						AVG(MA_TEMP) AS TEMP,AVG(MA_PH) AS PH, AVG(MA_EC) AS CL,AVG(MA_PRESS) AS PRESS,INSERT_DATE,
				DATE_FORMAT(MAX(INSERT_DATE),"%Y") AS Y,
				DATE_FORMAT(MAX(INSERT_DATE),"%m") AS M,
				DATE_FORMAT(MAX(INSERT_DATE),"%d") AS D,
				DATE_FORMAT(MAX(INSERT_DATE),"%H") AS H,
				DATE_FORMAT(MAX(INSERT_DATE),"%i") AS I,
				DATE_FORMAT(MAX(INSERT_DATE),"%s") AS S 
			FROM
				T_MODBUS
			WHERE
				SLAVE = '{$params['TANK']}' 
				AND INSERT_DATE BETWEEN "{$params['SDATE']}" 
				AND "{$params['EDATE']}" 
				GROUP BY SUBSTR(INSERT_DATE,1,15)
			ORDER BY
				INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);
		 //echo $this->db->last_query();
		return $query->result();
	}	

	public function detail_interface3($params)
	{

		$sql=<<<SQL
			SELECT
				CASE 
				when AVG(MA_LEVEL) < 429 Then  0
				when AVG(MA_LEVEL) < 11500 Then  ROUND((AVG(MA_LEVEL) - 4123)*0.001970443,0)
				when AVG(MA_LEVEL) < 25630 Then  ROUND((AVG(MA_LEVEL) + 2835)*0.001970443,0)
				when AVG(MA_LEVEL) > 25630 Then  65
				else 0004662005
				end  AS LEVEL, AVG(MA_TEMP) AS TEMP,AVG(MA_PH) AS PH, AVG(MA_EC) AS CL,AVG(MA_PRESS) AS PRESS,INSERT_DATE,
				DATE_FORMAT(MAX(INSERT_DATE),"%Y") AS Y,
				DATE_FORMAT(MAX(INSERT_DATE),"%m") AS M,
				DATE_FORMAT(MAX(INSERT_DATE),"%d") AS D,
				DATE_FORMAT(MAX(INSERT_DATE),"%H") AS H,
				DATE_FORMAT(MAX(INSERT_DATE),"%i") AS I,
				DATE_FORMAT(MAX(INSERT_DATE),"%s") AS S 
			FROM
				T_MODBUS
			WHERE
				SLAVE = '{$params['TANK']}' 
				AND INSERT_DATE BETWEEN "{$params['SDATE']}" 
				AND "{$params['EDATE']}" 
				GROUP BY SUBSTR(INSERT_DATE,1,15)
			ORDER BY
				INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);
		 //echo $this->db->last_query();
		return $query->result();
	}
	
	public function detail_interface4($params)
	{

		$sql=<<<SQL
			SELECT
			CASE 
				WHEN MA_LEVEL > 300 then 90
				when MA_LEVEL < 300 then ROUND((MA_LEVEL-100)*0.454545455 ,0)
				when MA_LEVEL < 100 then 0
				when MA_LEVEL = 0 then 5
				else 0
				end  AS LEVEL,AVG(MA_TEMP) AS TEMP,AVG(MA_PH) AS PH, AVG(MA_EC) AS CL,AVG(MA_PRESS) AS PRESS,INSERT_DATE,
				DATE_FORMAT(MAX(INSERT_DATE),"%Y") AS Y,
				DATE_FORMAT(MAX(INSERT_DATE),"%m") AS M,
				DATE_FORMAT(MAX(INSERT_DATE),"%d") AS D,
				DATE_FORMAT(MAX(INSERT_DATE),"%H") AS H,
				DATE_FORMAT(MAX(INSERT_DATE),"%i") AS I,
				DATE_FORMAT(MAX(INSERT_DATE),"%s") AS S 
			FROM
				T_MODBUS
			WHERE
				SLAVE = '{$params['TANK']}' 
				AND INSERT_DATE BETWEEN "{$params['SDATE']}" 
				AND "{$params['EDATE']}" 
				GROUP BY SUBSTR(INSERT_DATE,1,15)
			ORDER BY
				INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);
		 //echo $this->db->last_query();
		return $query->result();
	}
	
	public function detail_interface5($params)
	{

		$sql=<<<SQL
			SELECT
				MA_LEVEL AS LEVEL,MA_TEMP AS TEMP,MA_PH AS PH,MA_EC AS CL,MA_PRESS AS PRESS,INSERT_DATE,
				DATE_FORMAT(INSERT_DATE,"%Y") AS Y,
				DATE_FORMAT(INSERT_DATE,"%m") AS M,
				DATE_FORMAT(INSERT_DATE,"%d") AS D,
				DATE_FORMAT(INSERT_DATE,"%H") AS H,
				DATE_FORMAT(INSERT_DATE,"%i") AS I,
				DATE_FORMAT(INSERT_DATE,"%s") AS S 
			FROM
				T_MODBUS
			WHERE
				SLAVE = '{$params['TANK']}' 
				AND INSERT_DATE BETWEEN "{$params['SDATE']}" 
				AND (SELECT MAX(INSERT_DATE) FROM T_MODBUS)
			ORDER BY
				INSERT_DATE ASC
SQL;
		$query = $this->db->query($sql);
		 //echo $this->db->last_query();
		return $query->result();
	}	




	public function manual_list($params)
	{
		$where='';

		if($params['SDATE']!="" && isset($params['SDATE']) && $params['EDATE']!="" && isset($params['EDATE']))
			$where .= " AND INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";

		if($params['SLAVE']!="" && isset($params['SLAVE']))
			$where .= " AND SLAVE = {$params['SLAVE']} ";

		$sql=<<<SQL
			SELECT *, DATE_FORMAT(INSERT_DATE,'%Y년%m월%d일 %H시%i분') AS INSERT_BTN
			FROM T_MODBUS_MANUAL
			WHERE SLAVE IS NOT NULL
			AND GUBUN = '{$params['GUBUN']}'
			{$where}
			ORDER BY
				INSERT_DATE DESC
SQL;
		$query = $this->db->query($sql);
		 //echo $this->db->last_query();
		return $query->result();
	}	

	public function manual_list_cut($params)
	{
		$where='';

		if($params['SDATE']!="" && isset($params['SDATE']) && $params['EDATE']!="" && isset($params['EDATE']))
			$where .= " AND INSERT_DATE BETWEEN '{$params['SDATE']}' AND '{$params['EDATE']}'";

		if($params['SLAVE']!="" && isset($params['SLAVE']))
			$where .= " AND SLAVE = {$params['SLAVE']} ";

		$sql=<<<SQL
			SELECT *
			FROM T_MODBUS_MANUAL
			WHERE SLAVE IS NOT NULL 
			AND GUBUN = '{$params['GUBUN']}'
			{$where}

SQL;		
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->num_rows();
	}

	public function manual_list2($params)
	{
		$where='';

		if($params['IDX']!="" && isset($params['IDX']))
		{
			$where.=" AND INSERT_DATE between (select INSERT_DATE FROM T_MODBUS_MANUAL WHERE IDX = {$params['IDX']})";
			$where.=" AND (select DATE_ADD(INSERT_DATE, INTERVAL 3 MINUTE)  FROM T_MODBUS_MANUAL WHERE IDX = {$params['IDX']}) ";
		}
		if($params['SLAVE']!="" && isset($params['SLAVE']))
			$where.=" AND SLAVE = \"{$params['SLAVE']}\"";

		$sql=<<<SQL
			select 
				DATE_FORMAT(SENSOR_DATE,"%Y") AS Y,
				DATE_FORMAT(SENSOR_DATE,"%m") AS M,
				DATE_FORMAT(SENSOR_DATE,"%d") AS D,
				DATE_FORMAT(SENSOR_DATE,"%H") AS H,
				DATE_FORMAT(SENSOR_DATE,"%i") AS I,
				DATE_FORMAT(SENSOR_DATE,"%s") AS S,
				DATE_FORMAT(SENSOR_DATE,'%H시 %i분 %S초') AS INSERT_D,
				SENSOR_DATE, SENSOR, MANUAL, IDX
			from T_MODBUS_MANUAL
			where H_IDX = "{$params['IDX']}"
			UNION ALL
			select 
				DATE_FORMAT(INSERT_DATE,"%Y") AS Y,
				DATE_FORMAT(INSERT_DATE,"%m") AS M,
				DATE_FORMAT(INSERT_DATE,"%d") AS D,
				DATE_FORMAT(INSERT_DATE,"%H") AS H,
				DATE_FORMAT(INSERT_DATE,"%i") AS I,
				DATE_FORMAT(INSERT_DATE,"%s") AS S,
				DATE_FORMAT(INSERT_DATE,'%H시 %i분 %S초') AS INSERT_D,
				INSERT_DATE, MA_EC AS SENSOR_EC, "" AS MANUAL_EC, IDX
			from T_MODBUS
			where NOT EXISTS (select * from T_MODBUS_MANUAL where H_IDX = "{$params['IDX']}")
				{$where}
			LIMIT 5
SQL;
		$query = $this->db->query($sql);
		// echo $this->db->last_query();
		return $query->result();
	}	

	public function manual_up($params)
	{
		$sqlD = "DELETE FROM T_MODBUS_MANUAL WHERE H_IDX = '{$params['IDX']}'";
		$this->db->query($sqlD);
	
		$dateParams = ['SENSORDATE1', 'SENSORDATE2', 'SENSORDATE3', 'SENSORDATE4', 'SENSORDATE5'];
		$sensorParams = ['SENSOR1', 'SENSOR2', 'SENSOR3', 'SENSOR4', 'SENSOR5'];
		$manualParams = ['MANUAL1', 'MANUAL2', 'MANUAL3', 'MANUAL4', 'MANUAL5'];
	
		foreach ($dateParams as $key => $dateParams) {
			$date = $params[$dateParams];
			$sensor = $params[$sensorParams[$key]];
			$manual = $params[$manualParams[$key]];
	
			if (!empty($date)) {
				$sql = "INSERT INTO T_MODBUS_MANUAL (H_IDX, SENSOR_DATE, SENSOR, MANUAL, UPDATE_DATE)
						VALUE ( '{$params['IDX']}', '{$date}', '{$sensor}', '{$manual}', NOW())";
	
				$this->db->query($sql);
			}
		}
	
		return $this->db->affected_rows();
	}

	public function sensor_ins($params){
		$sql = "INSERT INTO T_MODBUS_MANUAL (INSERT_DATE, SLAVE, GUBUN)
					VALUES  (NOW(), '{$params['SLAVE']}', '{$params['GUBUN']}')";

		$this->db->query($sql);
	}



}
