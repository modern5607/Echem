<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .disabled {
        background: rgb(224, 223, 223);
    }
</style>


<form id="detailForm2">
    <div class="tbl-write01" style="margin-top: 86px;">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody>
                <tr>
                    <th class="w120">작업지시일</th>
                    <td><input type="text" disabled name="ORDER_DATE" class="form_input input_100" value="<?= isset($list[0]->ORDER_DATE) ? $list[0]->ORDER_DATE : '' ?>"></td>
                    <th class="w120">수주명</th>
                    <td><input type="text" disabled name="ACT_NAME" class="form_input input_100" value="<?= isset($list[0]->ACT_NAME) ? $list[0]->ACT_NAME : '' ?>"></td>
                </tr>
                <tr>
                    <th>거래처</th>
                    <td><input type="text" disabled name="BIZ_NAME" class="form_input input_100" value="<?= isset($list[0]->CUST_NM) ? $list[0]->CUST_NM : '' ?>"></td>
                    <th>수주일</th>
                    <td><input type="text" disabled name="ACT_DATE" class="form_input input_100" value="<?= isset($list[0]->ACT_DATE) ? $list[0]->ACT_DATE : '' ?>"></td>
                </tr>
                <tr>
                    <th>납품예정일</th>
                    <td><input type="text" disabled name="DEL_DATE" class="form_input input_100" value="<?= isset($list[0]->DEL_DATE) ? $list[0]->DEL_DATE : '' ?>"></td>
                    <th>주문수량</th>
                    <td><input type="number" disabled name="QTY" class="form_input input_100" value="<?= isset($list[0]->QTY) ? ROUND($list[0]->QTY,2) : '' ?>"></td>
                </tr>
                <tr>
                    <th class="w120">작업시작일</th>
                    <td><input type="text" disabled name="START_DATE" class="form_input input_100" value="<?= isset($list[0]->START_DATE) ? $list[0]->START_DATE : '' ?>"></td>
                    <th class="w120">작업종료일</th>
                    <td><input type="text" disabled name="END_DATE" class="form_input input_100" value="<?= isset($list[0]->END_DATE) ? $list[0]->END_DATE : '' ?>"></td>
                </tr>
                <tr>
                    <th>Li2CO3(생산량)</th>
                    <td><input type="text" disabled name="Li2CO3" class="form_input input_100" value="<?= isset($list[0]->PPLI2CO3_AFTER_INPUT) ? $list[0]->PPLI2CO3_AFTER_INPUT : '' ?>"></td>
                    <th>건조일</th>
                    <td><input type="text" disabled name="DRY_DATE" class="form_input input_100" value="<?= isset($list[0]->DRY_DATE) ? $list[0]->DRY_DATE : '' ?>"></td>
                </tr>
            </tbody>
            <?php
            if(is_numeric($str['idx']) && $list[0]->PACKAGE_YN != 'Y'){
                echo 
                '<tfoot>
                    <tr>
                            <td rowspan="5" colspan="4" class="cen" style="padding: 15px;">
                                <button type="button" class="btn blue_btn upBtn">포장등록</button>
                            </td>
                    </tr>
                </tfoot>';
            } ?>

        </table>
    </div>


</form>




<script>
    //제이쿼리 수신일 입력창 누르면 달력 출력
    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });

    $(".upBtn").click(function() {
        
	var formData = new FormData();
	formData.append("idx", '<?= $str["idx"] ?>');

        $.ajax({
            url: "<?= base_url('STOCK/update_package') ?>",
            type: "POST",
            dataType: "HTML",
			data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                alert('포장이 등록됐습니다.')
                load1()
            }
        })
    });
</script>