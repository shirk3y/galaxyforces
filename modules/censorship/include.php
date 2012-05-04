<?php

global $ROOT, $Censorship, $Swearword;

function import_censorship_data($file)
{
	global $Censorship, $Swearword;
	if (!count($a=explode("\n", @file_get_contents($file)))) return;
	for ($i=0; $i<count($a); $i+=2) {
		if (!$k=trim($a[$i])) continue;
		if (!$v=trim(@$a[$i+1])) $Swearword[]=$k;
		else $Censorship[$k]=$v;
	}
}

function censorship(&$str)
{
	global $Censorship, $Swearword;
	$str = str_ireplace($Swearword, '@%#', $str, $count);
	$str = str_ireplace(array_keys($Censorship), array_values($Censorship), $str);
	return $count == 0;
}

locale("module/censorship");

import_censorship_data($ROOT."CENSORSHIP.txt");
