<?php

$auth = false;
require("include/locale.php");

$Config['Debug'] = 0;
$Config['Internal'] = 1;

require("include/common.php");

if ($Config['Language'] == 'pl') {
	$Lang['user'] = 'gracz';
	$Lang['pass'] = 'hasło';
	$Lang['enta'] = 'wejście';
	$Lang['forum'] = 'przejrzyj forum';
	$Lang['enter'] = 'Galaxy Forces';
	$Lang['sse'] = 'Solar System Edition';
	$Lang['rzulw'] = 'Wielka Rzulwica';
	$Lang['irc'] = 'kanał komunikacyjny';
}
else {
	$Lang['user'] = 'user';
	$Lang['pass'] = 'pass';
	$Lang['enta'] = 'enta';
	$Lang['forum'] = 'jump to forum zone';
	$Lang['enter'] = 'Galaxy Forces';
	$Lang['sse'] = 'Solar System Edition';
	$Lang['rzulw'] = 'Wielka Rzulwica';
	$Lang['irc'] = 'communication channel';
}

?><html>
<head>
	<title>[ Galaxy Forces ]</title>
	<meta name="pragma" content="cache" />
	<meta name="author" content="zoltarx" />
	<meta name="description" content="Galaxy Forces A Free MMORPG Game" />
	<meta name="keywords" content="galaxy,forces,credits,metal,energy,mmorpg,synapse,shop,phpsynapse,synapse for php,colony,ben,angus,onion,online game,browser based" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="style/galaxy/style.css" />
	<link rel="shortcut icon" href="favicon.ico" />
</head>


<body bgcolor="black">

<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tr height="100">
<td>
	<table id="top" width="100%" cellspacing="0" cellpadding="0">
	<tr height="80" valign="middle">
	<td>
	<center>
	<acronym title="Galaxy Forces"><div id="logo" alt="Galaxy Forces"></div></acronym></td>
	</center>
	</td>
	</tr>
	</table>
</td>
</tr>

<tr height="20">
<td>
	<table id="belt" border="0" cellpadding="0" cellspacing="0" height="24" width="100%">
	<tr height="20" valign="middle">
	<td background="images/b1-bg.gif"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b2-left.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td align="center" background="images/b2-bg.gif" width="72">
		<a class="plus" href="link/forum">FORUM</a></font>
	</td><td background="images/b2-right.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b1-bg.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b2-left.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td align="center" background="images/b2-bg.gif" width="150">
		<a class="capacity" href="welcome.php"><img src="images/icon_gf.gif" border="0" vspace="0" align="left" /><?php echo $Lang['enter']; ?></a></font>
	</td><td background="images/b2-right.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b1-bg.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b2-left.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td align="center" background="images/b2-bg.gif" width="150">
		<a class="work" href="http://wiki.rgk.alyx.pl/index.php/GFAFFE"><img src="images/icon_affe.gif" border="0" vspace="0" align="left" /><?php echo $Lang['rzulw']; ?></a></font>
	</td><td background="images/b2-right.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b1-bg.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b2-left.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td align="center" background="images/b2-bg.gif" width="150">
		<a class="result" href="http://wiki.rgk.alyx.pl/index.php/SSE"><img src="images/icon_sse.gif" border="0" vspace="0" align="left" /><?php echo $Lang['sse']; ?></a></font>
	</td><td background="images/b2-right.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b1-bg.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b2-left.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td align="center" background="images/b2-bg.gif" width="72">
		<a class="minus" href="irc">IRC</a></font>
	</td><td background="images/b2-right.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b1-bg.gif"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td>
	</tr>
	</table>
</td>
</tr>

<tr valign="middle">
<td>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,42,0" id="sound2" width="1" height="1">
	<param name="movie" value="sounds/sound2.swf"><param name="quality" value="high"><param name="bgcolor" value="#000000">
	<embed name="sound1" src="sounds/sound2.swf" quality="high" bgcolor="#000000" swLiveConnect="true" width="1" height="1" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
	</object>

<center>

	<table align="center" border="0" cellpadding="0" cellspacing="0" width="328">
	<tr height="4" valign="top"><td width="4"><img src="images/table-topleft.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td><td background="images/table-top.gif"><img src="images/0.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td><td width="4"><img src="images/table-topright.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td></tr>
	<tr valign="middle">
	<td background="images/table-left.gif" width="4"><img src="images/0.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td>
	<td align="center" width="100%">

<?php switch (rand(1, 3)) { ?>
<?php case 1: ?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,42,0" id="intro1" width="320" height="200">
		<param name="movie" value="flash/intro1.swf"><param name="quality" value="high"><param name="bgcolor" value="#000000">
		<embed name="intro1" src="flash/intro1.swf" quality="high" bgcolor="#000000" swLiveConnect="true" width="320" height="200" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		</object>
<?php break; ?>
<?php case 2: ?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,42,0" id="intro1" width="320" height="200">
		<param name="movie" value="flash/intro2.swf"><param name="quality" value="high"><param name="bgcolor" value="#000000">
		<embed name="intro1" src="flash/intro2.swf" quality="high" bgcolor="#000000" swLiveConnect="true" width="320" height="200" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		</object>
<?php break; ?>
<?php case 3: ?>

		<object classid="CLSID:05589FA1-C356-11CE-BF01-00AA0055595A" width="320" height="200">
		<param name="FileName" value="movies/war.avi" /><param name="ShowDisplay" value="0" /><param name="ShowControls" value="0" /><param name="AutoStart" value="1" /><param name="PlayCount" value="1" /><param name="MovieWindowWidth" value="320" /><param name="MovieWindowHeight" value="200" />
		<embed src="movies/war.avi" width="320" height="200" autostart="true" loop="true" />
		</object>
<?php break; ?>
<?php } ?>

	</td>
	<td background="images/table-right.gif" width="4"><img src="images/0.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td>
	</tr>








	<tr height="4" valign="bottom"><td width="4"><img src="images/table-bottomleft.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td><td background="images/table-bottom.gif"><img src="images/0.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td><td width="4"><img src="images/table-bottomright.gif" alt="" border="0" height="4" hspace="0" vspace="0" width="4"></td></tr>

	</table>

</center>

	<br />

</td>
</tr>
<?php
/*

// PRELOADING IMAGES

<tr valign="bottom">
<td>
	<center>
<?php

$files = '';

function rd($path) {
	global $files;
	if ($d = opendir($path)) {
		while ($i = readdir($d)) {
			if ($i == ".." || $i == ".") continue;
			if (is_dir("$path/$i")) rd("$path/$i");
			else $files[] = "$path/$i";
		}
		closedir($d);
	}
}

rd('images');
rd('gallery');

foreach($files as $n) echo "\t<img src=\"$n\" width=\"16\" height=\"16\" />\n";

?></td>
</tr>

*/

?>


<tr height="20">
<td>
	<table id="belt" border="0" cellpadding="0" cellspacing="0" height="24" width="100%">
	<tr height="20" valign="middle">
	<td background="images/b1-bg.gif"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0"></td><td background="images/b2-left.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td align="center" background="images/b2-bg.gif" width="300">

		<form action="control.php" method="POST">
		<input type="hidden" name="action" value="login" />
		<input type="hidden" name="confirm" value="<?php echo $secret; ?>" />
		<font class="capacity"><?php echo $Lang['user']; ?>:</font> <input type="text" name="login" size="10" />
		<font class="minus"><?php echo $Lang['pass']; ?>:</font> <input type="password" name="password" size="10" />
		<input type="submit" value="<?php echo $Lang['enta']; ?>" />
		</font>
		</form>

	</td><td background="images/b2-right.gif" width="12"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0" width="12"></td><td background="images/b1-bg.gif"><img src="images/0.gif" alt="" border="0" height="24" hspace="0" vspace="0"></td>
	</tr>
	</table>
</td>
</tr>

<tr height="80" valign="middle">
<td background="images/top.gif">
<center>
<?php echo @file_get_contents("STATISTICS.txt"); ?>
</center>
</td>
</tr>





</table>

</body>
</html>
