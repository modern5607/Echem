<header>
    <div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
        <span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span>
        <form id="ajaxForm">
            <label>MP납기일</label>
            <input type="text" class="calendar" name="mpdate" value="<?= empty($str['mpdate']) ? date("Y-m-d",time()) : $str['mpdate'] ?>" />


            <?php
            if(!empty($SJGN)){
                ?>
                    <label for="sjgb">공정구분</label>
                    <select name="sjgb" id="sjgb" class="form_select">
                        
                    <?php
                    foreach($SJGB as $row){
                    ?>
                        <option value="<?php echo $row->D_CODE?>" <?php echo ($str['sjgb'] == $row->D_CODE)?"selected":"";?>><?php echo $row->D_NAME;?></option>
                    <?php
                    }
                    ?>
                    </select>
                <?php
                }
                ?>
            <button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
        </form>
    </div>
</header>

<style>
    .tbl-content table th {
        padding: 5px;
    }

    .tbl-content table td {
        white-space: nowrap;
    }
</style>

<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <th><?= $List[0]->NAME?></th>
                <th>이월</th>
                <th><?= $List[0]->_0MONTH?></th>
                <th><?= $List[0]->_1MONTH?></th>
                <th><?= $List[0]->_2MONTH?></th>
                <th><?= $List[0]->_3MONTH?></th>
                <th><?= $List[0]->_4MONTH?></th>
                <th><?= $List[0]->_5MONTH?></th>
                <th><?= $List[0]->_6MONTH?></th>

            </tr>
        </thead>
        <tbody>
            <?php
            $tmpList = $List[0];
            // array_splice($tmpList,1,8);
            // echo var_dump($tmpList);
            unset($List[0]);
            foreach ($List as $i => $row) {
            ?>
                <tr>
                    <td class="cen"><?= $row->NAME?></td>
                    <td class="cen <?=($row->__1MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-type="ER" data-date="<?=$tmpList->__1MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->__1MONTH==0)?"":number_format($row->__1MONTH)?></td>
                    <td class="cen <?=($row->_0MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_0MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_0MONTH==0)?"":number_format($row->_0MONTH)?></td>
                    <td class="cen <?=($row->_1MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_1MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_1MONTH==0)?"":number_format($row->_1MONTH)?></td>
                    <td class="cen <?=($row->_2MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_2MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_2MONTH==0)?"":number_format($row->_2MONTH)?></td>
                    <td class="cen <?=($row->_3MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_3MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_3MONTH==0)?"":number_format($row->_3MONTH)?></td>
                    <td class="cen <?=($row->_4MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_4MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_4MONTH==0)?"":number_format($row->_4MONTH)?></td>
                    <td class="cen <?=($row->_5MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_5MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_5MONTH==0)?"":number_format($row->_5MONTH)?></td>
                    <td class="cen <?=($row->_6MONTH==''||$row->DESC_GBN=='')?'':"link_s1"?>" data-date="<?=$tmpList->_6MONTH?>" data-desc="<?=$row->DESC_GBN?>"><?= ($row->_6MONTH==0)?"":number_format($row->_6MONTH)?></td>
                </tr>
            <?php
            }
            if (empty($List)) {
            ?>

                <tr>
                    <td colspan="15" class="list_none">제품정보가 없습니다.</td>
                </tr>

            <?php
            }
            ?>
        </tbody>
    </table>

</div>


<div id="pop_container">
    <div id="info_content" class="info_content" style="height:unset; width: 900px;">
        <div class="ajaxContent" style="width:900px ;"></div>
    </div>
</div>


<script>
    
    $(document).on("click", "h2 > span.close", function() {
        $(".ajaxContent").html('');
        $("#pop_container").fadeOut();
        $(".info_content").css("top", "-50%");
    });



    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });

    $(".link_s1").click(function(){
        var ER = $(this).data("type");

        $("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);
        var date = $(this).data("date");
        var desc = $(this).data("desc");
        
        $.ajax({
        type: "POST",
        url: "<?=base_url("PLN/plan_form") ?>",
        data: {
            type:ER,
            date:date,
            desc:desc
        },
        dataType: "html",
        success: function (data) {
            $('.ajaxContent').html(data);
        }
    });
    });
</script>