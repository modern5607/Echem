<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<style>
	#poplv h2 {
		font-size: 1.2em;
		line-height: 24px;
		padding: 10px 15px;
		background-color: rgb(59, 77, 115);
		color: #fff;
	}

	#poplv {
		background: #fff;
		padding-bottom: 10px;
		position: absolute;
		top: 5px;
		right: -285px;
		border: 2px solid rgb(59, 77, 115);
		display: none;
		text-align: left;
	}

	#poplv:after {
		border-top: 15px solid rgb(59, 77, 115);
		border-left: 15px solid transparent;
		border-right: 0px solid transparent;
		border-bottom: 0px solid transparent;
		content: "";
		position: absolute;
		top: 10px;
		left: -15px;
	}

	#poplv>p,
	#poplv>h3 {
		padding: 3px 15px;
		margin: 0;
	}

	#poplv>p {
		font-weight: initial;
	}

	.whatlv {
		position: absolute;
		cursor: pointer;
		top: 3px;
		right: 3px;
	}
</style>

<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm" onsubmit="return false">

					<label for="mid">아이디</label>
					<input type="text" name="mid" id="mid" value="<?php echo $str['mid'] ?>" size="6" />

					<label for="mname">이름</label>
					<input type="text" name="mname" id="mname" value="<?php echo $str['mname'] ?>" size="6" />

					<label for="level">권한
					</label>
					<select name="level" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<option value="1" <?php echo ($str['level'] == 1)?"selected":"";?>>일반</option>
						<option value="2" <?php echo ($str['level'] == 2)?"selected":"";?>>관리자</option>
					</select>

					<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
			<!--span class="btn print add_member"><i class="material-icons">add</i>신규등록</span-->
			<!--span class="btn print print_head"><i class="material-icons">get_app</i>출력하기</span-->
			<!--span class="btn print write_xlsx"><i class="material-icons">get_app</i>입력하기</span-->
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th style="width: 15%;">아이디</th>
						<th style="width: 15%;">이름</th>
						<th style="width: 10%;">권한</th>
						<th style="width: 17%;">전화</th>
						<th style="width: 17%;">휴대폰</th>
						<th style="width: 18%;">이메일</th>
						<th style="width: 8%;">상태</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($List as $i => $row) { ?>
						<tr>
							<td><?php echo $row->ID; ?></td>
							<td><?php echo $row->NAME; ?></td>
							<td class="cen">
								<select name="LEVEL" data-idx="<?php echo $row->IDX; ?>"style="padding:3px 10px; border:1px solid #ddd;">
									<option value="1" <?php echo ($row->LEVEL == 1)?"selected":"";?>>일반</option>
									<option value="2" <?php echo ($row->LEVEL == 2)?"selected":"";?>>관리자</option>
								</select>
							</td>
							<td class="cen"><?php echo $row->TEL; ?></td>
							<td class="cen"><?php echo $row->HP; ?></td>
							<td><?php echo $row->EMAIL; ?></td>
							<td class="cen"><?php echo ($row->STATE == 'Y') ? "사용" : "사용안함"; ?></td>
						</tr>


					<?php
					}
					if (empty($List)) {
					?>

						<tr>
							<td colspan="15" class="list_none">회원정보가 없습니다.</td>
						</tr>

					<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<div class="pagination">
			<?php
			if($this->data['cnt'] > 20){
			?>
			<div class="limitset">
				<select name="per_page">
					<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
					<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
					<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
					<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
				</select>
			</div>
			<?php
			}	
			?>
			<?php echo $this->data['pagenation'];?>
		</div>

	</div>
</div>



<div id="pop_container">

	<div id="info_content" class="info_content" style="height:unset;">

		<div class="ajaxContent"></div>

	</div>

</div>


<script>
	$("select[name='LEVEL']").on("change", function() {
		var sqty = $(this).val();
		var idx = $(this).data("idx");
		$.post("<?php echo base_url('SYS/level_up') ?>", {
			sqty: sqty,
			idx: idx
		}, function(data) {
			if (data > 0) {
				alert("권한등급이 변경되었습니다");
				location.reload();
			}
		});
	});


	$(".whatlv").on("click", function() {
		$("#poplv").fadeToggle();
	});
</script>