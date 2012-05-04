<?php

// ===========================================================================
// Modules {modules.php)
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.4
//	Created:	2004-08-12
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Synapse project (http://phpsynapse.sourceforge.net).
// You may only use, modify, copy or distribute this content under the terms
// of GNU General Public License (GPL) or Synapse Artistic Licence (SAL).
// See LINCENSE file for details.
// =========================================================================== 

if (!defined('__MODULES__')) {

function availablemodules()
{
	global $ROOT;
	$path = "{$ROOT}modules";
	$dh = opendir($path);
	while (false !== ($name = readdir($dh))) if (strpos($name, '.') === false && is_dir("$path/$name")) $names[] = $name;
	if (@$names) {
		sort($names);
		foreach ($names as $name) {	
			$modules[$name] = array(
				'install' => file_exists("$path/$name/install.php"),
				'uninstall' => file_exists("$path/$name/uninstall.php"),
				'include' => file_exists("$path/$name/include.php"),
				'box' => file_exists("$path/$name/box.php"),
				'page' => file_exists("$path/$name/page.php"),
			);
		}
		return $modules;
	}
	else return false;
}

function module($name, $section = 'index')
{
	global $ROOT, $Config, $Debug, $Lang, $Config;
	if (file_exists("{$ROOT}modules/$name/$section.php")) {
		if (@$Config['Debug'] && @$Config['Verbose']) $Debug[] = "Loading external module \"$name\" (using section \"$section\")";
		require("{$ROOT}modules/$name/$section.php");
	}
	elseif (@$Config['Debug'] && @$Config['Verbose']) $Debug[] = "Failed to include section \"$section\" from module \"$name\"";
}

function modules($list=array(), $section='include')
{
	global $User, $Modules, $logged;
	if (!$list) $list = @$Modules;
	if ($list) {
		$me = fname($_SERVER['PHP_SELF']);
		foreach ($list as $name => $module) {
			if (is_array($module)) {
				if (isset($module['enabled']) && !$module['enabled']) continue;
				elseif (@$module['registered'] && !$logged) continue;
				elseif (isset($module['pages'])) {
					$allowed = 0;
					if (! is_array($pages = $module['pages'])) $pages = explode(',', $module['pages']);
					for ($i = 0; ($i < count($pages)) && !$allowed; $i++) {
						if ($page = strtolower(trim($pages[$i]))) {
							if (($page[0] == '!') && ($page = trim(substr($page, 1))) && ($me == $page)) break;
							elseif ($page == '*' || $page == $me) $allowed++;
						}
						else $allowed++;
					}
					if (!$allowed) continue;
				}
				if (@$module['name']) $name = $module['name'];
				module($name, $section);
			}
			else module($module, $section);
		}
	}
}

if (@$db && $db->query("SELECT * FROM {$prefix}modules WHERE module_enabled=1 ORDER BY module_order;")) {
	while ($r = $db->fetchrow() and $m = $r['module_name']) {
		$allowed = true;
		for ($t = explode(',', @$r["module_groups"]), $i = 0; $i < count($t); $i++) {
			$allowed = false;
			$g = trim($t[$i]);
			if ($g == '' || $g == @$User['usergroup']) { $allowed = true; break; }
			if ($g == '!' && !@$User['usergroup']) { $allowed = true; break; }
			if ($g[0] == '!' && $User['usergroup'] == trim(substr($g, 1))) break;
			if ($g == '*') { $allowed = @$User['usergroup']; break; }
		} 
		if (!$allowed) continue;
		foreach (array('name', 'enabled', 'version', 'section', 'pages') as $f) $Modules[$m][$f] = @$r["module_$f"];
	}
}

if (is_array(@$Modules)) {
	foreach ($Modules as $key => $module) {
		if (@$module['section']) $Sections[$module['section']][] = $key;
	}
}

modules();

define('__MODULES__', TRUE);

}
