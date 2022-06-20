<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	header {
		width: 870px;
		position: relative;
		z-index: 10;
	}

	@media screen and (min-width: 1200px) {
		header {
			width: calc(100vw - 330px);
		}
	}

	;
</style>


<header>
	<div class="searchDiv">
		<form id="headForm" onsubmit="return false">
			<label for="date">날짜 선택</label>
			<select name="date" id="date" class="form_input">
				<option value="O.ORDER_DATE" <?= ($str['date'] == "O.ORDER_DATE") ? "selected" : '' ?>>작업지시일</option>
				<option value="O.START_DATE" <?= ($str['date'] == "O.START_DATE") ? "selected" : '' ?>>작업예정일</option>
				<option value="O.END_DATE" <?= ($str['date'] == "O.END_DATE") ? "selected" : '' ?>>작업종료일</option>
			</select>
			<input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
			<label>수주명</label>
			<input type="text" name="actnm" class="" size="11" value="<?= $str['actnm'] ?>">


			<label>포장여부</label>
			<select name="package" id="package" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">전체</option>
				<option value="Y" <?= ($str['package'] == 'Y') ? 'selected' : '' ?>>포장 완료</option>
				<option value="N" <?= ($str['package'] == 'N') ? 'selected' : '' ?>>포장 전</option>
			</select>

			<button class="search_submit head_search"><i class="material-icons">search</i></button>

			<input type="button" value="선택해제" class="link_s1 link_hover" data-idx="R" style="position:absolute;right:20px;color:#333;" autocomplete="off">
		</form>
	</div>
</header>


<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-hover">
		<thead>
			<tr>
				<th>NO</th>
				<th>작업지시일</th>
				<th>수주명</th>
				<th>거래처</th>
				<th>작업예정일</th>
				<th>작업종료일</th>
				<th>포장여부</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($list)) {
				foreach ($list as $i => $row) {
					$no = $pageNum + $i + 1;
			?>
					<tr class="link_hover" data-idx="<?= $row->IDX ?>">
						<td class="cen"><?= $no; ?></td>
						<td class="cen"><?= $row->ORDER_DATE ?></td>
						<td class="cen"><?= $row->ACT_NAME ?></td>
						<td class="cen"><?= $row->CUST_NM ?></td>
						<td class="cen"><?= $row->START_DATE ?></td>
						<td class="cen"><?= $row->END_DATE ?></td>
						<td class="cen"><?= ($row->PACKAGE_YN == "Y") ? "완료" : '' ?></td>
					</tr>


				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="15" class="list_none">정보가 없습니다.</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>


<div class="pagination2">
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





<script>
	$("input").attr("autocomplete", "off");

	$(".link_hover").click(function() {
		var idx = $(this).data("idx");

		$("tr").removeClass('over')
		$(this).addClass('over')

		$.ajax({
			url: "<?= base_url('STOCK/detail_package') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				idx: idx
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


	//제이쿼리 수신일 입력창 누르면 달력 출력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>