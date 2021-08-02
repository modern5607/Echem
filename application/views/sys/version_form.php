<h2>
    버전관리
    <span class="material-icons close">clear</span>
</h2>
<div class="formContainer">

    <form name="version" id="version" method="post" enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
        <div class="register_form">
            <fieldset class="form_1">
                <legend>이용정보</legend>
                <input type="hidden" name="MIDX" class="" id="IDX" value="<?=empty($List->IDX)?"":$List->IDX?>">
                <table>
                    <tbody>
                        <tr>
                            <th><label class="l_id">버전</label></th>
                            <td>
                                <input type="text" name="VER_NO" class="form_input input_100" id="VER_NO" value="<?=empty($List->VERSION)? "" : $List->VERSION ?>">
                                <p class="chk_msg" style="float: left; line-height: 32px; padding: 0 10px;"></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label class="l_id">비고</label></th>
                            <td>
                                <textarea name="VER_REMARK" id="VER_REMARK" class="form_input input_100" style="height: 200px;"><?=empty($List->REMARK)?"":$List->REMARK?></textarea>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </fieldset>

            <div class="bcont">
                <!-- <input type="submit" class="submitBtn blue_btn version_form_insert" value="입력" /> -->
                <button type="button" class="blue_btn version_form_insert">등록</button>

            </div>

        </div>

    </form>

</div>

<script>
$(document).off("change","#VER_NO");
$(document).on("change","#VER_NO",function(){
	var val = $(this).val();
	
	$.post("<?php echo base_url('SYS/version_Chk')?>",{val:val},function(data){
		$(".chk_msg").text(data.msg);
		
			if(data.state == 2){
				$("input[name='VER_NO']").val('');
				$("input[name='VER_NO']").focus();
				$(".chk_msg").css('color','red');
			}else{
				$(".chk_msg").css('color','#333');
			}
	},"JSON");
});


    $(document).on("click", ".version_form_insert", function() {
        var idx = $("#IDX").val();
        console.log(idx);
        var path='';
        //수정, 신규 구분
        if(idx!= '')
            path = "version_up";
        else
            path = "insert_ver_form";

        // console.log("<?=base_url('SYS/')?>"+path);
        var formData = new FormData($("#version")[0]);
        // for (var i of formData.entries())
        //     console.log(i[1]);

        if($("input[name='VER_NO']").val() == ""){
		alert("버전을 입력하세요");
		$("input[name='VER_NO']").focus();
		return false;
	    }

        $.ajax({
            url: "<?= base_url('SYS/')?>"+path,
            type: "post",
            data: formData,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                alert("등록 완료했습니다");
                $("#pop_container").fadeOut();
                $(".info_content").css("top", "-50%");
                location.reload();
            }
        });
    });
    
</script>