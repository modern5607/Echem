<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm" onsubmit="return false">
			<label for="date">날짜 선택</label>

			<input type="date" name="sdate" class="" size="11" value="<?= $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?= $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

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
				<th style="width:10%;">생산 등록일</th>
				<th style="width:10%;">생산량(Kg)</th>
				<th style="width:10%;">제품 색상</th>
				<th style="width:10%;">수분량</th>
				<th style="width:10%;">나트륨 함량</th>
				<th style="width:10%;">원료<br> 수용액 량</th>
				<th style="width:10%;">전도도</th>
				<th style="width:10%;">탄산나트륨<br>파우더 량</th>
				<th style="width:10%;">탄산나트륨<br>수용액 량</th>
				<!-- <th style="width:20%;">특이사항</th> -->
				
			</tr>
		</thead>
		<tbody>

			<?php
			$count=0;
			foreach ($list as $i => $row) {
				$num = $i + 1;
				$count += $row->OT_OUT;
			?>
				<tr class="pocbox">
					<td class="cen"><?= $num ?></td>
					<td class="cen"><?= $row->INSERT_DATE ?></td>
					<?php
						if ( !empty( $row->OT_OUT ) ) {
					?>
						<td class="right"><?= number_format($row->OT_OUT) ?></td>
					<?php
						} else {
					?>
       					<td class="right"><?= 0 ?></td>
					<?php
						}
					?>
					<td class="cen"><?= $row->OT_COL ?></td>
					<td class="cen"><?= $row->OT_WT ?></td>
					<?php
						if ( !empty( $row->OT_NA ) ) {
					?>
						<td class="right"><?= number_format($row->OT_NA) ?></td>
					<?php
						} else {
					?>
       					<td class="right"><?= 0 ?></td>
					<?php
						}
					?>
					<?php
						if ( !empty( $row->T_TANK ) ) {
					?>
						<td class="right"><?= number_format($row->T_TANK) ?></td>
					<?php
						} else {
					?>
       					<td class="right"><?= 0 ?></td>
					<?php
						}
					?>
					<td class="cen"><?= $row->T_JD ?></td>
					<?php
						if ( !empty( $row->NA2CO3 ) ) {
					?>
						<td class="right"><?= number_format($row->NA2CO3) ?></td>
					<?php
						} else {
					?>
       					<td class="right"><?= 0 ?></td>
					<?php
						}
					?>
					<?php
						if ( !empty( $row->NA2CO3_IN ) ) {
					?>
						<td class="right"><?= number_format($row->NA2CO3_IN) ?></td>
					<?php
						} else {
					?>
       					<td class="right"><?= 0 ?></td>
					<?php
						}
					?>
					<!-- <td><?= $row->REMARK1 ?></td> -->
				</tr>

			<?php
			}
			?>
			<tr style="background:#f3f8fd;" class="nhover">
			<td colspan="5" style="text-align:center;"><strong style="font-size: 15px;">제품 생산량</strong></td>
			<!-- <td class="cen"><?php echo $row->COMPONENT_NM; ?></td> -->
			<td class="right"><strong style="font-size: 15px;"><?php echo number_format($count); ?></strong></td>
			<td class="cen"><strong style="font-size: 15px;">KG</td>
			<td colspan="2"></td>
		</tbody>
	</table>




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