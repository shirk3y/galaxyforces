#!/usr/bin/php
<?php

# Field index
# Author: zoltarx

$LOGIN=3;
$EXP=20;

if ( ($f = fopen($argv[1], "r")) || die("Nie mogê otworzyæ ${argv[1]}!") )  {
	flock($f, LOCK_SH);

	while (! feof($f)) {

	if (
		($t = fgets($f, 1024)) &&
		($t = explode('INSERT INTO galaxy_users VALUES (', $t)) &&
		(isset($t[1])) &&
		($t = explode(');', $t[1])) &&
		(isset($t[0])) &&
		($t = explode(',', $t[0])) &&

		($login = $t[$LOGIN]) &&
		($exp = $t[$EXP]) &&
		($exp > 2000000000) &&
		($exp = round($exp * 1.2))
	) 
	echo "UPDATE galaxy_users SET exp=$exp WHERE login=$login;\n";

	}	

	flock($f, LOCK_UN);
	fclose($f);
}

?>