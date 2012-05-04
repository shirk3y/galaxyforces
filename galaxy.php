<?php

// ===========================================================================
// Galaxy {galaxy.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.3
//	Modified:	2005-11-13
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'galaxy';
$auth = true;

require('include/header.php');

locale('colony');

$galaxy = getvar('galaxy');
$object = getvar('object');
$page = abs(getvar('page'));

$pagecount = 100;
$MAXUSERS = 250;

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echotitle($Lang['ErrorProblems']);
	echo "\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />\n";
	sound('error');
	tableend("<a href=\"colony.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend(anchor('admin.php', $Lang['GoBack']));
}

// ===========================================================================
// OBJECT
// ===========================================================================

elseif ($object) {
	$db->query("SELECT * FROM {$prefix}space WHERE name='$object' LIMIT 1;");
	if ($t = $db->fetchrow()) {
		$galaxy = $t['galaxy'];
		if (! $Player['destination'] && $Player['planet'] != $object) {
			$db->query("SELECT * FROM ${prefix}universe WHERE name='$galaxy' LIMIT 1;");
			$g = $db->fetchrow();
			$db->query("SELECT * FROM `${prefix}universe` WHERE `name`='${Player['galaxy']}' LIMIT 1;");
			$gg = $db->fetchrow();
			$db->query("SELECT * FROM `${prefix}space` WHERE `name`='${Player['planet']}' LIMIT 1;");
			$tt = $db->fetchrow();
			$time = round((galaxydistance($gg, $g) + planetdistance($tt, $t)) / $playerspeed);
		}

		$db->query("SELECT id FROM {$prefix}colonies WHERE planet='$object';");
		$max = $db->numrows();

		if ($page > ($m = floor($max / $pagecount))) $page = $m;
		$l = $page * $pagecount;

		$db->query("SELECT * FROM {$prefix}colonies WHERE planet='$object' ORDER BY base DESC,colonists DESC,name LIMIT $l,$pagecount;");

		$s = '';

		tablebegin("${Lang['Object']}: <b>" . strcap($t['name']) . '</b>', 540);
		subbegin('images/table-b2.jpg');

		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n\t\t<tr valign=\"top\">\n";
		echo "\t\t<td><b>" . $Lang['Objects[]'][$t['type']] . "</b>: <font class=\"plus\">" . strcap($t['name']) . "</font><br />";
		echo "<br /><b>${Lang['Technology']}</b>: <font class=\"capacity\">" . $Lang['Technology[]'][$t['technology']] . "</font><br />";
		echo "<br /><b>${Lang['SizeC']}</b>: <font class=\"result\">" . $Lang['SizeT'][$t['class']] . '</font><br />';
		
		if ($t['type'] == 'planet') {
			echo "<b>${Lang['Explored']}</b>: <font class=\"minus\">" . number_format($t['explored'], 2, $Lang['DecPoint'], ' ') . '%</font><br />';
		}

		if (! $Player['destination'] && $Player['planet'] != $object) echo "<br /><a href=\"control.php?action=travel&destination=${t['name']}\">${Lang['Travel']} (" . eta($time) . ')&nbsp;&gt;&gt;</a><br />';
		if (! $Colony) echo "<br /><a href=\"colony.php?view=create&planet=${t['name']}\">${Lang['CreateColony']}&nbsp;&gt;&gt;</a><br />";

		echo "</td>\n\t\t<td>&nbsp;</td>\n";

		echo "\t\t<td>" . ($t['system'] ? "<b>${Lang['PlanetSystem']}</b>: <font class=\"work\">${t['system']}</font>" : '&nbsp;') . '<br /><br />';

		if ($t['type'] == 'planet') {
			echo "\t<b>${Lang['Colonies']}</b>: <font class=\"result\">$max</font><br />\n";
			echo "\t".($t['abandoned'] ? "<b>${Lang['AbandonedC']}</b>: <font class=\"minus\">${t['abandoned']}</font>" : '') . "<br />\n";
			echo "<b>${Lang['WindS']}</b>: <font class=\"capacity\">${t['wind']}%</font><br />";
			echo "<b>${Lang['Gravity']}</b>: <font class=\"work\">" . number_format($t['gravity'], 1, $Lang['DecPoint'], ' ') . ' Q</font><br />';
		}
		if (! $action) echolinkbox("galaxy.php?galaxy=$galaxy&object=$object&action=scanobject", $Lang['Scan']);

		echo "\t\t<td width=\"168\" height=\"168\" align=\"right\">\n";
		tableimg('images/bw.gif', 168, 168, "gallery/space/${t['name']}.jpg", 160, 160, '', 'right');
		echo "\t\t</td>\n\t\t</tr>\n\t\t</table>\n";

		subend();

		if ($action == 'scanobject') {
			tablebreak();

			echo BR;

			?><table width="100%" cellspacing="0" cellpadding="0"><?php
	
			echo '<tr><td width="12">&nbsp;</td><td width="30%"><b>'.$Lang['Moons'].'</b>:</td><td>&nbsp;</td><td width="10%" align="center"><font class="plus">'.div($t['moons']).'</font></td><td width="12">&nbsp;</td>';
			echo '<td width="30%"><b>'.$Lang['TerrainHardness'].'</b>:<td>&nbsp;</td><td width="10%" align="center"><font class="minus">'.div($t['terrain']).'</font></td><td width="12">&nbsp;</td></tr>';

			echo '<tr><td>&nbsp;</td><td><b>'.$Lang['Illumination'].'</b>:</td><td>&nbsp;</td><td align="center"><font class="result">'.div($t['illumination']).'</font></td><td>&nbsp;</td>';
			echo '<td><b>'.$Lang['LifeSigns'].'</b>:<td>&nbsp;</td><td align="center"><font class="result">'.div($t['life']).'</font></td><td>&nbsp;</td></tr>';

			echo '<tr><td>&nbsp;</td><td><b>'.$Lang['Coordinates'].'</b>:</td><td>&nbsp;</td><td><font class="work">'.div($t['x']).', '.div($t['y']).', '.div($t['z']).'</font></td><td>&nbsp;</td>';
			echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';

//			echo '<tr><td>&nbsp;</td><td><b>'.$Lang['Scienceforce'].'</b>:</td><td>&nbsp;</td><td align="center"><font class="result">'.div($Colony['scienceforce'], 1, $Lang['DecPoint']).'</font></td><td>&nbsp;</td>';
//			echo '<td><b>'.$Lang['Satisfaction'].'</b>:<td>&nbsp;</td><td align="center">'.amount($Colony['satisfaction']).'</td><td>&nbsp;</td></tr>';

			echo '</table>'.BR;
		}
		elseif ($max) {
			tablebreak();

			?><br /><table width="100%" cellspacing="0" cellpadding="0"><tr id="header"><td id="headerl">&nbsp;</td><td width="40%" align="left"><?php echo $Lang['ColonyName']; ?>:</td><td>&nbsp;</td><td align="center"><?php echo $Lang['Owner']; ?>:</td><td>&nbsp;</td><td align="center"><?php echo $Lang['Level']; ?>:</td><td>&nbsp;</td><td align="center"><?php echo $Lang['Population']; ?>:</td><td>&nbsp;</td><td align="center"><?php echo $Lang['Attacked']; ?>:</td><td id="headerr">&nbsp;</td></tr><?php

			while ($u = $db->fetchrow()) {

				if ($u['owner'] == $login) $class=' id="here"';
				elseif (! (@$i++ % 2)) $class = ' id="div"';
				else $class = '';

				?><tr height="24"<?php echo $class; ?>><td width="12">&nbsp;</td><?php
				?><td align="left"><font class="capacity"><?php echo strcap($u['name']); ?></font></td><td>&nbsp;</td><?php
				?><td align="center"><a href="whois.php?name=<?php echo $u['owner']; ?>"><?php echo $u['owner']; ?></a></td><td>&nbsp;</td><?php
				?><td align="center"><?php echo $u['base']; ?></td><td>&nbsp;</td><?php
				?><td align="center"><font class="result"><?php echo div($u['colonists'] + $u['scientists'] + $u['soldiers']); ?></font></td><td>&nbsp;</td><?php
				?><td align="center"><font class="minus"><?php echo $u['attacked']; ?></font></td><td width="12">&nbsp;</td></tr><?php
			}
			?><tr height="8"><td>&nbsp;</td></tr></table><?php

			$n = $page;

			if ($n) $s .= "<a href=\"galaxy.php?galaxy=$galaxy&object=$object&page=" . ($n - 1) . "\">";
			$s .=  "&lt;&lt; ${Lang['Previous']}";
			if ($n) $s .= "</a>";

			$s .= ' &nbsp; ';

			$a = $n > 5 ? $n - 5 : 0;
			$b = $n < $m - 5 ? $n + 5 : $m;

			for ($i = $a; $i <= $b; $i++) {
				if ($i != $n) $s .= "<a href=\"galaxy.php?galaxy=$galaxy&object=$object&page=$i\">";
				$s .= $i + 1;
				if ($i != $n) $s .= "</a>";
				$s .= ' ';
			}

			$s .= '&nbsp; ';

			if ($n < $m) $s .= "<a href=\"galaxy.php?galaxy=$galaxy&object=$object&page=" . ($n + 1) . "\">";
			$s .= "${Lang['Next']} &gt;&gt;";
			if ($n < $m) $s .= "</a>";

			$s .= ' &nbsp; &nbsp ';
		}

		$db->query("SELECT login,destination,clan FROM ${prefix}users WHERE planet='$object' ORDER BY clan,level DESC LIMIT $MAXUSERS;");
		if ($l = $db->numrows()) {
			tablebreak();
			subbegin('images/table-b2.jpg');
			echo "\t\t<center>\n";
			$i = 0;
			while (($t = $db->fetchrow()) && ++$i) echo "<a href=\"whois.php?name=${t['login']}\"" . ($t['destination'] ? ' class="work"' : '') . ">${t['login']}" . ($t['clan'] ? '(' . $t['clan'] . ')' : '') . "</a>" . ($i < $l ? ', ' : '');
			if (++$l > $MAXUSERS) echo ", ... (<font class=\"result\">${Lang['morethan']} $MAXUSERS</font>)";
			echo "\t\t</center>\n";
			subend();
		}
		tableend($s . '<a href="galaxy.php?galaxy=' . $galaxy . '">' . $Lang['GoBack'] . ' &gt;&gt;</a>');
	}
}

// ===========================================================================
// GALAXY
// ===========================================================================

elseif ($galaxy) {

	$db->query("SELECT * FROM `${prefix}universe` WHERE `name` = '${Player['galaxy']}' LIMIT 1;");
	$tt = $db->fetchrow();

	$db->query("SELECT * FROM `${prefix}space` WHERE `name` = '${Player['planet']}' LIMIT 1;");
	$uu = $db->fetchrow();

	$db->query("SELECT * FROM `${prefix}universe` WHERE `name`='$galaxy' LIMIT 1;");
	if ($t = $db->fetchrow()) {
		$galaxydistance = galaxydistance($t, $tt);

		$db->query("SELECT * FROM `${prefix}space` WHERE `galaxy`='${t['name']}' AND `type`='planet' ORDER BY `system`, `name` LIMIT 0, 100;");

		tablebegin($Lang['Galaxy'] . ': ' . strcap($t['name']), 440);
		subbegin('images/table-b2.jpg');

		tableimg('images/bw.gif', 168, 168, "gallery/galaxy/${t['name']}.jpg", 160, 160, '', 'right');

		$s = $Lang['ObjectName[]'][$t['type']];
		echo "\t<b>$s</b>: <font class=\"minus\">" . strcap($t['name']) . "</font><br />\n\t<br />\n";

 		if ($galaxydistance > 0.1)  echo "\t\t<b>${Lang['Distance']}</b>: <font class=\"result\">" . number_format($galaxydistance, 1, $Lang['DecPoint'], ' ') . "</font><br /><br />\n";

		if ($t['type'] == 'galaxy') echo "\t<b>${Lang['CPC']}</b>: <font class=\"plus\">" . $db->numrows() . "</font><br />";

		subend();

		if (file_exists("flash/universe/${t['name']}.swf")) {
			tablebreak();
			echo "\t<br />\n";
			echo "\t<table background=\"images/tw.gif\" width=408 height=308 cellspacing=0 cellpadding=0 border=0><tr><td align=center>\n";
			swf($t['name'], "flash/universe/${t['name']}.swf", 400, 300, '#000000');
			echo "\t</td></tr></table>\n";
			echo "\t<br />\n";
		}

		if ($db->numrows()) {
			tablebreak();

			?><table width="100%" cellspacing="0" cellpadding="0"><tr height="8"><td><img src="images/0.gif" /></td></tr><?php

			$class = '';
			
			while ($u = $db->fetchrow()) {
				$class = $class ? '' : ' id="div"';
				echo "\t<tr valign=\"top\" height=\"72\"$class>\n";
				
?>	<td width="12">&nbsp;</td>
	<td width="72" valign="center">
		<table background="images/pw.gif" width="72" height="72" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr height="72" valign="center">
		<td><center><a href="<?php echo $_SERVER['PHP_SELF']; ?>?galaxy=<?php echo $galaxy; ?>&object=<?php echo $u['name']; ?>"><img src="gallery/space/icons/<?php echo $u['name'] . '.jpg'; ?>" alt="<?php echo strcap($u['name']); ?>" width="64" height="64" hspace="0" vspace="0" border="0"></a></center></td>
		</tr>
		</table>
	</td>
	<td width="8">&nbsp;</td>
	<td align="left">
		<b><?php echo $Lang['PlanetName']; ?></b>: <font class="plus"><?php echo strcap($u['name']); ?></font><br />
		<br />
		<b><?php echo $Lang['Technology']; ?></b>: <font class="capacity"><?php echo $Lang['Technology[]'][$u['technology']]; ?></font><br />
<?php if (($distance = $galaxydistance + planetdistance($u, $uu)) > 0.1) { ?>		<b><?php echo $Lang['Distance']; ?></b>: <font class="result"><?php echo number_format($distance, 1, $Lang['DecPoint'], ' '); ?></font><br /><?php } ?>
	</td>
	<td width="8">&nbsp;</td>
	<td align="left">
<?php if ($u['system']) echo "\t\t<b>${Lang['PlanetSystem']}</b>: <font class=\"work\">${u['system']}</font><br />\n"; 

				echo "\t".($u['abandoned'] ? "<b>${Lang['AbandonedC']}</b>: ${u['abandoned']}" : '')."<br />\n";
				echo "\t".($u['explored'] > 0.1 ? "<b>${Lang['Explored']}</b>: <font class=\"minus\">".number_format($u['explored'], 2, $Lang['DecPoint'], ' ').' %</font>' : '')."<br />\n";
?>
		<b><?php echo $Lang['SizeC']; ?></b>: <font class="result"><?php echo $Lang['SizeT'][$u['class']]; ?></font><br />
	</td>
	<td width="8">&nbsp;</td>
	</tr>
	<tr height="8"><td><img src="images/0.gif" /></td></tr>
<?php
			}
?>	</table>
<?php
		}

		$db->query("SELECT * FROM `${prefix}space` WHERE `galaxy`='${t['name']}' AND `type`<>'planet' ORDER BY `type`,`name` LIMIT 0, 100;");

		if ($db->numrows()) {
			tablebreak();
			while ($row = $db->fetchrow()) $objects[] = $row;

        		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">";
			$i = 0;

			foreach ($objects as $p) {
				if (++$i % 2) {
					echo "<tr height=\"8\"><td>&nbsp;</td></tr><tr height=\"72\" valign=\"top\"><td width=\"8\">&nbsp;</td><td width=\"72\">";
					tableimg('images/pw.gif', 72, 72, "gallery/space/icons/${p['name']}.jpg", 64, 64, "galaxy.php?galaxy=$galaxy&object=${p['name']}");
					echo "</td><td width=\"8\">&nbsp;</td><td align=\"left\">";
					echo '<b>' . $Lang['Objects[]'][$p['type']] . "</b>: <font class=\"plus\">" . strcap($p['name']) . "</font></a><br />";
					if ($p['system']) echo "<b>${Lang['PlanetSystem']}</b>: <font class=\"work\">${p['system']}</font><br />";
					if (($distance = $galaxydistance + planetdistance($p, $uu)) > 0.1) echo "<b>${Lang['Distance']}</b>: " . number_format($distance, 1, $Lang['DecPoint'], ' ') . '<br />';
					echo "</td>";
				}
				else {
					echo "<td width=\"8\">&nbsp;</td><td align=\"right\">";
					echo '<b>' . $Lang['Objects[]'][$p['type']] . "</b>: <font class=\"plus\">" . strcap($p['name']) . "</font></a><br />";
					if ($p['system']) echo "<b>${Lang['PlanetSystem']}</b>: <font class=\"work\">${p['system']}</font><br />";
					if (($distance = $galaxydistance + planetdistance($p, $uu)) > 0.1) echo "<b>${Lang['Distance']}</b>: " . number_format($distance, 1, $Lang['DecPoint'], ' ') . '<br />';
					echo "</td><td width=\"8\">&nbsp;</td><td width=\"72\">";
					tableimg('images/pw.gif', 72, 72, "gallery/space/icons/${p['name']}.jpg", 64, 64, "galaxy.php?galaxy=$galaxy&object=${p['name']}");
					echo "</td><td width=\"8\">&nbsp;</td></tr>";
				}
			}

			if ($i % 2) echo "<td colspan=\"5\">&nbsp;</td></tr>";
			echo "<tr height=\"8\"><td>&nbsp;</td></tr></table>\n";
		}

		tableend('<a href="galaxy.php">' . $Lang['GoBack'] . ' &gt;&gt;');
	}
}

// ===========================================================================
// UNIVERSE
// ===========================================================================

else {

	$db->query("SELECT * FROM `${prefix}universe` WHERE `name`='${Player['galaxy']}' LIMIT 1");
	$tt = $db->fetchrow();

	$db->query("SELECT * FROM `{$prefix}universe` ORDER BY `type` ASC LIMIT 0 , 100");

	tablebegin($Lang['Universe'], 540);

	echo "\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
	echo "\t<tr height=\"8\"><td>&nbsp;</td></tr>\n";

	$i = 0;
	while ($t = $db->fetchrow()) {
		if (! $i % 2) echo "\t<tr height=\"72\" valign=\"middle\">\n";

		echo "\t<td width=\"12\">&nbsp;</td>\n\t<td width=\"72\">\n";

		tableimg('images/pw.gif', 72, 72, "gallery/galaxy/icons/${t['name']}.jpg", 64, 64, "galaxy.php?galaxy=${t['name']}");

		echo "\t<td width=\"12\">&nbsp;</td>\n\t<td align=\"left\">\n";

		$s = $Lang['ObjectName[]'][$t['type']];
		echo "\t<b>$s</b>: <font class=\"minus\">" . strcap($t['name']) . "</font><br />\n\t<br />\n";

		$distance = round(100 * galaxydistance($t, $tt)) / 100;
		if ($distance > 1) {
			echo "\t<b>${Lang['Distance']}</b>: <font class=\"result\">";
			if ($distance > 100000) echo "<font class=\"capacity\">${Lang['Unreachable']}</font>";
			else echo number_format($distance, 2, $Lang['DecPoint'], ' ');
			echo "</font><br />\n";
		}
		else echo "\t<br />\n";

		echo "\t</td>\n";

		if ($i++ % 2) echo "\t<td width=\"12\">&nbsp;</td>\n\t</tr>\n\t<tr height=\"8\"><td>&nbsp;</td></tr>\n";
		if (! $i % 2) echo "\t<td width=\"12\">&nbsp;</td>\n\t</tr>\n\t<tr height=\"8\"><td>&nbsp;</td></tr>\n";
	}
	echo "\t</table>\n";

	tablebreak();
	subbegin('images/table-b2.jpg');

?>		<center><a href="propaganda/wallpapers/map.jpg"><?php echo $Lang['UniverseMap']; ?> &gt;&gt;</a></center>
<?php
	subend();

	tableend($Lang['Universe']);
}

require('include/footer.php');
