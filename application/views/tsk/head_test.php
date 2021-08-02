<style>
.test-text{float: right; font-size: 14px; display:flex}
.check_submit{ padding: 2px 5px; cursor: pointer; background: #414350; color: #fff; margin:0 0 3px 10px;}
@media (max-width: 1238px) {  
	.bc__box header .search_submit{padding:4px 5px; };
};
</style>
<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="headForm">
			<label for="mid">MP납기일</label>
				<input type="text" name="date" class="calendar" size="13" value="<?php echo $str['date']; ?>" placeholder="<?= date("Y-m-d") ?>" /> 
				
				<label for="mname">POR NO</label>
				<input type="text" name="porno" id="porno" value="<?php echo $str['porno']?>" size='10' />			

			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header>

<button type="button" class="check_submit">전체선택</button>
<button type="button" class="test-icon test_ins_btn" style="float:right">&nbsp;&nbsp;<i class="material-icons">add_to_photos</i>검사신청</button>

<div class="test-text">
	신청 물량 :&nbsp;<p class="test_qty">0</p>&nbsp;/ 신청 중량 :&nbsp;<p class="test_weight">0</p>
</div>
	


	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th style="width:33px;"></th>
					<th>POR NO</th>
					<th>SEQ</th>
					<th>수량</th>
					<th>품명/재질/규격</th>
					<th>MP납기일</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if($this->data['cnt'] != 0){
			foreach($List as $i=>$row){ 
			if($row->POR_NO != "합계"){?>
			<tr>
				<td><input style="width:13px;" name="checkH" type="checkbox"></td>
				<td class="cen"><?= $row->POR_NO; ?></td>
				<td class="cen"><?= $row->POR_SEQ; ?></td>
				<td class="right" data-weight="<?= $row->WEIGHT; ?>"><?= number_format($row->PO_QTY); ?></td>
				<td><?= $row->MCCSDESC; ?></td>
				<td class="cen"><?= $row->PORRQDA; ?></td>
			</tr>
	
			<?php
			}}}else{
			?>
				<tr>
					<td colspan="15" class="list_none">검색조건에 부합한 작업이 없습니다.</td>
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

$(document).off("click",".check_submit");
$(document).on("click",".check_submit",function(){
	if($('input:checkbox[name=checkH]:checked').length != $('input:checkbox[name=checkH]').length) {
		$('input:checkbox[name=checkH]').prop("checked", true);
	}else{
		$('input:checkbox[name=checkH]').prop("checked", false);
	}
	Tcount();
});
// tr클릭시 체크 설정
$(document).off("click",".tbl-content table tr");
$(document).on("click",".tbl-content table tr",function(){
	if($(this).find('td:first-child :checkbox').is(":checked") == true) {
		$(this).find('td:first-child :checkbox').prop("checked", false);
	}else{
		$(this).find('td:first-child :checkbox').prop("checked", true);
	};
	Tcount();
});
$(document).off("click","input[type=checkbox]")
$(document).on("click","input[type=checkbox]",function(){
	if($(this).is(":checked") == true) { $(this).prop("checked", false); }else{ $(this).prop("checked", true); };
});


//체크된 신청 물량, 중량
function Tcount(){
	var Tqty = 0; var Tweight = 0;
	var checkbox = $("input[name=checkH]:checked");
	// 체크된 체크박스 값을 가져온다
	checkbox.each(function(i) {
		var tr = checkbox.parent().parent().eq(i);
		var td = tr.children();

		// td.eq(0)은 체크박스 이므로  td.eq(1)의 값부터 가져온다.
		var qty = td.eq(3).text() * 1
		var weight = Math.round(td.eq(3).data('weight'))
		Tqty += qty
		Tweight += weight
	});
	$('.test_qty').text(Tqty.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","))
	$('.test_weight').text(Tweight.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","))
}




$(document).off("click",".test_ins_btn");
$(document).on("click",".test_ins_btn",function(){
	
	var formData = new FormData();
	var checkbox = $("input[name=checkH]:checked");


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
		formData.append('date',$("input[name=sdate]").val())

	$.ajax({
		url  : "<?php echo base_url('/TSK/test_ins')?>",
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