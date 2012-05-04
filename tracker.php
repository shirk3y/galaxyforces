<?php
$index = 'control';
$auth = true;
$sound = 'tracker';

require('include/header.php');

if (isset($errors) && $errors) {
	 tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');

?>	<br />
	<b><?php echo $Lang['ErrorCantTrack']; ?></b><br />
	<br />
	<font class="error"><?php echo $errors; ?></font>
	<br />
	<a href="tracker.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	 tableend($Lang['Tracker']);
}
else {
	tablebegin($Lang['Tracker'], 500);

	if ($action == 'track') {

?>	<br />
	<b><?php echo $Lang['Tracker']; ?></b><br />
	<br />
	<?php echo $tracker; ?><br />
	<br />
	<a href="tracker.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	}
	elseif (checkplace('tracker')) {
		$cost = $place['parameters'];

		subbegin('images/table-b2.jpg');

?>		<table background="images/bw.gif" width="168" height="168" cellspacing="0" cellpadding="0" hspace="4" border="0" align="right">
		<tr height="168" valign="center">
		<td><center><img src="gallery/places/tracker.jpg" alt="" width="160" height="160" hspace="0" vspace="0" border="0"></center></td>
		</tr>
		</table>

		<center>
		<b><?php echo $Lang['TrackerWelcome']; ?></b><br />
		<br />
		<?php echo $Lang['TrackingCost']; ?>: <font class="result"><?php echo $cost; ?></font> [!]<br />
		<br />
		<form action="tracker.php" method="POST">
		<b><?php echo $Lang['Login']; ?></b>: &nbsp; &nbsp; <input type="text" name="name" size="8" /><br />
		<br />
		<input type="submit" value="<?php echo $Lang['Proceed']; ?>" /><input type="hidden" name="action" value="track" /><br />
		</form>
		<br />
<?php
		subend();
	}
	else echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
