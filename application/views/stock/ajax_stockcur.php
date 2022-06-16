<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm">
			
            <label>일자</label>
                <input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
                <input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

				<label for="spec">구분</label>
					<select name="spec" id="spec" style="padding:4px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<option value="원자재" <?= ($str['spec']=="원자재")?'selected':''; ?>>원자재</option>
						<option value="완제품" <?= ($str['spec']=="완제품")?'selected':''; ?>>완제품</option>
					</select>

				<label for="kind">입출고</label>
					<select name="kind" id="kind" style="padding:4px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<option value="IN" <?= ($str['kind']=="IN")?'selected':''; ?>>입고</option>
						<option value="OT" <?= ($str['kind']=="OT")?'selected':''; ?>>출고</option>
					</select>

			<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header> 

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width: 5%;">No</th>
				<th style="width: 10%;">일자</th>
				<th style="width: 10%;">구분</th>
				<th style="width: 15%;">품명</th>
				<th style="width: 10%;">거래처</th>
				<th style="width: 7%;">단위</th>
				<th style="width: 8%;">입출고</th>
				<th style="width: 7%;">수량</th>
				<th>비고</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($list)){
		foreach($list as $i=>$row){
			$no = $i+1;
		?>
			<tr>
				<td class="cen"><?= $no?></td>
				<td class="cen"><?= $row->TRANS_DATE?></td>
				<td><?= $row->SPEC?></td>
				<td><?= $row->ITEM_NAME?></td>
				<td><?= $row->BIZ_NM?></td>
				<td><?= $row->UNIT?></td>
				<td><?= ($row->KIND =="IN")?'입고':(($row->KIND == "OT")?'출고':'')?></td>
				<td class="right"><?= $row->QTY?></td>
				<td><?= $row->REMARK?></td>
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


<script>
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>