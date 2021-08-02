<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	.tbl-content table th {
		padding: 5px;
	}

	.tbl-content table td {
		white-space: nowrap;
	}

	/* .tbl-content table tr {font-size: 11px;} */
</style>

<div class="bc__box100">

	<header>
		<div class="searchDiv">
			<form id="ajaxForm">
				<label>납기일자</label>
				<input type="text" name="sdate" class="calendar" size="11" value="<?= empty($str['sdate']) ? date("Y-01-01") : $str['sdate'] ?>" /> ~
				<input type="text" name="edate" class="calendar" size="11" value="<?= empty($str['edate']) ? date("Y-m-d") : $str['edate'] ?>" />
				<label>POR NO</label>
				<input type="text" name="porno" value="<?= $str['porno']; ?>" />
				<?php
				if (!empty($SJGB)) {
				?>
					<label for="sjgb">수주구분</label>
					<select name="sjgb" id="sjgb" class="form_select">
						<option value="">전체</option>

						<?php
						foreach ($SJGB as $row) {
						?>
							<option value="<?= $row->D_CODE ?>" <?= ($str['sjgb'] == $row->D_CODE) ? "selected" : ""; ?>><?= $row->D_NAME; ?></option>
						<?php
						}
						?>
					</select>
				<?php
				}
				?>

				</select>
				<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
			</form>
		</div>
	</header>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th></th>
					<th>선체</th>
					<th>선장</th>
					<th>전장</th>
					<th>기장</th>
					<th>선실</th>
					<th>종합</th>
					<th>배관</th>
					<th>기타</th>
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
							<td class="right"><?=(empty($row->_1)|| $row->_1=="0")?"":number_format($row->_1,3) ?></td>
							<td class="right"><?=(empty($row->_2)|| $row->_2=="0")?"":number_format($row->_2,3) ?></td>
							<td class="right"><?=(empty($row->_3)|| $row->_3=="0")?"":number_format($row->_3,3) ?></td>
							<td class="right"><?=(empty($row->_4)|| $row->_4=="0")?"":number_format($row->_4,3) ?></td>
							<td class="right"><?=(empty($row->_5)|| $row->_5=="0")?"":number_format($row->_5,3) ?></td>
							<td class="right"><?=(empty($row->_6)|| $row->_6=="0")?"":number_format($row->_6,3) ?></td>
							<td class="right"><?=(empty($row->_7)|| $row->_7=="0")?"":number_format($row->_7,3) ?></td>
							<td class="right"><?=(empty($row->_8)|| $row->_8=="0")?"":number_format($row->_8,3) ?></td>
							<td class="right"><?=(empty($row->TOTAL)|| $row->TOTAL=="0")?"":number_format($row->TOTAL,3) ?></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">납기변경이력이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>POR No</th>
					<th>SEQ</th>
					<th>수량</th>
					<th>품면/재질/규격</th>
					<th>계약일</th>
					<th>MP납기일</th>
					<th>일수</th>
					<th>변경일</th>
					<th>변경발생일</th>
					<th>지연일수</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($List2)) {
					foreach ($List2 as $i => $row) {
				?>
						<tr>
							<td><?= $row->POR_NO ?></td>
							<td class="cen"><?= $row->POR_SEQ ?></td>
							<td class="right"><?= $row->PO_QTY+0 ?></td>
							<td><?= $row->MCCSDESC ?></td>
							<td><?= $row->POWRDA ?></td>
							<td><?= $row->PORRQDA ?></td>
							<td class="right"><?= $row->CNT_DATE ?> </td>
							<td class="right"><?= $row->NEW_MPDA ?></td>
							<td class="right"><?= $row->CHAG_MPDA ?></td>
							<td class="right"><?= $row->DELAY_DATE ?></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">납기변경이력이 없습니다.</td>
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
	//달력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});


	//input autocoamplete off
	$("input").attr("autocomplete", "off");
</script>