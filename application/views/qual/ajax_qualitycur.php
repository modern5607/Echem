<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="bdcont_100">

	<div class="">

		<header>
			<div class="searchDiv">
				<form id="ajaxForm" onsubmit="return false">
					<label>작업지시일</label>
					<input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
					<input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
					<label>수주명</label>
					<input type="text" name="actname" value="<?= $str['actname'] ?>" />
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
			<!-- <span class="btn print add_biz"><i class="material-icons">add</i>업체추가</span> -->
			<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>No</th>
						<th>작업지시일</th>
						<th>수주명</th>
						<th>거래처</th>
						<th>작업종료일</th>
						<th>주문수량</th>
						<th>실적 Li2Co2</th>
						<th>품질검사유무</th>
						<th>불량유무</th>
						<th>불량사유</th>
						<th>불량수량</th>
						<th>불량률</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (!empty($list)) {
						foreach ($list as $i => $row) {
							$no = $i + 1;
					?>

							<tr>
								<td class="cen"><?= $no; ?></td>
								<td class="cen"><?= $row->ORDER_DATE ?></td>
								<td><?= $row->ACT_NAME ?></td>
								<td><?= $row->BIZ_NAME ?></td>
								<td class="cen"><?= $row->END_DATE ?></td>
								<td class="right"><?= number_format($row->QTY) ?></td>
								<td class="right"><?= $row->PPLI2CO3_AFTER_INPUT ?></td>
								<td class="cen"><?= $row->QEXAM_YN ?></td>
								<td class="cen"><?= $row->DEFECT_YN ?></td>
								<td><?= $row->DEFECT_REMARK ?></td>
								<td class="right"><?= $row->DEFECT_QTY ?></td>
								<td class="right"><?= $row->DEFECT_RATE ?></td>
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

	</div>


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

<div id="pop_container">

	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">

			<!-- 데이터 -->

		</div>
	</div>

</div>