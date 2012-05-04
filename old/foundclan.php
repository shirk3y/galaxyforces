<?php

$index = 'clan';
$auth = true;

require('include/header.php');

$view = getvar('view');
$back = 'clanhall.php';

$pagename = $Lang['Clan'];

// ===========================================================================
// ERROR PAGE
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
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

elseif (checkplace('clanhall')) {
	tablebegin($pagename, 500);

	subbegin('images/table-b2.jpg');
	tableimg("images/bw.gif", 168, 168, 'gallery/places/clanhall.jpg', 160, 160, '', 'right');
	echo "\t\t<center><font class=\"h3\">${Lang['FoundClan']}</font><br /><br /><font class=\"result\">${Lang['FoundHallInformation']}</font><br />";
	subend();

	if (! $Group && ($Player['level'] > 1)) {
		tablebreak();
		subbegin();

?>	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form">
	<table width="100%" background="images/table.jpg" id="form" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><b><?php echo $Lang['ClanName']; ?></b> <font class="work"><i>(<?php echo $Lang['max32chars']; ?>)</i></font>:</td>
	<td align="right"><input type="text" size="32" maxlength="32" name="name" /></td>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Description']; ?></b>:</td>
	<td align="right"><input type="text" size="40" maxlength="240" name="description" /></td>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Tax']; ?></b>:</td>
	<td align="right"><select name="tax"><?php for ($i = 5; $i < 100; $i += 5) echo "<option value=\"$i\">$i%</option>"; ?></select></td>
	</tr>
	<tr>
	<td><b>WWW</b> <font class="work"><i>(<?php echo $Lang['ieforumlink']; ?>)</i></font>:</td>
	<td align="right"><input type="text" size="24" maxlength="64" name="www" /></td>
	</td>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2"><input type="hidden" name="action" value="foundclan" /><center><input type="submit" value="<?php echo $Lang['FoundClan']; ?>" /></center></td>
	</tr>
        </table>
	</form>
<?php
		subend();
	}

	tableend("<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// PAGE NOT AVAILABLE
// ===========================================================================

else {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	echo "\t\t<h3>${Lang['NotAvailable']}</h3><font class=\"capacity\">${Lang['BugHint']}</font><br /><br />";
	tableend($back ? "<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>" : "${Lang['Error']}: ${Lang['NotAvailable']}");
}

require('include/footer.php');
