<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					
					<label for="name">메뉴 이름</label>
					<input type="text" name="name" id="name" value="<?php echo $str['name']?>" size="6" />
					
					<label for="code">메뉴 코드</label>
					<input type="text" name="code" id="code" value="<?php echo $str['code']?>" size="6" />

					<label for="level">권한
					</label>
					<select name="level" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<option value="1" <?php echo ($str['level'] == 1)?"selected":"";?>>일반</option>
						<option value="2" <?php echo ($str['level'] == 2)?"selected":"";?>>관리자</option>
					</select>
					
					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>메뉴 이름</th>
						<th>메뉴 코드</th>
						<th>권한</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($menuList as $i=>$row){ ?>
				<tr>
					<td class="cen"><?php echo $row->MENU_NAME; ?></td>
					<td class="cen"><?php echo $row->MENU_CODE; ?></td>
					<td class="cen">
						<select name="LEVEL" data-idx="<?php echo $row->IDX;?>" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="1" <?php echo ($row->MENU_LEVEL == 1)?"selected":"";?>>일반</option>
						<option value="2" <?php echo ($row->MENU_LEVEL == 2)?"selected":"";?>>관리자</option>
						</select>
					</td>
				</tr>
		

				<?php
				}
				if(empty($menuList)){
				?>

					<tr>
						<td colspan="15" class="list_none">메뉴정보가 없습니다.</td>
					</tr>

				<?php
				}	
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>



<div id="pop_container">
	
	<div id="info_content" class="info_content" style="height:unset;">
		
		<div class="ajaxContent"></div>
		
	</div>

</div>








<script>
$("select[name='LEVEL']").on("change",function(){
	var sqty = $(this).val();
	var idx = $(this).data("idx");
	$.post("<?php echo base_url('SYS/menu_up') ?>",{sqty:sqty,idx:idx},function(data){
		if(data > 0){
			alert("권한등급이 변경되었습니다");
			load();
		}
	});
});

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>