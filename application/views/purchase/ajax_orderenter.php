<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm" onsubmit="return false">
			<label for="date">날짜 선택</label>
			<select name="date" id="date" class="form_input">
				<option value="ACT_DATE" <?= ($str['date'] == "ACT_DATE") ? "selected" : '' ?>>발주등록일</option>
				<option value="END_DATE" <?= ($str['date'] == "END_DATE") ? "selected" : '' ?>>입고일</option>
			</select>
			<input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

			<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn add add_head"><i class="material-icons">add</i>추가</span> -->
</header>

<div id="cocdHead" class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width:5%;">No</th>
				<th style="width:10%;">발주등록일</th>
				<th style="width:10%;">발주 수량</th>
				<th style="width:10%;">단위</th>
				<th style="width:20%;">비고</th>
				<th style="width:10%;">입고 수량</th>
				<th style="width:20%;">입고 특이사항</th>
				<th style="width:10%;">입고일</th>
				<th style="width:5%;">완료여부</th>
			</tr>
		</thead>
		<tbody>

			<?php
			foreach ($list as $i => $row) {
				$num = $i + 1;
			?>
				<tr class="pocbox">
					<td class="cen"><?= $num ?></td>
					<td class="cen"><?= $row->ACT_DATE ?></td>
					<td class="right"><?= number_format($row->QTY) ?></td>
					<td class="cen"><?= $row->UNIT ?></td>
					<td><?= $row->REMARK ?></td>
					<td class="right"><?= number_format($row->QTY2) ?></td>
					<td><?= $row->REMARK2 ?></td>
					<td class="cen"><?= $row->END_DATE ?></td>
					<td class="cen"><?= $row->END_YN ?></td>
				</tr>

			<?php
			}
			?>
		</tbody>
	</table>
</div>

<div class="pagination">
	<?php
	if($this->data['cnt'] > 20){
	?>
	<div class="limitset">
		<select name="per_page">
			<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
			<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
			<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
			<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
		</select>
	</div>
	<?php
	}	
	?>
	<?php echo $this->data['pagenation'];?>
</div>

<script>
	//제이쿼리 수신일 입력창 누르면 달력 출력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>