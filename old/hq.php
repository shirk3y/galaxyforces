<?php

// ===========================================================================
// Headquarters {hq.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.0
//	Modified:	2007-04-24
//	Author(s):	Desmond
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces Stellar Quest project.
// ===========================================================================

$auth = true;
$index = 'headquarters';

require('include/header.php');

$page = getvar('page');

locale('content/hq');
$pagename = $Lang['HeadQuarters'];

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
	sound('error');
	tableend("<a href=\"hq.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("<a href=\"hq.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// HQ
// ===========================================================================

else {
	if ($Player['usergroup'] == 'admin') {
?>
		<script>
		<!--
		function ask($url)
		{
			if (confirm('<?php echo $Lang['AreYouSure?']; ?>')) location.href = $url;
		}
		//-->
		</script>
<?php	}

	list($all, $unread) = checkmessages();
	
	tablebegin($pagename, 500);
	echo "<h3>${Lang['Welcome']} ".strcap($Player['login'])."!</h3>";
	echo '<table width="100%" cellspacing="0"><tr id="header"><td id="headerl">&nbsp;</td><td>';
	echo ($all ? ($all > 1 ? $Lang['YouHave '].$all.$Lang[' messages'] : $Lang['YouHave '].$all.$Lang[' message']).' ('.($unread ? '<a href="messages.php?rid='.$rid.'">'.($unread > 1 ? $unread.$Lang[' unread'] : $unread.$Lang[' unread1']).'</a>' : $unread.$Lang[' unread']).')' : $Lang['No messages']);
	if ($unread) sound('incomingtransmission');
	echo '</td><td id="headerr">&nbsp;</td></td></tr></table><br />';

	tablebreak();
	
	if (!$page) {
		require('include/bbcode.php');
	    $db->query("SELECT * FROM ${prefix}news WHERE locale='' OR locale='${Config['Language']}' ORDER BY timestamp DESC LIMIT 2;");

		echo "<h3>${Lang['News']}</h3>";
		echo "<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";

	    if ($db->numrows()) while ($n = $db->fetchrow()) {
	        $n['date'] = date("Y-m-d",$n['timestamp']);
	        $n['time'] = date("H:i:s",$n['timestamp']);

	        echo "<tr valign=\"top\">";
			echo "<td width=\"12\">&nbsp;</td><td width=\"80\"><font class=\"plus\">${n['date']}</font><br /><font class=\"result\">${n['time']}</font><br /><font class=\"capacity\">".strcap($n['from'])."</font><br /></td>";
			echo "<td width=\"16\">&nbsp;</td><td><p />".bbcode($n['message'])."<br /></td><td width=\"12\">&nbsp;</td>";
			echo "</tr><tr><td colspan=\"5\">&nbsp;</td></tr>";
		}
		else echo "</tr><tr align=\"center\"><td colspan=\"5\" class=\"result\"><i>${Lang['NoNews']}</i></td></tr>";
		echo "</table><br />";
	}
	else if ($page == 'archive') {
		echo "<h3>${Lang['Archive']}</h3>";
		
		$db->query("SELECT * FROM ${prefix}news WHERE locale='' OR locale='${Config['Language']}' ORDER BY timestamp DESC;");

		echo "<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
			<tr id=\"header\" valign=\"top\">
			<td id=\"headerl\">&nbsp;</td><td width=\"10\">&nbsp;</td>
			<td>&nbsp; &nbsp;</td><td align=\"center\">${Lang['From']}:</td>
			<td>&nbsp; &nbsp;</td><td align=\"center\">${Lang['Title']}</td>
			<td width=\"12\">&nbsp;</td><td align=\"center\">${Lang['Date']}</td>
			<td width=\"12\" id=\"headerr\">&nbsp;</td></tr>";

		$n = 0;
		$id = getvar('id');
		if ($db->numrows()) while ($m = $db->fetchrow()) {
			$n++;
			$class = !($n % 2) ? ' id="div"' : '';
			
			$date = date('Y-m-d', $m['timestamp']);
			$title = @$m['title'] ? $m['title'] : (strlen($m['message']) > '40' ? substr(str_replace('<br />', '', $m['message']), 0, 40) . ' ...' : str_replace('<br />', '', $m['message']));
			//if (!$m['title']) { if (strlen($m['message']) > '40') $m['title'] = substr($m['message'], 0, 40) . ' ...'; else $m['title'] = $m['message']; }

			echo "<tr height=\"24\"$class>
				<td></td><td>$n.</td>
				<td></td><td align=\"center\"><a href=\"whois.php?player=".urlencode($m['from'])."\">".strcap($m['from'])."</a></td>
				<td></td><td><a class=\"result\" href=\"hq.php?page=archive&id=${m['id']}\">$title &raquo;</a></td>
				<td></td><td align=\"center\"><font class=\"plus\">$date</font></td>
				<td></td></tr>";
				
			if ($m['id'] == $id) {
				require('include/bbcode.php');

				echo '<tr id=selection><td width="8">&nbsp;</td><td align="left" colspan="7"><p align="justify">'.bbcode($m['message']).'';
				if ($Player['usergroup'] == 'admin') {
					echo '<div align="right"><a href="admin.php?view=edit_news&id='.$m['id'].'">'.$Lang['Edit'].' &raquo;</a> &nbsp; ';
					echo '<a href="javascript:ask(\'admin.php?view=news&action=delete&id='.$m['id'].'\')" class="delete">'.$Lang['Delete'].' &raquo;</a></div>';
				}
				echo '</td><td width="8">&nbsp;</td></tr>';
			}
		} 
		else echo "</tr><tr align=\"center\"><td colspan=\"9\" class=\"result\"><br /><i>${Lang['NoNews']}</i></td></tr>";
		echo "</table><br />";	
	}
	else if ($page == 'chronicle') {
		//locale('chronicle');
		echo "<h3>${Lang['Chronicle']}</h3><center>";

		$db->query("SELECT * FROM `${prefix}chronicle` ORDER BY `timestamp` DESC LIMIT 100;");
		
		if ($db->numrows()) {
			echo "<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
			<tr id=\"header\" valign=\"middle\">
				<td id=\"headerl\">&nbsp;</td><td align=\"center\">${Lang['Date']}:</td>
				<td>&nbsp;</td><td align=\"left\">${Lang['Event']}</td>
				<td width=\"12\" id=\"headerr\">&nbsp;</td>
			</tr>";

			$i = 0;
			while (($c = $db->fetchrow()) && ++$i) {
				switch($c['type']) {
					case '':
						$id = '';
						$msg = "<b>${c['var1']} ${c['var2']} ${c['var3']}</b> - unknown event! ...";
						break;
					case 'msg':
						$style = explode(',',$c['var3']);
						$id = $style[0];
						if ($Player['language'] == 'pl') $msg = "<font class=\"${style[1]}\">${c['var1']}</font>"; else $msg = "<font class=\"minus\">${c['var2']}</font>";
						break;
					case 'newclan':
						$id = 'admit';
						$msg = "<a href=\"whois.php?name=${c['var1']}\"><b>".strcap($c['var1'])."</b></a> ${Lang['ChronicleNewClan']} <font class=\"plus\"><b>${c['var2']}</b></font>";
						break;
					case 'clanname':
						$id = '';
						$msg = "<b><a href=\"whois.php?name=${c['var1']}\">${c['var1']}</b></a> ${Lang['ChronicleClanName']} <a href=\"whois.php?name=${c['var2']}\"><b>${c['var2']}</b></a> ${Lang['ChronicleClanName2']} <font class=\"minus\"><b>${c['var3']}</b></font>";
						break;
					case 'disbandclan': 
						$id = '';
						$msg = "<a href=\"whois.php?name=${c['var1']}\"><b>${c['var1']}</b></a> ${Lang['ChronicleDisbandClan']} <a href=\"whois.php?name=${c['var2']}\"><b>${c['var2']}</b></a>";
						break;
					case 'destroycolony': 
						$id = 'alert';
						$msg = "<font class=\"minus\"><a href=\"whois.php?name=${c['var1']}\" class=\"minus\"><b>${c['var1']}</b></a> ${Lang['ChronicleDestroyColony']} <a href=\"whois.php?view=colony&name=${c['var2']}\" class=\"minus\"><b>${c['var2']}</b></a> ${Lang['ChronicleDestroyColony2']} <a href=\"whois.php?name=${c['var3']}\" class=\"minus\"><b>${c['var3']}</b></a></font>";
						break;
				}
				echo "\t\t<tr height=\"24\"" . ($id ? " id=\"$id\"" : '') . ">\n";
				echo "\t\t<td width=\"12\">&nbsp;</td>\n";
				echo "\t\t<td align=\"center\"><a href=\"stardate.php?date=${c['time']}\">" . div($c['time']) . "</a></td>\n";
				//echo "\t\t<td align=\"center\"><a href=\"stardate.php?date=${c['time']}\" title=\"".date( "Y-m-d H:i:s", $c['timestamp'] )."\">" . div($c['time']) . "</a></td>\n";
				echo "\t\t<td width=\"12\">&nbsp;</td>\n";
				echo "\t\t<td align=\"left\">$msg</td>\n";
				echo "\t\t<td width=\"12\">&nbsp;</td>\n";
				echo "\t\t</tr>\n";
			}
		echo "\t\t</tr></table><br />\n";
		} else echo "<center><span class=\"result\">${Lang['NoEvents']}</span></center><br />";
	}
	else if ($page == 'pillory') {
		echo "<h3>${Lang['Pillory']}</h3><center>";

		locale('admin');
		$db->query("SELECT `login`,`locked`,`banned`,`who`,`reason`,usergroup FROM `${prefix}users` WHERE `locked`>'$timestamp' OR `banned`>'$timestamp' ORDER BY `login`;");
		if (!$db->numrows()) echo "\t\t<br /><font class=\"minus\">${Lang['NoUsers']}!</font><br /><br />\n";
		else {
			echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
			echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"center\">${Lang['Login']}</td><td>&nbsp;</td><td align=\"center\">${Lang['Status']}</td><td>&nbsp;</td><td align=\"center\">${Lang['ByWho']}</td><td>&nbsp;</td><td align=\"center\">${Lang['To']}</td><td>&nbsp;</td><td align=\"center\">${Lang['Reason']}</td><td id=\"headerr\">&nbsp;</td></tr>\n";

			while ($t = $db->fetchrow()) {
				$time = ($t['locked'] ? $t['locked'] : $t['banned']);
				$time = date("Y-m-d H:i:s", $time);
				$status = '<font class="'.($t['locked'] >= $timestamp ? 'minus">'.$Lang['locked'] : 'work">'.$Lang['banned']).'</font>';
				
				echo "\t\t<tr><td></td><td align=\"center\"><a href=\"whois.php?name=${t['login']}\">".rank(strcap($t['login']), $t['usergroup'])."</a></td><td></td><td align=\"center\">$status</td><td></td><td align=\"center\"><a href=\"whois.php?name=${t['who']}\">".strcap($t['who'])."</a></td><td></td><td align=\"center\">$time</td><td></td><td class=\"capacity\" align=\"center\">".$t['reason']."</td><td align=\"right\">";
				echo "<td>";
				echo "</td><td></td></tr>\n";
			}
			echo "\t\t</table><br />\n";
		}
		echo "<span class=\"h3\">${Lang['LastBans']}</span><br /><br />";
		$db->query("SELECT `login`,`locked`,`banned`,`bancount`,`who`,`reason` FROM `${prefix}users` WHERE bancount > 1 ORDER BY `bancount` DESC, `login` LIMIT 50;");
		if (!$db->numrows()) echo "\t\t<br /><font class=\"minus\">${Lang['NoUsers']}!</font><br /><br />\n";
		else {
			echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
			echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"center\">${Lang['Login']}</td><td>&nbsp;</td><td align=\"center\">${Lang['BanCount']}</td><td>&nbsp;</td><td align=\"center\">${Lang['ByWho']}</td><td>&nbsp;</td><td align=\"center\">${Lang['Reason']}</td><td id=\"headerr\">&nbsp;</td></tr>\n";

			while ($t = $db->fetchrow()) {
				$sign='';
				if ($t['locked']) $sign = '<font class="minus"><b>!!</b></font>'; if ($t['banned']) $sign = '<font class="work"><b>!</b></font>';
				echo "\t\t<tr><td></td><td align=\"center\"><a href=\"whois.php?name=${t['login']}\">".strcap($t['login'])."</a></td><td></td><td align=\"center\">".$t['bancount']." ".$sign."</td><td></td><td align=\"center\"><a href=\"whois.php?name=${t['who']}\">".strcap($t['who'])."</a></td><td></td><td class=\"capacity\" align=\"center\">".$t['reason']."</td><td align=\"right\">";
				echo "<td>";
				echo "</td><td></td></tr>\n";
			}
			echo "\t\t</table><br />\n";
		}
	}
	else if ($page == 'staff') {
		echo "<h3>${Lang['Administration']}</h3>";

		$db->query("SELECT login,language,usergroup FROM ${prefix}users WHERE usergroup='admin' OR usergroup='mod' OR usergroup='global_mod' OR usergroup='friend' ORDER BY usergroup DESC;");

		$lang_name = $Player['language'].'_name';

		if (!$db->numrows()) echo "\t\t<br /><font class=\"minus\">${Lang['NoUsers']}!</font><br /><br />\n";
		else {
			echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
			echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"center\">&nbsp;</td><td>&nbsp;</td><td align=\"center\">${Lang['Login']}:</td><td>&nbsp;</td><td align=\"center\">${Lang['Rank']}:</td><td id=\"headerr\">&nbsp;</td></tr>\n";

			$n = 0;
			while ($t = $db->fetchrow()) {
				$n++;
				$id = $n % 2 ? ' id="div"' : '';

				echo "\t\t<tr$id><td></td><td align=\"center\">[ ${t['language']} ]</td><td></td><td align=\"center\"><a href=\"whois.php?name=".urlencode($t['login'])."\">".rank(strcap($t['login']), $t['usergroup'])."</a></td><td>&nbsp; &nbsp;</td><td align=\"center\">".$Ranks[$t['usergroup']][$lang_name]."</td><td></td></tr>\n";

			}
			echo "\t\t</table>\n\t\t<br />\n";
		}
	}
	else if ($page == 'forum') {
	    subbegin();
	    echo "<center><span class=\"h3\">${Lang['Forum']}</span></center><br />";
	    if (@include ("http://desmond.9o.pl/galaxy/forum/extern.php?action=new&show=10")) echo '';
		else echo 'Cannot get topic list from forum, try again later or visit forum directly!';
	    subend();
	}

	echo "<div id=\"div\"><a href=\"hq.php\">${Lang['News']} &raquo;</a> &nbsp; <a href=\"?page=archive\">${Lang['Archive']} &raquo;</a> &nbsp; <a href=\"?page=chronicle\">${Lang['Chronicle']} &raquo;</a> &nbsp; <a href=\"?page=pillory\">${Lang['Pillory']} &raquo;</a> &nbsp; <a href=\"?page=staff\" class=\"minus\">${Lang['Staff']} &raquo;</a><br />";
	echo "<a href=\"?page=forum\">${Lang['Forum']} &raquo;</a> &nbsp; <a href=\"?page=galaxypedia\">${Lang['Galaxypedia']} &raquo;</a></div>";
	
	tablebreak();
	module('galaxy', 'tip');
	
	tableend($pagename);
}

// ===========================================================================
// FOOTER
// ===========================================================================

require('include/footer.php');
