<?php

$index = 'control';
$auth = true;
$sound = 'clanhall';

require('include/header.php');

$view = getvar('view');

// ===========================================================================
// ERROR PAGE
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	sound('error');
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("$pagename");
}

// ===========================================================================
// RESULTS PAGE
// ===========================================================================

elseif ($result) {
	tablebegin("$pagename", 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("${Lang['Clan']}$title");
}

// ===========================================================================
// DEFAULT PAGE
// ===========================================================================

elseif (!$errors) {
	tablebegin($Lang['ClanHall']);

	$Groups = '';
	$db->query("SELECT * FROM `${prefix}groups` ORDER BY `score` DESC, `level` DESC, `name` ASC;");
	$max = $db->numrows();
?>
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		Klany
		<tr id="header">
		<td align="center">Nazwa</td>
		<td>&nbsp;</td>
		<td align="center">Utworzony</td>
		<td>&nbsp;</td>
		<td align="center">Wlasciciel</td>
		<td>&nbsp;</td>
		<td align="center">Rada (1)</td>
		<td>&nbsp;</td>
		<td align="center">Rada (2)</td>
		<td>&nbsp;</td>
		<td align="center">[!]</td>
		<td>&nbsp;</td>
		<td align="center">[C]</td>
		<td>&nbsp;</td>
		<td align="center">Punkty</td>
		<td>&nbsp;</td>
		<td align="center">Poziom</td>
		<td>&nbsp;</td>
		<td align="center">Podatek</td>
		<td>&nbsp;</td>
		</tr>

<?php
	while ($t = $db->fetchrow()) {
		echo "<tr><td>".$t['name']."</td><td>&nbsp;</td><td>".$t['created']."</td><td>&nbsp;</td><td>".$t['owner']."</td><td>&nbsp;</td><td>".$t['co1']."</td><td>&nbsp;</td><td>".$t['co2']."</td><td>&nbsp;</td><td>".strdiv($t['credits'])."</td><td>&nbsp;</td><td>".strdiv($t['crystals'])."</td><td>&nbsp;</td><td>".strdiv($t['score'])."</td><td>&nbsp;</td><td>".$t['level']."</td><td>&nbsp;</td><td>".$t['tax']."</td></tr>";
	}
	echo "</table>";
	tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
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
