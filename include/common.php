<?php

// ===========================================================================
// Common Engine {common.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	2.9
//	Created:	2004-03-22
//	Modified:	2005-11-13
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// This part initializes the main website engine and (almost) all required
// modules. It tries to be silent so after including this part you still can
// send your own output even if is not HTML.
//
// If including from other location than the main site root (default upper
// directory) you may want to define the real $ROOT before.
//
// Including this file causes site blocking for maintenance reasons. If the
// file MAINTENANCE is not empty, then $Config['MaintenancePage'] is being
// shown. All files that should be available during maintenance should set
// $Config['Maintenance'] value to true.
//
// If for some reasons you want to ignore configuration values (and set all by
// yourself) you will need to set $Config['Ignore'] value;
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// You may only use, modify, copy or distribute this content under the terms
// of GNU General Public License (GPL) or Synapse Artistic Licence (SAL).
// See LINCENSE file for details.
// =========================================================================== 

if (!defined('__COMMON_PHP__')) {

$timing_start = explode(' ', microtime());

if (!isset($ROOT)) $ROOT = '';

if (!@$Config['Internal']) {
	@filesize("{$ROOT}include/config.php") or (file_exists("{$ROOT}install.php") and header("Location: {$ROOT}install.php") and die) or die("Missing configuration!");
	include("{$ROOT}include/config.php");
}

if (@filesize("{$ROOT}MAINTENANCE.txt")) {
	if (!isset($Config['MaintenancePage'])) $Config['MaintenancePage'] = "{$ROOT}maintenance.php";
	if (!@$Config['Maintenance']) { 
		header("Location: {$Config['MaintenancePage']}\r\n"); 
		die; 
	}
}

if (!isset($Config['Debug'])) $Config['Debug'] = 1;

if ($Config['Debug']) error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); else error_reporting(E_ERROR | E_PARSE);

// -------------------------------------------------------------------
// Functions
// -------------------------------------------------------------------

function strdiv($x=0, $n=0, $d='.')
{
	return str_replace(' ', '&nbsp;', ($x < 1e8 ? number_format($x, $n, $d, ' ') : ($x < 1e14 ? number_format($x / 1e6, $n, $d, ' ').' M' : ($x < 1e20 ? number_format($x / 1e12, $n, $d, ' ').' G' : number_format($x / 1e18, $n, $d, ' ').' T'))));
}

function strcap($s)
{
	return $s ? strtoupper($s[0]).substr($s, 1) : '';
}

function escapesql($s)
{
	global $db;
	return method_exists(@$db, 'safe') ? $db->safe($s) : str_replace("''", "'", $s);
}

function strlower($str, $enc="UTF-8")
{
	if (function_exists('mb_convert_case')) return mb_convert_case($str, MB_CASE_LOWER, $enc); else return strtolower($str);
}

function strupper($str, $enc="UTF-8")
{
	if (function_exists('mb_convert_case')) return mb_convert_case($str, MB_CASE_UPPER, $enc); else return strtoupper($str);
}

function fname($fname)
{
	if ($p = strrpos($fname, "/")) $fname = substr($fname, -(strlen($fname) - ++$p));
	if ($p = strrpos($fname, "\\")) $fname = substr($fname, -(strlen($fname) - ++$p));
	if ($p = strrpos($fname, ".")) $fname = substr($fname, 0, $p-strlen($fname));
	return $fname;
}

function fext($fname)
{
	return substr(strrchr($fname, "."), 1);
}

function timestampdate($timestamp)
{
	return substr($timestamp, 0, 4).'-'.substr($timestamp, 4, 2).'-'.substr($timestamp, 6, 2);
}

function timestamptime($timestamp)
{
	return substr($timestamp, 8, 2).':'.substr($timestamp, 10, 2);
}

function elementexists($array, $element)
{
	for ($i=0; $i < count($array); $i++) if ($array[$i] == $element) return true;
	return false;
}

function sgn($x)
{
	return ($x < 0 ? -1 : ($x > 0 ? 1 : 0));
}

function is_assoc($var) {
	return is_array($var) && array_keys($var)!==range(0,sizeof($var)-1);
}

function array_delete($array, $item) {
	if (isset($array[$item])) unset($array[$item]);
	return array_merge($array);
}

function set_config($key, $value='')
{
	global $db, $prefix, $Config;
	@$db->query("DELETE FROM {$prefix}config WHERE config_key='$key';");
	@$db->query("INSERT INTO {$prefix}config (config_key, config_value) VALUES ('$key', '".@$db->safe($value)."');");
	$Config[$key] = $value;
}

function trace($die=true)
{
	global $db, $prefix, $layout, $Database, $Config, $User, $Menu, $Modules;
	?><h1>Breakpoint</h1><?php
	?><h3>Globals</h3><?php
	?><pre><?php
	echo '$_COOKIE = '; print_r($_COOKIE); echo "\n";
	echo '$_GET = '; print_r($_GET); echo "\n";
	echo '$_POST = '; print_r($_POST); echo "\n";
	echo '$_SERVER = '; print_r($_SERVER); echo "\n";
	?></pre><?php
	?><h3>Variables</h3><?php
	?><pre><?php
	if ($vars = get_defined_vars()) foreach ($vars as $name => $var) if (@$var) { echo "\$$name = "; var_export($var); echo "\n\n"; }
	?></pre><?php
	if ($die) die;
}

// -------------------------------------------------------------------
// Variables
// -------------------------------------------------------------------

function getvar($name)
{
	global $_POST, $_COOKIE, $_GET, $_SESSION;
	return isset($_POST[$name]) ? $_POST[$name] : (isset($_GET[$name]) ? $_GET[$name] : (isset($_COOKIE[$name]) ? $_COOKIE[$name] : (isset($_SESSION[$name]) ? $_SESSION[$name] : '')));
}

function postvar($var, $default='')
{
	return isset($_POST[$var]) ? $_POST[$var] : $default;
}

// -------------------------------------------------------------------
// Files
// -------------------------------------------------------------------

function writefile($filename, $content)
{
	if ($file = @fopen($filename, "w")) {
		flock($file, LOCK_EX);
		fputs($file, $content);
		flock($file, LOCK_UN);
		fclose($file);
		return true;
	}
	else return false;
}

function readfiles($path = '.', $recursive = true, $hidden = false) {
	$files = array();
	if (is_dir($path) && ($d = opendir($path))) {
		while ($i = readdir($d)) {
			if (! $i || $i == ".." || $i == "." || (! $hidden && ($i[0] == '.'))) continue;
			if (is_dir("$path/$i")) {
				if ($recursive && ($list = readfiles("$path/$i", $recursive))) for ($i = 0; $i < count($list); $i++) $files[] = $list[$i];
			}
			else $files[] = "$path/$i";
		}
		closedir($d);
	}
	return $files;
}

function str_bool($bool='')
{
	return is_bool($bool) ? ($bool ? 1 : '') : $bool;
}

function str_array($array=array())
{
	str_replace(",", "\\,", $array);
	return is_array($array) ? implode(",", $array) : $array;
}

function str_to_alnum($str)
{
	$r="";
	foreach (str_split($str) as $c)
		if (!(($c<'a'||$c>'z')&&($c<'a'||$c>'Z')&&($c<'0'||$c>'9')))
			$r.=$c;
	return $r;
}

function ctype_alnumusc($str)
{
	return ctype_alnum(strtr($str, '_', ''));
}

function phpvar($var, $prefix='')
{
	if (is_array($var)) {
		$result='array(';
		if (count($var)) {
			$prev = 0;
			foreach ($var as $key => $value) {
				$result .= "\n$prefix\t";

				if (is_numeric($key) && $key == $prev) $prev++;
				else {
					if (is_numeric($key)) $result .= "$key => ";
					else $result .= "'$key' => ";
				}

				if (is_array($value)) $result .= phpvar($value, $prefix."\t");
				else {
					if (is_bool($value)) $result .= $value ? 'true' : 'false';
					elseif (is_numeric($value)) $result .= $value;
					else $result .= "'".str_replace('\'', "\'", $value)."'";
				}
				$result .= ',';
			}
			$result .= "\n";
		}
		$result .= "$prefix)";
	}
	else {
		if (is_bool($var)) $result = $var ? 'true' : 'false';
		elseif (is_numeric($var)) $result = $var;
		else $result = "'".str_replace('\'', "\'", $var)."'";
	}
	return $result;
}

function parsecontent($content, $Vars=array(), $VarSource='', $VarDest='')
{
	$VarSource = '$Config[\'$1\']';
	$VarDest = '$Config[\'$1\']=$2;';
//	if (is_array(
}

// -------------------------------------------------------------------
// Logs
// -------------------------------------------------------------------

function echolog($content, $filename='common')
{
	global $ROOT, $Config;
	if (@$Config['Logging']) {
		$n = "{$ROOT}{$Config['LogPath']}{$filename}.log";
		if ((!@$Config['LogSize'] || !file_exists($n) || (filesize($n) < $Config['LogSize'])) && ($f = @fopen($n, 'a'))) {
			flock($f, LOCK_EX);
			fputs($f, $_SERVER['REMOTE_ADDR'].date(' [Y-m-d H:i:s] ').$content."\n");
			flock($f, LOCK_UN);
			fclose($f);
		}
	}
}

// -------------------------------------------------------------------
// Others
// -------------------------------------------------------------------

function sendmail($email, $subject, $message, $reply = '', $from = '') {
	global $Config;
	if (!$reply) $reply = $Config['Administrator'];
	if (!$from) $from = $Config['Robot'];
	return @mail($email, $subject, $message, "From: $from\r\nReply-To: $reply\r\n");
}

function error($message, $prefix = "\t") {
	global $Config, $Lang;
	echo "$prefix<p />\n$prefix<font class=error>" . $Lang['Error'] . ":</font> $message!\n";
	if ($Config['Administrator']) echo "$prefix&nbsp;\n$prefix<a href=\"mailto:" . $Config['Administrator'] . "\">" . $Lang['SendReport'] . "</a>\n";
	echo "$prefix<br />\n\n";
}

// -------------------------------------------------------------------
// Globals
// -------------------------------------------------------------------

$action = getvar('action');
$back = getvar('back');

if (!isset($view)) $view = getvar('view');
if (!isset($page)) $page = abs(getvar('page'));

$TIMESTAMP = $timestamp = date('YmdHis');
$RID = $rid = substr(md5(Rand(11111, 99999).time()), 16);

setcookie('RID', $RID, time()+3600);

// -------------------------------------------------------------------
// External configuration from database
// -------------------------------------------------------------------

if (@$Database) {
	include(@$ROOT."include/db.php");
	if (!@$db && !@$Config['Internal'] && $Config['Debug']) die('<b>Error</b>: Connection to database failed!');
	if (@$db && $db->query("SELECT * FROM ${prefix}config;")) while ($t = $db->fetchrow()) $Config[$t['config_key']] = $t['config_value'];
	if (@$Config['Messages']) include(@$ROOT."include/messages.php");
}
else {
	@include("{$ROOT}include/users.php");
}

// -------------------------------------------------------------------
// Other stuff
// -------------------------------------------------------------------

if (@$Config['Sessions']) session_start();

// -------------------------------------------------------------------
// Includes
// -------------------------------------------------------------------

if ($Config['Debug']) {
	require("{$ROOT}include/auth.php");
	require("{$ROOT}include/locale.php");
}
else {
	@include("{$ROOT}include/auth.php");
	@include("{$ROOT}include/locale.php");
}

locale('locale');
locale('messages');
locale('custom');

if ($Config['Debug']) {
	include("{$ROOT}include/emoticons.php");
	include("{$ROOT}include/modules.php");
}
else {
	@include("{$ROOT}include/emoticons.php");
	@include("{$ROOT}include/modules.php");
}

@include("{$ROOT}include/custom.php");

@include("{$ROOT}include/library.php");

define('__COMMON_PHP__', 1);

}
