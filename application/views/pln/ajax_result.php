<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	header label:after {
		content: ''
	}

	.ui-datepicker-calendar {
		display: none;
	}

	.tbl-content table thead tr:nth-child(2) th {
		width: 13%;
	}
</style>

<div class="bdcont_100">
	<header>
		<div class="searchDiv">
			<form id="ajaxForm">
				<label>납기월</label>
				<input type="text" name="sdate" id="month" size="11" value="<?= $str['sdate']; ?>" />

				<span class="btn print download"><i class="material-icons">get_app</i>엑셀받기</span> </button>
				<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
			</form>
		</div>
	</header>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th rowspan="2" style="width: 70px;">구분</th>
					<th colspan="3">배송계획</th>
					<th colspan="3">배송실적</th>
					<th rowspan="2">진행율</th>
				</tr>
				<tr>
					<th>정규</th>
					<th>돌발</th>
					<th>계</th>
					<th>정규</th>
					<th>돌발</th>
					<th>계</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($List1)) {
					foreach ($List1 as $i => $row) {
				?>
						<tr>
							<td class="cen"><?= $row->GB ?></td>
							<td class="right"><?= (empty($row->R_PLN + 0) || $row->R_PLN == "") ? "" : number_format($row->R_PLN,3)?></td>
							<td class="right"><?= (empty($row->G_PLN + 0) || $row->G_PLN == "") ? "" : number_format($row->G_PLN,3)?></td>
							<td class="right"><?= (empty($row->PLN_TOTAL + 0) || $row->PLN_TOTAL == "") ? "" : number_format($row->PLN_TOTAL,3)?></td>
							<td class="right"><?= (empty($row->R_ACT + 0) || $row->R_ACT == "") ? "" : number_format($row->R_ACT,3)?></td>
							<td class="right"><?= (empty($row->G_ACT + 0) || $row->G_ACT == "") ? "" : number_format($row->G_ACT,3)?></td>
							<td class="right"><?= (empty($row->ACT_TOTAL + 0) || $row->ACT_TOTAL == "") ? "" : number_format($row->ACT_TOTAL,3)?></td>
							<td class="right"><?= (empty($row->PROC + 0) || $row->PROC == "") ? "" : number_format($row->PROC,3)?></td>
						</tr>

					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">월간계획대비실적이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>


	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id='excel'>
			<thead>
				<tr>
					<th rowspan="2" style="width: 70px;">주차</th>
					<th rowspan="2" style="width: 70px;">구분</th>
					<th colspan="3">배송계획</th>
					<th colspan="3">실적</th>
					<th rowspan="2">진행율</th>
				</tr>
				<tr>
					<th>정규</th>
					<th>돌발</th>
					<th>계</th>
					<th>정규</th>
					<th>돌발</th>
					<th>계</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($List2)) {
					foreach ($List2 as $i => $row) {	?>
						<tr>
							<?php if ($i % 3 == 0) { ?>
								<td class="cen" rowspan="3" style="font-size: 30px;"><?= $row->WEEK ?></td>
							<?php } ?>
							<td><?= $row->GB ?></td>
							<td class="right"><?= (empty($row->R_PLN) || $row->R_PLN == "0") ? "" : $row->R_PLN + 0 ?></td>
							<td class="right"><?= (empty($row->G_PLN) || $row->G_PLN == "0") ? "" : $row->G_PLN + 0 ?></td>
							<td class="right"><?= (empty($row->PLN_TOTAL) || $row->PLN_TOTAL == "0") ? "" : $row->PLN_TOTAL + 0 ?></td>
							<td class="right"><?= (empty($row->R_ACT) || $row->R_ACT == "0") ? "" : $row->R_ACT + 0 ?></td>
							<td class="right"><?= (empty($row->G_ACT) || $row->G_ACT == "0") ? "" : $row->G_ACT + 0 ?></td>
							<td class="right"><?= (empty($row->ACT_TOTAL) || $row->ACT_TOTAL == "0") ? "" : $row->ACT_TOTAL + 0 ?></td>
							<td class="right"><?= (empty($row->PROC) || $row->PROC == "0") ? "" : $row->PROC + 0 ?></td>
						</tr>

					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">월간계획대비실적이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

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
		fnExcelReport('excel', '월간 계획대비 실적 [<?= $str['sdate'] ?>]');
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

	$(document).ready(function() {

	});

	//input autocoamplete off
	$("input").attr("autocomplete", "off");
</script>