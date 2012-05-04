<?php

// -------------------------------------------------------------------
// Destroy unit(s)...
// -------------------------------------------------------------------

function actiondestroyunits()
{
	global $login, $db, $action, $prefix, $errors, $Units, $Colony, $Planet, $Cost, $Lang, $amount, $name;
	if ($name && $amount) {
		if (@$Colony[$Units[$name]['id']] < $amount) $errors .= $Lang['ErrDestroy1'];
		else {
			if  (isset($Units[$name]['metal'])) $metal = round($Units[$name]['metal'] * $amount * 0.5); else $metal = 0;
			$Colony['metal'] += $metal;
			if ($Colony['metal'] > $Colony['metalcapacity']) $Colony['metal'] = $Colony['metalcapacity'];
			$Colony[$Units[$name]['id']] -= $amount;
			$db->query("UPDATE `${prefix}colonies` SET `metal` = '${Colony['metal']}', `" . $Units[$name]['id'] . "` = '" . $Colony[$Units[$name]['id']] . "' WHERE `name` = '${Colony['name']}' LIMIT 1");
			$Units = unitslist($Colony, $Planet);
			$Cost['metal'] = $metal;
		}
	}
	else $action = '';
}

// -------------------------------------------------------------------
// Build structure(s)...
// -------------------------------------------------------------------

function actionbuild()
{
	global $login, $action, $db, $prefix, $Lang, $errors, $stardate, $name, $amount;
	global $Cost, $Player, $Colony, $Exploration, $Builds, $Buildings, $Planet, $Galaxy;
	if (!$amount) $amount = 1;
	if (@$Colony['infrastructure'] && $name && $amount) {
		$errors = '';
		if ($Buildings) $errors .= $Lang['ErrBuild1'] . '<br />';
		else {
			$b = '';
			foreach ($Builds as $a) {
				if ($a['id'] == $name) {
					$b = $a;
					if (isset($b['level'])) $amount = 1;
					if (isset($b['credits'])) $b['credits'] *= $amount; else $b['credits'] = 0;
					if (isset($b['cost'])) $b['credits'] += $b['cost'] * $amount;
					@$b['energy'] *= $amount;
					@$b['silicon'] *= $amount;
					@$b['metal'] *= $amount;
					@$b['uran'] *= $amount;
					@$b['plutonium'] *= $amount;
					@$b['deuterium'] *= $amount;
					@$b['food'] *= $amount;
					@$b['crystals'] *= $amount;
					$b['score'] *= $amount;
					$b['time'] = round((50 / $Colony['infrastructure']) * $amount * $b['work'] / log($Colony['workforce']));
					break;
				}
			}
			if (!$b) $errors .= $Lang['ErrBuild2'] . '<br />';
			elseif ($Player['credits'] < $b['credits']) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
			elseif ($b['energy'] && $Colony['energy'] < $b['energy'] || $b['silicon'] && $Colony['silicon'] < $b['silicon'] || $b['metal'] && $Colony['metal'] < $b['metal'] || $b['uran'] && $Colony['uran'] < $b['uran'] || $b['plutonium'] && $Colony['plutonium'] < $b['plutonium'] || $b['deuterium'] && $Colony['deuterium'] < $b['deuterium'] || $b['food'] && $Colony['food'] < $b['food'] || $b['crystals'] && $Colony['crystals'] < $b['crystals']) $errors .= $Lang['ErrorNotEnoughResources'].'<br />';
			elseif ($Colony['workforce'] < 1) $errors .= $Lang['ErrorNotEnoughWorkforce'] . '<br />';
			elseif ($Colony['base'] && $Colony['colonistsfree'] < ceil(0.25 * $Colony['colonists'])) $errors .= $Lang['ErrorNotEnoughColonists'] . '<br />';
		}
		if (!$errors) {
			$db->query("INSERT INTO `${prefix}buildings` (`login`, `name`, `begin`, `time`, `amount`, `score`) VALUES ('$login', '$name', '$stardate', '${b['time']}', '$amount', '${b['score']}')");
			$Player['credits'] -= $b['credits'];
			$Colony['energy'] -= $b['energy'];
			$Colony['silicon'] -= $b['silicon'];
			$Colony['metal'] -= $b['metal'];
			$Colony['uran'] -= $b['uran'];
			$Colony['plutonium'] -= $b['plutonium'];
			$Colony['deuterium'] -= $b['deuterium'];
			$Colony['crystals'] -= $b['crystals'];
			$Colony['colonistsfree'] -= ceil(0.25 * $Colony['colonists']);
			updateplayer($Player);
			updatecolony($Colony);
			$Cost = $b;
		}
	}
	else $action = '';
}

// -------------------------------------------------------------------
// Product unit(s)...
// -------------------------------------------------------------------

function actionproduct()
{
	global $login, $action, $db, $prefix, $Lang, $errors, $stardate, $name, $amount;
	global $Cost, $Player, $Colony, $Productions, $ProductionsAvailable, $Planet, $Galaxy;

	if (@$Colony['military'] && $name && $amount) {
		$errors = '';
		if ($Productions && (count($Productions) >= $Colony['factory'])) $errors .= $Lang['ErrProduct1'] . '<br />';
		else if (!isset($ProductionsAvailable[$name])) $errors .= $Lang['ErrProduct2'] . '<br />';
		else {
			$b = $ProductionsAvailable[$name];
			if (isset($b['credits'])) $b['credits'] *= $amount; else $b['credits'] = 0;
			if (isset($b['cost'])) $b['credits'] += $b['cost'] * $amount;
			@$b['energy'] *= $amount;
			@$b['silicon'] *= $amount;
			@$b['metal'] *= $amount;
			@$b['uran'] *= $amount;
			@$b['plutonium'] *= $amount;
			@$b['deuterium'] *= $amount;
			@$b['food'] *= $amount;
			@$b['crystals'] *= $amount;
			$b['time'] = 1 + round(((50 / $Colony['military'])) * ($amount * $b['work'] / log($Colony['workforce'])) / ($Colony['factory'] + $Colony['tron']));
			if ($Player['credits'] < $b['credits']) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
			elseif ($b['energy'] && $Colony['energy'] < $b['energy'] || $b['silicon'] && $Colony['silicon'] < $b['silicon'] || $b['metal'] && $Colony['metal'] < $b['metal'] || $b['uran'] && $Colony['uran'] < $b['uran'] || $b['plutonium'] && $Colony['plutonium'] < $b['plutonium'] || $b['deuterium'] && $Colony['deuterium'] < $b['deuterium'] || $b['crystals'] && $Colony['crystals'] < $b['crystals']) $errors .= $Lang['ErrorNotEnoughResources'] . '<br />';
			elseif ($Colony['workforce'] < 1) $errors .= $Lang['ErrorNotEnoughWorkforce'] . '<br />';
			elseif ($Colony['colonistsfree'] + $Colony['tron'] < 1) $errors .= $Lang['ErrorNotEnoughColonists'] . '<br />';
		}
		if (!$errors) {
			$db->query("INSERT INTO `${prefix}productions` (`login`, `name`, `begin`, `time`, `amount`, `score`) VALUES ('$login', '$name', '$stardate', '${b['time']}', '$amount', '${b['score']}');");
			$Player['credits'] -= $b['credits'];
			$Colony['energy'] -= $b['energy'];
			$Colony['silicon'] -= $b['silicon'];
			$Colony['metal'] -= $b['metal'];
			$Colony['uran'] -= $b['uran'];
			$Colony['plutonium'] -= $b['plutonium'];
			$Colony['deuterium'] -= $b['deuterium'];
			$Colony['crystals'] -= $b['crystals'];
			$Colony['colonistsfree']--;
			updateplayer($Player);
			updatecolony($Colony);
			$Cost = $b;
		}
	}
	else $action = '';
}
 
// -------------------------------------------------------------------
// Colony management
// -------------------------------------------------------------------

function actionmanagement()
{
	global $db, $prefix, $login, $Lang, $Player, $Colony, $errors;
	list($infrastructure, $science, $military) = array((int)postvar('infrastructure'), (int)postvar('science'), (int)postvar('military'));
	$min = 25 - 10 * $Colony['managementtechnology'];
	$max = 50 + 20 * $Colony['managementtechnology'];
	if (($infrastructure + $science + $military != 100) || $infrastructure < $min || $science < $min || $military < $min || $infrastructure > $max || $science > $max || $military > $max) $errors .= $Lang['ErrorColonyManagement'].'<br />';
	else {	
		$cost = $Player['level'] * (1000 - 50 * $Colony['managementtechnology']) * (abs($Colony['infrastructure'] - $infrastructure) + abs($Colony['science'] - $science) + abs($Colony['military'] - $military));
		if ($Player['credits'] < $cost) $errors .= $Lang['ErrorNotEnoughCredits'].'<br />';
		else {
			$Player['credits'] -= $cost;
			$Colony['infrastructure'] = $infrastructure;
			$Colony['science'] = $science;
			$Colony['military'] = $military;
			$db->query("UPDATE {$prefix}users SET credits='{$Player['credits']}' WHERE login='{$Player['login']}';");
			$db->query("UPDATE {$prefix}colonies SET infrastructure='{$Colony['infrastructure']}',science='{$Colony['science']}',military='{$Colony['military']}' WHERE id='{$Colony['id']}';");
		}
	}
}

// -------------------------------------------------------------------
// Cancel research...
// -------------------------------------------------------------------

function actioncancelresearch()
{
	global $login, $db, $prefix, $Research;
	if ($id = getvar('id')) $db->query("DELETE FROM {$prefix}researches WHERE `id`='$id' AND `login`='$login';");
	$Research = readresearch();
}

// -------------------------------------------------------------------
// Cancel production...
// -------------------------------------------------------------------

function actioncancelproduction() {
	global $login, $db, $prefix, $Productions;
	if ($id = getvar('id')) $db->query("DELETE FROM {$prefix}productions WHERE `id`='$id' AND `login`='$login';");
	$Productions = readproductions();
}

// -------------------------------------------------------------------
// Cancel building...
// -------------------------------------------------------------------

function actioncancelbuilding()
{
	global $login, $Buildings;

	if ($Buildings) {
		buildingfinish($login);
		$Buildings = array();
	}
}

// -------------------------------------------------------------------
// Enable building
// -------------------------------------------------------------------

function actionenable()
{
	global $login, $db, $prefix, $Colony, $name;

	if ($Colony && $name && ($name .= 'off') && isset($Colony[$name])) {
		$Colony[$name] = 0;
		$db->query("UPDATE `${prefix}colonies` SET `$name`=0 WHERE `id`='${Colony['id']}';");
	}
}

// -------------------------------------------------------------------
// Disable building
// -------------------------------------------------------------------

function actiondisable() {
	global $login, $db, $prefix, $Colony, $name;

	if ($Colony && $name && ($name .= 'off') && isset($Colony[$name])) {
		$Colony[$name] = 1;
		$db->query("UPDATE `${prefix}colonies` SET `$name`=1 WHERE `id`='${Colony['id']}';");
	}
}

// -------------------------------------------------------------------
// Travel...
// -------------------------------------------------------------------

function actiontravel() {
	global $login, $db, $prefix, $Player, $playerspeed, $stardate, $errors, $Lang;
	$errors = '';

	if (! $Player['destination'] && ($destination = getvar('destination'))) {
		$db->query("SELECT * FROM `${prefix}space` WHERE `name`='${Player['planet']}' LIMIT 1;");
		$sp = $db->fetchrow();
		$db->query("SELECT * FROM `${prefix}universe` WHERE `name`='${Player['galaxy']}' LIMIT 1;");
		$sg = $db->fetchrow();
		$db->query("SELECT * FROM `${prefix}space` WHERE `name`='$destination' LIMIT 1;");
		$dp = $db->fetchrow();
		if ($dp) {
			$db->query("SELECT * FROM `${prefix}universe` WHERE `name` = '${dp['galaxy']}' LIMIT 1");
			$dg = $db->fetchrow();
			$distance = galaxydistance($dg, $sg) + planetdistance($dp, $sp);
			$time = round($distance / $playerspeed) + $stardate;
			if ($Player['mp'] >= $sp['gravity']) {
				$Player['mp'] -= $sp['gravity'];
				$Player['destination'] = $destination;
				$Player['time'] = $time;
				$db->query("UPDATE `${prefix}users` SET `mp`=`mp`-${sp['gravity']},`destination`='$destination',`time`='$time',`distance`='$distance' WHERE `login`='$login';");
			}
			else $errors .= $Lang['ErrorNotEnoughMP'] . '<br />';
		}
	}
}

// -------------------------------------------------------------------
// Cancel travel...
// -------------------------------------------------------------------

function actioncanceltravel() {
	global $login, $db, $prefix, $Player;
	$Player['destination'] = '';
	$Player['time'] = 0;
	$db->query("UPDATE `${prefix}users` SET `destination` = '', `time` = '0' WHERE `login` = '$login' LIMIT 1");
}

// -------------------------------------------------------------------
// Academy: train soldiers...
// -------------------------------------------------------------------

function actiontrain() {
	global $login, $db, $prefix, $Player, $Colony, $amount, $errors, $soldierstraincost, $Lang, $result;
	if ($Colony && $amount && $Colony['academy']) {
		if ($amount > $Colony['colonistsfree']) $errors .= $Lang['ExE1'] . '<br />';
		if ($amount * $soldierstraincost > $Player['credits']) $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
		if ($amount > $Colony['barracks'] * 50 - $Colony['soldiers']) $errors .= $Lang['NoRoomForS'] . '<br />';

		if (! $errors) {
			$Player['credits'] -= $amount * $soldierstraincost;
			$Colony['colonists'] -= $amount;
			$Colony['colonistsfree'] -= $amount;
			$Colony['soldiers'] += $amount;
			$db->query("UPDATE `${prefix}users` SET `credits` = '${Player['credits']}' WHERE `login` = '$login' LIMIT 1");
			$db->query("UPDATE `${prefix}colonies` SET `colonists` = '${Colony['colonists']}', `soldiers` = '${Colony['soldiers']}' WHERE `name` = '${Colony['name']}' LIMIT 1");
			$result = $amount;
		}
	}
}

// -------------------------------------------------------------------
// Scan object
// -------------------------------------------------------------------

function actionscanobject()
{
	global $login, $db, $prefix, $Lang, $errors, $Player;
	if (($x = equipmentparameters('scanner')) == 0) $errors .= $Lang['ErrorNoScanner'].'<br />';
	elseif ($Player['mp'] < ($x = 2 * ($x > 9 ? 1 : 10 - $x))) $errors .= $Lang['ErrorNotEnoughMP'].'<br />';
	else {
		$Player['mp'] -= $x;
		$db->query("UPDATE {$prefix}users SET mp='{$Player['mp']}' WHERE id='{$Player['id']}';");
	}
}

// -------------------------------------------------------------------
// Explore
// -------------------------------------------------------------------

function actionexplore()
{
	global $login, $db, $prefix, $Lang, $Var, $errors;
	global $Player, $Colony, $Exploration, $Planet, $Galaxy;
	global $starday, $stardate, $planetexplorecost, $galaxyexplorecost, $colonistexplorecost, $scientistsexplorecost, $soldiersexplorecost, $foodexplorecost, $vesselsexplorecost;

	$colonists = postvar('colonists');
	$scientists = postvar('scientists');
	$soldiers = postvar('soldiers');
	$vessels = postvar('vessels');
	$type = postvar('type');
	$time = postvar('time');

	if (!$time) $time = 1;
	$duration = $time * $starday;
	$target = $type == 'planet' ? $Planet['name'] : $Galaxy['name'];

	if ($Exploration) $errors .= $Lang['ExE9'] . '<br />';
	elseif ($type != 'planet' && $type != 'galaxy') $errors .= $Lang['ExE5'] . '<br />';
	else {
		if ($type == 'planet' && $colonists < 1) $errors .= $Lang['ExE6'] . '<br />';
		if ($type == 'galaxy') {
			if ($vessels < 1) $errors .= $Lang['ExE7'] . '<br />';
			elseif ($vessels * $Var['units']['vessel']['capacity'] < $colonists + $scientists + $soldiers) $errors .= $Lang['ExE8'] . '<br />';
		}
		if ($colonists > $Colony['colonistsfree']) $errors .= $Lang['ExE1'] . '<br />';
		if ($scientists > $Colony['scientistsfree']) $errors .= $Lang['ExE2'] . '<br />';
		if ($soldiers > $Colony['soldiersfree']) $errors .= $Lang['ExE3'] . '<br />';
		if ($vessels > $Colony['vesselsfree']) $errors .= $Lang['ExE4'] . '<br />';
		$cost = ($type == 'planet' ? $planetexplorecost : $galaxyexplorecost) + $time * ($colonists * $colonistexplorecost + $scientists * $scientistsexplorecost + $soldiers * $soldiersexplorecost);
		$energy = $vessels * $time * $vesselsexplorecost;
		$food = ($colonists + $scientists + $soldiers) * $foodexplorecost * $time;
		if ($cost > $Player['credits']) $errors .= $Lang['ExEC'] . " $cost!<br />";
		if ($energy > $Colony['energy'])  $errors .= $Lang['ExEC'] . " $energy ${Lang['energy']}.<br />";
		if ($food > $Colony['food'])  $errors .= $Lang['ExEC'] . " $food ${Lang['food']}.<br />";
	}

	if (! $errors) {
		$db->query("INSERT INTO {$prefix}exploration (`login`,`type`,`target`,`begin`,`time`,`colonists`,`scientists`,`soldiers`,`vessels`) VALUES ('$login','$type','$target','$stardate','$duration','$colonists','$scientists','$soldiers','$vessels');");
		$Player['credits'] -= $cost;
		$Colony['energy'] -= $energy;
		$Colony['food'] -= $food;
		$Colony['colonistsfree'] -= $colonists;
		$Colony['scientistsfree'] -= $scientists;
		$Colony['soldiersfree'] -= $soldiers;
		$Colony['vesselsfree'] -= $vessels;
		$Exploration['cost'] = $cost;
		$Exploration['energy'] = $energy;
		$Exploration['food'] = $food;
		updateplayer($Player);
		updatecolony($Colony);
	}
}

// -------------------------------------------------------------------
// Cancel expedition
// -------------------------------------------------------------------

function actioncancelexpedition()
{
	global $Player, $Colony, $Exploration;

	if (!$name) $name = $login;
	if ($Exploration) {
		$Colony['colonistsfree'] += $Exploration['colonists'];
		$Colony['scientistsfree'] += $Exploration['scientists'];
		$Colony['soldiersfree'] += $Exploration['soldiers'];
		$Colony['vesselsfree'] += $Exploration['vessels'];
		explorationfinish($name);
		$Exploration = '';
	}
}

// -------------------------------------------------------------------
// Repair damaged colony
// -------------------------------------------------------------------

function actionrepair()
{
	global $db, $prefix, $login, $Colony;

	$damage = $Colony['damage'];

	$ce = 25;
	$cm = 10;

	$energy = $damage * $ce;
	$metal = $damage * $cm;

	if ($energy > $Colony['energy']) {
		$energy = $Colony['energy'];
		if ($energy / $ce < $damage) $damage = $energy / $ce;
	}
	if ($metal > $Colony['metal']) {
		$metal = $Colony['metal'];
		if ($metal / $cm < $damage) $damage = $metal / $cm;
	}

	$energy = $damage * $ce;
	$metal = $damage * $cm;

	$Colony['energy'] -= $energy;
	$Colony['metal'] -= $metal;
	$Colony['damage'] -= $damage;

	$db->query("UPDATE `${prefix}colonies` SET `energy`='${Colony['energy']}',`metal`='${Colony['metal']}',`damage`='${Colony['damage']}' WHERE `id`='${Colony['id']}' AND `owner`='$login'");
}

// -------------------------------------------------------------------
// Change player's ship
// -------------------------------------------------------------------

function actionshipexchange() {
	global $db, $prefix, $login, $name, $Player, $Colony, $Planet, $Units;

	if ($Units[$name] && ($Player['planet'] == $Colony['planet'])) {
		$old = $Player['ship'];
		$Colony[$old]++;
		$Colony[$name]--;
		$Player['ship'] = $name;
		$db->query("UPDATE `${prefix}users` set `ship`='$name' WHERE `id`='${Player['id']}' LIMIT 1;");
		$db->query("UPDATE `${prefix}colonies` set `$old`='${Colony[$old]}',`$name`='${Colony[$name]}' WHERE `id`='${Colony['id']}' LIMIT 1;");
		$Units = unitslist($Colony, $Planet);
	}
}

// -------------------------------------------------------------------
// Initiate research...
// -------------------------------------------------------------------

function actioninitiate() {
	global $login, $action, $db, $prefix, $Lang, $errors, $stardate, $name, $Player, $Colony, $Technologies, $Research;
	if (@$Colony['science'] && $Technologies && $name && (!$Research) && isset($Technologies[$name]) && !$Technologies[$name]['completed']) {
		$t = $Technologies[$name];
		if ($Player['credits'] < $t['credits']) $errors .= $Lang['ErrorNotEnoughCredits'].'<br />';
		elseif (($t['energy'] && $Colony['energy'] < $t['energy']) || ($t['silicon'] && $Colony['silicon'] < $t['silicon']) || ($t['metal'] && $Colony['metal'] < $t['metal']) || ($t['uran'] && $Colony['uran'] < $t['uran']) || ($t['plutonium'] && $Colony['plutonium'] < $t['plutonium']) || ($t['deuterium'] && $Colony['deuterium'] < $t['deuterium']) || ($t['crystals'] && $Colony['crystals'] < $t['crystals'])) $errors .= $Lang['ErrorNotEnoughResources'].'<br />';
		elseif ($Colony['scienceforce'] < 1) $errors .= $Lang['ErrorNotEnoughScienceforce'] . '<br />';
		elseif ($Colony['scientistsfree'] < ceil(0.5 * $Colony['scientists'])) $errors .= $Lang['ErrorNotEnoughScientists'] . '<br />';
		if (!$errors) {
			$score = $t['score'];
			$time = 1 + round((25 / $Colony['science']) * $t['work'] / log($Colony['scienceforce']));
			$Research = array('name' => $name, 'end' =>$stardate + $time);
			$Player['credits'] -= $t['credits'];
			$Colony['energy'] -= $t['energy'];
			$Colony['silicon'] -= $t['silicon'];
			$Colony['metal'] -= $t['metal'];
			$Colony['uran'] -= $t['uran'];
			$Colony['plutonium'] -= $t['plutonium'];
			$Colony['deuterium'] -= $t['deuterium'];
			$Colony['crystals'] -= $t['crystals'];
			$Colony['scientistsfree'] -= ceil(0.5 * $Colony['scientists']);
			updateplayer($Player);
			updatecolony($Colony);
			$db->query("INSERT INTO {$prefix}researches (`login`,`name`,`begin`,`time`,`score`) VALUES ('$login','$name','$stardate','$time','$score');");
		}
	}
}
