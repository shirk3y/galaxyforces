<?php

// ===========================================================================
// Teleport {teleport.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Modified:	2005-05-01
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require('include/header.php');

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorCantTeleport']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
	sound('error');
	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// TELEPORT
// ===========================================================================

else {
	tablebegin($Lang['Teleport'], 500);

	if ($action == 'teleport') {
		sound('teleported');
?>	<br />
	<h3><?php echo $Lang['Teleport']; ?></h3><br />
	<br />
	<?php echo $Lang['Teleport3']; ?><br />
	<br />
<?php
	}
	elseif (checkplace('teleport')) {
		//$name = $place['parameters'];
		
		$names = explode(',', $place['parameters']);
		$prices = explode(',', $place['extra']);

		subbegin();
		
		?><table width="100%" cellspacing="0" cellpadding="0"><tr valign="top"><td>

		<center><font class="h3"><?php echo $Lang['TeleportWelcome']; ?></font><br />
<?php
		sound('teleport');

		?><p /><table width="100%" align="center" cellspacing="0" cellpadding="0"><?php

		$i = 0;
		
		foreach ($names as $name) {
			$db->query("SELECT `galaxy` FROM `{$prefix}planets` WHERE `name` = '$name' LIMIT 1");
			if ($t = $db->fetchrow()) $galaxy = $t['galaxy']; else $galaxy = '';
			?><tr><td><a class="result" href="galaxy.php?galaxy=<?php echo $galaxy; ?>&planet=<?php echo $place['parameters']; ?>"><?php echo strcap($name); ?></a><td>&nbsp;</td><td align="center"><?php echo div($prices[$i]); ?> <b>[!]</b></td><td>&nbsp;</td><td align="right"><a href="teleport.php?action=teleport&name=<?php echo $name; ?>"><?php echo $Lang['UseTeleport']; ?>&nbsp;&gt;&gt;</a></td></tr><?php
			$i++;
		}
		
		?></table>

		<td width="8">&nbsp;</td>
		<td width="168"><?php tableimg("images/bw.gif", 168, 168, 'gallery/places/teleport.jpg', 160, 160, '', 'right', 'javascript:avatar()'); ?></td>
		</tr>
		</table>
<?php
		subend();
	}
	else echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
