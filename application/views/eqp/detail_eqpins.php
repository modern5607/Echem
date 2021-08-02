<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .re {
        color: red;
        top: 5px;
        right: 10px;
    }

    .re:after {
        content: "*";
        font-size: 16px;
        font-weight: 600;
    }

    #detailForm2 .tbl-content th {
        background: white;
    }

    #detailForm2 .tbl-content td {
        background: white;
    }
</style>

<header style="margin-bottom: 0px;">
    <div class="searchDiv">
        <form id="detailForm">
            <input type="hidden" name="mode" value="new">
            <div style="height: 34px; padding-top: 0px;"><span class="btn print new"><i class="material-icons">get_app</i>신규등록</span></div>

        </form>
    </div>
</header>


<form id="detailForm2">
    <input type="hidden" name="mode" value="<?= $mode ?>">
    <input type="hidden" name="idx" value="<?= $idx ?>">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th>관리번호</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->MNGNUM) ? "" : $List->MNGNUM ?>' name="mngnum" disabled></td>
                    <th>장비명<span class="re"></span></th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->NAME) ? "" : $List->NAME ?>' name="name"></td>
                </tr>
                <tr>
                    <th>장비구분<span class="re"></span></th>
                    <td>
                        <select name="eqpgb" id="EQGB" class="form_input input_100" >
                            <?php
                            foreach ($EQGB as $row) { 
                                    $selected = (!empty($List) && $row->D_CODE == $List->EQPGB) ? "selected" : ""; ?>
                                    <option value="<?= $row->D_CODE ?>" <?= $selected ?>> <?=$row->D_NAME?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <th>규격</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->STANDARD) ? "" : $List->STANDARD ?>' name="standard"></td>

                </tr>
                <tr>
                    <th>고유번호</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->IDENNUM) ? "" : $List->IDENNUM ?>' name="idennum"></td>
                    <th>구매일자</th>
                    <td>
                        <input type="text" class="calendar form_input input_100" autocomplete="off" value='<?= empty($List->BUYDATE) ? "" : $List->BUYDATE ?>' name="buydate" value="<?= empty($str['epodate']) ? "" : $str['epodate'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th>장비상태</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->EQPSTATUS) ? "" : $List->EQPSTATUS ?>' name="eqpstatus"></td>
                    <th>자산구분</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->ASSETGB) ? "" : $List->ASSETGB ?>' name="assetgb"></td>
                </tr>
                <tr>
                    <th>제조사</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->MAKE) ? "" : $List->MAKE ?>' name="make"></td>
                    <th>교정주기(일)<span class="re"></span></th>
                    <td>
                        <input type="number" class="form_input input_100" autocomplete="off" value='<?= empty($List->CORCYCLE) ? "" : $List->CORCYCLE ?>' name="corcycle">
                    </td>
                </tr>
                <tr>
                    <th>교정일자</th>
                    <td>
                        <input type="text" class="calendar form_input input_100" autocomplete="off" value='<?= (empty($List->CORDATE) ||$List->CORDATE=="0000-00-00") ? "" : $List->CORDATE ?>' name="cordate" disabled />
                    </td>
                    <th>차기교정일</th>
                    <td>
                        <input type="text" class="calendar form_input input_100" autocomplete="off" value='<?= (empty($List->NEXTCORDATE) || $List->NEXTCORDATE=="0000-00-00")? "" : $List->NEXTCORDATE ?>' name="nextcordate" disabled />
                    </td>
                </tr>
                <tr>
                    <th>비고</th>
                    <td colspan="3"><input type="text" class="form_input input_100" autocomplete="off" value='<?= empty($List->REMARK) ? "" : $List->REMARK ?>' name="remark"></td>
                </tr>

            </thead>
            <tfoot>
                <tr>
                    <td rowspan="5" colspan="4" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn save" data-mode="<?= $mode ?>"><?= $mode_text ?></button></td>
                </tr>

            </tfoot>
        </table>
    </div>
</form>



<script>
    //차기 교정일 날짜 계산 함수
    // function addDays(date, days) {
    //     var result = new Date(date);
    //     result.setDate(result.getDate() + days);
    //     return result;
    // }

    $(".new").click(function() {
        load2();
    });

    $("input[name='name'],input[name='eqpgb'],input[name='idennum']").on("propertychange change keyup paste input", function() {
        var NAME = $("input[name='name']");
        var EQPGB = $("input[name='eqpgb']");
        var IDENNUM = $("input[name='idennum']");

        var mngnum_string = NAME.val() + '-' + EQPGB.val() + '-' + IDENNUM.val();
        $("input[name='mngnum']").val(mngnum_string);
    });

    // //차기교정일 자동완성
    // $("input[name='corcycle'],input[name='cordate']").change(function() {
    //     var corcycle = $("input[name='corcycle']");
    //     var cordatesplits = $("input[name='cordate']").val().split('-');
    //     var cordate = new Date(cordatesplits[0], cordatesplits[1], cordatesplits[2]);
    //     console.log(cordate);
    //     if(corcycle.val()=="" || $("input[name='cordate']").val()=="")  //둘다 채워야 자동완성 실행
    //         return;

    //     var nextcordate = addDays(cordate,corcycle.val()*1);    //교정일자 + 교정주기
    //     nextcordate = nextcordate.getFullYear()+'-'+nextcordate.getMonth()+'-'+nextcordate.getDate();   //문자열 형식 맞추기
    //     $("input[name='nextcordate']").val(nextcordate);    
    // });

    $(".save").click(function() {

        var chkNumber = /[0-9]/; //숫자 정규식

        var NAME = $("input[name='name']"); //장비명
        var EQPGB = $("input[name='eqpgb']"); //장비구분
        var CORCYCLE = $("input[name='corcycle']"); //교정주기(일)
        var IDENNUM = $("input[name='idennum']"); //고유번호
        var NEXTCORDATE = $("input[name='nextcordate']"); //차기교정일
        var MNGNUM = $("input[name='mngnum']"); //차기교정일


        if (NAME.val() == "") {
            alert('장비명을 입력해주세요');
            NAME.focus();
            return false;
        }
        if (EQPGB.val() == "") {
            alert('장비구분을 입력해주세요');
            EQPGB.focus();
            return false;
        }

        if (!chkNumber.test(CORCYCLE.val())) {
            alert('숫자만 입력해주세요');
            CORCYCLE.focus();
            return false;
        } else if (CORCYCLE.val() == "") {
            alert("교정주기를 입력해주세요");
            CORCYCLE.focus();
            return false;
        }

        //ajax 형태로 변경
        var formData = new FormData($("#detailForm2")[0]);
        // formData.append("nextcordate",NEXTCORDATE.val());
        formData.append("mngnum", MNGNUM.val());
        // for (var i of formData.entries())
        //     console.log(i[0] + ", " + i[1]);

        $.ajax({
            url: "<?= base_url("EQP/eqpins_ins") ?>",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if (data > 0) {
                    alert("저장되었습니다.");
                    load();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
        return false;

    });


    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });
</script>