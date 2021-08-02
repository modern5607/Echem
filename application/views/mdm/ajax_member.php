<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
.non_update{border: 0; padding: 3px 8px; background: #aaa; color: #fff; cursor: default;} 
</style>

<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					
					<label for="mid">아이디</label>
					<input type="text" name="mid" id="mid" value="<?php echo $str['mid']?>" size='10' />
					
					<label for="mname">이름</label>
					<input type="text" name="mname" id="mname" value="<?php echo $str['mname']?>" size='10' />

					<label for="level">권한</label>
					<select name="level" class="searchSelect">
						<option value="">전체</option>
						<option value="1" <?php echo ($str['level'] == 1)?"selected":"";?>>일반</option>
						<option value="2" <?php echo ($str['level'] == 2)?"selected":"";?>>관리자</option>
					</select>
					<label>사용유무</label>
						<select name="useyn" id="useyn" style="padding:4px 10px; border:1px solid #ddd;">
							<option value="A" <?= ($str['useyn'] == 'A')?'selected':'' ?>>전체</option>
							<option value="Y" <?= ($str['useyn'] == 'Y')?'selected':'' ?>>사용</option>
							<option value="N" <?= ($str['useyn'] == 'N')?'selected':'' ?>>미사용</option>
						</select>
					
					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
			<?= ($_SESSION['user_level']=='2') ? '<span class="btn print member_insert"><i class="material-icons">add</i>신규등록</span>' : ""; ?>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>아이디</th>
						<th>권한</th>
						<th>이름</th>
						<th>작업팀</th>
						<th>전화</th>
						<th>휴대폰</th>
						<th>이메일</th>
						<th>상태</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($memberList as $i=>$row){ ?>
				<tr>
					<td><?php echo $row->ID; ?></td>
					<td class="cen"><?= ($row->LEVEL == 1)?"일반":(($row->LEVEL == 2)?"관리자":'') ?></td>
					<td class="cen"><?= $row->NAME; ?></td>
					<td class="cen"><?= $row->PART; ?></td>
					<td class="cen"><?= $row->TEL; ?></td>
					<td class="cen"><?= $row->HP; ?></td>
					<td><?= $row->EMAIL; ?></td>
					<td class="cen"><?= ($row->STATE == 'Y') ? "사용" : "미사용"; ?></td>
					<td class="cen">
						<span class=" <?= ($row->ID == $_SESSION['user_id'] || $_SESSION['user_level']=='2') ? "mod member_update" : "non_update"; ?>" data-idx="<?php echo $row->IDX;?>">수정</span>
					</td>
				</tr>
		
				<?php
				}
				if(empty($memberList)){
				?>
					<tr>
						<td colspan="15" class="list_none">회원정보가 없습니다.</td>
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
		<div class="ajaxContent"></div>
	</div>
</div>



<script>
var modchk = false;
//신규
$(document).off("click",".member_insert");
$(document).on("click",".member_insert",function(){
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	$.ajax({
		url:"<?php echo base_url('/MDM/member_form')?>",
		type : "post",
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
	});
});

//수정
$(document).off("click",".member_update");
$(document).on("click",".member_update",function(){
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	modchk = true;
	var idx = $(this).data("idx");
	$.ajax({
		url:"<?php echo base_url('/MDM/member_form')?>",
		type : "post",
		data : {idx:idx},
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

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>