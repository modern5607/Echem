<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<link href="<?= base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">

<script src="<?= base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?= base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>

<h2>
	<?= $str['title'];?>
	<span class="material-icons close">clear</span>
</h2>


<div class="formContainer">


	<form name="bizRegForm" id="bizRegForm">
		<input type="hidden" name="mod" value="<?= $str['mode']?>">
		<input type="hidden" name="IDX" value="<?= $str['IDX']?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id res">업체명</label></th>
							<td>
								<input type="text" id="CUST_NM" name="CUST_NM" value="<?= isset($info->CUST_NM)?$info->CUST_NM:"";?>" class="form_input input_100">
								<p class="chk_msg"></p>
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">주소</label></th>
							<td>
								<input type="text" name="ADDRESS" value="<?= isset($info->ADDRESS)?$info->ADDRESS:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw ">연락처</label></th>
							<td>
								<input type="tel" maxlength="13" id="phoneNum" name="TEL" value="<?= isset($info->TEL)?$info->TEL:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">담당자</label></th>
							<td>
								<input type="text" name="CUST_NAME" value="<?= isset($info->CUST_NAME)?$info->CUST_NAME:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">주거래품목</label></th>
							<td>
								<input type="text" name="ITEM" value="<?= isset($info->ITEM)?$info->ITEM:"";?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="">거래처구분</label></th>
							<td>
								<select name="CUST_TYPE" id="CUST_TYPE" class="form_input">
									<option value="">::선택::</option>
										<?php foreach($BIZGB as $i=>$row){ ?> 
											<option value="<?= $row->D_NAME ?>" <?=($row->D_CODE == $info->CUST_TYPE)?'selected':'' ?> ><?= $row->D_NAME ?></option>
										<?php }?>
								</select>
							</td>
						</tr>
						<tr>
							<th><label class="">사용유무</label></th>
							<td>
								<label>사용 :
									<input type="radio" name="USE_YN" value="Y" <?= ((isset($info->USE_YN) && $info->USE_YN == "Y")?"checked":(empty($info->USE_YN)))?"checked":"";?>> 
								</label>
								<label>미사용 :
									<input type="radio" name="USE_YN" value="N" <?= (isset($info->USE_YN) && $info->USE_YN == "N")?"checked":"";?>>
								</label>
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">비고</label></th>
							<td>
								<textarea name="REMARK" class="form_input input_100"><?= isset($info->REMARK)?$info->REMARK:"";?></textarea>
							</td>
						</tr>
						<?php 
							if(isset($data['mode'])){ //수정인경우
								echo '<input type="hidden" name="IDX" value="'.$info->IDX.'">';
						 	};
						 ?>
						
					</tbody>
				</table>
			</fieldset>
			
			<div class="bcont">
				<span id="loading"><img src='<?= base_url('_static/img/loader.gif');?>' width="100"></span>
				<?php
				if(isset($data)){ //수정인경우
				?>
				<button type="button" class="submitBtn blue_btn">수정</button>
				<?php
				}else{	
				?>
				<button type="button" class="submitBtn blue_btn">입력</button>
				<?php
				}
				?>
			</div>
			
		</div>

	</form>

</div>







<script>
//아이디 중복 여부 확인
var bizchk = 1;
$(document).off("change","#CUST_NM");
$(document).on("change","#CUST_NM",function(){
	var name = $(this).val();
	
	$.post("<?= base_url('MDM/biz_nameChk')?>",{name:name},function(data){
		$(".chk_msg").text(data.msg);
		
			if(data.state == 2){
				$("input[name='CUST_NM']").val('');
				$("input[name='CUST_NM']").focus();
				bizchk = 0;
			}else{
				bizchk = 1;
			}
	},"JSON");
});
$(document).off("keyup","#CUST_NM");
$(document).on("keyup","#CUST_NM",function(){ bizchk = 0;});


//업체 리스트 업데이트(추가,변경)
$(".submitBtn").on("click",function(){

	var formData = new FormData($("#bizRegForm")[0]);
	var $this = $(this);

	if(bizchk == 0){
		alert("업체명을 확인하세요");
		$("input[name='CUST_NM']").focus();
		return false;
	}
	if($("select[name='CUST_TYPE']").val() == ""){
		alert("거래구분을 선택하세요");
		$("select[name='CUST_TYPE']").focus();
		return false;
	}
	if($("input[name='CUST_NM']").val() == ""){
		alert("업체명을 입력하세요");
		$("input[name='CUST_NM']").focus();
		return false;
	}

	$.ajax({
		url  : "<?= base_url('/MDM/biz_ins_up')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		beforeSend  : function(){
			$this.hide();
			$("#loading").show();
		},
		success : function(data){

			var jsonData = JSON.parse(data);
			if(jsonData.status == "ok"){
			
				setTimeout(function(){
					alert(jsonData.msg);
					$(".ajaxContent").html('');
					$("#pop_container").fadeOut();
					$(".info_content").css("top","-50%");
					$("#loading").hide();
					
					load();

				},1000);

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	});
});


//전화번호 입력 자동 -
var autoHypenPhone = function(str){
      str = str.replace(/[^0-9]/g, '');
      var tmp = '';
      if( str.length < 4){
          return str;
      }else if(str.length < 7){
          tmp += str.substr(0, 3);
          tmp += '-';
          tmp += str.substr(3);
          return tmp;
      }else if(str.length < 11){
          tmp += str.substr(0, 3);
          tmp += '-';
          tmp += str.substr(3, 3);
          tmp += '-';
          tmp += str.substr(6);
          return tmp;
      }else{              
          tmp += str.substr(0, 3);
          tmp += '-';
          tmp += str.substr(3, 4);
          tmp += '-';
          tmp += str.substr(7);
          return tmp;
      }
  
      return str;
}

var phoneNum = document.getElementById('phoneNum');

phoneNum.onkeyup = function(){
  this.value = autoHypenPhone( this.value ) ;  
}

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>