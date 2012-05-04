<?php

// ===========================================================================
// Clan {clan.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.3
//	Modified:	2005-11-12
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'clan';
$auth = true;
$js[] = 'functions';

require('include/header.php');

locale('clan');

$view = getvar('view');
$back = getvar('back');
$checkall = getvar('checkall');
$category = getvar('category');
$page = (int)getvar('page');

$pagecount = 25;

$gowner = getvar('gowner');
$gco1 = getvar('gco1');
$gco2 = getvar('gco2');
$clanname = getvar('clanname');
$gtax = getvar('gtax');
$gdescription = getvar('gdescription');
$action = getvar('action');

if ($action == 'clanadmin') {
	if (!$Player['privileged']) $errors .= $Lang['ErrorAccessDenied'].'<br />';
	else {
		$owner = trim(strip_tags(postvar('owner')));
		$co1 = trim(strip_tags(postvar('co1')));
		$co2 = trim(strip_tags(postvar('co2')));
		$tax = trim(strip_tags(postvar('tax')));
		$description = trim(strip_tags(postvar('description')));
		$www = trim(strip_tags(postvar('www')));

		if ($tax > 95) $tax = 95; elseif ($tax < 0) $tax = 0;

		if (($owner && !playerexists($owner)) || ($co1 && !playerexists($co1)) || ($co2 && !playerexists($co2))) $errors .= $Lang['ErrorUsersNotExist'].'!<br />';
		elseif (($co1 && ($co1 == $co2 || $co1 == $owner)) || ($co2 && ($co2 == $co1 || $co2 == $owner))) $errors .= $Lang['ErrorBadUsers'].'!<br />';
		else {
			$sql = "UPDATE {$prefix}groups SET co1='".$db->safe(strtolower($co1))."',co2='".$db->safe(strtolower($co2))."'";
			if ($Player['login'] == $Group['owner'] || checkplace('clanhall')) $sql .= ",tax='".$db->safe($tax)."'";
			if ($Player['login'] == $Group['owner']) {
				if ($owner) $sql .= ",owner='".$db->safe(strtolower($owner))."'";
				$sql .= ",description='".$db->safe($description)."'";
				$sql .= ",www='".$db->safe($www)."'";
			}	
			if ($db->query($sql." WHERE name='".$Group['name']."';")) {
				$result .= $Lang['UpdateComplete'].'<br />';
				$db->query("INSERT INTO {$prefix}clanmessages (`type`,`time`,`clan`,`from`,`to`) VALUES ('statuschange','$stardate','${Group['name']}','$login','');");
			}
			else {
				echo($sql." WHERE name='".$Group['name']."';");
				echo '<br />'; echo $db->safe("' `;");
				$errors .= $Lang['ErrorQueryFailed'].'!<br />';
				}
		}
	}
}
elseif ($action == 'changeclanname') {
	if ($Player['login'] != $Group['owner']) $errors .= $Lang['ErrorAccessDenied'].'<br />';
	elseif (!checkplace('clanhall')) $errors .= $Lang['ErrorHallRequired'].'!<br />';
	elseif ($name = trim(strip_tags(postvar('name')))) {
		$db->query("SELECT * FROM {$prefix}groups WHERE name='".$db->safe($name)."';");
		if ($db->numrows()) $errors .= $Lang['ErrorClanExists'].'!<br />';
		else {
			$db->query("UPDATE {$prefix}groups SET name='".$db->safe($name)."' WHERE name='".$Group['name']."';");
			$db->query("UPDATE {$prefix}users SET clan='".$db->safe($name)."' WHERE clan='".$Group['name']."';");
			$db->query("UPDATE {$prefix}clanmessages SET clan='".$db->safe($name)."' WHERE clan='".$Group['name']."';");

			$Player['clan'] = $Group['name'] = $name;
			$db->query("INSERT INTO {$prefix}clanmessages (`type`,`time`,`clan`,`from`,`to`) VALUES ('namechange','$stardate','{$Group['name']}','$login','');");

			$result .= $Lang['UpdateComplete'].'<br />';
		}
	}
}

$back = $back ? $back : ($view ? 'clan.php' : 'control.php');

switch ($view) {
	case 'list': $title = ': '.$Lang['Members']; break;
	default: $title = '';
}
$pagename = $Lang['Clan'].$title;

switch ($category) {
	case 'type': $order = "`type`,`time` DESC,`timestamp` DESC"; break;
	case 'typedesc': $order = "`type` DESC,`time` DESC,`timestamp` DESC"; break;
	case 'timedesc': $order = "`time`,`timestamp`"; break;
	case 'time':
	default: $order = "`time` DESC, `timestamp` DESC"; break;
}

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
	sound('error');
	tableend('<a href="clan.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>');
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br />";
	tableend('<a href="clan.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>');
}

// ===========================================================================
// CHANGE NAME
// ===========================================================================

elseif ($view == 'changename') {
	if ($Player['login'] != $Group['owner']) {
		tablebegin($pagename);
		echo '<br />'.$Lang['NotAvailable'].'<br /><br />';
		tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Clan']);
	}
	else {
		tablebegin($pagename, 500);

		subbegin('images/table-b2.jpg');
		echo '<center><font class="h3">'.$Lang['ChangeClanName'].'</font><br /><br />';
		echo '<form action="clan.php" method="POST"><input type="hidden" name="action" value="changeclanname" />';
		echo $Lang['Name'].': &nbsp; <input name="name" type="text" value="'.$Group['name'].'" size="32" maxlength="32" /><br /><br />';
		echo '<input type="submit" value="'.$Lang['ChangeClanName'].'" /></form>';

		subbreak();
		tableimg("images/bw.gif", 168, 168, $Group['avatar'] ? $Group['avatar'] : 'gallery/avatars/noavatar.gif', 160, 160, '', 'right');
		subend();
		tableend("<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
	}
}

// ===========================================================================
// ADMINISTRATION
// ===========================================================================

elseif ($view == 'admin') {
	if (!$Player['privileged']) {
		tablebegin($pagename);
		echo '<br />'.$Lang['NotAvailable'].'<br /><br />';
		tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Clan']);
	}
	else {
		tablebegin($pagename, 500);

		subbegin('images/table-b2.jpg');
		echo "\t\t<center><font class=\"h3\">${Lang['Administration']}</font><br /><br />";

?>	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><b><?php echo $Lang['Owner']; ?></b>:</td><td>&nbsp;</td>
<?php
		if ($Player['login'] == $Group['owner']) {
?>
	<td align="right"><input type="text" size="10" maxlength="150" name="owner" value="<?php echo $Group['owner']; ?>"/></td>
<?php
		}
		else {
?>
	<td align="right"><?php echo $Group['owner']; ?><input type="hidden" size="10" maxlength="150" name="owner" value="<?php echo $Group['owner']; ?>"/></td>
<?php
		}
?>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Council']; ?></b>:</td><td>&nbsp;</td>
	<td align="right"><input type="text" size="10" maxlength="150" name="co1" value="<?php echo $Group['co1']; ?>"/></td>
	</tr>
	<tr>
	<td><b></td><td>&nbsp;</td>
	<td align="right"><input type="text" size="10" maxlength="150" name="co2" value="<?php echo $Group['co2']; ?>"/></td>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Tax']; ?></b>:</td><td>&nbsp;</td>
<?php
		if ($Player['login'] == $Group['owner'] || checkplace('clanhall')) {
?>
	<td align="right"><select name="tax"><?php for ($i = 5; $i < 100; $i += 5) { echo "<option value=\"$i\""; if ($Group['tax'] == $i) echo "SELECTED"; echo ">$i%</option>"; } ?></select></td>
	</tr>
<?php
		}
		else {
?>
	<td align="right"><?php echo $Group['tax']; ?>%</td>
	</tr>
<?php
		}

		echo '<tr><td><b>'.$Lang['Description'].'</b>:</td><td>&nbsp;</td><td>';
		if ($Player['login'] == $Group['owner']) echo '<input type="text" size="25" maxlength="160" name="description" value="'.$Group['description'].'" />';
		else echo $Group['description'];
		echo '</td></tr>';

		echo '<tr><td><b>'.$Lang['Website'].'</b>:</td><td>&nbsp;</td><td>';
		if ($Player['login'] == $Group['owner']) echo '<input type="text" size="25" maxlength="80" name="www" value="'.$Group['www'].'" />';
		else echo $Group['www'];
		echo '</td></tr>';

?>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><input type="hidden" name="action" value="clanadmin" /><center><input type="submit" value="<?php echo $Lang['Accept']; ?>" /></center></td></tr>
        </table>
<?php
		subbreak();
		tableimg("images/bw.gif", 168, 168, $Group['avatar'] ? $Group['avatar'] : 'gallery/avatars/noavatar.gif', 160, 160, '', 'right');
		subend();

		if ($Player['login'] == $Group['owner']) {
			tablebreak();
			echo '<br /><a href="clan.php?view=changename">'.$Lang['ChangeClanName'].'&nbsp;&gt;&gt;</a><br />';
			echo '<br />';
		}

		tableend("<a href=\"$back\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
	}
}

// ===========================================================================
// LIST
// ===========================================================================

elseif ($view == 'list') {
	tablebegin($pagename);

	echo "<h3>${Lang['Members']}</h3>";

	$db->query("SELECT `login`,`level`,`score`,`credits`,`bank` FROM `${prefix}users` WHERE `clan`='${Group['name']}' ORDER BY `level` DESC, `login` ASC;");
	while ($t = $db->fetchrow()) $tab[] = $t;

?>	<form action="messages.php" name="form" method="POST"><input type="hidden" name="view" value="compose" />
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr id="header">
	<td width="12">&nbsp;</td>
	<td align="left">&nbsp;</td>
	<td align="left"><?php echo $Lang['Login']; ?>:</td>
	<td width="8">&nbsp;</td>
	<td align="center"><?php echo $Lang['Level']; ?>:</td>
	<td width="8">&nbsp;</td>
	<td align="center"><?php echo $Lang['Colony']; ?>:</td>
	<td width="12">&nbsp;</td>
	<td align="center"><?php echo $Lang['Planet']; ?>:</td>
	<td width="12">&nbsp;</td>
	<td align="center"><?php echo str_replace(' ', '&nbsp;', $Lang['Population']); ?>:</td>
	<td width="12">&nbsp;</td>
	<td align="center"><?php echo str_replace(' ', '&nbsp;', $Lang['Credits']); ?>:</td>
	<td width="12">&nbsp;</td>
	<td align="center"><?php echo str_replace(' ', '&nbsp;', $Lang['Score']); ?>:</td>
	<td width="8">&nbsp;</td>
	</tr>
<?php
	$i = 0;
	foreach ($tab as $t) {
		$i++;
		$t['cash'] = $t['credits'] + $t['bank'];
		$db->query("SELECT `name`,`planet`,`colonists`,`scientists`,`soldiers` FROM `${prefix}colonies` WHERE `owner` = '${t['login']}' LIMIT 1;");
		if ($u = $db->fetchrow()) {
			$colony = $u['name'];
			$population = $u['colonists'] + $u['scientists'] + $u['soldiers'];
			$planet = $u['planet'];
		}
		else {
			$colony = '';
			$population = '';
			$planet = '';
		}
		if ($t['login'] == $login) $id=' id="here"';
		elseif (! ($i % 2)) $id = ' id="div"';
		else $id = '';

?>	<tr height="24"<?php echo $id; ?>>
	<td>&nbsp;</td>
	<td align="left"><input type="checkbox"<?php echo $checkall ? ' checked' : ''; ?> name="checkbox[]" value="<?php echo $t['login']; ?>"></input></td>
	<td align="left"><a href="whois.php?name=<?php echo $t['login']; ?>"><?php echo $t['login']; ?></a></td>
	<td>&nbsp;</td>
	<td align="center"><font class="result"><?php echo $t['level']; ?></font></td>
	<td>&nbsp;</td>
	<td align="center"><?php echo $colony; ?></td>
	<td>&nbsp;</td>
	<td align="center"><a class="work" href="galaxy.php?object=<?php echo $planet; ?>"><?php echo strcap($planet); ?></a></td>
	<td>&nbsp;</td>
	<td align="center"><font class="capacity"><?php echo div($population); ?></font></td>
	<td>&nbsp;</td>
	<td align="center"><font class="result"><?php echo div($t['cash']); ?></font></td>
	<td>&nbsp;</td>
	<td align="center"><font class="result"><?php echo div($t['score']); ?></font></td>
	<td>&nbsp;</td>
	</tr>
<?php
	}

?>	</table>
	<br /><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&checkall=1" onclick="setcheckboxes('form', 'checkbox[]', true); return false;"><?php echo $Lang['SelectAll']; ?></a> &nbsp;/&nbsp; <a href="<?php echo $_SERVER['REQUEST_URI']; ?>" onclick="setcheckboxes('form', 'checkbox[]', false); return false;"><?php echo $Lang['UnselectAll']; ?></a><br /><br />
	<input type="hidden" name="action" value="broadcast" /><input type="submit" value="<?php echo $Lang['SendMessage']; ?>" />
	</form>
	<br />
	<br />
<?php
	tableend("<a href=\"clan.php?rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RECULTIVATION
// ===========================================================================

elseif ($view == 'recultivation') {
	tablebegin($pagename);

	echo "<h3>${Lang['PlanetRecultivation']}</h3>";
	echo "<font class=\"result\">${Lang['RecultDesc']}</font><br /><br />";
	tablebreak();
	$p = '';
	$db->query("SELECT * FROM `${prefix}space` WHERE `explored`>50 ORDER BY `explored` DESC,`galaxy`,`name`");
	echo "<br />";
	if ($db->numrows()) {
		echo "<table width=\"100%\">";
		while ($u = $db->fetchrow()) {
			if ($u['life'] < 15) $lifesigns = '<font class="minus">' . $Lang['LifeSigns[]'][0];
			elseif ($u['life'] < 40) $lifesigns = '<font class="work">' . $Lang['LifeSigns[]'][1];
			elseif ($u['life'] < 75) $lifesigns = '<font class="result">' . $Lang['LifeSigns[]'][2];
			elseif ($u['life'] < 90) $lifesigns = '<font class="plus">' . $Lang['LifeSigns[]'][3];
			else $lifesigns = '<font class="plus">' . $Lang['LifeSigns[]'][4];

			if ($u['terrain'] < 15) $terrain = '<font class="minus">' . $Lang['Terrain[]'][0];
			elseif ($u['terrain'] < 40) $terrain = '<font class="work">' . $Lang['Terrain[]'][1];
			elseif ($u['terrain'] < 75) $terrain = '<font class="result">' . $Lang['Terrain[]'][2];
			elseif ($u['terrain'] < 90) $terrain = '<font class="plus">' . $Lang['Terrain[]'][3];
			else $terrain = '<font class="plus">' . $Lang['Terrain[]'][4];

?>	<tr height="72" valign="middle">
	<td width="12">&nbsp;</td>
	<td width="72">
		<table background="images/pw.gif" width="72" height="72" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr height="72" valign="center">
		<td><center><a href="galaxy.php?galaxy=<?php echo $u['galaxy']; ?>&planet=<?php echo $u['name']; ?>"><img src="gallery/space/icons/<?php echo $u['name'] . '.jpg'; ?>" alt="<?php echo $u['name']; ?>" width="64" height="64" hspace="0" vspace="0" border="0"></a></center></td>
		</tr>
		</table>
	</td>
	<td width="12">&nbsp;</td>
	<td align="left">
		<b><?php echo $Lang['PlanetName']; ?></b>: <font class="plus"><?php echo $u['name']; ?></font><br />
		<b><?php echo $Lang['Technology']; ?></b>: <font class="capacity"><?php echo $Lang['Technology[]'][$u['technology']]; ?></font><br />
		<b><?php echo $Lang['TerrainHardness']; ?></b>: <?php echo $terrain; ?><br />
	</td>
	<td width="12">&nbsp;</td>
	<td align="left">
		<b><?php echo $Lang['Explored']; ?></b>: <font class="minus"><?php echo round(100 * $u['explored']) / 100; ?> %</font><br />
		<b><?php echo $Lang['SizeC']; ?></b>: <font class="result"><?php echo $Lang['SizeT'][$u['class']]; ?></font><br />
		<b><?php echo $Lang['LifeSigns']; ?></b>: <?php echo $lifesigns; ?><br />
	</td>
	<td width="12">&nbsp;</td>
	<td align="right">
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
		<center>
		<b><?php echo $Lang['Credits']; ?></b>:&nbsp;<input type="text" size="10" value="100000" name="credits" />
		<input type="hidden" name="action" value="recultivation" />
		<input type="hidden" name="name" value="<?php echo $u['name']; ?>" />
		<br /><br />
		<input type="submit" value="<?php echo $Lang['PlanetRecultivation']; ?>" />
		</form>
		</center>
	</td>
	<td width="12">&nbsp;</td>
	</tr>
	<tr height="8"><td>&nbsp;</td></tr>
<?php
		}
		echo "\t</table>\n";
	}
	else echo "<font class=\"error\">${Lang['Ta_NR']}</font><br /><br />";
	tableend("<a href=\"clan.php?rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// CLAN
// ===========================================================================

elseif ($Player['clan']) {
	$db->query("SELECT `id`,`login`,`online`,`level` FROM `{$prefix}users` WHERE `clan`='${Player['clan']}';");
	$av = 0;
	while ($t = $db->fetchrow()) {
		$Members[] = $t;
		$av += $t['level'];
	}
	$av = round(10 * $av / count($Members)) / 10;

	tablebegin($pagename);

	subbegin('images/table-b2.jpg');

	echo "\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\" name=\"form\">\n\t<tr valign=\"top\">\n\t<td>\n";
	echo "\t<b>${Lang['ClanName']}</b>: <font class=\"capacity\">${Group['name']}</font><br /><br />\n";
	echo "\t<b>${Lang['Owner']}</b>: <a href=\"whois.php?rid=$rid&name=${Group['owner']}\">${Group['owner']}</a>";

	if ($Group['co1'] || $Group['co2']) {
		echo ", <b>${Lang['Council']}</b>: ";
		if ($Group['co1']) echo "<a href=\"whois.php?name=${Group['co1']}\">${Group['co1']}</a>";
		if ($Group['co1'] && $Group['co2']) echo ', ';
		if ($Group['co2']) echo "<a href=\"whois.php?name=${Group['co2']}\">${Group['co2']}</a>";
		echo "<br />\n";
	}

?>			<br />
			<b><?php echo $Lang['Members']; ?></b>: <font class="result"><?php echo count($Members); ?></font><?php if ($view != 'list') { ?> &nbsp; <a href="clan.php?view=list"><?php echo $Lang['List']; ?> &gt;&gt;</a><?php } ?><br />
			<b><?php echo $Lang['AverageLevel']; ?></b>: <font class="minus"><?php echo number_format($av, 1, $Lang['DecPoint'], ' '); ?></font><br />
<?php
		if ($Group['www']) echo "\t\t<b>WWW</b>: <a href=\"http://${Group['www']}\">${Group['www']}</a><br />\n";
		if ($Group['www']) echo "\t\t<br />\n";
		if ($Group['description']) echo "\t\t<b>${Lang['Description']}</b>: <font class=\"result\">${Group['description']}</font><br />\n";

?>		</td>
		<td width="8">&nbsp;</td>
		<td>
			<b><?php echo $Lang['Level']; ?></b>: <font class="plus"><?php echo $Group['level']; ?></font>, <b><?php echo $Lang['Score']; ?></b>: <font class="result"><?php echo div($Group['score']); ?></font><br />
			<br />
			<b><?php echo $Lang['Credits']; ?></b>: <font class="result"><?php echo div($Group['credits']); ?></font><br />
			<b><?php echo $Lang['Crystals']; ?></b>: <font class="capacity"><?php echo div($Group['crystals']); ?></font><br />
			<b><?php echo $Lang['Tax']; ?></b>: <font class="minus"><?php echo $Group['tax']; ?>%</font><br />
			<br />
			<b><?php echo $Lang['Attack']; ?></b>: <font class="plus"><?php echo div($Group['attack']); ?></font>, <b><?php echo $Lang['Defense']; ?></b>: <font class="capacity"><?php echo div($Group['defense']); ?></font><br />
		</td>
		<td width="8">&nbsp;</td>
		<td width="168"><?php tableimg("images/bw.gif", 168, 168, ($Group['avatar'] ? $Group['avatar'] : 'gallery/avatars/noavatar.gif'), 160, 160, 'javascript:avatar()', 'right'); ?></td>
		</tr>
		</table>
<?php

	subend();

	tablebreak();

	echo "\t<br /><a href=\"clandonation.php?name=${Group['name']}\">${Lang['Donation']} &gt;&gt;</a><br />\n";

	if ($Player['privileged']) {
		if ($view != 'recultivation') echo "\t<br /><a href=\"clan.php?view=recultivation\">${Lang['PlanetRecultivation']}&nbsp;&gt;&gt;</a><br />\n";
		echo '<br /><a href="userdonation.php">'.$Lang['ClanSupport'].'&nbsp;&gt;&gt</a><br />';
		echo '<br /><a class="capacity" href="clan.php?view=admin">'.$Lang['Administration'].'&nbsp;&gt;&gt;</a><br />';
	}

	echo "\t<br /><a class=\"delete\" href=\"javascript:ask('control.php?action=leave&confirm=$secret')\">";
	if ($Group['owner'] == $login) echo $Lang['DisbandGroup'];
	else echo $Lang['LeaveGroup'];
	echo "&nbsp;&gt;&gt;</a><br />\n\t<br />\n";

	$db->query("SELECT `id` FROM `${prefix}clanmessages` WHERE `clan`='${Group['name']}';");
	$max = $db->numrows();

	if ($page > $m = floor($max / $pagecount)) $page = $m;
	$l = $page * $pagecount;

	$db->query("SELECT * FROM `${prefix}clanmessages` WHERE `clan`='${Group['name']}' ORDER BY $order LIMIT $l,$pagecount;");

	if ($db->numrows()) {
		tablebreak();
		echo "\t<br />\n<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n\t<tr id=\"header\"><td id=\"headerl\" width=\"12\">&nbsp;</td>";
		echo "<td align=\"center\">".'<a'.($category == 'type' ? ' class="minus"' : ($category == 'typedesc' ? ' class="result"': ''))." href=\"clan.php?rid=$rid&page=$page&category=".($category == 'type' ? 'typedesc' : 'type')."\" onmouseover=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Message']}</a>:</td>";
		echo "<td width=\"12\">&nbsp;</td>";
		echo "<td width=\"120\" align=\"center\">".'<a'.($category == 'time' ? ' class="minus"' : ($category == 'timedesc' ? ' class="result"': ''))." href=\"clan.php?rid=$rid&page=$page&category=".($category == 'time' ? 'timedesc' : 'time')."\" onmouseover=\"self.status='${Lang['ReverseOrder']}'; return true\" onMouseOut=\"self.status=''; return true\">${Lang['Time']}</a>:</td>";
		echo "<td id=\"headerr\" width=\"12\">&nbsp;</td></tr>\n";

		$i = 0;

		while (($t = $db->fetchrow()) && ++$i) {
			switch($t['type']) {
				case 'statuschange':
					$id = '';
					$msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmStatusChange']}";
					break;
				case 'namechange':
					$id = '';
					$msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmNameChange']}";
					break;
				case 'recultivation':
					$id = '';
					$msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmRecultivation']} <font class=\"plus\">${t['to']}</font>, <b>${Lang['Score']}:</b> <font class=\"result\">" . div($t['credits']) . "</font>";
					break;
				case 'counciladmit':
					$id = 'admit';
					$msg = "<a href=\"whois.php?name=${t['to']}\">${t['to']}</a> ${Lang['cmCouncilAdmit']} <a href=\"whois.php?name=${t['from']}\">${t['from']}</a>";
					break;
				case 'ownerchange':
					$id = 'admit';
					$msg = "<a href=\"whois.php?name=${t['to']}\">${t['to']}</a> ${Lang['cmOwnerChange']} <a href=\"whois.php?name=${t['from']}\">${t['from']}</a>";
					break;
				case 'join':
					$id = 'join';
					$msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmJoin']}...";
					break;
				case 'leave':
					$id = 'minus';
					$msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmLeave']}...";
					break;
				case 'attack':
					$id = 'alert';
					$msg = "<a href=\"whois.php?name=${t['to']}\">${t['to']}</a> ${Lang['cmAttack']} <a href=\"whois.php?name=${t['from']}\">${t['from']}</a>!";
					break;
				case 'councildismiss':
					$id = 'here';
					$msg = "<a href=\"whois.php?name=${t['to']}\">${t['to']}</a> ${Lang['cmCouncilDismiss']} <a href=\"whois.php?name=${t['from']}\">${t['from']}</a>!";
					break;
				case 'reject':
					$id = 'here';
					$msg = "<a href=\"whois.php?name=${t['to']}\">${t['to']}</a> ${Lang['cmReject']} <a href=\"whois.php?name=${t['from']}\">${t['from']}</a>!";
					break;
				case 'admit':
					$id = 'admit';
					$msg = "<a href=\"whois.php?name=${t['to']}\">${t['to']}</a> ${Lang['cmAdmit']} <a href=\"whois.php?name=${t['from']}\">${t['from']}</a>!";
					break;
				case 'donate':
					$id = '';
					if ($t['to']) $msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmDonateUser']} <a href=\"whois.php?name=${t['to']}\">${t['to']}</a> - ";
					else $msg = "<a href=\"whois.php?name=${t['from']}\">${t['from']}</a> ${Lang['cmDonate']} - ";
					if ($t['credits']) $msg .= "<b>${Lang['Credits']}:</b> <font class=\"result\">" . div($t['credits']) . "</font> <b>[!]</b>";
					if ($t['credits'] && $t['crystals']) $msg .= ', ';
					if ($t['crystals']) $msg .= "<b>${Lang['Crystals']}:</b> <font class=\"capacity\">" . div($t['crystals']) . "</font> <b>[C]</b>";
					break;
			}

			echo "\t\t<tr height=\"24\"" . ($id ? " id=\"$id\"" : '') . ">\n";
			echo "\t\t<td width=\"12\">&nbsp;</td>\n";
			echo "\t\t<td align=\"center\">$msg</td>\n";
			echo "\t\t<td width=\"12\">&nbsp;</td>\n";
			echo "\t\t<td align=\"center\"><a href=\"stardate.php?date=${t['time']}\">" . div($t['time']) . "</a></td>\n";
			echo "\t\t<td width=\"12\">&nbsp;</td>\n";
			echo "\t\t</tr>\n";

		}

		echo "\t\t<tr><td colspan=\"7\">&nbsp;</td></tr>\n";
		echo "\t\t</table>\n";
	}

	$n = $page;

	$s = ($n ? "<a href=\"clan.php?category=$category&page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
	$s .= ($n ? "<a href=\"clan.php?category=$category&page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';

	$a = $n > 5 ? $n - 5 : 0;
	$b = $n < $m - 5 ? $n + 5 : $m;

	if ($a > 0) $s .= '... ';

	for ($i = $a; $i <= $b; $i++) {
		if ($i != $n) $s .= "<a href=\"clan.php?category=$category&page=$i\">";
		$s .= $i + 1;
		if ($i != $n) $s .= "</a>";
		$s .= ' ';
	}

	if ($b < $m) $s .= ' ...';

	$s .= " &nbsp; ".($n < $m ? "<a href=\"clan.php?category=$category&page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
	$s .= ' &nbsp; '.($n < $m ? "<a href=\"clan.php?category=$category&page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');

	tableend($s);

	echo "\t<script>\n\t<!--\n\tfunction ask(\$url) {\n\t\tif (confirm('${Lang['AreYouSure?']}')) location.href = \$url;\n\t}\n\n\tfunction avatar() {\n\t\t\$msg = prompt('${Lang['EnterAvatarURL']}', '');\n\t\tif (\$msg > '') {\n\t\t\t\$url = '${_SERVER['PHP_SELF']}?rid=$rid&action=changeclanavatar&url=' + \$msg;\n\t\t\t	document.location.href = \$url;\n\t\t}\n";
	if ($Group['avatar']) echo "\t\telse if (\$msg != null) {\n\t\t\t\$url = '${_SERVER['PHP_SELF']}?rid=$rid&action=changeclanavatar';\n\t\t\tdocument.location.href = \$url;\n\t\t}\n";
	echo "\t}\n\n\tfunction description() {\n\t\t\$msg = prompt('${Lang['EnterDescription']}:', '');\n\t\t\$msg = \$msg.replace(/\\+/g,\"%2B\"); // code: kot\n\t\t\$msg = \$msg.replace(/\\&/g,\"%26\");\n\t\t\$msg = \$msg.replace(/\\#/g,\"%23\");\n\t\tif (\$msg > '') {\n\t\t\t\$url = '${_SERVER['PHP_SELF']}?rid=$rid&action=changeclandescription&description=' + \$msg;\n\t\t\tdocument.location.href = \$url;\n\t\t}\n";
	if ($Group['description']) echo "\t\telse if (\$msg != null) {\n\t\t\t\$url = '${_SERVER['PHP_SELF']}?rid=$rid&action=changeclandescription';\n\t\t\tdocument.location.href = \$url;\n\t\t}\n";
	echo "\t}\n\t//-->\n\t</script>\n";
}
else {
	tablebegin($Lang['Clan']);

	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

	tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Clan']);
}

require('include/footer.php');
