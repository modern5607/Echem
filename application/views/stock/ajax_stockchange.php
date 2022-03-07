<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<header>
	<div class="searchDiv">
		<form id="ajaxForm">

			<label for="">구분</label>
				<select name="" id="" style="padding:4px 10px; border:1px solid #ddd;">
					<option value="">전체</option>
					<option value="">원자재</option>
					<option value="">완제품</option>
				</select>
			
			<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header> 

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr><th colspan="12">입고</th></tr>
			<tr>
				<th style="width: 5%;">No</th>
				<th class="cen" style="width: 10%;">구분</th>
				<th style="width: 25%;">품목명</th>
				<th style="width: 7%;">단위</th>
				<th style="width: 7%;">현재고</th>
				<th style="width: 8%;">입고량</th>
				<th style="width: 10%;">입고일</th>
				<th>사유</th>
				<th style="width: 5%;"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($list)){
		foreach($list as $i=>$row){
			$no = $i+1;
		?>
			<tr data-kind="IN">
				<td class="cen"><?= $no?></td>
				<td><?= $row->SPEC?></td>
				<td><?= $row->ITEM_NAME?></td>
				<td><?= isset($row->UNIT)?$row->UNIT:"" ?></td>
				<td class="right"><?= isset($row->STOCK)?round($row->STOCK,2):"0"?></td>
				<td><input type="number" name="QTY" class="input_100"></td>
				<td><input type="text" name="DATE" class="calendar" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>" 
				style="border: 1px solid #ddd; padding: 3px 7px; width:100%; background: white;"></td>
				<td><input type="text" name="REMARK" class="input_100"></td>
				<td class="cen"><span type="button" class="submitBtn btn" style="background-color: white;" data-idx="<?=$row->IDX?>">수정</span></td>
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
<br><br><br><br><br>
<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr><th colspan="12">출고</th></tr>
			<tr>
			<th style="width: 5%;">No</th>
				<th class="cen" style="width: 10%;">구분</th>
				<th style="width: 25%;">품목명</th>
				<th style="width: 7%;">단위</th>
				<th style="width: 7%;">현재고</th>
				<th style="width: 8%;">출고량</th>
				<th style="width: 10%;">출고일</th>
				<th>사유</th>
				<th style="width: 5%;"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($list)){
		foreach($list as $i=>$row){
			$no = $i+1;
		?>
			<tr data-kind="OT">
				<td class="cen"><?= $no?></td>
				<td><?= $row->SPEC?></td>
				<td><?= $row->ITEM_NAME?></td>
				<td><?= isset($row->UNIT)?$row->UNIT:"" ?></td>
				<td class="right"><?= isset($row->STOCK)?round($row->STOCK,2):"0"?></td>
				<td><input type="number" name="QTY" class="input_100"></td>
				<td><input type="text" name="DATE" class="calendar" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>" 
				style="border: 1px solid #ddd; padding: 3px 7px; width:100%; background: white;"></td>
				<td><input type="text" name="REMARK" class="input_100"></td>
				<td class="cen"><span type="button" class="submitBtn btn" style="background-color: white;" data-idx="<?=$row->IDX?>">수정</span></td>
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

	$(".submitBtn").on("click",function(){

		var formData = new FormData();
		formData.append("IDX", $(this).data("idx"));
		formData.append("KIND", $(this).parents("tr").data("kind"));
		formData.append("QTY", $(this).parents("tr").find("input[name='QTY']").val());
		formData.append("DATE", $(this).parents("tr").find("input[name='DATE']").val());
		formData.append("REMARK", $(this).parents("tr").find("input[name='REMARK']").val());


		// FormData의 값 확인
		for (var pair of formData.entries()) {
		console.log(pair[0]+ ', ' + pair[1]);
		}


		$.ajax({
			url: "<?php echo base_url('STOCK/stock_update') ?>",
			type: "POST",
			data: formData,
			//asynsc : true,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) { 
				location.reload();
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});
	});
</script>