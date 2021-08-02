<style>
.bc__box header .print { float: right; position: relative; top: -25px; }

.book {width: 210mm;height: 297mm; margin: 0; padding: 0;background-color: #FAFAFA;font: 10pt "Tahoma";}
.page {width: 210mm;height: 297mm;}
.subpage {padding: 1cm;border: 5px red solid;height: 297mm;outline: 2cm #FFEAEA solid;}
@page {size: A4;margin: 0;}
</style>

    <div class="book">
        <div class="page">
            <div class="subpage" id="content">
			작업자 : <?= $str['worker']; ?>   일자 : <?= $str['sdate']; ?> <?= $str['edate']; ?>  <span class="material-icons close">clear</span>
				<div class="tbl-content" style="max-height:inherit;">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<thead>
							<tr>
								<th>POR NO</th>
								<th>SEQ</th>
								<th>수량</th>
								<th>총 중량</th>
								<th>품명/재질/규격</th>
								<th>제작검사시한</th>
								<th>공정</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($List as $i=>$row){?>
						<tr>
							<td class="cen"><?= $row->POR_NO; ?></td>
							<td class="cen"><?= $row->POR_SEQ; ?></td>
							<td class="right"><?= number_format($row->PO_QTY); ?></td>
							<td class="right"><?= number_format($row->WEIGHT*$row->PO_QTY,3); ?></td>
							<td><?= $row->MCCSDESC; ?></td>
							<?php if($row->PROC_YN != "Y" && (empty($str['worker']) == true OR $row->PROC_MAN == $str["worker"]) && (empty($str['date']) == true OR $str["date"] == $row->PROC_PLN )){?>
							<td class="cen"><?= $row->PROC_PLN; ?></td>
							<td class="cen" data-gjst="PROC">절단/가공</td>
							<?php
							}elseif($row->ASSE_YN != "Y" && (empty($str["worker"]) == true OR $row->ASSE_MAN == $str["worker"]) && (empty($str["date"]) == true OR $str["date"] == $row->ASSE_PLN )){?>
							<td class="cen"><?= $row->ASSE_PLN; ?></td>
							<td class="cen" data-gjst="ASSE">취부</td>
							<?php
							}elseif($row->WELD_YN != "Y" && (empty($str["worker"]) == true OR $row->WELD_MAN == $str["worker"]) && (empty($str["date"]) == true OR $str["date"] == $row->WELD_PLN )){?>
							<td class="cen"><?= $row->WELD_PLN; ?></td>
							<td class="cen" data-gjst="WELD">용접</td>
							<?php
							}elseif($row->MRO_YN != "Y" && (empty($str["worker"]) == true OR $row->MRO_MAN == $str["worker"]) && (empty($str["date"]) == true OR $str["date"] ==  $row->MRO_PLN )){?>
							<td class="cen"><?= $row->MRO_PLN; ?></td>
							<td class="cen" data-gjst="MRO">사상</td>
							<?php
							}?>
						</tr>
						<?php
						}	
						?>
						</tbody>
					</table>
				</div>
			
			</div>
        </div>
    </div>