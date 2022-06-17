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
	};
</style>
<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm" onsubmit="return false">
			
            <label>일자</label>
                <input type="date" name="sdate" class="" size="11" value="<?= $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
                <input type="date" name="edate" class="" size="11" value="<?= $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
				<label>수주명</label>
			<input type="text" name="actnm" class="" size="11" value="<?= $str['actnm']?>">
			<label for="biz">거래처</label>
			<select name="biz" id="biz" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">전체</option>
				<?php foreach ($BIZ as $row) { ?>
					<option value="<?= $row->IDX ?>" <?= ($str['biz'] == $row->IDX) ? "selected" : ""; ?>><?= $row->CUST_NM; ?></option>
				<?php } ?>
			</select>
			<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn add add_head"><i class="material-icons">add</i>추가</span> -->
</header>

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width:5%;">NO</th>
				<th style="width:10%;">수주일</th>
				<th style="width:10%;">수주명</th>
				<th style="width:10%;">수량(T)</th>
				<th style="width:10%;">거래처</th>
				<th style="width:10%;">납기예정일</th>
				<th style="width:10%;">배송방법</th>
				<th style="width:10%;">출고여부</th>
				<th>수주 세부사항</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($list)) {
				foreach ($list as $i => $row) {
					$no = $pageNum + $i + 1;
			?>
					<tr class="headLink">
						<td class="cen"><?= $no ?></td>
						<td class="cen"><?= $row->ACT_DATE ?></td>
						<td><?= $row->ACT_NAME ?></td>
						<td class="right"><?= round($row->QTY,2) ?></td>
						<td><?= $row->CUST_NM ?></td>
						<td class="cen"><?= $row->DEL_DATE ?></td>
						<td class="cen"><?= $row->SHIP_WAY ?></td>
						<td class="cen"><?= $row->END_YN ?></td>
						<td><?= $row->REMARK ?></td>
					</tr>
				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="15" class="list_none">납기변경내역이 없습니다.</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
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
	//달력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});


	//input autocoamplete off
	$("input").attr("autocomplete", "off");
</script>