<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .disabled {
        background: rgb(224, 223, 223);
    }
</style>


<div class="bdcont_100">
<header>
			<div class="searchDiv" style="height: 66px;">
            <i class="material-icons">info</i><span> 공정별 수율을 등록하게되면 작업지시를 수정 및 삭제가 불가능합니다. 작업지시를 삭제하기 위해선 해당 화면에서 항목을 선택후 삭제를 해주세요.
			</span></div>
		</header>
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
                        <td colspan="2"><input type="text" readonly name="ORDER_DATE" class="form_input input_100 disabled" value="<?= isset($info->ORDER_DATE) ? $info->ORDER_DATE : '' ?>"></td>
                        <th class="w120">수주명</th>
                        <td colspan="2"><input type="text" readonly name="ACT_NAME" class="form_input input_100 disabled" value="<?= isset($info->ACT_NAME) ? $info->ACT_NAME : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>거래처</th>
                        <td colspan="2"><input type="text" readonly name="CUST_NM" class="form_input input_100 disabled" value="<?= isset($info->CUST_NM) ? $info->CUST_NM : '' ?>"></td>
                        <th>수주일</th>
                        <td colspan="2"><input type="text" readonly name="ACT_DATE" class="form_input input_100 disabled" value="<?= isset($info->ACT_DATE) ? $info->ACT_DATE : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>납품예정일</th>
                        <td colspan="2"><input type="text" readonly name="DEL_DATE" class="calendar form_input input_100 disabled" value="<?= isset($info->DEL_DATE) ? $info->DEL_DATE : '' ?>"></td>
                        <th>주문수량</th>
                        <td colspan="2"><input type="number" readonly name="QTY" class="form_input input_100 disabled" value="<?= isset($info->QTY) ? $info->QTY : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120">작업시작일</th>
                        <td colspan="2"><input type="text" name="START_DATE" readonly class="calendar form_input input_100 disabled" value="<?= isset($info->START_DATE) ? $info->START_DATE : '' ?>"></td>
                        <th class="w120">작업종료일</th>
                        <td colspan="2"><input type="text" name="END_DATE" readonly class="calendar form_input input_100 disabled" value="<?= isset($info->END_DATE) ? $info->END_DATE : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120 res">원료(드럼)</th>
                        <td colspan="1"><input type="text" name="PHRAW_INPUT" class="form_input input_100" value="<?= isset($info->PHRAW_INPUT) ? $info->PHRAW_INPUT : '' ?>"></td>
                        <th class="w120 res">LiCl(여과후)</th>
                        <td colspan="1"><input type="text" name="PHLICL_AFTER_INPUT" class="form_input input_100" value="<?= isset($info->PHLICL_AFTER_INPUT) ? $info->PHLICL_AFTER_INPUT : '' ?>"></td>
                        <th class="w120">수율</th>
                        <td colspan="1"><input type="text" readonly name="NA2CO3" class="form_input input_100 disabled" value="<?= isset($info->NA2CO3) ? $info->NA2CO3 : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120 res">Na2Co3</th>
                        <td colspan="1"><input type="text" name="PHNA2CO3_INPUT" class="form_input input_100" value="<?= isset($info->PHNA2CO3_INPUT) ? $info->PHNA2CO3_INPUT : '' ?>"></td>
                        <th class="w120 res">H2O</th>
                        <td colspan="1"><input type="text" name="PHH2O_INPUT" class="form_input input_100" value="<?= isset($info->PHH2O_INPUT) ? $info->PHH2O_INPUT : '' ?>"></td>
                        <th class="w120">수율</th>
                        <td colspan="1"><input type="text" readonly name="NA2CO3" class="form_input input_100 disabled" value="<?= isset($info->NA2CO3) ? $info->NA2CO3 : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120">Li2Co3(생산량)</th>
                        <td colspan="2"><input type="text" readonly name="PPLI2CO3_AFTER_INPUT" class="form_input input_100 disabled" value="<?= isset($info->PPLI2CO3_AFTER_INPUT) ? $info->PPLI2CO3_AFTER_INPUT : '' ?>"></td>
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
                        <?php } else{}?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>


<script>
    $(".submitBtn").click(function() {
        var check=0;
        var formData = new FormData($("#detailForm")[0]);

        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);

        if ($("input[name='PHRAW_INPUT'").val() == "") {
            alert("원료를 입력해 주세요.");
            $("input[name='PHRAW_INPUT'").focus();
        }
        if ($("input[name='PHLICL_AFTER_INPUT'").val() == "") {
            alert("LiCl를 입력해 주세요.");
            $("input[name='PHLICL_AFTER_INPUT'").focus();
        }
        if ($("input[name='PHNA2CO3_INPUT'").val() == "") {
            alert("Na2Co3를 입력해 주세요.");
            $("input[name='PHNA2CO3_INPUT'").focus();
        }
        if ($("input[name='PHH2O_INPUT'").val() == "") {
            alert("H2O를 입력해 주세요.");
            $("input[name='PHH2O_INPUT'").focus();
        }

        if(check==0)
            

        $.ajax({
            url: "<?= base_url('PROD/update_pharvest') ?>",
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


        if ($("input[name='PHRAW_INPUT'").val() == "") {
            alert("원료를 입력해 주세요.");
            $("input[name='PHRAW_INPUT'").focus();
        }
        if ($("input[name='PHLICL_AFTER_INPUT'").val() == "") {
            alert("LiCl를 입력해 주세요.");
            $("input[name='PHLICL_AFTER_INPUT'").focus();
        }
        if ($("input[name='PHNA2CO3_INPUT'").val() == "") {
            alert("Na2Co3를 입력해 주세요.");
            $("input[name='PHNA2CO3_INPUT'").focus();
        }
        if ($("input[name='PHH2O_INPUT'").val() == "") {
            alert("H2O를 입력해 주세요.");
            $("input[name='PHH2O_INPUT'").focus();
        }

        $.ajax({
            url: "<?= base_url('PROD/update_pharvest') ?>",
            type: "POST",
            dataType: "HTML",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data == 1)
                    alert("수정되었습니다.");
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
            url: "<?= base_url('PROD/del_pharvest') ?>",
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