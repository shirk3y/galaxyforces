<?php

// ===========================================================================
// User donation {userdonation.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'clan';
$auth = true;

require('include/header.php');

$name = getvar('name');
if (!($back = getvar('back'))) $back = 'clan.php';

$pagename = $Lang['UserDonation'];

// ===========================================================================
// ERROR
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
}

// ===========================================================================
// RESULTS
// ===========================================================================

elseif ($result) {
	tablebegin("$pagename", 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend($Lang['Clan']);
}

// ===========================================================================
// ACCESS DENIED
// ===========================================================================

elseif (! $Player['privileged']) {
	tablebegin($pagename);
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";
	tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Clan']);
}

// ===========================================================================
// DEFAULT
// ===========================================================================

else {
	tablebegin($pagename, 500);

	subbegin('images/table-b2.jpg');
	echo "\t\t<center><font class=\"h3\">${Lang['UserDonation']}</font><br /><br />";

	$db->query("SELECT name from {$prefix}groups ORDER BY name;");
	if ($db->numrows()) {

?>	<br />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
	<table align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><b><?php echo $Lang['UserName']; ?></b>:</td><td>&nbsp;</td>
	<td align="right"><input name="name" value="<?php echo $name; ?>" /></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><b><?php echo $Lang['Credits']; ?></b>:</td><td>&nbsp;</td>
	<td align="right"><input type="text" size="8" maxlength="240" name="credits" /></td>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Crystals']; ?></b>:</td><td>&nbsp;</td>
	<td align="right"><input type="text" size="6" maxlength="240" name="crystals" /></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><input type="hidden" name="action" value="userdonation" /><center><input type="submit" value="<?php echo $Lang['Donation']; ?>" /></center></td></tr>
	</table>
<?php
	}

	subbreak();
	tableimg("images/bw.gif", 168, 168, 'gallery/places/bank.jpg', 160, 160, '', 'right');
	subend();

	tableend("<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

require('include/footer.php');
