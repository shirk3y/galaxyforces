<table width="100%" class="overall" cellspacing="0" cellpadding="0">
<tr height="100">
<td>
	<table id="top" width="100%" cellspacing="0" cellpadding="0">
	<tr height="80" valign="middle">
	<td width="200"><acronym title="Galaxy Forces"><div id="logo" alt="Galaxy Forces"></div></acronym></td>
	<td align="center">&nbsp;<?php echo @file_get_contents("{$ROOT}BANNER.txt"); ?>&nbsp;</td>
<?php

if ($logged && $Player['planet']) {

?>	<td width="60" align="right">
		<?php /*
			if($Player['dad']) {
				echo "<font class='error' size=3><b>$Lang[DeletionAccount] " . substr($Player[dad], 0, 4) . "-" . substr($Player[dad], 4, 2) . "-" . substr($Player[dad], 6, 2) . " " . substr($Player[dad], 8, 2) . ":" . substr($Player[dad], 10, 2) . ":" . substr($Player[dad], 12, 2)."</b></font>";
			}
		*/ ?>
<?php
include("zegar.php");
?>
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

<tr height="24">
<td>
	<table id="belt" width="100%" height="24" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr>
	<td background="images/b1-bg.gif"><img class="space" src="images/0.gif"></td>
<?php

if ($logged && $auth) {
	if (@$Colony) switch ($Planet['technology']) {
		case 'tron':
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Energy'].' [E]"><img class="icon" src="images/energy.jpg" align="left" alt="[E]"></acronym>&nbsp;'.($Colony['energy'] > $Colony['energycapacity'] ? '<font class="work">' . strdiv($Colony['energy']) . '</font>' : strdiv($Colony['energy'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Silicon'].' [S]"><img class="icon" src="images/silicon.jpg" align="left" alt="[S]"></acronym>&nbsp;'.($Colony['silicon'] > $Colony['siliconcapacity'] ? '<font class="work">'.strdiv($Colony['silicon']).'</font>' : strdiv($Colony['silicon'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Metal'].' [M]"><img class="icon" src="images/metal.jpg" align="left" alt="[M]"></acronym>&nbsp;'.($Colony['metal'] > $Colony['metalcapacity'] ? '<font class="work">'.strdiv($Colony['metal']).'</font>' : strdiv($Colony['metal'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Plutonium'].' [P]"><img class="icon" src="images/plutonium.jpg" align="left" alt="[P]"></acronym>&nbsp;'.($Colony['plutonium'] > $Colony['plutoniumcapacity'] ? '<font class="work">' . strdiv($Colony['plutonium']) . '</font>' : strdiv($Colony['plutonium'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Deuterium'].' [D]"><img class="icon" src="images/deuterium.jpg" align="left" alt="[D]"></acronym>&nbsp;'.($Colony['deuterium'] > $Colony['deuteriumcapacity'] ? '<font class="work">' . strdiv($Colony['deuterium']) . '</font>' : strdiv($Colony['deuterium'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			break;

		case 'tron':
		case 'human':
		default:
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Energy'].' [E]"><img class="icon" src="images/energy.jpg" align="left" alt="[E]"></acronym>&nbsp;'.($Colony['energy'] > $Colony['energycapacity'] ? '<font class="work">' . strdiv($Colony['energy']) . '</font>' : strdiv($Colony['energy'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Metal'].' [M]"><img class="icon" src="images/metal.jpg" align="left" alt="[M]"></acronym>&nbsp;'.($Colony['metal'] > $Colony['metalcapacity'] ? '<font class="work">'.strdiv($Colony['metal']).'</font>' : strdiv($Colony['metal'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Uran'].' [U]"><img class="icon" src="images/uran.jpg" align="left" alt="[U]"></acronym>&nbsp;'.($Colony['uran'] > $Colony['urancapacity'] ? '<font class="work">' . strdiv($Colony['uran']) . '</font>' : strdiv($Colony['uran'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Food'].' [F]"><img class="icon" src="images/food.jpg" align="left" alt="[F]"></acronym>&nbsp;'.($Colony['food'] > $Colony['foodcapacity'] ? '<font class="work">' . strdiv($Colony['food']) . '</font>' : strdiv($Colony['food'])).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
			echo "\t".'<td class="left"><img class="space" src="images/0.gif"></td><td class="bg"><acronym title="'.$Lang['Crystals'].' [C]"><img class="icon" src="images/crystals.jpg" align="left" alt="[C]"></acronym>&nbsp;'.strdiv($Colony['crystals']).'</td><td class="right"><img class="space" src="images/0.gif"></td><td class="div">&nbsp;</td>'."\n";
	}

?>	<td class="left"><img class="space" src="images/0.gif"></td>
	<td width="100" background="images/b2-bg.gif" align="center"><acronym title="<?php echo $Lang['Credits']; ?> [!]"><img class="icon" src="images/credits.jpg" align="left" alt="[!]"></acronym>&nbsp;<?php echo strdiv($Player['credits']); ?></td>
	<td class="right"><img class="space" src="images/0.gif" alt="" width="12" height="24"></td>
<?php
}
else {
?>	<td width="12" background="images/b2-left.gif"><img src="images/0.gif" alt="" width="12" height="24"></td>
	<td width="400" background="images/b2-bg.gif" align="center"><?php echo @file_get_contents('MOTD.txt'); ?></td>
	<td width="12" background="images/b2-right.gif"><img src="images/0.gif" alt="" width="12" height="24"></td>
<?php
}
?>	<td class="div"><img class="space" src="images/0.gif"></td>
	</table>
</td>
</tr>

<tr valign="top" height="16"><td><img src="images/0.gif" alt="" width="1" height="16"></td></tr>

<tr valign="top">
<td>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr valign="top" height="400">
	<td width="10"></td>
	<td width="160">

<?php

tablebegin($Lang['Menu']);

$Style['menu.icon.dimension']=16;
$Style['menu.icon.extension']='.png';

$Style['menu.item.prefix']=
'		<tr><td class="item">';

$Style['menu.item.suffix']=
'</td></tr>
';

$Style['menu.prefix']=
'		<table class="menu">
		<tr><td class="space"></td></tr>
';


$Style['menu.suffix']=
'		<tr><td class="space"></td></tr>
		</table>

';

ob_start();

?>		<tr><td class="space"></td></tr>
		</table>
<?php

tablebreak();

?>		<table class="menu">
		<tr><td class="space"></td></tr>
<?php

$Style['menu.separator']=ob_get_contents();

ob_end_clean();

style_menu_galaxy();

tableend('<b>'.file_get_contents('VERSION.txt').'</b>');

if (! $logged) {
	tablebegin('GF');

?>
		<table class="menu"> 
		<tr><td class="space"></td></tr> 
		<tr><td class="item"><a href="documentation.php"><?php __('MenuDocumentation'); ?></a></td></tr> 
		<tr><td class="item"><a href="http://www.sourceforge.net/projects/galaxyforces"><?php __('MenuDownload'); ?></a></td></tr> 
		<tr><td class="item"><a href="licence.php"><?php __('MenuLicence'); ?></a></td></tr> 
		<tr><td class="space"></td></tr> 
		</table>
<?php

	tableend($Lang['Project']);
}

echo "\t</td>\n\t<td width=\"12\">&nbsp;";
if (@$sound) sound($sound);
echo "</td>\n\t<td align=\"center\">\n";

style_module_section(@$Sections["top"], "section-top");
