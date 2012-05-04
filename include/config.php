<?php

## ===========================================================================
## Configuration {config.php}
## ===========================================================================

## ---------------------------------------------------------------------------
## General
## ---------------------------------------------------------------------------

# $Config['Debug'] = FALSE;
$Config['Debug'] = TRUE;
$Config['Logins'] = TRUE;
$Config['Registration'] = 'auto'; // [ 'auto', 'email', 'admin' ]
$Config['AuthType'] = 'cookie'; // [ 'cookie', 'session', 'http' ]
$Config['Style'] = 'galaxy';
$Config['Stylesheet'] = 'style/local.css';
$Config['IgnoreUserStyle'] = TRUE;
$Config['DefaultLanguage'] = 'en';
#$Config['Language'] = 'en';
#$Config['LoginTime'] = 20 * 60;
$Config['AuthPage'] = "control.php";
$Config['Refresh'] = 0;	// 15 * 60;
$Config['Title'] = 'Galaxy Forces Project';
$Config['Generator'] = 'Galaxy Website Engine';
$Config['Author'] = 'zoltarx';
$Config['Description'] = 'Galaxy Forces Project';
$Config['Keywords'] = 'galaxy,forces,opensource,project';
$Config['Cache'] = 'cache';
$Config['Disclaimer'] = '&copy; <a href="http://en.wikipedia.org/wiki/Copyleft">Copyleft</a> by <a href="http://galaxy.alyx.pl/crew.php">Crew</a>';
$Config['Administrator']='';
$Config['Bugs'] = 'bugs@localhost?Subject=Bug%20report';
$Config['Robot'] = 'robot@localhost';
$Config['AccessControl'] = FALSE;
$Config['Policy'] = 'accept';	// [ accept | deny ]
$Config['AllowIP'] = array('127.0.0.1', '10.0.0.1');
$Config['DenyIP'] = '';
$Config['MessageLife'] = 100; // days after message dies
# $Config['Logging'] = FALSE;
$Config['Logging'] = TRUE;
$Config['LogPath'] = 'log/';
$Config['MaxLogSize'] = 16777216; // 16M

## ---------------------------------------------------------------------------
## Database
## ---------------------------------------------------------------------------

$Database['layer'] = 'mysql';
$Database['type']='mysql';
$Database['host']='localhost';
$Database['user']='galaxy';
$Database['password']='';
$Database['name']='galaxy_053';
$Database['prefix']='galaxy_';
$Database['persistent']=false;
$Database['charset']='UTF-8';

## ---------------------------------------------------------------------------
## Scripts
## ---------------------------------------------------------------------------

$JS[]="core";
$JS[]="topframe";
$JS[]="local";

## ---------------------------------------------------------------------------
## Groups
## ---------------------------------------------------------------------------

$Config['Moderators'] = 'moderators';
$Config['Administrators'] = 'wheel';
$Config['Forum'] = 'forum';
$Config['JailChief'] = 'jailchief';

## ---------------------------------------------------------------------------
## Modules
## ---------------------------------------------------------------------------

$Modules[]='galaxy';
$Modules[]='chat';
$Modules[]='online';
$Modules[]='censorship';
$Modules[]='bug';
$Modules[]='gg';
$Modules[]='tracker';

$Config["module.bug.section"] = "bottom";

$Config['module.galaxy.thicklength'] = 300;

#$Config['module.tracker.ga'] = 'UA-XXXXX-X';
#$Config['module.tracker.ga-async'] = true;

$Config['module.gg.user'] = '';
$Config['module.gg.secret'] = '';
$Config['module.gg.description'] = '';
$Config['module.gg.forward'] = false;

## ---------------------------------------------------------------------------
## Media
## ---------------------------------------------------------------------------

$Media['_'] = 'media/';	// relative path to media
$Media['+'] = 'galaxy/'; // subdirectory added to the end of path

$Media['icon'] = 'icon/'; // full path will be media/icon/galaxy/ and media/icon/ if not found

## ---------------------------------------------------------------------------
## Menu
## ---------------------------------------------------------------------------

$Menu[]=array(
	'$'=>'welcome',
	'*'=>'-',
	'_'=>'MenuWelcome',
);
$Menu[]=array(
	'$'=>'news',
	'*'=>'-',
	'_'=>'MenuNews',
);
$Menu[]=array(
	'$'=>'login',
	'*'=>'-',
	'_'=>'MenuLogin',
);
$Menu[]=array(
	'$'=>'register',
	'*'=>'-',
	'_'=>'MenuRegister',
);
$Menu[]=array(
	'$'=>'admin',
	'*'=>'@',
	'_'=>'MenuAdministration',
);
/*
$Menu[]=array(
	'$'=>'hq',
	'*'=>'+',
	'_'=>'MenuHeadquarters',
);
*/
$Menu[]=array(
	'$'=>'messages',
	'*'=>'+',
	'_'=>'MenuMessages',
);
$Menu[]=array(
	'$'=>'ads',
	'*'=>'+',
	'_'=>'MenuAds',
);
$Menu[]=array('$'=>'-','*'=>'+'); // break, authenticated
$Menu[]=array(
	'$'=>'control',
	'*'=>'+',
	'_'=>'MenuControl',
);
$Menu[]=array(
	'$'=>'equipment',
	'*'=>'+',
	'_'=>'MenuEquipment',
);
$Menu[]=array(
	'$'=>'galaxy',
//	'*'=>'+',
	'*'=>'',
	'_'=>'MenuGalaxyMap',
);
$Menu[]=array(
	'$'=>'clan',
	'*'=>'%',
	'_'=>'MenuClan',
);
$Menu[]=array('$'=>'-','*'=>'+'); // break, authenticated
$Menu[]=array(
	'$'=>'colony',
	'*'=>'+',
	'_'=>'MenuColony',
);
$Menu[]=array(
	'$'=>'structures',
	'*'=>'#',
	'_'=>'MenuStructures',
);
$Menu[]=array(
	'$'=>'units',
	'*'=>'#',
	'_'=>'MenuUnits',
);
$Menu[]=array('$'=>'-','*'=>'#'); // break, colony
$Menu[]=array(
	'$'=>'explore',
	'*'=>'#',
	'_'=>'MenuExplore',
);
$Menu[]=array(
	'$'=>'research',
	'*'=>'#',
	'_'=>'MenuResearch',
);
$Menu[]=array(	'$'=>'build',		'*'=>'#',	'_'=>'MenuBuild',		);
$Menu[]=array(	'$'=>'production',	'*'=>'#',	'_'=>'MenuProduction',	);
$Menu[]=array(	'$'=>'attack',	'*'=>'#',	'_'=>'MenuAttack',		);

$Menu[]=array(	'$'=>'-',		'*'=>'+',					);

$Menu[]=array(	'$'=>'whois',		'*'=>'+',	'_'=>'MenuWhois',		);
$Menu[]=array(	'$'=>'highscores',	'*'=>'+',	'_'=>'MenuHighScores',	);
$Menu[]=array(	'$'=>'profile',	'*'=>'+',	'_'=>'MenuProfile',		);

$Menu[]=array(	'$'=>'-',		'*'=>'',					);

$Menu[]=array(	'$'=>'propaganda',	'*'=>'-',	'_'=>'MenuPropaganda',	);
$Menu[]=array(	'$'=>'links',		'*'=>'-',	'_'=>'MenuLinks',		);
/*
$Menu[]=array(
	'$'=>'tutorial',
	'*'=>'+',
	'_'=>'MenuTutorial',
);
*/
/*
$Menu[]=array(
	'$'=>'regulations',
	'*'=>'+',
	'_'=>'MenuRegulations',
);
*/
$Menu[]=array(
	'$'=>'wiki',
	'*'=>'*',
	'_'=>'wiki',
	'@'=>'http://wiki.galaxy.alyx.pl/',
);
/*
$Menu[]=array(
	'$'=>'forum',
	'*'=>'*',
	'_'=>'MenuForum',
);
*/
$Menu[]=array(
	'$'=>'irc',
	'*'=>'*',
	'_'=>'MenuIRC',
	'@'=>'http://www.ircnet.pl/denora/?m=c&p=status&chan=%23galaxy',
);
$Menu[]=array(
	'$'=>'contact',
	'*'=>'-',
	'_'=>'MenuContact',
);
$Menu[]=array('$'=>'-','*'=>'+'); // break, authenticated
$Menu[]=array(
	'$'=>'logout',
	'*'=>'+',
	'_'=>'MenuLogout',
	'@'=>'welcome.php?action=logout'
);
