<?php
$index = 'control';
$auth = true;

require('include/header.php');

if (isset($errors) && $errors) {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');

	sound('error');

?>	<br />
	<?php echo $Lang['ErrorCantWork']; ?><br />
	<br />
	<font class="error"><?php echo $errors; ?></font>
	<br />
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	 tableend($Lang['TheMines']);
}
elseif (checkplace('mines')) {

	tablebegin($Lang['TheMines'], 500);

	if ($action == 'mine') {

?>		<h3><?php echo $Lang['MineWork']; ?></h3>
		<font class="result"><?php echo $result; ?></font>
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=mine"><?php echo $Lang['WorkMore']; ?> &gt;&gt;</a><br />
		<br />
<?php
	}
	else {
		subbegin();

?>		<table background="images/bw.gif" width="168" height="168" cellspacing="0" cellpadding="0" hspace="4" border="0" align="right">
		<tr height="168" valign="center">
		<td><center><img src="gallery/places/mines.jpg" alt="" width="160" height="160" hspace="0" vspace="0" border="0"></center></td>
		</tr>
		</table>

		<center>
		<b><?php echo $Lang['MinesWelcome']; ?></b><br />
<?php sound('mines'); ?>
		<br />
		<font class="result"><?php echo $Lang['MinesDescription']; ?></font><br />
		<br />
		<form action="mines.php" method="POST" name="form">
		<?php echo $Lang['SpendMP']; ?>: &nbsp;
		<input type="hidden" name="action" value="mine" /><input type="text" size="6" name="amount" />
		&nbsp; <font class="capacity">Max: <?php echo $Player['mp']; ?></font><br />
		<br />
		<input type="submit" value="<?php echo $Lang['Work']; ?>" />
		</form>
		</center>

		<script>
		<!--
			document.form.amount.focus();
		//-->
		</script>
<?php
		subend();
	}

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}
else {
	tablebegin($Lang['TheMines'], 500);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
