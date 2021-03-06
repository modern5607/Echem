<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
header {width: calc(100vw - 330px);}
</style>


<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="headForm" onsubmit="return false">

					<label for="date">날짜 선택</label>
					<select name="date" id="date" class="form_input">
						<option value="O.ORDER_DATE" <?= ($str['date'] == "O.ORDER_DATE") ? "selected" : '' ?>>작업지시일</option>
						<option value="O.START_DATE" <?= ($str['date'] == "O.START_DATE") ? "selected" : '' ?>>작업예정일</option>
						<option value="O.END_DATE" <?= ($str['date'] == "O.END_DATE") ? "selected" : '' ?>>작업종료일</option>
					</select>
					<input type="date" name="sdate" value="<?= $str['sdate']; ?>" class="" /> ~
					<input type="date" name="edate" value="<?= $str['edate']; ?>" class="" />
					<label>수주명</label>
					<input type="text" name="actnm" class="" size="11" value="<?= $str['actnm'] ?>">
					<label for="biz">거래처</label>
					<select name="biz" id="biz" style="padding:4px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<?php foreach ($BIZ as $row) { ?>
							<option value="<?= $row->IDX ?>" <?= ($str['biz'] == $row->IDX) ? "selected" : ""; ?>><?= $row->CUST_NM; ?></option>
						<?php } ?>
					</select>

					<button class="search_submit head_search"><i class="material-icons">search</i></button>
					
				</form>
			</div>
			<!-- <span class="btn print add_order"  style="padding:7px 11px;"><i class="material-icons">add</i>작업지시 등록</span> -->
			<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>NO</th>
						<th>작업지시일</th>
						<th>수주명</th>
						<th>거래처</th>
						<th>작업예정일</th>
						<th>작업종료일</th>
						<th>등록여부</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($list as $i => $row) {
						$no = $pageNum + $i + 1;
					?>
						<tr class="link_hover" data-idx="<?= $row->IDX ?>" data-hidx="<?= $row->ACT_IDX ?>">
							<td class="cen"><?= $no; ?></td>
							<td class="cen"><?= (!empty($row->ORDER_DATE)) ? date("Y-m-d", strtotime($row->ORDER_DATE)) : '' ?></td>
							<td class="cen"><?= $row->ACT_NAME ?></td>
							<td class="cen"><?= $row->CUST_NM ?></td>
							<td class="cen"><?= (!empty($row->START_DATE)) ? date("Y-m-d", strtotime($row->START_DATE)) : '' ?></td>
							<td class="cen"><?= (!empty($row->END_DATE)) ? date("Y-m-d", strtotime($row->END_DATE)) : '' ?></td>
							<td class="cen"><?= ($row->PHINPUT_YN == "Y") ? $row->PHINPUT_YN : "N" ?></td>
						</tr>


					<?php
					}
					?>
				</tbody>
			</table>
		</div>


	</div>
</div>

<div class="pagination2">
	<?php
	if ($this->data['cnt'] > 20) {
	?>
		<div class="limitset">
			<select name="per_page">
				<option value="20" <?php echo ($perpage == 20) ? "selected" : ""; ?>>20</option>
				<option value="50" <?php echo ($perpage == 50) ? "selected" : ""; ?>>50</option>
				<option value="80" <?php echo ($perpage == 80) ? "selected" : ""; ?>>80</option>
				<option value="100" <?php echo ($perpage == 100) ? "selected" : ""; ?>>100</option>
			</select>
		</div>
	<?php
	}
	?>
	<?php echo $this->data['pagenation']; ?>
</div>



<div id="pop_container">

	<div id="info_content" class="info_content" style="height:auto;">

		<div class="ajaxContent">


		</div>

	</div>

</div>


<script>
	$("input").attr("autocomplete", "off");

	$(".link_hover").click(function() {
		var idx = $(this).data("idx");
		var hidx = $(this).data("hidx");

		$(".link_hover").removeClass("over");
		$(this).addClass("over");


		$.ajax({
			url: "<?= base_url('PROD/detail_pharvest') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				idx: idx,
				hidx: hidx
			},
			beforeSend: function() {
				$(this).hide();
				$(".tbl-write01 > table").hide();
				$("#loading").show();
			},
			success: function(data) {
				$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		})

	});


	$(".add_order").on("click", function() {

		$(".ajaxContent").html('');
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('PROD/order_form') ?>",
			type: "POST",
			dataType: "HTML",
			data: {},
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


	//제이쿼리 수신일 입력창 누르면 달력 출력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>