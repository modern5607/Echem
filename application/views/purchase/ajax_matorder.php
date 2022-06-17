<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm">

			<label for="sdate">발주등록일</label>
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
				<th style="width:15%;" class="res">발주등록일</th>
				<th style="width:15%;" class="res">거래처(수입)</th>
				<th style="width:15%;" class="res">발주 수량</th>
				<th style="width:15%;" class="res">단위</th>
				<th style="width:15%;">입고 예정일</th>
				<th>비고</th>
				<th style="width:5%;"></th>
			</tr>
		</thead>
		<tbody>
			<tr class="insertHead">
				<td class="cen"></td>
				<td><input type="date" name="ADATE" class="" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>" style="border: 1px solid #ddd; padding: 5px 7px; width:100%; background: white;"></td>
				<td>
					<select name="BIZTYPE" id="BIZTYPE" class="form_input input_100">
						<option value="">거래처</option>
						<?php foreach ($bizType as $row) { ?>
							<option value="<?= $row->IDX ?>"><?= $row->CUST_NM; ?></option>
						<?php } ?>
					</select>
				</td>
				<td><input type="number" min="0" name="QTY" id="QTY" value="" class="form_input input_100" style="width:100%;"></td>
				
				<td>
					<select name="UNIT" id="UNIT" class="form_input input_100">
						<option value="">공통코드</option>
						<?php foreach ($cocd as $row) { ?>
							<option value="<?= $row->D_NAME ?>"><?= $row->D_NAME; ?></option>
						<?php } ?>
					</select>
				</td>
				<td><input type="date" name="DDATE" class="" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>" style="border: 1px solid #ddd; padding: 5px 7px; width:100%; background: white;"></td>
				<td><input type="text" name="REMARK" id="REMARK" value="" class="form_input input_100" style="width:100%;"></td>
				<td class="cen"><span type="button" class="submitBtn btn">추가</span></td>
			</tr>


			<?php
			foreach ($list as $i => $row) {
				$num = $i + 1;
			?>
				<tr class="pocbox">
					<td class="cen"><?= $num ?></td>
					<td class="cen"><?= $row->ACT_DATE ?></td>
					<td class="cen"><?= $row->CUST_NM ?></td>
					<td class="right"><?= round($row->QTY, 2) ?></td>
					<td class="cen"><?= $row->UNIT ?></td>
					<td class="cen"><?= $row->DEL_DATE ?></td>
					<td><?= $row->REMARK ?></td>
					<td class="cen">
						<?= ($row->END_YN == 'N') ? "<span type='button' class='delBtn btn' data-idx='".$row->IDX."'>삭제</span>" : ''; ?>

					</td>
				</tr>

			<?php
			}
			?>
		</tbody>
	</table>
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
		formData.append("BIZTYPE", $("select[name='BIZTYPE']").val());
		formData.append("QTY", $("input[name='QTY']").val());
		formData.append("ADATE", $("input[name='ADATE']").val());
		formData.append("UNIT", $("select[name='UNIT']").val());
		formData.append("DDATE", $("input[name='DDATE']").val());
		formData.append("REMARK", $("input[name='REMARK']").val());

		// FormData의 값 확인
		for (var pair of formData.entries()) {
			console.log(pair[0] + ', ' + pair[1]);
		}

		if ($("input[name='ADATE']").val() == "") {
			alert("발주등록일을 입력해 주세요.");
			$("input[name='ADATE']").focus();
			return false;
		}
		if ($("select[name='BIZTYPE']").val() == "") {
			alert("거래처를 선택해 주세요.");
			$("select[name='BIZTYPE']").focus();
			return false;
		}
		
		if ($("input[name='QTY']").val() == "") {
			alert("출고수량 입력하세요.");
			$("input[name='QTY']").focus();
			return false;
		}
		if ($("select[name='UNIT']").val() == "") {
			alert("단위를 선택해 주세요.");
			$("select[name='UNIT']").focus();
			return false;
		}

		$.ajax({
			url: "<?= base_url('PURCHASE/component_head_insert') ?>",
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

		if (confirm('삭제하시겠습니까?') !== false) {

			$.get("<?= base_url('PURCHASE/del_component') ?>", {
				idx: idx
			}, function(data) {
				location.reload();
			}, "JSON").done(function(jqXHR) {
				location.reload();
			})
		}
	});
</script>