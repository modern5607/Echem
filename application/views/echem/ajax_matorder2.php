<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm" onsubmit="return false">
			<label for="date">날짜 선택</label>
			<select name="date" id="date" class="form_input">
				<option value="ACT_DATE" <?=($str['date']=="ACT_DATE")?"selected":''?> >발주등록일</option>
				<option value="DEL_DATE" <?=($str['date']=="DEL_DATE")?"selected":''?>>입고예정일</option>
			</select>
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
				<th style="width:12%;" class="res">등록일</th>
				<th style="width:12%;" class="res">납품처</th>
				<th style="width:12%;" class="res">품목</th>
				<th style="width:12%;" class="res">등록 구분</th>
				<th style="width:12%;" class="res">수량</th>
				<th style="width:12%;" class="res">단위</th>
				<th>출하 비고</th>
				<th style="width:5%;"></th>
			</tr>
		</thead>
		<tbody>

		<tr class="insertHead">
				<td class="cen"></td>
				<td><input type="date" name="INSERT_DATE" class="" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>" style="border: 1px solid #ddd; padding: 5px 7px; width:100%; background: white;"></td>
				<td><input type="text" name="CUST" id="CUST" value="" class="form_input input_100" style="width:100%;"></td>
				<td><input type="text" name="COL1" id="COL1" value="제품명" class="form_input input_100" style="width:100%;"></td>
				<td><input type="text" name="IGGBN" id="IGGBN" value="출고" class="" style="width:100%;"></td>
				<td><input type="number" min="0" name="STOCK" id="STOCK" value="" class="form_input input_100" style="width:100%;"></td>
				<td><input type="text" min="0" name="UNIT" id="UNIT" value="Kg" class="form_input input_100" style="width:100%;"></td>
				<td><input type="text" name="REMARK" id="REMARK" value="" class="form_input input_100" style="width:100%;"></td>
				<td class="cen"><span type="button" class="submitBtn btn">추가</span></td>
		</tr>

		<?php 
		if(!empty($list)){
		$count=0;
		$remark=0;
		foreach($list as $i=>$row){ 
			$no = $i+1; 
			$num = $pageNum + $i + 1;
			if($row->IGGBN == "출고"){
				$count += $row->STOCK;
			}
			?>
				<tr class="pocbox">
					<td class="cen"><?= $num ?></td>
					<td class="cen"><?= $row->INSERT_DATE ?></td>
					<td class="cen"><?= $row->CUST ?></td>
					<td class="cen"><?= $row->COL1 ?></td>
					<td class="cen"><?= $row->IGGBN ?></td>
					<td class="right"><?= number_format(round($row->STOCK, 2)) ?></td>
					<td class="cen"><?= $row->UNIT ?></td>
					<td><?= $row->REMARK ?></td>
					<td class="cen">
						<?= ($row->STOCK > 0) ? "<span type='button' class='delBtn btn' data-idx='".$row->IDX."'>삭제</span>" : ''; ?>
					</td> 
				</tr>
			<?php
			}
			?>
				<tr style="background:#f3f8fd;" class="nhover">
					<td colspan="4" style="text-align:center;"><strong>LI2CO3</strong></td>
					<td colspan="1" style="text-align:center;"><strong>합계</strong></td>
					<!-- <td class="cen"><?php echo $row->COMPONENT_NM; ?></td> -->
					<td class="right"><strong><?php echo number_format($count); ?></strong></td>
					<td class="cen">KG</td>
					<td colspan="2"></td>
				</tr>
			<?php
			}else{
			?>
				<tr>
					<td colspan="15" class="list_none">입고정보가 없습니다.</td>
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

	$(".submitBtn").on("click", function() {

		var formData = new FormData();
		formData.append("INSERT_DATE", $("input[name='INSERT_DATE']").val());
		formData.append("IGGBN", $("input[name='IGGBN']").val());
		formData.append("STOCK", $("input[name='STOCK']").val());
		formData.append("REMARK", $("input[name='REMARK']").val());
		formData.append("CUST", $("input[name='CUST']").val());
		formData.append("COL1", $("input[name='COL1']").val());
		formData.append("UNIT", $("input[name='UNIT']").val());

 

		// FormData의 값 확인
		for (var pair of formData.entries()) {
			console.log(pair[0] + ', ' + pair[1]);
		}
		if ($("input[name='STOCK']").val() == "0") {
			alert("1이상 입력하세요.");
			$("input[name='STOCK']").focus();
			return false;
		}
		if ($("input[name='STOCK']").val() == "") {
			alert("수량 입력하세요.");
			$("input[name='STOCK']").focus();
			return false;
		}

		$.ajax({
			url: "<?= base_url('ECHEM/component_head_insert2') ?>",
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

	$(".delBtn").on("click", function() {
		var idx = $(this).data("idx");
		var stock = $(this).data("stock");

		if (confirm('삭제하시겠습니까?') !== false) {

			$.get("<?= base_url('ECHEM/del_component2') ?>", {
				idx: idx
			}, function(data) {
				location.reload();
			}, "JSON").done(function(jqXHR) {
				location.reload();
			})
		}
	});
</script>