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
				<label>일자</label>
				<input type="text" name="sdate" id="smonth" value="<?= $str['sdate']; ?>" /> ~
				<input type="text" name="edate" id="emonth" value="<?= $str['edate']; ?>" />

				<label>건수</label><input type="radio" name="type" value="num" <?= ($str['type'] == 'num') ? "checked" : "" ?>>
				<label>중량</label><input type="radio" name="type" value="weight" <?= ($str['type'] == 'weight') ? "checked" : "" ?>>

				<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
				<span class="btn print download" data-type="<?=($str['type'] == 'num')?"건수":"중량"?>"><i class="material-icons">get_app</i>엑셀받기</span> </button>

			</form>
		</div>
	</header>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id="excel">
			<thead>
				<tr>
					<th>월</th>
					<th>주차</th>
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
				if (!empty($List)) {
					foreach ($List as $i => $row) {
				?>
						<tr>
							<td class="cen"><?= $row->MONTH ?></td>
							<td class="cen"><?= $row->WEEK ?></td>
							<td class="right"><?= ($row->_1=="0")?"":number_format($row->_1)?></td>
							<td class="right"><?= ($row->_2=="0")?"":number_format($row->_2)?></td>
							<td class="right"><?= ($row->_3=="0")?"":number_format($row->_3)?></td>
							<td class="right"><?= ($row->_4=="0")?"":number_format($row->_4)?></td>
							<td class="right"><?= ($row->_5=="0")?"":number_format($row->_5)?></td>
							<td class="right"><?= ($row->_6=="0")?"":number_format($row->_6)?></td>
							<td class="right"><?= ($row->_7=="0")?"":number_format($row->_7)?></td>
							<td class="right"><?= ($row->_8=="0")?"":number_format($row->_8)?></td>
							<td class="right"><?= ($row->TOTAL=="0")?"":number_format($row->TOTAL)?></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">월간 실행계획이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

</div>

<div class="pagination">
	<?php
	if ($this->data['cnt'] > 20) {
	?>
		<div class="limitset">
			<select name="per_page">
				<option value="20" <?= ($perpage == 20) ? "selected" : ""; ?>>20</option>
				<option value="50" <?= ($perpage == 50) ? "selected" : ""; ?>>50</option>
				<option value="80" <?= ($perpage == 80) ? "selected" : ""; ?>>80</option>
				<option value="100" <?= ($perpage == 100) ? "selected" : ""; ?>>100</option>
			</select>
		</div>
	<?php
	}
	?>
	<?= $this->data['pagenation']; ?>
</div>

<div id="pop_container">

	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">

			<!-- 데이터 -->

		</div>
	</div>

</div>



<script>

$('.download').click(function(){
	var type = $(this).data("type");
	fnExcelReport('excel','월간 실행계획 현황 '+type+'별 [<?=$str['sdate'].'~'.$str['edate'] ?>]');
});

	$("#smonth").datepicker({

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
	$("#emonth").datepicker({

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


	function fnExcelReport(id, title) {
		var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
		tab_text = tab_text + '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
		tab_text = tab_text + '<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
		tab_text = tab_text + '<x:Name>Sheet1</x:Name>';
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

</script>