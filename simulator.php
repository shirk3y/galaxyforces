<?php

// ===========================================================================
// Simulator {simulator.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	0.1
//	Created:	2005-10-15
//	Modified:	2005-10-15
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project (http://galaxy.game-host.org).
// ===========================================================================

$index = 'simulator';
$auth = true;
$js[] = 'functions';

require('include/header.php');

locale('battle');

/*

$view = getvar('view');
$back = getvar('back');
$checkall = getvar('checkall');
$category = getvar('category');
$page = getpvar('page');

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
			$sql = "UPDATE {$prefix}groups SET co1='".$db->safe($co1)."',co2='".$db->safe($co2)."'";
			if ($Player['login'] == $Group['owner'] || checkplace('clanhall')) $sql .= ",tax='".$db->safe($tax)."'";
			if ($Player['login'] == $Group['owner']) {
				if ($owner) $sql .= ",owner='".$db->safe($owner)."'";
				$sql .= ",description='".$db->safe($description)."'";
				$sql .= ",www='".$db->safe($www)."'";
			}	
			if ($db->query($sql." WHERE name='".$Group['name']."';")) {
				$result .= $Lang['UpdateComplete'].'<br />';
				$db->query("INSERT INTO {$prefix}clanmessages (`type`,`time`,`clan`,`from`,`to`) VALUES ('statuschange','$stardate','${Group['name']}','$login','');");
			}
			else $errors .= $Lang['ErrorQueryFailed'].'!<br />';
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

*/

$back = $back ? $back : ($view ? 'simulator.php' : 'colony.php');

switch ($view) {
	case 'list': $title = ': '.$Lang['Members']; break;
	default: $title = '';
}
$pagename = $Lang['Clan'].$title;

switch (@$category) {
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
	tableend('<a href="simulator.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>');
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br />";
	tableend('<a href="simulator.php">'.$Lang['GoBack'].'&nbsp;&gt;&gt;</a>');
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
// DEFAULT
// ===========================================================================

elseif ($Colony['tron'] > 1 || $Colony['militarytechnology']) {
	tablebegin($Lang['Simulator']);

	echo '<h3>'.$Lang['SimulatorTitle'].'</h3>';
	
	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

	tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Clan']);
}

// ===========================================================================
// NOT AVAILABLE
// ===========================================================================

else {
	tablebegin($Lang['Simulator']);

	echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

	tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Clan']);
}

require('include/footer.php');
