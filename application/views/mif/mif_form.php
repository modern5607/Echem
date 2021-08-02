<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<h2>
    <?= $title; ?>
    <span class="material-icons close">clear</span>
</h2>


<div class="formContainer">
    <form name="noticeform" id="noticeform">
        <div class="register_form">
            <fieldset class="form_1">
                <table>
                    <tbody>
                        <tr>
                            <th><label>제목</label></th>
                            <td>
                            <input type="text" name="title_form" class="form_input input_100">
                            </td>
                        </tr>
                        <tr>
                            <th><label>내용</label></th>
                            <td>
                                <textarea name="cont_form" id="cont_form" cols="30" rows="10"
                                    class="form_input input_100"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label>종료일</label></th>
                            <td>
                                <input type="date" name="enddate_form" class="form_input input_100">
                            </td>
                        </tr>

                    </tbody>
                </table>
            </fieldset>

            <div class="bcont">
                <span id="loading"><img src='<?= base_url('_static/img/loader.gif'); ?>' width="100"></span>

                <button type="button" class="submitBtn blue_btn">입력</button>
            </div>
        </div>
    </form>
</div>


<script>
$(".submitBtn").on("click", function() {

    var formData = new FormData($("#noticeform")[0]);
    
    for(var i of formData.entries())
		console.log(i[0]+i[1]);

    if ($("input[name='title_form']").val() == "") {
        alert("제목을 입력하세요");
        $("input[name='title_form']").focus();
        return false;
    }

    if ($("textarea[name='cont_form']").val() == "") {
        alert("내용을 입력하세요");
        $("textarea[name='cont_form']").focus();
        return false;
    }
    
    if ($("input[name='enddate_form']").val() == "") {
        alert("내용을 입력하세요");
        $("input[name='enddate_form']").focus();
        return false;
    }

    $.ajax({
        url: "<?= base_url('MIF/notice_ins') ?>",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            if(data>0)
            {
                alert("등록했습니다.");
                $("#pop_container").fadeOut();
                $(".info_content").animate({
                    top: "50%"
                }, 500);
                load();
            }
           
        }
        
    });
});

$(document).on("click", "h2 > span.close", function() {
    $(".ajaxContent").html('');
    $("#pop_container").fadeOut();
    $(".info_content").css("top", "-50%");
});
</script>