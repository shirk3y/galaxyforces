<?php

// ===========================================================================
// Finger {whois.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.6
//	Modified:	2005-11-12
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

$js[] = 'functions';
$js[] = 'e107';

require('include/header.php');

//locale('admin');
locale('groups');

$view = getvar('view');
$category = getvar('category');

$dl = 60 * 60 * 24;

// ===========================================================================
// COLONY
// ===========================================================================

if ($view == 'colony') {
	$db->query("SELECT * FROM `${prefix}colonies` WHERE `name`='$name' LIMIT 1;");
	if ($t = $db->fetchrow()) {
		tablebegin("${Lang['Colony']}: <b>${t['name']}</b>");

		subbegin();

		echo "\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t<tr valign=\"top\">\n";
		echo "\t<td align=\"left\">\n";

		echo "\t<b>${Lang['Name']}</b>: <font class=\"capacity\">${t['name']}</font><br />\n";
		echo "\t<b>${Lang['Owner']}</b>: <a href=\"whois.php?rid=$rid&name=${t['owner']}\">${t['owner']}</a><br />\n";
		echo "\t<b>${Lang['Planet']}</b>: <a class=\"minus\" href=\"galaxy.php?rid=$rid&object=${t['planet']}\">".strcap($t['planet'])."</a><br /><br />\n";
		if ($t['description']) echo "\t<b>${Lang['Description']}</b>: <font class=\"result\">".emoticons($t['description'])."</font><br />\n";

		echo "\t</td><td>&nbsp;</td>\n";

		echo "\t<td width=\"168\" align=\"right\">\n";

		$avatar = $t['avatar'] ? $t['avatar'] : 'gallery/avatars/noavatar.gif';
		tableimg('images/bw.gif', 168, 168, $avatar, 160, 160, '', 'right');

		echo "\t</td>\n\t</tr>\n\t</table>\n";

		subend();
		tablebreak();

		echo "\t<br /><a href=\"attack.php?rid=$rid&name=${t['name']}\">${Lang['Attack']}&nbsp;&gt;&gt;</a><br />\n";
		echo "\t<br />\n";

		tableend("<a href=\"whois.php?name=$name&rid=$rid\">${Lang['GoBack']}&nbsp;&gt;&gt</a>");
	}
	else {
		echo "fak";
	}
}

// ===========================================================================
// USER
// ===========================================================================

elseif ($name) {
	tablebegin("Finger: <b>$name</b>");

	$db->query("SELECT * FROM {$prefix}users WHERE login='$name';");

	if ($t = $db->fetchrow()) {

		$days = Round((time() - mktime(0, 0, 0, substr($t['seen'], 4, 2) ,substr($t['seen'], 6, 2), substr($t['seen'], 0, 4))) / $dl);
		switch ($days) {
			case 0: $lastseen = $Lang['Today']; break;
			case 1: $lastseen =  $Lang['day'].' '.$Lang['ago']; break;
			default: $lastseen =  $days.' '.$Lang['days'].' '.$Lang['ago'];
		}

		$days = Round((time() - mktime(0, 0, 0, substr($t['registered'], 5, 2) ,substr($t['registered'], 8, 2), substr($t['registered'], 0, 4))) / $dl);
		if ($days < 21) $age = $Lang['Age[]'][0];
		elseif ($days < 60) $age = $Lang['Age[]'][1];
		elseif ($days < 120) $age = $Lang['Age[]'][2];
		elseif ($days < 200) $age = $Lang['Age[]'][3];
		elseif ($days < 300) $age = $Lang['Age[]'][4];
		else $age = $Lang['Age[]'][5];

		$db->query("SELECT `name` FROM `${prefix}colonies` WHERE `owner` = '$name' LIMIT 1");
		$colony = ($c = $db->fetchrow()) ? $c['name'] : '';

		subbegin();

		echo "\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t<tr valign=\"top\">\n";
		echo "\t<td align=\"left\">\n";

		if (($t['locked']) && ($t['locked'] >= date('YmdHis'))) echo "<b><font class='error'>KONTO ZABLOKOWANE PRZEZ $t[who] do " . timestampdate($t['locked']) . " " . timestamptime($t['locked'])." z powodu: $t[reason].</font></b><br>";
		if (($t['banned']) && ($t['banned'] >= date('YmdHis'))) echo "<b><font color='capility'>Uzytkownik nie moze wypowiadac sie na chacie do ". timestampdate($t['banned']) . " " . timestamptime($t['banned']) . ". Bana przyznal $t[who] za $t[reason].</font></b><br>";

		echo "\t<b>${Lang['Name']}</b>: <font class=\"plus\">${t['login']}</font><br />\n";
		echo "\t<b>${Lang['Rank']}</b>: <font class=\"result\">".$Lang['Groups[]'][$t['usergroup']].'</font>'.($User['usergroup'] == $Config['Administrators'] ? " &nbsp; <a href=\"admin.php?view=changegroup&name=${t['login']}\">${Lang['Change']}&nbsp;&gt;&gt;</a>" : '')."<br />\n";

		echo "\t<br />\n";

		echo "\t".($colony ? "<b>${Lang['Colony']}</b>: <font class=\"work\">$colony</font> &nbsp; <a href=\"whois.php?view=colony&name=$colony\">${Lang['More']}&nbsp;&gt;&gt;</a>" : '')."<br />\n";

		echo "\t";
		if ($t['clan'])
		{
			echo "<b>${Lang['Group']}</b>: <font class=\"capacity\">${t['clan']}</font>";
			$db->query("SELECT * FROM `${prefix}groups` WHERE `name`='$t[clan]'");
			while ($g = $db->fetchrow())
			{
				if ($g['owner'] == $t['login']) echo " <b>(${Lang['Owner']})</b>";
				if ($g['co1'] == $t['login']) echo " <b>(${Lang['Council']})</b>";
				if ($g['co2'] == $t['login']) echo " <b>(${Lang['Council']})</b>";
			}
		}
		echo "<br />\n";

		echo "\t<b>${Lang['Registration date']}</b>: <font class=\"result\">${t['registered']}</font><br />\n";
		echo "\t<b>${Lang['LastSeen']}</b>: <font class=\"work\">$lastseen</font><br />\n";

		echo "\t".($t['killed'] ? "<b>${Lang['Kills']}</b>: <font class=\"plus\">".div($t['killed'])."</font>" : '')."<br />\n";
		echo "\t<b>${Lang['Win%']}</b>: <font class=\"delete\">".($t['killed']+$t['killedby'] ? round(100*$t['killed']/($t['killed']+$t['killedby'])).'%' : $Lang['n/a'])."</font><br />\n";
		
		echo "\t".($t['www'] ? "<b>WWW</b>: <a target=\"_blank\" href=\"http://${t['www']}\">${t['www']}</a>" : '')."<br />\n";

		echo "\t</td><td>&nbsp;</td><td align=\"left\">\n";

		echo "\t<b>${Lang['Level']}</b>: <font class=\"plus\">${t['level']}</font><br />\n";
		echo "\t<b>${Lang['Score']}</b>: <font class=\"result\">".div($t['score'])."</font><br />\n";

		echo "\t<br />\n";

		echo "\t<b>${Lang['Age']}</b>: <font class=\"work\">$age</font><br />\n";
		echo "\t<b>${Lang['Reputation']}</b>: <font class=\"capacity\">".playernature($t['reputation'])."</font><br />\n";
		echo "\t<b>${Lang['Strength']}</b>: <font class=\"result\">".(floor(100*$t['strength'])/100)."</font><br />\n";
		echo "\t<b>${Lang['Agility']}</b>: <font class=\"work\">".(floor(100*$t['agility'])/100)."</font><br />\n";
		echo "\t".($t['lastkilled'] ? "<b>${Lang['LastKilled']}</b>: <a href=\"whois.php?name=${t['lastkilled']}\">${t['lastkilled']}</a>" : '')."<br />\n";
		echo "\t".($t['lastkilledby'] ? "<b>${Lang['LastKilledBy']}</b>: <a class=\"delete\" href=\"whois.php?name=${t['lastkilledby']}\">${t['lastkilledby']}</a>" : '')."<br />\n";
		echo "\t".($t['gg'] ? "<b>GG#</b>: <a href=\"gg:${t['gg']}\">${t['gg']}</a> <img src=\"http://www.gadu-gadu.pl/users/status.asp?id=${t['gg']}\" hspace=\"0\" vspace=\"0\" border=\"0\" width=\"12\" height=\"12\" />" : '')."<br />\n";

		echo "\t</td><td>&nbsp;</td>\n";

		echo "\t<td width=\"168\" align=\"right\">\n";

		$link = $login == $t['login'] ? 'javascript:avatar()' : '';
		$avatar = $t['avatar'] ? $t['avatar'] : 'gallery/avatars/noavatar.gif';
		tableimg('images/bw.gif', 168, 168, $avatar, 160, 160, $link, 'right');

		echo "\t</td>\n\t</tr>\n\t</table>\n";

		subend();
		tablebreak();

		echo "\t\t<br /><a href=\"messages.php?view=compose&rid=$rid&to=${t['login']}\">${Lang['SendMessage']}&nbsp;&gt;&gt</a><br />\n";

		if ($Player['planet'] == $t['planet']) echo style_linkbox("equipment.php?view=giveitems&name={$t['login']}", $Lang['GiveItems'], 'capacity');

		if ($Group && $Player['privileged']) {
			if (($t['clan'] == $Group['name']) && ($t['login'] != $login)) {
				if ($t['login'] == $Group['co1'] || $t['login'] == $Group['co2']) {
					if ($login == $Group['owner']) echo "\t\t<br /><a href=\"javascript:ask('clan.php?action=changeclanowner&name=${t['login']}&confirm=$secret')\">{$Lang['ChangeGroupOwner']}&nbsp;&gt;&gt</a><br />\n";
					echo "\t\t<br /><a class=\"delete\" href=\"javascript:ask('clan.php?action=rejectfromcouncil&name=${t['login']}&confirm=$secret')\">{$Lang['RejectFromCouncil']}&nbsp;&gt;&gt</a><br />\n";
				}
				elseif (! ($Group['co1'] && $Group['co2'])) echo "\t\t<br /><a href=\"clan.php?action=addtocouncil&name=${t['login']}&confirm=$secret\">{$Lang['AddToCouncil']}&nbsp;&gt;&gt</a><br />\n";
				if ($t['login'] != $Group['owner']) echo "\t\t<br /><a class=\"delete\" href=\"javascript:ask('clan.php?action=reject&name=${t['login']}&confirm=$secret')\">{$Lang['RejectFromGroup']}&nbsp;&gt;&gt</a><br />\n";
			}
			elseif (! $t['clan']) {
				$db->query("SELECT `from` FROM `${prefix}clanmessages` WHERE `type`='join' AND `from`='${t['login']}' AND `clan`='${Group['name']}' LIMIT 1;");
				if ($db->numrows()) echo "\t\t<br /><a href=\"clan.php?action=admit&name=${t['login']}\">{$Lang['JoinToGroup']}&nbsp;&gt;&gt</a><br />\n";
				else echo "\t\t<br /><a href=\"control.php?action=invitate&name=${t['login']}\">{$Lang['SendInvitation']}&nbsp;&gt;&gt</a><br />\n";
			}
		}

		if ($t['locked'] < $timestamp && ($User['usergroup'] == 'wheel' || $User['usergroup'] == 'moderators' || $User['usergroup'] == 'jailchief')) {
			if ($t['banned'] < $timestamp) echo "\t\t<br /><a class=\"delete\" href=\"admin.php?view=ban&name=${t['login']}\">${Lang['BanUser']}&nbsp;&gt;&gt</a><br />\n";
			else echo "\t\t<br /><a href=\"admin.php?action=unlock&name=${t['login']}\">${Lang['UnbanUser']}&nbsp;&gt;&gt</a><br />\n";
		}

		if ($User['usergroup'] == 'wheel') {
			if ($t['locked'] < $timestamp) echo style_linkbox("admin.php?view=lock&name=${t['login']}", $Lang['LockAccount'], 'delete');
			else echo style_linkbox("admin.php?action=unlock&name=${t['login']}", $Lang['UnlockAccount'], 'delete');
			echo style_linkbox("admin.php?view=changepassword&name=${t['login']}", $Lang['ChangePassword'], 'delete');
			echo style_linkbox("admin.php?view=teleport&name=${t['login']}", $Lang['Teleport'], 'capacity');
		}

		echo "\t\t<br />\n";
	}
        else {

?>	<br />
	<font class="error"><?php echo $Lang['ErrorLoginNotExists']; ?>!</font><br />
	<br />
<?php
	}

	tableend('Finger');

	echo "\t<script>\n\t<!--\n\tfunction ask(\$url) {\n\t\tif (confirm('${Lang['AreYouSure?']}')) location.href = \$url;\n\t}\n\t//-->\n\t</script>\n";
}
elseif ((!$name) && (!$back)) {
	tablebegin('Finger', 500);

	echo "\t<br /><font class=\"h3\">${Lang['FindUser']}</font><br />\n\t<br />\n";

?>	
	<form action="whois.php" method="POST" name="form">
	<table align="center" cellspacing="0" cellpadding="0" border="0">
	<tr valign="top">
	<td>
		<?php echo $Lang['Login']; ?>:
	</td>
	<td>&nbsp; &nbsp;
	<td>
		<input type="text" name="name" />
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td colspan="3" align="center">
		<input type="submit" value="<?php echo $Lang['Check']; ?>" />
	</td>
	</tr>
	</table>
	</form>
<?php
	echo "\t<br />\n";

	tableend('Finger');
}

if ($name == $login) {

?>	<script>
	<!--

	function avatar() {
		$msg = prompt('<?php echo $Lang['EnterAvatarURL']; ?>', '');
		if ($msg > '') {
			$msg = $msg.replace(/\+/g,"%2B"); // code: kot
			$msg = $msg.replace(/\&/g,"%26");
			$msg = $msg.replace(/\#/g,"%23");
			$url = '<?php echo $_SERVER['PHP_SELF']; ?>?name=<?php echo $name; ?>&rid=<?php echo $rid; ?>&action=changeplayeravatar&url=' + $msg;
			document.location.href = $url;
		}
<?php
	 if ($Player['avatar']) {

?>		else if ($msg != null) {
			$url = '<?php echo $_SERVER['PHP_SELF']; ?>?name=<?php echo $name; ?>&rid=<?php echo $rid; ?>&action=changeplayeravatar';
			document.location.href = $url;
		}
<?php
	}

?>	}
	//-->
        </script>
<?php

}

require('include/footer.php');
