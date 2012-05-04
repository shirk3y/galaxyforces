<?php

// ===========================================================================
// Database {db.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	2.1
//	Created:	2004-03-31
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// erik dot dobecky at NOSPAM dot fi-us dot com
// 30-Apr-2005 12:15 
// A useful way that we have developed filters against SQL injection attempts is to preg_grep the $_REQUEST global with the following regular expression (regex):
// 
//  '/[\'")]* *[oO][rR] *.*(.)(.) *= *\\2(?:--)?\\1?/'
// 
// which is used simply as:
//
// $SQLInjectionRegex = '/[\'")]* *[oO][rR] *.*(.)(.) *= *\\2(?:--)?\\1?/';
// $suspiciousQueryItems = preg_grep($SQLInjectionRegex, $_REQUEST);
// 
// which matches any of the following (case insensitive, a=any char) strings (entirely):
// 
// ' or 1=1--
// " or 1=1--
// or 1=1--
// ' or 'a'='a
// " or "a"="a
// ') or ('a'='a 
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of phpSynapse project. See documentation for details.
// ===========================================================================

if (!defined('__DATABASE__')) {

// ---------------------------------------------------------------------------
// Functions
// ---------------------------------------------------------------------------

function availabledrivers()
{
	global $ROOT;
	$dh = opendir("{$ROOT}db");
	while (false !== ($filename = readdir($dh))) {
		if ($ext = substr($filename, 1+strrpos($filename, '.')) == 'php') {
			$files[] = $filename;
		}
	}
	sort($files);
	foreach ($files as $file) {
		$CLASSNAME = '';
		$SUPPORTS = array();
		@include("{$ROOT}db/$file");
		$drivers[substr($file, 0, -4)] = array('classname'=>$CLASSNAME,'supports'=>$SUPPORTS);
	}
	return $drivers;
}

function availablelayers($drivers=array())
{
	if (!$drivers) $drivers = availabledrivers();
	foreach ($drivers as $name => $driver) foreach ($driver['supports'] as $layer) if ($layer) $layers[$layer][] = $name;
	return $layers;
}

function sql_parse($filename, $prefix='', $layer='', $source='')
{
	$s = @file_get_contents($filename);
	$s = str_replace('TABLE #', "TABLE $prefix", $s);
	$s = str_replace('FROM #', "FROM $prefix", $s);
	$s = str_replace('INTO #', "INTO $prefix", $s);
	$s = ereg_replace('--([ A-Za-z:0-9.]{0,})', '', $s);
	return $s;
}

function sql_explode($sql)
{
	$queries = array();
	$b=0;
	$q='';
	for ($i=0; $i<strlen($sql); $i++) {
		switch($sql{$i}) {
		case '`': if ($q == '`') $q = ''; elseif (!$q) $q = '`'; break;
		case "'": if ($q == "'") $q = ''; elseif (!$q) $q = "'"; break;
		case '"': if (@$sql{$i-1} != '\\' && $q == '"') $q = ''; elseif (!$q) $q = '"'; break;
		case '(': if (!$q) $b++; break;
		case ')': if (!$q) $b--; break;
		case ';': if (!($b || $q)) {
			$queries[] = trim(substr($sql, 0, ++$i));
			$sql = substr($sql, $i);
			$i = 0;
		}
		}
	}
	return $queries;
}

function sql_schema($name, $dir='')
{
	global $ROOT, $prefix, $layer;
	if (!$dir) $dir="{$ROOT}/sql/";
	if (!is_dir($dir)) $dir=$ROOT;
	if ($layer && file_exists("{$dir}{$name}-{$layer}.sql")) $schema = "{$dir}{$name}-{$layer}.sql"; else $schema = "{$dir}{$name}.sql";
	return sql_explode(sql_parse($schema, $prefix, $layer));
}

function str_sqlsafe($s)
{
	return trim(!get_magic_quotes_gpc()?addslashes($s):$s);	
}

define('__DATABASE__', 1);

}

// ---------------------------------------------------------------------------
// Connect
// ---------------------------------------------------------------------------

if (@$Database && !isset($db)) {
	$layer = @$Database['layer'];
	$prefix = @$Database['prefix'];
	$driver = !empty($Database['driver']) ? $Database['driver'] : $layer ? $layer : 'null';
	include(@$ROOT."db/{$driver}.php");
	if (class_exists($CLASSNAME))
	{
		$db = new $CLASSNAME($Database);
		if (!$db->connect()) {
			$Errors[] = $db->error();
			unset($db);
		}
		if (@$Config['Security']) {
			unset($Database['password']);
			if (!is_object($db)) unset($db->password);
		}
	}
}
