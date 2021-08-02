<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="detailForm">
			<input type="hidden" name="hidx" value="<?= $hidx ?>" />
			<label>code</label>
			<input type="text" name="d_code" value="<?= $str['code'] ?>" />
			<label>name</label>
			<input type="text" name="d_name" value="<?= $str['name'] ?>" />
			<label>사용유무</label>
			<select name="d_use" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">전체</option>
				<option value="Y" <?= ($str['use'] == "Y") ? "selected" : ""; ?>>사용</option>
				<option value="N" <?= ($str['use'] == "N") ? "selected" : ""; ?>>미사용</option>
			</select>
			<button type="button" class="search_submit detail_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<?php if ($de_show_chk) { //hid값이 없는경우는 노출안됨 
	?>
		<span class="btn add add_detail" data-hidx="<?= $hidx; ?>"><i class="material-icons">add</i>추가</span>
	<?php } ?>
	<span class="btn print download"><i class="material-icons">get_app</i>엑셀받기</span>
</header>

<div id="cocdDetail" class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" id="excel">
		<thead>
			<tr>
				<th>head-code</th>
				<th>code</th>
				<th>name</th>
				<th>사용유무</th>
				<th>비고</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($List as $i => $row) {
			?>

				<tr>
					<td><?= $row->H_CODE; ?></td>
					<td><?= $row->CODE; ?></td>
					<td><?= $row->NAME; ?></td>
					<td class="cen"><?= ($row->USE_YN == "Y") ? "사용" : "미사용"; ?></td>
					<td><?= $row->REMARK; ?></td>
					<td class="cen"><button type="button" class="mod mod_detail" data-idx="<?= $row->IDX; ?>">수정</button></td>
				</tr>

			<?php
			}
			if (empty($List)) {
			?>
				<tr>
					<td class="cen" colspan="6" style="padding:30px 0;">상위공통코드를 선택하세요</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>

<script>
	//input autocoamplete off
	$("input").attr("autocomplete", "off");


	$(".add_detail").on("click", function() {

		var hidx = $(this).data("hidx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('MDM/ajax_cocdDetail_form') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				mode: "add",
				hidx: hidx
			},
			success: function(data) {
				$(".ajaxContent").html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		})

	});

	$(".print_detail").on("click", function() {
		var HIDX = "<?= $hidx ?>";
		var H_IDX = (HIDX != "") ? "/" + HIDX : "";
		if (confirm('Detail List를 엑셀다운로드 하시겠습니까?') !== false) {
			location.href = "<?= base_url('MDM/excelDown') ?>" + H_IDX;
		}
	});

	$(".mod_detail").on("click", function() {

		var idx = $(this).data("idx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('MDM/ajax_cocdDetail_form') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				mode: "mod",
				idx: idx
			},
			success: function(data) {
				$(".ajaxContent").html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});

	});

	$(document).on("click", "h2 > span.close", function() {
		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top", "-50%");
		// location.reload();

	});

	$('.download').click(function() {

		fnExcelReport('excel', '123123');
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

		//수정 버튼 열 삭제
		exportTable.find("th:last-child,td:last-child").each(function(index,elem){
			$(elem).remove();
		});
		console.log(exportTable.html());

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