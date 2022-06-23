<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm" onsubmit="return false">
                    <?php date_default_timezone_set('Asia/Seoul');?>
                    <label for="login">로그인 날짜</label>
					<input type="text" class="calendar" size="13" name="login" id="login" autocomplete="off" value="<?php echo $str['login']?>" />

					<label for="id">아이디</label>
					<input type="text" name="id" id="id" value="<?php echo $str['id']?>" >



					<label for="admin">전체보기</label>
					<input type="checkbox" name="admin" id="admin" value="chk" <?php echo ($str['admin'] == "chk")?"checked":"" ?> >

					<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
		</header>
        
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>IP</th>
						<th>아이디</th>
						<th>로그인 시간</th>
						<th>로그아웃 시간</th>
						<th>OS</th>
						<th>BROWSER</th>
						<th>상태</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($userlog as $i=>$row){ ?>
				<tr>
					<td class="cen"><?php echo $row->IP; ?></td>
					<td class="cen"><?php echo $row->MID; ?></td>
					<td class="cen"><?php echo $row->SDATE; ?></td>
					<td class="cen"><?php echo $row->EDATE; ?></td>
					<td class="cen"><?php echo $row->OS; ?></td>
					<td class="cen"><?php echo $row->BROWSER; ?></td>
					<td class="cen"><?php echo $row->STATUS; ?></td>
				</tr>
		

				<?php
				}
				if(empty($userlog)){
				?>

					<tr>
						<td colspan="15" class="list_none">접속기록이 없습니다.</td>
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


<script>
    $(".calendar").datetimepicker({
        format:'Y-m-d',
        timepicker:false,
        lang:'ko-KR'
    });
	
//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>