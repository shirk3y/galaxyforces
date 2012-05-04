<body<?php if (!@$Config['IgnoreFrames']) { ?> onLoad="if (top.frames.length>0) top.location.href=self.location.href;"<?php } ?>>

<table class="overall" cellspacing="0" cellpadding="0">
<tr id="top">
<td background="images/top.gif">
	<table class="overall" cellspacing="0" cellpadding="0">
	<tr height="80" valign="middle">
	<td width="200"><img id="logo" src="images/logo.gif" alt="Galaxy Forces" /></td>
	<td align="center">&nbsp;<?php echo @file_get_contents("{$ROOT}BANNER.txt"); ?>&nbsp;</td>
<?php

if ($logged && $Player['planet']) {

?>	<td width="60" align="right">
		<?php /*
			if($Player['dad']) {
				echo "<font class='error' size=3><b>$Lang[DeletionAccount] " . substr($Player[dad], 0, 4) . "-" . substr($Player[dad], 4, 2) . "-" . substr($Player[dad], 6, 2) . " " . substr($Player[dad], 8, 2) . ":" . substr($Player[dad], 10, 2) . ":" . substr($Player[dad], 12, 2)."</b></font>";
			}
		*/ ?>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="46" height="16" id="zegar" align="middle">
		<param name="allowScriptAccess" value="sameDomain" /><param name="movie" value="flash/zegar.swf" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="scale" value="noborder" /><param name="wmode" value="transparent" /><param name="bgcolor" value="#000000" />
		<embed src="flash/zegar.swf" menu="false" quality="high" scale="noborder" wmode="transparent" bgcolor="#000000" width="46" height="16" name="zegar" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>
	</td>
	<td width="72" align="right">
		<table id="pw" background="images/pw.gif" cellspacing="0" cellpadding="0"><tr><td><a href="galaxy.php?galaxy=<?php echo $Player['galaxy']; ?>&object=<?php echo $Player['planet']; ?>"><img src="gallery/space/icons/<?php echo $Player['planet']; ?>.jpg" alt="<?php echo $Player['planet']; ?>" width="64" height="64"></a></center></td></tr></table>
	</td>
<?php
}
?>	<td width="12">&nbsp;</td>
	</tr>
	</table>
</td>
</tr>
<tr id="belt">
<td>
	<table id="belt" width="100%" height="24" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr>
	<td background="images/b1-bg.gif"><img class="spacer" src="images/0.gif"></td>
<?php

if ($logged && $auth) {
	if (@$Colony) switch ($Planet['technology']) {
		case 'tron':
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Energy'].' [E]"><img class="icon" src="images/energy.jpg" align="left" alt="[E]"></acronym>&nbsp;'.($Colony['energy'] > $Colony['energycapacity'] ? '<font class="work">' . strdiv($Colony['energy']) . '</font>' : strdiv($Colony['energy'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Silicon'].' [S]"><img class="icon" src="images/silicon.jpg" align="left" alt="[S]"></acronym>&nbsp;'.($Colony['silicon'] > $Colony['siliconcapacity'] ? '<font class="work">'.strdiv($Colony['silicon']).'</font>' : strdiv($Colony['silicon'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Metal'].' [M]"><img class="icon" src="images/metal.jpg" align="left" alt="[M]"></acronym>&nbsp;'.($Colony['metal'] > $Colony['metalcapacity'] ? '<font class="work">'.strdiv($Colony['metal']).'</font>' : strdiv($Colony['metal'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Plutonium'].' [P]"><img class="icon" src="images/plutonium.jpg" align="left" alt="[P]"></acronym>&nbsp;'.($Colony['plutonium'] > $Colony['plutoniumcapacity'] ? '<font class="work">' . strdiv($Colony['plutonium']) . '</font>' : strdiv($Colony['plutonium'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Deuterium'].' [D]"><img class="icon" src="images/deuterium.jpg" align="left" alt="[D]"></acronym>&nbsp;'.($Colony['deuterium'] > $Colony['deuteriumcapacity'] ? '<font class="work">' . strdiv($Colony['deuterium']) . '</font>' : strdiv($Colony['deuterium'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			break;

		case 'tron':
		case 'human':
		default:
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Energy'].' [E]"><img class="icon" src="images/energy.jpg" align="left" alt="[E]"></acronym>&nbsp;'.($Colony['energy'] > $Colony['energycapacity'] ? '<font class="work">' . strdiv($Colony['energy']) . '</font>' : strdiv($Colony['energy'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Metal'].' [M]"><img class="icon" src="images/metal.jpg" align="left" alt="[M]"></acronym>&nbsp;'.($Colony['metal'] > $Colony['metalcapacity'] ? '<font class="work">'.strdiv($Colony['metal']).'</font>' : strdiv($Colony['metal'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Uran'].' [U]"><img class="icon" src="images/uran.jpg" align="left" alt="[U]"></acronym>&nbsp;'.($Colony['uran'] > $Colony['urancapacity'] ? '<font class="work">' . strdiv($Colony['uran']) . '</font>' : strdiv($Colony['uran'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Food'].' [F]"><img class="icon" src="images/food.jpg" align="left" alt="[F]"></acronym>&nbsp;'.($Colony['food'] > $Colony['foodcapacity'] ? '<font class="work">' . strdiv($Colony['food']) . '</font>' : strdiv($Colony['food'])).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="spacer" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Crystals'].' [C]"><img class="icon" src="images/crystals.jpg" align="left" alt="[C]"></acronym>&nbsp;'.strdiv($Colony['crystals']).'</td><td class="right"><img class="spacer" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
	}

?>	<td class="left"><img class="spacer" src="images/0.gif"></td>
	<td width="100" background="images/b2-bg.gif" align="center"><acronym title="<?php echo $Lang['Credits']; ?> [!]"><img class="icon" src="images/credits.jpg" align="left" alt="[!]"></acronym>&nbsp;<?php echo strdiv($Player['credits']); ?></td>
	<td class="right"><img class="spacer" src="images/0.gif" alt="" width="12" height="24"></td>
<?php
}
else {
?>	<td width="12" background="images/b2-left.gif"><img src="images/0.gif" alt="" width="12" height="24"></td>
	<td width="400" background="images/b2-bg.gif" align="center"><?php echo @file_get_contents('MOTD.txt'); ?></td>
	<td width="12" background="images/b2-right.gif"><img src="images/0.gif" alt="" width="12" height="24"></td>
<?php
}
?>	<td class="div"><img class="spacer" src="images/0.gif"></td>
	</table>
</td>
</tr>
<tr valign="middle" height="16"><td><img src="images/0.gif" alt="" width="1" height="16"></td></tr>
<tr valign="top">
<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr valign="top">
	<td width="12"></td>
	<td width="120">
<?php

tablebegin($Lang['Menu'], 120);

echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";

if ($logged & $auth) {
	echo "\t\t<tr><td><a href=\"control.php?rid=$rid\">${Lang['MenuControl']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"equipment.php?rid=$rid\">${Lang['MenuEquipment']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"messages.php?rid=$rid\">${Lang['MenuMessages']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"ads.php?rid=$rid\">${Lang['MenuAds']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"galaxy.php?rid=$rid\">${Lang['MenuGalaxyMap']}</a></td></tr>\n";
	if (@$Player['clan']) echo "\t\t<tr><td><a href=\"clan.php?rid=$rid\">${Lang['MenuClan']}</a></td></tr>\n";
	if (@$Player['usergroup']) echo "\t\t<tr><td><a href=\"admin.php?rid=$rid\">${Lang['MenuAdministration']}</a></td></tr>\n";
	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n\t\t</table>\n";

	tablebreak();

	echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
	echo "\t\t<tr><td><a href=\"colony.php?rid=$rid\">${Lang['MenuColony']}</a></td></tr>\n";

	if ($Colony) {
		echo "\t\t<tr><td><a href=\"structures.php?rid=$rid\">${Lang['MenuStructures']}</a></td></tr>\n";
		echo "\t\t<tr><td><a href=\"units.php?rid=$rid\">${Lang['MenuUnits']}</a></td></tr>\n";
		echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
		echo "\t\t</table>\n";

		tablebreak();

		echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
		if ($Colony['scout'] || $Colony['base']) echo "\t\t<tr><td><a href=\"explore.php?rid=$rid\">${Lang['MenuExplore']}</a></td></tr>\n";
		if ($Colony['worker'] || $Colony['base']) echo "\t\t<tr><td><a href=\"build.php?rid=$rid\">${Lang['MenuBuild']}</a></td></tr>\n";
		if ($Colony['factory'] || $Colony['tron']) echo "\t\t<tr><td><a href=\"production.php?rid=$rid\">${Lang['MenuProduction']}</a></td></tr>\n";
		if ($Player['level'] > 4 && ($Colony['base'] || $Colony['tacticstechnology'])) echo "\t\t<tr><td><a href=\"attack.php?rid=$rid\">${Lang['MenuAttack']}</a></td></tr>\n";
		if ($Colony['laboratory'] || $Colony['databank'] && $Colony['mmu']) echo "\t\t<tr><td><a href=\"research.php?rid=$rid\">${Lang['MenuResearch']}</a></td></tr>\n";
	}

	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
	echo "\t\t</table>\n";

	tablebreak(); 

	echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";

	echo "\t\t<tr><td><a href=\"whois.php?rid=$rid\">${Lang['MenuWhois']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"highscores.php?rid=$rid\">${Lang['MenuHighScores']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"profile.php?rid=$rid\">${Lang['MenuProfile']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"http://galaxy.game-host.org/forum/\">${Lang['MenuForum']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"irc?login=$login\">IRC</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"welcome.php?rid=$rid&action=logout\">${Lang['MenuLogout']}</a></td></tr>\n";
}
else {
	echo "\t\t<tr><td><a href=\"welcome.php?rid=$rid\">${Lang['MenuWelcome']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"news.php?rid=$rid\">${Lang['MenuNews']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"control.php?rid=$rid\">${Lang['MenuLogin']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"register.php?rid=$rid\">${Lang['MenuRegister']}</a></td></tr>\n";

	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
	echo "\t\t</table>\n";

	tablebreak(); 

	echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";

	echo "\t\t<tr><td><a href=\"propaganda.php?rid=$rid\">${Lang['MenuPropaganda']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"links.php?rid=$rid\">${Lang['MenuLinks']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"http://galaxy.game-host.org/forum/\">${Lang['MenuForum']}</a></td></tr>\n";

	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
	echo "\t\t</table>\n";

	tablebreak(); 

	echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";

	echo "\t\t<tr><td><a href=\"irc\">IRC</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"contact.php\">${Lang['MenuContact']}</a></td></tr>\n";
}

echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
echo "\t\t</table>\n";

tableend('<b>'.file_get_contents('VERSION.txt').'</b>');

if (!$logged) {
	echo "\t\t<br />\n";
	tablebegin('GF', 120);

	echo "\t\t<table id=\"menu\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";

	echo "\t\t<tr><td><a href=\"documentation.php?rid=$rid\">${Lang['MenuDocumentation']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"http://www.sourceforge.net/projects/galaxyforces\">${Lang['MenuDownload']}</a></td></tr>\n";
	echo "\t\t<tr><td><a href=\"licence.php?rid=$rid\">${Lang['MenuLicence']}</a></td></tr>\n";

	echo "\t\t<tr class=\"spacer\"><td></td></tr>\n";
	echo "\t\t</table>\n";

	tableend($Lang['Project']);
}

echo "\t</td>\n\t<td width=\"12\">&nbsp;";
if (@$sound) sound($sound);
echo "</td>\n\t<td align=\"center\">\n";
