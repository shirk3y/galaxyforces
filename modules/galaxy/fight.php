<?php

// -------------------------------------------------------------------
// Fight	() () () +-----+ () () ()
// -------------------------------------------------------------------

function fight($attacker, $defender)
{
	global $Lang;

	$a = '<b><font class="plus">' . $attacker['login'] . '</font></b>';
	$d = '<b><font class="capacity">' . $defender['login'] . '</font></b>';
	$b = '<br />';

	$result = '';
	$damage = 0;

	$deaf = round($attacker['deaf'] + ($attacker['distancefight'] ? 0 : $attacker['strength'] - $defender['armor']));
	$hit = round(80 + 3 * ($attacker['agility'] - $defender['agility']) + $attacker['hit']);
	$block = round($defender['armor'] + ($attacker['distancefight'] ? $defender['agility'] - $attacker['agility'] : $defender['strength'] - $attacker['strength']) + $defender['block']);
	$ch = round(5 + $attacker['criticalhit']);

	if ($attacker['freeze']) $attacker['freeze'] = FALSE;
	elseif (($x = Rand(0, 99)) <= $hit) {
		if (! $x || $x <= $ch) {
			$ratio = 2 + $attacker['critical'];
			$result .= "$a ${Lang['Fight3']} (<font class=\"plus\">$x</font> : <font class=\"capacity\">$ch</font>)$b";
		}
		elseif (($x = Rand(0, 99)) <= $block) {
			$ratio = 0;
			$result .= "$d ${Lang['Fight2']} (<font class=\"plus\">$x</font> : <font class=\"capacity\">$block</font>)$b";
		}
		else {
			$ratio = 1;
			if (($x = Rand(0, 99)) <= $deaf) {
				$result .= "$a ${Lang['Fight8']} $d (<font class=\"plus\">$x</font> : <font class=\"capacity\">$deaf</font>)$b";
				$defender['freeze'] = TRUE;
			}
		}

		$damage = round($ratio * Rand(100 * $attacker['min'], 100 * $attacker['max']) - 100 * $defender['armor']) / 100;

		if ($damage > 0) {
			if ($damage >= $defender['hp'] - 0.01) {
				$damage = $defender['hp'];
				$result .= "$a ${Lang['Fight4']} $d$b";
				$defender['hp'] = 0;
			}
			else {
				$result .= "$a ${Lang['Fight5']} $d: <font class=\"minus\"><b>$damage</b></font> (";
				$hurt = round(100 * ($defender['hp'] - $damage) / $defender['hpmax']);
				if ($hurt < 15) $result .= '<font class="minus">' . $Lang['Condition[]'][0];
				elseif ($hurt < 40) $result .= '<font class="work">' . $Lang['Condition[]'][1];
				elseif ($hurt < 75) $result .= '<font class="result">' . $Lang['Condition[]'][2];
				elseif ($hurt < 90) $result .= '<font class="plus">' . $Lang['Condition[]'][3];
				else $result .= '<font class="plus">' . $Lang['Condition[]'][4];
				$result .= ")</font>$b";
				$defender['hp'] -= $damage;
			}
		}
	}
	else $result .= "$a {$Lang['Fight1']} (<font class=\"plus\">$x</font> : <font class=\"capacity\">$hit</font>)$b";

	return array('damage' => $damage, 'attacker' => $attacker, 'defender' => $defender,  'result' => $result);
}

function actionfight($global) {
	global $fight, $login, $db, $prefix, $errors, $stardate, $Player, $Lang, $starday, $attackers, $defenders, $winner;
	$fight = '';

	$attackers = '';
	$defenders = '';

	if ($Player['mp'] < ($mp = 0.8 + $Player['level'] * 0.2)) $errors .= $Lang['ErrorYouNeedRest'] . '<br />';
	elseif ($Player['hp'] < 1) $errors .= $Lang['ErrorYouNeedHealing'] . '<br />';
	elseif (($global || checkplace('arena')) && ($name = getvar('name')) && $name != $Player['login']) {
		$db->query("SELECT `id` FROM `${prefix}users` WHERE `login`='$name';");
		if (! $db->numrows()) $errors .= $Lang['ErrorLoginNotExists'] . '<br />';
		else {
			$result = engine(0, $name);
			$attacker = $Player;
			$defender = $result['Player'];
			if ($defender['hp'] < 0.2 * $defender['hpmax']) $errors .= $Lang['ErrorEnemyTooWeak'] . '<br />';
			if ($attacker['hp'] < $attacker['hpmin'] * $attacker['hpmax'] / 100) $errors .= $Lang['ErrorURTooWeak'] . '<br />';
			if ($defender['level'] < $attacker['level'] - 9) $errors .= $Lang['ErrorLevelDifference'] . '<br />';
			if ($defender['destination']) $errors .= $Lang['ErrorEnemyNotArrived'] . '<br />';
			elseif (! $global && $defender['planet'] != $attacker['planet']) $errors .= $Lang['ErrorHackingAttempt'] . '<br />';
			if ($attacker['clan'] && ($attacker['clan'] == $defender['clan'])) $errors .= $Lang['ErrorAlly'] . '<br />';
		}
		if (! $errors) {
			$agility = $attacker['agility'] > $defender['agility'] ? $attacker['agility'] / $defender['agility'] : -($defender['agility'] / $attacker['agility']);
			if ($agility > 10) $agility = 10;
			elseif ($agility < -10) $agility = -10;

			$attackers[] = $attacker;
			$defenders[] = $defender;

			$atacker['hpstore'] = $attacker['hp'];
//				$attacker['hit'] = floor(80 + 3 * $agility) + 10;
//				$attacker['critical'] = floor(1 + ($agility > 0 ? $agility : 0));
//				$attacker['deaf'] = 5;
			$attacker['weapon'] = '';
			$attacker['freeze'] = FALSE;

			$defender['hpstore'] = $defender['hp'];
//				$defender['hit'] = floor(80 - 3 * $agility) + 10;
//				$defender['critical'] = floor(1 - ($agility < 0 ? $agility : 0));
//				$defender['deaf'] = 5;
			$defender['weapon'] = '';
			$defender['freeze'] = FALSE;

			$count = 0;

			while ($attacker['hp'] && $defender['hp']) {
				$t = fight($attacker, $defender);
				$attacker = $t['attacker'];
				$defender = $t['defender'];
				$fight .= $t['result'];
				$count++;

				if ($attacker['hp'] && $defender['hp']) {
					$t = fight($defender, $attacker);
					$attacker = $t['defender'];
					$defender = $t['attacker'];
					$fight .= $t['result'];
					$count++;
				}
			}

			$fight .= '<br />';

			$levelmodifier = $defender['level'] / $attacker['level']; if ($levelmodifier < 0.1) $levelmodifier = 0.1; elseif ($levelmodifier > 3) $r = 3;
			$hpmodifier = $defender['hpstore'] / $defender['hpmax'];

			if ($attacker['hp'] > $defender['hp']) {
				$exp = round($Player['level'] * $hpmodifier * $levelmodifier * (log(1 + $count) / 50) * $Player['exp']) / (50 + $Player['level']);
				$fight .= '<b>' . $Lang['FightWin'] . '</b><br /><br />';
				$winner = TRUE;
				$fight .= $Lang['GainedExperience'] . ': <font class="plus">' . div($exp) . '</font><br />';
				$attacker['exp'] += $exp;
				$attacker['killed']++;
				$attacker['lastkilled'] = $defender['login'];
				$attacker['score'] += Rand(1, 10);
				$defender['killedby']++;
				$defender['lastkilledby'] = $attacker['login'];
			}
			else {
				$exp = round($Player['level'] * $hpmodifier * $levelmodifier * (log(1 + $count) / 50) * $Player['exp']) / (100 - $Player['level']);
				$fight .= '<b>' . $Lang['FightLoose'] . '</b><br /><br />';
				if ($exp > $attacker['exp']) $exp = $attacker['exp'];
				$fight .= $Lang['LostExperience'] . ': <font class="minus">' . div($exp) . '</font><br />';
				$winner = FALSE;
				$attacker['exp'] -= $exp;
				$attacker['killedby']++;
				$attacker['lastkilledby'] = $defender['login'];
				$defender['score'] += Rand(1, 5);
				$defender['exp'] += $exp;
				$defender['killed']++;
				$defender['lastkilled'] = $attacker['login'];
			}

			$attacker['mp'] -= $mp;

			$db->query("UPDATE `${prefix}users` SET `score`='${defender['score']}',`online`='${defender['online']}',`hp`='${defender['hp']}',`exp`='${defender['exp']}',`mp`=${defender['mp']},`killed`='${defender['killed']}',`killedby`='${defender['killedby']}',`lastkilled`='${defender['lastkilled']}',`lastkilledby`='${defender['lastkilledby']}' WHERE `login`='${defender['login']}';");
			$db->query("UPDATE `${prefix}users` SET `score`='${attacker['score']}',`online`='${attacker['online']}',`hp`='${attacker['hp']}',`exp`='${attacker['exp']}',`mp`=${attacker['mp']},`killed`='${attacker['killed']}',`killedby`='${attacker['killedby']}',`lastkilled`='${attacker['lastkilled']}',`lastkilledby`='${attacker['lastkilledby']}' WHERE `login`='${attacker['login']}';");

			$Player = $attacker;
		}
	}
}
