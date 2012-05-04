<?php

// ===========================================================================
// Chat Module {modules/chat}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.1
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// You may only use, modify, copy or distribute this content under the terms
// of GNU General Public License (GPL) or Synapse Artistic Licence (SAL).
// See LINCENSE file for details.
// =========================================================================== 

global $db, $prefix, $login, $timestamp, $Config, $logged, $banned, $RID, $Lang;

locale('chat');

if (!isset($Config['ChatHistory'])) $Config['ChatHistory'] = 1;
if (!isset($Config['ChatLife'])) $Config['ChatLife'] = 21600;

function chat($author, $message)
{
	global $db, $prefix, $login, $timestamp, $Config;
	if (@$db) {
		$db->query("INSERT INTO ${prefix}chat (timestamp,author,message) VALUES ('$timestamp','$author','$message');");
		if ($Config['ChatLife']) $db->query("UPDATE ${prefix}chat SET `hidden`=1 WHERE timestamp<'".date('YmdHis', time() - $Config['ChatLife'])."';");
		if ($Config['ChatHistory']) echolog("$login: $message", 'chat');
	}
}

function chatbox($last=30,$verbose=1,$break=1)
{
	global $db, $prefix, $Lang, $User, $RID;
	if (@$db) {
		$db->query("SELECT * FROM ${prefix}chat WHERE `hidden`=0 ORDER BY timestamp DESC LIMIT $last;");
		while ($t = $db->fetchrow()) {
			echo '<b><a href="whois.php?name='.strip_tags($t['author'])."\" onmouseover=\"self.status='${Lang['ChatSent']}: ".timestampdate($t['timestamp']).' '.timestamptime($t['timestamp'])."'; return true\" onmouseout=\"self.status=''; return true\">${t['author']}</a></b>: ".emoticons($t['message']).'<br />';
			if ($User['usergroup'] == 'wheel' || $User['usergroup'] == 'moderators') echo '<a class="delete" href="admin.php?action=chatdelete&view=chat&id='.$t['id']."&RID=".$RID.'">'.$Lang['Delete'].'</a><br />';
			if (@++$i < $db->numrows() && $break) echo '<hr size="1">';
		}
		if (!@$i && $verbose) echo "\t\t<center>${Lang['ChatNoMessages']}</center>\n";
	}
}

if (@$logged && !$banned && $chat = @$_GET['chat']) {
	$cid = @$_COOKIE['cid'];
	$oid = @$_GET['rid'];
	if (!$cid || !$oid || $cid != $oid) {
		if ($chat = $db->safe(trim(strip_tags($chat)))) {
			foreach (explode(' ', $chat) as $word) {
				if (strlen($word) > 20) $words[] = substr($word, 0, 19) . '...';
				else $words[] = $word;
			}
			chat($login, join(' ', $words));
			setcookie('cid', @$oid, time() + 3600);
		}
	}
}
