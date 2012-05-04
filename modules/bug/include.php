<?php

global $Config;
global $Sections;
global $Lang;
global $User;

locale('module/bug', $Config['Language']);

if (!isset($Config[$key="module.bug.group"])) $Config[$key]="wheel";

if (in_array($Config[$key],(array)$User["usergroup"]))
{
	if (isset($Config[$key="module.bug.section"])) $Sections[$Config[$key]][] = "bug";
}
