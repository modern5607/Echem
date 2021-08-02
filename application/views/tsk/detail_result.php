	<div class="tbl-content" style="margin-top: 86px;" >
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>POR NO</th>
					<th>건수</th>
					<th>수량</th>
					<th>총 중량</th>
					<th>품명/재질/규격</th>
					<th>납기일</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if(!empty($List)){
			foreach($List as $i=>$row){ 
			?>
			<tr>
				<td class="cen"><?= $row->POR_NO; ?></td>
				<td class="cen"><?= $row->SEQ; ?></td>
				<td class="right"><?= number_format($row->PO_QTY); ?></td>
				<td class="right"><?= number_format(($row->PO_QTY * $row->WEIGHT),3); ?></td>
				<td><?= $row->MCCSDESC; ?></td>
				<td class="cen"><?= $row->PORRQDA; ?></td>
			</tr>
	
			<?php
			}}elseif($str['gbn'] != 0 or $str['desc']){
			?>
				<tr>
					<td colspan="15" class="list_none">건수가 없습니다.</td>
				</tr>
			<?php
			}else{
			?>
				<tr>
					<td colspan="15" class="list_none">항목을 선택해주세요.</td>
				</tr>
			<?php
			}	
			?>
			</tbody>
		</table>
	</div>




<div id="pop_container">
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent"></div>
	</div>
</div>



<script>
// 달력
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

$(document).off("click",".test_del_btn");
$(document).on("click",".test_del_btn",function(){
	
	var formData = new FormData();
	var checkbox = $("input[name=checkD]:checked");


	// 체크된 체크박스 값을 가져온다
	checkbox.each(function(i) {
		var tr = checkbox.parent().parent().eq(i);
		var td = tr.children();

		// td.eq(0)은 체크박스 이므로  td.eq(1)의 값부터 가져온다.
		var porno = td.eq(1).text()
		var seq = td.eq(2).text()
		
		formData.append('porno['+i+']',porno)
		formData.append('seq['+i+']',seq)
	});


	$.ajax({
		url  : "<?php echo base_url('/TSK/test_del')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		success : function(data){
			load();
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr,textStatus,errorThrown);
		}
	});
});

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>