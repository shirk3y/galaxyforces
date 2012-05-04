<?php

// -------------------------------------------------------------------
// Change clan avatar
// -------------------------------------------------------------------

function actionchangeclanavatar()
{
	global $login, $db, $prefix, $Group, $Player;

	if ($Group && $Player['privileged']) {
		$url = getvar('url');
		if ($url && strpos('-' . $url, 'http://') != 1) $url = 'http://' . $url;
		if (ereg(";' \"", $url)) $url = '';
		$ext = substr(strrchr($url, '.'), 1);
		if (($ext == 'jpg') || ($ext == 'gif') || ($ext == 'png') || ($ext == 'jpeg') || (! $url)) {
			$db->query("UPDATE `{$prefix}groups` SET `avatar` = '$url' WHERE `id` = '${Group['id']}' LIMIT 1;");
			$Group['avatar'] = $url;
		}
	}
}

// -------------------------------------------------------------------
// Request joining to the group
// -------------------------------------------------------------------

function actionjoin() {
	global $db, $prefix, $login, $name, $Player, $stardate, $errors, $result, $Lang, $places;

	if (checkplace('clanhall')) $ratio = $places['clanhall']['parameters'];
	else $ratio = 10;

	if (! $Player['clan']) {
		$result = Rand(220, 350) * $ratio;
		if ($Player['credits'] < $result) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
		else {
			$db->query("SELECT `id` FROM `${prefix}groups` WHERE `name`='$name' LIMIT 1;");
			if ($db->numrows()) {
				$db->query("DELETE FROM `${prefix}clanmessages` WHERE `from`='$login' AND `type`='join' AND `clan`='$name';");
				$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`) VALUES ('join','$stardate','$name','$login');");
				$db->query("UPDATE `${prefix}users` SET `credits`=`credits`-'$result' WHERE `id`='${Player['id']}';");
				$Player['credits'] -= $result;
			}
		}
		$result = "${Lang['ReqSt']}: <b>${name}</b>.<br /><br /><b>${Lang['Credits']}</b>: $result [!]<br />";
	}
	else $errors .= $Lang['ErrorCantJoin'] . '<br />';
}

// -------------------------------------------------------------------
// Leave the group
// -------------------------------------------------------------------

function actionleave() {
	global $db, $prefix, $login, $Group, $stardate, $errors, $result, $Lang, $secret, $Player;

	if ($Group && (($confirm = getvar('confirm')) == $secret)) {
		if ($Group['owner'] == $login) {
			$db->query("INSERT INTO `${prefix}chat` (`from`,`message`) VALUES ('<font color=\"yellow\">$login</font>', '<font class=\"capacity\"><i>disbanded: <b>${Group['name']}</b></i></font>...');");
			$db->query("UPDATE `${prefix}users` SET `clan`='' WHERE `clan`='${Group['name']}';");
			$db->query("DELETE FROM `${prefix}clanmessages` WHERE `clan`='${Group['name']}';");
			$db->query("DELETE FROM `${prefix}groups` WHERE `name`='${Group['name']}';");
			$result = "${Lang['DisSt']}: <b>${Group['name']}</b>.<br />";
		}
		else {
			if ($Group['co1'] == $login) $db->query("UPDATE `${prefix}groups` SET `co1`='' WHERE `id`='${Group['id']}';");
			elseif ($Group['co2'] == $login) $db->query("UPDATE `${prefix}groups` SET `co2`='' WHERE `id`='${Group['id']}';");
			$db->query("UPDATE `${prefix}users` SET `clan`='' WHERE `login`='$login';");
			$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`) VALUES ('leave','$stardate','${Group['name']}','$login');");
			$result = "${Lang['LeaveSt']}: <b>${Group['name']}</b>.<br />";
		}
		$Player['clan'] = '';
		$Group = '';
	}
	else $errors .= $Lang['ErrorProblems'] . '<br />';
}

// -------------------------------------------------------------------
// Admit joining to the group
// -------------------------------------------------------------------

function actionadmit() {
	global $db, $prefix, $login, $name, $Player, $Group, $stardate;

	if ($Group && $Player['privileged']) {
		$db->query("SELECT `clan`,`credits`,`bank` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
		if (($p = $db->fetchrow()) && ! $p['clan']) {
			$db->query("SELECT `from` FROM `${prefix}clanmessages` WHERE `type`='join' AND `from`='$name' AND `clan`='${Group['name']}' LIMIT 1;");
			if ($db->numrows()) {
				$credits = floor($p['credits'] * $Group['tax'] / 100);
				$bank = floor($p['bank'] * $Group['tax'] / 100);
				$Group['credits'] += $payment = $credits + $bank;
				$db->query("DELETE FROM `${prefix}clanmessages` WHERE `from`='$name' AND `type`='join';");
				$db->query("UPDATE `${prefix}groups` set `credits`=`credits`+'$payment' WHERE `id`='${Group['id']}';");
				$db->query("UPDATE `${prefix}users` set `clan`='${Group['name']}',`credits`=`credits`-'$credits',`bank`=`bank`-'$bank' WHERE `login`='$name';");
				$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`) VALUES ('admit','$stardate','${Group['name']}','$login','$name');");
			}
		}
	}
}

// -------------------------------------------------------------------
// Reject from group
// -------------------------------------------------------------------

function actionreject() {
	global $db, $prefix, $login, $name, $Player, $Group, $stardate, $secret;

	if ($Group && $Player['privileged'] && (($confirm = getvar('confirm')) == $secret)) {
		$db->query("SELECT `clan` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
		if (($p = $db->fetchrow()) && ($p['clan'] == $Group['name']) && ($name != $Group['owner'])) {
			$db->query("UPDATE `${prefix}users` set `clan`='' WHERE `login`='$name';");
			$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`) VALUES ('reject','$stardate','${Group['name']}','$login','$name');");
			if ($Group['co1'] == $name) {
				$db->query("UPDATE `${prefix}groups` SET `co1`='' WHERE `name`='${Group['name']}';");
				$Group['co1'] = '';
			}
			elseif ($Group['co2'] == $name) {
				$db->query("UPDATE `${prefix}groups` SET `co2`='' WHERE `name`='${Group['name']}';");
				$Group['co2'] = '';
			}
		}
	}
}

// -------------------------------------------------------------------
// Send an invitation to the group
// -------------------------------------------------------------------

function actioninvitate()
{
	global $db, $prefix, $login, $name, $Player, $Group, $Lang, $result, $errors;

	$db->query("SELECT login,clan,language,antispam FROM ${prefix}users WHERE login='$name';");

	if (($t = $db->fetchrow()) && !$t['clan']) {
		if ($t['antispam']) {
			$errors .= $Lang['SpamBlocked'].'!<br />';
			return;
		}
		
		$Prev = locale('galaxy', $t['language']);

		$msg = "<a href=\"whois.php?name=${Player['login']}\">${Player['login']}</a> {$Lang['msg.ivitation.1']} <font class=\"result\">${Group['name']}</font>.<br /><br /><b>${Lang['msg.ivitation.3']}</b>:<br /><br />";
		$msg .= "<b>${Lang['Level']}</b>: <font class=\"plus\">${Group['level']}</font><br /><b>${Lang['Score']}</b>: <font class=\"result\">" . div($Group['score']) . "</font><br />";
		$msg .= "<b>${Lang['Tax']}</b>: <font class=\"minus\">${Group['tax']}%</font><br />${Lang['Attack']}</b>: <font class=\"plus\">" . div($Group['attack']) . "</font><br /><b>${Lang['Defense']}</b>: <font class=\"capacity\">" . div($Group['defense']) . "</font><br />";
		$msg .= "<br />${Lang['msg.ivitation.2']}: <a href=\"description.php?subject=clanhall\">${Lang['ClanHall']}</a><br /><br />";
		$msg .= "<a href=\"control.php?action=join&name=${Group['name']}\">${Lang['JoinReq']} &gt;&gt;</a><br />";

		sendmessage($Lang['subject.ivitation'] . ": ${Group['name']}", $msg, $Player['login'], $name);

		$result = "${Lang['InvS']}: <b>$name</b>.<br />";

		$Lang = $Prev;
	}
}

// -------------------------------------------------------------------
// Planet recultivation
// -------------------------------------------------------------------

function actionrecultivation()
{
	global $login, $db, $prefix, $stardate, $Group, $Player, $result, $errors, $Lang;

	if ($Group && $Player['privileged'] && ($credits = abs(getvar('credits'))) && ($name = escapesql(getvar('name')))) {
		if ($Group['credits'] < $credits || $credits < 100000) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
		elseif ($db->query("SELECT * FROM `${prefix}space` WHERE `name`='$name' AND `type`='planet';") && ($t = $db->fetchrow())) {
			$n = round($credits / 100000);
			$r = 0;
			for ($i = 0; ($i < $n) && ($r < $t['explored']); $i++) {
				switch ($t['class']) {
					case 'small': $c = Rand(81, 100); break;
					case 'medium': $c = Rand(61, 80); break;
					case 'big': $c = Rand(41, 60); break;
					case 'huge': $c = Rand(21, 40); break;
					case 'giant': $c = Rand(1, 20); break;
				}
				$r += $c * Rand(1, 100) / 1000;
			}
			if ($r > $t['explored']) $r = $t['explored'];
			if ($i < $n) $n = $i;
			$score = round((1 / $c) * $t['explored'] * $r * (10 + $Group['level']));
			$cost = $n * 100000;
			$db->query("UPDATE `${prefix}space` SET `explored`=`explored`-'$r' WHERE `id`='${t['id']}';");
			$db->query("UPDATE `${prefix}groups` SET `credits`=`credits`-'$cost',`score`=`score`+'$score' WHERE `id`='${Group['id']}';");
			$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`,`credits`) VALUES ('recultivation','$stardate','${Group['name']}','$login','${t['name']}','$score');");

			$a = number_format($r, 2, $Lang['DecPoint'], ' ');
			$c = number_format($cost, 0, '', ' ');
			$s = number_format($score, 0, '', ' ');
			$result = "${Lang['Reclt1']} <font class=\"plus\">${t['name']}</font> ${Lang['Reclt2']} <font class=\"capacity\">$a %</font>.<br /><br /><b>${Lang['Credits']}</b>: $c [!]<br /><b>${Lang['ScoreGained']}</b>: $s.<br />";
		}
	}
}

// -------------------------------------------------------------------
// Found clan
// -------------------------------------------------------------------

function actionfoundclan() {
	global $db, $prefix, $login, $name, $Player, $Group, $errors, $result, $Lang, $places;

	if (checkplace('clanhall') && ! $Player['clan'] && (($tax = abs(getvar('tax'))) > 0) && $name) {
		$description = strip_tags(escapesql(@$_POST['description']));
		$www = strip_tags(escapesql(@$_POST['www']));
		$ratio = $places['clanhall']['parameters'];
		if ($ratio < 1) $ratio = 1;
		$credits = 1000000 * $ratio;
		if ($Player['credits'] < $credits) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
		elseif ($Player['level'] < 10) $errors .= $Lang['Error2LL'] . '<br />';
		else {
			$db->query("SELECT `id` FROM `${prefix}groups` WHERE `name`='$name';");
			if ($db->numrows()) $errors .= $Lang['ErrorCNE'] . '<br />';
			else {
				$Player['credits'] -= $credits;
				$Player['clan'] = $name;
				$Group = array('name' => $name, 'owner' => $login);
				$db->query("INSERT INTO `${prefix}groups` (`name`,`created`,`description`,`owner`,`tax`,`www`) VALUES ('$name',NOW(),'$description','$login','$tax','$www');");
				$db->query("UPDATE `${prefix}users` SET `credits`=${Player['credits']},`clan`='$name' WHERE `id`='${Player['id']}';");
				$db->query("INSERT INTO `${prefix}chat` (`from`,`message`) VALUES ('<font color=\"yellow\">$login</font>', '<font class=\"capacity\"><i>found: <b>${Group['name']}</b></i></font>...');");
			}
		}
		$result = "${Lang['ClanCreated']}: <b>${name}</b>.<br /><br /><b>${Lang['Credits']}: <font class=\"minus\">" . div($credits) . '</font> [!]</b><br />';
	}
	else $errors .= $Lang['ErrorCCF'] . '<br />';
}

// -------------------------------------------------------------------
// Clan donation
// -------------------------------------------------------------------

function actionclandonation()
{
	global $login, $db, $prefix, $stardate, $Colony, $Player, $Group, $Lang, $errors, $result;

	if ($name = escapesql(getvar('name'))) {
		$db->query("SELECT * FROM `${prefix}groups` WHERE `name`='$name';");
		if ($t = $db->fetchrow()) {
			if (($credits = abs(getvar('credits'))) > $Player['credits']) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
			if (($crystals = abs(getvar('crystals'))) && (! $Colony || ($crystals > $Colony['crystals']))) $errors .= $Lang['ErrorNotEnoughResources'] . '<br />';
		}
		else $errors .= $Lang['ErrorClanNotExists'] . '<br />';

		if (! $errors && ($credits || $crystals)) {
			if ($credits) {
				$Player['credits'] -= $credits;
				$db->query("UPDATE `${prefix}users` SET `credits`=`credits`-'$credits' WHERE `id`='${Player['id']}';");
				$db->query("UPDATE `${prefix}groups` SET `credits`=`credits`+'$credits' WHERE `id`='${t['id']}';");
			}
			if ($crystals) {
				$Colony['crystals'] -= $crystals;
				$db->query("UPDATE `${prefix}colonies` SET `crystals`=`crystals`-'$crystals' WHERE `id`='${Colony['id']}';");
				$db->query("UPDATE `${prefix}groups` SET `crystals`=`crystals`+'$crystals' WHERE `id`='${t['id']}';");
			}
			if ($Group && ($Group['name'] == $t['name'])) {
				$Group['credits'] += $credits;
				$Group['crystals'] += $crystals;
			}
			$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`credits`,`crystals`) VALUES ('donate','$stardate','${t['name']}','${Player['login']}','$credits','$crystals');");
			$result .= $Lang['ClanDonated'] . '<br />';
		}
	}
}

// -------------------------------------------------------------------
// User donation
// -------------------------------------------------------------------

function actionuserdonation()
{
	global $login, $db, $prefix, $stardate, $Player, $Group, $Lang, $errors, $result, $name;

	if ($name && $Group && $Player['privileged']) {
		$credits = abs(getvar('credits'));
		$crystals = abs(getvar('crystals'));

		if ($crystals || $credits) {
			if ($Group['crystals'] < $crystals || $Group['credits'] < $credits) $errors .= $Lang['ErrorNotEnoughResources'] . '<br />';
			else {
				if ($crystals) {
					$db->query("SELECT `id`,`crystals` FROM `${prefix}colonies` WHERE `owner`='$name';");
					if ($t = $db->fetchrow()) {
						$t['crystals'] += $crystals;
						$Group['crystals'] -= $crystals;
						$db->query("UPDATE `${prefix}colonies` SET `crystals`='${t['crystals']}' WHERE `id`='${t['id']}';");
						$db->query("UPDATE `${prefix}groups` SET `crystals`='${Group['crystals']}' WHERE `id`='${Group['id']}';");
					}
					else $errors .= $Lang['ErrorColonyNotExists'] . '<br />';
				}

				if (! $errors && $credits) {
					$db->query("SELECT `id`,`credits` FROM `${prefix}users` WHERE `login`='$name';");
					if ($t = $db->fetchrow()) {
						$t['credits'] += $credits;
						$Group['credits'] -= $credits;
						$db->query("UPDATE `${prefix}users` SET `credits`='${t['credits']}' WHERE `id`='${t['id']}';");
						$db->query("UPDATE `${prefix}groups` SET `credits`='${Group['credits']}' WHERE `id`='${Group['id']}';");
					}
					else $errors .= $Lang['ErrorLoginNotExists'] . '<br />';
				}

				if (! $errors) {
					$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`,`credits`,`crystals`) VALUES ('donate','$stardate','${Group['name']}','$login','$name','$credits','$crystals');");
					$result .= $Lang['UserDonated'] . '<br />';
				}
			}
		}
	}
}

// -------------------------------------------------------------------
// Add to council
// -------------------------------------------------------------------

function actionaddtocouncil() {
	global $db, $prefix, $login, $name, $Player, $Group, $stardate, $secret, $Lang, $errors;

	if ($Group && $Player['privileged'] && (($confirm = getvar('confirm')) == $secret)) {
		if ($Group['co1'] && $Group['co2']) $errors .= "${Lang['ErrorC2']}<br />";
		else {
			$db->query("SELECT `clan` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
			if (($p = $db->fetchrow()) && ($p['clan'] == $Group['name'])) {
				if (! $Group['co1']) {
					$Group['co1'] = $name;
					$db->query("UPDATE `${prefix}groups` set `co1`='$name' WHERE `name`='${Group['name']}';");
				}
				else {
					$Group['co2'] = $name;
					$db->query("UPDATE `${prefix}groups` set `co2`='$name' WHERE `name`='${Group['name']}';");
				}
				$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`) VALUES ('counciladmit','$stardate','${Group['name']}','$login','$name');");
			}
		}
	}
}

// -------------------------------------------------------------------
// Reject from council
// -------------------------------------------------------------------

function actionrejectfromcouncil() {
	global $db, $prefix, $login, $name, $Player, $Group, $stardate, $secret, $Lang, $errors;

	if ($Group && $Player['privileged'] && (($confirm = getvar('confirm')) == $secret)) {
		$db->query("SELECT `clan` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
		if (($p = $db->fetchrow()) && ($p['clan'] == $Group['name']) && ($Group['co1'] == $name || $Group['co2'] == $name)) {
			if ($Group['co1'] == $name) {
				$Group['co1'] = '';
				$db->query("UPDATE `${prefix}groups` set `co1`='' WHERE `name`='${Group['name']}';");
			}
			else {
				$Group['co2'] = '';
				$db->query("UPDATE `${prefix}groups` set `co2`='' WHERE `name`='${Group['name']}';");
			}
			$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`) VALUES ('councildismiss','$stardate','${Group['name']}','$login','$name');");
		}
	}
}

// -------------------------------------------------------------------
// Change clan owner
// -------------------------------------------------------------------

function actionchangeclanowner() {
	global $db, $prefix, $login, $name, $Player, $Group, $stardate, $secret, $Lang, $errors;

	if ($Group && $login == $Group['owner'] && (($confirm = getvar('confirm')) == $secret)) {
		$db->query("SELECT `clan` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
		if (($p = $db->fetchrow()) && ($p['clan'] == $Group['name']) && ($Group['co1'] == $name || $Group['co2'] == $name)) {
			if ($Group['co1'] == $name) {
				$Group['co1'] = $login;
				$Group['owner'] = $name;
			}
			else {
				$Group['co2'] = $login;
				$Group['owner'] = $name;
			}
			$db->query("UPDATE `${prefix}groups` set `owner`='${Group['owner']}',`co1`='${Group['co1']}',`co2`='${Group['co2']}' WHERE `name`='${Group['name']}';");
			$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`) VALUES ('ownerchange','$stardate','${Group['name']}','$login','$name');");
		}
	}
}

// -------------------------------------------------------------------
// Clan administration
// -------------------------------------------------------------------

function actionclanadmin()
{
	global $login, $db, $prefix, $stardate, $Player, $Group, $Lang, $errors, $result, $owner, $co1, $co2, $tax, $description;
	
	$db->query("SELECT `name` FROM `${prefix}groups` WHERE `owner`='$owner';");
	if ($t = $db->fetchrow()) {
		$clanname = $t['name'];
	}
	$db->query("SELECT `name` FROM `${prefix}groups` WHERE `co1`='$co1';");
	if ($t = $db->fetchrow()) {
		$clanname = $t['name'];
	}
	$db->query("SELECT `name` FROM `${prefix}groups` WHERE `co2`='$co2';");
	if ($t = $db->fetchrow()) {
		$clanname = $t['name'];
	}

	if ($clanname && $Player['privileged']) {

		if ($owner || $co1 || $co2 || $tax || $description) {
				if (! $errors) {
					$db->query("SELECT `login` FROM `${prefix}users` WHERE `login`='$owner';");
					if ($t = $db->fetchrow()) {
						if ($t['login'] == $owner) $db->query("UPDATE `${prefix}groups` SET `owner` = '$owner' WHERE `name`='$clanname';");
					}
						else $errors .= $Lang['ErrorLoginNotExists'] . '<br />';

					$db->query("SELECT `login` FROM `${prefix}users` WHERE `login`='$co1';");
					if ($t = $db->fetchrow()) {
						if ($t['login'] == $co1) $db->query("UPDATE `${prefix}groups` SET `co1` = '$co1' WHERE `name`='$clanname';");
					}
						else $errors .= $Lang['ErrorLoginNotExists'] . '<br />';

					$db->query("SELECT `login` FROM `${prefix}users` WHERE `login`='$co2';");
					if ($t = $db->fetchrow()) {
						if ($t['login'] == $co2) $db->query("UPDATE `${prefix}groups` SET `co2` = '$co2' WHERE `name`='$clanname';");
					}
						else $errors .= $Lang['ErrorLoginNotExists'] . '<br />';

					if ($tax >= 95) $tax = 95;
					$description = strip_tags($description);
					$db->query("UPDATE `${prefix}groups` SET `tax` = '$tax', `description` = '$description' WHERE `name`='$clanname';");

				$result .= $Lang['ChangeClan'] . '<br />';
				}

//					if (! $errors) {
//						$db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`,`credits`,`crystals`) VALUES ('donate','$stardate','${Group['name']}','$login','$name','$credits','$crystals');");
//						$result .= $Lang['UserDonated'] . '<br />';
//					}
		}
	}
}
