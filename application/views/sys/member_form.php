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
                    <table class="none_border" cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th colspan="4" style="font-size:15px; border-bottom:1px solid red;">개인정보</th>
                            </tr>
                            <tr>
                                <th>회원아이디<span class="<?= (!isset($Info))?'re':''; ?>"></span></th>
                                <td>
                                    <input type="text" name="ID" id="ID"
                                        value="<?= isset($Info) ? $Info->ID : ""; ?>" <?= isset($Info) ? "readonly" : ""; ?>
                                        class="form_input input_100">
                                </td>
                                <th>이름</th>
                                <td><input type="text" name="NAME" id="NAME"
                                    value="<?= isset($Info) ? $Info->NAME : ""; ?>" class="form_input input_100"></td>
                            </tr>
                            <tr>
                                <th><?= (isset($Info))?'비밀번호변경':'비밀번호'; ?><span class="<?= (!isset($Info))?'re':''; ?>"></span></th>
                                <td><input type="password" name="PWD" id="PWD" value="" class="form_input input_100"></td>
                                <th>비밀번호확인<span class="<?= (!isset($Info))?'re':''; ?>"></span></th>
                                <td><input type="password" name="PWD_CHK" id="PWD_CHK" value="" class="form_input input_100"></td>
                            </tr>
                            <tr>
                                <th>권한</th>
                                <td>

                                    <label>일반 :
                                        <input type="radio" name="LEVEL" id="LEVEL" style="width:inherit"
                                            <?php echo ((isset($Info) && $Info->LEVEL == 1) || empty($Info)) ? "checked" : ""; ?> value="1">
                                    </label>
                                    &nbsp&nbsp
                                    <label>관리자 :
                                        <input type="radio" name="LEVEL" id="LEVEL" style="width:inherit"
                                            <?php echo (isset($Info) && $Info->LEVEL == 2) ? "checked" : ""; ?> value="2">
                                    </label>

                                </td>
                                <th>상태</th>
                                <td>
                                    <label>사용 :
                                        <input type="radio" name="STATE" id="STATE" style="width:inherit"
                                            <?php echo ((isset($Info) && $Info->STATE == 'Y') || empty($Info)) ? "checked" : ""; ?>
                                            value="Y">
                                    </label>
                                    &nbsp&nbsp
                                    <label>미사용 :
                                        <input type="radio" name="STATE" id="STATE" style="width:inherit"
                                            <?php echo (isset($Info) && $Info->STATE == 'N') ? "checked" : ""; ?> value="N">
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th>이메일</th>
                                <td colspan="3"><input type="text" name="EMAIL" id="EMAIL"
                                        value="<?php echo isset($Info) ? $Info->EMAIL : ""; ?>"
                                        class="form_input input_100">
                                </td>
                            </tr>
                            <tr>
                                <th>주소</th>
                                <td colspan="3">
                                    <input type="text" name="ADDR1" id="ADDR1"
                                        value="<?php echo isset($Info) ? $Info->ADDR1 : ""; ?>"
                                        class="form_input input_100">
                                </td>
                            </tr>
                            <tr>
                                <th>연락처</th>
                                <td><input type="text" name="TEL" id="TEL"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        value="<?php echo isset($Info) ? $Info->TEL : ""; ?>" class="form_input input_100"></td>
                                <th>휴대폰</th>
                                <td><input type="text" name="HP" id="HP"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                        value="<?php echo isset($Info) ? $Info->HP : ""; ?>" class="form_input input_100"></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div style="margin:20px 0; text-align:center; ">
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

<?php 
    if(!isset($Info)){
?>
		if (pwd == "" && !modchk) {
			alert("비밀번호를 입력하세요");
			$("input[name='PWD']").focus();
			return false;
		}
<?php } ?>

if (pwd != chkP) {
    alert("비밀번호를 확인해주세요");
    $("input[name='PWD']").focus();
    return false;
}

    if(pwd != ""){
		if (pwd.length < 8 || pwd.length > 20)
        msg += "8자리 ~ 20자리 이내로 입력해주세요\n";
		if (num < 0 || eng < 0 || spe < 0)
			msg += "영문, 숫자, 특수문자를 혼합하여 입력해주세요\n"
    }

		if (msg != '') {
			alert(msg);
			return false;
		}


		modchk = false;
		return;
	}


    $(".member_update").click(function() {
        var formchk=  memberformChk();
        if(formchk == false)
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

            if (data.state == 2) {
                $("input[name='ID']").val("");
                $("input[name='ID']").focus();
            }

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