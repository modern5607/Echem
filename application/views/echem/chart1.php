<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="pageTitle">
<h1><?php echo $title;?></h1>
</div>


<!DOCTYPE html>
<html>
  <head>
	<title>Insert title here</title>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   
    <meta charset="utf-8">
    <title>Line_Controls_Chart</title>
    
	<style type ="text/css">
       #mid_content{
       margin:10px;
	   vertical-align: top;
       }

	   .kpimean{
			float:right; font-size:15px;  display:flex;
			border:3px solid #414350;
			background:#fff
		}
		.kpimean>p:last-child{
			border-right:0;
		}
		.kpimean>p{
			margin:5px 0;
			padding:0 5px;
			border-right:1px solid #aaa;
			color:#333;
		}
    </style>
    
 
    <!-- jQuery -->
		<script src="https://code.jquery.com/jquery.min.js"></script>
    <!-- google charts -->
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
		<link href="<?php echo base_url('_static/summernote/summernote-lite.css')?>" rel="stylesheet">
		<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>
		<script src="<?php echo base_url('_static/summernote/summernote-lite.js')?>"></script>
		<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js')?>"></script>
		
  </head>
	<body>
	         <div id = "mid_content" style="height:80%; border:2px solid gray;">
				<div style="width: 100%; background:#8C8C8C; color: #fff;">
					<div class="bc__box100">
						<header>
							<div style="float:left">
								<form id="items_formupdate">
									<label style="color:#fff" for="">일자</label>
										<input type="text" name="sdate" value="<?php echo ($str['sdate']!="")?$str['sdate']:date("Y-m-d", strtotime("-1 month", time()))?>" size="12" autocomplete="off">~
										<input type="text" name="edate" value="<?php echo ($str['edate']!="")?$str['edate']:date("Y-m-d",time())?>" size="12" autocomplete="off">

									<button class="search_submit"><i class="material-icons">search</i></button>
								</form>
							</div>

							<div class="kpimean">
								<p>생산목표 : 450 Kg</p>
								<p>원료투입 : 450 Kg</p>
								<p>제품생산 : 450 Kg</p>
							</div>
						</header>		 
					</div>
				</div>
                 <!-- 라인 차트 생성할 영역 -->
                     <div id="lineChartArea" style="height:80%; padding:20px 20px 0px 0px;"></div>
            </div>

    	</body>
	<script>
		$("input[name='sdate'],input[name='edate']").datetimepicker({
			format:'Y-m-d',
			timepicker:false,
			lang:'ko-KR'
		});

		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawVisualization);

		function drawVisualization() { 

			 var data = new google.visualization.DataTable();
			 var chartDateformat     = 'MM-dd';
			 var chartLineCount    = <?php echo $this->data['cnt']; ?>;

			 
			  //그래프에 표시할 컬럼 추가
			  data.addColumn('string' , '날짜');
			  data.addColumn('number'   , '생산 목표');
			  data.addColumn('number'   , '원료 투입');
			  data.addColumn('number'   , '제품 생산');

			  //그래프에 표시할 데이터
				
			  var dataRow  = [];
	 
				<?php
					foreach($List as $row){
				?>
					var year  = "<?php echo $row->YE; ?>";
					var month = "<?php echo $row->MO; ?>";
					var date  = "<?php echo $row->DA; ?>";


					dataRow = [''+year+'-'+month+'-'+date+'', <?php echo (int)$row->ACT; ?>,<?php echo (int)$row->T_SU; ?>,<?php echo (int)$row->OT_OUT; ?>];
					data.addRow(dataRow);
	
				<?php
					}
				?>

			var options = {
					//title : '스마트공장 KPI 설비가동률',
					focusTarget   : 'category',
					width: '100%', height: 600, 
					chartArea : {'width': '75%','height' : '80%'},
					tooltip       : {textStyle : {fontSize:12}, showColorCode : true,trigger: 'both'},
					vAxis: {viewWindow:{min:100, max:600,step:11},
							ticks:[100,150,200,250,300,350,400,450,500,550,600]},
					hAxis: {title: '기간별 생산량', 
							format: chartDateformat,
							// slantedText: true, slantedTextAngle: -90, 
							// gridlines:{count:chartLineCount}, 
							textStyle: {fontSize:12}
							},
					seriesType: 'bars',
					series: {0:{color: '#acf', visibleInLegend: true, type:'steppedArea'},
							 1:{color: 'gray', visibleInLegend: true, type:'line', pointSize:8},
            				 2:{color: 'red', visibleInLegend: true, type:'line', pointSize:8}},
				};
			
			var chart = new google.visualization.ComboChart(document.getElementById('lineChartArea'));
			chart.draw(data, options);
		}
</script>
</html>