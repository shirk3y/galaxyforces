<?php

// ===========================================================================
// Mercenary {mercenary.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-06-24
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require('include/header.php');

$pagename = $Lang['Mercenary'];

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrCantHire']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']} &gt;&gt;</a><br />\n\t\t<br />\n";
	tableend($pagename);
	sound('error');
}

else {
	tablebegin($Lang['Mercenary'], 500);

	if ($Colony && checkplace('mercenary')) {
		subbegin('images/table-b2.jpg');

		tableimg('images/bw.gif', 168, 168, "gallery/places/mercenary.jpg", 160, 160, '', 'right');

		echo "\t<center><font class=\"h3\">${Lang['MercenaryWelcome']}</font><br /><br />\n";

?>		<table width="300" align="left" cellspacing="0" cellpadding="0">
<?php
		$a = floor($Player['credits'] / ($place['parameters'] * $colonistshirecost));
		$b = 20 + 20 * $Colony['flats'] - $Colony['colonists'] - $Colony['scientists'];
		$max = ($a < $b) ? $a : $b;

?>		<tr height="8"><td>&nbsp;</td></tr>
		<tr valign="middle">
		<td>&nbsp;</td>
		<td><b><?php echo $Lang['Colonists']; ?></b>:<br /><?php echo $Lang['Cost']; ?></b>: <font class="result"><?php echo div($place['parameters'] * $colonistshirecost); ?></font> <b>[!]</b></td>
		<td>&nbsp;</td>
		<td><?php echo $Lang['Colony']; ?>: <font class="plus"><?php echo $Colony['colonists']; ?></font><br /><b>Max</b>: <font class="capacity"><?php echo $max; ?></font></td>
		<td>&nbsp;</td>
		<td align="center">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
			<input type="hidden" name="action" value="hire" />
			<input type="hidden" name="name" value="colonists" />
			<input type="text" size="5" name="amount" value="1" />
			<input type="submit" value="<?php echo $Lang['Hire']; ?>" />
			</form>
		</td>
		</tr>
<?php
		$a = floor($Player['credits'] / ($place['parameters'] * $scientistshirecost));
		$max = ($a < $b) ? $a : $b;

?>		<tr height="8"><td>&nbsp;</td></tr>
		<tr valign="middle">
		<td>&nbsp;</td>
		<td><b><?php echo $Lang['Scientists']; ?></b>:<br /><?php echo $Lang['Cost']; ?></b>: <font class="result"><?php echo div($place['parameters'] * $scientistshirecost); ?></font> <b>[!]</b></td>
		<td>&nbsp;</td>
		<td><?php echo $Lang['Colony']; ?>: <font class="plus"><?php echo $Colony['scientists']; ?></font><br /><b>Max</b>: <font class="capacity"><?php echo $max; ?></font></td>
		<td>&nbsp;</td>
		<td width="130" align="center">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
			<input type="hidden" name="action" value="hire" />
			<input type="hidden" name="name" value="scientists" />
			<input type="text" size="5" name="amount" value="1" />
			<input type="submit" value="<?php echo $Lang['Hire']; ?>" />
			</form>
		</td>
		</tr>
		<tr height="8"><td>&nbsp;</td></tr>
		</table>
<?php
		subend();

		tablebreak();
		module('galaxy', 'tip');
	}
	else echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
}

require('include/footer.php');
