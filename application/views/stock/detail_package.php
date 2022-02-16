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
        <div class="tbl-write01" style="margin-top: 86px;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <th class="w120">작업지시일</th>
                        <td><input type="text" readonly name="ORDER_DATE" class="form_input input_100 disabled" value="<?= isset($info->ORDER_DATE) ? $info->ORDER_DATE : '' ?>"></td>
                        <th class="w120">수주명</th>
                        <td><input type="text" readonly name="ACT_NAME" class="form_input input_100 disabled" value="<?= isset($info->ACT_NAME) ? $info->ACT_NAME : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>거래처</th>
                        <td><input type="text" readonly name="BIZ_NAME" class="form_input input_100 disabled" value="<?= isset($info->BIZ_NAME) ? $info->BIZ_NAME : '' ?>"></td>
                        <th>수주일</th>
                        <td><input type="text" readonly name="ACT_DATE" class="form_input input_100 disabled" value="<?= isset($info->ACT_DATE) ? $info->ACT_DATE : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>납품예정일</th>
                        <td><input type="text" readonly name="DEL_DATE" class="calendar form_input input_100 disabled" value="<?= isset($info->DEL_DATE) ? $info->DEL_DATE : '' ?>"></td>
                        <th>주문수량</th>
                        <td><input type="number" readonly name="QTY" class="form_input input_100 disabled" value="<?= isset($info->QTY) ? $info->QTY : '' ?>"></td>
                    </tr>
                    <tr>
                        <th class="w120">작업시작일</th>
                        <td><input type="text" name="START_DATE" class="form_input input_100 disabled" value="<?= isset($info->START_DATE) ? $info->START_DATE : '' ?>"></td>
                        <th class="w120">작업종료일</th>
                        <td><input type="text" name="END_DATE" class="form_input input_100 disabled" value="<?= isset($info->END_DATE) ? $info->END_DATE : '' ?>"></td>
                    </tr>
                    <tr>
                        <th>Li2CO3(생산량)</th>
                        <td><input type="text" readonly name="BIZ_NAME" class="form_input input_100 disabled" ></td>
                        <th>건조일</th>
                        <td><input type="text" readonly name="ACT_DATE" class="form_input input_100 disabled" ></td>
                    </tr>
                </tbody>
                <!-- <tfoot>
                    <tr>
                            <td rowspan="5" colspan="4" class="cen" style="padding: 15px;">
                                <button type="button" class="btn blue_btn upBtn">포장등록</button>
                            </td>
                    </tr>
                </tfoot> -->

            </table>
        </div>


    </form>


</div>


<script>
    //제이쿼리 수신일 입력창 누르면 달력 출력
    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });
</script>