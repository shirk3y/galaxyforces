<?php

require('include/header.php');

locale('website/welcome');

tablebegin('Galaxy Forces', 550);

?>
	<br />
	
	<table width="100%">
	<tr valign="top">
	<td class="margin"></td>
	<td align="center">
<?php echo $Lang['WelcomePage']; ?>
	</td>
	<td class="margin"></td>
	<td>
<?php
	$date = date('Ymd');
	$db->query("SELECT login from galaxy_users where registered='$date' ORDER BY id DESC;");
	if ($registered = $db->numrows()) {
		$t = $db->fetchrow();
		$last = $t['login'];
	}
	else {
		$registered = 0;
		$last = '';
	}
?>
<center><b><?php echo $Lang['RegisteredToday']; ?></b>: <font class="result"><?php echo $registered; ?></font><?php if ($last) { ?><br /><br /><b><?php echo$Lang['RegisteredLast']; ?></b>: <font class="plus"><?php echo $last; ?></font><?php } ?></center>
<br />

	</td>
	<td class="margin"></td>
	<td align="right" width="168">
<?php tableimg('images/pw.gif', 72, 72, "gallery/space/icons/prophetie.jpg", 64, 64, '', 'right'); ?>
	<td class="margin"></td>
	</tr>
	</table>

	<br />
	
<?php

	tablebreak();
	subbegin();

	echo $Lang['WelcomeText'];

	if ($content = trim(@file_get_contents("COMMUNITY.txt"))) echo "<br /><br />".$content;

	subend();

	tableend('Galaxy Forces', 500);

	require('include/footer.php');
