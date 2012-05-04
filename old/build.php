<?php

// ===========================================================================
// Build {build.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.2
//	Modified:	2005-11-11
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'builds';
$auth = true;
$js[] = 'functions';

require('include/header.php');

$pagename = $Lang['Build'];

if (! @$Colony) $errors .= "${Lang['NotAvailable']}<br />";

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />\n";
	sound('error');
	tableend("<a href=\"colony.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// BUILD
// ===========================================================================

else {
	echo "\t<script>\n\t<!--\n\tfunction ask(\$url)\n\t{\n\t\tif (confirm('${Lang['AreYouSure?']}')) location.href = \$url;\n\t}\n\t//-->\n\t</script>\n";

	if ($Buildings) {
		tablebegin($pagename, 500);
		subbegin();

		tableimg('images/bw.gif', 168, 168, "gallery/buildings/${Buildings['name']}.jpg", 160, 160, '', 'right');

		echo "\t<center><font class=\"h3\">${Lang['Building']}</font></center>\n";
?>	<br />
	<b><?php echo $Lang['Name']; ?></b>: <font class="plus"><?php echo $Builds[$Buildings['name']]['name']; ?></font><br />
	<b><?php echo $Lang['Amount']; ?></b>: <font class="result"><?php echo $Buildings['amount']; ?></font><br />
	<b><?php echo $Lang['Progress']; ?></b>: <font class="capacity"><?php echo round(100 * ($stardate - $Buildings['begin']) / $Buildings['time']); ?> %</font><br />
	<br />
	<?php echo $Lang['FullETA']; ?>: <b><?php echo eta($Buildings['end'] - $stardate); ?><br />
	<br />
	<center><a href="javascript:ask('<?php echo $_SERVER['PHP_SELF']; ?>?action=cancelbuilding')" class="delete"><?php echo $Lang['BuildC']; ?> &gt;&gt;</a></center>
	<br />
<?php
		sound('building');

		subend();
		tableend($Lang['Build']);
	}
	elseif ($action == 'build') {
		if ($errors) {
			tablebegin('<font color="red" class="error">' . $Lang['Error'] . '!</font>', '400');

?>	<br />
	<b><?php echo $Lang['ErrorCantBuild']; ?></b><br />
	<br />
	<font color="red" class="error"><?php echo $errors; ?></font>
	<br />
	<a href="javascript:history.back(1)"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
	<br />
<?php
		 	tableend($Lang['Build']);
			sound('error');
		}
		else {
			tablebegin($Lang['Build'], 500);
?>		<br />
		<font class="h3"><?php echo $Lang['BuildS']; ?></font><br />
		<br />
		<table align="center" cellspacing="0" cellpadding="0" border="0">
<?php
 			if ($Cost['credits']) {
?>		<tr>
		<td><?php echo $Lang['Credits']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><b><?php echo div($Cost['credits']); ?></b></td>
		</tr>
<?php
			}
 			if ($Cost['energy']) echo "\t<tr><td>${Lang['Energy']}:</td><td></td><td><b>".div($Cost['energy'])."</b></td></tr>\n";
 			if ($Cost['silicon']) echo "\t<tr><td>${Lang['Silicon']}:</td><td></td><td><b>".div($Cost['silicon'])."</b></td></tr>\n";
 			if ($Cost['metal']) echo "\t<tr><td>${Lang['Metal']}:</td><td></td><td><b>".div($Cost['metal'])."</b></td></tr>\n";
 			if ($Cost['uran']) {
?>		<tr>
		<td><?php echo $Lang['Uran']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><b><?php echo div($Cost['uran']); ?></b></td>
		</tr>
<?php
			}
 			if ($Cost['crystals']) {
?>		<tr>
		<td><?php echo $Lang['Crystals']; ?>:</td>
		<td>&nbsp; &nbsp;</td>
		<td><b><?php echo div($Cost['crystals']); ?></b></td>
		</tr>
<?php
			}
?>		</table>
		<br />
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>"><?php echo $Lang['GoBack']; ?> &gt;&gt;</a><br />
		<br />
<?php
			tableend($Lang['Build']);
			sound('buildingstarted');
		}
	}
	else {
		tablebegin($Lang['Build']);
		
?>	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<?php
		foreach ($Builds as $s) {

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
	<tr height="72" valign="middle">
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
		<b><?php echo $Lang['Amount']; ?></b>: <font class="capacity"><?php echo strdiv($Colony[$s['id']]); ?>
	</td>
	<td width="8">&nbsp;</td>
	<td><?php if (isset($s['level'])) { ?><b><?php echo $Lang['Level']; ?></b>: <font class="capacity"><?php echo $s['level']; ?></font><?php } ?></td>
	<td width="8">&nbsp;</td>
	<td width="100" align="left">
<?php
			if (isset($s['cost'])) echo "\t\t<b>[!]</b>&nbsp;<font class=\"work\">" . div($s['credits'] + $s['cost']) . "</font><br />\n";
			elseif (isset($s['credits'])) echo "\t\t<b>[!]</b>&nbsp;<font class=\"result\">" . div($s['credits']) . "</font><br />\n";

			if (@$s['energy']) echo "\t\t<b>[E]</b>&nbsp;" . div($s['energy']) . "<br />\n";
			if (@$s['silicon']) echo "\t\t<b>[S]</b>&nbsp;" . div($s['silicon']) . "<br />\n";
			if (@$s['metal']) echo "\t\t<b>[M]</b>&nbsp;" . div($s['metal']) . "<br />\n";
			if (@$s['uran']) echo "\t\t<b>[U]</b>&nbsp;" . div($s['uran']) . "<br />\n";
			if (@$s['crystals']) echo "\t\t<b>[C]</b>&nbsp;" . div($s['crystals']) . "<br />\n";

			echo '</td>';

			if ($Colony['infrastructure']) {
	
?>	<td width="4">&nbsp;</td>
	<td width="80" align="center">
		[ <font class="plus"><?php echo eta(round((50 / $Colony['infrastructure']) * $s['work'] / log($Colony['workforce']))); ?></b></font> ]<br />
	</td>
	<td width="4">&nbsp;</td>
	<td width="100" align="right">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rid=<?php echo $rid; ?>" method="POST">
		<input type="hidden" name="action" value="build" />
		<input type="hidden" name="name" value="<?php echo $s['id']; ?>" />
		<?php if (! isset($s['level'])) { ?><input size="4" maxlength="8" name="amount" value="1" />&nbsp;<?php } ?><input type="submit" value="<?php echo $Lang['build']; ?>" />
		</form>
	</td>
	<td width="12">&nbsp;</td>
<?php
			}
			echo '</tr>';

		}
		echo "\t".'<tr height="8"><td colspan="9">&nbsp;</td></tr>'."\n\t</table>\n";
		tableend(count($Builds) . $Lang[' structure(s) available']);

		if ($action == 'cancelbuilding') sound('processcancelled');
		else sound('selectstructure');
	}
}

require('include/footer.php');
