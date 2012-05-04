<?php

// ===========================================================================
// Auth control {auth.php)
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.9
//	Created:	2004-08-06
//	Modified:	2005-11-19
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// -------------------------------------------------------------------------:--
// Description:
//
// This is basic authenticate module which can be used to control if user is
// logged in. It supplies logging and logout using "action" variable (must be
// defined). Only two states of "action" variable are correct for this module:
// "login" and "logout".
//
// Using $db (database port for all phpSynapse modules) it checks users table
// to find user, and then it can redirect user to login page if "$auth" is set
// to true.
//
// If everything is o.k. $logged is set to "true". If your page sets $auth to
// "true" (before including header page) then this module prevents loading it!
//
// Simple example php page that shows how to use this module in your site:
//
//	$auth = true;
//	require('include/header.php');
//	echo "Authentication passed!"
//	require('include/footer.php');
//
// Note: you have to use "users" table that has "login" and "password" fields
// where password is hashed using crypt() function
//
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// Changes:
//	1.6 Authorisation via HTTP
//	1.4 Added new variables: $locked and $banned
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// Requires: common.php, config.php, db.php
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of phpSynapse project. See documentation for details.
// ===========================================================================

if (!defined('__AUTH_PHP__')) {
	
if (!@$Config['LoginPage']) $Config['LoginPage'] = 'login.php';
if (!@$Config['DefaultAuth']) $Config['DefaultAuth'] = FALSE;
if (!@$Config['AuthType']) $Config['AuthType'] = 'cookie';

if (!isset($auth)) $auth = $Config['DefaultAuth'];

switch($Config['AuthType']) {
	case 'http':
		$login = @$_SERVER['PHP_AUTH_USER'];
		$password = @$_SERVER['PHP_AUTH_PW'];
		break;
	case 'cookie':
		$login = strip_tags(isset($_COOKIE['login']) ? $_COOKIE['login'] : @$_POST['login']);
		$password = @$_POST['password'];
		$salt = isset($_COOKIE['salt']) ? $_COOKIE['salt'] : '';
		break;
	case 'session':
		$login = strip_tags(getrvar('login'));
		$password = @$_POST['password'];
		$salt = isset($_SESSION['salt']) ? $_SESSION['salt'] : '';
		break;
	default: $salt = '';
}

$ip = $_SERVER['REMOTE_ADDR'];
$secret = md5(crypt(date('d'), crypt($ip, '_)')) . $login);
$unique = $_SERVER['SERVER_NAME']."\n".$_SERVER['HTTP_USER_AGENT']."\n".$_SERVER['REMOTE_ADDR'];
$seed = '';
$logged = false;

if (@$db && ($login = $db->safe($login)) && (($Config['AuthType'] == 'http' && $password) || ($action == 'login' && $password || ($salt && $action != 'logout')))) {
	$db->query("SELECT password,seed,usergroup,language,style,ip,lastip,locked,banned FROM ${prefix}users WHERE login='$login' AND active=1;");
	if ($t = $db->fetchrow()) {
		if ($locked = $t['locked'] > $timestamp) $logged = false;
		elseif ($action == 'login' || $Config['AuthType'] == 'http') {
			if (md5($password) == $t['password']) {
				$hex="0123456789abcdef";
				for ($i = 1; $i <= 16; $i++) $seed .= $hex[rand(0, 15)];
				$salt = sha1($login.$unique.$seed);
				$logged = true;
				if ($ip != $t['ip']) { $t['lastip'] = $t['ip']; $t['ip'] = $ip; }
				$db->query("UPDATE ${prefix}users SET seed='$seed',seen='$timestamp',online='$timestamp',ip='${t['ip']}',lastip='${t['lastip']}',locked='' WHERE login='$login';");
			}
		}
		elseif ($salt == sha1($login.$unique.$t['seed'])) $logged = true;
		else $logged = false;

		unset($t['password']);

		if (!($banned = $t['banned'] > $timestamp) && $t['banned']) $db->query("UPDATE ${prefix}users SET banned='' WHERE login='$login';");

		if ($logged) {
			$User = $t;
			$Config['Language'] = $t['language'];
			if ($t['style'] && ! @$Config['IgnoreUserStyle']) $Config['Style'] = $t['style'];
			switch($Config['AuthType']) {
				case 'http':
					break;
				case 'cookie':
					$time = empty($Config['LoginTime']) ? 0 : time() + $Config['LoginTime'];
					setcookie('login', $login, $time);
					setcookie('salt', $salt, $time);
					break;
				case 'session':
					$_SESSION['login'] = $login;
					$_SESSION['salt'] = $salt;
					break;
			}
			if (!$seed) $db->query("UPDATE ${prefix}users SET online='$timestamp' WHERE login='$login';");
		}
	}
}

if ($action == 'logout' && $login && @$db) $db->query("UPDATE ${prefix}users SET seed='',online='' WHERE login='$login';");

if (!$logged) {
	switch($Config['AuthType']) {
		case 'cookie':
			setcookie('login', '', time() - 86400);
			setcookie('salt', '', time() - 86400);
			break;
		case 'session':
			$_SESSION['login'] = '';
			$_SESSION['salt'] = '';
			break;
	}
}

if (@$locked) {
	header("Location: accountlocked.php?time=${t['locked']}\r\n"); 
	die;
}
elseif ($auth && !$logged) {
	if ($Config['AuthType'] == 'http') {
		header("WWW-Authenticate: Basic realm=\"${Config['Title']}\"");
		header('HTTP/1.0 401 Unauthorized');
	}
	elseif ($Config['LoginPage']) {
		$url = "${Config['LoginPage']}?rid=$rid&back=${_SERVER['PHP_SELF']}";
		if ($login) $url .= "&login=$login";
		if ($action) $url .= "&action=$action";
		header("Location: $url\r\n");
	}
	die;
}

if ($logged && $action == "login") {
	if (empty($Config['AuthPage'])) break;
	if (strtolower(substr($_SERVER['PHP_SELF'], -strlen($Config['LoginPage']))) == strtolower($Config['LoginPage'])) {
		header('Location: '.$Config['AuthPage']."\r\n");
		die;
	}
}

define('__AUTH_PHP__', TRUE);

}
