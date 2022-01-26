<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	#detailForm2{
		padding-top:86px
	}
    #detailForm2 .tbl-content th {
        background: white;
    }

    #detailForm2 .tbl-content td {
        background: white;
    }
</style>


<form id="detailForm2">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th>품명</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>

                    <th>수량</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>
                </tr>
                <tr>
                    <th>납기요청일</th>
                    <td><input type="text" class="form_input input_100 calendar" autocomplete="off" value='<?= date('Y-m-d') ?>' name=""></td>
                        
                    <th>납기예정일</th>
                    <td><input type="text" class="form_input input_100 calendar" autocomplete="off" value='<?= date('Y-m-d') ?>' name=""></td>
                </tr>
                <tr>
                    <th>거래처</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>

                    <th>거래처 담당자</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>
                </tr>
                <tr>
                    <th>등록일</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>

                    <th>등록 ID</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td rowspan="5" colspan="4" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn save" >저장</button></td>
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
</script>