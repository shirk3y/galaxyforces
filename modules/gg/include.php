<?php

// ===========================================================================
// Gadu-Gadu Gateway Module
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	3
//	Created:	2004-08-10
//	Modified:	2010-01-16
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// This module uses "klasy-gg.inc" from www2gg package that can be found at:
// http://gg.wha.la.
// ---------------------------------------------------------------------------

// ===========================================================================
// You may find licence details at http://galaxy.game-host.org ;-)
// ===========================================================================

if (!defined('__MOD_GG__')) {

global $Config, $sendmessagehandlers;

function ggconnect() 
{
	global $Config, $gg;
	require_once('klasy-gg.inc');
	if (empty($Config['module.gg.user'])||empty($Config['module.gg.secret'])) return false;
	$gg = new www2gg((int)$Config['module.gg.user'], $Config['module.gg.secret']);
	if (@$Config['module.gg.debug']) $gg->debug = true;
	if (@$Config['Security']) unset($Config['module.gg.secret']);
	if (!empty($Config['module.gg.description'])) $gg->ustaw_opisy($Config['gg.description'], $Config['gg.description']);
	return true;
}

function ggmessage($uid, $message) {
	global $gg;
	if (!isset($gg) && !ggconnect()) return false;
	$gg->error = false;
	$gg->wiadomosc((int)$uid, $message);
	return empty($gg->error);
}
	
function sendggmessage($subject, $message, $from, $to)
{
	global $Config, $db, $prefix;
	if ($Config['module.gg.forward'] && is_object($db) && $db->query("SELECT gg,ggpublic,language from {$prefix}users WHERE login='".$db->safe($to)."';") && $user = $db->fetchrow()) ggmessage($user['gg'], "odebrano wiadomo¶æ (PM) od: $from ($subject)");
}
	
$sendmessagehandlers[] = 'sendggmessage';

define('__MOD_GG__', 1);

}
