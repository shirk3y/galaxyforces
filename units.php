<?php

// ===========================================================================
// Units {units.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-11-02
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================
 
$index = 'units';
$auth = true;

require('include/header.php');

// ===========================================================================
// GALAXOPEDIA
// ===========================================================================

if ($view == 'unitslist') {
	tablebegin($Lang['Units'], 500);

	echo '<br /><table width="100%" cellspacing="0" cellpadding="0">';

	$i = 0;
	
	foreach ($Var['units'] as $id => $unit) {
		if (!$i) echo '<tr'.($c = @$c ? '' : ' class="div"').'>';
		echo '<td class="space">&nbsp;</td><td class="pw">';
		tableimg('images/pw.gif', 72, 72, "gallery/units/$id.jpg", 64, 64 , "description.php?type=unit&subject=$id");
		echo '</td>';
		if ($i++ == 4) {
			echo '<td>&nbsp;</td></tr>';
			$i = 0;
		}
	}
	if ($i != 4) echo '<td>&nbsp;</td></tr>'; 

	echo '</table><br />';
	
	tableend('Galaxopedia');
}

// ===========================================================================
// UNITS
// ===========================================================================

elseif (isset($Colony) && $Colony) {
	tablebegin('<a href="units.php?view=unitslist">'.$Lang['Units'].'</a>');

	echo '<table width="100%" cellspacing="0" cellpadding="0">';

	$count = 0;
	if ($Units) foreach ($Units as $s) {
		if (!isset($type)) $type = $s['type'];
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
		<td><center><a href="description.php?type=unit&subject=<?php echo $s['id']; ?>&back=<?php echo $_SERVER['PHP_SELF']; ?>"><img src="gallery/units/icons/<?php echo $s['id']; ?>.jpg" alt="" width="64" height="64" hspace="0" vspace="0" border="0"></a></center></td>
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
?>		<b><?php echo $Lang['Amount']; ?></b>: <font class="plus"><?php echo div($s['amount']); ?></font><br />
<?php
			}
?>	</td>
	<td width="8">&nbsp;</td>
	<td width="72" align="left">
<?php
	if (isset($s['energyratio']) && $s['energyratio']) echo "\t\t<b>[E] </b>" . amount($s['energyratio'], 0) . "<br />\n";
	if (@$s['siliconratio']) echo "\t\t<b>[S]</b> ".amount($s['siliconratio'], 0)."<br />\n";
	if (@$s['metalratio']) echo "\t\t<b>[M]</b> ".amount($s['metalratio'], 0)."<br />\n";
	if (isset($s['uranratio']) && $s['uranratio']) echo "\t\t<b>[U] </b>" . amount($s['uranratio'], 0) . "<br />\n";
	if (@$s['plutoniumratio']) echo "\t\t<b>[P]</b> ".amount($s['plutoniumratio'], 0)."<br />\n";
	if (@$s['deuteriumratio']) echo "\t\t<b>[D]</b> ".amount($s['deuteriumratio'], 0)."<br />\n";
	if (isset($s['foodratio']) && $s['foodratio']) echo "\t\t<b>[F] </b>" . amount($s['foodratio'], 0) . "<br />\n";
?>	</td>
	<td width="4">&nbsp;</td>
	<td width="72" align="left">
<?php
	if (isset($s['workforce'])) echo "\t\t<b>[W]</b> <font class=\"work\">" . ($s['workforce']) . "</font><br />\n";
	if (@$s['scienceforce']) echo "\t\t<b>[S]</b> <font class=\"work\">${s['scienceforce']}</font><br />\n";
	if (isset($s['attack']) && $s['attack']) echo "\t\t<b>[A]</b> <font class=\"plus\">" . div($s['attack']) . "</font><br />\n";
	if (isset($s['damage'])) echo "\t\t<b>[D]</b> <font class=\"capacity\">" . div($s['damage']) . "</font><br />\n";
	if (isset($s['capacity']) && $s['capacity']) echo "\t\t<b>[C]</b> <font class=\"result\">" . div($s['capacity']) . "</font><br />\n";
	if (isset($s['foodcapacity'])) echo "\t\t<b>[F]</b> <font class=\"capacity\">" . div($s['foodcapacity']) . "</font><br />\n";
	if (isset($s['flats'])) echo "\t\t<b>[P] </b><font class=\"capacity\">" . div($s['flats']) . "</font><br />\n";
	if (isset($s['barracks'])) echo "\t\t<b>[B] </b><font class=\"capacity\">" . div($s['barracks']) . "</font><br />\n";
?>	</td>
	<td width="4">&nbsp;</td>
	<td width="110" align="right">
<?php
	if (isset($s['amount'])) {

?>		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST">
		<input type="hidden" name="action" value="destroyunits" />
		<input type="hidden" name="name" value="<?php echo $s['id']; ?>" />
		<input size="4" maxlength="8" name="amount" value="0" />&nbsp;<input type="submit" value="<?php echo $Lang['destroy']; ?>" />
		</form>
<?php
		if (($Player['planet'] == $Colony['planet']) && ($type == 'fighter' || $type == 'thief')) echo "\t\t<a href=\"equipment.php?action=shipexchange&name=${s['id']}\">${Lang['Equip']} &gt;&gt;</a>\n";
	}
	else echo "&nbsp;";
?>	</td>
	<td width="4">&nbsp;</td>
	</tr>
<?php
	}
	else echo "\t<tr><td align=\"center\"><br />${Lang['No units']}</td></tr>\n";

?>	<tr height="8"><td colspan="9">&nbsp;</td></tr>
	</table>
<?php
	tableend($Lang['Count'] . ': <font class="result">' . div($count) . '</font>');
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
