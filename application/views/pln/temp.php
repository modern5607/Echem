<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<div id="pageTitle">
    <h1><?php echo $title; ?></h1>
</div>

<style>
.tbl-content table th{padding: 5px;}
.tbl-content table td{white-space: nowrap;}

</style>

<div class="bdcont_100">
    <div class="bc__box100">
        <header>
            <!-- <span class="btn print add_notice"><i class="material-icons">add</i>신규등록</span> -->

        </header>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr style="font-size: 11px;">
                        <th>호선</th>
                        <th>계약번호</th>
                        <th>POR번호</th>
                        <th>SEQ</th>
                        <th>단중</th>
                        <th>총중량</th>
                        <th>계약량</th>
                        <th>계약금액</th>
                        <th>입고량</th>
                        <th>품명/재질/규격</th>
                        <th>ACTIVITY</th>
                        <th>소요부서</th>
                        <th>제작업체</th>
                        <th>후처리</th>
                        <th>후처리업체</th>
                        <th>POR발행일</th>
                        <th>계약일</th>
                        <th>MP납기</th>
                        <th>제작검사시한</th>
                        <th>제작검사완료</th>
                        <th>제작검사차이</th>
                        <th>포장검사시한</th>
                        <th>포장검사완료</th>
                        <th>포장검사차이</th>
                        <th>제작관리예약</th>
                        <th>제작관리인계시한</th>
                        <th>제작관리인계입고</th>
                        <th>제작관리입고</th>
                        <th>제작관리차이</th>
                        <th>후처리예약</th>
                        <th>후처리시한</th>
                        <th>후처리완료</th>
                        <th>후처리입고</th>
                        <th>자재배송요청</th>
                        <th>자재배송완료</th>
                        <th>자재배송차이</th>
                        <th>배송요청번호</th>
                        <th>배송장소</th>
                        <th>배송요청자</th>
                        <th>요청자연락처</th>
                        <th>배송요청부서</th>
                        <th>배송담당자</th>
                        <th>사급요청번호</th>
                        <th>사급요청일</th>
                        <th>사급업체</th>
                        <th>MPPL공사</th>
                        <th>MPPLNO</th>
                        <th>MPPLSEQ</th>
                        <th>구매담당</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($List as $i => $row) {
                        // $num = $i+1; 
                    ?>
                        <tr style="font-size: 11px;">
                            <td><?= $row->PJT_NO ?></td>
                            <td><?= $row->PO_NO ?></td>
                            <td><?= $row->POR_NO ?></td>
                            <td><?= $row->POR_SEQ ?></td>
                            <td class="cen"><?= $row->UNITW +0?></td>
                            <td class="cen"><?= $row->WEIGHT+0 ?></td>
                            <td class="cen"><?=number_format($row->PO_QTY)?></td>
                            <td class="cen"><?=number_format($row->PO_AMT)?></td>
                            <td class="cen"><?=number_format($row->REC_QTY)?></td>
                            <td><?= $row->MCCSDESC ?></td>
                            <td><?= $row->ACTIVITY ?></td>
                            <td><?= $row->PORRQDT ?></td>
                            <td><?= "" ?></td>
                            <td><?= $row->AREANO ?></td>
                            <td><?= $row->AVCODE ?></td>
                            <td><?= $row->PORWRDA ?></td>
                            <td><?= $row->POWRDA ?></td>
                            <td><?= $row->PORRQDA ?></td>
                            <td><?= $row->INRQDA ?></td>
                            <td><?= $row->INSDA ?></td>
                            <td class="cen"><?= $row->INSDIF ?></td>
                            <td><?= $row->INSPAC ?></td>
                            <td><?= $row->INSPDA ?></td>
                            <td class="cen"><?= $row->INSPDIF ?></td>
                            <td><?= "" ?></td>
                            <td><?= $row->MANAGRQ ?></td>
                            <td><?= $row->MANAGDA ?></td>
                            <td><?= "" ?></td>
                            <td><?= "" ?></td>
                            <td><?= "" ?></td>
                            <td><?= $row->POSTRQ ?></td>
                            <td><?= $row->POSTDA ?></td>
                            <td><?= $row->POSTINDA ?></td>
                            <td><?= $row->REGDAG ?></td>
                            <td><?= $row->TRNDAG ?></td>
                            <td class="cen"><?= $row->TMDIF ?></td>
                            <td><?= $row->REQNO ?></td>
                            <td><?= $row->SHOP ?></td>
                            <td><?= $row->REQMPNO ?></td>
                            <td><?= $row->TEL_NO ?></td>
                            <td><?= "" ?></td>
                            <td><?= "" ?></td>
                            <td><?= "" ?></td>
                            <td><?= "" ?></td>
                            <td><?= $row->VENDORCD ?></td>
                            <td><?= "" ?></td>
                            <td><?= $row->MPPLNO ?></td>
                            <td><?= "" ?></td>
                            <td><?= "" ?></td>

                        </tr>
                    <?php
                    }
                    if (empty($List)) {
                    ?>
                        <tr>
                            <td colspan="15" class="list_none">데이터가 없습니다.</td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>