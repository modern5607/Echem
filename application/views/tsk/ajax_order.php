<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	#AA {
		width: 21cm;
		height: 29.7cm;
		margin: 30mm 45mm 30mm 45mm;
		/* change the margins as you want them to be. */
	}

	table.greyGridTable {
		border: 0px solid black;
		width: 100%;
		text-align: left;
		border-collapse: collapse;
	}

	table.greyGridTable td,
	table.greyGridTable th {
		border: 0px solid black;
		font-weight: bold;
		padding: 2px 1px;

		white-space: nowrap;
	}


	table.greyGridTable2 {
		border: 1px solid black;
		width: 100%;
		text-align: left;
		border-collapse: collapse;
	}

	table.greyGridTable2 td,
	table.greyGridTable2 th {
		border: 1px solid black;
		text-align: center;
		padding: 5px 1px;
		font-size: 13px;
		white-space: nowrap;
	}

	table.greyGridTable2 td.right {
		padding-right: 4px;
		text-align: right;
	}


	table.greyGridTable thead th {
		font-size: 14px;
		font-weight: bold;
		text-align: center;
	}

	.endline {
		page-break-before: always
	}

	.page-break-tr {
		page-break-inside: avoid;
	}
</style>

<header>
	<div class="searchDiv">
		<form id="ajaxForm">

			<label for="mid">작업일자</label>
			<input type="text" name="date" class="calendar" size="11" value="<?= $str['date'] ?>" />

			<label for="level">작업자</label>
			<select name="worker" id="worker" class="form_select">

			</select>

			<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
			<?php
			if(!empty($List)){ ?>
				<span class="btn print page_print"><i class="material-icons">get_app</i>출력</span> </button>
			<?php }?>

		</form>
	</div>
</header>

<div id="AA">
	<div>
		<table class="greyGridTable">
			<thead>

				<tr>
					<th colspan="7" style="font-size: 30px; padding-bottom: 15px;">작 업 지 시 서 (출력용)</th>
				</tr>

			</thead>
			<div style="float: right;">
				출력일 : <?= date("Y-m-d h:i:s") ?>
			</div>
			<tbody>
				<tr style="margin-top: 10px;">
					<td style="width: 70px;">작업일 : </td>
					<td style="width: 250px;"><?= $str['date'] ?></td>
					<td style="width: 70px;">작업자 : </td>
					<td style="width: 250px;"><?= $str['worker'] ?></td>
					<td style="width: 70px;">총중량(Kg) : </td>
					<td style="width: 250px;"><?= array_sum(array_column($List, 'WEIGHT')) ?></td>
					<td style="text-align: right; width: 100px;">1 of 1</td>
				</tr>

			</tbody>
		</table>
		<table class="greyGridTable2" style="margin-top: 2px;">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">POR No</th>
					<th rowspan="2">SEQ</th>
					<th rowspan="2">총중량</th>
					<th rowspan="2">수량</th>
					<th rowspan="2">품명/재질/규격</th>
					<th rowspan="2">제작검사시한</th>
					<th colspan="2">작업지시</th>
					<th rowspan="2">작업완료</th>
				</tr>
				<tr>
					<th>공정</th>
					<th>중량</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($List)) {
					foreach ($List as $i => $row) {
						$num = $i + 1; ?>
						<tr class="page-break-tr">
							<td><?= $num ?></td>
							<td><?= $row->POR_NO ?></td>
							<td><?= $row->POR_SEQ ?></td>
							<td class="right"><?= $row->WEIGHT + 0 ?></td>
							<td class="right"><?= $row->PO_QTY + 0 ?></td>
							<td><?= $row->MCCSDESC ?></td>
							<td><?= $row->INRQDA ?></td>
							<td><?= $row->GB ?></td>
							<td class="right"><?= $row->WEIGHT2 + 0 ?></td>
							<td><?= $row->END ?></td>
						</tr>
				<?php }
				} ?>
			</tbody>
		</table>
	</div>
</div>


<script>
$("input[name='date']").change(function () { 
	search_member();
});

function search_member()
{
	var date = $("input[name='date']");
	date = date.val();

	$.ajax({
		type: "post",
		url: "<?=base_url("TSK/ajax_memberlist")?>",
		data: {
			date :date
		},
		success: function (data) {
			var dataset = JSON.parse(data);
			var html="";

			if(dataset==0)
				html+="<option value=''>없음</option>";
			else
			{
				$.each(dataset, function (index, info) { 
				html+="<option value='"+info.MAN+"'>"+info.MAN+"</option>";
				});
			}
			$("select[name='worker']").html(html);
		}
	});
}

$(document).ready(function () {
	search_member();
});
	//달력
	$(".xdsoft_datetimepicker").remove();
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});

	//input autocoamplete off
	$("input").attr("autocomplete", "off");

	$(".page_print").click(function() {
		let $container = $("#AA").clone() // 프린트 할 특정 영역 복사
		//  container.print();
		let cssText = "" // 스타일 복사
		for (const node of $("style")) {
			cssText += node.innerHTML
		}
		console.log($container[1]);
		/** 팝업 */
		let innerHtml = $container[0].innerHTML
		let popupWindow = window.open("", "_blank", 'width=700,height=1000')
		popupWindow.document.write("<!DOCTYPE html>" +
			"<html>" +
			"<head>" +
			"<style>" + cssText + "</style>" +
			"</head>" +
			"<body>" + innerHtml + "</body>" +
			"</html>")

		popupWindow.document.close()
		popupWindow.focus()

		/** 1초 지연 */
		setTimeout(() => {
			popupWindow.print() // 팝업의 프린트 도구 시작
			// popupWindow.close() // 프린트 도구 닫혔을 경우 팝업 닫기
		}, 1000)
	});
</script>