<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .head_table .tbl-content table thead th {
        font-size: 11px;
        padding: 7px;
    }

    .head_table .tbl-content table td {
        white-space: nowrap;
    }
</style>
<header>
    <div class="searchDiv">
        <form id="headForm">
            <label>장비구분</label>
            <select name="eqgb" id="EQGB" class="form_select">
                <option value="">전체</option>
                <?php
                foreach ($EQGB as $row) { ?>
                    <option value="<?= $row->D_CODE ?>" <?= ($str['eqgb'] == $row->D_CODE) ? "selected" : ""; ?>><?= $row->D_NAME; ?></option>
                <?php }?>
            </select>
            <label>장비명</label>
            <input type="text" name="eqname" value="<?= $str['eqname']; ?>" />

            <button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
        </form>
    </div>
</header>


<div class="head_table">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th>순번</th>
                    <th>관리번호</th>
                    <th>장비구분</th>
                    <th>장비명</th>
                    <th>규격</th>
                    <th>구매일</th>
                    <th>제조사</th>
                    <th>주기(일)</th>
                    <th>교정일</th>
                    <th>차기교정일</th>
                    <th>고유번호</th>
                    <th>장비상태</th>
                    <th>자산구분</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($List)) {
                    foreach ($List as $i => $row) {
                        $num = $i + 1;
                ?>
                        <tr data-idx="<?= $row->IDX ?>" style="cursor: pointer;" class="eqpitem">
                            <td class="cen"><?= $num ?></td>
                            <td><?= $row->MNGNUM ?></td>
                            <td><?= $row->EQPGB ?></td>
                            <td><?= $row->NAME ?></td>
                            <td><?= $row->STANDARD ?></td>
                            <td><?= $row->BUYDATE ?></td>
                            <td><?= $row->MAKE ?></td>
                            <td class="right"><?= $row->CORCYCLE ?></td>
                            <td><?=(empty($row->CORDATE) || $row->CORDATE=='0000-00-00')?"": $row->CORDATE ?></td>
                            <td><?=(empty($row->NEXTCORDATE) || $row->NEXTCORDATE=='0000-00-00')?"": $row->NEXTCORDATE ?></td>
                            <td><?= $row->IDENNUM ?></td>
                            <td><?= $row->EQPSTATUS ?></td>
                            <td><?= $row->ASSETGB ?></td>
                        </tr>

                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="15" class="list_none">등록된 장비가 없습니다</td>
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

<script>
    $(document).off("click", ".eqpitem");
    
    $(".eqpitem").click(function() {
        var idx = $(this).data("idx");
        var mngnum = $(":nth-child(2)",this).html();
        var name = $(":nth-child(3)",this).html();

        console.log("mngnum:"+mngnum);
        console.log("name:"+name);
        $(".eqpitem").removeClass("over");
        $(this).addClass("over");

        $.ajax({
            url: "<?= base_url('EQP/detail_eqphis') ?>",
            type: "post",
            data: {
                idx: idx,
                mngnum:mngnum,
                name:name,
                mode: "modify"
            },
            dataType: "html",
            cache: false,
            success: function(data) {
                $("#ajax_detail_container").empty();
                $("#ajax_detail_container").html(data);
            }
        });
    });
</script>