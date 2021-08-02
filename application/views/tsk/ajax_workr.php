<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
.workbtn{float: right; padding: 2px 5px; cursor: pointer; background: #ff4141; color: #fff; margin:0 0 3px 10px;}
.checkbtn{ padding: 2px 5px; cursor: pointer; background: #414350; color: #fff; margin:0 0 3px 10px;}
.workqw{float: right; font-size:15px;}
	@media (max-width: 1210px) {  
		.bc__box header .search_submit{padding:4px 10px;};
	};
</style>

<div class="bdcont_100">
	<header>
		<div class="searchDiv">
			<form id="ajaxForm">
				<label for="mid">MP납기일</label>
					<input type="text" name="sdate" class="calendar" size="13" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~ 
					<input type="text" name="edate" class="calendar" size="13" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
				
				<label for="mname">POR NO</label>
				<input type="text" name="porno" id="porno" value="<?php echo $str['porno']?>" size='8' />

				<label for="level">작업자</label>
					<select name="worker" id="worker" class="form_select" style="padding: 3px 3px;">
					<option value="allworkerselect" <?= empty($str['worker'])?"selected":"" ?>>전체</option>
					<?php 
					foreach($Member as $i=>$row){
					?>
					<option value="<?= $row->NAME; ?>" <?= ($row->NAME == $str['worker'])?"selected":"" ?>><?= $row->NAME; ?></option>
					<?php } ?>
					</select>

				<label>공정구분</label>
				<select name="gj" id="" class="form_select">
					<option value="">전체</option>
					<option value="PROC" <?= ($str['gj'] == "PROC")?"selected":"" ?>>절단/가공</option>
					<option value="ASSE" <?= ($str['gj'] == "ASSE")?"selected":"" ?>>취부</option>
					<option value="WELD" <?= ($str['gj'] == "WELD")?"selected":"" ?>>용접</option>
					<option value="MRO" <?= ($str['gj'] == "MRO")?"selected":"" ?>>사상</option>
				</select>

				
				<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
			</form>


			
		</div>
	</header>

	<div class="tbl-content">
		<span class="workbtn add"><i class="material-icons">add</i>작업완료</span>
		<button type="button" class="check_submit checkbtn">전체선택</button>
		<?php 
		$QTY = 0;
		$WEIGHT = 0;
		foreach($List as $i=>$row){ 
		if($row->POR_NO == "합계"){?>
			<span class="workqw" style="">
					미처리 물량 : <?= number_format($row->PO_QTY); ?> / 미처리 중량 : <?= number_format($row->WEIGHT); ?>
			</span>
		<?php }} ?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th style="width:33px;"></th>
					<th>POR NO</th>
					<th>SEQ</th>
					<th>수량</th>
					<th>단일 중량</th>
					<th>총 중량</th>
					<th>품명/재질/규격</th>
					<th>MP납기일</th>
					<th>작업자</th>
					<th>공정</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if($this->data['cnt'] != 0){
			foreach($List as $i=>$row){ 
			if($row->POR_NO != "합계"){?>
			<tr>
			<?php 
			if(
				(empty($str['worker']) 
				OR ($row->PROC_MAN == $str["worker"] && $row->PROC_YN != "Y")
				OR ($row->ASSE_MAN == $str["worker"] && $row->PROC_YN != "N" && $row->ASSE_YN != "Y")
				OR ($row->WELD_MAN == $str["worker"] && $row->ASSE_YN != "N" && $row->WELD_YN != "Y")
				OR ($row->ASSE_MAN == $str["worker"] && $row->WELD_YN != "N" && $row->MRO_YN != "Y")) 
				&& (empty($str['gj']) 
				OR ($str["gj"] == "PROC" && $row->PROC_YN != "Y")
				OR ($str["gj"] == "ASSE" && $row->PROC_YN != "N" && $row->ASSE_YN != "Y")
				OR ($str["gj"] == "WELD" && $row->ASSE_YN != "N" && $row->WELD_YN != "Y")
				OR ($str["gj"] == "MRO" && $row->WELD_YN != "N" && $row->MRO_YN != "Y"))
			){
			?>
				<td><input style="width:13px;" name="check" type="checkbox"></td>
			<?php
			}else{
			?>
				<td></td>
			<?php
			}
			?>
				<td class="cen"><?= $row->POR_NO; ?></td>
				<td class="cen"><?= $row->POR_SEQ; ?></td>
				<td class="right"><?= number_format($row->PO_QTY); ?></td>
				<td class="right"><?= number_format($row->WEIGHT,3); ?></td>
				<td class="right"><?= number_format($row->WEIGHT*$row->PO_QTY,3); ?></td>
				<td><?= $row->MCCSDESC; ?></td>
				<td class="cen"><?= $row->PORRQDA; ?></td>
				<?php if($row->PROC_YN != "Y" && (empty($str['worker']) == true OR $row->PROC_MAN == $str["worker"]) && (empty($str['gj']) == true OR $str["gj"] == "PROC" )){?>
				<td class="cen"><?= $row->PROC_MAN; ?></td>
				<td class="cen" data-gjst="PROC">절단/가공</td>
				<?php
				}elseif($row->ASSE_YN != "Y" && (empty($str["worker"]) == true OR $row->ASSE_MAN == $str["worker"]) && (empty($str["gj"]) == true OR $str["gj"] == "ASSE" )){?>
				<td class="cen"><?= $row->ASSE_MAN; ?></td>
				<td class="cen" data-gjst="ASSE">취부</td>
				<?php
				}elseif($row->WELD_YN != "Y" && (empty($str["worker"]) == true OR $row->WELD_MAN == $str["worker"]) && (empty($str["gj"]) == true OR $str["gj"] == "WELD" )){?>
				<td class="cen"><?= $row->WELD_MAN; ?></td>
				<td class="cen" data-gjst="WELD">용접</td>
				<?php
				}elseif($row->MRO_YN != "Y" && (empty($str["worker"]) == true OR $row->MRO_MAN == $str["worker"]) && (empty($str["gj"]) == true OR $str["gj"] == "MRO" )){?>
				<td class="cen"><?= $row->MRO_MAN; ?></td>
				<td class="cen" data-gjst="MRO">사상</td>
				<?php
				}?>
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
</div>


<div class="pagination">
	<?php
	if($this->data['cnt'] > 20){
	?>
	<div class="limitset">
		<select name="per_page">
			<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
			<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
            <?php if($this->data['cnt'] > 50){ ?>
			<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
            <?php }; if($this->data['cnt'] > 80){ ?>
			<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
            <?php }; ?>
		</select>
	</div>
	<?php
	}	
	?>
	<?php echo $this->data['pagenation'];?>
</div>


<div id="pop_container">
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent"></div>
	</div>
</div>



<script>
//달력
$(".xdsoft_datetimepicker").remove();
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

//수정
$(document).off("click",".workbtn ");
$(document).on("click",".workbtn ",function(){
	if($('input:checkbox[name=check]:checked').length == 0){
		return false;
	}

	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);
	
	var formData = new FormData();
	var checkbox = $("input[name=check]:checked");


	// 체크된 체크박스 값을 가져온다
	checkbox.each(function(i) {
		var tr = checkbox.parent().parent().eq(i);
		var td = tr.children();

		// td.eq(0)은 체크박스 이므로  td.eq(1)의 값부터 가져온다.
		var porno = td.eq(1).text()
		var seq = td.eq(2).text()
		var qty = td.eq(3).text()
		var worker = td.eq(8).text()
		var gj = td.eq(9).text()

		formData.append('porno['+i+']',porno)
		formData.append('seq['+i+']',seq)
		formData.append('qty['+i+']',qty)
		formData.append('worker['+i+']',worker)
		formData.append('gj['+i+']',gj)
	});


	$.ajax({
		url:"<?php echo base_url('TSK/workr_form')?>",
		type : "post",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
	});
});


//팝업 닫기
$(document).off("click","h2 > span.close");
$(document).on("click","h2 > span.close",function(){
	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
});








$(document).off("click",".check_submit");
$(document).on("click",".check_submit",function(){
	if($('input:checkbox[name=check]:checked').length != $('input:checkbox[name=check]').length) {
		$('input:checkbox[name=check]').prop("checked", true);
	}else{
		$('input:checkbox[name=check]').prop("checked", false);
	}
	
});
// tr클릭시 체크 설정
$(document).off("click",".tbl-content table tr");
$(document).on("click",".tbl-content table tr",function(){
	if($(this).find('td:first-child :checkbox').is(":checked") == true) {
		$(this).find('td:first-child :checkbox').prop("checked", false);
	}else{
		$(this).find('td:first-child :checkbox').prop("checked", true);
	};
	
});
$(document).off("click","input[type=checkbox]")
$(document).on("click","input[type=checkbox]",function(){
	if($(this).is(":checked") == true) { $(this).prop("checked", false); }else{ $(this).prop("checked", true); };
});




//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>