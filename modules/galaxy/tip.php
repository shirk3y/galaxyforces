<?php

global $prefix, $db, $language;

$db->query("SELECT * FROM ${prefix}tips WHERE locale='$language';");

if ($n = $db->numrows()) {
	for ($i = 0; $i < $m = Rand(1, $n); $i++) $t = $db->fetchrow();
	echo "\t\t<br /><font class=\"tip\">${t['tip']}</font><br /><br />\n";
}
