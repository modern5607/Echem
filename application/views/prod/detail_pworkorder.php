<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .disabled {
        background: rgb(224, 223, 223);
    }
</style>


<div class="bdcont_100">
    <form id="detailForm">
        <input type="hidden" name="mode" value="<?= $str['mode'] ?>">
        <input type="hidden" name="idx" value="<?= $idx ?>">
        <input type="hidden" name="hidx" value="<?= $hidx ?>">
        <div class="tbl-write01" style="margin-top: 86px;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <div id="loading" style="margin: 170px 0px;"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></div>
                <tbody>
                    <tr>
                        <th class="w120">작업지시일</th>
                        <td><input type="text" readonly name="ORDER_DATE" class="form_input input_100 disabled" value="<?= isset($info->ORDER_DATE) ? $info->ORDER_DATE : '' ?>"></td>
                        <th class="w120">수주명</th>
                        <td><input type="text" readonly name="ACT_NAME" class="form_input input_100 disabled" value="<?= isset($info->ACT_NAME) ? $info->ACT_NAME : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>거래처</th>
                        <td><input type="text" readonly name="CUST_NM" class="form_input input_100 disabled" value="<?= isset($info->CUST_NM) ? $info->CUST_NM : '' ?>"></td>
                        <th>수주일</th>
                        <td><input type="text" readonly name="ACT_DATE" class="form_input input_100 disabled" value="<?= isset($info->ACT_DATE) ? $info->ACT_DATE : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>납품예정일</th>
                        <td><input type="text" readonly name="DEL_DATE" class="form_input input_100 disabled" value="<?= isset($info->DEL_DATE) ? $info->DEL_DATE : '' ?>"></td>
                        <th>주문수량</th>
                        <td><input type="number" readonly name="QTY" class="form_input input_100 disabled" value="<?= isset($info->QTY) ? $info->QTY : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120 res">작업시작일</th>
                        <td><input type="text" name="START_DATE" readonly class="form_input input_100 disabled" value="<?= isset($info->START_DATE) ? $info->START_DATE : '' ?>"></td>
                        <th class="w120 res">작업종료일</th>
                        <td><input type="text" name="END_DATE" readonly class="form_input input_100 disabled" value="<?= isset($info->END_DATE) ? $info->END_DATE : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120 res">원료투입일</th>
                        <td><input type="text" name="RAW_DATE" class="calendar form_input input_100" value="<?= (empty($info->RAW_DATE) || $info->RAW_DATE == "0000-00-00") ? '' : $info->RAW_DATE ?>"></td>
                        <th class="w120 res">Na2Co3</th>
                        <td><input type="text" name="NA2CO3_DATE" class="calendar form_input input_100" value="<?= (empty($info->NA2CO3_DATE) || $info->NA2CO3_DATE == "0000-00-00") ? '' : $info->NA2CO3_DATE ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120 res">교반공정일</th>
                        <td><input type="text" name="MIX_DATE" class="calendar form_input input_100" value="<?= (empty($info->MIX_DATE) || $info->MIX_DATE == "0000-00-00") ? '' : $info->MIX_DATE ?>"></td>
                        <th class="w120 res">세척 공정일</th>
                        <td><input type="text" name="WASH_DATE" class="calendar form_input input_100" value="<?= (empty($info->WASH_DATE) || $info->WASH_DATE == "0000-00-00") ? '' : $info->WASH_DATE ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120 res">건조 공정일</th>
                        <td><input type="text" name="DRYPHASE_DATE" class="calendar form_input input_100" value="<?= (empty($info->DRYPHASE_DATE) || $info->DRYPHASE_DATE == "0000-00-00") ? '' : $info->DRYPHASE_DATE ?>"></td>
                    </tr>
                    <tr>
                        <th>특이사항</th>
                        <td colspan="3"><input type="text" name="REMARK" class="form_input input_100" value="<?= isset($info->REMARK) ? $info->REMARK : '' ?>"></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <?php if ($str['mode'] == "mod") { ?>
                            <td rowspan="5" colspan="6" class="cen" style="padding: 15px;">
                                <button type="button" class="btn blue_btn upBtn">수정</button>
                                <button type="button" class="btn blue_btn delBtn">삭제</button>
                            </td>
                        <?php } else  if ($str['mode'] == "new") { ?>
                            <td rowspan="5" colspan="6" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn submitBtn">등록</button></td>
                        <?php } else {
                        } ?>
                    </tr>
                </tfoot>

            </table>
        </div>
    </form>
</div>


<script>
    $(".submitBtn").click(function() {
        var formData = new FormData($("#detailForm")[0]);

        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);

        // if ($("input[name='START_DATE'").val() == "") {
        //     alert("작업 시작일을 입력해 주세요");
        //     $("input[name='START_DATE'").focus();
        //     return false;
        // }
        // if ($("input[name='END_DATE'").val() == "") {
        //     alert("작업 시작일을 입력해 주세요");
        //     $("input[name='END_DATE'").focus();
        //     return false;
        // }

        $.ajax({
            url: "<?= base_url('PROD/update_pworkorder') ?>",
            type: "POST",
            dataType: "HTML",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1)
                    alert("등록되었습니다");
                else
                    alert("실패");
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        })

    });
    $(".upBtn").click(function() {
        var formData = new FormData($("#detailForm")[0]);

        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);


        // if ($("input[name='ORDER_DATE'").val() == "") {
        //     alert("작업 시작일을 입력해 주세요");
        //     $("input[name='ORDER_DATE'").focus();
        //     return false;
        // }
        // if ($("input[name='START_DATE'").val() == "") {
        //     alert("작업 시작일을 입력해 주세요");
        //     $("input[name='START_DATE'").focus();
        //     return false;
        // }
        // if ($("input[name='END_DATE'").val() == "") {
        //     alert("작업 시작일을 입력해 주세요");
        //     $("input[name='END_DATE'").focus();
        //     return false;
        // }

        $.ajax({
            url: "<?= base_url('PROD/update_pworkorder') ?>",
            type: "POST",
            dataType: "HTML",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1)
                    alert("수정되었습니다");
                else
                    alert("실패");
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        })
    });

    $(".delBtn").click(function() {
        if (!confirm("삭제하시겠습니까?"))
            return;
        var formData = new FormData($("#detailForm")[0]);

        $.ajax({
            url: "<?= base_url('PROD/del_pworkorder') ?>",
            type: "POST",
            dataType: "HTML",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1)
                    alert("삭제되었습니다");
                else
                    alert("실패");
            }
        })
    });

    //제이쿼리 수신일 입력창 누르면 달력 출력
    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });
</script>