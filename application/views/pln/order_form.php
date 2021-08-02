<h2>
    엑셀업로드
    <span class="material-icons close">clear</span>
</h2>
<div class="formContainer">

    <form name="codeHead" id="codeHead" method="post" action="<?= base_url('PLN/order_exUp')?>"
        enctype="multipart/form-data" onsubmit="return xlsxupload(this)">
        <div class="register_form">
            <fieldset class="form_1">
                <legend>이용정보</legend>
                <table>
                    <tbody>
                        <tr>
                            <th><label class="l_id">코드</label></th>
                            <td>
                                <input type="file" name="xfile" id="xfile" value=""accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            </td>
                        </tr>
                        
                        <tr>
                            <th><label class="l_id">시작행선택</label></th>
                            <td>
                                <input type="text" name="rownum" id="rownum" value="2" class="form_input" size="5" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">

                                <p>확장자(.xlsx)만 등록가능합니다.</p>
                                <p>데이터 시작열을 입력해주세요</p>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

            <div class="bcont">
                <input type="submit" class="submitBtn blue_btn" value="입력" />
            </div>

        </div>

    </form>

</div>

<script>

function xlsxupload(f){
	
	var file = $("#xfile").val();

	if(!file){
		alert("xlsx파일을 등록하세요");
		return false;
	}

	return;


}
</script>