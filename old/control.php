<?php

// ===========================================================================
// Control {control.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	2.3
//	Modified:	2005-11-13
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$index = 'control';
$auth = true;

require('include/header.php');
include('include/messages.php');

$value = getvar('value');
$view = getvar('view');

$pagename = $Lang['Control'];

// ===========================================================================
// LEVELUP
// ===========================================================================

if ($action == 'levelup') {
	if ($Player['exp'] >= $Player['exp4level']) {
		$a = ': <font class="plus">+';
		$b = '</font><br />';

		$Player['strength'] += $strength = round(100 * Rand(5, 10 + $Player['level']) / 30) / 100;
		$Player['agility'] += $agility = round(100 * Rand(5, 10 + $Player['level']) / 25) / 100;
		$Player['hpmax'] += $hpmax = round(10 * Rand(3, 5 + $Player['level'])) / 40;
		$Player['mpmax'] += $mpmax = round(10 * Rand(3, 5 + $Player['level'])) / 40;
		$Player['hpgain'] += $hpgain = round(10 * Rand(5, 10 + $Player['level']) / 30) / 100;
		$Player['mpgain'] += $mpgain = round(10 * Rand(5, 10 + $Player['level']) / 50) / 100;
		$Player['score'] += 2 + $Player['level'];
		$Player['exp'] = $Player['exp4level'];
		$Player['level']++;
		$Player['sp'] += 4;

		$result .= "<font class=\"h3\">${Lang['LevelUp']}</font><br />";
		$result .= "<br />${Lang['GainedLevel']}: <font class=\"capacity\"><b>${Player['level']}</b></font><br />";
		$result .= "<br /><b>${Lang['Strength']}</b>$a$strength$b<b>${Lang['Agility']}</b>$a$agility$b";
		$result .= "<br /><b>${Lang['Health']}</b>$a$hpmax$b<b>${Lang['MovePoints']}</b>$a$mpmax$b";
		$result .= "<br /><b>${Lang['Vitality']}</b>$a$hpgain$b<b>${Lang['Regeneration']}</b>$a$mpgain$b";

		$db->query("INSERT INTO {$prefix}chat (`timestamp`,`author`,`message`) VALUES ('$timestamp','<font color=\"yellow\">$login</font>','<font class=\"capacity\"><i>level up</i></font>...');");
		$db->query("UPDATE `${prefix}users` SET `exp`='{$Player['exp']}',`level`='${Player['level']}',`score`='${Player['score']}',`sp`='${Player['sp']}',`strength`=`strength`+$strength,`agility`=`agility`+$agility,`hpmax`=`hpmax`+$hpmax,`mpmax`=`mpmax`+$mpmax,`hpgain`=`hpgain`+$hpgain,`mpgain`=`mpgain`+$mpgain WHERE `id`='${Player['id']}';");

		$Player = readplayer($login);
	}
	else $errors .= "${Lang['ErrorProblems']}<br />";
}

// ===========================================================================
// DISTRIBUTE
// ===========================================================================

elseif ($action == 'distribute') {
	if ($name && ($value = abs($value))) {
		for ($i = 0; $Player['sp'] && ($i < $value); $i++) {
			switch ($name) {
				case 'strength': case 'agility': @$$name += round(100 * Rand(5, 10 + $Player['level']) / 50) / 100; break;
				case 'psi': case 'force': case 'knowledge': case 'alcoholism': case 'pocketstealing': case 'intellect': case 'hacking': if ($Player[$name]) @$$name += round(100 * Rand(5, 10 + $Player['level']) / 50) / 100; break;
				case 'hpmax': case 'mpmax': @$$name += round(10 * Rand(5, 10 + $Player['level'])) / 100; break;
				case 'hpgain': case 'mpgain': @$$name += round(100 * Rand(5, 10 + $Player['level']) / 100) / 100; break;
			}
			$Player['sp']--;
		}

		$db->query("UPDATE `${prefix}users` SET `$name`=`$name`+'${$name}',`sp`='${Player['sp']}' WHERE `id`='${Player['id']}';");
		$Player = readplayer($login);
	}

	$view = 'distribute';
}

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin("<font class=\"error\">${Lang['Error']}!</font>", '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br />\n";
	echo "\t\t<br />\n";
	sound('error');
	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// STATISTICS
// ===========================================================================

elseif ($view == 'statistics' || $view == 'distribute') {
	tablebegin($Lang['Statistics'], 500);

	if ($view == 'distribute')
		if ($Player['sp']) echo "\t<br /><b>{$Lang['Skillpoints']}</b>: <font class=\"result\">${Player['sp']}</font><br />\n";
		else {
			echo "\t<br /><font class=\"result\">{$Lang['NoSkillpointsLeft']}</font><br />\n";
			$view = 'statistics';
		}

	echo "\t\t<br />\n";

	echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"left\">${Lang['Attribute']}:</td><td width=\"60\">${Lang['Symbol']}:</td><td width=\"160\">${Lang['Value']}:</td>";
	if ($view == 'distribute') echo '<td width="100">&nbsp;</td>';
	echo "<td id=\"headerr\">&nbsp;</td></tr>\n";

	echo "\t\t<tr><td></td><td>${Lang['Strength']}</td><td align=\"center\"><b>[S]</b><td align=\"center\">".($Player['strengthmodifier'] != 0 ? '<font class="work">'.$Player['strength'].'</font>&nbsp;'.amount($Player['strengthmodifier']) : '<font class="plus">'.$Player['strength'].'</font>').'</td>';
	if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="strength" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
	echo "<td></td></tr>\n";

	echo "\t\t<tr class=\"div\"><td></td><td>${Lang['Agility']}</td><td align=\"center\"><b>[A]</b></td><td align=\"center\">".($Player['agilitymodifier'] != 0 ? '<font class="work">'.$Player['agility'].'</font>&nbsp;'.amount($Player['agilitymodifier']) : '<font class="plus">'.$Player['agility'].'</font>').'</td>';
	if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="agility" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
	echo "<td></td></tr>\n";

	echo "\t\t<tr><td></td><td>${Lang['Health']}</td><td align=\"center\"><b>[HP]</b></td><td align=\"center\">".($Player['hpmodifier'] != 0 ? '<font class="work">'.$Player['hpmax'].'</font>&nbsp;'.amount($Player['hpmodifier']) : '<font class="plus">'.$Player['hpmax'].'</font>').'</td>';
	if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="hpmax" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
	echo "<td></td></tr>\n";

	echo "\t\t<tr class=\"div\"><td></td><td>${Lang['MovePoints']}</td><td align=\"center\"><b>[MP]</b></td><td align=\"center\">".($Player['mpmodifier'] != 0 ? '<font class="work">'.$Player['mpmax'].'</font>&nbsp;'.amount($Player['mpmodifier']) : '<font class="plus">'.$Player['mpmax'].'</font>').'</td>';
	if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="mpmax" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
	echo "<td></td></tr>\n";

	echo "\t\t<tr><td></td><td>${Lang['Vitality']}</td><td></td><td align=\"center\"><font class=\"plus\">+${Player['hpgain']}</font></td>";
	if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="hpgain" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
	echo "<td></td></tr>\n";

	echo "\t\t<tr class=\"div\"><td></td><td>${Lang['Regeneration']}</td><td></td><td align=\"center\"><font class=\"plus\">+${Player['mpgain']}</font></td>";
	if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="mpgain" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
	echo "<td></td></tr>\n";

	$class = TRUE;

	if ($Player['psi']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['Psi']}</td><td></td><td class=\"plus\" align=\"center\">${Player['psi']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="psi" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	if ($Player['force']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['Force']}</td><td></td><td class=\"plus\" align=\"center\">${Player['force']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="force" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	if ($Player['intellect']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['Intellect']}</td><td></td><td class=\"plus\" align=\"center\">${Player['intellect']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="intellect" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	if ($Player['knowledge']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['Knowledge']}</td><td></td><td class=\"plus\" align=\"center\">${Player['knowledge']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="knowledge" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	if ($Player['pocketstealing']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['PocketStealing']}</td><td></td><td class=\"plus\" align=\"center\">${Player['pocketstealing']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="pocketstealing" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	if ($Player['hacking']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['Hacking']}</td><td></td><td class=\"plus\" align=\"center\">${Player['hacking']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="hacking" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	if ($Player['alcoholism']) {
		$class = $class ? '' : ' class="div"';
		echo "\t\t<tr$class><td></td><td>${Lang['Alcoholism']}</td><td></td><td class=\"plus\" align=\"center\">${Player['alcoholism']}</td>";
		if ($view == 'distribute') echo '<td align="right"><form action="control.php" method="POST"><input type="hidden" name="action" value="distribute" /><input type="hidden" name="name" value="alcoholism" /><input type="text" size="2" name="value" value="1" />&nbsp;&nbsp;<input type="submit" value="'.$Lang['Distribute'].'" /></form></td>';
		echo "<td></td></tr>\n";
	}

	echo "\t<tr><td>&nbsp;</td></tr>\n";

	$class = $class ? '' : ' class="div"';
	echo "\t\t<tr$class><td></td><td>${Lang['Reputation']} ".$playernature."</td><td></td><td align=\"center\"><font class=\"".($Player['reputation'] <= 2.5 ? 'minus' : ($Player['reputation'] >= 2.5 ? 'plus' : 'result'))."\">${Player['reputation']}</font> (".round(100*reputationmodifier($Player['reputation'])).'%)</td>';
	echo ($view == 'distribute' ? '<td></td>' : '')."<td></td></tr>\n";

	echo "\t\t</table>\n";
	echo "\t\t<br />\n";

	tableend("<a href=\"control.php\">${Lang['GoBack']}&nbsp;&gt;&gt;</a>");
}

// ===========================================================================
// CONTROL
// ===========================================================================

else {
	tablebegin($pagename, 500);
	subbegin('images/table-b2.jpg');

	list($all, $unread) = checkmessages();

	echo "\t\t<b>${Lang['Name']}</b>: <a class=\"capacity\" href=\"whois.php?name=$login\">$login</a> &nbsp; <a href=\"control.php?view=statistics\">${Lang['Statistics']}&nbsp;&gt;&gt;</a>";
	if ($unread) sound('incomingtransmission');
	echo "<br />\n";

?>		<?php echo $Player['clan'] ? "<b>${Lang['Group']}</b>: <a href=\"clan.php\">${Player['clan']}</a>" : ''; ?><br />
		<br />
		<b><?php echo $Lang['Level']; ?></b>: <font class="plus"><?php echo $Player['level']; ?></font><?php echo $Player['sp'] ? ', <b>' . $Lang['Skillpoints'] . '</b>:&nbsp;<font class="result">'.$Player['sp'].'</font> &nbsp; <a href="control.php?view=distribute">'.$Lang['Distribute'].'&nbsp;&gt;&gt;</a>' : ''; ?><br />
<?php
		if ($Player['exp'] < $Player['exp4level']) echo '<b>'.$Lang['Experience'].'</b>: <font class="result">'.div($Player['exp']).'</font> / <font class="capacity">'.strdiv($Player['exp4level']).'</font><br />';
		else echo '<a href="control.php?action=levelup">'.$Lang['LevelUp'].' &gt;&gt;</a><br />';

?>		<br />
		<b>[MP]</b>: <font class="result"><?php echo $Player['mp']; ?></font> / <font class="capacity"><?php echo $Player['mpmax']; ?></font> <?php echo amount($Player['mpgain']); ?><br />
	</td>
	<td width="8">&nbsp;</td>
	<td align="right">
		<b><?php echo $Lang['Score']; ?></b>: <font class="plus"><?php echo div($Player['score']); ?></font><br />
		<b><?php echo $Lang['Reputation']; ?></b>: <font class="<?php if ($Player['reputation'] <= 2.5) echo "minus"; elseif ($Player['reputation'] >= 2.5) echo "plus"; else echo "result"; ?>"><?php echo $Player['reputation']; ?></font> (<?php echo $playernature; ?>)<br />
		<br />
		<b><?php echo $Lang['Strength']; ?></b>: <font class="result"><?php echo floor(100 * $Player['strength']) / 100; ?></font>, <b><?php echo $Lang['Damage']; ?></b>: <font class="plus"><?php echo $Player['min']; ?> - <?php echo $Player['max']; ?></font><br />
		<b><?php echo $Lang['Agility']; ?></b>: <font class="work"><?php echo floor(100 * $Player['agility']) / 100; ?></font>, <b><?php echo $Lang['Armor']; ?></b>: <font class="minus"><?php echo $Player['armor']; ?></font><br />
		<br />
<?php
	echo "\t\t<b>[HP]</b>: <font class=\"".($Player['hp'] > $Player['hpmax'] ? 'work' : 'result').'">'.$Player['hp'].'</font>&nbsp;/&nbsp;<font class="'.($Player['hpmodifier'] ? 'work' : 'capacity').'"</font>'.$Player['hpmax'].'</font> (';
	$hurt = round(100 * $Player['hp'] / $Player['hpmax']);
	if ($hurt < 15) echo '<font class="minus">'.$Lang['Condition[]'][0];
	elseif ($hurt < 40) echo '<font class="work">'.$Lang['Condition[]'][1];
	elseif ($hurt < 75) echo '<font class="result">'.$Lang['Condition[]'][2];
	elseif ($hurt < 90) echo '<font class="plus">'.$Lang['Condition[]'][3];
	else echo '<font class="plus">'.$Lang['Condition[]'][4];
	echo '</font>)&nbsp;'.amount($Player['hpgain'])."<br />\n";

	subend();

	if ($view == 'distribute') {
	}

	if ($view == 'statistics') {
		tablebreak();
		echo "\t\t<br />\n";
		echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">\n";
		echo "\t\t<tr id=\"header\"><td id=\"headerl\">&nbsp;</td><td align=\"left\">${Lang['Attribute']}:</td><td width=\"100\">${Lang['Value']}:</td><td id=\"headerr\">&nbsp;</td></tr>\n";
		echo "\t\t<tr><td></td><td><b>${Lang['Strength']}</b></td><td align=\"center\">".($Player['strengthmodifier'] != 0 ? '<font class="work">'.$Player['strength'].' '.amount($Player['strengthmodifier']) : $Player['strength'])."</td><td></td></tr>\n";
		echo "\t\t<tr><td></td><td><b>${Lang['Agility']}</b></td><td align=\"center\">".($Player['agilitymodifier'] != 0 ? '<font class="work">'.$Player['agility'].' '.amount($Player['agilitymodifier']) : $Player['agility'])."</td><td></td></tr>\n";
		echo "\t\t</table>\n";
		echo "\t\t<br />\n";
	}
	else {
		if ($Player['destination']) {
			tablebreak();
			if ($Player['time'] <= $stardate) {

?>		<br />
		<b><?php echo $Lang['LandingOn']; ?></b>: <font class="plus"><?php echo strcap($Player['destination']); ?></font><br />
		<br />
<?php
			}
			else {

?>		<br />
		<b><?php echo $Lang['GoingTo']; ?></b>: <font class="plus"><?php echo strcap($Player['destination']); ?></font><br />
		<br />
		<?php echo $Lang['FullETA']; ?>: <b><?php echo eta($Player['time'] - $stardate); ?><br />
		<br />
		<a class="delete" href="control.php?action=canceltravel"><?php echo $Lang['CancelTravel']; ?> &gt;&gt;</a><br />
		<br />
<?php
			}
		}
		else {

			locale('places');

			$name = $Player['planet'];

			$db->query("SELECT * FROM ${prefix}markets WHERE position='$name';");
			if ($t = $db->fetchrow()) {
				$t += $Lang['places']['market'];
				$places['market'] = $t;
			}
			$db->query("SELECT * FROM ${prefix}places WHERE position='$name';");
			while ($t = $db->fetchrow()) {
				if (@$Lang['places'][$t['type']]) $t += $Lang['places'][$t['type']];
				$places[$t['type']] = $t;
			}

			if (@$places) {
				tablebreak();
		        echo "\t\t<table id=\"sub\" cellspacing=\"0\" cellpadding=\"0\">";

				$i = 0;
				
				foreach ($places as $p) {
					$requirements = '';
					if ($p['level'] || $p['reputation']) {
						if ($p['level']) $requirements = $Lang['Level'].' <font class="plus">'.$p['level'].'</font>';
						if ($p['reputation']) $requirements = ($requirements ? ', ' : '').$Lang['Reputation'].' <font class="minus">'.$p['reputation'].'</font>';
						$requirements = '<i><font class="result">'.$Lang['Requirements'].'</font>: '.$requirements.'</i>';
					}

					if (++$i % 2) {
						echo "<tr height=\"8\"><td>&nbsp;</td></tr><tr height=\"72\" valign=\"top\"><td width=\"12\">&nbsp;</td><td width=\"72\">";
						tableimg('images/pw.gif', 72, 72, "gallery/places/icons/${p['type']}.jpg", 64, 64, "description.php?subject=${p['type']}&back=control.php");
						echo "</td><td width=\"12\">&nbsp;</td><td align=\"left\">";
						echo "<a href=\"${p['type']}.php?rid=$rid\"><b>${p['name']}</b></a><br />$requirements<br />${p['description']}<br /></td>";
					}
					else {
						echo "<td width=\"12\">&nbsp;</td><td align=\"right\">";
						echo "<a href=\"${p['type']}.php?rid=$rid\"><b>${p['name']}</b></a><br />$requirements<br />${p['description']}<br /></td>";
						echo "<td width=\"12\">&nbsp;</td><td width=\"72\">";
						tableimg('images/pw.gif', 72, 72, "gallery/places/icons/${p['type']}.jpg", 64, 64, "description.php?subject=${p['type']}&back=control.php");
						echo "</td><td width=\"12\">&nbsp;</td></tr>";
					}
				}

				if ($i % 2) echo "<td colspan=\"5\">&nbsp;</td></tr>";
				echo "<tr height=\"8\"><td>&nbsp;</td></tr></table>\n";
			}

			tablebreak();

			subbegin();


// Planet info

	$db->query("SELECT * FROM `${prefix}space` WHERE `name`='${Player['planet']}' LIMIT 1;");
	$t = $db->fetchrow();

	$res = $db->query("SELECT SUM(colonists) FROM {$prefix}colonies WHERE planet='${Player['planet']}';");
	$pop = $db->fetchrow($res);

		echo "\t\t<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" align=\"center\">\n\t\t<tr valign=\"top\">\n";
		echo "\t\t<td><b>" . $Lang['Objects[]'][$t['type']] . "</b>: <font class=\"plus\">" . strcap($t['name']) . "</font>";
		
		echo "<br /><br /><b>${Lang['SizeC']}</b>: <font class=\"result\">" . $Lang['SizeT'][$t['class']] . '</font>';
		
		echo '<br />';
		
		if ($t['type'] == 'planet') {
			echo "<b>${Lang['Explored']}</b>: <font class=\"minus\">" . number_format($t['explored'], 2, $Lang['DecPoint'], ' ') . '%</font><br />';
		}

		echo "\t\t<td align=\"right\">" . ($t['system'] ? "<b>${Lang['PlanetSystem']}</b>: <font class=\"work\">${t['system']}</font>" : '&nbsp;') . '<br /><br />';
		if ($t['type'] == 'star')
		{
			echo "<b><font class=\"minus\">${Lang['StarWarning']}!</b></font><br />";
		}
		if ($t['type'] == 'planet') {
			echo "<b>${Lang['Gravity']}</b>: <font class=\"work\">" . number_format($t['gravity'], 1, $Lang['DecPoint'], ' ') . ' Q</font><br />';
			echo "\t<b>${Lang['Population']}</b>: <font class=\"result\">".strdiv((int)$pop[0])."</font><br />\n";
		}

//		echo "\t\t<td width=\"168\" height=\"168\" align=\"right\">\n";
//		tableimg('images/bw.gif', 168, 168, "gallery/space/${t['name']}.jpg", 160, 160, '', 'right');
		echo "\t\t</td>\n\t\t</tr>\n\t\t</table>\n";

// fin

			subend();

		}

//		tablebreak();
//		echo "\t\t<br /><a href=\"control.php?view=statistics&rid=$rid\">${Lang['Statistics']}&nbsp;&gt;&gt;</a><br />\n";
//		echo "\t\t<br />\n";
	}

	ob_start();
	module('galaxy', 'tip');
	$tip=ob_get_contents();
	ob_end_clean();
	
	if (!empty($tip)) {
		tablebreak(); 
		echo $tip;
	}

	tableend(($all ? ($all > 1 ? $all.$Lang[' messages'] : $all.$Lang[' message']).' ('.($unread ? '<a href="messages.php?rid='.$rid.'">'.($unread > 1 ? $unread.$Lang[' unread'] : $unread.$Lang[' unread1']).'</a>' : $unread.$Lang[' unread']).')':$Lang['No messages']));
}

require('include/footer.php');
