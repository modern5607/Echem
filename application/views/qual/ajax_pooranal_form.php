<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<link href="<?= base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">
<script src="<?= base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?= base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>

<h2>
    <?= $title; ?>
    <span class="material-icons close">clear</span>
</h2>


<div class="formContainer">

    <form id="ajaxForm">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="hidx" value="<?= $hidx ?>">
        <div class="register_form">
            <fieldset class="form_1">
                <table>
                    <tbody>
                        <tr>
                            <th><label>등록일자</label></th>
                            <td>
                            <input type="text" readonly="" name="ORDER_DATE" class="form_input input_100" value="<?= !empty($info->DEFECT_REMARK_DATE) ? $info->DEFECT_REMARK_DATE : ""; ?>" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <th><label>비고</label></th>
                            <td>
                                <textarea name="DEFECT_REMARK" id="DEFECT_REMARK" class="form_input input_100" style="height: 200px;"><?= !empty($info->DEFECT_REMARK) ? $info->DEFECT_REMARK : ""; ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        

            <div class="bcont">
                <span id="loading"><img src='<?= base_url('_static/img/loader.gif'); ?>' width="100"></span>
                <?php
                if (!empty($info->DEFECT_REMARK)) { //수정인경우
                ?>
                    <button type="button" class="submitBtn blue_btn">수정</button>
                <?php
                } else {
                ?>
                    <button type="button" class="submitBtn blue_btn">등록</button>
                <?php
                }
                ?>
            </div>
        </div>
    </form>
</div>


<script>

    $(".submitBtn").on("click", function() {
        var formData = new FormData();
        formData.append('hidx',$("input[name='hidx']").val());
        formData.append('idx',$("input[name='idx']").val());
        formData.append('DEFECT_REMARK',$("textarea").val());


        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);

        $.ajax({
            url: "<?= base_url('QUAL/update_pooranal_form') ?>",
            type: "POST",
            dataType: "HTML",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1)
                {
                    alert("성공");
                    $(".ajaxContent").html('');
                    $("#pop_container").fadeOut();
                    $(".info_content").css("top", "-50%");
                    load();
                }
                else
                    alert("실패");
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
    });

    $(document).on("click", "h2 > span.close", function() {
        $(".ajaxContent").html('');
        $("#pop_container").fadeOut();
        $(".info_content").css("top", "-50%");
        // location.reload();

    });
</script>