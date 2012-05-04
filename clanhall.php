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

elseif (checkplace('clanhall')) {
	tablebegin($Lang['ClanHall']);

	subbegin('images/table-b2.jpg');
	tableimg("images/bw.gif", 168, 168, 'gallery/places/clanhall.jpg', 160, 160, '', 'right');
	echo "<center><font class=\"h3\">${Lang['ClanHallWelcome']}</font><br /><br />";

	if ($result && ($action == 'join')) {

?>		<font class="result"><?php echo $Lang['ClanHallRS']; ?></font><br />
		<br />
		<b><?php echo $Lang['Credits']; ?>:</b> <font class="minus"><?php echo div($result); ?></font> [!]<br />
<?php
	}
	else {

?>		<font class="result"><?php echo $view ? $Lang['ClanHallInformation'] : $Lang['ClanHallDescription']; ?></font><br />
<?php
	}

	subend();

	$Groups = '';
	$db->query("SELECT * FROM `${prefix}groups` ORDER BY `score` DESC, `level` DESC, `name` ASC;");
	$max = $db->numrows();
	while ($t = $db->fetchrow()) $Groups[] = $t;

	if (($action != 'join') && ($view > 0) && ($view <= $max)) {

		tablebreak();

		subbegin('images/table-b2.jpg');

		$g = $Groups[$view - 1];

		$db->query("SELECT `login`,`level` FROM `${prefix}users` WHERE `clan`='${g['name']}' ORDER BY `login` ASC;");
		$Members = '';
		$av = 0;
		while ($t = $db->fetchrow()) {
			$Members[] = $t;
			$av += $t['level'];
		}
		$av /= count($Members);

?>		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr valign="top">
		<td>
			<b><?php echo $Lang['ClanName']; ?></b>: <font class="capacity"><?php echo $g['name'] . $name; ?></font><?php echo $g['www'] ? " &nbsp; <a href=\"http://${g['www']}\">WWW &gt;&gt</a>" : ''; ?><br />
			<br />
			<b><?php echo $Lang['Owner']; ?></b>: <a href="whois.php?name=<?php echo $g['owner']; ?>"><?php echo $g['owner']; ?></a>
<?php
		if ($g['co1'] || $g['co2']) {
			echo ", <b>${Lang['Council']}</b>: ";
			if ($g['co1']) echo "<a href=\"whois.php?name=${g['co1']}\">${g['co1']}</a>";
			if ($g['co1'] && $g['co2']) echo ', ';
			if ($g['co2']) echo "<a href=\"whois.php?name=${g['co2']}\">${g['co2']}</a>";
			echo "<br />\n";
		}

?>			<b><?php echo $Lang['Members']; ?></b>: <font class="result"><?php echo count($Members); ?></font>, <b><?php echo $Lang['AverageLevel']; ?></b>: <font class="minus"><?php echo number_format($av, 1, $Lang['DecPoint'], ' '); ?></font><br />
<?php
		if ($g['www']) echo "\t\t<br />\n";
		if ($g['description']) echo "\t\t<b>${Lang['Description']}</b>: <font class=\"result\">${g['description']}</font><br />\n";

?>		</td>
		<td width="8">&nbsp;</td>
		<td>
			<b><?php echo $Lang['Level']; ?></b>: <font class="plus"><?php echo $g['level']; ?></font><br />
			<b><?php echo $Lang['Score']; ?></b>: <font class="result"><?php echo div($g['score']); ?></font><br />
			<b><?php echo $Lang['Tax']; ?></b>: <font class="minus"><?php echo $g['tax']; ?>%</font><br />
			<br />
			<b><?php echo $Lang['Attack']; ?></b>: <font class="plus"><?php echo div($g['attack']); ?></font><br />
			<b><?php echo $Lang['Defense']; ?></b>: <font class="capacity"><?php echo div($g['defense']); ?></font><br />
		</td>
		<td width="8" rowspan="2">&nbsp;</td>
		<td width="168" rowspan="2">
			<table background="images/bw.gif" width="168" height="168" cellspacing="0" cellpadding="0" hspace="4" border="0" align="right">
			<tr height="168" valign="center">
			<td><center><img src="<?php echo $g['avatar'] ? $g['avatar'] : 'gallery/avatars/noavatar.gif'; ?>" alt="" width="160" height="160" hspace="0" vspace="0" border="0" /></center></td>
			</tr>
			</table>
		</td>
		</tr>
		<tr>
		<td colspan="3">
			<center>
			<a href="clanhall.php?action=join&name=<?php echo $g['name']; ?>"><?php echo $Lang['JoinReq']; ?> &gt;&gt;</a><br />
			</center>
		</td>
		</tr>
		</table>
<?php

		subend();

	}
	else {
		tablebreak();

		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

		$i = 0;
		foreach ($Groups as $g) {

			if (++$i % 2) {

?>	<tr height="8"><td>&nbsp;</td></tr>
	<tr height="72" valign="top">
	<td width="12">&nbsp;</td>
	<td width="72">
<?php
				tableimg('images/pw.gif', 72, 72, ($g['avatar'] ? $g['avatar'] : 'gallery/avatars/icons/noavatar.gif'), 64, 64, 'clanhall.php?view=' . $i, '', $g['name']);

?>	</td>
	<td width="12">&nbsp;</td>
	<td align="left">
		<a href="clanhall.php?view=<?php echo $i; ?>"><b><?php echo $g['name']; ?></b></a><br />
		<b><?php echo $Lang['Level']; ?></b>: <font class="plus"><?php echo $g['level']; ?></font>, <b><?php echo $Lang['Score']; ?></b>: <font class="result"><?php echo div($g['score']); ?></font><br />
<?php
				if ($g['description']) echo "\t\t<br />\n\t\t<font class=\"result\">${g['description']}</font><br />\n";

?>
	</td>
<?php
			}
			else {

?>	<td width="12">&nbsp;</td>
	<td align="right">
		<a href="clanhall.php?view=<?php echo $i; ?>"><b><?php echo $g['name']; ?></b></a><br />
		<b><?php echo $Lang['Level']; ?></b>: <font class="plus"><?php echo $g['level']; ?></font>, <b><?php echo $Lang['Score']; ?></b>: <font class="result"><?php echo div($g['score']); ?></font><br />
<?php
				if ($g['description']) echo "\t\t<br />\n\t\t<font class=\"result\">${g['description']}</font><br />\n";

?>
	</td>
	<td width="12">&nbsp;</td>
	<td width="72">
<?php
				tableimg('images/pw.gif', 72, 72, ($g['avatar'] ? $g['avatar'] : 'gallery/avatars/icons/noavatar.gif'), 64, 64, 'clanhall.php?view=' . $i, '', $g['name']);

?>	</td>
	<td width="12">&nbsp;</td>
	</tr>
<?php
			}
		}

		if ($i % 2) {

?>	<td colspan="5">&nbsp;</td>
	</tr>
<?php
		}
?>	<tr height="8"><td>&nbsp;</td></tr>
	</table>


<?php
	}

	if (! $Group) {
		tablebreak();
		echo "<br /><a href=\"clandonation.php\">${Lang['ClanDonation']}&nbsp;&gt;&gt;</a><br />";
		echo "<br /><a href=\"foundclan.php\">${Lang['FoundClan']}&nbsp;&gt;&gt;</a><br />";
		echo "<br />";
	}

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
