<?php

$index = 'clan';
$auth = true;

require('include/header.php');

$name = getvar('name');
if (! ($back = getvar('back'))) $back = 'control.php';

$pagename = $Lang['ClanDonation'];

// ===========================================================================
// ERROR PAGE
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
	sound('error');	
}

// ===========================================================================
// RESULTS PAGE
// ===========================================================================

elseif ($result) {
	tablebegin("$pagename", 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend($Lang['Clan']);
}

// ===========================================================================
// DEFAULT PAGE
// ===========================================================================

else {
	tablebegin($pagename, 500);

	subbegin('images/table-b2.jpg');
	echo "\t\t<center><font class=\"h3\">${Lang['ClanDonation']}</font><br /><br />";

	$db->query("SELECT `name` from `${prefix}groups` ORDER BY `name`;");
	if ($db->numrows()) {

?>	<br />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
	<table align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><b><?php echo $Lang['ClanName']; ?></b>:</td><td>&nbsp;</td>
	<td align="right"><select name="name"><option value=""></option><?php

		while ($t = $db->fetchrow()) {
			echo "<option value=\"${t['name']}\"";
			if (($name == $t['name']) || (! $name && $Group && ($t['name'] == $Group['name']))) echo ' selected';
			echo ">${t['name']}</option>";
		}

?></select></td>
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
	<tr><td colspan="3"><input type="hidden" name="action" value="clandonation" /><center><input type="submit" value="<?php echo $Lang['Donation']; ?>" /></center></td></tr>
        </table>
<?php
	}

	subbreak();
	tableimg("images/bw.gif", 168, 168, 'gallery/places/bank.jpg', 160, 160, '', 'right');
	subend();

	tableend("<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

require('include/footer.php');
