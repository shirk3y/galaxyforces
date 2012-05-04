<?php

// ===========================================================================
// Description {description.php}
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	1.5
//	Modified:	2005-11-13
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// This file is a part of Galaxy Forces project.
// ===========================================================================

$auth = true;

require('include/header.php');

require('modules/galaxy/descriptions.php');

$id = abs(getvar('id'));
$page = getvar('page');
$subject = getvar('subject');
$type = getvar('type');

if ($page) $back .= "?page=$page";

tablebegin($Lang['Description'], 500);

if (isset($Descriptions[$subject])) {
	$description = $Descriptions[$subject];

	locale('colony');

	subbegin();

?>		<table background="images/bw.gif" width="168" height="168" cellspacing="0" cellpadding="0" hspace="4" border="0" align="right">
		<tr height="168" valign="center">
		<td><center><img src="gallery/<?php echo $description['picture']; ?>" alt="" width="160" height="160" hspace="0" vspace="0" border="0"></center></td>
		</tr>
		</table>
<?php
	echo "\t<b>${Lang['Name']}</b>: <font class=\"plus\">${description['name']}</font><br />\n";
	echo "\t<b>${Lang['Type']}</b>: <font class=\"capacity\">${description['type']}</font><br />\n";

	if ($description['description']) echo "\t\t<p /><font class=\"result\">${description['description']}</font>\n";
	else echo "\t\t<br />\n";

	subend();

	switch ($type) {
		case 'unit':
			tablebreak();
			subbegin('images/table-a1.jpg');

			echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n\t\t<tr valign=\"top\">\n";
			echo "\t\t<td>\n";
			echo "\t\t<b>${Lang['Attack']}:</b> <font class=\"plus\">" . div(@$Var['units'][$subject]['attack']) . "</font><br />\n";
			echo "\t\t<b>${Lang['Damage']}:</b> <font class=\"minus\">" . div($Var['units'][$subject]['damage']) . "</font><br />\n";
			if (@$Var['units'][$subject]['speed']) echo "\t\t<b>${Lang['Speed']}:</b> <font class=\"work\">" . div($Var['units'][$subject]['speed']) . "</font><br />\n";
			if (@$Var['units'][$subject]['capacity']) echo "\t\t<b>${Lang['Capacity']}:</b> <font class=\"capacity\">".div($Var['units'][$subject]['capacity'])."</font><br />\n";
			if (@$Var['units'][$subject]['quarters']) echo "\t\t<b>${Lang['Quarters']}:</b> <font class=\"plus\">".div($Var['units'][$subject]['quarters'])."</font><br />\n";
			echo "\t\t<br />\n";
			echo "\t\t<b>${Lang['Score']}:</b> <font class=\"capacity\">" . div($Var['units'][$subject]['score']) . "</font><br />\n";
			if (@$Var['units'][$subject]['exp']) echo "\t\t<b>${Lang['Experience']}:</b> <font class=\"capacity\">" . div($Var['units'][$subject]['exp']) . "</font><br />\n";

			if (@$Var['units'][$subject]['workforce'] || @$Var['units'][$subject]['scienceforce'] || @$Var['units'][$subject]['communications']) {
				echo '<br />';
				if (@$Var['units'][$subject]['workforce']) echo '<b>'.$Lang['Workforce'].'</b>: <font class="work">'.$Var['units'][$subject]['workforce'].'</font><br />';
				if (@$Var['units'][$subject]['scienceforce']) echo '<b>'.$Lang['Scienceforce'].'</b>: <font class="capacity">'.$Var['units'][$subject]['scienceforce'].'</font><br />';
				if (@$Var['units'][$subject]['communications']) echo '<b>'.$Lang['Communications'].'</b>: <font class="plus">'.$Var['units'][$subject]['communications'].'</font><br />';
			}

			echo "\t\t</td>\n\t\t<td align=\"right\">\n";

			if (@$Var['units'][$subject]['credits']) echo "\t\t<b>${Lang['Credits']} [!]:</b> <font class=\"result\">" . div($Var['units'][$subject]['credits']) . "</font><br />\n";
			if (@$Var['units'][$subject]['energy']) echo "\t\t<b>${Lang['Energy']} [E]:</b> <font class=\"result\">" . div($Var['units'][$subject]['energy']) . "</font><br />\n";
			if (@$Var['units'][$subject]['metal']) echo "\t\t<b>${Lang['Metal']} [M]:</b> <font class=\"result\">" . div($Var['units'][$subject]['metal']) . "</font><br />\n";
			if (@$Var['units'][$subject]['uran']) echo "\t\t<b>${Lang['Uran']} [U]:</b> <font class=\"result\">" . div($Var['units'][$subject]['uran']) . "</font><br />\n";
			if (@$Var['units'][$subject]['food']) echo "\t\t<b>${Lang['Food']} [F]:</b> <font class=\"result\">" . div($Var['units'][$subject]['food']) . "</font><br />\n";
			if (@$Var['units'][$subject]['crystals']) echo "\t\t<b>${Lang['Crystals']} [C]:</b> <font class=\"result\">" . div($Var['units'][$subject]['crystals']) . "</font><br />\n";
			if (@$Var['units'][$subject]['work']) echo "\t\t<b>${Lang['Work']} [W]:</b> <font class=\"result\">" . div($Var['units'][$subject]['work']) . "</font><br />\n";
			if ($enemies = @$Var['units'][$subject]['enemy']) {
				echo '<br /><b>'.$Lang['Enemy'].':</b> ';
				foreach (explode(',', $enemies) as $enemy) echo (@$i++ ? ', ' : '').'<a href="description.php?subject='.$enemy.'&back='.$back.'&type=unit">'.$Lang['units'][$enemy]['name'].'</a>';
				echo '<br />';
			}
			echo "\t\t</td>\n\t\t</tr>\n\t\t</table>\n";

			subend();
			break;
		case 'equipment':
		case 'items':
			if (! $id) break;
			$db->query("SELECT * FROM `${prefix}$type` WHERE `id`='$id';");
			if (! ($t = $db->fetchrow())) break;

			$l = '';
			$r = '';

			if ($t['max'] - $t['min'] > 0.1) $l .= "\t\t<b>${Lang['Damage']}:</b> <font class=\"plus\">${t['min']} - ${t['max']}</font><br />\n";
			if ($t['armor']) $l .= "\t\t<b>${Lang['Armor']}:</b> <font class=\"plus\">${t['armor']}</font><br />\n";
			if ($t['hit']) $l .= "\t\t<b>${Lang['Hit']}:</b> " . amount($t['hit'], 0) . "<br />\n";
			if ($t['criticalhit']) $l .= "\t\t<b>${Lang['CriticalHit']}:</b> " . amount($t['criticalhit'], 0) . "<br />\n";
			if ($t['critical']) $l .= "\t\t<b>${Lang['Critical']}:</b> " . amount($t['critical']) . "<br />\n";
			if ($t['block']) $l .= "\t\t<b>${Lang['Block']}:</b> " . amount($t['block'], 0) . "<br />\n";
			if ($t['deaf']) $l .= "\t\t<b>${Lang['Deafness']}:</b> " . amount($t['deaf'], 0) . "<br />\n";
			if ($t['hide']) $l .= "\t\t<b>${Lang['Hiding']}:</b> " . amount($t['hide'], 0) . "<br />\n";
			if ($t['protection']) $l .= "\t\t<b>${Lang['Protection']}:</b> " . amount($t['protection'], 0) . "<br />\n";
			if ($t['type'] == 'weapon' || $t['type'] == 'weapon2') $l .= "\t\t<b>${Lang['DistanceWeapon']}:</b> <font class=\"work\">" . ($t['distance'] ? $Lang['Yes'] : $Lang['No']) . "</font><br />\n";
			if ($t['req_level']) $r .= "\t\t<b>${Lang['RequiredLevel']}:</b> <font class=\"result\">${t['req_level']}</font><br />\n";
			if ($t['req_strength']) $r .= "\t\t<b>${Lang['RequiredStrength']}:</b> <font class=\"result\">${t['req_strength']}</font><br />\n";
			if ($t['req_agility']) $r .= "\t\t<b>${Lang['RequiredAgility']}:</b> <font class=\"result\">${t['req_agility']}</font><br />\n";
			if ($t['req_psi']) $r .= "\t\t<b>${Lang['RequiredPsi']}:</b> <font class=\"result\">${t['req_psi']}</font><br />\n";
			if ($t['req_force']) $r .= "\t\t<b>${Lang['RequiredForce']}:</b> <font class=\"result\">${t['req_force']}</font><br />\n";
			if ($t['req_intellect']) $r .= "\t\t<b>${Lang['RequiredIntellect']}:</b> <font class=\"result\">${t['req_intellect']}</font><br />\n";
			if ($t['req_knowledge']) $r .= "\t\t<b>${Lang['RequiredKnowledge']}:</b> <font class=\"result\">${t['req_knowledge']}</font><br />\n";
			if ($t['req_pocketstealing']) $r .= "\t\t<b>${Lang['RequiredPocketStealing']}:</b> <font class=\"result\">${t['req_pocketstealing']}</font><br />\n";
			if ($t['req_hacking']) $r .= "\t\t<b>${Lang['RequiredHacking']}:</b> <font class=\"result\">${t['req_hacking']}</font><br />\n";
			if ($t['req_alcoholism']) $r .= "\t\t<b>${Lang['RequiredAlcoholism']}:</b> <font class=\"result\">${t['req_alcoholism']}</font><br />\n";
			if ($t['parameters']) $l .= "\t\t<b>${Lang['Parameters']}:</b> <font class=\"result\">${t['parameters']}</font><br />\n";
			if ($t['use']) $l .= "\t\t<b>${Lang['InitiativeRequired']}:</b> <font class=\"minus\">${t['use']}</font><br />\n";

			if ($t['class']) $r .= "<b>${Lang['Class']}</b>: <font class=\"work\">" . $Lang['ItemClasses[]'][$t['class']] . "</font><br />\n";


			if ($l || $r) {
				tablebreak();
				subbegin('images/table-a1.jpg');
				echo "\t\t<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n\t\t<tr valign=\"top\">\n\t\t<td>\n$l\t\t</td>\n\t\t<td align=\"right\">\n$r\t\t</td>\n\t\t</tr>\n\t\t</table>\n";
				subend();
			}

			if ($type == 'items' && $User['usergroup'] == $Config['Administrators']) {
				tablebreak();
				echo "\t<form action=\"admin.php\" method=\"POST\"><br />${Lang['Login']}: &nbsp; <input type=\"hidden\" name=\"action\" value=\"give\"><input type=\"hidden\" name=\"id\" value=\"$id\"><input type=\"text\" name=\"name\">";
				if ($t['count']) echo " &nbsp; ${Lang['Count']}: &nbsp; <input type=\"text\" name=\"count\" value=\"1\">";
				echo " &nbsp; <input type=\"submit\" value=\"${Lang['Give']}\"><br /></form>\n";
				echo "\t<br />\n";
			}

			break;
	}

}
else echo "\t\t<br />\n\t\t${Lang['NotAvailable']}<br />\n\t\t<br />\n";

tableend($back ? "<a href=\"$back\">${Lang['GoBack']} &gt;&gt;</a>" : $Lang['Description']);

require('include/footer.php');
