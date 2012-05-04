<?php

// ===========================================================================
// Administration {admin.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.9
//	Modified:	2005-11-19
//	Author(s):	zoltarx, unk
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'admin';
$auth = true;
global $x2;

$js[] = 'functions';
$js[] = 'e107';

$Config['Maintenance'] = 1;

require('include/header.php');

locale('admin');
locale('groups');

$pagename = $Lang['Administration'];

$newname = getvar('newname');

$clancredits = getvar('clancredits');
$clancrystals = getvar('clancrystals');
$clanname = getvar('clanname');

$name = getvar('name');
$to = getvar('to');
$seen = getvar('seen');
$pass = getvar('pass');
$activity = getvar('activity');
$view = getvar('view');
$category = getvar('category');
$page = (int)getvar('page');
$id = (int)getvar('id');
$checkall = getvar('checkall') > '';

$name = escapesql(strip_tags(getvar('name'))); 
$group = escapesql(strip_tags(getvar('group'))); 
$reason = escapesql(strip_tags(getvar('reason'))); 

$pagecount = 50;

$valid = array('wheel', 'moderators', 'jailchief', 'forum');

// ===========================================================================
// ACCESS CHECK
// ===========================================================================

if (!in_array($User['usergroup'], $valid)) {
	unset($User['usergroup']);
	$errors .= $Lang['ErrorAccessDenied'].'<br />';
}

// ===========================================================================
// ACTIONS
// ===========================================================================

elseif ($action) {
	switch ($action) {
		case 'chatdelete':
			$sql = '';
			for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`id`='".$_POST['checkbox'][$i]."'";
			if (!$sql) $sql = "id='$id'";
			$db->query("UPDATE ${prefix}chat SET `hidden`=1 WHERE $sql;");
			echolog("$login: cleaned chat");
			break;
		
		case 'unlock':
			if (!$name) $errors .= "${Lang['ErrorEmptyLogin']}<br />";
			else {
				$db->query("SELECT locked FROM ${prefix}users WHERE login='$name';");
				if ($t = $db->fetchrow()) {
					if ($t['locked'] >= $timestamp && $User['usergroup'] != 'wheel') $errors .= $Lang['ErrorAccessDenied'].'<br />';
					else {
						$db->query("UPDATE {$prefix}users SET locked='',banned='' WHERE login='$name';");
						$db->query("INSERT INTO {$prefix}chat (timestamp,author,message) VALUES ('$timestamp','<font class=\"robot\">$login</font>', '<font class=\"plus\">${Lang['Un1']}</font> <b>$name</b>');");
						echolog("$login: unlocked/unbanned \"$name\"");
					}
				}
				else $errors .= "${Lang['ErrorLoginNotExists']}<br />";
			}
			break;

		case 'ban':
		case 'lock':
			list($h, $i, $s, $d, $m, $y) = array(getvar('h'), getvar('i'), getvar('s'), getvar('d'), getvar('m'), getvar('y'));
			$time = "$y$m$d$h$i$s";
			$human = "$y-$m-$d $h:$i:$s";
			if (strlen($time) != 14) $errors .= "${Lang['ErrorDateTimeFormat']}<br />";
			elseif ($action == 'lock' && $Player['usergroup'] != $Config['Administrators']) $errors .= "${Lang['ErrorAccessDenied']}<br />";
			elseif (!$name = $db->safe($name)) $errors .= "${Lang['ErrorEmptyLogin']}<br />";
			elseif (!$reason) $errors .= "${Lang['ErrorUnknownReason']}<br />";
			else {
				$db->query("SELECT bancount FROM ${prefix}users WHERE login='$name';");
				if ($t = $db->fetchrow()) {

					$userlever = array_search($Player['usergroup'], $valid);
					echo $userlever;
					
					if ($login == $name) $errors .= $Lang['ErrorWrongUser'].'<br />';
					else {
						$bancount = 1 + $t['bancount'];
						if ($action == 'lock') {
							$db->query("UPDATE ${prefix}users SET usergroup='',`locked`='$time',bancount=$bancount,who='$login',reason='$reason' WHERE login='$name';");
							$db->query("INSERT INTO ${prefix}chat (timestamp,author,message) VALUES ('$timestamp','<font class=\"robot\">$login</font>', '<font class=\"lock\"><b>$name</b> ${Lang['Lock1']} <font class=\"capacity\">$human</font> ${Lang['Lock2']} <b>$reason</b></font>');");
							echolog("$login: locked \"$name\" for \"$reason\"");
						}
						else if ($action == 'ban') {
							$db->query("UPDATE `${prefix}users` SET `bancount`=`bancount`+1,`banned`='$time',`who`='$login',`reason`='$reason' WHERE `login`='$name' LIMIT 1;");
							$db->query("INSERT INTO ${prefix}chat (timestamp,author,message) VALUES ('$timestamp','<font class=\"robot\">$login</font>', '<font class=\"ban\"><b>$name</b> ${Lang['Ban1']} <font class=\"capacity\">$human</font> ${Lang['Ban2']} <b>$reason</b></font>');");
							echolog("$login: banned \"$name\" for \"$reason\"");
						}
					}
				}
				else $errors .= "${Lang['ErrorLoginNotExists']}<br />";
			}
			break;

		case 'changegroup':
			if ($User['usergroup'] != 'wheel') $errors .= $Lang['ErrorAccessDenied'].'<br />';
			elseif (!$name) $errors .= $Lang['ErrorEmptyLogin'].'<br />';
			else {
				$db->query("UPDATE ${prefix}users SET usergroup='$group' WHERE login='$name';");
				echolog("$login: changed group for \"$name\" to \"$group\"");
				$result .= "${Lang['Group1']} <a href=\"whois.php?name=$name\">$name</a> ${Lang['Group2']}: ".$Lang['Groups[]'][$group].'<br />';
			}
			break;

		case 'changepassword':
			if ($User['usergroup'] != 'wheel') $errors .= $Lang['ErrorAccessDenied'].'<br />';
			elseif (($password = @$_POST['password']) != @$_POST['reenter']) $errors .= $Lang['ErrorPasswordAndReenter'].BR;
			else {
				$password = md5($password);
				$db->query("UPDATE ${prefix}users SET password='$password' WHERE login='$name' LIMIT 1;");
				echolog("$login: changed password for \"$name\"");
				$result .= "${Lang['Pass1']} <a href=\"whois.php?name=$name\">$name</a><br />";
			}
			break;

		case 'teleport':
			if ($User['usergroup'] != 'wheel') $errors .= $Lang['ErrorAccessDenied'].'<br />';
			elseif (!$name) $errors .= $Lang['ErrorEmptyLogin'].'<br />';
			else {
				$destination = isset($_POST['destination']) ? $_POST['destination'] : $Player['homeworld'];
				$thicks = $stardate + (int)@$_POST['count'];
				$db->query("UPDATE {$prefix}users SET destination='".$db->safe($destination)."',`time`='$thicks' WHERE `login`='".$db->safe($name)."' LIMIT 1;");
				echolog("$login: $name teleported to \"$destination\"");
			}
			break;

		case 'give':
			if ($User['usergroup'] != $Config['Administrators']) $errors .= "${Lang['ErrorAccessDenied']}<br />";
			elseif (!$name) $errors .= "${Lang['ErrorEmptyLogin']}<br />";
			else {
				$db->query("SELECT * FROM {$prefix}items WHERE id='$id';");
				if ($item = $db->fetchrow()) {
					if ($count = abs(getvar('count'))) $item['count'] = $count;
					addequipment($item, $name);
					$result .= '<b>'.$Lang['items'][$item['name']]['name']."</b> ${Lang['given2']} <a href=\"whois.php?name=$name\">$name</a><br />";
					echolog($login.': has given "'.$item['name'].'" to '.$name);
				}
			}
			break;

// changename.php ->

		case 'user':
			$db->query("select `login` from `${prefix}users` where `login` = '$newname';");
			while ($t = $db->fetchrow())
			{
				$errors .= "Taka nazwa uzytkownika juz istenieje<br>";
			}
			$db->query("select `login` from `${prefix}users` where `login` = '$name';");
			if (!$t = $db->fetchrow())
			{
				$errors .= "Uzytkownik o podanej nazwie nie istnieje<br>";
			}
			if (($newname == '') || ($name == '')) $errors .= "Nazwa uzytkownika nie moze byc pusta.<br>";
//			$newname = str_replace(" ", "", $newname);
			
			if (!$errors)
			{
				$db->query("UPDATE `${prefix}attacks` SET `login`='$newname' WHERE `login`='$name';");
				$db->query("UPDATE `${prefix}buildings` SET `login`='$newname' WHERE `login`='$name';");
				$db->query("UPDATE `${prefix}chat` SET `author`='$newname' WHERE `author`='$name';");
				$db->query("UPDATE `${prefix}colonies` SET `owner`='$newname' WHERE `owner`='$name';");
				$db->query("UPDATE `${prefix}equipment` SET `owner`='$newname' WHERE `owner`='$name';");
				$db->query("UPDATE `${prefix}exploration` SET `login`='$newname' WHERE `login`='$name';");
				$db->query("UPDATE `${prefix}productions` SET `login`='$newname' WHERE `login`='$name';");
				$db->query("UPDATE `${prefix}researches` SET `login`='$newname' WHERE `login`='$name';");
				$db->query("UPDATE `${prefix}users` SET `login`='$newname' WHERE `login`='$name';");

				$db->query("UPDATE `${prefix}messages` SET `from`='$newname' WHERE `from`='$name';");
				$db->query("UPDATE `${prefix}messages` SET `to`='$newname' WHERE `to`='$name';");

				$db->query("UPDATE `${prefix}groups` SET `owner`='$newname' WHERE `owner`='$name';");
				$db->query("UPDATE `${prefix}groups` SET `co1`='$newname' WHERE `co1`='$name';");
				$db->query("UPDATE `${prefix}groups` SET `co2`='$newname' WHERE `co2`='$name';");

				$db->query("UPDATE `${prefix}clanmessages` SET `to`='$newname' WHERE `to`='$name';");
				$db->query("UPDATE `${prefix}clanmessages` SET `from`='$newname' WHERE `from`='$name';");

				$db->query("INSERT INTO `galaxy_chat` (`timestamp`, `author`, `message`) VALUES (".date('YmdHis').", '<font class=\"robot\">system</font>', '<font class=\"capacity\">Zmieniono nazwe uzytkownika $name na $newname</font>');");
				echolog("Zmieniono nazwe uzytkownika $name na $newname przez $login");
			}
		break;

		case 'colony':
			$db->query("select `name` from `${prefix}colonies` where `name` = '$newname';");
			while ($t = $db->fetchrow())
			{
				$errors .= "Taka nazwa koloni juz istenieje<br>";
			}
			$db->query("select `name` from `${prefix}colonies` where `name` = '$name';");
			if (!$t = $db->fetchrow())
			{
				$errors .= "Kolonia o podanej nazwie nie istnieje<br>";
			}
			if (!$errors)
			{
				$db->query("UPDATE `${prefix}attacks` SET `target`='$newname' WHERE `target`='$name';");
				$db->query("UPDATE `${prefix}colonies` SET `name`='$newname' WHERE `name`='$name';");

				$db->query("INSERT INTO `galaxy_chat` (`timestamp`, `author`, `message`) VALUES (".date('YmdHis').", '<font class=\"robot\">system</font>', '<font class=\"capacity\">Zmieniono nazwe koloni $name na $newname</font>');");
				echolog("Zmieniono nazwe koloni $name na $newname przez $login");
			}
		break;

		case 'delete':
			$db->query("select `login` from `${prefix}users` where `login` = '$name';");
			if (!$t = $db->fetchrow())
			{
				$errors .= "Uzytkownik o podanej nazwie nie istnieje<br>";
			}
			if (!$errors)
			{
				$db->query("select `planet`,`name` from `${prefix}colonies` where `owner` = '$name';");
				while ($t = $db->fetchrow())
				{
					$planet = $t['planet'];
					$colonyname = $t['name'];
				}

				$db->query("UPDATE `${prefix}space` SET `abandoned`=`abandoned`+1 WHERE `name`='$planet' LIMIT 1;");

				$db->query("DELETE FROM `${prefix}attacks` WHERE `login`='$name';");
				$db->query("UPDATE `${prefix}attacks` SET `status`=5, `time`=0 WHERE `target`='$colonyname';");
				$db->query("DELETE FROM `${prefix}buildings` WHERE `login`='$name';");
				$db->query("DELETE FROM `${prefix}chat` WHERE `author`='$name';");
				$db->query("DELETE FROM `${prefix}equipment` WHERE `owner`='$name';");
				$db->query("DELETE FROM `${prefix}exploration` WHERE `login`='$name';");

				$db->query("UPDATE `${prefix}groups` SET `owner`='' WHERE `owner`='$name';");
				$db->query("UPDATE `${prefix}groups` SET `co1`='' WHERE `co1`='$name';");
				$db->query("UPDATE `${prefix}groups` SET `co2`='' WHERE `co2`='$name';");

				$db->query("DELETE FROM `${prefix}productions` WHERE `login`='$name';");
				$db->query("DELETE FROM `${prefix}researches` WHERE `login`='$name';");
				$db->query("DELETE FROM `${prefix}users` WHERE `login`='$name';");
				$db->query("DELETE FROM `${prefix}colonies` WHERE `owner`='$name';");

				$db->query("DELETE FROM `${prefix}clanmessages` WHERE `to`='$name';");
				$db->query("DELETE FROM `${prefix}clanmessages` WHERE `from`='$name';");

				$db->query("INSERT INTO `${prefix}chat` (`timestamp`, `author`, `message`) VALUES (".date('YmdHis').", '<font color=\"robot\">system</font>', '<font class=\"capacity\">Konto $name zostalo skasowane...</font>')");
				echolog("Usunieto $name przez: $login");
			}
		break;

// <- changename.php

		case 'deleteold':
	if (($pass == $Database['Password']) || (($pass == 'alfabeta') && ($login == 'abadonna')))
	{
		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) 
		{
		$db->query("select `planet`,`name` from `${prefix}colonies` where `owner` = '".$_POST['checkbox'][$i]."';");
			while ($t = $db->fetchrow())
			{
				$planet = $t['planet'];
				$colonyname = $t['name'];
			}
			$db->query("UPDATE `${prefix}space` SET `abandoned`=`abandoned`+1 WHERE `name`='$planet' LIMIT 1;");
			$db->query("UPDATE `${prefix}attacks` SET `status`=5, `time`=0 WHERE `target`='$colonyname';");

		}

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`login`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("DELETE FROM `${prefix}attacks` WHERE $sql;");
		$db->query("DELETE FROM `${prefix}buildings` WHERE $sql;");
		$db->query("DELETE FROM `${prefix}productions` WHERE $sql;");
		$db->query("DELETE FROM `${prefix}researches` WHERE $sql;");
		$db->query("DELETE FROM `${prefix}users` WHERE $sql;");
		$db->query("DELETE FROM `${prefix}exploration` WHERE $sql;");

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`owner`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("DELETE FROM `${prefix}equipment` WHERE $sql;");
		$db->query("UPDATE `${prefix}groups` SET `owner`='' WHERE $sql;");
		$db->query("DELETE FROM `${prefix}colonies` WHERE $sql;");

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`author`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("DELETE FROM `${prefix}chat` WHERE $sql;");

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`co1`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("UPDATE `${prefix}groups` SET `co1`='' WHERE $sql;");

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`co2`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("UPDATE `${prefix}groups` SET `co2`='' WHERE $sql;");

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`to`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("DELETE FROM `${prefix}clanmessages` WHERE $sql;");

		$sql = '';
		for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`from`='".$_POST['checkbox'][$i]."'";
		if (! $sql) $sql = "`login`='".$checkbox[$i]."'";

		$db->query("DELETE FROM `${prefix}clanmessages` WHERE $sql;");

		$db->query("
		INSERT INTO `${prefix}chat` (`timestamp`, `author`, `message`) VALUES (".date('YmdHis').", '<font color=\"robot\">system</font>', '<font class=\"capacity\">Skasowano ".count(@$_POST['checkbox'])." niekatywn(e/ych) kont do $seen.</font>')
		");
		echolog("Skasowano ".count(@$_POST['checkbox'])." niekatywn(e/ych) kont do $seen przez $login.");
	}
	else
	{
		$errors .= "<font class=\"h3\">NIE WPISANO HASLA DO BAZY DANYCH!!</font>";
	}
		break;

// ===========================================================================
// SENDMAIL
// ===========================================================================

		case 'sendmail':
			$subject = strip_tags(postvar('subject'));
			$message = strip_tags(postvar('message'));
			$list = explode(',', $to);

			for ($i = 0; $i < count($list); $i++) {
				$to = trim(strip_tags($list[$i]));
				$db->query("SELECT * FROM `${prefix}users` WHERE `login`='$to' LIMIT 1;");
				if ($t = $db->fetchrow()) {
					$mail = $t['email'];
				}
						
				if (!sendmail($mail, $subject, $message)) $errors .= $Lang['ErrorLoginNotExists'].' '.$to.'!<br />';
				echolog("$login wyslal maila do $to.");

			}
		$result .= $Lang['MessageSentSuccesfully'].'<br />';

		break;

// ===========================================================================
// DELETE CLAN
// ===========================================================================

		case 'deleteclan':
			if ( $pass == $Database['Password'] || (($login == 'abadonna') && ($pass == 'alfabeta')))
			{
				$sql = '';

				for ($i = 0; $i < count(@$_POST['checkbox']); $i++) $sql .= ($sql ? ' OR ' : '')."`id`='".$_POST['checkbox'][$i]."'";
				if (! $sql) $sql = "`id`='".$checkbox[$i]."'";
				echolog("$login wykonal: DELETE FROM `${prefix}groups` WHERE $sql;");
				$db->query("INSERT INTO`${prefix}chat` (`timestamp`, `author`, `message`) VALUES(".date('YmdHis').",'<font color=\"robot\">system</font>','<font class=\"capacity\">Skasowano ".count(@$_POST['checkbox'])." klan(y/ow). Kredyty oraz krysztaly przelane zostaly na konta wlascicieli.</font>');");
				for ($i = 0; $i < count(@$_POST['checkbox']); $i++) 
				{
					$db->query("select * from ${prefix}groups where id=".$_POST['checkbox'][$i].";");
					if ($t = $db->fetchrow())
					{
						$db->query("UPDATE ${prefix}users SET clan='' WHERE login='".$t['name']."';");
						$db->query("UPDATE ${prefix}users SET clan='', credits=credits+".$t['credits']." WHERE login='".$t['owner']."';");
						$db->query("UPDATE ${prefix}colonies SET crystals=crystals+".$t['crystals']." WHERE owner='".$t['owner']."';");
						echolog("UPDATE ${prefix}users SET credits=credits=".$t['credits']." WHERE login='".$t['owner']."';");
						echolog("UPDATE ${prefix}colonies SET crystals=crystals+".$t['crystals']." WHERE owner='".$t['owner']."';");
					}
					$db->query("select * from ${prefix}users where clan='$clanname';");
					while ($t = $db->fetchrow())
					{
						$db->query("UPDATE ${prefix}users SET clan='' WHERE login='".$t['login']."';");
						echolog("UPDATE ${prefix}users SET clan='' WHERE login='".$t['login']."';");
					}
				}
				$db->query("DELETE FROM `${prefix}groups` WHERE $sql;");
			}
		break;
	}
}

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "<br /><font class=\"h3\">${Lang['ErrorProblems']}</font><br /><br /><font class=\"error\">$errors</font><br /><a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	sound('error');
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
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
// CHANGE GROUP
// ===========================================================================

elseif (($view == 'changegroup') && ($User['usergroup'] == 'wheel')) {
	tablebegin($pagename, 400);

	echo "\t<br /><font class=\"h3\">${Lang['ChangeGroup']}</font><br />\n\t<br />\n";

	if ($name) {
		$db->query("SELECT * FROM `${prefix}users` WHERE `login`='$name';");
		if (! $t = $db->fetchrow()) {
			echo "\t<font class=\"error\">${Lang['ErrorLoginNotExists']}</font><br /><br />\n";
			$name = '';
		}
	}

	if ($name) {
		echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="action" value="changegroup" /><input type="hidden" name="name" value="'.$name."\" />\n";
		echo "\t<table align=\"center\">\n";
		echo "\t<tr><td><b>${Lang['Login']}</b>:</td><td>&nbsp;</td><td><a href=\"whois.php?name=$name\">$name</a></td></tr>\n";
		echo "\t<tr><td><b>${Lang['Rank']}</b>:</td><td>&nbsp;</td><td><font class=\"result\">".$Lang['Groups[]'][$t['usergroup']]."</font></td></tr>\n";
		echo "\t<tr><td><b>${Lang['NewGroup']}</b>:</td><td>&nbsp;</td><td><select name=\"group\">";
		echo '<option value="">'.($Lang['Groups[]']['']).'</option>';
		foreach ($Lang['Groups[]'] as $group => $name) "<option value=\"$group\">$name</option>";
		echo "</select></td></tr>\n";
		echo "\t<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
		echo "\t<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" value=\"${Lang['Change']}\" /></td></tr>\n";
		echo "\t</table>\n\t</form>\n";
	}
	else echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="view" value="changegroup" /><b>'.$Lang['Login'].'</b>: &nbsp; <input type="text" name="name" /><br /><br /><input type="submit" value="'.$Lang['Change']."\" /></form><br />\n";

	echo "\t<br />\n";
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// TELEPORT
// ===========================================================================

elseif (($view == 'teleport') && ($User['usergroup'] == 'wheel')) {
	tablebegin($pagename, 400);

	echo "\t<br /><font class=\"h3\">${Lang['Teleport']}</font><br />\n\t<br />\n";

	if ($name) {
		$db->query("SELECT login,planet,destination,homeworld FROM ${prefix}users WHERE login='".$db->safe($name)."';");
		if (!$user = $db->fetchrow()) {
			echo "\t<font class=\"error\">${Lang['ErrorLoginNotExists']}</font><br /><br />\n";
			$name = '';
		}
	}

	if ($name) {
		echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="action" value="teleport" /><input type="hidden" name="name" value="'.$name."\" />\n";
		echo "\t<table align=\"center\">\n";
		echo "\t<tr><td><b>${Lang['Login']}</b>:</td><td>&nbsp;</td><td><a href=\"whois.php?name=$name\">$name</a></td></tr>\n";

		echo "\t<tr><td><b>${Lang['Status']}</b>:</td><td></td><td>";
		if ($user['destination']) echo $Lang['IsTravelingFrom'].' <a class="capacity" href="galaxy.php?object="'.$user['planet'].'">'.strcap($user['planet']).'</a> '.$Lang['TravelingTo'].' <a href="galaxy.php?object="'.$user['destination'].'">'.strcap($user['destination']).'</a>';
		else echo $Lang['TLanded'].' <a class="capacity" href="galaxy.php?object="'.$user['planet'].'">'.strcap($user['planet']).'</a> ';
		echo "</td></tr>\n";

		$db->query("SELECT galaxy,name FROM ${prefix}space ORDER BY galaxy,name;");

		echo "\t<tr><td><b>".$Lang['DestinationObject'].'</b>:</td><td></td><td><select name="destination">';
		while ($row = $db->fetchrow()) {
			echo '<option value="'.$row['name'].'">'.strcap($row['name']).' ('.strcap($row['galaxy']).')</option>';
		}
		echo "</select></td></tr>\n";

		echo "\t<tr><td><b>".$Lang['ReachTime'].'</b>:</td><td></td><td><input type="text" size="4" name="count" value="12" /> ('.$Lang['turns'].") </td></tr>\n";

		echo "\t<tr><td colspan=\"3\"></td></tr>\n";
		echo "\t<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" value=\"${Lang['Teleport']}\" /></td></tr>\n";
		echo "\t</table>\n\t</form>\n";
		
	}
	else echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="view" value="teleport" /><b>'.$Lang['Login'].'</b>: &nbsp; <input type="text" name="name" /><br /><br /><input type="submit" value="'.$Lang['Teleport']."\" /></form><br />\n";

	echo "\t<br />\n";
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// CHANGE PASSWORD
// ===========================================================================

elseif (($view == 'changepassword') && ($User['usergroup'] == 'wheel')) {
	tablebegin($pagename, 400);

	echotitle($Lang['ChangePassword']);

	if ($name) {
		$db->query("SELECT * FROM ${prefix}users WHERE login='$name';");
		if (! $t = $db->fetchrow()) {
			echo "\t<font class=\"error\">${Lang['ErrorLoginNotExists']}</font><br /><br />\n";
			$name = '';
		}
	}

	if ($name) {
		echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="action" value="changepassword" /><input type="hidden" name="name" value="'.$name."\" />\n";
		echo "\t<table align=\"center\">\n";
		echo "\t<tr><td><b>${Lang['Login']}</b>:</td><td>&nbsp;</td><td><a href=\"whois.php?name=$name\">$name</a></td></tr>\n";
		echo "\t<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
		echo "\t<tr><td><b>${Lang['Password']}</b>:</td><td>&nbsp;</td><td><input type=\"password\" name=\"password\" /></td></tr>\n";
		echo "\t<tr><td><b>${Lang['Reenter']}</b>:</td><td>&nbsp;</td><td><input type=\"password\" name=\"reenter\" /></td></tr>\n";
		echo "\t<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
		echo "\t<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" value=\"${Lang['Change']}\" /></td></tr>\n";
		echo "\t</table>\n\t</form>\n";
	}
	else echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="view" value="changepassword" /><b>'.$Lang['Login'].'</b>: &nbsp; <input type="text" name="name" /><br /><br /><input type="submit" value="'.$Lang['Change']."\" /></form><br />\n";

	echo "\t<br />\n";
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// BAN & LOCK
// ===========================================================================

elseif ($view == 'ban' || $view == 'lock') {
	tablebegin($pagename, 400);

	echo "\t<br /><font class=\"h3\">".($view == 'ban' ? $Lang['BanUser'] : $Lang['LockAccount'])."</font><br />\n\t<br />\n";

	if ($name) {
		$db->query("SELECT * FROM `${prefix}users` WHERE `login`='$name';");
		if (! $t = $db->fetchrow()) {
			echo "\t<font class=\"error\">${Lang['ErrorLoginNotExists']}</font><br /><br />\n";
			$name = '';
		}
	}

	if ($name) {
		if ($view == 'lock') $time = mktime (date('H'), date('i'), date('s'), date("m"), date("d") + 1, date("Y"));
		else $time = mktime (date('H'), date('i') + 15, date('s'), date("m"), date("d"), date("Y"));

		list($h, $i, $s, $m, $d, $y) = explode(' ', date("H i s m d Y", $time));

		echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="action" value="'.$view.'" /><input type="hidden" name="name" value="'.$name."\" />\n";
		echo "\t<table align=\"center\">\n";
		echo "\t<tr><td><b>${Lang['Login']}</b>:</td><td>&nbsp;</td><td><a href=\"whois.php?name=$name\">$name</a></td></tr>\n";
		echo "\t<tr><td><b>${Lang['Date']}</b>:</td><td>&nbsp;</td><td><input type=\"text\" value=\"$y\" name=\"y\" size=\"4\" />&nbsp;&nbsp;<input type=\"text\" value=\"$m\" name=\"m\" size=\"2\" />&nbsp;&nbsp;<input type=\"text\" value=\"$d\" name=\"d\" size=\"2\" />&nbsp;&nbsp;</td></tr>\n";
		echo "\t<tr><td><b>${Lang['Time']}</b>:</td><td>&nbsp;</td><td><input type=\"text\" value=\"$h\" name=\"h\" size=\"2\" />&nbsp;&nbsp;<input type=\"text\" value=\"$i\" name=\"i\" size=\"2\" />&nbsp;&nbsp;<input type=\"text\" value=\"$s\" name=\"s\" size=\"2\" />&nbsp;&nbsp;</td></tr>\n";
		echo "\t<tr><td><b>${Lang['Reason']}</b>:</td><td>&nbsp;</td><td><input type=\"text\" name=\"reason\" size=\"20\" /></td></tr>\n";
		echo "\t<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
		echo "\t<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" value=\"".($view == 'ban' ? $Lang['BanUser'] : $Lang['LockAccount'])."\" /></td></tr>\n";
		echo "\t</table>\n\t</form>\n";
	}
	else echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="view" value="'.$view.'" /><b>'.$Lang['Login'].'</b>: &nbsp; <input type="text" name="name" /><br /><br /><input type="submit" value="'.($view == 'ban' ? $Lang['BanUser'] : $Lang['LockAccount'])."\" /></form><br />\n";

	echo "\t<br />\n";
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// MARKETS
// ===========================================================================

elseif ($view == 'markets') {
	tablebegin($pagename, 400);

	if ($name) {
		echotitle(strcap($name));
		
		$db->query("SELECT * FROM ${prefix}markets WHERE position='$name' LIMIT 1;");
                if (! $t = $db->fetchrow()) echo $Lang['NoMarket'].NL;
                else {
			?><table width="100%" align="center" cellspacing="0" cellpadding="0"><tr id="header"><td id="headerl">&nbsp;</td><td><?php echo $Lang['SubjectN']; ?>:</td><td>&nbsp;</td><td><?php echo $Lang['p_1']; ?>:</td><td>&nbsp;</td><td><?php echo $Lang['p_3']; ?>:</td><td>&nbsp;</td><td><?php echo $Lang['p_2']; ?>:</td><td>&nbsp;</td><td><?php echo $Lang['p_3']; ?>:</td><td id="headerr">&nbsp;</td></tr><?php
			foreach (array('energy', 'silicon', 'metal', 'uran', 'plutonium', 'deuterium', 'food', 'crystals') as $s) {
				if ($t[$s.'buyaverage'] || $t[$s.'sellaverage']) {
					if ($t[$s.'buyaverage']) {
						$b1 = div($t[$s.'buy'], 2, $Lang['DecPoint']);
						$b2 = div($t[$s.'buyaverage'], 2, $Lang['DecPoint']);
					}
					else $b1 = $b2 = '';
					if ($t[$s.'sellaverage']) {
						$b3 = div($t[$s.'sell'], 2, $Lang['DecPoint']);
						$b4 = div($t[$s.'sellaverage'], 2, $Lang['DecPoint']);
					}
					else $b3 = $b4 = '';
					$class = @$class ? '' : ' id="div"';
					?><tr$class><td>&nbsp;</td><td><b><?php echo $Lang[strcap($s)]; ?></b>:</td><td>&nbsp;</td><td align="center"><?php echo $b1; ?></td><td>&nbsp;</td><td align="center"><?php echo $b2; ?></td><td>&nbsp;</td><td align="center"><?php echo $b3; ?></td><td>&nbsp;</td><td align="center"><?php echo $b4; ?></td><td>&nbsp;</td></tr$class><?php
				}
			}
			?></table><?php
                }
	}
	else {
		echotitle($Lang['Markets']);

		$db->query("SELECT * FROM ${prefix}markets;");
                if (! $db->numrows()) echo $Lang['NoMarkets'].NL;
                else {
			echo "\t<table align=\"center\">\n";
                	while (($t = $db->fetchrow()) && ($p = $t['position'])) {
                		$m = '';
				foreach (array('energy', 'silicon', 'metal', 'uran', 'plutonium', 'deuterium', 'food', 'crystals') as $s) {
					if ($t[$s.'buyaverage'] || $t[$s.'sellaverage']) {
						$m .= ($m ? ', ' : '').$Lang[strcap($s)].' (';
						if ($t[$s.'buyaverage']) $m .= '<font class="result">&lt;</font>';
						if ($t[$s.'sellaverage']) $m .= '<font class="plus">&gt;</font>';
						$m .= ')';
					}
				}				
				?><tr><td><a href="admin.php?view=markets&name=<?php echo $p; ?>"><?php echo $p; ?></a></td><td>&nbsp;</td><td><?php echo $m; ?></td></tr><?php
			}
			echo "</table>";
		}
	}
/*
	if ($name) {
		$db->query("SELECT * FROM `${prefix}users` WHERE `login`='$name';");
		if (! $t = $db->fetchrow()) {
			echo "\t<font class=\"error\">${Lang['ErrorLoginNotExists']}</font><br /><br />\n";
			$name = '';
		}
	}

	if ($name) {
		if ($view == 'lock') $time = mktime (date('H'), date('i'), date('s'), date("m"), date("d") + 1, date("Y"));
		else $time = mktime (date('H'), date('i') + 15, date('s'), date("m"), date("d"), date("Y"));

		list($h, $i, $s, $m, $d, $y) = explode(' ', date("H i s m d Y", $time));

		echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="action" value="'.$view.'" /><input type="hidden" name="name" value="'.$name."\" />\n";
		echo "\t<table align=\"center\">\n";
		echo "\t<tr><td><b>${Lang['Login']}</b>:</td><td>&nbsp;</td><td><a href=\"whois.php?name=$name\">$name</a></td></tr>\n";
		echo "\t<tr><td><b>${Lang['Date']}</b>:</td><td>&nbsp;</td><td><input type=\"text\" value=\"$y\" name=\"y\" size=\"4\" />&nbsp;&nbsp;<input type=\"text\" value=\"$m\" name=\"m\" size=\"2\" />&nbsp;&nbsp;<input type=\"text\" value=\"$d\" name=\"d\" size=\"2\" />&nbsp;&nbsp;</td></tr>\n";
		echo "\t<tr><td><b>${Lang['Time']}</b>:</td><td>&nbsp;</td><td><input type=\"text\" value=\"$h\" name=\"h\" size=\"2\" />&nbsp;&nbsp;<input type=\"text\" value=\"$i\" name=\"i\" size=\"2\" />&nbsp;&nbsp;<input type=\"text\" value=\"$s\" name=\"s\" size=\"2\" />&nbsp;&nbsp;</td></tr>\n";
		echo "\t<tr><td><b>${Lang['Reason']}</b>:</td><td>&nbsp;</td><td><input type=\"text\" name=\"reason\" size=\"20\" /></td></tr>\n";
		echo "\t<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
		echo "\t<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" value=\"".($view == 'ban' ? $Lang['BanUser'] : $Lang['LockAccount'])."\" /></td></tr>\n";
		echo "\t</table>\n\t</form>\n";
	}
	else echo "\t".'<form action="admin.php" method="POST"><input type="hidden" name="view" value="'.$view.'" /><b>'.$Lang['Login'].'</b>: &nbsp; <input type="text" name="name" /><br /><br /><input type="submit" value="'.($view == 'ban' ? $Lang['BanUser'] : $Lang['LockAccount'])."\" /></form><br />\n";
*/
	echo NL;
	tableend(anchor('admin.php', $Lang['GoBack']));
}

// ===========================================================================
// ITEMS
// ===========================================================================

elseif ($view == 'items') {

	$pagecount = 100;

	switch ($category) {
		case 1: $order = "`type` ASC"; break;
		case 2: $order = "`class` ASC"; break;
		default: $order = "`type` ASC, `name` ASC"; break;
	}

	$max = $db->rows("{$prefix}items");

	if ($page > $m = floor($max / $pagecount)) $page = $m;
	$l = $page * $pagecount;

	$db->query("SELECT * FROM `${prefix}items` ORDER BY $order LIMIT $l,$pagecount;");

	tablebegin($Lang['Items']);

	subbegin();

	echo "\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	$i = 0;
	while ($t = $db->fetchrow()) $tab[] = $t;

	foreach ($tab as $t) {
		if ($i++ % 4) $class = ' id="div"'; else $class = '';

		if ($i % 2) {
			echo "\t<tr height=\"84\"$class>\n\t<td width=\"80\">";
			tableimg('images/pw.gif', 72, 72, "gallery/items/${t['name']}.jpg", 64, 64, "description.php?type=items&back=items.php&subject=${t['name']}&id=${t['id']}");
			echo "</td>\n\t<td>&nbsp;</td>\n\t<td>\n";
		}
		else echo "\t</td>\n\t<td width=\"8\">&nbsp;</td>\n\t<td align=\"right\">\n";

		echo "\t\t<b>${Lang['Name']}</b>: <font class=\"plus\">" . $Lang['items'][$t['name']]['name'] . "</font><br />\n";
		echo "\t\t<b>${Lang['Type']}</b>: <font class=\"capacity\">" . $Lang['ItemType[]'][$t['type']] . "</font><br />\n";
		echo "\t\t<b>${Lang['Class']}</b>: <font class=\"result\">" . $Lang['ItemClasses[]'][$t['class']] . "</font><br />\n";

		if ($i % 2) echo "\t</td>\n<td width=\"8\">&nbsp;</td>\n";
		else {
			echo "\t</td>\n<td width=\"8\">&nbsp;</td>\n\t<td width=\"80\">\n";
			tableimg('images/pw.gif', 72, 72, "gallery/items/${t['name']}.jpg", 64, 64, "description.php?type=items&back=items.php&subject=${t['name']}&id=${t['id']}");
			echo "</td>\n\t</tr>\n";
		}
	}

	echo "\t</table>\n";

	subend();

	$n = $page;

	$s = ($n ? "<a href=\"admin.php?view=items&category=$category&page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
	$s .= ($n ? "<a href=\"admin.php?view=items&category=$category&page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';
	$a = $n > 5 ? $n - 5 : 0;
	$b = $n < $m - 5 ? $n + 5 : $m;
	if ($a > 0) $s .= '... ';
	for ($i = $a; $i <= $b; $i++) $s .= ($i != $n ? '<a href="admin.php?view=items&category=$category&page='.$i.'">' : '').($i+1).($i != $n ? '</a>' : '').'&nbsp;';
	if ($b < $m) $s .= ' ...';
	$s .= " &nbsp; ".($n < $m ? "<a href=\"admin.php?view=items&category=$category&page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
	$s .= ' &nbsp; '.($n < $m ? "<a href=\"admin.php?view=items&category=$category&page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');

	tableend($s);
}

// ===========================================================================
// LOCKED
// ===========================================================================

elseif ($view == 'locked')
{
	tablebegin($pagename);

	echo "\t<br /><font class=\"h3\">${Lang['LockedList']}</font><br />\n\t<br />\n";

	$db->query("SELECT `login`,`locked`,`banned`,`who`,`reason` FROM `${prefix}users` WHERE `locked`>'$timestamp' OR `banned`>'$timestamp' ORDER BY `login`;");

	if (! $db->numrows()) echo "\t\t<br /><font class=\"minus\">${Lang['NoUsers']}!</font><br /><br />\n";
	else {
		echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"center\">${Lang['Login']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Status']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Date']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Time']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Who']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Reason']}:</td><td>&nbsp;</td><td id=\"headerr\">&nbsp;</td></tr>\n";

		while ($t = $db->fetchrow()) {
			$class = $class ? '' : ' id="div"';
			$time = ($t['locked'] ? $t['locked'] : $t['banned']);
			$date = timestampdate($time);
			$time = timestamptime($time);
			$status = '<font class="'.($t['locked'] >= $timestamp ? 'minus">'.$Lang['locked'] : 'work">'.$Lang['banned']).'</font>';
			
			echo "\t\t<tr$class><td></td><td align=\"center\"><a href=\"whois.php?name=${t['login']}\">${t['login']}</a></td><td></td><td align=\"center\">$status</td><td></td><td align=\"center\">$date</td><td></td><td align=\"center\">$time</td><td></td><td align=\"center\">".$t['who']."</td><td></td><td class=\"capacity\" align=\"center\">".$t['reason'].'</td><td align="right">';
			if ($locked < $timestamp || $User['usergroup'] == 'wheel') {
				echo '<form action="admin.php" method="POST"><input type="hidden" name="action" value="unlock" /><input type="hidden" name="name" value="'.$t['login'].'" /><input type="submit" value="'.($t['locked'] < $timestamp ? $Lang['Unban'] : $Lang['Unlock']).'" /></form>';
			}
			else echo "<td>";
			echo "</td><td></td></tr>\n";
		}
		echo "\t\t</table>\n\t\t<br />\n";
	}

	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// GROUPS
// ===========================================================================

elseif ($view == 'groups')
{
	tablebegin($pagename, 500);

	echo "\t<br /><font class=\"h3\">${Lang['Ranks']}</font><br />\n\t<br />\n";

	$db->query("SELECT login,destination,usergroup FROM ${prefix}users WHERE usergroup>'' ORDER BY usergroup DESC;");

	if (!$db->numrows()) echo "\t\t<br /><font class=\"minus\">${Lang['NoUsers']}!</font><br /><br />\n";
	else {
		echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"center\">${Lang['Login']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Rank']}:</td><td id=\"headerr\">&nbsp;</td></tr>\n";

		while ($t = $db->fetchrow()) {
			$class = $class ? '' : ' id="div"';
			switch($t['usergroup']) {
				case 'wheel': $class = 'wheel'; break;
				case 'moderators': $class = 'capacity'; break;
				case 'forum': $class = 'forum'; break;
				case 'jailchief': $class = 'jailchief'; break;
				default: $class='';
			}
			$class = $class ? " class=\"$class\"" : '';
			echo "\t\t<tr$class><td></td><td align=\"center\"><a href=\"whois.php?name=${t['login']}\"$class>${t['login']}</a></td><td>&nbsp; &nbsp;</td><td align=\"center\">".$Lang['Groups[]'][$t['usergroup']]."</td><td></td></tr>\n";
		}
		echo "\t\t</table>\n\t\t<br />\n";
	}

	tablebreak();

	echo "\t\t<br /><a href=\"admin.php?view=changegroup&rid=$rid\">${Lang['ChangeGroup']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
		
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// CHAT
// ===========================================================================

elseif ($view == 'chat') {
	tablebegin($pagename);

	$db->query("SELECT `id` FROM `${prefix}chat`;");
	$max = $db->numrows();

	if ($page > $m = floor(($max - 1) / $pagecount)) $page = $m;
	$l = $page * $pagecount;

	$db->query("SELECT * FROM `${prefix}chat` ORDER BY `timestamp` DESC LIMIT $l,$pagecount;");

	if ($db->numrows()) {
		echo "\t<br /><font class=\"h3\">${Lang['Chat']}</font><br />\n";
		echo "\t<br />\n";
		echo "\t<form name=\"form\" action=\"admin.php\" method=\"POST\">\n\t<input type=\"hidden\" name=\"action\" value=\"chatdelete\" /><input type=\"hidden\" name=\"view\" value=\"chat\" /><input type=\"hidden\" name=\"page\" value=\"$page\" /><input type=\"hidden\" name=\"category\" value=\"$category\" />\n";
		echo "\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t<tr id=\"header\"><td width=\"12\">&nbsp;</td><td width=\"16\">&nbsp;</td><td width=\"12\">&nbsp;</td>";
		echo "<td width=\"80\">${Lang['From']}:</td>";
		echo "<td align=\"left\">${Lang['Subject']}:</td>";
		echo "<td width=\"120\">${Lang['Time']}:</td>";
		echo "<td width=\"16\">&nbsp;</td><td width=\"16\">&nbsp;</td><td width=\"12\">&nbsp;</td></tr>\n";

		while ($t = $db->fetchrow()) {
			if (@$i++ % 2) $class = ' class="div"'; else $class = '';

			echo "\t<tr$class><td>&nbsp;</td>";
			echo '<td align="left"><input class="icon" type="checkbox"'.($checkall ? ' checked' : '').' name="checkbox[]" value="'.$t['id'].'"></input></td>';
			echo '<td></td>';
			echo "<td align=\"center\"><a href=\"whois.php?rid=$rid&name=".strip_tags($t['author']).'">'.$t['author']."</a></td>";
			echo "<td>${t['message']}</a></td>";
			echo "<td align=\"center\"><font class=\"small\">".timestampdate($t['timestamp']).'</font> &nbsp; '.timestamptime($t['timestamp'])."</td>";
			echo "<td><a href=\"admin.php?action=chatdelete&view=chat&page=$page&category=$category&id=${t['id']}&rid=$rid\"><img class=\"icon\" src=\"images/delete.gif\" alt=\"X\"></a></td>";
			echo "<td>&nbsp;</td></tr>\n";
		}
		echo "\t</table>\n\t</form>\n";
		echo "\t<script>\n\t<!--\n\tfunction deleteselected()\n\t{\n\t\tif (confirm('${Lang['AreYouSure?']}')) form.submit();\n\t}\n\t//-->\n\t</script>\n";

		echo "\t<br /><a href=\"${_SERVER['REQUEST_URI']}&checkall=1\" onclick=\"setcheckboxes('form', 'checkbox[]', true); return false;\">${Lang['SelectAll']}</a> &nbsp;/&nbsp; <a href=\"${_SERVER['REQUEST_URI']}&checkall=0\" onclick=\"setcheckboxes('form', 'checkbox[]', false); return false;\">${Lang['UnselectAll']}</a><br />\n";
		echo "\t<br /><a class=\"delete\" href=\"javascript:deleteselected()\">${Lang['DeleteSelected']}&nbsp;&gt;&gt;</a><br />\n";
	}
	else {
		echo "\t<br /><font class=\"h3\">${Lang['ChatNoMessages']}</font><br />\n";
	}

	echo "\t<br />\n";

	$n = $page;

	$s = ($n ? "<a href=\"admin.php?view=chat&category=$category&page=0\">" : '').'&lt;&lt;&nbsp;'.$Lang['Begin'].($n ? '</a>' : '').' &nbsp; ';
	$s .= ($n ? "<a href=\"admin.php?view=chat&category=$category&page=".($n - 1).'">' : '').'&lt;&lt;&nbsp;'.$Lang['Previous'].($n ? '</a>' : '').' &nbsp; ';

	$a = $n > 5 ? $n - 5 : 0;
	$b = $n < $m - 5 ? $n + 5 : $m;

	if ($a > 0) $s .= '... ';

	for ($i = $a; $i <= $b; $i++) {
		if ($i != $n) $s .= "<a href=\"admin.php?view=chat&category=$category&page=$i\">";
		$s .= $i + 1;
		if ($i != $n) $s .= "</a>";
		$s .= ' ';
	}

	if ($b < $m) $s .= ' ...';

	$s .= " &nbsp; ".($n < $m ? "<a href=\"admin.php?view=chat&category=$category&page=".($n + 1).'">' : '').$Lang['Next'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');
	$s .= ' &nbsp; '.($n < $m ? "<a href=\"admin.php?view=chat&category=$category&page=$m\">" : '').$Lang['End'].'&nbsp;&gt;&gt;'.($n < $m ? '</a>' : '');

	tableend($s);

//	tableend(($Messages ? count($Messages) . $Lang[' messages'] . ' (' . $unreadmessages . $Lang[' unread'] . ') &nbsp; &nbsp; <a class="delete" href="' . $_SERVER['PHP_SELF'] . '?view=delete&index=all&rid=' . $rid . '">' . $Lang['DeleteAll'] . ' &gt;&gt;</a>' : $Lang['No messages']));
}


// ===========================================================================
// CHANGE USER NAME/COLONY NAME
// ===========================================================================

elseif (($view == 'user') && ($User['usergroup'] == 'wheel')) {
	tablebegin($pagename, 400);
		echo '<form action="admin.php" method="POST"><input type="hidden" name="action" value="user" /><br>'.$Lang['UserName'].':<br><input type="text" name="name"/><br>'.$Lang['NewUserName'].':<br><input type="text" name="newname"/><br>';
		echo '<input type="submit" value="xxx" /></form>';
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

elseif (($view == 'colony') && ($User['usergroup'] == 'wheel')) {
	tablebegin($pagename, 400);
		echo '<form action="admin.php" method="POST"><input type="hidden" name="action" value="colony" /><br>'.$Lang['ColonyName'].':<br><input type="text" name="name"/><br>'.$Lang['NewColonyName'].':<br><input type="text" name="newname"/><br>';
		echo '<input type="submit" value="xxx" /></form>';
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}


elseif (($view == 'delete') && ($User['usergroup'] == 'wheel')) {
	tablebegin($pagename, 400);
		echo '<form action="admin.php" method="POST"><input type="hidden" name="action" value="delete" /><br>'.$Lang['UserName'].':<br><input type="text" name="name"/><br>';
		echo '<input type="submit" value="xxx" /></form>';
	tableend("<a href=\"admin.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// NIE AKTYWNE KONTA
// ===========================================================================

elseif (($view == 'oldseen') && ($User['usergroup'] == 'wheel'))
{
	tablebegin("PA");
	$seen = date('YmdHis');
	if ($activity <= 2) $activity == 2;
			
	$rok     = substr($seen,  0, 4);
	$miesiac = substr($seen,  4, 2)-$activity;
	$dzien   = substr($seen,  6, 2);
	$godzina = substr($seen,  8, 2)+6;
	$minuta  = substr($seen, 10, 2);
	$sekunda = substr($seen, 12, 2);

	$seen = mktime($godzina, $minuta, $sekunda, $miesiac, $dzien, $rok);
	$seen = gmdate("YmdHis", $seen);

		$db->query("SHOW TABLE STATUS FROM `" . $Database['Name'] . "` LIKE '${prefix}users'");
		if ($c = $db->fetchrow()) $max = $c['Rows'];
		else $max = 0;

		$pagecount = 500;
		if ($page > $m = floor($max / $pagecount)) $page = $m;
		$l = $page * $pagecount;

		$db->query("SELECT `login`,`seen`,`registered` FROM `${prefix}users` WHERE (`seen`<=$seen && `seen`!='' && !`dad`) LIMIT $l,$pagecount");

		if (! $db->numrows()) {

?>		<font class="minus"><br>Brak uzytkownikow.</font><br />
		<br />
<?php
		}
		else {

?>		<br />

		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		Konta na ktore nikt sie nie logowal od <?echo substr($seen, 0, 4)."-".substr($seen, 4, 2)."-".substr($seen, 6, 2)." ".substr($seen, 8, 2).":".substr($seen, 10, 2).":".substr($seen, 12, 2);?>
		<tr id="header">
		<td align="center">&nbsp;</td>
		<td>&nbsp;</td>
		<td align="center">Login:</td>
		<td>&nbsp;</td>
		<td align="center">Seen:</td>
		<td>&nbsp;</td>
		<td align="center">Register:</td>
		<td>&nbsp;</td>
		</tr>

<?php
			$i = 0;
			$x = 0;
		echo "<form name='form' action='admin.php' method='POST'><input type='hidden' name='action' value='deleteold' /><input type='hidden' name='seen' value='".substr($seen, 0, 4)."-".substr($seen, 4, 2)."-".substr($seen, 6, 2)." ".substr($seen, 8, 2).":".substr($seen, 10, 2).":".substr($seen, 12, 2)."' />";
			while ($t = $db->fetchrow()) {
				if (($t['login'] == 'robot') || ($t['login'] == 'zoltrax') || ($t['login'] == 'admin'))
				{
					echo "";
				}
				else
				{
				$x++;
				if (!($i++ % 2)) $class = ' id="div"'; else $class = '';
				if ($t['seen']) $t['seen'] = substr($t['seen'], 0, 4)."-".substr($t['seen'], 4, 2)."-".substr($t['seen'], 6, 2)." ".substr($t['seen'], 8, 2).":".substr($t['seen'], 10, 2).":".substr($t['seen'], 12, 2);

?>		<tr height="24" valign="middle"<?php echo $class; ?>>
		<td><form ><input class="icon" type="checkbox"'.($checkall ? '' : ' checked').' name="checkbox[]" value="<?php echo $t['login']; ?>"></input></td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><a href="whois.php?name=<?php echo $t['login']; ?>"><?php echo $t['login']; ?></a></td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $t['seen']; ?></td>
		<td>&nbsp; &nbsp;</td>
		<td align="center"><?php echo $t['registered']; ?></td>
		<td>&nbsp; &nbsp;</td>
<?php
				}
			}
echo "\t<script>\n\t<!--\n\tfunction deleteselected()\n\t{\n\t\t
if (confirm('${Lang['AreYouSure?']}')) form.submit();\n\t}\n\t//-->\n\t</script>\n";

echo "\t<br /><a href=\"${_SERVER['REQUEST_URI']}&checkall=1\" onclick=\"setcheckboxes('form', 'checkbox[]', true); return false;\">${Lang['SelectAll']}</a> &nbsp;/&nbsp; <a href=\"${_SERVER['REQUEST_URI']}&checkall=0\" onclick=\"setcheckboxes('form', 'checkbox[]', false); return false;\">${Lang['UnselectAll']}</a><br />\n";
echo "\t<br /><a class=\"delete\" href=\"javascript:deleteselected()\">${Lang['DeleteSelected']}&nbsp;&gt;&gt;</a><br />\n
Wpisz haslo do bazy: <input type='password' name='pass' value=''/>";
?>
		</form>
		</table>
		<br />
<?php
		}
		
$n = $page;

$s = '';
if ($n) $s .= "<a href=\"admin.php?view=oldseen&activity=$activity&page=" . ($n - 1) . "\">";
$s .=  "&lt;&lt; ${Lang['Previous']}";
if ($n) $s .= "</a>";

$s .= ' &nbsp; ';

$a = $n > 5 ? $n - 5 : 0;
$b = $n < $m - 5 ? $n + 5 : $m;

for ($i = $a; $i <= $b; $i++) {
	if ($i != $n) $s .= "<a href=\"admin.php?view=oldseen&activity=$activity&page=$i\">";
	$s .= $i + 1;
	if ($i != $n) $s .= "</a>";
	$s .= ' ';
}

$s .= '&nbsp; ';

if ($n < $m) $s .= "<a href=\"admin.php?view=oldseen&activity=$activity&page=" . ($n + 1) . "\">";
$s .= "${Lang['Next']} &gt;&gt;";
if ($n < $m) $s .= "</a>";

	$db->query("SELECT `login` FROM `${prefix}users` WHERE (`seen`<=$seen && `seen`!='' && !`dad`)");
	while ($db->fetchrow()) {
				$x2++;
			}
if ($x2 == 0) tableend("<a href=admin.php>$Lang[GoBack] &gt;&gt;</a>");
$X = @$x + ($n * $pagecount);
if ($x2 == 1) tableend("$s&nbsp;&nbsp;&nbsp;$X/$x2 soba]&nbsp;&nbsp;&nbsp;<a href=admin.php>$Lang[GoBack] &gt;&gt;</a>");
if (($x2 >= 2) && ($x2 <= 4)) tableend("$s&nbsp;&nbsp;&nbsp;$X/$x2 osoby&nbsp;&nbsp;&nbsp;<a href=admin.php>$Lang[GoBack] &gt;&gt;</a>");
if ($x2 >= 5) tableend("$s&nbsp;&nbsp;&nbsp;$X/$x2 osob&nbsp;&nbsp;&nbsp;<a href=admin.php>$Lang[GoBack] &gt;&gt;</a>");
}

// ===========================================================================
// SEND MAIL
// ===========================================================================

elseif (($view == 'compose') && ($User['usergroup'] == 'wheel'))
{
	require('include/editor.php');

	echo "\t<form action=\"admin.php\" method=\"POST\" name=\"form\"><input type=\"hidden\" name=\"action\" value=\"sendmail\" />\n";

	tablebegin($Lang['Messages'], 500);

	echo "\t<br /><font class=\"h3\">${Lang['ComposeMessage']}</font><br />\n";
?>	<table id="form" align="center" cellspacing="0" cellpadding="0" border="0">
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><b><?php echo $Lang['To:']; ?></b></td>
	<td>&nbsp;</td>
	<td><input type="text" size="30" maxlength="512" name="to"/></td>
	</tr>
	<tr>
	<td><b><?php echo $Lang['Subject:']; ?></b></td>
	<td>&nbsp;</td>
	<td><input type="text" size="60" maxlength="240" name="subject"/></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td><b><?php echo $Lang['Message:']; ?></b></td>
	<td>&nbsp;</td>
	<td>
<?php
	editor('message', 72, 20, 'left');
?>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td colspan="3">
		<center><input type="submit" value="<?php echo $Lang['Send']; ?>" /></center>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	</table>
<?php
	tableend('<a href="admin.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
?>
	</form>

	<script>
	<!--
		document.form.<?php echo $to ? $subject ? 'message' : 'subject' : 'to'; ?>.focus();
	//-->
	</script>
<?php
}

// ===========================================================================
// CLAN LIST
// ===========================================================================

elseif (($view == 'clanlist') && ($User['usergroup'] == 'wheel')) {
	tablebegin("Kasowanie klanow");
	if (!$name)
	{

	$Groups = '';
	$db->query("SELECT * FROM `${prefix}groups` ORDER BY `score` DESC, `level` DESC, `name` ASC;");
	$max = $db->numrows();
?>
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		Klany
		<tr id="header">
		<td align="center">&nbsp;</td>
		<td>&nbsp;</td>
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
		</tr><form name='form' action='admin.php' method='POST'><input type='hidden' name='action' value='deleteclan'/>

<?php
	while ($t = $db->fetchrow()) {
		echo "<tr><td><input class=\"icon\" type=\"checkbox\" name=\"checkbox[]\" value=".$t['id']."></input></td>&nbsp;<td></td><td><a href='admin.php?view=clanlist&name=".$t['name']."'>".$t['name']."</a></td><td>&nbsp;</td><td>".$t['created']."</td><td>&nbsp;</td><td>".$t['owner']."</td><td>&nbsp;</td><td>".$t['co1']."</td><td>&nbsp;</td><td>".$t['co2']."</td><td>&nbsp;</td><td>".strdiv($t['credits'])."</td><td>&nbsp;</td><td>".strdiv($t['crystals'])."</td><td>&nbsp;</td><td>".strdiv($t['score'])."</td><td>&nbsp;</td><td>".$t['level']."</td><td>&nbsp;</td><td>".$t['tax']."</td></tr>";
	}
	echo "\t<script>\n\t<!--\n\tfunction deleteselected()\n\t{\n\t\t
	if (confirm('${Lang['AreYouSure?']}')) form.submit();\n\t}\n\t//-->\n\t</script>\n";
	echo "</table><br>Wpisz haslo do bazy: <input type='password' name='pass' value=''/>&nbsp;&nbsp;<a class=\"delete\" href=\"javascript:deleteselected()\">${Lang['DeleteSelected']}&nbsp;&gt;&gt;</a><br /></form>";
	tableend('<a href="admin.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
	}

	else

	{
	$db->query("SELECT * FROM `${prefix}users` WHERE clan='$name';");
	$users = $db->numrows();
	echo "<br>Ilosc osob w klanie '<font class='minus'>$name</font>': $users<br><br>";
	tableend('<a href="admin.php?view=clanlist">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
	}

}

// ===========================================================================
// ADMINISTRATION
// ===========================================================================

else {
	tablebegin(strcap($Lang['MenuAdministration']));

	echo style_linkbox('admin.php?view=chat', $Lang['Chat']);
	echo style_linkbox('highscores.php?category=login', $Lang['Users']);
	echo style_linkbox('admin.php?view=groups', $Lang['Ranks']);
	echo style_linkbox('admin.php?view=ban', $Lang['BanUser']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=lock', $Lang['LockAccount']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=changepassword', $Lang['ChangePassword']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=teleport', $Lang['Teleport']);
	echo style_linkbox('admin.php?view=locked', $Lang['LockedList']);
	echo style_linkbox('admin.php?view=items', $Lang['Items']);
	echo style_linkbox('admin.php?view=markets', $Lang['Markets']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('phpmyadmin', $Lang['Database']);

	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=user', $Lang['ChangeName']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=colony', $Lang['ChangeColony']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=delete', $Lang['DeleteUser']);
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=clanlist', "Lista klanow");
	if ($User['usergroup'] == 'wheel') echo style_linkbox('admin.php?view=compose', "Wyslanie maila");
	if ($User['usergroup'] == 'wheel')
	echo "<br>Niekatywne konta (2 > miesiecy): <form name='form2' action='admin.php?view=oldseen' method='POST'><input type='text' name='activity' value='5'/>&nbsp;&nbsp;<a href='javascript:document.form2.submit();'>Pokaz</a></form><br>";

	echo BR;

	tableend(strcap($Lang['MenuAdministration']));
}

require('include/footer.php');
