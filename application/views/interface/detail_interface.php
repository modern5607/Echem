<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
th{
    backgnumber_format-color: #eee;
}

</style>

<div class="tbl-write01" style="margin-top: 136px;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="nhover">
        <tbody>
            <tr>
                <th class="w120">수신일</th>
                <td colspan="3"><input readonly type="text" name="ID" value="<?=empty($info->INSERT_DATE)?"":($info->INSERT_DATE)?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">탱크명</th>
                <td colspan="3"><input readonly type="text" name="ID" value="<?=empty($info)?"":$info->TANK?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">수위</th>
                <td colspan="3"><input readonly type="text" name="ID" value="<?=empty($info->LEVEL)?"":$info->LEVEL?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">온도</th>
                <td colspan="3"><input readonly type="text" name="fluxtime" value="<?=empty($info->TEMP)?"":$info->TEMP?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">PH</th>
                <td colspan="3"><input readonly type="text" name="fluxtime" value="<?=empty($info->PH)?"":number_format($info->PH)?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">CL</th>
                <td colspan="3"><input readonly type="text" name="fluxweight" value="<?=empty($info->CL)?"":number_format($info->CL)?>" class="form_input input_100"></td>
            </tr>
            <tr>
                <th class="w120">압력</th>
                <td colspan="3"><input readonly type="text" name="soldertime" value="<?=empty($info->PRESS)?"":$info->PRESS?>" class="form_input input_100"></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
// $("#INSERT_DATE").datetimepicker({
//     format: 'Y-m-d H:i:00',
//     lang: 'ko-KR'
// });


// $("#LINE2").on("change", function() {
//     if ($(this).val() == "") {
//         $("input[name='2ND_P_T']").val('');
//     }
// });

// $("#LINE3").on("change", function() {
//     if ($(this).val() == "") {
//         $("input[name='3ND_P_T']").val('');
//     }
// });
</script>