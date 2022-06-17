<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<div class="bdcont_100">
    <div class="">
        <header>
            <div style="float:left;">
                <form id="ajaxForm">
                    <input type="hidden" name="pageNum" value="<?=$pageNum?>">
                    <label for="title">제목</label>
                    <input type="text" name="title" id="title" value="<?= $str['title'] ?>" size="12" onkeypress="if(event.keyCode=='13')load();" />

                    <label for="cont">내용</label>
                    <input type="text" name="cont" id="cont" value="<?= $str['cont'] ?>" size="12" onkeypress="if(event.keyCode=='13')load();"/>

                    <button class="search_submit ajax_search"><i class="material-icons">search</i></button>
                </form>
            </div>
            <span class="btn print add_notice"><i class="material-icons">add</i>신규등록</span>
        </header>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>제목</th>
                        <th>내용</th>
                        <th>생성일</th>
                        <th>종료일</th>
                        <th>첨부여부</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($List as $i => $row) { 
                        $num = $pageNum+$i+1; 
                        $isfile =''; 
                        ?>
                        <tr>
                            <td class="cen"><?= $num?></td>
                            <td><?=$row->TITLE?></td>
                            <td class="cen"><?=$row->CONTENT?></td>
                            <td><?= date("Y-m-d",strtotime($row->INSERT_DATE))?></td>
                            <td><?=$row->END_DATE?></td>
                            <td><?=$isfile?></td>
                        </tr>
                    <?php
                    }
                    if (empty($List)) {
                    ?>
                        <tr>
                            <td colspan="15" class="list_none">공지사항이 없습니다.</td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="pagination noflex">
            <?= $this->data['pagenation']; ?>
            <?php
            if ($this->data['cnt'] > 20) {
            ?>
                <div class="limitset">
                    <select name="per_page">
                        <option value="20" <?= ($perpage == 20) ? "selected" : ""; ?>>20</option>
                        <option value="50" <?= ($perpage == 50) ? "selected" : ""; ?>>50</option>
                        <option value="80" <?= ($perpage == 80) ? "selected" : ""; ?>>80</option>
                        <option value="100" <?= ($perpage == 100) ? "selected" : ""; ?>>100</option>
                    </select>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
</div>



<div id="pop_container">

    <div id="info_content" class="info_content" style="height:unset;">

        <div class="ajaxContent"></div>

    </div>

</div>


<script>
$('.add_notice').click(function (){
    $("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

    $.ajax({
        type: "POST",
        url: "<?=base_url("MIF/notice_form") ?>",
        data: "data",
        dataType: "html",
        success: function (data) {
            $('.ajaxContent').html(data);
        }
    });

});


</script>