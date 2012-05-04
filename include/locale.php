<?php

// ===========================================================================
// Language {locale.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	2.4
//	Created:	2004-03-31
//	Modified:	2005-09-11
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
//
// This is a localisation module that loads the $Lang array.
// It provides you locale functions that allow your site to use separate
// locale sets, they should be defined in locale/{preffered_language}.
//
// This sets the default language to use in case it was not recognized or
// user is not logged in.
//
//	$Config['DefaultLanguage']='en';
//
// You may also allow to use only one language:
//
//	$Config['Language']='en';
//
// You need at least define locale for messages which is the default.
// For your own you may want to define custom locale which will be included
// as well.
//
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// ===========================================================================

if (! defined('__LOCALE_PHP__')) {

if (!isset($Config['DefaultLanguage'])) $Config['DefaultLanguage'] = 'en';

function locales()
{
	global $ROOT;
	$dh = opendir("{$ROOT}locale");
	while (false !== ($name = readdir($dh))) if (is_dir("{$ROOT}locale/$name") && file_exists("{$ROOT}locale/$name/locale.php")) $locales[] = $name;
	sort($locales);
	foreach ($locales as $locale) {
		@include("{$ROOT}locale/$locale/locale.php");
		if (isset($Lang['Language'])) {
			$result[$locale] = $Lang['Language'];
			unset($Lang['Language']);
		}
	}
	return $result;
}

function locale($name, $language='')
{
	global $Config, $Lang, $ROOT;
	$Prev = $Lang;
	if (!$language) $language = $Config['Language'];
	if (file_exists("{$ROOT}locale/$language/$name.php")) @include("{$ROOT}locale/$language/$name.php");
	else @include("{$ROOT}locale/{$Config['DefaultLanguage']}/$name.php");
	return $Prev;
}

function discover_language($accept="")
{
	global $Config;
	$path=isset($Config['LocalePath'])?$Config['LocalePath']:"locale";
	if (!$accept && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $accept=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
	if (!$accept || !$search=explode(';', $path)) return $Config['DefaultLanguage'];
	$tab=$alt=array();
	foreach (explode(',', $accept) as $chunk) {
		$x=explode(";", $chunk);
		$tab[]=$chunk=$x[0];
		$x=explode("-", $chunk);
		if (@$x[1]) $alt[]=$x[0];
	}
	foreach (array_unique(array_merge($tab, $alt)) as $lc)
		foreach ($search as $dir) if ($dir!='' && @is_dir("$dir/$lc"))
			return $lc;
	return $Config['DefaultLanguage'];
}

function lang($text)
{
	global $Lang;
	return empty($Lang[$text]) ? $text : $Lang[$text];
}

function __($text)
{
	echo lang($text);
}

define('__LOCALE_PHP__', 1);

}

if (!isset($Config['Language']) || $Config['Language'] == "auto") $Config['Language'] = discover_language();
