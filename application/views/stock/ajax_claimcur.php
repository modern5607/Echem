<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm">
			
            <label>일자</label>
                <input type="text" name="sdate" class="calendar" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
                <input type="text" name="edate" class="calendar" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

			<label for="biz">거래처</label>
				<select name="biz" id="biz" style="padding:4px 10px; border:1px solid #ddd;">
					<option value="">거래처</option>
					<?php foreach ($BIZ as $row) { ?>
						<option value="<?php echo $row->IDX ?>" <?php echo ($str['biz'] == $row->IDX) ? "selected" : ""; ?>><?php echo $row->CUST_NM; ?></option>
					<?php } ?>
				</select>	


			<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header> 

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width: 5%">No</th>
				<th style="width: 10%">수주일</th>
				<th style="width: 15%">수주명</th>
				<th style="width: 10%">거래처</th>
				<th style="width: 8%">수주량</th>
				<th style="width: 10%">납기일</th>
				<th style="width: 8%">납품량</th>
				<th style="width: 10%">반품일</th>
				<th style="width: %">클레임사항</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($list)){
		foreach($list as $i=>$row){
			$no = $pageNum+$i+1;
		?>

			<tr>
				<td class="cen"><?php echo $no;?></td>
				<td class="cen"><?= $row->ACT_DATE?></td>
				<td><?= $row->ACT_NAME?></td>
				<td><?= $row->CUST_NM?></td>
				<td class="right"><?= round($row->QTY,2) ?></td>
				<td class="cen"><?= $row->END_DATE?></td>
				<td class="right"><?= round($row->BQTY,2)?></td>
				<td class="cen"><?= $row->CLAIM_DATE?></td>
				<td><?= $row->CLAIM?></td>
			</tr>

		<?php
		}}else{
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


	<div class="pagination">
	<?php
	if ($this->data['cnt'] > 20) {
	?>
		<div class="limitset">
			<select name="per_page">
				<option value="20" <?php echo ($perpage == 15) ? "selected" : ""; ?>>20</option>
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


<script>
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>