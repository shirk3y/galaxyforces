<?php

// -------------------------------------------------------------------
// Market (Buy)
// -------------------------------------------------------------------

global $valid_resources;

function actionbuy() {
	global $login, $db, $prefix, $Lang, $Player, $Colony, $amount, $place, $name, $errors, $valid_resources;
	if ($Colony && in_array($name, $valid_resources) && $amount && checkplace('market') && ($averageprice = @$place[$name.'buyaverage'])) {

		if (!($price = $place[$name.'buy'])) $price = $averageprice;
		elseif ($price < 0.66 * $averageprice) $price = 0.66 * $averageprice;
		elseif ($price > 1.33 * $averageprice) $price = 1.33 * $averageprice;	
		$price *= reputationmodifier($Player['reputation']);		
		
		if (floor($Player['credits']) >= floor($credits = $amount * $price)) {
			if (!isset($Colony[$name.'capacity']) || floor($Colony[$name.'capacity'] - $Colony[$name]) >= $amount) {
				$Colony[$name] += $amount;
				$db->query("UPDATE `${prefix}colonies` SET `$name`='${Colony[$name]}' WHERE `id`='${Colony['id']}';");
				$Player['credits'] -= $amount * $price;
				$db->query("UPDATE `${prefix}users` SET `credits`='${Player['credits']}' WHERE `login`='$login';");
			}
			else $errors .= $Lang['ErrorNotEnoughCapacity'].'<br />';
		}
		else $errors .= $Lang['ErrorNotEnoughCredits'].'<br />';
	}
}

// -------------------------------------------------------------------
// Market (Sell)
// -------------------------------------------------------------------

function actionsell() {
	global $login, $db, $prefix, $Lang, $Player, $Colony, $amount, $place, $places, $name, $errors, $valid_resources;
	if ($Colony && in_array($name, $valid_resources) && $amount && checkplace('market') && ($averageprice = @$place[$name.'sellaverage'])) {
	
		if (!($price = $place[$name.'sell'])) $price = $averageprice;
		elseif ($price < 0.66 * $averageprice) $price = 0.66 * $averageprice;
		elseif ($price > 1.33 * $averageprice) $price = 1.33 * $averageprice;
		$price /= reputationmodifier($Player['reputation']); 

		if (floor($Colony[$name]) >= floor($amount)) {
			$Colony[$name] -= $amount;
			$db->query("UPDATE `${prefix}colonies` SET `$name`='{$Colony[$name]}' WHERE `id`='{$Colony['id']}';");
			$Player['credits'] += $amount * $price;
			$db->query("UPDATE `${prefix}users` SET `credits`='{$Player['credits']}' WHERE `login`='$login';");
		}
		else $errors .= $Lang['ErrorNotEnoughResources'].'<br />';
	}
}

// -------------------------------------------------------------------
// Bank (Deposit)
// -------------------------------------------------------------------

function actiondeposit() {
	global $login, $db, $prefix, $Player, $amount, $errors, $places, $Lang;
	if (checkplace('bank')) {
		if ($amount && ($amount <= $Player['credits'])) {
			if ($amount + $Player['bank'] > $places['bank']['parameters']) {
				$errors .= $Lang['ErrorCannotDeposit'] . '<b>' . div($places['bank']['parameters']) . '<b>!<br />';
			}
			else {
				$Player['credits'] -= $amount;
				$Player['bank'] += $amount;
				$db->query("UPDATE `${prefix}users` SET `credits` = '${Player['credits']}', `bank` = '${Player['bank']}' WHERE `login` = '$login' LIMIT 1");
			}
		}
	}
}

// -------------------------------------------------------------------
// Bank (Withdraw)
// -------------------------------------------------------------------

function actionwithdraw() {
	global $login, $db, $prefix, $Player, $amount, $places;
	if (checkplace('bank')) {
		if ($amount && ($amount <= $Player['bank'])) {
			$Player['credits'] += $amount;
			$Player['bank'] -= $amount;
			$db->query("UPDATE `${prefix}users` SET `credits` = '${Player['credits']}', `bank` = '${Player['bank']}' WHERE `login` = '$login' LIMIT 1");
		}
	}
}

// -------------------------------------------------------------------
// Bank (Transfer)
// -------------------------------------------------------------------

function actionbanktransfer() {
	global $login, $db, $prefix, $Player, $Lang, $amount, $place, $errors, $result, $name;
	if (checkplace('bank')) {
		if (($credit = equipmentparameters('creditcard')) > 0) {
			if (($tax = log($place['parameters'] / 1000) - log($credit / 10000)) < 0.5) $tax = 0.5;			
			if (($cost = round($amount * (100 + $tax) / 100)) < 100) $cost = 100;
			if ($amount > $place['parameters'] || $amount > $credit) $errors .= $Lang['BankLimit'].'<br />';
			if ($Player['bank'] < $cost) $errors .= $Lang['ErrorNotEnoughCredits'].'<br />';
			if ($Player['login'] == $name) $errors .= $Lang['ErrorCannotDonate'].'<br />';
			if (!$errors) {
				$db->query("SELECT bank FROM {$prefix}users WHERE login='".$db->safe($name)."';");
				if (!($row = $db->fetchrow())) $errors .= $Lang['ErrorLoginNotExists'].'<br />'; 
				elseif ($row['bank'] >= $place['parameters']) $errors .= $Lang['BankLimit'].'<br />';
				else {
					$bank = $row['bank'] + $amount;
					$Player['bank'] -= $cost;
					$db->query("UPDATE {$prefix}users SET bank='".$bank."' WHERE login='".$db->safe($name)."';");
					$db->query("UPDATE {$prefix}users SET bank='".$Player['bank']."' WHERE login='".$Player['login']."';");
					$result .= $Lang['DonationFor'].' <a href="whois.php?name='.$name.'">'.$name.'</a><br />';
					$result .= $Lang['Credits'].': <b>'.div($amount).'</b> [!]<br />';
					$result .= '<font class="minus">'.$Lang['Tax'].': <b>'.div($cost - $amount).'</b> [!]</font><br />';
				}
			}
		}
		else $errors .= $Lang['NoCC'].'<br />';
/*		if ($amount && ($amount <= $Player['bank'])) {
			$Player['credits'] += $amount;
			$Player['bank'] -= $amount;
			$db->query("UPDATE `${prefix}users` SET `credits` = '${Player['credits']}', `bank` = '${Player['bank']}' WHERE `login` = '$login' LIMIT 1");
		}
*/
	}
}

// -------------------------------------------------------------------
// Healer
// -------------------------------------------------------------------

function actionheal() {
	global $login, $db, $prefix, $errors, $Player, $Lang, $places;
	$errors = '';
	if (checkplace('healer') && $Player['hpmax'] > $Player['hp']) {
		if (($cost = round($places['healer']['parameters'] * ($Player['hpmax'] - $Player['hp']) * 20)) < $Player['credits']) {
			$Player['credits'] -= $cost;
			$Player['hp'] = $Player['hpmax'];
			if ($Player['mp'] > 10) $Player['mp'] -= 0.3;
			elseif ($Player['mp'] > 1) $Player['mp'] -= 0.15;
			$db->query("UPDATE `${prefix}users` SET `credits`='${Player['credits']}',`hp`='${Player['hp']}',`mp`='${Player['mp']}' WHERE `login`='$login';");
		}
		else $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
	}
}

// -------------------------------------------------------------------
// Academy
// -------------------------------------------------------------------

function actionacademy() {
	global $login, $db, $prefix, $Player, $Colony, $amount, $place, $errors, $Lang, $result;
	if ($Colony && $amount && checkplace('academy') && ($cost = round(reputationmodifier($Player['reputation'])*$place['parameters']))) {
		if ($amount > $Colony['colonistsfree']) $errors .= $Lang['ExE1'].'<br />';
		if ($amount * $cost > $Player['credits']) $errors .= $Lang['ErrorNotEnoughCredits'].'<br />';
		if ($amount > $Colony['barracks'] * 50 - $Colony['soldiers']) $errors .= $Lang['NoRoomForS'].'<br />';
		if (! $errors) {
			$Player['credits'] -= $amount * $cost;
			$Colony['colonists'] -= $amount;
			$Colony['colonistsfree'] -= $amount;
			$Colony['soldiers'] += $amount;
			$db->query("UPDATE {$prefix}users SET `credits`='{$Player['credits']}' WHERE `login`='{$Player['login']};");
			$db->query("UPDATE {$prefix}colonies SET `colonists`='{$Colony['colonists']}', `soldiers`='{$Colony['soldiers']}' WHERE `id`='${Colony['id']}';");
			$result = $amount;
		}
	}
}

// -------------------------------------------------------------------
// Mercenary
// -------------------------------------------------------------------

function actionhire() {
	global $login, $db, $prefix, $Player, $Colony, $amount, $name, $errors, $scientistshirecost, $colonistshirecost, $Lang, $places;
	if (!in_array($name, array("", "colonists", "scientists"))) {
		echolog("Action 'hire', value incorrect for \$name - $name", "security");
		return false;
	}
	if ($Colony && $amount && checkplace('mercenary') && $name && ($price = $places['mercenary']['parameters'])) {
		$price *= ($name == 'scientists' ? $scientistshirecost : $colonistshirecost);
		if ($amount > 20 + 20 * $Colony['flats'] - $Colony['colonists'] - $Colony['scientists']) $errors .= $Lang['ErrNoRoom'] . '<br />';
		elseif ($Player['credits'] < $amount * $price) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
		else {
			$Colony[$name] += $amount;
			$Player['credits'] -= $amount * $price;
			$db->query("UPDATE `${prefix}colonies` SET `$name` = '${Colony[$name]}' WHERE `name` = '${Colony['name']}' LIMIT 1");
			$db->query("UPDATE `${prefix}users` SET `credits` = '${Player['credits']}' WHERE `login` = '$login' LIMIT 1");
		}
	}
}

// -------------------------------------------------------------------
// Teleport
// -------------------------------------------------------------------

function actionteleport() {
	global $db, $prefix, $Player, $Lang, $errors, $name, $place;
	$errors = '';
	if (checkplace('teleport')) {
		$names = explode(',', $place['parameters']);
		$prices = explode(',', $place['extra']);
		$b = FALSE;
		for ($i = 0; $i < count($names); $i++) {
			if ($names[$i] == $name) { 
				if ($Player['credits'] < $prices[$i]) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
				elseif ($Player['mp'] >= 5) {
					$Player['mp'] -= 5;
					$Player['credits'] -= $prices[$i];
					$Player['planet'] = $name;
					$db->query("UPDATE ${prefix}users SET mp='${Player['mp']}',planet='${Player['planet']}',credits='${Player['credits']}' WHERE id='${Player['id']}' LIMIT 1;");
				}
				else $errors .= $Lang['ErrorNotEnoughMP'] . '<br />';
				break;
			}
		}
	}
}

// -------------------------------------------------------------------
// Tracker
// -------------------------------------------------------------------

function actiontrack() {
	global $login, $db, $prefix, $Player, $Lang, $errors, $tracker, $places, $stardate, $name;
	$errors = '';
	$tracker = '';
	if (checkplace('tracker') && $name) {
		$cost = $places['tracker']['parameters'];
		$db->query("SELECT `login`, `planet`, `destination`, `time` FROM `${prefix}users` WHERE `login` = '$name' LIMIT 1");
		if ($t = $db->fetchrow()) {
			if ($Player['credits'] < $cost) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
			else {
				$Player['credits'] -= $cost;
				$db->query("UPDATE `${prefix}users` SET `credits` = '${Player['credits']}' WHERE `login` = '$login' LIMIT 1");
				if ($t['destination']) {
					$tracker = '<a href="whois.php?name=' . $t['login'] . '">' . $t['login'] . '</a> ' . $Lang['IsTravelingFrom'] . ' <font class="result">' . $t['planet'] . '</font> ' . $Lang['TravelingTo'] . ' <font class="result">' . $t['destination'] . '</font>.<br />';
					$tracker .= '<br /><b>ETA</b>: <font class="work">' . eta($t['time'] - $stardate) . '</font>';
				}
				else $tracker = '<a href="whois.php?name=' . $t['login'] . '">' . $t['login'] . '</a> ' . $Lang['TLanded'] . ' <font class="result">' . $t['planet'] . '</font>.';
			}
		}
		else $errors .= $Lang['ErrorLoginNotExists'] . '<br />';
	}
}

// -------------------------------------------------------------------
// Gambler
// -------------------------------------------------------------------

function actiongamble() {
	global $login, $db, $prefix, $Player, $Colony, $Lang, $result, $places, $errors;
	$errors = '';
	$result = '';
	if (checkplace('gambler')) {
		$cost = $places['gambler']['parameters'];
		if ($Player['credits'] < $cost) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
		elseif ($Player['mp'] < 0.1 + 0.1 * $Player['level']) $errors .= $Lang['ErrorNotEnoughMP'] . '<br />';
		else {
			$Player['mp'] -= 0.1 + 0.1 * $Player['level'];
			if (Rand(0, 199) < 2) {
				$exp = round(33 * Rand(1, 5 * $cost) * $Player['level']) / 100;
				$Player['exp'] += $exp;
				$result = $Lang['GambleTE'] . $exp . '!<br />';
			}
			elseif ($Colony && Rand(0, 99) < 1) {
				$Colony['crystals'] += $crystals = floor(Rand(1, $cost) * $Player['level'] / 25) + 1;

				$result = $Lang['GambleC'] . $crystals . '!<br />';
				$db->query("UPDATE `${prefix}colonies` SET `crystals`='${Colony['crystals']}' WHERE `id`='${Colony['id']}';");
			}
			elseif (Rand(0, 199) < 4) {
				$Player['credits'] += $credits = Rand(50, 50 * $cost);
				$result = $Lang['GambleWon'] . $credits . ' [!].<br />';
			}
			elseif(Rand(0, 99) < 5) {
				$result = $Lang['GambleE'] . '<br />';
			}
			else {
				$result = $Lang['GambleN' . Rand(1, 3)] . '<br />';
				$Player['credits'] -= $cost;
			}
			$db->query("UPDATE `${prefix}users` SET `exp` = '${Player['exp']}', `credits` = '${Player['credits']}', `mp` = '${Player['mp']}' WHERE `login` = '$login' LIMIT 1");
		}
	}
}

// -------------------------------------------------------------------
// Mines
// -------------------------------------------------------------------

function actionmine() {
	global $login, $db, $prefix, $Player, $Colony, $places, $Lang, $result, $errors, $amount;
	$errors = '';
	$result = '';
	$b = FALSE;
	$amount = floor($amount / 0.25);
	if (! $amount) $amount = 1;
	if (checkplace('mines')) {
		if ($Player['mp'] < 0.25 * $amount) $errors .= $Lang['ErrorNotEnoughMP'] . '<br />';
		else {
			$kind = $places['mines']['parameters'];
			$credits = 0;

			for ($i = 0; $i < $amount; $i++) {
				$Player['mp'] -= 0.25;
				$credits += round(Rand(3, 15 + 5 * $kind) * ($Player['level'] + 5) / 5);

				if ($Colony && Rand(0, 199) < 2 + $kind) {
					$Colony['metalsources'] += $sources = round(5 * ($Player['level'] + 1)) / 10;
					$result .= $Lang['MineWork2'] . $sources . '.<br />';
					$b = TRUE;
				}
				if (Rand(0, 199) < 2 + $kind) {
					$Player['score'] += $score = Rand(1, 30 + 5 * $Player['level']);
					$result .= "${Lang['MineWork3']} <font class=\"plus\">" . div($score) . '</font>.<br />';
				}
				if (Rand(0, 199) < 2 + $kind) {
					$exp = Rand(5, 20 + 5 * $Player['level']);
					if ($Player['level'] > 5) $exp *= 100 * $Player['level'];
					$Player['exp'] += $exp;
					if (Rand(0, 99) < 25) $s = $Lang['MineWork9'];
					else if (Rand(0, 99) < 25) $s = $Lang['MineWork8'];
					else $s = $Lang['MineWork4'];
					$result .= "<font class=\"capacity\">$s</font><br /><b>${Lang['Experience']}</b>: <font class=\"capacity\"><b>"  . div($exp) . '</b></font>.<br />';
				}
				if (Rand(0, 99) < 1 + $kind) {
					$Player['credits'] += $credits = Rand(10, 500 * $Player['level']);
					if (Rand(0, 99) < 25) $s = $Lang['MineWork7'];
					else if (Rand(0, 99) < 25) $s = $Lang['MineWork6'];
					else $s = $Lang['MineWork5'];
					$result .= "<font class=\"work\"><b>$s</b>:</font> <font class=\"plus\"><b>" . div($credits) . '</b> [!]</font>.<br />';
				}
			}

			$Player['credits'] += $credits;
			$result .= $Lang['MineWork1'] . '<b>' . div($credits) . '</b> [!].<br />';

			if ($b) $db->query("UPDATE `${prefix}colonies` SET `metalsources` = '${Colony['metalsources']}' WHERE `id` = '${Colony['id']}' LIMIT 1;");
			$db->query("UPDATE `${prefix}users` SET `score`='${Player['score']}',`exp`='${Player['exp']}',`credits`='${Player['credits']}',`mp`='${Player['mp']}' WHERE `login`='$login';");
		}
	}
}

// -------------------------------------------------------------------
// Thoria
// -------------------------------------------------------------------

function actionthoria() {
	global $login, $db, $prefix, $Player, $Group, $Colony, $places, $Lang, $result, $errors, $amount;
	$errors = '';
	$result = '';
	$b = FALSE;
	if (checkplace('thoria')) {
		$mpcost = ($p = $places['thoria']['parameters']) * $Player['level'] / 2;
		if (! $amount = floor($amount / $mpcost)) $amount = 1;
		if ($Player['mp'] < $mpcost * $amount) $errors .= $Lang['ErrorNotEnoughMP'] . '<br />';
		elseif ($Player['hp'] < $places['thoria']['parameters'] * $Player['hpmax'] / 10) $errors .= $Lang['ErrorNotEnoughHP'] . '<br />';
		else {
			$credits = 0;
			$score = 0;
			$exp = 0;
			$uransources = 0;

			$n = log($Player['hpmax']);

			for ($i = 0; $i < $amount; $i++) {
				$credits += floor($n * Rand(5 + $mpcost, 25 * $mpcost));
				$Player['mp'] -= $mpcost;

				if (Rand(0, 999) < 50 * $n) {
					if (Rand(0, 999) < 50 * $n) {
						if (Rand(0, 999) < 50 * $p * $n + $amount) {
							$item = generateitem('item');
							$name = $Lang['items'][$item['name']]['name'];
							addequipment($item);
							$result .= "${Lang['FoundItem']}: <b><font class=\"capacity\">$name</font></b><br />";
							break;
						}
						if ($Colony) $uransources = round(Rand(1, 25 * $mpcost) / 10);
						else $credits += Rand(100, 250 * $p * $mpcost);
					}
					else $score += floor($n * Rand(1, 15 * $mpcost));
				}

				if (Rand(0, 999) < 5 * $p * $n) {
					$Player['exp'] += ($exp = round(Rand(1, 10 + $amount) * $p * $Player['exp'] / 1000));
					$Player['hp'] = 0;
					$result .= $Lang['Accident'] . '<br />' . $Lang['Experience'] . ': <font class="work"><b>' . div($exp) . '</b></font><br />';
					break;
				}

			}

			if ($uransources) {
				$Colony['uransources'] += $uransources;
				$db->query("UPDATE `${prefix}colonies` SET `uransources`='${Colony['uransources']}' WHERE `id`='${Colony['id']}';");
				$result .= $Lang['UranSourcesGained'] . ': <b><font class="work">' . div($uransources) . '</font></b><br />';
			}

			if ($score) {
				$tax = $Group ? round($score * $Group['tax'] / 100) : 0;
				$Player['score'] += $score - $tax;
				$result .= $Lang['ScoreGained'] . ': <b><font class="result">' . div($score) . '</font></b><br />';
				if ($tax) {
					$Group['score'] += $tax;
					$db->query("UPDATE `${prefix}groups` SET `score`=`score`+$tax WHERE `id`='${Group['id']}';");
					$result .= $Lang['ClanTaxGiven'] . ': <b><font class="tax">' . div($tax) . '</font></b><br />';
				}
			}

			if ($credits) {
				$tax = $Group ? round($credits * $Group['tax'] / 100) : 0;
				$Player['credits'] += $credits - $tax;
				$result .= $Lang['PaymentForWork'] . ': <b><font class="plus">' . div($credits) . '</font> [!]</b><br />';
				if ($tax) {
					$Group['credits'] += $tax;
					$db->query("UPDATE `${prefix}groups` SET `credits`=`credits`+$tax WHERE `id`='${Group['id']}';");
					$result .= $Lang['ClanTaxGiven'] . ': <b><font class="tax">' . div($tax) . '</font> [!]</b><br />';
				}
			}

			if ($b) $db->query("UPDATE `${prefix}colonies` SET `uransources`='${Colony['uransources']}' WHERE `id`='${Colony['id']}';");
			$db->query("UPDATE `${prefix}users` SET `score`='${Player['score']}',`exp`='${Player['exp']}',`credits`='${Player['credits']}',`mp`='${Player['mp']}',`hp`='${Player['hp']}' WHERE `id`='${Player['id']}';");
		}
	}
}
