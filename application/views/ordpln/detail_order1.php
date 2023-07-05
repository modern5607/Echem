
<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- 주문등록 디테일 복사 뷰 -->

<style>
    #detailForm2 {
        padding-top: 80px
    }

    #detailForm2 .tbl-content th {
        background: white;
    }

    #detailForm2 .tbl-content td {
        background: white;
    }
</style>
<?php
if (!empty($str['idx']) && $str['idx'] != "r") {
    $check = isset($list[0]->SDATE) ? "Y" : "N";    
    $adate = $list[0]->INSERT_DATE;
    $ddate = $list[0]->INSERT_DATE;
    $tTank = $list[0]->T_TANK;
    $tSu = $list[0]->T_SU;
    $tJd = $list[0]->T_JD;
    $na2co3In = $list[0]->NA2CO3_IN;
    $na2co3 = $list[0]->NA2CO3;
    $ps1 = $list[0]->PS_1;
    $ps2 = $list[0]->PS_2;
    $ps3 = $list[0]->PS_3;
    $otOut = ROUND($list[0]->OT_OUT, 3);
    $otCol = $list[0]->OT_COL;
    $remark = $list[0]->REMARK;
    $biz = $list[0]->BIZ_IDX;
    $biznm = $list[0]->BIZ_NAME;
    $remark1 = $list[0]->REMARK1;
    $remark01 = $list[0]->REMARK01;
    $useWl = $list[0]->USE_WL;
    $otNa = $list[0]->OT_NA;
    $otWt = $list[0]->OT_WT;
    $lWl = $list[0]->L_WL;
    $lJd = $list[0]->L_JD;
    $lSu = $list[0]->L_SU;
    $uWl = $list[0]->U_WL;
    $uJd = $list[0]->U_JD;
    $uSu = $list[0]->U_SU;
    $dWl = $list[0]->D_WL;
    $uSu = $list[0]->U_SU;
    $dJd = $list[0]->D_JD;
    $dSu = $list[0]->D_SU;
    $zWl = $list[0]->Z_WL;
    $zJd = $list[0]->Z_JD;
    $zSu = $list[0]->Z_SU;

} else {
    $adate = date('Y-m-d');
    $ddate = date('Y-m-d');
    $check = '';    
    $tTank = '';    
    $tSu = '';    
    $tJd = '';    
    $na2co3In = '';    
    $na2co3 = '';    
    $ps1 = '';    
    $ps2 = '';    
    $ps3 = '';    
    $otOut = '';    
    $otCol = '';    
    $remark = '';    
    $biz = '';    
    $biznm = '';    
    $remark1 = '';    
    $remark01 = '';    
    $useWl = '';    
    $otNa = '';    
    $otWt = '';    
    $lWl = '';    
    $lJd = '';    
    $lSu = '';    
    $uWl = '';    
    $uJd = '';    
    $uSu = '';    
    $dWl = '';    
    $uSu = '';    
    $dJd = '';    
    $dSu = '';    
    $zWl = '';    
    $zJd = '';    
    $zSu = '';    

}
?>


<form id="detailForm2">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
            <thead>
                <tr>
                    <th colspan="4" style="background:#D9E5FF;">원료 정보</th>
                    <th class="res">등록일</th>
                    <td><input type="text" class="form_input input_100 calendar" autocomplete="off" value='<?= $adate ?>' name="INSERT_DATE"></td>
                </tr>
                <tr>
                    <!-- <th>L 사원료</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $lWl ?>' name="L_WL"></td> -->
                    <th colspan="2" >L 사 수용액</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $lSu ?>' name="L_SU"></td>
                    <th colspan="2" >L 사 전도도</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $lJd ?>' name="L_JD"></td>
                </tr>
                <tr>
                    <!-- <th>U 사원료</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $uWl ?>' name="U_WL"></td> -->
                    <th colspan="2">U 사 수용액</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $uSu ?>' name="U_SU"></td>
                    <th colspan="2">U 사 전도도</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $uJd ?>' name="U_JD"></td>
                </tr>
                <tr>
                    <!-- <th>D 사원료</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $dWl ?>' name="D_WL"></td> -->
                    <th colspan="2">D 사 수용액</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $dSu ?>' name="D_SU"></td>
                    <th colspan="2">D 사 전도도</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $dJd ?>' name="D_JD"></td>
                </tr>
                <tr>
                    <!-- <th>기타</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $zWl ?>' name="Z_WL"></td> -->
                    <th colspan="2">E 사 수용액</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $zSu ?>' name="Z_SU"></td>
                    <th colspan="2">E 사 전도도</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $zJd ?>' name="Z_JD"></td>
                </tr>

                <tr>
                    <th colspan="2" style="background:#D9E5FF; ">5T 반응탱크</th>
                    <th>수용액 양</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $tTank ?>' name="T_TANK"></td>
                    <th>전도도</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $tJd ?>' name="T_JD"></td>
                </tr>

                <tr>
                    <th colspan="2" style="background:#D9E5FF; ">탄산나트륨</th>
                    <th>파우더 량</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $na2co3 ?>' name="NA2CO3"></td>
                    <th>수용액 량</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $na2co3In ?>' name="NA2CO3_IN"></td>
                </tr>

                <tr>
                    <th colspan="4" style="background:#D9E5FF; ">생산 제품</th>
                    <th>생산량</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $otOut ?>' name="OT_OUT"></td>

                </tr>
                <tr>
                    <th>색상</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $otCol ?>' name="OT_COL"></td>
                    <th>나트튬 함량</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $otNa ?>' name="OT_NA"></td>
                    <th>수분량</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $otWt ?>' name="OT_WT"></td>
                </tr>

                <tr>
                    <th colspan="6" style="background:#D9E5FF; ">폐수</th>
                </tr>
                <tr>
                    <th>1차 폐수</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $ps1 ?>' name="PS_1"></td>
                    <th>2차 폐수</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $ps2 ?>' name="PS_2"></td>
                    <th>3차 폐수</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $ps3 ?>' name="PS_3"></td>
                </tr>
               
                <tr>
                    <th>비고</th>
                    <td colspan="5">
                        <textarea name="REMARK01" id="" style="resize:none;" class="form_input input_100" rows="2"><?= $remark01 ?></textarea>
                    </td>
                </tr>

            </thead>
            <tfoot>
                <tr>
                    <?php if (!empty($str['idx']) && $str['idx'] != "r") { ?>
                        <?php if ($check == 'N') { ?>
                            <td rowspan="5" colspan="6" class="cen" style="padding: 15px;">
                                <button type="button" class="btn blue_btn upBtn">수정</button>
                                <button type="button" class="btn blue_btn delBtn">삭제</button>
                            </td>
                        <?php } else { ?>
                <tr>
                                <td rowspan="5" colspan="6" class="cen" style="padding: 15px;">
                                <button type="button" class="btn blue_btn upBtn">수정</button>
                                <button type="button" class="btn blue_btn delBtn">삭제</button>
                            </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <td rowspan="5" colspan="6" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn submitBtn">저장</button></td>
        <?php } ?>
        </tr>
            </tfoot>
        </table>
    </div>
</form>



<script>
    <?php if ($check == 'Y') { ?>
        $("#detailForm2 input").attr("readonly", true);
        $("#detailForm2 textarea").attr("readonly", true);
        $("#detailForm2 .calendar").removeClass("calendar");
        $('#detailForm2 option').attr('disabled', true);
    <?php } ?>

    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });


    $(".submitBtn").on("click", function() {

        var formData = new FormData();
        formData.append("INSERT_DATE", $("input[name='INSERT_DATE']").val());
        formData.append("T_TANK", $("input[name='T_TANK']").val());
        formData.append("T_SU", $("input[name='T_SU']").val());
        formData.append("T_JD", $("input[name='T_JD']").val());
        formData.append("NA2CO3_IN", $("input[name='NA2CO3_IN']").val());
        formData.append("NA2CO3", $("input[name='NA2CO3']").val());
        formData.append("OT_OUT", $("input[name='OT_OUT']").val());
        formData.append("OT_COL", $("input[name='OT_COL']").val());
        formData.append("REMARK", $("input[name='REMARK']").val());
        formData.append("USE_WL", $("input[name='USE_WL']").val());
        formData.append("REMARK1", $("textarea[name='REMARK1']").val());
        formData.append("REMARK01", $("textarea[name='REMARK01']").val());
        formData.append("OT_NA", $("input[name='OT_NA']").val());
        formData.append("OT_WT", $("input[name='OT_WT']").val());
        formData.append("L_WL", $("input[name='L_WL']").val());
        formData.append("L_JD", $("input[name='L_JD']").val());
        formData.append("L_SU", $("input[name='L_SU']").val());
        formData.append("U_WL", $("input[name='U_WL']").val());
        formData.append("U_JD", $("input[name='U_JD']").val());
        formData.append("U_SU", $("input[name='U_SU']").val());
        formData.append("D_WL", $("input[name='D_WL']").val());
        formData.append("D_JD", $("input[name='D_JD']").val());
        formData.append("D_SU", $("input[name='D_SU']").val());
        formData.append("Z_WL", $("input[name='Z_WL']").val());
        formData.append("Z_JD", $("input[name='Z_JD']").val());
        formData.append("Z_SU", $("input[name='Z_SU']").val());
        formData.append("PS_1", $("input[name='PS_1']").val());
        formData.append("PS_2", $("input[name='PS_2']").val());
        formData.append("PS_3", $("input[name='PS_3']").val());



        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);

        if ($("input[name='REMARK']").val() == "") {
            alert("생산정보를 입력하세요");
            $("input[name='REMARK']").focus();
            return false;
        }
        if ($("input[name='OT_OUT']").val() == "") {
            alert("생산량을 입력하세요.");
            $("input[name='OT_OUT']").focus();
            return false;
        }
        if ($("input[name='ADATE']").val() == "") {
            alert("수주일을 입력하세요.");
            $("input[name='ADATE']").focus();
            return false;
        }
        if ($("select[name='BIZ_IDX']").val() == "") {
            alert("원료명을 선택하세요");
            $("select[name='BIZ_IDX']").focus();
            return false;
        }


        if ($("input[name='QTY']").val() == "") {
            alert("수주 수량 입력하세요.");
            $("input[name='QTY']").focus();
            return false;
        }
        if ($("input[name='QTY']").val() == "0") {
            alert("1이상 입력하세요.");
            $("input[name='QTY']").focus();
            return false;
        }

        $.ajax({
            url: "<?= base_url('ECHEM/order_insert1') ?>",
            type: "POST",
            data: formData,
            //asynsc : true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
    });


    $(".delBtn").on("click", function() {
        var idx = '<?= $str['idx'] ?>';

        if (confirm('삭제하시겠습니까?') !== false) {

            $.get("<?= base_url('ECHEM/del_order1') ?>", {
                idx: idx
            }, function(data) {
                location.reload();
            }, "JSON").done(function(jqXHR) {
                location.reload();
            })
        }
    });


    $(".upBtn").on("click", function() {
        var formData = new FormData();
        formData.append("INSERT_DATE", $("input[name='INSERT_DATE']").val());
        formData.append("T_TANK", $("input[name='T_TANK']").val());
        formData.append("T_SU", $("input[name='T_SU']").val());
        formData.append("T_JD", $("input[name='T_JD']").val());
        formData.append("NA2CO3_IN", $("input[name='NA2CO3_IN']").val());
        formData.append("NA2CO3", $("input[name='NA2CO3']").val());
        formData.append("OT_OUT", $("input[name='OT_OUT']").val());
        formData.append("OT_COL", $("input[name='OT_COL']").val());
        formData.append("REMARK", $("input[name='REMARK']").val());
        formData.append("USE_WL", $("input[name='USE_WL']").val());
        formData.append("REMARK1", $("textarea[name='REMARK1']").val());
        formData.append("REMARK01", $("textarea[name='REMARK01']").val());
        formData.append("BIZ", $("select[name='BIZ_IDX']").val());
        formData.append("OT_NA", $("input[name='OT_NA']").val());
        formData.append("OT_WT", $("input[name='OT_WT']").val());
        formData.append("L_WL", $("input[name='L_WL']").val());
        formData.append("L_JD", $("input[name='L_JD']").val());
        formData.append("L_SU", $("input[name='L_SU']").val());
        formData.append("U_WL", $("input[name='U_WL']").val());
        formData.append("U_JD", $("input[name='U_JD']").val());
        formData.append("U_SU", $("input[name='U_SU']").val());
        formData.append("D_WL", $("input[name='D_WL']").val());
        formData.append("D_JD", $("input[name='D_JD']").val());
        formData.append("D_SU", $("input[name='D_SU']").val());
        formData.append("Z_WL", $("input[name='Z_WL']").val());
        formData.append("Z_JD", $("input[name='Z_JD']").val());
        formData.append("Z_SU", $("input[name='Z_SU']").val());
        formData.append("PS_1", $("input[name='PS_1']").val());
        formData.append("PS_2", $("input[name='PS_2']").val());
        formData.append("PS_3", $("input[name='PS_3']").val());
        formData.append("IDX", '<?= $str['idx'] ?>');

        for (var i of formData.entries())
            console.log(i[0] + ", " + i[1]);

        if ($("input[name='REMARK']").val() == "") {
            alert("생산정보를 입력하세요");
            $("input[name='REMARK']").focus();
            return false;
        }
        if ($("input[name='OT_OUT']").val() == "") {
            alert("생산량을 입력하세요.");
            $("input[name='OT_OUT']").focus();
            return false;
        }
        if ($("input[name='ADATE']").val() == "") {
            alert("수주일을 입력하세요.");
            $("input[name='ADATE']").focus();
            return false;
        }

        if ($("input[name='QTY']").val() == "") {
            alert("수주 수량 입력하세요.");
            $("input[name='QTY']").focus();
            return false;
        }
        if ($("input[name='QTY']").val() == "0") {
            alert("1이상 입력하세요.");
            $("input[name='QTY']").focus();
            return false;
        }
        if (confirm('수정하시겠습니까?') !== false) {
            $.ajax({
                url: "<?= base_url('ECHEM/update_order1') ?>",
                type: "POST",
                data: formData,
                //asynsc : true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
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
    var number = document.getElementById('count');

    number.onkeydown = function(e) {
        if(!((e.keyCode > 95 && e.keyCode < 106)
        	|| (e.keyCode > 47 && e.keyCode < 58) 
        	|| e.keyCode == 8)) {
            return false;
        }
    }
</script>