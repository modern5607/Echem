<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>


<h2>
	작업자등록
	<span class="material-icons close">clear</span>
</h2>

<div class="formContainer">
	<form name="memberform" id="memberform">
	<input type="hidden" name="mod" value="<?php echo isset($memInfo)?1:0;?>">
	<input type="hidden" name="IDX" value="<?php echo isset($memInfo)?$memInfo->IDX:"";?>">
		<div class="register_form" style="padding:0;">
			<fieldset class="form_1">
				<table>
					<tbody>
						<tr>
							<th <?= empty($memInfo)?'class="res"':"";?>>회원아이디</th>
							<td colspan="3">
								<input style="float: left;" type="text" name="ID" id="ID" value="<?php echo isset($memInfo)?$memInfo->ID:"";?>" <?php echo isset($memInfo)?"readonly":"";?> class="form_input">
								<p class="chk_msg" style="float: left; line-height: 32px; padding: 0 10px;"></p>
							</td>
						</tr>
						<tr>
							<th <?= empty($memInfo)?'class="res"':"";?>>비밀번호</th>
							<td><input type="password" name="PWD" id="PWD" value="" class="form_input"></td>
							<th <?= empty($memInfo)?'class="res"':"";?>>비밀번호확인</th>
							<td><input type="password" name="PWD_CHK" id="PWD_CHK" value="" class="form_input"></td>
						</tr>
						<tr>
							<th class="res">이름</th>
							<td><input type="text" name="NAME" id="NAME" value="<?php echo isset($memInfo)?$memInfo->NAME:"";?>" class="form_input"></td>
							<th>작업팀</th>
							<td><input type="text" name="PART" id="PART" value="<?php echo isset($memInfo)?$memInfo->PART:"";?>" class="form_input"></td>
						</tr>
						<tr>
							<th>연락처</th>
							<td><input type="text" maxlength="12" name="TEL" id="TEL" value="<?php echo isset($memInfo)?$memInfo->TEL:"";?>" class="form_input"></td>
							<th>휴대폰</th>
							<td><input type="text" maxlength="13" name="HP" id="HP" value="<?php echo isset($memInfo)?$memInfo->HP:"";?>" class="form_input"></td>
						</tr>
						<tr>
							<th>이메일</th>
							<td colspan="3"><input type="text" name="EMAIL" id="EMAIL" value="<?php echo isset($memInfo)?$memInfo->EMAIL:"";?>" class="form_input" style="width:100%"></td>
						</tr>
						<tr style="height:45px;">
							<th>권한</th>
							<td>
								<label>일반 : 
								<input type="radio" style="width:30px;" name="LEVEL" id="LEVEL" <?php echo ((isset($memInfo) && $memInfo->LEVEL == 1) || empty($memInfo))?"checked":"";?> value="1">
								</label>
								<label>관리자 : 
								<input type="radio" style="width:30px;" name="LEVEL" id="LEVEL" <?php echo (isset($memInfo) && $memInfo->LEVEL == 2)?"checked":"";?> value="2">
								</label>
							</td>
							<th>상태</th>
							<td>
								<label>사용 : 
								<input type="radio" style="width:30px;" name="STATE" id="STATE" <?php echo ((isset($memInfo) && $memInfo->STATE == 'Y') || empty($memInfo))?"checked":"";?> value="Y">
								</label>
								<label>미사용 : 
								<input type="radio" style="width:30px;" name="STATE" id="STATE" <?php echo (isset($memInfo) && $memInfo->STATE == 'N')?"checked":"";?> value="N">
								</label>
							</td>
						</tr>
						
					</tbody>
				</table>
			</fieldset>
			
			<div class="bcont">
				<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
				<button type="button" class="submitBtn blue_btn">
					<?php if(isset($data)){ echo "수정"; }else{ echo "입력"; }; ?>
				</button>
			</div>
			
		</div>

	</form>

</div>







<script>
//아이디 중복 여부 확인
var idchk = 1;
$(document).off("change","#ID");
$(document).on("change","#ID",function(){
	var id = $(this).val();
	
	$.post("<?php echo base_url('MDM/member_idChk')?>",{id:id},function(data){
		$(".chk_msg").text(data.msg);
		
			if(data.state == 2){
				$("input[name='ID']").val('');
				$("input[name='ID']").focus();
				$(".chk_msg").css('color','red');
				idchk = 0;
			}else{
				$(".chk_msg").css('color','#333');
				idchk = 1;
			}
	},"JSON");
});
$(document).off("keyup","#ID");
$(document).on("keyup","#ID",function(){ idchk = 0;});


//아이디 중복 여부 확인
var namechk = 1;
$(document).off("change","input[name='NAME']");
$(document).on("change","input[name='NAME']",function(){

	var name = $("input[name='NAME']").val();	
	$.post("<?php echo base_url('MDM/member_nameChk')?>",{name:name},function(data){
			if(data.state == 2){
				$("input[name='NAME']").val(name+'('+data.cnt+')');
				namechk = 0;
				console.log(2)
			}else{
				namechk = 1;
				console.log(1)
			}
	},"JSON");
});
$(document).off("keyup","#ID");
$(document).on("keyup","#ID",function(){ namechk = 0;});


//업체 리스트 업데이트(추가,변경)
$(document).off("click",".submitBtn");
$(document).on("click",".submitBtn",function(){
	var formData = new FormData($("#memberform")[0]);
	var $this = $(this);


	if(idchk == '0'){
		alert("아이디를 확인하세요");
		$("input[name='CUST_NM']").focus();
		return false;
	}
	if($("input[name='ID']").val() == ""){
		alert("아이디를 입력하세요");
		$("input[name='ID']").focus();
		return false;
	}
	if($("input[name='NAME']").val() == "" ){
		alert("이름을 입력하세요");
		$("input[name='NAME']").focus();
		return false;
	}    
	var pwd = $("input[name='PWD']").val();
    var num = pwd.search(/[0-9]/g);
    var eng = pwd.search(/[a-z]/ig);
    var spe = pwd.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);
    if (pwd != "" && (pwd.length < 8 || num < 0 || eng < 0 || spe < 0)){
        var msg = "최소 8자 이상의 영문, 숫자, 특수문자가 혼합되어야 합니다.\n";
        alert(msg);
        return false;
    }
	if(pwd == "" && !modchk){
		alert("비밀번호를 입력하세요");
		$("input[name='PWD']").focus();
		return false;
	}
	if(pwd != $("input[name='PWD_CHK']").val()){
		alert("비밀번호를 확인해주세요");
		$("input[name='PWD_CHK']").val('')
		$("input[name='PWD_CHK']").focus();
		return false;
	}
	
	$.ajax({
		url  : "<?php echo base_url('/MDM/member_ins_up')?>",
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

document.getElementById('TEL').onkeyup = function(){ this.value = autoHypenPhone( this.value ) ;  }
document.getElementById('HP').onkeyup = function(){ this.value = autoHypenPhone( this.value ) ;  }

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>