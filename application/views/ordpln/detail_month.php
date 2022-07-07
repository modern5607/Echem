<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    #detailForm2 {
        padding-top: 86px
    }

    #detailForm2 .tbl-content th {
        background: white;
    }

    #detailForm2 .tbl-content td {
        background: white;
    }

    #detailForm2 th {
        min-width: 100px;
    }
</style>


<form id="detailForm2">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th>월차등록일</th>
                    <td><input type="text" class="form_input input_100 calendar" autocomplete="off" value="<?= isset($list) ? $list->VACATION_DATE : ''  ?>" name="VACATION_DATE"></td>

                </tr>
                <tr>
                    <th>작업자</th>
                    <td>
                        <select name="MEMBER_IDX" id="MEMBER_IDX" class="form_input input_100">
                            <option value="">선택</option>
                            <?php foreach ($member as $row) { ?>
                                <option value="<?= $row->IDX ?>" <?= (isset($list) && $list->NAME == $row->NAME) ? 'selected' : '' ?>><?= $row->NAME; ?></option>
                            <?php } ?>
                        </select>
                        <!-- <input type="text" class="form_input input_100" value="<?= isset($list) ? $list->NAME : '' ?>"> -->
                    </td>

                    <th></th>
                    <td></td>
                </tr>
                <tr>
                    <th>월차사유</th>
                    <td colspan="3">
                        <textarea name="REMARK" id="" style="resize:none;" class="form_input input_100" rows="5"><?= isset($list) ? $list->REMARK : '' ?></textarea>
                    </td>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <?php if (!empty($str['idx']) && $str['idx'] != "r") { ?>
                        <td rowspan="5" colspan="4" class="cen" style="padding: 15px;">
                            <button type="button" class="btn blue_btn upBtn">수정</button>
                            <button type="button" class="btn blue_btn delBtn">삭제</button>
                        </td>
                    <?php } else { ?>
                        <td rowspan="5" colspan="4" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn submitBtn">저장</button></td>

                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</form>



<script>
    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });


    $(".submitBtn").on("click", function() {

        var formData = new FormData();
        formData.append("VACATION_DATE", $("input[name='VACATION_DATE']").val());
        formData.append("MEMBER_IDX", $("select[name='MEMBER_IDX']").val());
        formData.append("REMARK", $("textarea[name='REMARK']").val());

        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);

        if ($("input[name='VACATION_DATE']").val() == "") {
            alert("연차일자를 입력해주세요.");
            $("input[name='VACATION_DATE']").focus();
            return false;
        }
        if ($("select[name='MEMBER_IDX']").val() == "") {
            alert("작업자를 선택하여 주세요.");
            $("select[name='MEMBER_IDX']").focus();
            return false;
        }

        $.ajax({
            url: "<?= base_url('ORDPLN/offday_insert') ?>",
            type: "POST",
            data: formData,
            asynsc: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result == 1)
                    alert("등록되었습니다.");
                else
                    alert("실패 하였습니다. 다시 시도해 주세요");

                // location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
    });

    //------------삭제---------------------------------//
    $(".delBtn").on("click", function() {
        var idx = '<?= $str['idx'] ?>';

        if (confirm('삭제하시겠습니까?') !== false) {


            $.post("<?= base_url('ORDPLN/offday_del') ?>", {
                idx: idx
            }, function(result) {

                location.reload();

            },).done(function() {

                location.reload();
            })
        }
    });
    //----------------------------수정---------------------------//

    $(".upBtn").on("click", function() {
        var idx = '<?= $str['idx'] ?>';
        console.log(idx);
        var formData = new FormData();
        //IDX가 없네요
        formData.append("IDX", idx);
        formData.append("VACATION_DATE", $("input[name='VACATION_DATE']").val());
        formData.append("MEMBER_IDX", $("select[name='MEMBER_IDX']").val());
        formData.append("REMARK", $("textarea[name='REMARK']").val());


        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }

        if (confirm('수정하시겠습니까?') !== false) {
            $.ajax({
                url: "<?= base_url('ORDPLN/update_offday') ?>",
                type: "POST",
                data: formData,
                asynsc: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert(xhr);
                    alert(textStatus);
                    alert(errorThrown);
                }
            });
        }
    });
</script>