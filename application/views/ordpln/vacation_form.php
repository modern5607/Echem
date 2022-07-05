<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<style>
    .bottom_l {
        border-bottom: 3px solid #ddd;
    }
</style>
<h2>
    <?php echo $title; ?>
    <span class="material-icons close">clear</span>
</h2>

<div class="formContainer">
    <div class="register_form">
        <fieldset class="form_2">
            <table class="nhover" style="width:100%">
                <thead>
                    <tr>
                        <th>작업자</th>
                        <th>월차사유</th>
                        <th>비고</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="cen" style="border-bottom: 2px solid #888;">
                            <select name="NEW_NAME" id="NEW_NAME" class="form_input cen">
                                <option value="">선택</option>
                                <?php foreach ($Member as $row) { ?>
                                    <option value="<?= $row->IDX ?>"><?= $row->NAME ?></option>
                                <?php } ?>

                            </select>
                            <!-- <input name="NEW_NAME" type="text" class="form_input "> -->

                        </td>
                        <td class="cen" style="border-bottom: 2px solid #888;"><input name="NEW_REMARK" type="text" class="form_input "></td>
                        <td class="cen" style="border-bottom: 2px solid #888;"><span type="button" class="submitBtn btn">추가</span></td>

                    </tr>
                    <?php foreach ($List as $row) { ?>
                        <tr>
                            <td class="cen">
                                <select name="NAME_<?= $row->IDX ?>" id="NAME" class="form_input">
                                    <option value="">선택</option>
                                    <?php foreach ($Member as $mrow) { ?>
                                        <option value="<?= $mrow->IDX ?>" <?= ($row->NAME == $mrow->NAME) ? "selected" : '' ?>><?= $mrow->NAME ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <input name="NAME_<?= $row->IDX ?>" type="text" class="form_input " value="<?= $row->NAME ?>"> -->

                            </td>
                            <td class="cen"><input name="REMARK_<?= $row->IDX ?>" type="text" class="form_input " value="<?= $row->REMARK ?>"></td>
                            <td class="cen"><span type="button" data-idx="<?= $row->IDX ?>" class="updateBtn btn">수정</span> <span type="button" data-idx="<?= $row->IDX ?>" class="delBtn btn">삭제</span></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </fieldset>

        <!-- <div class="bcont"> 
            <span id="loading"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></span>
            <button type="button" class="submitBtn blue_btn"> 등록 </button>
        </div> -->

    </div>

</div>

<script>
    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });


    $(".submitBtn").click(function() {
        // var IDX = $(this).data("idx");
        var MEMBER_IDX = $("select[name='NEW_NAME']").val();
        var REMARK = $("input[name='NEW_REMARK']").val();
        var DATE = "<?= $setDate ?>";

        // console.log(IDX);
        console.log(MEMBER_IDX);
        console.log(REMARK);
        console.log(DATE);

        $.ajax({
            type: "post",
            url: "<?= base_url('ORDPLN/vacation_insert') ?>",
            data: {
                MEMBER_IDX: MEMBER_IDX,
                REMARK: REMARK,
                DATE: DATE
            },
            dataType: "html",
            success: function(data) {
                if (data == 1) {
                    alert("성공적으로 등록되었습니다.");
                    location.reload();
                } else {
                    alert("실패, 다시 시도해 주세요");
                    location.reload();
                }
            }
        });
    });

    $(".updateBtn").click(function() {
        var IDX = $(this).data("idx");
        var MEMBER_IDX = $("select[name='NAME_" + IDX + "']").val();
        var REMARK = $("input[name='REMARK_" + IDX + "']").val();
        var DATE = "<?= $setDate ?>";

        // console.log(IDX);
        console.log(MEMBER_IDX);
        console.log(REMARK);
        console.log(DATE);

        $.ajax({
            type: "post",
            url: "<?= base_url('ORDPLN/vacation_update') ?>",
            data: {
                IDX: IDX,
                MEMBER_IDX: MEMBER_IDX,
                REMARK: REMARK,
                DATE: DATE
            },
            dataType: "html",
            success: function(data) {
                if (data == 1) {
                    alert("성공적으로 수정되었습니다.");
                    location.reload();
                } else {
                    alert("실패, 다시 시도해 주세요");
                    location.reload();
                }
            }
        });
    });

    $(".delBtn").click(function() {
        var IDX = $(this).data("idx");

        if (!confirm("삭제하시겠습니까?"))
            return;

        $.ajax({
            type: "post",
            url: "<?= base_url('ORDPLN/vacation_delete') ?>",
            data: {
                IDX: IDX
            },
            dataType: "html",
            success: function(data) {
                if (data == 1) {
                    alert("삭제되었습니다.");
                    location.reload();
                } else {
                    alert("실패, 다시 시도해 주세요");
                    location.reload();
                }
            }
        });
    });
</script>