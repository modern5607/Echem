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

<!-- <header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="headForm">
			< <label>발주 요청일</label>
			<input type="text" name="date" value="" class="calendar" / >
			
			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn add add_head"><i class="material-icons">add</i>추가</span> >
</header> -->


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
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>
                        
                    
                    <th>납기예정일</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='' name=""></td>
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

                    <th>등로 ID</th>
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
