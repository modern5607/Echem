<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<link href="<?= base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?= base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<style>
    #poplv {
        background: #fff;
        padding-bottom: 10px;
        position: absolute;
        top: 210px;
        right: -275px;
        border: 2px solid rgb(59, 77, 115);
        display: none;
    }

    #poplv:after {
        border-top: 15px solid rgb(59, 77, 115);
        border-left: 15px solid transparent;
        border-right: 0px solid transparent;
        border-bottom: 0px solid transparent;
        content: "";
        position: absolute;
        top: 10px;
        left: -15px;
    }

    #poplv>p,
    #poplv>h3 {
        padding: 3px 15px;
    }

    .whatlv {
        position: absolute;
        cursor: pointer;
        top: 3px;
        right: 3px;
    }
</style>

<h2>
    회원정보
    <span class="material-icons close">clear</span>
</h2>

<form name="memberform" id="memberform">
    <input type="hidden" name="mod" value="<?= isset($Info) ? 1 : 0; ?>">
    <input type="hidden" name="IDX" value="<?= isset($Info) ? $Info->IDX : ""; ?>">


    <div class="formContainer">
        <div class="bdcont_100">
            <div class="bc__box100">


                <div class="tbl-content">
                    <h3>개인정보</h3>
                    <table class="none_border" cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th>회원아이디<span class="re"></span></th>
                                <td colspan="5">
                                    <input style="width:130px" type="text" name="ID" id="ID" value="<?= isset($Info) ? $Info->ID : ""; ?>" <?= isset($Info) ? "readonly" : ""; ?> class="form_input">
                                    <p class="chk_msg"></p>
                                </td>
                            </tr>
                            <tr>
                                <th>비밀번호<span class="re"></span></th>
                                <td><input type="password" name="PWD" id="PWD" value="" class="form_input"></td>
                                <th>비밀번호확인<span class="re"></span></th>
                                <td><input type="password" name="PWD_CHK" id="PWD_CHK" value="" class="form_input"></td>
                            </tr>
                            <tr>
                                <th>이메일</th>
                                <td colspan="5"><input style="width:342px" type="text" name="EMAIL" id="EMAIL" value="<?= isset($Info) ? $Info->EMAIL : ""; ?>" class="form_input"></td>
                            </tr>
                            <tr>
                                <th>이름</th>
                                <td><input type="text" name="NAME" id="NAME" value="<?= isset($Info) ? $Info->NAME : ""; ?>" class="form_input"></td>
                                <th>권한</th>
                                <td style="display: flex; justify-content: space-between; position:relative;">
                                    <select name="LEVEL" id="LEVEL" style="padding:5px 10px; border:1px solid #ddd;">
                                        <?php for ($i = 1; $i <= 3; $i++) { ?>
                                            <option value="<?= $i ?>" <?= (isset($Info) && $Info->LEVEL == $i) ? "selected" : ""; ?>><?= $i ?></option>
                                        <?php } ?>
                                    </select>
                                    <i class="material-icons whatlv">help_outline</i>
                                </td>

                            </tr>
                            <tr>
                                <th>연락처</th>
                                <td><input type="text" name="TEL" id="TEL" value="<?= isset($Info) ? $Info->TEL : ""; ?>" class="form_input"></td>
                                <th>휴대폰</th>
                                <td><input type="text" name="HP" id="HP" value="<?= isset($Info) ? $Info->HP : ""; ?>" class="form_input"></td>
                            </tr>
                            <tr>
                                <th>혈액형</th>
                                <td><input type="text" name="BLOOD" id="BLOOD" value="<?= isset($Info) ? $Info->BLOOD : ""; ?>" class="form_input"></td>
                                <th>상태</th>
                                <td>
                                    <label>사용 :
                                        <input type="radio" style="width:15px;" name="STATE" id="STATE" <?= ((isset($Info) && $Info->STATE == 1) || empty($Info)) ? "checked" : ""; ?> value="1">
                                    </label>
                                    <label>미사용 :
                                        <input type="radio" style="width:15px;" name="STATE" id="STATE" <?= (isset($Info) && $Info->STATE == 0) ? "checked" : ""; ?> value="0">
                                    </label>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>


                <div class="tbl-content">
                    <h3>추가정보</h3>
                    <table class="none_border" cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th>학력</th>
                                <td>
                                    <input type="text" name="SCHOOL" id="SCHOOL" value="<?= isset($Info) ? $Info->SCHOOL : ""; ?>" class="form_input">
                                </td>
                                <th>가족사항</th>
                                <td><input type="text" name="FAMILY" id="FAMILY" value="<?= isset($Info) ? $Info->FAMILY : ""; ?>" class="form_input"></td>
                            </tr>
                            <tr>
                                <th>경력</th>
                                <td>
                                    <input type="text" name="EXPERIENCE" id="EXPERIENCE" value="<?= isset($Info) ? $Info->EXPERIENCE : ""; ?>" class="form_input">
                                </td>
                                <th>면허</th>
                                <td><input type="text" name="LICENSE" id="LICENSE" value="<?= isset($Info) ? $Info->LICENSE : ""; ?>" class="form_input"></td>
                            </tr>
                            <tr>
                                <th>병역</th>
                                <td>
                                    <input type="text" name="ARMY" id="ARMY" value="<?= isset($Info) ? $Info->ARMY : ""; ?>" class="form_input">
                                </td>
                                <th>아이피</th>
                                <td><input type="text" name="IP" id="IP" value="<?= isset($Info) ? $Info->IP : ""; ?>" class="form_input"></td>
                            </tr>
                            <tr>
                                <th>등록일</th>
                                <td colspan="3">
                                    <input type="text" name="REGDATE" id="REGDATE" value="<?= isset($Info) ? substr($Info->REGDATE, 0, 10) : ""; ?>" class="form_input">
                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>

                <div id="poplv">
                    <i class="material-icons whatlv" style="color:#fff">close</i>
                    <h2>권한레벨</h2>
                    <h3>다음 레벨부터 페이지에 접속할 수 있습니다.</h3>
                    <p>1레벨: SMT생산관리, 조립생산관리</p>
                    <p>2레벨: 주문/계획, 재고/수불관리, 자재관리</p>
                    <p>3레벨: 기준정보, BOM, 시스템관리, KPI</p>
                </div>

            </div>
        </div>
    </div>

    <div style="margin:20px 0; text-align:center;">
        <input type="button" class="mod blue_btn member_update" value="<?= isset($Info) ? "회원수정" : "회원등록"; ?>">
    </div>

</form>

<script>

var modchk = false;

	function memberformChk() {
		var pwd = $("input[name='PWD']").val();
		var chkP = $("input[name='PWD_CHK']").val();
		var id = $("input[name='ID']").val();

        
        var idchk = id.search(/[ㄱ-ㅎㅏ-ㅣ가-힣]/g);
		var num = pwd.search(/[0-9]/g);
		var eng = pwd.search(/[a-z]/ig);
		var spe = pwd.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

        msg='';
		if (id == "") {
			alert("아이디를 입력하세요");
			$("input[name='ID']").focus();
			return false;
		}

        if(idchk<-1)
        {
            alert("아이디는 영문으로 입력해 주세요");
            $("input[name='ID']").focus();
            return;
        }


		if (pwd == "" && !modchk) {
			alert("비밀번호를 입력하세요");
			$("input[name='PWD']").focus();
			return false;
		}

		if (pwd != chkP) {
			alert("비밀번호를 확인해주세요");
			$("input[name='PWD']").focus();
			return false;
		}

		if (pwd.length < 8 || pwd.length > 20)
        msg += "8자리 ~ 20자리 이내로 입력해주세요\n";
		if (pwd == '')
			msg += "비밀번호는 공백 없이 입력해주세요\n";
		if (num < 0 || eng < 0 || spe < 0)
			msg += "영문, 숫자, 특수문자를 혼합하여 입력해주세요\n"

		if (msg != '') {
			alert(msg);
			return false;
		}


		modchk = false;
		return;

	}


    $(".member_update").click(function() {
        var formchk=  memberformChk();
        if(formchk ==false)
            return;
        formData = new FormData($("#memberform")[0]);

        // for (var i of formData.entries())
        //     console.log(i[0] + ", " + i[1]);

        $.ajax({
            type: "POST",
            url: "<?= base_url('SYS/member_formUpdate') ?>",
            data: formData,
            cache: false,
			contentType: false,
			processData: false,
            success: function(data) {
                var jsonData = JSON.parse(data);

                alert(jsonData);

                $(".ajaxContent").html('');
                $("#pop_container").fadeOut();
                $(".info_content").css("top", "-50%");
                load();
                
            }
        });

    });



    function idchk(id) {
        var idchk = id.search(/[ㄱ-ㅎㅏ-ㅣ가-힣]/g);
        console.log(idchk);

        if(idchk>-1)
        {
            $(".chk_msg").text("영문으로 입력해 주세요");
            $("input[name='ID']").focus();
            return;
        }

        $.post("<?= base_url('SYS/chk_memberid') ?>", {
            id: id
        }, function(data) {
            $(".chk_msg").text(data.msg);

            setTimeout(function() {
                $(".chk_msg").text("");
                if (data.state == 2) {
                    $("input[name='ID']").val("");
                    $("input[name='ID']").focus();
                }
            }, 1000);

        }, "JSON");
    }


    $(document).on("change", "#ID", function() {
        var id = $(this).val();
        idchk(id);
    });




    $("input[name='REGDATE']").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });

    $(".whatlv").on("click", function() {
        $("#poplv").fadeToggle();
    });

    $("input").attr("autocomplete", "off");
</script>