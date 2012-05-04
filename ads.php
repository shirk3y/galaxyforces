<?php

// ===========================================================================
// Ads {ads.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.2
//	Modified:	2007-06-20
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces SSE.
// ===========================================================================

$index = 'ads';
$auth = true;

// JavaScript code
$js[] = 'functions';
$js[] = 'e107';

require('include/header.php');

$ad = getvar('ad');
$view = getvar('view');
$login = strtolower($login);

locale('content/ads');

$pagename = $Lang['Ads'];

// ===========================================================================
// ACTION
// ===========================================================================

if ($action == 'add') {
	list($lang, $type, $title, $message, $time, $status, $notify) = array(getvar('lang'), getvar('type'), str_sqlsafe(htmlspecialchars(strip_tags(getvar('title')))), str_sqlsafe(htmlspecialchars(str_replace("\n", '[br]', (strip_tags(getvar('message')))))), getvar('time'), getvar('status'), getvar('notify'));
	if (!$title || !$message || ($Player['usergroup'] != 'admin' && !$time)) $errors = $Lang['NoInput'].'<br />';
	else {
		if (function_exists('censorship') && !censorship($message)) $errors = $Lang['BackOff'].'<br />';
		if ($Player['usergroup'] != 'admin') $status = '0';
		if ($notify) $notify = '1';
		$time = 6 * ceil($time / 6); if ($time < 6) $time = 6; else if ($time > 72) $time = 72;
		$expires = $time * 3600 + time();
		$class = $Player['usergroup'] ? $Player['usergroup'] : 'user';
echo "INSERT INTO `${prefix}ads` (`expires`,`time`,`lang`,`class`,`author`,`title`,`type`,`content`,`status`,`notify`) VALUES ('".$expires."','".time()."','".$lang."','".$class."','".$Player['login']."','".$title."','".$type."','".$message."','$status','$notify');";		
		if ($db->query("INSERT INTO `${prefix}ads` (`expires`,`time`,`lang`,`class`,`author`,`title`,`type`,`content`,`status`,`notify`) VALUES ('".$expires."','".time()."','".$lang."','".$class."','".$Player['login']."','".$title."','".$type."','".$message."','$status','$notify');")) $result .= $Lang['AdAdded'].'<br />';
	else $errors = mysql_error().'<br />';
	}
}
else if ($action == 'reply') {
	$ad = getvar('ad');
	$message = str_sqlsafe(htmlspecialchars(str_replace("\n", '[br]', (strip_tags(getvar('message'))))));
	if (!$message) $errors = $Lang['NoInput'].'<br />';
	else if ($db->query("SELECT id,status,notify,author,title FROM `${prefix}ads` WHERE id='$ad' AND type!='reply' LIMIT 1;") && (!$t = $db->fetchrow())) $errors = $Lang['AdNotExists'].'<br />';
	else if ($t['status'] != '2') {
		if (function_exists('censorship') && !censorship($message)) $errors = $Lang['BackOff'].'<br />';
		if ($t['author'] != $login && $t['notify'] == '1') sendmessage($Lang['TitleNotify'], '<a href="whois.php?player='.$login.'">'.strcap($login).'</a> '.$Lang['MsgNotify'].' <a href="ads.php?ad='.$ad.'" class="result">"'.$t['title'].'"</a>', 'Robot', $t['author']);
		$class = $Player['usergroup'] ? $Player['usergroup'] : 'user';
		if ($db->query("INSERT INTO `${prefix}ads` (`class`,`time`,`author`,`title`,`type`,`content`) VALUES ('".$class."','".time()."','".$Player['login']."','".$ad."','reply','".$message."');")) $result .= $Lang['ReplySent'].'<br />';
		else $errors = mysql_error().'<br />';
		$db->query("UPDATE `${prefix}ads` SET replies=replies+1 WHERE id='$ad';");
	} else $errors = 'Whoops! <br />';
}
else if ($action == 'edit') {
	$ad = getvar('ad');
	list($lang, $type, $title, $message, $time, $status, $notify) = array(getvar('lang'), getvar('type'), str_sqlsafe(htmlspecialchars(strip_tags(getvar('title')))), str_sqlsafe(htmlspecialchars(str_replace("\n", '[br]', (strip_tags(getvar('message')))))), getvar('time'), getvar('status'), getvar('notify'));
	if ($db->query("SELECT type,title FROM `${prefix}ads` WHERE id='$ad' LIMIT 1;") && ($t = $db->fetchrow())) {
		if (((!$message || !$title) && $t['type'] != 'reply') || (!$message)) $errors .= $Lang['ErrorNoContent'].'<br />';
		else {
			if (function_exists('censorship') && !censorship($message)) $errors = $Lang['BackOff'].'<br />';
			$notify = ($notify ? $notify :'');
			$clausule = ($Player['usergroup'] == 'admin' ? '' : " AND `author`='".$login."'");
			if ($Player['usergroup'] != 'admin') $status = '0';

			if ($t['type'] == 'reply') {
				$type = 'reply';
				$time = time();
				$title = $t['title'];
			}
			
			$time_sql = '';
			if ($time) {
				$time = 6 * ceil($time / 6);
				if ($time < 6) $time = 6;
				else if ($time > 72) $time = 72;
				
				$time_sql = ",`expires`='".( time() + $time * 3600 )."'";
			}
			if ($db->query("UPDATE `${prefix}ads` SET lang='$lang',title='$title',type='$type',content='$message',status='$status',notify='$notify'".$time_sql." WHERE `id`='".$ad."'".$clausule." ;")) $result = $Lang['PostEdited']."<br />";
			else $errors = mysql_error().'<br />';
		}
	} else $errors = $Lang['AdNotExists'].'<br />';
}
else if ($action == 'remove') {
	$ad = getvar('ad');
	if ($db->query("SELECT `type`,`title`,`author` FROM `${prefix}ads` WHERE id='$ad' LIMIT 1;") && $t = $db->fetchrow()) {
		if ($Player['usergroup'] == 'admin' || $Player['usergroup'] == 'mod' || $Player['usergroup'] == 'global_mod') $clausule = '';
		else $clausule = " AND `author`='".$login."'";
		if ($db->query("DELETE FROM `${prefix}ads` WHERE id='$ad' OR (type='reply' AND title='$ad') ".$clausule.";")) {
			if ($t['type'] == 'reply') {
				$ad = $t['title'];
				$db->query("UPDATE `${prefix}ads` SET replies=replies-1 WHERE id='$ad';");
			}
			if ($t['type'] == 'reply') {
				$result = $Lang['ReplyDeleted'].'<br />';
				if ($t['author'] != $login) sendmessage($Lang['TitleReDeleted'], '<a href="whois.php?player='.$login.'">'.strcap($login).'</a> '.$Lang['MsgReDeleted'].' <a href="ads.php?ad='.$t['title'].'" class="result">"'.$t['title'].'"</a>', 'Robot', $t['author']);
			}
			else {
				$result = $Lang['AdDeleted'].'<br />';
				if ($t['author'] != $login) sendmessage($Lang['TitleAdDeleted'], '<a href="whois.php?player='.$login.'">'.strcap($login).'</a> '.$Lang['MsgAdDeleted'].' <span class="result">"'.$t['title'].'"</span>', 'Robot', $t['author']);
			}
		}
		else $errors = $Lang['Error'].' '.mysql_error().'<br />';
	}
	else $errors = $Lang['AdNotExists'].'<br />';
}

// ===========================================================================
// ERROR
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<h3>${Lang['ErrorProblems']}</h3><font class=\"error\">$errors</font><br />";
	echo "<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend($pagename);
}

// ===========================================================================
// RESULTS
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend($pagename);
}

// ===========================================================================
// ADS LIST
// ===========================================================================

else if (!$view) {
	tablebegin($pagename);
		echo "<br /><span class=\"h3\">$pagename</span><br /><br />";
		echo "<input type='hidden' name='idads' id='idads' value='0' /><table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n\t".'
			<tr id="header">
				<td width="12" id="headerl">&nbsp;</td>
				<td width="50">
					<a href="" class="result">'.$Lang['Language'].'</a>
				</td><td width="8">&nbsp;</td><td width="75">
					<a href="" class="capacity">'.$Lang['Category'].'</a>
				</td><td width="8">&nbsp;</td><td width="75">
					<a href="">'.$Lang['Author'].'</a>
				</td><td width="8">&nbsp;</td><td align="left" width="40%">
					<a href="" class="result">'.$Lang['Title'].'</a>
				</td><td width="8">&nbsp;</td><td width="50">
					<a href="">'.$Lang['Replies'].'</a>
				</td><td width="8">&nbsp;</td><td width="50">
					<a href="">'.$Lang['Time'].'</a>
				</td><td width="8">&nbsp;</td><td width="50">
					<a href="" class="minus">'.$Lang['Expires'].'</a>
				</td>
				<td width="12" id="headerr">&nbsp;</td>
			</tr>';
			
		
		// Read from db, remove expired msgs
		
		$tab = array();
		$del = false;

		$db->query("SELECT * FROM `${prefix}ads` WHERE `type` != 'reply' AND `expired`=0;");
		while ($t = $db->fetchrow()) {
			if ($t['expires'] <= $now) $del=true;
			else $tab[] = $t;
		}
		if ($del) $db->query("UPDATE `${prefix}ads` SET `expired`=1 WHERE `expires`<=".time());
?>
	<script>
	<!--

	function ask($url) {
		if (confirm('<?php echo $Lang['AreYouSure?']; ?>')) location.href = $url;
	}

	//-->
	</script>
<?php
		if (count($tab)) foreach ($tab as $t) {
			$i++;
			if (isset($Ranks[$t['class']]['chat'])) $id = ' style="'.$Ranks[$t['class']]['chat'].'"';
			else $id = (! ($i % 2)) ? $id = ' id="div"' :  $id = '';
			// Generate ads list
			echo '<tr align="center"'.$id.'>
					<td>&nbsp;</td><td class="result">
						'.@$Lang['Lang[]'][$t['lang']].'
					</td><td>&nbsp;</td><td class="capacity">
						'.@$Lang['AdTypes[]'][$t['type']].'
					</td><td>&nbsp;</td><td>
						<a href="whois.php?player='.$t['author'].'">'.strcap($t['author']).'</a>
					</td><td>&nbsp;</td><td align="left">
						<a href="?ad='.$t['id'].($page ? "&page=$page" : '').'" class="result">'.$t['title'].' &raquo;</a>
					</td><td>&nbsp;</td><td>
						<a class="onclick" onclick=\'ADS.replies("'.$t['id'].'")\'>'.$t['replies'].' &raquo;</a>
					</td><td>&nbsp;</td><td>
						'.($t['time'] ? date('Y-m-d H:i ', (int)$t['time']) : $Lang['Unknown']).'
					</td><td>&nbsp;</td><td class="minus">
						'.($t['expires'] ? date('Y-m-d H:i', (int)$t['expires']) : $Lang['Never']).'
					</td><td>&nbsp;</td>';
				echo '</tr>'; //href="?ad='.$t['id'].($page ? "&page=$page" : '').'#replies"
				echo "<tr style='height: 0px; padding: 0;'><td style='line-height: 0px; padding: 0;' colspan='15'><center><div style='width:500px;' id='ads{$t['id']}'></div></center></td></tr>";
			// View ad
			if (isset($ad) && $ad == $t['id']) {
				require('include/bbcode.php');
				echo '<tr align="center">
						<td colspan="15">
							<br /><table cellspacing="0" cellpadding="0" style="width: 75%;">
								<tr id="header">
									<td id="headerl">&nbsp;</td><td>'.$Lang['Message'].'</td><td id="headerr">&nbsp;</td>
								</tr><tr id="div">
									<td id="headerl">&nbsp;</td><td>'.bbcode($t['content']).'<br /><div align="right"><b><a href="?ad='.$ad.'&view=reply">'.$Lang['Reply'].' &raquo;</a></b>';
				if ($t['author'] == $login || in_array($Player['usergroup'], array('admin', 'global_mod'))) echo " &nbsp; <a href=\"?ad=$ad&view=edit\">${Lang['Edit']} &raquo;</a> &nbsp;  <a href=\"javascript:ask('ads.php?action=remove&ad=".$t['id']."')\" class=\"minus\">${Lang['Delete']} &raquo;</a>";
				echo '</div></td><td id="headerr">&nbsp;</td>
								</tr><tr>
									<td id="headerb" colspan="3">&nbsp;</td>
								</tr>
							</table>';
				// Ad have replies?
				if ($t['replies'] > 0) {
					$db->query("SELECT * FROM `${prefix}ads` WHERE type = 'reply' AND title = $ad;");
					
					echo '<a name="replies"></a><table cellspacing="0" cellpadding="0" style="width: 50%;">
							<tr id="header">
								<td id="headerl">&nbsp;</td><td>'.$Lang['Replies'].'</td><td id="headerr">&nbsp;</td>
							</tr>';
					while ($r = $db->fetchrow()) {
						if (isset($Ranks[$r['class']]['chat'])) $id = ' style="'.$Ranks[$r['class']]['chat'].'"';
						else $id = ' id="div"';
						echo '<tr'.$id.'><td id="headerl">&nbsp;</td><td>'.bbcode($r['content']).'<br /><b><a href="whois.php?player='.$r['author'].'">'.strcap($r['author']).'</a> at <span class="minus">'.date('Y-m-d H:i', $r['time']).'</span></b>';
						if ($t['author'] == $login || in_array($Player['usergroup'], array('admin', 'global_mod'))) echo "<div align=\"right\"><a href=\"?ad=".$r['id']."&view=edit\">${Lang['Edit']} &raquo;</a> &nbsp;  <a href=\"javascript:ask('ads.php?action=remove&ad=".$r['id']."')\" class=\"minus\">${Lang['Delete']} &raquo;</a></div>";
						echo '</td><td id="headerr">&nbsp;</td>
							
							</tr>';
					}
					if (!$db->numrows()) echo '<tr id="headerbox" align="center"><td id="headerl">&nbsp;</td><td><span class="minus">'.$Lang['NoReplies'].'</span></td><td id="headerr">&nbsp;</td></tr>';
					echo '<tr>
								<td id="headerb" colspan="3">&nbsp;</td>
							</tr>
						</table>
						</td>
				<tr>';
				}
			}			
		}
		
		if (mysql_error()) echo '<tr align="center"><td colspan="15"><br /><span class="minus">'.mysql_error().'</span></td><tr>'; // whoops!
		
		if (!count($tab)) echo '<tr align="center"><td colspan="15"><br /><span class="minus">'.$Lang['NoAds'].'</span></td><tr>'; // no ads :(

		echo '</table>';
		
		tablebreak();
	
		echo '<br /><a href="?view=add">'.$Lang['AddAd'].' &raquo;</a><br /><br />';
	tableend($pagename);
}

// ===========================================================================
// ADD / EDIT
// ===========================================================================

else if ($view == 'add' || $view == 'edit' || $view == 'reply') {
	$expires = $title = $lang = $message = $expires = '';
	if (!$lang) $lang = $Player['language'];

	switch($view) {
		case 'add': $pagename = $Lang['AddAd']; $submit = $Lang['Save']; break;
		case 'edit': $pagename = $Lang['EditAd']; $submit = $Lang['Edit']; break;
		case 'reply': $pagename = $Lang['Reply'];  $submit = $Lang['Reply']; break;
	}

	if ($view == 'edit') {
		$ad = getvar('ad');
		$db->query("SELECT `expires`,`lang`,`title`,`content`,`type`,`status`,`notify` FROM `${prefix}ads` WHERE id='$ad' LIMIT 1;");
		if ($row = $db->fetchrow()) {
			$expires = $row['expires'];
			$title = $row['title'];
			$lang = $row['lang'];
			$type = $row['type'];
			$message = $row['content'];
			$status = $row['status'];
			$notify = $row['notify'];
		} else echo mysql_error().'No!';
		
	}

	tablebegin($pagename, 500);

	echo '<br /><span class="h3">'.$pagename.'</span><br /><br />';
	echo '<form action="ads.php" method="post" name="ads"><input type="hidden" name="action" value="'.$view.'"><input type="hidden" name="ad" value="'.$ad.'">
		<table id="form" align="center" cellspacing="0" cellpadding="0" border="0">';
	if (($view !== 'edit' || @$type !== 'reply') && $view !== 'reply') { echo '
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
			<td><b>'.$Lang['Title'].':</b></td>
			<td>&nbsp;</td>
			<td><input type="text" size="55" maxlength="40" name="title" value="'.$title.'" />
				<select name="type">';
	 
	foreach ($Lang['AdTypes[]'] as $key => $v) echo '<option value="'.$key.'"'.($key == $type ? ' selected' : '').'>'.$Lang['AdTypes[]'][$key].'</option>';
	echo '</select></td>
		</tr>
		<tr>
			<td><b>'.$Lang['Time'].':</b></td>
			<td>&nbsp;</td>
			<td><select name="time">'.($expires ? '<option value="0">'.$Lang['DoNotChange'].'</option>' : '' );
			for ($i = 24; $i <= 168; $i += 24) { echo '<option value="'.$i.'">'.$i.' '.$Lang['hours'].'</option>'; }
		echo '</select></td>
		</tr>
		<tr>
			<td><b>'.$Lang['Language'].':</b></td>
			<td>&nbsp;</td>
			<td><select name="lang">';
			foreach ($Lang['Lang[]'] as $key => $v) echo '<option value="'.$key.'"'.($key == $lang ? ' selected' : '').'>'.$Lang['Lang[]'][$key].($key ? " ($key)" : '').'</option>';
		echo '</tr>';
		if ($Player['usergroup'] == 'admin') echo '
		<tr>
			<td><b>'.$Lang['Status'].':</b></td>
			<td>&nbsp;</td>
			<td><select name="status">
				<option value="0"'.($status == 0 ? ' selected' : '').'>'.$Lang['Status[]'][0].'</option><option value="1"'.($status == 1 ? ' selected' : '').'>'.$Lang['Status[]'][1].'</option><option value="2"'.($status == 2 ? ' selected' : '').'>'.$Lang['Status[]'][2].'</option>
			</tr>';
		echo '<tr>
			<td colspan="2">&nbsp;</td>
			<td><input type="checkbox" name="notify"'.(@$notify ? ' checked' : '').'> '.$Lang['NotifyAnsvers'].'</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>';
		}
		echo '
		<tr>
			<td><b>'.$Lang['Message:'].'</b></td>
			<td>&nbsp;</td>
			<td>';
	require('include/editor.php');
	editor('message', 72, 20, 'left', str_ireplace( '[br]', '', $message ) );
	echo '</td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
			<td colspan="3">';

	echo '<center><input type="submit" value="'.$submit.'" /></center>';
?>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	</table>
<?php
			
			echo '</form>';
	tableend("<a href=\"ads.php\">${Lang['GoBack']} &raquo;</a>");
} 

require('include/footer.php');
