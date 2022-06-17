<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	.ui-datepicker-calendar {
		display: none;
	}
</style>

<div class="bdcont_100">
	<header>
		<div class="searchDiv">
			<form id="ajaxForm">
				<label>납기월</label>
				<input type="text" name="sdate" id="month" size="11" value="<?= $str['sdate']; ?>" />

				<button class="search_submit ajax_search"><i class="material-icons">search</i></button>

				<span class="btn print download"><i class="material-icons">get_app</i>엑셀받기</span> </button>

			</form>
		</div>
	</header>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>구분</th>
					<th>선체</th>
					<th>선장</th>
					<th>전장</th>
					<th>기장</th>
					<th>선실</th>
					<th>종합</th>
					<th>배관</th>
					<th>기타</th>
					<th>합계</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($List1)) {
					foreach ($List1 as $i => $row) {	?>
						<tr>
							<td class="cen"><?= $row->GB ?></td>
							<td class="right"><?=(empty($row->_1+0)|| $row-> _1+0=="")?"":number_format($row->_1+0) ?></td>
							<td class="right"><?=(empty($row->_2+0)|| $row-> _2+0=="")?"":number_format($row->_2+0) ?></td>
							<td class="right"><?=(empty($row->_3+0)|| $row-> _3+0=="")?"":number_format($row->_3+0) ?></td>
							<td class="right"><?=(empty($row->_4+0)|| $row-> _4+0=="")?"":number_format($row->_4+0) ?></td>
							<td class="right"><?=(empty($row->_5+0)|| $row-> _5+0=="")?"":number_format($row->_5+0) ?></td>
							<td class="right"><?=(empty($row->_6+0)|| $row-> _6+0=="")?"":number_format($row->_6+0) ?></td>
							<td class="right"><?=(empty($row->_7+0)|| $row-> _7+0=="")?"":number_format($row->_7+0) ?></td>
							<td class="right"><?=(empty($row->_8+0)|| $row-> _8+0=="")?"":number_format($row->_8+0) ?></td>
							<td class="right"><?=(empty($row->TOTAL+0)|| $row-> TOTAL+0=="")?"":number_format($row->TOTAL+0) ?></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">월간작업진행 내역이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>


	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id="excel">
			<thead>
				<tr>
					<th>주차</th>
					<th>구분</th>
					<th>선체</th>
					<th>선장</th>
					<th>전장</th>
					<th>기장</th>
					<th>선실</th>
					<th>종합</th>
					<th>배관</th>
					<th>기타</th>
					<th>합계</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($List2)) {
					foreach ($List2 as $i => $row) {	?>
						<tr>
							<td class="cen" rowspan="4" style="font-size: 30px;"><?= $row->WEEK ?></td>
							<td>정규</td>
							<td class="right"><?=(empty($row-> R1)|| $row-> R1=="")?"":$row->R1 ?></td>
							<td class="right"><?=(empty($row-> R2)|| $row-> R2=="")?"":$row->R2 ?></td>
							<td class="right"><?=(empty($row-> R3)|| $row-> R3=="")?"":$row->R3 ?></td>
							<td class="right"><?=(empty($row-> R4)|| $row-> R4=="")?"":$row->R4 ?></td>
							<td class="right"><?=(empty($row-> R5)|| $row-> R5=="")?"":$row->R5 ?></td>
							<td class="right"><?=(empty($row-> R6)|| $row-> R6=="")?"":$row->R6 ?></td>
							<td class="right"><?=(empty($row-> R7)|| $row-> R7=="")?"":$row->R7 ?></td>
							<td class="right"><?=(empty($row-> R8)|| $row-> R8=="")?"":$row->R8 ?></td>
							<td class="right"><?=(empty($row-> R_TOTAL)|| $row-> R_TOTAL=="")?"":$row->R_TOTAL ?></td>
						</tr>
						<tr>
							<td>돌발</td>
							<td class="right"><?=(empty($row-> G1)|| $row-> G1=="")?"":$row->G1 ?></td>
							<td class="right"><?=(empty($row-> G2)|| $row-> G2=="")?"":$row->G2 ?></td>
							<td class="right"><?=(empty($row-> G3)|| $row-> G3=="")?"":$row->G3 ?></td>
							<td class="right"><?=(empty($row-> G4)|| $row-> G4=="")?"":$row->G4 ?></td>
							<td class="right"><?=(empty($row-> G5)|| $row-> G5=="")?"":$row->G5 ?></td>
							<td class="right"><?=(empty($row-> G6)|| $row-> G6=="")?"":$row->G6 ?></td>
							<td class="right"><?=(empty($row-> G7)|| $row-> G7=="")?"":$row->G7 ?></td>
							<td class="right"><?=(empty($row-> G8)|| $row-> G8=="")?"":$row->G8 ?></td>
							<td class="right"><?=(empty($row-> G_TOTAL)|| $row-> G_TOTAL=="")?"":$row->G_TOTAL ?></td>
						</tr>
						<tr>
							<td>계</td>
							<td class="right"><?=(empty($row-> TOTAL1)|| $row-> TOTAL1=="")?"":$row->TOTAL1 ?></td>
							<td class="right"><?=(empty($row-> TOTAL2)|| $row-> TOTAL2=="")?"":$row->TOTAL2 ?></td>
							<td class="right"><?=(empty($row-> TOTAL3)|| $row-> TOTAL3=="")?"":$row->TOTAL3 ?></td>
							<td class="right"><?=(empty($row-> TOTAL4)|| $row-> TOTAL4=="")?"":$row->TOTAL4 ?></td>
							<td class="right"><?=(empty($row-> TOTAL5)|| $row-> TOTAL5=="")?"":$row->TOTAL5 ?></td>
							<td class="right"><?=(empty($row-> TOTAL6)|| $row-> TOTAL6=="")?"":$row->TOTAL6 ?></td>
							<td class="right"><?=(empty($row-> TOTAL7)|| $row-> TOTAL7=="")?"":$row->TOTAL7 ?></td>
							<td class="right"><?=(empty($row-> TOTAL8)|| $row-> TOTAL8=="")?"":$row->TOTAL8 ?></td>
							<td class="right"><?=(empty($row-> TOTAL)|| $row-> TOTAL=="")?"":$row->TOTAL ?></td>
						</tr>
						<tr>
							<td>진행율 (%)</td>
							<td class="right"d><?=(empty($row->PROC1+0 )|| $row->PROC1+0 =="")?"":$row->PROC1+0 ?></td>
							<td class="right"d><?=(empty($row->PROC2+0 )|| $row->PROC2+0 =="")?"":$row->PROC2+0 ?></td>
							<td class="right"d><?=(empty($row->PROC3+0 )|| $row->PROC3+0 =="")?"":$row->PROC3+0 ?></td>
							<td class="right"d><?=(empty($row->PROC4+0 )|| $row->PROC4+0 =="")?"":$row->PROC4+0 ?></td>
							<td class="right"d><?=(empty($row->PROC5+0 )|| $row->PROC5+0 =="")?"":$row->PROC5+0 ?></td>
							<td class="right"d><?=(empty($row->PROC6+0 )|| $row->PROC6+0 =="")?"":$row->PROC6+0 ?></td>
							<td class="right"d><?=(empty($row->PROC7+0 )|| $row->PROC7+0 =="")?"":$row->PROC7+0 ?></td>
							<td class="right"d><?=(empty($row->PROC8+0 )|| $row->PROC8+0 =="")?"":$row->PROC8+0 ?></td>
							<td class="right"d><?=(empty($row->PROC_TOTAL+0 )|| $row->PROC_TOTAL+0 =="")?"":$row->PROC_TOTAL+0 ?></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">월간작업진행 내역이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<div id="pop_container">
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">
			<!-- 데이터 -->
		</div>
	</div>
</div>

<script>
	$('.download').click(function() {
		fnExcelReport('excel', '월간 작업진행 현황 [<?= $str['sdate'] ?>]');
	});
	function fnExcelReport(id, title) {
		var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
		tab_text = tab_text + '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
		tab_text = tab_text + '<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
		tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';
		tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
		tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
		tab_text = tab_text + "<table border='1px'>";
		var exportTable = $('#' + id).clone();
		exportTable.find('input').each(function(index, elem) {
			$(elem).remove();
		});
		tab_text = tab_text + exportTable.html();
		tab_text = tab_text + '</table></body></html>';
		var data_type = 'data:application/vnd.ms-excel';
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");
		var fileName = title + '.xls';
		//Explorer 환경에서 다운로드
		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
			if (window.navigator.msSaveBlob) {
				var blob = new Blob([tab_text], {
					type: "application/csv;charset=utf-8;"
				});
				navigator.msSaveBlob(blob, fileName);
			}
		} else {
			var blob2 = new Blob([tab_text], {
				type: "application/csv;charset=utf-8;"
			});
			var filename = fileName;
			var elem = window.document.createElement('a');
			elem.href = window.URL.createObjectURL(blob2);
			elem.download = filename;
			document.body.appendChild(elem);
			elem.click();
			document.body.removeChild(elem);
		}
	}

	$("#month").datepicker({

		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm',
		onClose: function(dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
			// $(this).blur();
		}
	});


	//input autocoamplete off
	$("input").attr("autocomplete", "off");


	
</script>