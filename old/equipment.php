<?php

// ===========================================================================
// Equipment {equipment.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.3
//	Modified:	2005-11-13
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$auth = true;

require('include/header.php');

$back = getvar('back');
$page = abs(getvar('page'));
$view = getvar('view');

if (!$back) $back = 'control.php';

$pagename = $Lang['Equipment'];

$avatar = $Player['avatar'] ? $Player['avatar'] : 'gallery/avatars/noavatar.gif';

// ===========================================================================
// ERRORS
// ===========================================================================

if ($errors) {
	tablebegin('<font class="error">' . $Lang['Error'] . '!</font>', '400');
	echo "\t\t<br />\n\t\t<font class=\"h3\">${Lang['ErrorProblems']}</font><br />\n\t\t<br />\n\t\t<font class=\"error\">$errors</font>\n\t\t<br />\n\t\t<a href=\"javascript:history.back(1)\">${Lang['GoBack']} &gt;&gt;</a><br />\n\t\t<br />\n";
	tableend("<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>");
	sound('error');
}

// ===========================================================================
// RESULT
// ===========================================================================

elseif ($result) {
	tablebegin($pagename, 400);
	echo "\t\t<br /><font class=\"result\">$result</font><br /><a href=\"${_SERVER['PHP_SELF']}\">${Lang['GoBack']}&nbsp;&gt;&gt;</a><br /><br />";
	tableend($pagename);
}

// ===========================================================================
// SELL
// ===========================================================================

elseif ($view == 'sellitem' && checkplace('itemshop')) {
	tablebegin($Lang['SellItem'], 500);

	$Backpack = array();
	foreach ($Equipment as $t) if (! $t['active']) $Backpack[] = $t;

	if ($Backpack) {

		$mod = reputationmodifier($Player['reputation']);
		if (($ratio = $place['extra']) < 1) $ratio = 1;
		$ratio /= 5;

		subbegin();

		?><table width="100%" cellspacing="0" cellpadding="0"><?php

		$c = FALSE;

		foreach ($Backpack as $t) {
			$b = ! @$b;

			$icon = "gallery/items/icons/${t['name']}.jpg";

			if ($b) {
				$align = 'left';
				if ($c) echo "\t\t<tr valign=\"middle\"><td colspan=\"7\">&nbsp;</td></tr>\n"; else $c = TRUE;
				echo "\t\t<tr valign=\"middle\">\n";
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=equipment&back=equipment.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n";
				echo "\t\t<td width=\"8\">&nbsp;</td>\n";
			}
			else $align = 'right';

			echo "\t\t<td align=\"$align\"><font class=\"result\"><b>";
			echo $Lang['items'][$t['name']]['name'];
			if ($t['level']) echo " +${t['level']}";
			echo '</b></font><br />';

			echo "<b>${Lang['Price']}</b>: ".div(round($t['price'] * $ratio / $mod)).BR;

			echo "<br />";

			echo "\t<form action=\"equipment.php\" method=\"POST\"><input type=\"hidden\" name=\"action\" value=\"sellitem\"><input type=\"hidden\" name=\"view\" value=\"sellitem\"><input type=\"hidden\" name=\"id\" value=\"${t['id']}\">";
			if ($t['count']) { ?><b><?php echo $Lang['Count']; ?></b>:&nbsp;<input type="text" size="4" name="amount" value="<?php echo $t['count']; ?>">&nbsp;<?php }
			echo "<input type=\"submit\" value=\"${Lang['Sell']}\"><br /></form>\n";

			echo "</td>\n";

			echo "\t\t<td width=\"8\">&nbsp;</td>\n";

			if (! $b) {
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=equipment&back=equipment.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n\t\t</tr>\n";
			}
		}

		echo "\t\t</table>\n";
		subend();
	}

	tableend(anchor('itemshop.php', $Lang['GoBack']));
}

// ===========================================================================
// EQUIPMENT
// ===========================================================================

else {
	tablebegin($pagename);

	subbegin('images/table-b2.jpg');

	echo "\t<script>\n\t<!--\n\n\tfunction ask(\$url) {\n\t\tif (confirm('${Lang['AreYouSure?']}')) location.href = \$url;\n\t}\n\n\tfunction avatar() {\n\t\t\$msg = prompt('${Lang['EnterAvatarURL']}', '');\n\t\tif (\$msg > '') {\n\t\t\t\$msg = \$msg.replace(/\\+/g,\"%2B\"); // code: kot\n\t\t\t\$msg = \$msg.replace(/\\&/g,\"%26\");\n\t\t\t\$msg = \$msg.replace(/\\#/g,\"%23\");\n\t\t\t\$url = '${_SERVER['PHP_SELF']}?name=${name}&rid=${rid}&action=changeplayeravatar&url=' + \$msg;\n\t\t\tdocument.location.href = \$url;\n\t\t}\n";
	if ($Player['avatar']) echo "\t\telse if (\$msg != null) {\n\t\t\t\$url = '${_SERVER['PHP_SELF']}?name=${name}&rid=${rid}&action=changeplayeravatar';\n\t\t\tdocument.location.href = \$url;\n\t\t}\n";
	echo "\t}\n\t//-->\n\t</script>\n";

?>
	<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr valign="top">
	<td align="left">
		<b><?php echo $Lang['Name']; ?></b>: <a href="whois.php?name=<?php echo $Player['login']; ?>"><?php echo $Player['login']; ?></a><br />
		<?php echo $Player['clan'] ? "<b>${Lang['Group']}</b>: <a href=\"clan.php\">${Player['clan']}</a>" : ''; ?><br />
		<br />
		<b><?php echo $Lang['Strength']; ?></b>: <font class="result"><?php echo floor(100 * $Player['strength']) / 100; ?></font><br />
		<b><?php echo $Lang['Agility']; ?></b>: <font class="work"><?php echo floor(100 * $Player['agility']) / 100; ?></font><br />
		<br />
<?php
	echo "\t\t<b>[MP]</b>: <font class=\"" . ($Player['mp'] > $Player['mpmax'] ? 'work' : 'result') . "\">${Player['mp']}</font> / <font class=\"capacity\">${Player['mpmax']}</font> " . amount($Player['mpgain']) . "<br />\n";
	echo "\t\t<b>[HP]</b>: <font class=\"" . ($Player['hp'] > $Player['hpmax'] ? 'work' : 'result') . "\">${Player['hp']}</font> / <font class=\"capacity\">${Player['hpmax']}</font> " . amount($Player['hpgain']) . "<br />\n";
?>
	</td>
	<td width="8">&nbsp;</td>
	<td align="left">
		<b><?php echo $Lang['Level']; ?></b>:&nbsp;<font class="plus"><?php echo $Player['level']; ?></font><br />
		<b><?php echo $Lang['Reputation']; ?></b>:&nbsp;<font class="result"><?php echo $Player['reputation']; ?></font> (<?php echo $playernature; ?>)<br />
		<br />
		<b><?php echo $Lang['Damage']; ?></b>:&nbsp;<font class="plus"><?php echo $Player['min']; ?> - <?php echo $Player['max']; ?></font><br />
		<b><?php echo $Lang['Armor']; ?></b>:&nbsp;<font class="minus"><?php echo $Player['armor']; ?></font><br />
		<br />
<?php
	if ($Player['hit']) echo "\t\t<b>${Lang['Hit']}</b>:&nbsp;" . amount($Player['hit'], 0) . "</font><br />\n";
	if ($Player['criticalhit']) echo "\t\t<b>${Lang['CriticalHit']}</b>:&nbsp;" . amount($Player['criticalhit'], 0) . "</font><br />\n";
	if ($Player['critical']) echo "\t\t<b>${Lang['Critical']}</b>:&nbsp;" . amount($Player['critical']) . "</font><br />\n";
	if ($Player['block']) echo "\t\t<b>${Lang['Block']}</b>:&nbsp;" . amount($Player['block'], 0) . "</font><br />\n";
	if ($Player['deaf']) echo "\t\t<b>${Lang['Deafness']}</b>:&nbsp;" . amount($Player['deaf'], 0) . "</font><br />\n";
	if ($Player['hide']) echo "\t\t<b>${Lang['Hiding']}</b>:&nbsp;" . amount($Player['hide'], 0) . "</font><br />\n";
	if ($Player['protection']) echo "\t\t<b>${Lang['Protection']}</b>:&nbsp;" . amount($Player['protection'], 0) . "</font><br />\n";

?>		<br />
	<td width="8">&nbsp;</td>
	<td width="168" align="right">
<?php tableimg('images/bw.gif', 168, 168, $avatar, 160, 160, 'javascript:avatar()', 'right'); ?>
	</td>
	</tr>
	</table>
<?php

subend();

	$Active = '';
	$Backpack = '';

	if ($Equipment)
		foreach ($Equipment as $t)
			if ($t['active']) $Active[] = $t;
			else $Backpack[] = $t;

	if ($Active) {

		tablebreak();
		subbegin('images/table-a1.jpg');

		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

		$b = FALSE;
		$c = FALSE;

		foreach ($Active as $t) {
			$b = ! $b;

			$icon = "gallery/items/icons/${t['name']}.jpg";

			if ($b) {
				$align = 'left';

				if ($c) echo "\t\t<tr valign=\"middle\"><td colspan=\"7\">&nbsp;</td></tr>\n"; else $c = TRUE;
				echo "\t\t<tr valign=\"middle\">\n";
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=equipment&back=equipment.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n";
			}
			else {
				$align = 'right';

				echo "\t\t<td>&nbsp;</td>\n";
			}

			echo "\t\t<td width=\"8\">&nbsp;</td>\n\t\t<td align=\"$align\"><font class=\"result\"><b>";
			echo $Lang['items'][$t['name']]['name'];
			if ($t['level']) echo " +${t['level']}";
			echo '</b></font><br />';

			$r = '';
			if ($t['min'] || $t['max']) $r .= ($r ? ', ' : '') . "<b>${Lang['Damage']}</b>: <font class=\"plus\">${t['min']}-${t['max']}</font>";
			if ($t['armor']) $r .= ($r ? ', ' : '') . "<b>${Lang['Armor']}</b>: <font class=\"minus\">${t['armor']}</font>";

			echo "$r<br />";

			echo "<br /><a href=\"equipment.php?action=unequip&id=${t['id']}&rid=$rid\">${Lang['Unequip']} &gt;&gt;</a>";
			echo "</td>\n";

			echo "\t\t<td width=\"8\">&nbsp;</td>\n";

			if (! $b) {
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=equipment&back=equipment.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n\t\t</tr>\n";
			}
		}

		echo "\t\t</table>\n";
		subend();
	}

	tableend($Lang['Equipment']);

	echo "\t\t<br />\n";

	tablebegin($Lang['Ship']);

	subbegin('images/table-a3.jpg');

	$ship = $Player['ship'];

	echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n\t\t<tr>\n";

	echo "\t\t<td width=\"72\" align=left>\n";
	tableimg('images/pw.gif', 72, 72, "gallery/units/icons/${ship}.jpg", 64, 64, "description.php?type=unit&back=equipment.php&page=$page&subject=$ship", '', $Lang['units'][$ship]['name']);
	echo "\t\t</td>\n";

	echo "\t\t<td width=\"8\">&nbsp;</td>\n";
	echo "\t\t<td align=\"left\">\n";

	echo "\t\t<font class=\"capacity\"><b>" . $Lang['units'][$ship]['name'] . '</b></font><br />';
	echo '<br />';
	echo "<b>${Lang['Speed']}</b>: <font class=\"plus\">" . $Var['units'][$ship]['speed'] . '</font><br />';
	echo "<br />\n";
	echo "\t\t</td>";

	echo "\t\t<td align=\"right\" width=\"8\">&nbsp;</td>\n";
	echo "\n\t\t</tr>\n\t\t</table>\n";

/*
	if ($guns = $Player['guns']) {
		echo "\t\t</td>\n\t\t<td width=\"8\">&nbsp;</td>\n\t\t<td align=\"right\">\n";
		tableimg('images/pw.gif', 72, 72, "gallery/equipment/icons/${guns['name']}.jpg", 64, 64, "description.php?name=${guns['name']}", '', $Lang['equipment'][$guns['name']]['name']);
	}

	if ($shields = $Player['shields']) {
		echo "\t\t</td>\n\t\t<td width=\"8\">&nbsp;</td>\n\t\t<td align=\"right\">\n";
		tableimg('images/pw.gif', 72, 72, "gallery/equipment/icons/${shields['name']}.jpg", 64, 64, "description.php?name=${shields['name']}", '', $Lang['equipment'][$shields['name']]['name']);
	}

	if ($engine = $Player['engine']) {
		echo "\t\t</td>\n\t\t<td width=\"8\">&nbsp;</td>\n\t\t<td align=\"right\">\n";
		tableimg('images/pw.gif', 72, 72, "gallery/equipment/icons/${engine['name']}.jpg", 64, 64, "description.php?name=${engine['name']}", '', $Lang['equipment'][$engine['name']]['name']);
	}

	echo "\t\t</td>\n\t\t</tr>\n\t\t</table>\n";
*/

	subend();

	if ($Backpack) {

		tablebreak();
		subbegin('images/table-a2.jpg');

		echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";

		$b = FALSE;
		$c = FALSE;

		foreach ($Backpack as $t) {
			$b = ! $b;

			$icon = "gallery/items/icons/${t['name']}.jpg";

			if ($b) {
				$align = 'left';
				if ($c) echo "\t\t<tr valign=\"middle\"><td colspan=\"7\">&nbsp;</td></tr>\n"; else $c = TRUE;
				echo "\t\t<tr valign=\"middle\">\n";
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=equipment&back=equipment.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n";
				echo "\t\t<td width=\"8\">&nbsp;</td>\n";
			}
			else $align = 'right';

			echo "\t\t<td align=\"$align\"><font class=\"result\"><b>";
			echo $Lang['items'][$t['name']]['name'];
			if ($t['level']) echo " +${t['level']}";
			echo '</b></font><br />';

			$sr = '';
			if ($t['min'] || $t['max']) $sr .= ($sr ? ', ' : '') . "<b>${Lang['Damage']}</b>:&nbsp;<font class=\"plus\">${t['min']}-${t['max']}</font>";
			if ($t['armor']) $sr .= ($sr ? ', ' : '') . "<b>${Lang['Armor']}</b>:&nbsp;<font class=\"minus\">${t['armor']}</font>";
			if ($t['count']) $sr .= ($sr ? ', ' : '') . "<b>${Lang['Count']}</b>:&nbsp;<font class=\"minus\">" . div($t['count']) . '</font>';

			echo "$sr<br /><br />";

			if ($view == 'giveitems' && $name) {
				echo '<form action="equipment.php" method="POST"><input type="hidden" name="action" value="giveitems" /><input type="hidden" name="view" value="giveitems" /><input type="hidden" name="id" value="'.$t['id'].'" /><input type="hidden" name="name" value="'.$name.'" />';
				if ($t['count']) echo $Lang['Count'].':&nbsp;<input type="text" size="5" name="amount" value="0" />&nbsp;';
				echo '<input type="submit" value="'.$Lang['Give'].'" /></form>';
			}
			else {
				switch ($t['type']) {
				case 'guns': case 'shields': case 'engine': case 'belt': case 'helmet': case 'armor': case 'belt': case 'gloves': case 'implant': case 'artifact': case 'weapon': case 'weapon2':					
					echo "<a href=\"equipment.php?rid=$rid&action=equip&id=${t['id']}\">${Lang['Equip']}&nbsp;&gt;&gt;</a> ";
					break;
				case 'item':
					echo "<a href=\"equipment.php?rid=$rid&action=use&id=${t['id']}\">${Lang['Use']}&nbsp;&gt;&gt;</a> ";
					break;
				case 'drink':
					echo "<a href=\"equipment.php?rid=$rid&action=use&id=${t['id']}\">${Lang['Drink']}&nbsp;&gt;&gt;</a> ";
					break;
				}

				echo "<a href=\"javascript:ask('equipment.php?rid=$rid&action=dropitem&id=${t['id']}')\" class=\"delete\">${Lang['DropItem']}&nbsp;&gt;&gt;</a>";
			}
	
			echo "</td>\n";

			echo "\t\t<td width=\"8\">&nbsp;</td>\n";

			if (!$b) {
				echo "\t\t<td width=\"72\">\n";
				tableimg('images/pw.gif', 72, 72, $icon, 64, 64, "description.php?type=equipment&back=equipment.php&subject=${t['name']}&id=${t['id']}");
				echo "\t\t</td>\n\t\t</tr>\n";
			}
		}

		echo "\t\t</table>\n";
		subend();
	}

	tableend("<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>");
}

require('include/footer.php');
