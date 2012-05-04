<?php

// ===========================================================================
// Structures {structures.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-11-19
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'structures';
$auth = true;

require('include/header.php');

if (isset($Colony) && $Colony) {
	tablebegin($Lang['Structures']);

?>	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<?php
	$count = 0;
	foreach ($Structures as $s) {
		if (! isset($type)) $type = $s['type'];
		elseif ($s['type'] != $type) {
?>	<tr height="8"><td colspan="9">&nbsp;</td></tr>
	</table>
<?php
			tablebreak();
?>	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<?php
			$type = $s['type'];
		}
?>	<tr height="8"><td colspan="9">&nbsp;</td></tr>
	<tr height="72" valign="top">
	<td width="12">&nbsp;</td>
	<td width="72">
		<table background="images/pw.gif" width="72" height="72" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr height="72" valign="center">
		<td><center><a href="description.php?subject=<?php echo $s['id']; ?>&back=<?php echo $_SERVER['PHP_SELF']; ?>"><img src="gallery/buildings/icons/<?php echo $s['id']; ?>.jpg" alt="" width="64" height="64" hspace="0" vspace="0" border="0"></a></center></td>
		</tr>
		</table>
	</td>
	<td width="8">&nbsp;</td>
	<td align="left">
		<font class="result"><b><?php echo $s['name']; ?></b></font><br />
		<?php echo $s['description']; ?><br />
	</td>
	<td width="8">&nbsp;</td>
	<td width="80" align="left">
<?php
			if (isset($s['level'])) {
				$count++;
?>		<b><?php echo $Lang['Level']; ?></b>: <font class="capacity"><?php echo $s['level']; ?></font><br />
<?php
			}
			if (isset($s['amount'])) {
				$count += $s['amount'];
?>		<b><?php echo $Lang['Amount']; ?></b>: <font class="plus"><?php echo $s['amount']; ?></font><br />
<?php
			}
?>	</td>
	<td width="8">&nbsp;</td>
	<td width="72" align="left">
<?php
	if (@$s['energyratio']) echo "\t\t<b>[E] </b>" . amount($s['energyratio'], 0) . "<br />\n";
	if (@$s['siliconratio']) echo "\t\t<b>[S]</b> ".amount($s['siliconratio'], 0)."<br />\n";
	if (@$s['metalratio']) echo "\t\t<b>[M] </b>" . amount($s['metalratio'], 0) . "<br />\n";
	if (@$s['uranratio']) echo "\t\t<b>[U] </b>" . amount($s['uranratio'], 0) . "<br />\n";
	if (@$s['plutoniumratio']) echo "\t\t<b>[P]</b> ".amount($s['plutoniumratio'], 0)."<br />\n";
	if (@$s['deuteriumratio']) echo "\t\t<b>[D]</b> ".amount($s['deuteriumratio'], 0)."<br />\n";
	if (@$s['foodratio']) echo "\t\t<b>[F] </b>" . amount($s['foodratio'], 0) . "<br />\n";

?>	</td>
	<td width="4">&nbsp;</td>
	<td width="72" align="left">
<?php
	if (isset($s['energycapacity'])) echo "\t\t<b>[E] </b><font class=\"capacity\">" . div($s['energycapacity']) . "</font><br />\n";
	if (@$s['siliconcapacity']) echo "\t\t<b>[S] </b><font class=\"capacity\">".div($s['siliconcapacity'])."</font><br />\n";
	if (@$s['metalcapacity']) echo "\t\t<b>[M] </b><font class=\"capacity\">".div($s['metalcapacity'])."</font><br />\n";
	if (isset($s['urancapacity'])) echo "\t\t<b>[U] </b><font class=\"capacity\">" . div($s['urancapacity']) . "</font><br />\n";
	if (isset($s['foodcapacity'])) echo "\t\t<b>[F] </b><font class=\"capacity\">" . div($s['foodcapacity']) . "</font><br />\n";
	if (@$s['plutoniumcapacity']) echo "\t\t<b>[P] </b><font class=\"capacity\">".div($s['plutoniumcapacity'])."</font><br />\n";
	if (@$s['deuteriumcapacity']) echo "\t\t<b>[D] </b><font class=\"capacity\">".div($s['deuteriumcapacity'])."</font><br />\n";
	if (isset($s['flats'])) echo "\t\t<b>[P] </b><font class=\"capacity\">" . div($s['flats']) . "</font><br />\n";
	if (isset($s['barracks'])) echo "\t\t<b>[S] </b><font class=\"capacity\">" . div($s['barracks']) . "</font><br />\n";
?>	</td>
	<td width="4">&nbsp;</td>
	<td width="110" align="right">
<?php	if (isset($s['amount'])) {
?>		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST">
		<input type="hidden" name="action" value="destroybuildings" />
		<input type="hidden" name="name" value="<?php echo $s['id']; ?>" />
		<input size="4" maxlength="8" name="amount" value="0" />&nbsp;<input type="submit" value="<?php echo $Lang['destroy']; ?>" />
		</form>
<?php
	}
	else echo "&nbsp;";

	if (isset($Colony[$s['id'] . 'off']))
		if ($Colony[$s['id'] . 'off']) echo "\t\t<center><a href=\"structures.php?action=enable&name=${s['id']}\">${Lang['Enable']} &gt;&gt;</a></center><br />";
		else echo "\t\t<center><a class=\"delete\" href=\"structures.php?action=disable&name=${s['id']}\">${Lang['Disable']} &gt;&gt;</a></center><br />";

?>	</td>
	<td width="4">&nbsp;</td>
	</tr>
<?php
	}
?>	<tr height="8"><td colspan="9">&nbsp;</td></tr>
	</table>
<?php
	tableend($count . $Lang[' structure(s)']);
}
else {
	tablebegin($Lang['Colony'], 500);
?>	<br />
	<b><?php echo $Lang['HaveNoColony']; ?></b><br />
	<br />
	<?php echo $Lang['NoColonyTip']; ?><br />
	<br />
	<a href="create.php"><?php echo $Lang['CreateColony']; ?> &gt;&gt;</a><br />
	<br />
	<a href="control.php"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
	tableend($Lang['Structures']);
}

require('include/footer.php');
