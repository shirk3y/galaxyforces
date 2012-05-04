<?php

require('include/header.php');

locale('website/welcome');

tablebegin('Galaxy Forces', 500);

?>	<br />
	<table width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top"><td width="12">&nbsp;</td><td align="center">
		<?php echo $Lang['WelcomePage']; ?>
	</td><td width="12">&nbsp;</td><td align="center" width="168">
<?php
	tableimg('images/pw.gif', 72, 72, "gallery/space/icons/prophetie.jpg", 64, 64, '', 'right');
?>
	</td><td width="12">&nbsp;</td></tr></table>
	<br />
<?php
	tablebreak();
	subbegin();

	$date = date('Ymd');
	$db->query("SELECT login from galaxy_users where registered='$date' ORDER BY id DESC;");
	if ($registered = $db->numrows()) {
		$t = $db->fetchrow();
		$last = $t['login'];
	}
	else {
		$registered = 0;
		$last = '';
	}

	echo "\t\t" . '<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0"><tr>';
	echo '<td><b>' . $Lang['RegisteredToday'] . '</b>: <font class="result">' . $registered . '</font></td><td align="right">';
	echo $last ? '<b>' . $Lang['RegisteredLast'] . '</b>: <font class="plus">' . $last . '</font>' : '&nbsp;';
	echo '</td></tr></table>';

	subend();
	tablebreak();
	subbegin();

?>	If you are looking for any <font class="warning">tips</font> or <font class="warning">manual</font> you should browse <a href="http://galaxy.game-host.org/forum">forum</a> zone before making any questions on public chat ;-))) Eh, and as always: "still under development"...<br />
	<br />
	In case you enjoy this game you can also join the <a href="GALAXYCREW">GF Crew</a> via special admin site on our forum!<br />
	<br />
	If you play this game and want to know what upgrades were last added to the game look to the <a href="news.php">news</a> section.<br />
<?php
	subend();
	tablebreak();
	subbegin();

?>	<font class="capacity">Znajd¼ nas w sieci ircnet (<a href="irc://poznan.ircnet.pl/galaxy">poznan.ircnet.pl</a>, <a href="irc://lublin.ircnet.pl/galaxy">lublin.ircnet.pl</a>) na kanale <font class="result">#galaxy</font>!</font><br />
	<br />
	Je¶li potrzebujesz <font class="warning">wskazówek</font> lub <font class="warning">instrukcji</font> zajrzyj na <a href="http://galaxy.game-host.org/forum">forum</a> przed zadawaniem pytañ na publicznym czacie ;-))) I jak zawsze "wci±¿ wersja rozwojowa"...<br />
	<br />
	Je¶li spodoba³a ci siê gra, mo¿esz do³±czyæ do nas. zajrzyj na <a href="http://galaxy.game-host.org/forum/index.php?c=5&sid=0331a008064b0ba1d90c9210bbc2b5d9"> administracyjn± sekcjê</a> forum.<br />
<?php
	subend();
	tableend('Galaxy Forces', 500);

	require('include/footer.php');
