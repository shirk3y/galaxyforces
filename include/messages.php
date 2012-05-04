<?php

// ===========================================================================
// Messages {messages.php)
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	0.7
//	Created:	2004-01-31
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// Description:
//
// This module defines global functions used to send/receive private messages.
//
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// You may only use, modify, copy or distribute this content under the terms
// of GNU General Public License (GPL) or Synapse Artistic Licence (SAL).
// See LINCENSE file for details.
// =========================================================================== 

if (!defined('__MESSAGES__')) {

function deletemessage($login, $id=-1, $recent='')
{
	global $login, $db, $prefix;
	if ($recent) $db->query("DELETE FROM `${prefix}messages` WHERE `timestamp`<'$recent';");
	elseif ($id < 0) $db->query("DELETE FROM `${prefix}messages` WHERE `to`='$login';");
	else $db->query("DELETE FROM `${prefix}messages` WHERE `id`='$id' AND `to`='$login';");
}

function readmessages($login) {
	global $db, $prefix;
	$db->query("SELECT * FROM `${prefix}messages` WHERE `to`='$login' ORDER BY `timestamp` DESC LIMIT 0,100;");
	return $db->fetchall();
}

function checkmessages() {
	global $login, $db, $prefix;
	$db->query("SELECT `read` FROM `${prefix}messages` WHERE `to`='$login';");
	$all = $db->numrows();
	while ($t = $db->fetchrow()) @$unread += $t['read'] ? 0 : 1;
	return array($all, @$unread);
}

function sendmessage($subject, $message, $from='', $to='', $type='message', $when=0)
{
	global $login, $db, $prefix, $Config, $timestamp, $sendmessagehandlers;

	if ($Config['MessageLife']) deletemessage(0, 0, date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $Config['MessageLife'], date("Y"))));
	if (!$from) {
		if ($type == 'message') $from = 'system';
		elseif ($type == 'report') $from = 'robot';
	}
	if ($db->query("SELECT id FROM {$prefix}users WHERE login='".$db->safe($to)."';") && $db->numrows()) {
		$db->query("INSERT INTO `${prefix}messages` (`type`,`timestamp`,`from`,`to`,`subject`,`message`) VALUES ('$type','$timestamp','".$db->safe($from)."','".$db->safe($to)."','".$db->safe($subject)."','".$db->safe($message)."');");
		if (is_array($sendmessagehandlers)) foreach ($sendmessagehandlers as $handler) eval("$handler('".addslashes($subject)."','".addslashes($message)."','".addslashes($from)."','".addslashes($to)."');");
		return true;
	}
}

define('__MESSAGES__', 1);

}
