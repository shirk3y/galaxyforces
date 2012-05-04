<?php

// ===========================================================================
// Galaxy Forces MMORPG
// ===========================================================================

// ---------------------------------------------------------------------------
//	Version:	0.4.6 -- 0.5.1.2
//	Created:	2004-08-10
//	Modified:	2005-11-12
//	Author(s):	zoltarx
// ---------------------------------------------------------------------------

// ===========================================================================
// You may find licence details at http://galaxy.game-host.org ;-)
// ===========================================================================

if (!defined('__MOD_GALAXY__')) {

global $Config, $logged, $action, $login, $db, $prefix, $Lang, $secret, $language, $fight, $distance, $tracker, $result, $amount, $name, $Research, $Technologies, $Attacks, $Incoming, $places, $place, $starmonth;
global $Cost, $Player, $Group, $Equipment, $Exploration, $Buildings, $Structures, $Builds, $Units, $Colony, $Planet, $Galaxy, $Var, $Productions, $ProductionsAvailable, $Attackers, $Defenders;
global $begining, $thicklength, $stardate, $starday, $playernature, $errors, $winner;
global $soldierstraincost, $colonistshirecost, $scientistshirecost, $planetexplorecost, $galaxyexplorecost, $colonistexplorecost, $scientistsexplorecost, $soldiersexplorecost, $foodexplorecost, $vesselsexplorecost, $foundpercentage, $amiminlevel, $tronminreputation;
global $defaultgalaxy, $playerspeed, $planet;
global $valid_resources;

locale('galaxy', $language = $Config['Language']);
locale('units', $language);

if (!$logged) return;

include("{$ROOT}include/messages.php");
include("{$ROOT}modules/galaxy/config.php");

// -------------------------------------------------------------------
// Configuration
// -------------------------------------------------------------------

//	$begining = 371343600; // Do not remove, this is one of secrets... :)

foreach (array(
	"begining"=>1092088800,
	"thicklength"=>300,
	"starday"=>12,
) as $name=>$value)
	$State['registry']=$$name=isset($Config[$key="module.galaxy.$name"])
	? $Config[$key] 
	: $value;
	
$begining = 1092088800;
$thicklength = 300;
$starday = 12;

$maximumsteps=10;

$defaultgalaxy = 'home';

$tronminreputation = -5;
$amiminlevel = 50;

$vesselsexplorecost = 50;
$planetexplorecost = 100;
$galaxyexplorecost = 500;
$colonistexplorecost = 5;
$scientistsexplorecost = 10;
$soldiersexplorecost = 2;
$foodexplorecost = 1.5;
$foundpercentage = 10;

$colonistshirecost = 100;
$scientistshirecost = 250;
$soldiershirecost = 10000;

$valid_resources = array('energy', 'silicon', 'metal', 'uran', 'plutonium', 'deuterium', 'food', 'crystals');

// -------------------------------------------------------------------
// Variables
// -------------------------------------------------------------------

$name = strip_tags(escapesql(getvar('name')));
$amount = abs(getvar('amount'));

$errors = '';
$result = '';

// -------------------------------------------------------------------
// Functions
// -------------------------------------------------------------------

function div($s='')
{
	return strdiv($s);
}

function amount($s, $d = 1)
{
	global $Lang;
	$t = number_format($s, $d, $Lang['DecPoint'], ' ');
	if ($s > 0) return "<font class=\"plus\">+$t</font>";
	elseif ($s < 0) return "<font class=\"minus\">$t</font>";
	else return $s;
}

function stardate($time=0)
{
	global $begining, $thicklength;
	if (!$time) $time = time();
	return floor(($time - $begining) / $thicklength);
}

function startime($time=0)
{
	global $begining, $thicklength, $stardate;
	if (!$time) $time = $stardate;
	return $time * $thicklength + $begining;
}

function eta($time=0)
{
	global $thicklength, $Lang;
	if ($time < 0) return $Lang['n/a'];
	$y = $time * $thicklength;

	$y = ($y -= ($s = $y % 60)) / 60;
	$y = ($y -= ($m = $y % 60)) / 60;
	$y = ($y -= ($h = $y % 24)) / 24;
	$y = round(($y -= ($d = $y % 365.24)) / 365.24);

	if ($thicklength < 60) $result = ($s < 10 ? '0' : '') . $s . 's';
	else $result = '';
	if ($thicklength < 60 || $m || $h || $d || $y) {
		$result = ($m < 10 ? '0' : '') . $m . 'm' . ($result ? '&nbsp;' : '') . $result;
		if ($h || $d || $y) {
			$result = ($h < 10 ? '0' : '') . $h . 'h&nbsp;' . $result;
			if ($d || $y) {
				$result = $d . 'd&nbsp;' . $result;
				if ($y) $result = number_format($y, 0, '', ' ') . 'y&nbsp;' . $result;
			}
		}
	}
	return $result;
}

function planetdistance($g, $h)
{
	return 10 * sqrt(pow($g['x'] - $h['x'], 2) + pow($g['y'] - $h['y'], 2) + pow($g['z'] - $h['z'], 2));
}

function galaxydistance($g, $h)
{
	return 1000 * sqrt(pow($g['x'] - $h['x'], 2) + pow($g['y'] - $h['y'], 2) + pow($g['z'] - $h['z'], 2));
}

function playernature($reputation)
{
	global $Lang;
	if ($reputation <= -10) return $Lang['natureevil2'];
	elseif ($reputation <= -5) return $Lang['natureevil'];
	elseif ($reputation <= 5) return $Lang['natureneutral'];
	elseif ($reputation <= 10) return $Lang['naturegood'];
	else return $Lang['naturegood2'];
}

function reputationmodifier($reputation)
{
	if ($reputation < -2.5) return 1 + 0.25*log(- 1.5 - $reputation);
	elseif ($reputation > 2.5) return 1 / (1 + 0.25*log($reputation - 1.5));
	else return 1;
}

// -------------------------------------------------------------------
// Collecting data
// -------------------------------------------------------------------

function readplayer($name)
{
	global $db, $prefix;
	$db->query("SELECT * FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
	$Player = $db->fetchrow();
	$Player['strength'] += $Player['strengthmodifier']; if ($Player['strength'] < 0) $Player['strength'] = 0;
	$Player['agility'] += $Player['agilitymodifier'];  if ($Player['agility'] < 0) $Player['agility'] = 0;
	$Player['hpmax'] += $Player['hpmodifier']; if ($Player['hpmax'] < 0) $Player['hpmax'] = 0;
	$Player['mpmax'] += $Player['mpmodifier']; if ($Player['mpmax'] < 0) $Player['mpmax'] = 0;
	$Player['expbegin'] = (pow(2, $Player['level'] - 1) - 1) * 100;
	$Player['exp4level'] = (pow(2, $Player['level']) - 1) * 100;
	return $Player;
}

function readplanet($name) 
{
	global $db, $prefix;
	$db->query("SELECT * FROM `${prefix}space` WHERE `name`='$name' LIMIT 1;");
	if ($t = $db->fetchrow()) return $t;
	else return '';
}

function readbuildings($name) 
{
	global $db, $prefix, $login;
	if (! $name) $name = $login;
	$db->query("SELECT * FROM `${prefix}buildings` WHERE `login` = '$name' LIMIT 1;");
	if ($t = $db->fetchrow()) {
		$t['end'] = $t['begin'] + $t['time'];
		return $t;
	}
	else return '';
}

function readattacks($login, $target='') {
	global $db, $prefix, $Var, $Attackers;
	$db->query("SELECT * FROM {$prefix}attacks WHERE ".($target ? "target='$target';" : "login='$login';"));
	$r = array();
	while ($t = $db->fetchrow()) {
		$t['end'] = $t['begin'] + $t['time'];
		$t['units'] = 0; $t['power'] = $t['bonus'];
		foreach ($Attackers as $u) { $t['units'] += $t[$u]; $t['power'] += $t[$u]*$Var['units'][$u]['attack']; }
		$r[] = $t;
	}
	return $r;
}

function readresearch($name='') {
	global $db, $prefix, $login;
	if (!$name) $name = $login;
	$db->query("SELECT * FROM {$prefix}researches WHERE `login`='$name';");
	if ($t = $db->fetchrow()) {
		$t['end'] = $t['begin'] + $t['time'];
		return $t;
	}
	else return '';
}

function readproductions($name='') {
	global $db, $prefix, $login;
	$result = array();
	if (!$name) $name = $login;
	$db->query("SELECT * FROM {$prefix}productions WHERE `login`='$name';");
	while ($t = $db->fetchrow()) {
		$t['end'] = $t['begin'] + $t['time'];
		$result[] = $t;
	}
	return $result;
}

function readexploration($name = '') {
	global $login, $db, $prefix;
	if (! $name) $name = $login;
	$db->query("SELECT * FROM `${prefix}exploration` WHERE `login` = '$name' LIMIT 1;");
	if ($t = $db->fetchrow()) {
		$t['end'] = $t['begin'] + $t['time'];
		return $t;
	}
	else return '';
}

function readgalaxy($name) {
	global $db, $prefix;
	$db->query("SELECT * FROM `${prefix}universe` WHERE `name` = '$name' LIMIT 1;");
	if ($t = $db->fetchrow()) return $t;
	else return 0;
}

function readequipment(&$Player)
{
	global $db, $prefix;

	$Player['min'] = 1;
	$Player['max'] = 0;
	$Player['armor'] = 1 + round(5 * ($Player['strength'] + $Player['agility'])) / 100;

	$Player['hit'] = 0;
	$Player['criticalhit'] = 0;
	$Player['critical'] = 0;
	$Player['block'] = 0;
	$Player['deaf'] = 0;
	$Player['hide'] = 0;
	$Player['protection'] = 0;

	$Player['weapon'] = '';
	$Player['weapon2'] = '';
	$Player['guns'] = '';
	$Player['shields'] = '';
	$Player['engine'] = '';

	$Equipment = array();

	$db->query("SELECT * FROM `${prefix}equipment` WHERE `owner`='${Player['login']}';");
	while ($t = $db->fetchrow()) {
		if ($t['active']) {
			switch ($t['type']) {
				case 'guns': $Player['guns'] = $t; break;
				case 'shields': $Player['shields'] = $t; break;
				case 'engine': $Player['engine'] = $t; break;
				case 'weapon': $Player['weapon'] = $t; break;
				case 'weapon2': if ($Player['weapon']) $Player['weapon'] = $t; else $Player['weapon2'] = $t; break;
			}
			$Player['hit'] += $t['hit'];
			$Player['criticalhit'] += $t['criticalhit'];
			$Player['critical'] += $t['critical'];
			$Player['block'] += $t['block'];
			$Player['armor'] += $t['armor'];
			$Player['min'] += $t['min'];
			$Player['max'] += $t['max'];
		}
		$Equipment[$t['id']] = $t;
	}

	$Player['distancefight'] = $Player['weapon'] && $Player['weapon']['distance'] && ((! $Player['weapon2']) || $Player['weapon2']['distance']);

	if ($Player['distancefight']) $Player['max'] += round(100 * (1 + $Player['agility'] / 2 > 3 ? 1 + $Player['agility'] / 2 : 3)) / 100;
	else $Player['max'] += round(100 * (1 + $Player['strength'] / 2 > 3 ? 1 + $Player['strength'] / 2 : 3)) / 100;

	return $Equipment;
}

function addequipment($item, $name='')
{
	global $db, $prefix, $login, $Equipment, $Lang;
	if ($name) {
		$db->query("SELECT login FROM ${prefix}users where login='$name';");
		$db->numrows() or die("User $name doesn't exists!");
	}
	else $name = $login;

	if ($item['count']) {
		if ($name == $login) {
			foreach ($Equipment as $id => $tab) {
				if ($tab['name'] == $item['name']) {
					$db->query("UPDATE {$prefix}equipment SET count=count+${item['count']} WHERE id='$id';");
					if ($name == $login) $Equipment[$id]['count'] += $item['count'];
					return true;
				}
			}
		}
		elseif ($db->query("SELECT id,name,count FROM {$prefix}equipment WHERE name='{$item['name']}' AND owner='$name' LIMIT 1;") && ($row = $db->fetchrow())) {
			$db->query("UPDATE {$prefix}equipment SET count=".($item['count'] + $row['count'])." WHERE id='{$row['id']}';");
			return true;
		}
	}

	$db->query("INSERT INTO `${prefix}equipment` (`owner`,`name`,`type`,`count`,`damaged`,`active`,`level`,`levelmax`,`price`,`distance`,`req_level`,`req_strength`,`req_agility`,`req_psi`,`req_force`,`req_intellect`,`req_knowledge`,`req_pocketstealing`,`req_hacking`,`req_alcoholism`,`weight`,`min`,`max`,`armor`,`hit`,`criticalhit`,`critical`,`block`,`speed`,`deaf`,`hide`,`protection`,`hp`,`mp`,`parameters`,`use`) VALUES ('$name','${item['name']}','${item['type']}','${item['count']}','${item['damaged']}','${item['active']}','${item['level']}','${item['levelmax']}','${item['price']}','${item['distance']}','${item['req_level']}','${item['req_strength']}','${item['req_agility']}','${item['req_psi']}','${item['req_force']}','${item['req_intellect']}','${item['req_knowledge']}','${item['req_pocketstealing']}','${item['req_hacking']}','${item['req_alcoholism']}','${item['weight']}','${item['min']}','${item['max']}','${item['armor']}','${item['hit']}','${item['criticalhit']}','${item['critical']}','${item['block']}','${item['speed']}','${item['deaf']}','${item['hide']}','${item['protection']}','${item['hp']}','${item['mp']}','${item['parameters']}','${item['use']}');");

	if ($name == $login) $Equipment[] = $item;
}

function delequipment($id, $count=1)
{
	global $db, $prefix, $login, $Equipment, $Lang;
	if (@$Equipment[$id]) {
		if ($count < $Equipment[$id]['count']) {
			$n = $Equipment[$id]['count'] -= $count;
			$db->query("UPDATE ${prefix}equipment SET count='$n' WHERE id='$id' LIMIT 1;");
		}
		else {
			if (($count = $Equipment[$id]['count']) < 1) $count = 1;
			$Equipment = array_delete($Equipment, $id);
			$db->query("DELETE FROM ${prefix}equipment WHERE id='$id' LIMIT 1;");
		}
		return $count;
	}
	return 0;
}

function equipmentparameters($type)
{
	global $Equipment;
	if (@$Equipment) foreach ($Equipment as $item) if ($item['type'] == $type && $item['parameters'] > @$p) $p = $item['parameters'];
	return @$p;
}

function unitslist($Colony, $Planet)
{
	global $Lang, $Var;
	$result = '';
	$r = array('bx1','bx2','bx5','bx10','ax3','ax6','cx7','cx13','walker','mmu','worker','hawk','valkyrie','crusader','warrior','dragon','whisper','nemesis','bee','scavenger','carrier','cage','vessel','detector','satellite');
	for ($i = 0; $i < count($r); $i++) if ($a = $Colony[$r[$i]]) $result[$r[$i]] = $Lang['units'][$r[$i]] + $Var['units'][$r[$i]] + array('amount' => $a, 'energyratio'=>-@$Var['units'][$r[$i]]['e']*$a,'siliconratio'=>-@$Var['units'][$r[$i]]['s']*$a,'metalratio'=>-@$Var['units'][$r[$i]]['m']*$a,'uranratio'=>-@$Var['units'][$r[$i]]['u']*$a,'plutoniumratio'=>-@$Var['units'][$r[$i]]['p']*$a,'deuteriumratio'=>-@$Var['units'][$r[$i]]['d']*$a,'foodratio'=>-@$Var['units'][$r[$i]]['f']*$a);
	if (isset($result['satellite'])) $result['satellite']['energyratio']=$Planet['illumination']*$result['satellite']['amount'];
	return $result;
}

function structureslist($Colony, $Planet)
{
	global $Lang, $Var;
	$result = '';
	if ($Colony['base']) $result['base'] = $Lang['structures']['base'] + $Var['structures']['base'] + array('level' => $Colony['base'], 'food' => $Colony['base'], 'foodratio' => $Colony['base'], 'metalcapacity' => 500, 'energyratio' => 5 * $Colony['base'], 'energycapacity' => 1000 * $Colony['base'], 'metalratio' => 1.0 * $Colony['base'], 'foodcapacity' => 450 + 50 * $Colony['base'], 'flats' => 20);
	if ($Colony['tron']) $result['tron'] = $Lang['structures']['tron'] + $Var['structures']['base'] + array('level'=>$Colony['tron'],'energyratio'=>250*$Colony['tron'],'siliconratio'=>$Colony['tron'],'metalratio'=>5*$Colony['tron'],'energycapacity'=>10000*$Colony['tron'],'siliconcapacity'=>500*$Colony['tron'],'metalcapacity'=>1000*$Colony['tron']);
	if ($Colony['laboratory']) $result['laboratory'] = $Lang['structures']['laboratory'] + $Var['structures']['laboratory'] + array('level' => $Colony['laboratory']);
	if ($Colony['databank']) $result['databank'] = $Lang['structures']['databank'] + $Var['structures']['databank'] + array('level' => $Colony['databank']);
	if ($Colony['factory']) $result['factory'] = $Lang['structures']['factory'] + $Var['structures']['factory'] + array('amount' => $Colony['factory'], 'energyratio' => -($Colony['factory'] * (10 + 10 * $Colony['factory']) / 2) );
	if ($Colony['spacedepot']) $result['spacedepot']=$Lang['structures']['spacedepot']+$Var['structures']['spacedepot']+array('amount'=>$Colony['spacedepot'],'energyratio'=>-($Colony['spacedepot']*(100+50*$Colony['spacedepot'])/2));
	if ($Colony['flats']) $result['flats'] = $Lang['structures']['flats'] + $Var['structures']['flats'] + array('amount' => $Colony['flats'], 'flats' => 20 * $Colony['flats'], 'energyratio' => -1.7 * $Colony['flats'], 'foodcapacity' => 5 * $Colony['flats']);
	if ($Colony['barracks']) $result['barracks'] = $Lang['structures']['barracks'] + $Var['structures']['barracks'] + array('amount' => $Colony['barracks'], 'barracks' => 50 * $Colony['barracks'], 'energyratio' => -1.2 * $Colony['barracks']);
	if ($Colony['academy']) $result['academy'] = $Lang['structures']['academy'] + $Var['structures']['academy'] + array('amount' => $Colony['academy']);
	if ($Colony['bunker']) $result['bunker']=$Lang['structures']['bunker']+$Var['structures']['bunker']+array('amount'=>$Colony['bunker'],'energyratio'=>-0.5*$Colony['bunker']);
	if ($Colony['lasertower']) $result['lasertower']=$Lang['structures']['lasertower']+$Var['structures']['lasertower']+array('amount'=>$Colony['lasertower'],'energyratio'=>-10*$Colony['lasertower']);
	if ($Colony['plasmatower']) $result['plasmatower']=$Lang['structures']['plasmatower']+$Var['structures']['plasmatower']+array('amount'=>$Colony['plasmatower'],'energyratio'=>-50*$Colony['plasmatower']);
	if ($Colony['windgenerator']) $result['windgenerator'] = $Lang['structures']['windgenerator'] + $Var['structures']['windgenerator'] + array('amount' => $Colony['windgenerator'] , 'energyratio' => $Planet['wind'] * $Colony['windgenerator']);
	if ($Colony['solarbattery']) $result['solarbattery'] = $Lang['structures']['solarbattery'] + $Var['structures']['solarbattery'] + array('amount' => $Colony['solarbattery'] , 'energyratio' => 60 * $Colony['solarbattery']);
	if ($Colony['fusionreactor']) $result['fusionreactor'] = $Lang['structures']['fusionreactor'] + $Var['structures']['fusionreactor'] + array('amount' => $Colony['fusionreactor'] , 'energyratio' => 1000 * $Colony['fusionreactor'], 'uranratio' => -25 * $Colony['fusionreactor']);
	if ($Colony['metalextractor']) $result['metalextractor'] = $Lang['structures']['metalextractor'] + $Var['structures']['metalextractor'] + array('amount' => $Colony['metalextractor'] , 'energyratio' => -5 * $Colony['metalextractor'], 'metalratio' => (2.5 * $Colony['metalextractor'] < $Colony['metalsources'] ? 2.5 * $Colony['metalextractor'] : $Colony['metalsources']));
	if ($Colony['uranmine']) $result['uranmine'] = $Lang['structures']['uranmine'] + $Var['structures']['uranmine'] + array('amount' => $Colony['uranmine'] , 'energyratio' => -25 * $Colony['uranmine'], 'metalratio' => -0.5 * $Colony['uranmine'], 'uranratio' => 5 * $Colony['uranmine']);
	if ($Colony['foodplanting']) $result['foodplanting'] = $Lang['structures']['foodplanting'] + $Var['structures']['foodplanting'] + array('amount' => $Colony['foodplanting'] , 'energyratio' => -2 * $Colony['foodplanting'], 'foodratio' => 5 * $Colony['foodplanting']);
	if ($Colony['energysilo']) $result['energysilo']=$Lang['structures']['energysilo']+$Var['structures']['energysilo']+array('amount'=>$Colony['energysilo'],'energycapacity'=>(1000+150*$Colony['energycentertechnology'])*$Colony['energysilo']);
	if ($Colony['metalsilo']) $result['metalsilo'] = $Lang['structures']['metalsilo'] + $Var['structures']['metalsilo'] + array('amount' => $Colony['metalsilo'], 'metalcapacity' => (500+100*$Colony['metalcentertechnology']) * $Colony['metalsilo']);
	if ($Colony['uransilo']) $result['uransilo']=$Lang['structures']['uransilo']+$Var['structures']['uransilo']+array('amount'=>$Colony['uransilo'],'urancapacity'=>(250+50*$Colony['urancentertechnology'])*$Colony['uransilo'],'energyratio'=>(-5-2*$Colony['urancentertechnology'])*$Colony['uransilo']);
	if ($Colony['foodsilo']) $result['foodsilo']=$Lang['structures']['foodsilo']+$Var['structures']['foodsilo']+array('amount'=>$Colony['foodsilo'],'foodcapacity'=>(300+50*$Colony['foodcentertechnology'])*$Colony['foodsilo'],'energyratio'=> (-2-0.5*$Colony['foodcentertechnology'])*$Colony['foodsilo']);
	return $result;
}

function buildslist($Colony, $Player)
{
	global $Lang, $Var;
	$result = array();

	switch ($Colony['base']) {
		case '1': if ($Colony['uranmine'] && $Player['level'] > 4) $result['base'] = $Lang['structures']['base'] + $Var['structures']['base'] + array('level'=>2,'energy'=>2000,'metal'=>1000,'uran'=>500,'work'=>1000,'score'=>100); break;
		case '2': if ($Colony['laboratory'] && $Colony['depot'] && $Player['level'] > 9) $result['base'] = $Lang['structures']['base'] + $Var['structures']['base'] + array('level'=>3,'energy'=>5000,'metal'=>1500,'uran'=>1500,'work'=>2500,'crystals'=>25,'score'=>1000); break;
		case '3': if ($Player['score'] > 9999 && $Player['level'] > 14) $result['base'] = $Lang['structures']['base'] + $Var['structures']['base'] + array('level'=>4,'energy'=>250000,'uran'=>15000,'work'=>5000,'crystals'=>2000,'score'=>10000); break;
		case '4': if ($Player['score'] > 99999 && $Player['level'] > 24) $result['base'] = $Lang['structures']['base'] + $Var['structures']['base'] + array('level'=>5,'energy'=>1000000,'metal'=>500000,'crystals'=>5000,'work'=>10000,'score'=>25000); break;
	}
		
	switch ($Colony['tron']) {
		case '1': if ($Colony['communicationstechnology'] && $Player['level'] > 4) $result['tron'] = $Lang['structures']['tron'] + $Var['structures']['base'] + array('level'=>2,'energy'=>10000,'silicon'=>500,'metal'=>1000,'work'=>1000,'score'=>100); break;
	}

	if ($Colony['base'] > $Colony['laboratory'] + 1) $result['laboratory'] = $Lang['structures']['laboratory'] + $Var['structures']['laboratory'] + array('level'=>$Colony['laboratory'] + 1,'energy'=>500+500*$Colony['laboratory'],'metal'=>750+500*$Colony['laboratory'],'credits'=>35000+15000*$Colony['laboratory'],'work'=>150+100*($Colony['laboratory']+$Colony['base']),'score'=>10+25*$Colony['laboratory']);
	if ($Colony['tron'] > $Colony['databank']) $result['databank'] = $Lang['structures']['databank'] + $Var['structures']['databank'] + array('level'=>$Colony['databank'] + 1,'energy'=>5000+10000*$Colony['databank'],'metal'=>1000+1000*$Colony['databank'],'credits'=>5000+15000*$Colony['databank'],'work'=>250*($Colony['databank'] + $Colony['tron']),'score'=>10+25*$Colony['databank']);

	if ($Colony['base']) {
		$result['factory'] = $Lang['structures']['factory'] + $Var['structures']['factory'];
		if ($Colony['factory'] && $Colony['corthosiumtechnology']) $result['spacedepot']=$Lang['structures']['spacedepot']+$Var['structures']['spacedepot'];
		$result['flats'] = $Lang['structures']['flats'] + $Var['structures']['flats'];
		$result['barracks'] = $Lang['structures']['barracks'] + $Var['structures']['barracks'];
		if ($Colony['barracks']) {
			if ($Colony['base'] > 1) $result['academy'] = $Lang['structures']['academy'] + $Var['structures']['academy'];
			$result['bunker']=$Lang['structures']['bunker']+$Var['structures']['bunker'];
		}
		if ($Colony['defensivetechnology']) $result['lasertower']=$Lang['structures']['lasertower']+$Var['structures']['lasertower'];
		if ($Colony['offensivetechnology']) $result['plasmatower']=$Lang['structures']['plasmatower']+$Var['structures']['plasmatower'];
		$result['windgenerator'] = $Lang['structures']['windgenerator'] + $Var['structures']['windgenerator'];
		if ($Colony['base'] > 1) $result['solarbattery'] = $Lang['structures']['solarbattery'] + $Var['structures']['solarbattery'];
		if ($Colony['base'] > 2) $result['fusionreactor'] = $Lang['structures']['fusionreactor'] + $Var['structures']['fusionreactor'];
		$result['metalextractor'] = $Lang['structures']['metalextractor'] + $Var['structures']['metalextractor'];
		if ($Colony['uransilo']) $result['uranmine'] = $Lang['structures']['uranmine'] + $Var['structures']['uranmine'];
		if ($Colony['base'] > 1) $result['foodplanting'] = $Lang['structures']['foodplanting'] + $Var['structures']['foodplanting'];
		$result['energysilo'] = $Lang['structures']['energysilo'] + $Var['structures']['energysilo'];
		$result['metalsilo'] = $Lang['structures']['metalsilo'] + $Var['structures']['metalsilo'];
		if ($Colony['uransources']) $result['uransilo'] = $Lang['structures']['uransilo'] + $Var['structures']['uransilo'];
		if ($Colony['base'] > 1) $result['foodsilo'] = $Lang['structures']['foodsilo'] + $Var['structures']['foodsilo'];
	}

	if ($result) foreach ($result as $id => $r) if (@$r['max'] && $Colony[$id] > $r['max']) $result[$id]['cost'] = round(($Colony[$id] - $r['max']) * $r['credits'] * $r['tax'] / 100);

	return $result;
}

function productionslist($Colony)
{
	global $Lang, $Var;
	$result = array();
	if ($Colony['factory']) {
		$result['bx1'] = $Lang['units']['bx1'] + $Var['units']['bx1'];
		$result['bx2'] = $Lang['units']['bx2'] + $Var['units']['bx2'];
		if ($Colony['bxtechnology']) {
			$result['bx5'] = $Lang['units']['bx5'] + $Var['units']['bx5'];
			$result['bx10'] = $Lang['units']['bx10'] + $Var['units']['bx10'];
			if ($Colony['bxtechnology'] > 1) {
				if ($Colony['advancedscanningtechnology'] > 1) $result['ax3'] = $Lang['units']['ax3'] + $Var['units']['ax3'];
				if ($Colony['bxtechnology'] > 2) {
					if ($Colony['advancedscanningtechnology'] > 1) $result['ax6'] = $Lang['units']['ax6'] + $Var['units']['ax6'];
					$result['walker'] = $Lang['units']['walker'] + $Var['units']['walker'];
				}
			}
		}
	}
	if ($Colony['tron']) {
		if ($Colony['databank']) $result['mmu'] = $Lang['units']['mmu'] + $Var['units']['mmu'];
		$result['worker'] = $Lang['units']['worker'] + $Var['units']['worker'];
	}
	if ($Colony['factory']) {
		$result['hawk'] = $Lang['units']['hawk'] + $Var['units']['hawk'];
		if ($Colony['hawktechnology']) $result['hawk']['work'] /= (1 + $Colony['hawktechnology']);
		if ($Colony['hawktechnology'] > 1 && $Colony['metalcentertechnology'] > 1) $result['valkyrie'] = $Lang['units']['valkyrie'] + $Var['units']['valkyrie'];
		if ($Colony['crusadertechnology']) $result['crusader'] = $Lang['units']['crusader'] + $Var['units']['crusader'];
		if ($Colony['warriortechnology']) $result['warrior'] = $Lang['units']['warrior'] + $Var['units']['warrior'];
		if ($Colony['dragontechnology']) $result['dragon'] = $Lang['units']['dragon'] + $Var['units']['dragon'];
		if ($Colony['whispertechnology']) $result['whisper'] = $Lang['units']['whisper'] + $Var['units']['whisper'];
		if ($Colony['nemesistechnology']) $result['nemesis'] = $Lang['units']['nemesis'] + $Var['units']['nemesis'];
		if ($Colony['scavengertechnology']) $result['scavenger'] = $Lang['units']['scavenger'] + $Var['units']['scavenger'];
		if ($Colony['carriertechnology']) $result['carrier'] = $Lang['units']['carrier'] + $Var['units']['carrier'];
	}
	if ($Colony['tron']) {
		if ($Colony['spaceshipstechnology']) $result['cage'] = $Lang['units']['cage'] + $Var['units']['cage'];
	}
	if ($Colony['factory']) {
		if ($Colony['vesseltechnology']) $result['vessel'] = $Lang['units']['vessel'] + $Var['units']['vessel'];
		if ($Colony['detectortechnology']) $result['detector'] = $Lang['units']['detector'] + $Var['units']['detector'];
		if ($Colony['satellitestechnology']) $result['satellite']=$Lang['units']['satellite']+$Var['units']['satellite'];

		foreach ($result as $id => $r)
			if (isset($r['max']) && $Colony[$id] > $r['max'])
				$result[$id]['cost'] = round(($Colony[$id] - $r['max']) * $r['credits'] * $r['tax'] / 100);
	}
	return $result;
}

function addtech($Colony, $name)
{
	global $Lang, $Var;

	$v = $Var['technologies'][$id = $name.'technology'];
	$a = pow(10, $l = $Colony[$id]);

	@$v['credits'] *= $a;
	@$v['energy'] *= $a;
	@$v['silicon'] *= $a;
	@$v['metal'] *= $a;
	@$v['uran'] *= $a;
	@$v['plutonium'] *= $a;
	@$v['deuterium'] *= $a;
	@$v['crystals'] *= $a;
	@$v['work'] *= $a;

	$v['id'] = $id;
	if (!isset($v['max'])) $v['max'] = 1;
	list($v['level'], $v['completed']) = $l < $v['max'] ? array($l + 1, false) : array($l, true);

	return array($id => $Lang['technologies'][$id] + $v);
}

function technologieslist($Colony) {
	global $Lang, $Var;
	$result = array();
	if ($Colony['base'] + $Colony['tron'] > 1) {
		$result += addtech($Colony, 'management');
	}
	if ($Colony['laboratory']) {
		$result += addtech($Colony, 'foodcenter');
		$result += addtech($Colony, 'resources');
	}
	if ($Colony['resourcestechnology']) {
		$result += addtech($Colony, 'energycenter');
		if ($Colony['laboratory'] > 1) $result += addtech($Colony, 'metalcenter');
		if ($Colony['laboratory'] > 2) $result += addtech($Colony, 'urancenter');
		if ($Colony['laboratory'] > 3) $result += addtech($Colony, 'crystalcenter');
	}
	if ($Colony['laboratory'] > 1) $result += addtech($Colony, 'military');
	if ($Colony['militarytechnology']) {
		if ($Colony['laboratory'] > 2) $result += addtech($Colony, 'defensive');
		if ($Colony['laboratory'] > 3) $result += addtech($Colony, 'offensive');
	}
	if ($Colony['laboratory']) {
		$result += addtech($Colony, 'bx');
		$result += addtech($Colony, 'detector');
	}
	if ($Colony['detectortechnology']) {
		if ($Colony['laboratory'] > 1) $result += addtech($Colony, 'advancedscanning');
		if ($Colony['spacedepot'] && ($Colony['laboratory'] > 2)) $result += addtech($Colony, 'satellites');
	}
	if ($Colony['laboratory']) $result += addtech($Colony, 'hawk');
	if ($Colony['hawktechnology']) {
		$result += addtech($Colony, 'crusader');
		$result += addtech($Colony, 'scavenger');
	}
	if ($Colony['crusadertechnology']) {
		if ($Colony['laboratory'] > 1) {
			$result += addtech($Colony, 'warrior');
			if ($Colony['laboratory'] > 2) {
				$result += addtech($Colony, 'corthosium');
			}
		}
		$result += addtech($Colony, 'dragon');
	}
	if ($Colony['warriortechnology']) {
		$result += addtech($Colony, 'carrier');
		$result += addtech($Colony, 'nemesis');
		if ($Colony['laboratory'] > 3 && $Colony['corthosiumtechnology']) $result += addtech($Colony, 'whisper');
	}
	if ($Colony['detectortechnology']) {
		$result += addtech($Colony, 'vessel');
	}
	if ($Colony['databank']) $result += addtech($Colony, 'tron');
	if ($Colony['trontechnology']) {
		$result += addtech($Colony, 'spaceships');
		if ($Colony['spaceshipstechnology']) $result += addtech($Colony, 'tactics');
		$result += addtech($Colony, 'bio');
		$result += addtech($Colony, 'atom');
		$result += addtech($Colony, 'communications');
	}
	return $result;
}

function updateplayer($Player) {
	global $db, $prefix;
	$db->query("UPDATE {$prefix}users SET `credits`='${Player['credits']}',`bank`='${Player['bank']}',`score`='${Player['score']}',`exp`='${Player['exp']}',`hp`='${Player['hp']}',`hpgain`='${Player['hpgain']}',`mp`='${Player['mp']}',`mpgain`='${Player['mpgain']}',`thicks`='${Player['thicks']}',`strengthmodifier`='${Player['strengthmodifier']}',`agilitymodifier`='${Player['agilitymodifier']}',`hpmodifier`='${Player['hpmodifier']}',`mpmodifier`='${Player['mpmodifier']}' WHERE `id`='${Player['id']}';");
}

function updatecolony($Colony) {
	global $db, $prefix;
	$db->query("UPDATE {$prefix}colonies SET `thicks`='${Colony['thicks']}',`energy`='${Colony['energy']}',`silicon`='${Colony['silicon']}',`metal`='${Colony['metal']}',`uran`='${Colony['uran']}',`plutonium`='${Colony['plutonium']}',`deuterium`='${Colony['deuterium']}',`food`='${Colony['food']}',`crystals`='${Colony['crystals']}' WHERE `id`='${Colony['id']}' LIMIT 1;");
}

function explorationfinish($name) {
	global $login, $db, $prefix;
	if (!$name) $name = $login;
	$db->query("DELETE FROM {$prefix}exploration WHERE login='$name';");
}

function buildingfinish($name) {
	global $login, $db, $prefix;
	if (!$name) $name = $login;
	$db->query("DELETE FROM {$prefix}buildings WHERE login='$name';");
}

function checkplace($name)
{
	global $db, $prefix, $Player, $place, $places;
	if (!$Player['destination']) {
		switch ($name) {
			case 'market':  $db->query("SELECT * FROM {$prefix}markets WHERE position='{$Player['planet']}' LIMIT 1;"); break;
			default:  $db->query("SELECT * FROM ${prefix}places WHERE type='$name' AND position='${Player['planet']}' LIMIT 1;");
		}
		return $places[$name] = $place = $db->fetchrow();
//			return $place = $db->fetchrow();
	}
	else return FALSE;
}

function playerexists($name)
{
	global $db, $prefix;
	$db->query("SELECT login FROM {$prefix}users WHERE login='".$db->safe($name)."';");
	return $db->numrows() > 0;
}

function colonyexists($name)
{
	global $db, $prefix;
	$db->query("SELECT `owner` FROM `${prefix}colonies` WHERE `name`='$name' LIMIT 1;");
	return ($t = $db->fetchrow()) ? $t['owner'] : '';
}

function playerlevel($name)
{
	global $db, $prefix;
	$db->query("SELECT `level` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
	return ($t = $db->fetchrow()) ? $t['level'] : '';
}

function playerscore($name) {
	global $db, $prefix;
	$db->query("SELECT `score` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
	return ($t = $db->fetchrow()) ? $t['score'] : '';
}

function playergroup($name) {
	global $db, $prefix;
	$db->query("SELECT `clan` FROM `${prefix}users` WHERE `login`='$name' LIMIT 1;");
	return ($t = $db->fetchrow()) ? $t['clan'] : '';
}

// ===================================================================
// ENGINE :)
// ===================================================================

// -------------------------------------------------------------------
// Generate random item in diablo style
// -------------------------------------------------------------------

function generateitem($type = '', $level = 0, $class = '')
{
	global $db, $prefix;

	$w = '';
	if ($type) $w = " AND `type`='$type'";
	$t = '';
	$db->query("SELECT * FROM `${prefix}items` WHERE `class`='$class'$w;");
	while ($x = $db->fetchrow()) $t[] = $x;

	if ($t) {
		$i = $t[rand(0, count($t) - 1)];
		$i['maxlevel'] = $level;
		$i['level'] = $l = rand(0, $level);
		$i['price'] += floor($i['price'] * ($level - $l) / 2);
		$i['price'] += floor($i['price'] * $l * 2);
		$i['req_level'] += floor($i['req_level'] * $l / 2);
		$i['req_strength'] += floor(10 * $i['req_strength'] * $l / 3) / 10;
		$i['req_agility'] += floor(10 * $i['req_agility'] * $l / 3) / 10;
		$i['req_psi'] += floor(10 * $i['req_psi'] * $l / 3) / 10;
		$i['req_force'] += floor(10 * $i['req_force'] * $l / 3) / 10;
		$i['min'] += floor(100 * $i['min'] * $l / 4) / 100;
		$i['max'] += floor(100 * $i['max'] * $l / 4) / 100;
		$i['armor'] += floor(100 * $i['armor'] * $l / 4) / 100;
		$i['hit'] += floor($i['hit'] * $l / 5);
		$i['criticalhit'] += floor($i['criticalhit'] * $l / 5);
		$i['block'] += floor($i['block'] * $l / 5);
		$i['speed'] += floor($i['speed'] * $l / 5);
		$i['deaf'] += floor($i['deaf'] * $l / 5);
		$i['hide'] += floor($i['hide'] * $l / 5);
		$i['protection'] += floor($i['protection'] * $l / 5);

		return $i;
	}
	else return FALSE;
}

// -------------------------------------------------------------------
// Making some reports
// -------------------------------------------------------------------

function defenderreport($lost, $t, $Colony)
{
	global $Lang, $Attackers, $Defenders;

	$r = $lost ? $Lang['AttackLost'] : $Lang['AttackWon'];
	$r .= '<br /><br /><font class="minus"><b>'.$Lang['Enemy'].'</b>:</font><br /><br /><table align="center" width="100%" cellspacing="0" cellpadding="0" border="0"><tr id="header"><td id="headerl">&nbsp;</td><td align="left">'.$Lang['UnitName'].':</td><td>&nbsp; &nbsp;</td><td align="center">'.$Lang['Count'].':</td><td>&nbsp; &nbsp;</td><td id="headerr" align="center">'.$Lang['ELost'].':</td></tr>';

	$td1 = '<tr><td>&nbsp;</td><td align="left">';
	$td2 = '</td><td>&nbsp;</td><td align="center"><font class="result">';
	$td3 = '</font></td><td>&nbsp;</td><td align="center"><font class="plus">';
	$td4 = '</font></td><td>&nbsp;</td><td align="center"><font class="minus">';
	$td5 = '</font></td></tr>';
	
	foreach ($Attackers as $unit) if ($t[$unit]) $r .= $td1.$Lang['units'][$unit]['name'].$td2.strdiv($t[$unit]).$td3.strdiv($t[$unit.'lost']).$td5;

	$r .= '</table><br />';
	$r .= '<font class="plus"><b>'.$Lang['Colony'].'</b>:</font><br /><br />';
	$r .= '<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0"><tr id="headerw"><td id="headerwl">&nbsp;</td><td align="left">'.$Lang['UnitName'].':</td><td>&nbsp; &nbsp;</td><td align="center">'.$Lang['Count'].':</td><td>&nbsp; &nbsp;</td><td id="headerwr" align="center">'.$Lang['ELost'].':</td></tr>';

	foreach ($Defenders as $unit) if ($Colony[$unit] || $t[$unit.'killed']) $r .= $td1.$Lang['units'][$unit]['name'].$td2.strdiv($Colony[$unit]).$td4.strdiv($t[$unit.'killed']).$td5;

	$r .= '</table><br />';

	if ($t['colonistskilled']) $r .= $Lang['Lost'].': '.$Lang['Colonists'].': <b>'.$t['colonistskilled'].'</b>.<br />';
	if ($t['scientistskilled']) $r .= $Lang['Lost'].': '.$Lang['Scientists'].': <b>'.$t['scientistskilled'].'</b>.<br />';
	if ($t['soldierskilled']) $r .= $Lang['Lost'].': '.$Lang['Soldiers'].': <b>'.$t['soldierskilled'].'</b>.<br />';

	$r .= '<br />';

	if ($t['metal']) $r .= $Lang['Lost'].': '.$Lang['Metal'].' <b>[M]</b> <font class="minus">'.div($t['metal']).'.</font><br />';
	if ($t['uran']) $r .= $Lang['Lost'].': '.$Lang['Uran'].' <b>[M]</b> <font class="minus">'.div($t['uran']).'.</font><br />';
	if ($t['crystals']) $r .= $Lang['Lost'].': '.$Lang['Crystals'].' <b>[M]</b> <font class="minus">'.div($t['crystals']).'.</font><br />';
	if ($t['soldierslost']) $r .= '<font class="result">'.$Lang['EnemySoldiersKilled'].': '.div($t['soldierslost']).'.</font><br />';
	if ($t['bx10lost']) $r .= '<font class="result">'.$Lang['RobotsDestroyed'].': '.div($t['bx10lost']).'.</font><br />';
	if ($t['solarbattery']) $r .= $Lang['Lost'].': '.$Lang['structures']['solarbattery']['name'].': '.div($t['solarbattery']).'.</font><br />';
	if ($t['windgenerator']) $r .= $Lang['Lost'].': '.$Lang['structures']['windgenerator']['name'].': '.div($t['windgenerator']).'.</font><br />';

	$r .= '<br />';

	if ($t['score'] > 0) $r .= '<font class="minus">'.$Lang['ScoreLost'].': '.div($t['score']).'.</font><br />';
	elseif ($t['score'] < 0) $r .= '<font class="plus">'.$Lang['ScoreGained'].': '.div(-$t['score']).'.</font><br />';
	if ($t['exp'] > 0) $r .= '<font class="minus">'.$Lang['LostExperience'].': '.div($t['exp']).'.</font><br />';
	elseif ($t['exp'] < 0) $r .= '<font class="plus">'.$Lang['GainedExperience'].': '.div(-$t['exp']).'.</font><br />';
	if ($t['credits'] > 0) $r .= '<font class="minus">'.$Lang['CreditsLost'].': '.div($t['credits']).'.</font><br />';
	elseif ($t['credits'] < 0) $r .= '<font class="plus">'.$Lang['CreditsGained'].': '.div(-$t['credits']).'.</font><br />';

	return $r;
}

function attackerreport($t)
{
	global $Lang, $Attackers, $Defenders;

	switch ($t['status']) {
		case 2: $msg = '<font class="plus">'.$Lang['AttackW'].'</font><br /><br />'; break;
		case 3: $msg = '<font class="minus">'.$Lang['AttackLost'].'</font><br /><br />'; break;
		case 4: $msg = '<font class="minus">'.$Lang['AttackE'].'</font><br /><br />'; break;
		case 5: $msg = '<font class="result">'.$Lang['AttackC'].'</font><br /><br />'; break;
	}

	$r = '';

	$td1 = '<tr><td>&nbsp;</td><td align="left">';
	$td2 = '</td><td>&nbsp;</td><td align="center"><font class="result">';
	$td3 = '</font></td><td>&nbsp;</td><td align="center"><font class="plus">';
	$td4 = '</font></td><td>&nbsp;</td><td align="center"><font class="minus">';
	$td5 = '</font></td></tr>';
	
	foreach ($Defenders as $unit) if ($t[$unit.'killed']) $r .= $td1.$Lang['units'][$unit]['name'].$td4.strdiv($t[$unit.'killed']).$td5;
	
	if ($r) {
		$msg .= '<font class="minus"><b>'.$Lang['Enemy'].'</b>:</font><br /><br />';
		$msg .= '<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0"><tr id="header"><td id="headerl">&nbsp;</td><td align="left">'.$Lang['UnitName'].':</td><td>&nbsp; &nbsp;</td><td id="headerr" align="center">'.$Lang['Destroyed'].':</td></tr>';
		$msg .= $r;
		$msg .= '</table><br />';
	}

	$msg .= '<font class="plus"><b>'.$Lang['Troops'].'</b>:</font><br /><br /><table align="center" width="100%" cellspacing="0" cellpadding="0" border="0"><tr id="headerw"><td id="headerwl">&nbsp;</td><td align="left">'.$Lang['UnitName'].':</td><td>&nbsp; &nbsp;</td><td align="center">'.$Lang['Count'].':</td><td>&nbsp; &nbsp;</td><td id="headerwr" align="center">'.$Lang['ELost'].':</td></tr>';

	foreach ($Attackers as $unit) if ($t[$unit]) $msg .= $td1.$Lang['units'][$unit]['name'].$td2.strdiv($t[$unit]).$td3.strdiv($t[$unit.'lost']).$td5;
	
	$msg .= '</table><br />';

	if ($t['soldierslost']) $msg .= '<font class="minus">'.$Lang['SoldiersLost'].': '.div($t['soldierslost']).'.</font><br />';
	if ($t['colonistskilled']) $msg .= $Lang['Killed'].': '.$Lang['Colonists'].': <b>'.$t['colonistskilled'].'</b>.<br />';
	if ($t['scientistskilled']) $msg .= $Lang['Killed'].': '.$Lang['Scientists'].': <b>'.$t['scientistskilled'].'</b>.<br />';
	if ($t['soldierskilled']) $msg .= $Lang['Killed'].': '.$Lang['Soldiers'].': <b>'.$t['soldierskilled'].'</b>.<br />';
	if ($t['solarbattery']) $msg .= $Lang['Destroyed'].': '.$Lang['structures']['solarbattery']['name'].': '.div($t['solarbattery']).'.</font><br />';
	if ($t['windgenerator']) $msg .= $Lang['Destroyed'].': '.$Lang['structures']['windgenerator']['name'].': '.div($t['windgenerator']).'.</font><br />';

	$msg .= '<br />';

	if ($t['metal']) $msg .= $Lang['Gained'].': '.$Lang['Metal'].' <b>[M]</b> <font class="plus">'.div($t['metal']).'.</font><br />';
	if ($t['uran']) $msg .= $Lang['Gained'].': '.$Lang['Uran'].' <b>[M]</b> <font class="plus">'.div($t['uran']).'.</font><br />';
	if ($t['crystals']) $msg .= $Lang['Gained'].': '.$Lang['Crystals'].' <b>[M]</b> <font class="plus">'.div($t['crystals']).'.</font><br />';

	$msg .= '<br />';

	if ($t['score'] < 0) $msg .= '<font class="minus">'.$Lang['ScoreLost'].': '.div(-$t['score']).'.</font><br />';
	elseif ($t['score'] > 0) $msg .= '<font class="plus">'.$Lang['ScoreGained'].': '.div($t['score']).'.</font><br />';
	if ($t['exp'] < 0) $msg .= '<font class="minus">'.$Lang['LostExperience'].': '.div(-$t['exp']).'.</font><br />';
	elseif ($t['exp'] > 0) $msg .= '<font class="plus">'.$Lang['GainedExperience'].': '.div($t['exp']).'.</font><br />';
	if ($t['credits'] < 0) $msg .= '<font class="minus">'.$Lang['CreditsLost'].': '.div(-$t['credits']).'.</font><br />';
	elseif ($t['credits'] > 0) $msg .= '<font class="plus">'.$Lang['CreditsGained'].': '.div($t['credits']).'.</font><br />';

	return $msg;
}

// -------------------------------------------------------------------
// Colony Battle
// -------------------------------------------------------------------

function battle($t, $i, &$Player, &$Colony)
{
	global $db, $prefix, $Lang, $Var, $Attackers, $Defenders;

	$name = $Player['login'];

	$db->query("SELECT `thicks`,`login`,`score`,`exp`,`credits` FROM {$prefix}users WHERE login='".$t['login']."';");
	$Attacker = $db->fetchrow();

	// ATTACK: PREPARE $a (atacking), $d (defending), $ai/$di (indexes)

	$a = array();
	$d = array();
	
	foreach ($Attackers as $unit) $a[] = array('id'=>$unit, 'amount'=>$t[$unit]);
	foreach ($Defenders as $unit) $d[] = array('id'=>$unit, 'amount'=>$Colony[$unit]);

	for ($j = 0; $j < count($a); $j++) $ai[$a[$j]['id']] = $j;
	for ($j = 0; $j < count($d); $j++) $di[$d[$j]['id']] = $j;

	$ac = 0;	// count
	$aa = 0;	// attack
	$ad = 0;	// damage
	$ak = 0;	// killed
	foreach($a as $u) {
		$ac += $u['amount'];
		$aa += $u['amount']*@$Var['units'][$u['id']]['attack'];
		$ad += $u['amount']*@$Var['units'][$u['id']]['damage'];
	}
	$as = $ac;	// store

	$dc = 0;	// count
	$da = 0;	// attack
	$dd = 0;	// damage
	$dk = 0;	// killed
	foreach($d as $u) {
		$dc += $u['amount'];
		$da += $u['amount']*@$Var['units'][$u['id']]['attack'];
		$dd += $u['amount']*@$Var['units'][$u['id']]['damage'];
	}
	$ds = $dc;	// store

	$e = (($aa + $t['bonus'] - $Colony['defense']) / $aa) - Rand(-5 * $t['strategy'], 10 * $t['strategy'] + 15 * $t['strategy'] * $t['status']) / 100;
	if ($e < 0) $e = 0;
	$aa *= $e;

	$msg = $Lang['Attack0'].' <a href="whois.php?name='.$t['login'].'">'.$t['login'].'</a>'.$Lang['Attack1'].$t['owner'].$Lang['Attack00'].'<font class="capacity">'.$Lang['Strategies'][$t['strategy']].'</font>'.$Lang['Attack000'].'<br /><br />';

	if (!$t['strategy'] && ($aa > 3 * $da)) {
		if ($Player['credits'] > 100000) $t['credits'] = 1 + round(Rand(1, 200) * $Player['credits'] / 10000);
		$t['score'] = 1 + round(Rand(1, 100) * $Player['score'] / 10000);
		$t['exp'] = 1 + round(Rand(1, 100) * $Player['exp'] / 10000);
		$e = 100;
		$t['status'] = 2;
		$lost = TRUE;
	}
	elseif (!$t['strategy'] && ($da > 3 * $aa)) {
		$t['credits'] = -1 - round(Rand(1, 250) * $Attacker['credits'] / 10000);
		$t['score'] = -1 - round(Rand(1, 100) * $Attacker['score'] / 10000);
		$t['exp'] = -1 - round(Rand(1, 100) * $Attacker['exp'] / 10000);
		$e = 0;
		$t['status'] = 4;
		$lost = FALSE;
	}
	else {
		// ATTACK: STRATEGY 0
		if (!$t['strategy']) {
			// Agresor: Atacking enemy units
			foreach ($a as $u) {
				$unit = $Var['units'][$u['id']];
				if ($enemies = $unit['enemy']) foreach (explode(',', $enemies) as $enemy) {
					$c = floor($e * $u['amount'] * $unit['attack'] / $Var['units'][$enemy]['damage']);
					if ($d[$di[$enemy]]['amount'] < $c) $c = $d[$di[$enemy]]['amount'];
					$aa -= $c * $Var['units'][$enemy]['damage'];
					$dd -= $c * $Var['units'][$enemy]['damage'];
					$ak += $c;
					$dc -= $c;
					$d[$di[$enemy]]['amount'] -= $c;
					$t["${enemy}killed"] += $c;
				}
			}
			// Defender: Atacking enemy units
			foreach ($d as $u) {
				$unit = $Var['units'][$u['id']];
				if ($enemies = @$unit['enemy']) foreach (explode(',', $enemies) as $enemy) {
					$c = floor($u['amount'] * $unit['attack'] / $Var['units'][$enemy]['damage']);
					if ($a[$ai[$enemy]]['amount'] < $c) $c = $a[$ai[$enemy]]['amount'];
					$da -= $c * $Var['units'][$enemy]['damage'];
					$ad -= $c * $Var['units'][$enemy]['damage'];
					$dk += $c;
					$ac -= $c;
					$a[$ai[$enemy]]['amount'] -= $c;
					$t["${enemy}lost"] += $c;
				}
			}
		}

		// ATTACK: STRATEGY 0 & 1
		if ($t['strategy'] < 2) {
			do {
				// Agresor: Atacking all
				if (($dc > 0) && ($aa > 0)) {
					$ee = $aa / $dc;
					foreach ($d as $u) {
						if (!$t['strategy'] && ($Var['units'][$u['id']]['type'] == 'robot')) continue;
						$c = floor($e * $u['amount'] * $ee / $Var['units'][$u['id']]['damage']);
						if ($u['amount'] < $c) $c = $u['amount'];
						$da -= $c*@$Var['units'][$u['id']]['attack'];
						$d[$di[$u['id']]]['amount'] -= $c;
						$t["${u['id']}killed"] += $c;
						$ak += $c;
					}
				}
				// Defender: Atacking all
				if (($ac > 0) && ($da > 0)) {
					$ee = $da / $ac;
					foreach ($a as $u) {
						$c = floor($u['amount'] * $ee / $Var['units'][$u['id']]['damage']);
						if ($u['amount'] < $c) $c = $u['amount'];
						$a[$ai[$u['id']]]['amount'] -= $c;
						$t["${u['id']}lost"] += $c;
						$dk += $c;
					}
				}
			} while (($t['strategy'] == 2) && ($ak < 0.7 * $ds) && ($dk < 0.99 * $as));
		}

		if (!$ds || $ak / $ds > $dk / $as) $lost = TRUE;
		else $lost = FALSE;

		$t['soldierslost'] = floor($t['soldiers'] * $dk / $as);
		$t['bx10lost'] = floor($t['bx10'] * $dk / $as);
		$t['walkerlost'] = floor($t['walker'] * $dk / $as);

		if ($lost) {
			if ($c = $a[$ai['scavenger']]['amount']) {
				if (Rand(1, 30) < 10) $t['uran'] = round(Rand(1, 20 + 25 * $t['strategy'] + 5 * $c) * $Colony['uran'] / 1000);
				else $t['metal'] = round(Rand(1, 20 + 25 * $t['strategy'] + 5 * $c) * $Colony['metal'] / 1000);
			}

			if ($c = $a[$ai['carrier']]['amount']) {
				$t['uran'] = round(Rand(1, 10 + 15 * $t['strategy'] + 5 * $c) * $Colony['uran'] / 1000);
				$t['metal'] = round(Rand(1, 10 + 15 * $t['strategy'] + 5 * $c) * $Colony['metal'] / 1000);
				$t['crystals'] = round(Rand(1, 5 + 15 * $t['strategy'] + 5 * $c) * $Colony['crystals'] / 1000);
			}

			if (($c = $t['soldiers'] - $t['soldierslost'] - $dk) > 0) {
				if ($Colony['soldiersfree'] > 0) $f = $c / $Colony['soldiersfree'];
				else $f = $c;
				if ($f > 5) $f = 5;
				else if ($f < 0) $f = 0;
				$t['colonistskilled'] = round($f * Rand(1, 5 + 10 * $t['strategy']) * $Colony['colonistsfree'] / 100);
				$t['scientistskilled'] = round($f * Rand(1, 5 + 10 * $t['strategy']) * $Colony['scientistsfree'] / 100);
				$t['soldierskilled'] = round($f * Rand(1, 5 + 10 * $t['strategy']) * $Colony['soldiersfree'] / 100);
				$t['soldierslost'] += round(Rand(1, 50) * $c / 100);
			}

			if (($c = $t['bx10'] - $t['bx10lost'] + $t['carrier'] - $t['carrierlost']) > 0) {
				for ($j = 0; $j < $c; $j++) {
					if ($t['solarbattery'] < $Colony['solarbattery']) if (Rand(1, 100) < 10) $t['solarbattery'] += 1;
					if ($t['windgenerator'] < $Colony['windgenerator']) if (Rand(1, 100) < 15) $t['windgenerator'] += 1;
				}
			}

			if ($ds) $dmax = 100 * $ak / $ds;
			else $dmax = 1500;

			if ($t['strategy']) $Colony['damage'] += Rand(1, $dmax) / 100;
			else $t['credits'] = 1 + round(Rand(1, 100) * $Player['credits'] / 10000);

			$t['score'] = 1 + round(Rand(1, 150 + 200 * $t['strategy']) * $Player['score'] / 10000) + $t['windgenerator'] + $t['solarbattery'];
			$t['exp'] = 1 + round(Rand(1, 150 + 250 * $t['strategy']) * $Player['exp'] / 10000) + $t['soldierskilled'];
			$t['status'] = 2;
		}
		else {
			if (!$t['strategy']) $t['credits'] = -1 - round(Rand(1, 150) * $Attacker['credits'] / 10000);
			$t['score'] = -1 - round(Rand(1, 150 + 200 * $t['strategy']) * $Attacker['score'] / 10000);
			$t['exp'] = -1 - round(Rand(1, 150 + 300 * $t['strategy']) * $Attacker['exp'] / 10000) - $t['soldierslost'];
			$t['status'] = 3;
		}
	}

	// ATTACK: RESULTS & REPORT

	if ($Player['credits'] < $t['credits']) $t['credits'] = $Player['credits'];
	if ($Player['exp'] < $t['exp']) $t['exp'] = $Player['exp'];
	if ($Colony['metal'] < $t['metal']) $t['metal'] = $Colony['metal'];
	if ($Colony['uran'] < $t['uran']) $t['uran'] = $Colony['uran'];
	if ($Colony['crystals'] < $t['crystals']) $t['crystals'] = $Colony['crystals'];
	if ($Colony['colonists'] < $t['colonistskilled']) $t['colonistskilled'] = $Colony['colonists'];
	if ($Colony['scientists'] < $t['scientistskilled']) $t['scientistskilled'] = $Colony['scientists'];
	if ($Colony['soldiers'] < $t['soldierskilled']) $t['soldierskilled'] = $Colony['soldiers'];
	
	$Player['credits'] -= $t['credits'];
	$Player['exp'] -= $t['exp'];

	$Colony['metal'] -= $t['metal'];
	$Colony['uran'] -= $t['uran'];
	$Colony['crystals'] -= $t['crystals'];
	$Colony['solarbattery'] -= $t['solarbattery'];
	$Colony['windgenerator'] -= $t['windgenerator'];
	$Colony['attacked'] += 1;
	$Colony['colonists'] -= $t['colonistskilled'];
	$Colony['scientists'] -= $t['scientistskilled'];
	$Colony['soldiers'] -= $t['soldierskilled'];

	foreach ($Defenders as $unit) $Colony[$unit] -= $t[$unit.'killed'];

	$e = round(100 * $e) / 100;

	$t['begin'] = $t['end'];

	$sql = "UPDATE {$prefix}attacks SET `begin`='${t['begin']}',`status`='${t['status']}',`efficacy`='$e',`colonistskilled`='${t['colonistskilled']}',`scientistskilled`='${t['scientistskilled']}',`soldierslost`='${t['soldierslost']}',`soldierskilled`='${t['soldierskilled']}',";
	foreach ($Attackers as $u) $sql .= "`{$u}lost`='".$t[$u.'lost']."',";
	foreach ($Defenders as $u) $sql .= "`{$u}killed`='".$t[$u.'killed']."',";
	$sql .= "`windgenerator`='${t['windgenerator']}',`solarbattery`='${t['solarbattery']}',";
	$sql .= "`score`='{$t['score']}',`exp`='{$t['exp']}',`credits`='{$t['credits']}',`metal`='${t['metal']}',`uran`='${t['uran']}',`crystals`='${t['crystals']}' WHERE id='${t['id']}';";

	$db->query($sql);

	$sql = "UPDATE `${prefix}colonies` SET `damage`='${Colony['damage']}',`windgenerator`='${Colony['windgenerator']}',`solarbattery`='${Colony['solarbattery']}',`attacked`='${Colony['attacked']}',`colonists`='${Colony['colonists']}',`scientists`='${Colony['scientists']}',`soldiers`='${Colony['soldiers']}'";
	foreach ($Defenders as $unit) $sql .= ",`$unit`='{$Colony[$unit]}'";	
	$sql .= "WHERE id='{$Colony['id']}';";
	$db->query($sql);

	sendmessage($Lang['AttackS'].$t['login'], defenderreport($lost, $t, $Colony), '', $name, 'report', $i);
}

// -------------------------------------------------------------------
// Exploration
// -------------------------------------------------------------------

function explore($i, $Exploration, $Player, $Colony, $Units, $Planet)
{
	global $foundpercentage, $db, $prefix, $Lang, $starday;

	$Colony['colonistsfree'] += $Exploration['colonists'];
	$Colony['scientistsfree'] += $Exploration['scientists'];
	$Colony['soldiersfree'] += $Exploration['soldiers'];
	$Colony['vesselsfree'] += $Exploration['vessels'];
	$Colony['colonistseat'] += $Exploration['colonists'];
	$Colony['scientistseat'] += $Exploration['scientists'];
	$Colony['soldierseat'] += $Exploration['soldiers'];
	if (isset($Units['vessel'])) $Units['vessel']['amount'] = $Colony['vesselsfree'];

	$crew = $Exploration['colonists'] + $Exploration['scientists'] + $Exploration['soldiers'];
	$z = 700 * ($Exploration['scientists'] / ($crew) / $Exploration['time']);
	if ($z > $foundpercentage) $z = $foundpercentage;

	$killed = $Exploration['time'] * (1 - ($Exploration['soldiers'] / ($crew) / $Exploration['time'])  / 5);

	$msg = $Lang['Explor1'] . '<br />';

	if ($Exploration['type'] == 'planet') {
		switch ($Planet['class']) {
			case 'small': $e = 0.001; break;
			case 'medium': $e = 0.0005; break;
			case 'big': $e = 0.00033; break;
			case 'huge': $e = 0.00025; break;
			case 'giant': $e = 0.00015; break;
		}
		$e *= $Exploration['time'];
		$Planet['explored'] += $e;
		if ($Planet['explored'] > 100) $Planet['explored'] = 100;
		elseif ($Planet['explored'] < 0) $Planet['explored'] = 0;
		$db->query("UPDATE `${prefix}space` SET `explored`='${Planet['explored']}' WHERE `name`='${Planet['name']}' LIMIT 1;");
		$msg .= $Lang['Explor2'] . '<font class="capacity">' . (round(1000 * $e) / 1000) . '</font>%.<br />';
	}
	$msg .= '<br /><font class="result"><b>' . $Lang['Explor3'] . '</b></font>:<br /><br />';

	$b = TRUE;

	for ($j = 0; $j < $Exploration['time'] / $starday; $j++) {
		$found = FALSE;
		if (Rand(0, 99) < $foundpercentage + $z) $found = TRUE;
		if ($found && !$killed) $killed = Rand(0, 99) < 5;

		if ($found) {
			$b = FALSE;
			$Player['score']++;
			if (Rand(0, 99) < 5 + ($Exploration['type'] == 'galaxy' ? 10 : 0)) {
				$v = round($crew * Rand(1, 15) / 50) + 1;
				$Colony['crystals'] += $v;
				$Player['score'] += 2;
				$msg .= $Lang['ExplorRC'] . $v . $Lang['ExplorRC1'] . '<br />';
			}
			elseif (Rand(0, 199) < 75 + 25 * $Colony['resourcestechnology']) {
				if (Rand(0, 999) < 333) {
					if (($v = floor(1 + round(Rand(1, 25 + $crew - 3 * $Exploration['vessels'] + $Colony['satellite']) / 11))) < 1) $v = 1;
					$Colony['uransources'] += $v;
					$msg .= $Lang['ExplorSU'] . '<font class="plus">' . $v . '</font>.<br />';
					$Player['score'] += 1;
				}
				else {
					if (($v = floor(1 + round(Rand(1, 66 + $crew - 2 * $Exploration['vessels'] + $Colony['satellite']) / 9))) < 1) $v = 1;
					$Colony['metalsources'] += $v;
					$msg .= $Lang['ExplorSM'] . '<font class="plus">' . $v . '</font>.<br />';
					$Player['score'] += 1;
				}
			}
			else {
				if (Rand(0, 199) < 119) {
					if (($v = ($Colony['base']+$Colony['tron'])*$Player['level']*round(($Exploration['vessels']+$Colony['satellite']+$crew)*rand(1, 50)/10)) < 1) $v = 1;
					$Colony['metal'] += $v;
					$msg .= $Lang['ExplorRM'].'<font class="capacity">'.$v.'</font>.<br />';
				}
				else {
					if (($v = $Player['level']*round(($Exploration['vessels']+$Colony['satellite']+$crew)*rand(1, 15)/10)) < 1) $v = 1;
					$Colony['uran'] += $v;
					$msg .= $Lang['ExplorRU'].'<font class="capacity">'.$v.'</font>.<br />';
				}
			}
		}
	}

	$db->query("UPDATE `${prefix}colonies` SET `uransources`='${Colony['uransources']}',`metalsources`='${Colony['metalsources']}' WHERE `id`='${Colony['id']}' LIMIT 1;");

	if ($b) $msg .= $Lang['ExplorX'] . '<br />';

	$msg .= '<br />';

	if ($Exploration['vessels'] && $killed) $killed = Rand(1, 100) < 50;

	if ($killed) { // killed
		$colonistskilled = round($Exploration['colonists'] * Rand(1, 33) / 100);
		$scientistskilled = round($Exploration['scientists'] * Rand(1, 45) / 100);
		$soldierskilled = round($Exploration['soldiers'] * Rand(1, 25) / 100);

		$Colony['colonists'] -= $colonistskilled;
		$Colony['scientists'] -= $scientistskilled;
		$Colony['soldiers'] -= $soldierskilled;
		$Colony['colonistsfree'] -= $colonistskilled;
		$Colony['scientistsfree'] -= $scientistskilled;
		$Colony['soldiersfree'] -= $soldierskilled;

		$Player['exp'] += $exp = round(0.1 * $scientistskilled + 0.2 * $colonistskilled + 0.5 * $soldierskilled);

		if ($colonistskilled || $scientistskilled || $soldierskilled) {
			$msg .= '<font class="delete"><b>' . $Lang['ELost'] . '</b></font>:<br /><br />';
			if ($colonistskilled) $msg .= $Lang['Colonists'].': <b>'.$colonistskilled.'</b><br />';
			if ($scientistskilled) $msg .= $Lang['Scientists'].':<b>'.$scientistskilled.'</b></br />';
			if ($soldierskilled) $msg .= $Lang['Soldiers'] . ':<b>'.$soldierskilled.'</b><br />';
			if ($exp) $msg .= $Lang['GainedExperience'] . ':<b>'.$exp.'</b><br />';
			$msg .= '<br />';

			$db->query("UPDATE `${prefix}colonies` SET `colonists` = '${Colony['colonists']}', `scientists` = '${Colony['scientists']}', `soldiers` = '${Colony['soldiers']}' WHERE `id` = '${Colony['id']}' LIMIT 1;");
		}
	}

	if ($Exploration['type'] == 'planet') {
		if (Rand(0, 99) < 10) { // score bonus
			$a = round(1 + $crew / 5 + $Exploration['scientists'] / 2);
			$Player['score'] += $a;
			$msg .= $Lang['ExplorB2'] . '<b>' . $a . '</b>.<br /><br />';
		}
		elseif (Rand(0, 99) < 10) { // exp bonus
			$a = round(1 + $crew / 5 + $Exploration['soldiers'] / 2);
			$Player['exp'] += $a;
			$msg .= $Lang['ExplorB1'] . '<b>' . $a . '</b>.<br /><br />';
		}
	}

	$msg .= '<a href="explore.php">' . $Lang['Explor4'] . ' &gt;&gt;<br />';

	explorationfinish($Player['login']);

	sendmessage($Lang['ExplorS'], $msg, '', $Player['login'], 'report', $i);

	$result = array(
		'Player' => $Player,
		'Colony' => $Colony,
		'Units' => $Units,
		'Planet' => $Planet
	);

	return $result;
}

// -------------------------------------------------------------------
// Event: Corruption
// -------------------------------------------------------------------

function eventcorruption($i, &$Colony, &$Player)
{
	global $db, $prefix, $Lang;

	$msg = $Lang['Event1'] . '<br /><br />';

	$colonistskilled = round($Colony['colonistsfree'] * Rand(1, 40) / 100);
	$scientistskilled = round($Colony['scientistsfree'] * Rand(1, 10) / 100);
	$soldierskilled = round($Colony['soldiersfree'] * Rand(1, 20) / 100);
	$reputationlost = Rand(1, 50) / 100;
	$satisfactionlost = Rand(1, 30) / 10;

	$Colony['colonists'] -= $colonistskilled;
	$Colony['scientists'] -= $scientistskilled;
	$Colony['soldiers'] -= $soldierskilled;
	$Colony['colonistsfree'] -= $colonistskilled;
	$Colony['scientistsfree'] -= $scientistskilled;
	$Colony['soldiersfree'] -= $soldierskilled;
	$Colony['lost'] += $colonistskilled + $scientistskilled + $soldierskilled;
	$Colony['satisfaction'] -= $satisfactionlost;
	$Player['reputation'] -= $reputationlost;

	if ($colonistskilled || $scientistskilled || $soldierskilled) {
		$msg .= '<font class="delete"><b>' . $Lang['ELost'] . '</b></font>:<br /><br />';
		if ($colonistskilled) $msg .= $Lang['Colonists'].': <b>'.$colonistskilled.'</b><br />';
		if ($scientistskilled) $msg .= $Lang['Scientists'].':<b>'.$scientistskilled.'</b></br />';
		if ($soldierskilled) $msg .= $Lang['Soldiers'].':<b>'.$soldierskilled.'</b><br />';
		if ($satisfactionlost > 0.05) $msg .= "<br /><b>${Lang['SatisfactionLost']}</b>: <font class=\"minus\">$satisfactionlost</font><br />";
		$db->query("UPDATE `${prefix}colonies` SET `colonists`='${Colony['colonists']}',`scientists`='${Colony['scientists']}',`soldiers`='${Colony['soldiers']}',`lost`='${Colony['lost']}',`satisfaction`='${Colony['satisfaction']}' WHERE `id`='${Colony['id']}';");
	}
	else $msg .= $Lang['FNK'] . '<br />';

	$msg .= "<br /><b>${Lang['ReputationLost']}</b>: <font class=\"minus\">$reputationlost</font><br />";
	$db->query("UPDATE `${prefix}users` SET `reputation`='${Player['reputation']}' WHERE `id`='${Player['id']}';");

	sendmessage($Lang['EventS1'], $msg, '', $Player['login'], 'report', $i);
}

// -------------------------------------------------------------------
// Event: Starving
// -------------------------------------------------------------------

function eventstarving($i, &$Colony, &$Player)
{
	global $db, $prefix, $Lang;

	$msg = $Lang['Event2'] . '<br /><br />';

	$colonistskilled = round($Colony['colonistsfree'] * Rand(1, 15) / 100);
	$scientistskilled = round($Colony['scientistsfree'] * Rand(1, 25) / 100);
	$soldierskilled = round($Colony['soldiersfree'] * Rand(1, 10) / 100);
	$reputationlost = Rand(1, 30) / 100;
	$satisfactionlost = Rand(1, 50) / 10;

	$Colony['colonists'] -= $colonistskilled;
	$Colony['scientists'] -= $scientistskilled;
	$Colony['soldiers'] -= $soldierskilled;
	$Colony['colonistsfree'] -= $colonistskilled;
	$Colony['scientistsfree'] -= $scientistskilled;
	$Colony['soldiersfree'] -= $soldierskilled;
	$Colony['lost'] += $colonistskilled + $scientistskilled + $soldierskilled;
	$Colony['satisfaction'] -= $satisfactionlost;
	$Player['reputation'] -= $reputationlost;

	if ($colonistskilled || $scientistskilled || $soldierskilled) {
		$msg .= '<font class="delete"><b>' . $Lang['ELost'] . '</b></font>:<br /><br />';
		if ($colonistskilled) $msg .= $Lang['Colonists'].': <b>'.$colonistskilled.'</b><br />';
		if ($scientistskilled) $msg .= $Lang['Scientists'].':<b>'.$scientistskilled.'</b></br />';
		if ($soldierskilled) $msg .= $Lang['Soldiers'].':<b>'.$soldierskilled.'</b><br />';
		if ($satisfactionlost > 0.05) $msg .= "<br /><b>${Lang['SatisfactionLost']}</b>: <font class=\"minus\">$satisfactionlost</font><br />";
		$db->query("UPDATE `${prefix}colonies` SET `colonists`='${Colony['colonists']}',`scientists`='${Colony['scientists']}',`soldiers`='${Colony['soldiers']}',`lost`='${Colony['lost']}',`satisfaction`='${Colony['satisfaction']}' WHERE `id`='${Colony['id']}';");
	}
	else $msg .= $Lang['FNK'] . '<br />';

	$msg .= "<br /><b>${Lang['ReputationLost']}</b>: <font class=\"minus\">$reputationlost</font><br />";
	$db->query("UPDATE `${prefix}users` SET `reputation`='${Player['reputation']}' WHERE `id`='${Player['id']}';");
	sendmessage($Lang['EventS2'], $msg, '', $Player['login'], 'report', $i);
}

// -------------------------------------------------------------------
// Event: Expend
// -------------------------------------------------------------------

function eventexpend($i, &$Colony, &$Player)
{
	global $db, $prefix, $Lang;

	$expend = 0;
		
	$msg = $Lang['Event3_'.rand(1, 3)].'<br /><br />';

	$db->query("SELECT metalsources,siliconsources,uransources,plutoniumsources FROM {$prefix}colonies WHERE id='{$Colony['id']}';");
	$r = $db->fetchrow();

	if ($r['metalsources'] > 2500*$Colony['metalcentertechnology'] && ($m = round(0.0001*rand(0, 500)*$r['metalsources']))) {
		$msg .= $Lang['Metal'].': <b>'.strdiv($m).'</b><br />';
		$Colony['metalsources'] -= $m; $r['metalsources'] -= $m; $expend++;
	}

	if ($Colony['siliconsources'] > 2500*$Colony['metalcentertechnology'] && ($s = round(0.0001*rand(0, 500)*$Colony['siliconsources']))) {
		$msg .= $Lang['Silicon'].': <b>'.strdiv($s).'</b><br />';
		$Colony['siliconsources'] -= $s; $r['siliconsources'] -= $s; $expend++;
	}

	if ($Colony['uransources'] > 750*$Colony['urancentertechnology'] && ($u = round(0.0001*rand(0, 75+$Player['level'])*$Colony['uransources']))) {
		$msg .= $Lang['Uran'].': <b>'.strdiv($u).'</b><br />';
		$Colony['uransources'] -= $u; $r['uransources'] -= $u; $expend++;
	}

	if ($Colony['plutoniumsources'] > 750*$Colony['urancentertechnology'] && ($p = round(0.0001*rand(0, 75+$Player['level'])*$Colony['plutoniumsources']))) {
		$msg .= $Lang['Plutonium'].': <b>'.strdiv($p).'</b><br />';
		$Colony['plutoniumsources'] -= $p; $r['plutoniumsources'] -= $p; $expend++;
	}

	if ($expend) {
		$db->query("UPDATE {$prefix}colonies SET metalsources='{$r['metalsources']}',siliconsources='{$r['siliconsources']}',uransources='{$r['uransources']}',plutoniumsources='{$r['plutoniumsources']}' WHERE id='{$Colony['id']}';");
		sendmessage($Lang['EventS3'], $msg, '', $Player['login'], 'report', $i);
	}
}

// -------------------------------------------------------------------
// Event: Crystals
// -------------------------------------------------------------------

function eventcrystals($i, &$Colony, &$Player)
{
	global $db, $prefix, $Lang;

	if (($expend = round($Colony['crystals']*rand(1, 15)/1000)) > 0) {
		$msg = $Lang['Event4_'.rand(1, 3)].'<br /><br />'.$Lang['Crystals'].': '.strdiv($expend);
		$Colony['crystals'] -= $expend;
	}

	if ($expend) {
		$db->query("UPDATE {$prefix}colonies SET crystals='{$Colony['crystals']}' WHERE id='{$Colony['id']}';");
		sendmessage($Lang['EventS4'], $msg, '', $Player['login'], 'report', $i);
	}
}

// -------------------------------------------------------------------
// Event: Satellites tax
// -------------------------------------------------------------------

function eventsattax($i, &$Colony, &$Player)
{
	global $db, $prefix, $Lang;

	$cost = $Colony['satellite']*1000;
	if ($Player['credits'] < $cost) {
		$cost -= $Player['credits'];
		$Player['credits'] = 0;
		if ($Player['bank'] < $cost) {
			$cost = $Player['bank'];
			$Player['bank'] = 0;
		}
		else $Player['bank'] -= $cost;
	}
	else $Player['credits'] -= $cost;

	if ($cost) {
		$msg = $Lang['Event5_'.rand(1, 3)].'<br /><br />'.$Lang['Credits'].': '.strdiv($cost).' [!]';
		//$db->query("UPDATE {$prefix}users SET credits='{$Player['credits']}',bank='{$Player['bank']}' WHERE login='{$Player['login']}';");
		sendmessage($Lang['EventS5'], $msg, '', $Player['login'], 'report', $i);
	}
}

// -------------------------------------------------------------------
// Rates Rates Rates RRR RRR RRR ! ! !
// -------------------------------------------------------------------

function countrates(&$Colony, $Planet, $Units)
{
	$Colony['energycapacity'] = 10000*$Colony['tron'] + 1000*$Colony['base'] + (1000 + 150 * $Colony['energycentertechnology']) * $Colony['energysilo'];
	$Colony['energyplus'] = 250*$Colony['tron'] + 5*$Colony['base'] + $Planet['wind']*$Colony['windgenerator'] + 60*$Colony['solarbattery'];
	$Colony['energyminus'] = (5+2*$Colony['urancentertechnology'])*$Colony['uransilo'] + (2 + 0.5 * $Colony['foodcentertechnology']) * $Colony['foodsilo'] + ($Colony['spacedepot']*(100+50*$Colony['spacedepot'])/2) + ($Colony['factory'] * (10 + 10 * $Colony['factory']) / 2) + 5 * $Colony['metalextractor'] + 2 * $Colony['foodplanting'] + 1.7 * $Colony['flats'] + 1.2*$Colony['barracks'] + 0.5*$Colony['bunker'] + 10*$Colony['lasertower'] + 50*$Colony['plasmatower'];

	$Colony['siliconcapacity'] = 500*$Colony['tron'];
	$Colony['siliconplus'] = $Colony['tron'];
	$Colony['siliconminus'] = 0;

	$Colony['metalcapacity'] = 1000*$Colony['tron'] + 500*$Colony['base'] + (500+100*$Colony['metalcentertechnology']) * $Colony['metalsilo'];
	$Colony['metalplus'] = 5*$Colony['tron'] + $Colony['base'] + (2.5 * $Colony['metalextractor'] < $Colony['metalsources'] ? 2.5 * $Colony['metalextractor'] : $Colony['metalsources']);
	$Colony['metalminus'] = 0.5 * $Colony['uranmine'];

	$Colony['urancapacity'] = (250+50*$Colony['urancentertechnology'])*$Colony['uransilo'];
	$Colony['uranplus'] = 0;
	$Colony['uranminus'] = 0;

	$Colony['plutoniumcapacity'] = 0;
	$Colony['plutoniumplus'] = 0;
	$Colony['plutoniumminus'] = 0;

	$Colony['deuteriumcapacity'] = 0;
	$Colony['deuteriumplus'] = 0;
	$Colony['deuteriumminus'] = 0;

	$Colony['foodcapacity'] = 500 * $Colony['base'] + 5 * $Colony['flats'] + (300 + 50 * $Colony['foodcentertechnology']) * $Colony['foodsilo'];
	$Colony['foodplus'] = $Colony['base'];
	$Colony['foodminus'] = 0.08 * $Colony['scientistseat'] + 0.10 * $Colony['colonistseat'] + 0.12 * $Colony['soldierseat'];

	if (isset($Units) && $Units) foreach ($Units as $u) foreach (array('energy','silicon','metal','uran','plutonium','deuterium','food') as $res) $Colony[$res.'minus'] -= $u[$res.'ratio'];

	if (!$Colony['uranmineoff'] && ($Colony['energy'] + $Colony['energyplus'] - $Colony['energyminus'] >= 25 * $Colony['uranmine'])) {
		$Colony['uranplus'] += 5 * $Colony['uranmine'] < $Colony['uransources'] ? 5 * $Colony['uranmine'] : $Colony['uransources'];
		$Colony['energyminus'] += 25 * $Colony['uranmine'];
	}

	if (!$Colony['fusionreactoroff'] && ($Colony['uran'] + $Colony['uranplus'] - $Colony['uranminus'] >= 25 * $Colony['fusionreactor'])) {
		$Colony['energyplus'] += 1000 * $Colony['fusionreactor'];
		$Colony['uranminus'] += 25 * $Colony['fusionreactor'];
	}

	if ($Colony['energy'] > 0) $Colony['foodplus'] += 5 * $Colony['foodplanting'];

	foreach (array('energy','silicon','metal','uran','plutonium','deuterium','food') as $res) if ($Colony[$res.'minus'] < 0) $Colony[$res.'minus'] = 0;
}

// -------------------------------------------------------------------
// Main Engine
// -------------------------------------------------------------------

function engine($stardate = 0, $name = '', $steps = null)
{
	global $login, $db, $prefix, $Lang, $language, $Var, $starday, $maximumsteps;

	if (is_null($steps)) $steps = $maximumsteps;
	if (!$stardate) $stardate = stardate();
	if (!$name) $name = $login;

	// $Player

	$Player = readplayer($name);

	$db->query("SELECT `galaxy` FROM `${prefix}space` WHERE `name`='${Player['planet']}';");
	$t = $db->fetchrow();
	$Player['galaxy'] = $t['galaxy'];

	// $Group

	if ($Player['clan']) {
		$db->query("SELECT * FROM `${prefix}groups` WHERE `name`='${Player['clan']}';");
		$Group = $db->fetchrow();
		$Player['privileged'] = ($Player['login'] == $Group['owner']) || ($Player['login'] == $Group['co1']) || ($Player['login'] == $Group['co2']);
	}
	else $Group = '';

	// $Colony

	$db->query("SELECT * FROM `${prefix}colonies` WHERE `owner`='$name' LIMIT 1;");
	if ($t = $db->fetchrow()) {
		$t['defense'] = round($t['base']*(50000 + 5000*$t['militarytechnology']) * (100 - $t['damage']) / 100) + $t['soldiers'] + 15 * $t['bunker'] + 50 * $t['lasertower'] + 100 * $t['plasmatower'];
		if ($Player['planet'] == $t['planet']) $t['defense'] += $Player['level'] * 100;
		if ($Player['clan']) $t['defense'] += $Group['defense'];
		$t['maxattacks'] = 1 + $t['militarytechnology'];
		$Colony = $t;
	}
	else $Colony = '';

	// ...

	list($Exploration, $Buildings, $Productions, $Research, $Attacks) = array(readexploration($name), readbuildings($name), readproductions($name), readresearch($name), readattacks($name));

	// $Equipment

	$Equipment = readequipment($Player);

	if ($Colony) {
		$Planet = readplanet($Colony['planet']);
		$Galaxy = readgalaxy($Planet['galaxy']);
		$Structures = structureslist($Colony, $Planet);
		$Units = unitslist($Colony, $Planet);
		$Technologies = technologieslist($Colony);
		$Incoming = readattacks('', $Colony['name']);
	}
	else {
		$Planet = '';
		$Galaxy = '';
		$Structures = '';
		$Units = '';
		$Technologies = '';
		$Incoming = '';
	}

	if ($Player['language'] != $language) {
		$prevlanguage = $language;
		$language = $Player['language'];
		locale('galaxy', $language);
		locale('units', $language);
		$localechanged = TRUE;
	}
	else $localechanged = FALSE;

	for ($i = $Player['thicks'] + 1; $i <= $stardate; $i++) {
		// BEGIN ( traveling ) //
		if ($Player['destination'] && $i >= $Player['time']) {
			$Player['voyaged'] += $Player['distance'];
			$Player['planet'] = $Player['destination'];
			$Player['destination'] = '';
			$Player['time'] = 0;
			$db->query("UPDATE `${prefix}users` SET `planet`='${Player['planet']}',`destination`='',`time`='0',`voyaged`='${Player['voyaged']}' WHERE `id`='${Player['id']}';");
		}
		// END ( traveling ) //

		$Player['bank'] = round($Player['bank'] * (1 + Rand(0, 5) / 1000 / (35 + $Player['level'])));

		if ($s = sgn($a = $Player['hpmodifier'])) {
			if (abs($Player['hpmodifier']) < $Player['hpgain']) $Player['hpmodifier'] = 0;
			$Player['hpmodifier'] -= $s * $Player['hpgain'];
		}

		if ($s = sgn($a = $Player['mpmodifier'])) {
			if (abs($Player['mpmodifier']) < $Player['mpgain']) $Player['mpmodifier'] = 0;
			$Player['mpmodifier'] -= $s * $Player['mpgain'];
		}

		if ($Player['hp'] < $Player['hpmax']) {
			$Player['hp'] += $Player['hpgain'];
			if ($Player['hp'] > $Player['hpmax']) $Player['hp'] = $Player['hpmax'];
		}

		if ($Player['mp'] < $Player['mpmax']) {
			$Player['mp'] += $Player['mpgain'];
			if ($Player['mp'] > $Player['mpmax']) $Player['mp'] = $Player['mpmax'];
		}

		if ($s = sgn($a = $Player['strengthmodifier'])) {
			if (abs($Player['strengthmodifier']) < $Player['mpgain']) {
				$Player['strength'] -= $s * $Player['strengthmodifier'];
				$Player['strengthmodifier'] = 0;
			}
			else {
				$Player['strengthmodifier'] -= $s * $Player['mpgain'];
				$Player['strength'] -= $s * $Player['mpgain'];
			}
		}

		if ($s = sgn($a = $Player['agilitymodifier'])) {
			if (abs($Player['agilitymodifier']) < $Player['hpgain']) {
				$Player['agility'] -= $s * $Player['agilitymodifier'];
				$Player['agilitymodifier'] = 0;
			}
			else {
				$Player['agilitymodifier'] -= $s * $Player['hpgain'];
				$Player['agility'] -= $s * $Player['hpgain'];
			}
		}
	}

	$Player['thicks'] = $stardate;

	if ($Colony) {
		$Colony['colonistsfree'] = $Colony['colonists'];
		$Colony['scientistsfree'] = $Colony['scientists'];
		$Colony['soldiersfree'] = $Colony['soldiers'];
		$Colony['vesselsfree'] = $Colony['vessel'];

		$Colony['colonistseat'] = $Colony['colonists'];
		$Colony['scientistseat'] = $Colony['scientists'];
		$Colony['soldierseat'] = $Colony['soldiers'];

		if ($Attacks)
			foreach ($Attacks as $a) {
				$Colony['soldierseat'] -= $a['soldiers'];
				$Colony['soldiersfree'] -= $a['soldiers'];
				$Colony['defense'] -= $a['soldiers'];
			}

		if ($Exploration) {
			$Colony['colonistsfree'] -= $Exploration['colonists'];
			$Colony['scientistsfree'] -= $Exploration['scientists'];
			$Colony['soldiersfree'] -= $Exploration['soldiers'];
			$Colony['vesselsfree'] -= $Exploration['vessels'];
			$Colony['colonistseat'] -= $Exploration['colonists'];
			$Colony['scientistseat'] -= $Exploration['scientists'];
			$Colony['soldierseat'] -= $Exploration['soldiers'];
		}

		if ($Productions) $Colony['colonistsfree'] -= count($Productions);
		if ($Buildings) $Colony['colonistsfree'] -= ceil(0.25 * $Colony['colonists']); // count($Buildings);
		if ($Research) $Colony['scientistsfree'] -= ceil(0.5 * $Colony['scientists']);
		if (isset($Units['vessel'])) $Units['vessel']['amount'] = $Colony['vesselsfree'];

		countrates($Colony, $Planet, $Units);

		$chk = '';

		// ===================================================================
		// M A I N   L O O P
		// ===================================================================

		for ($i = $Colony['thicks'] + 1; $i <= $stardate && ($steps--); $i++) {
			// BEGIN ( building ) //
			if ($Buildings && $i >= $Buildings['end']) {
				$Colony[$Buildings['name']] += $Buildings['amount'];
				$Colony['colonistsfree'] += ceil(0.25 * $Colony['colonists']);
				if ($Group) {
					$Group['score'] += $t = round($Buildings['score'] * $Group['tax'] / 100);
					$Player['score'] += $Buildings['score'] - $t;
					$db->query("UPDATE `${prefix}groups` SET `score`=`score`+$t WHERE `id`='${Group['id']}';");
				}
				else $Player['score'] += $Buildings['score'];
				$db->query("UPDATE `${prefix}colonies` SET `${Buildings['name']}` = '${Colony[$Buildings['name']]}' WHERE `owner` = '$name' LIMIT 1");
				buildingfinish($name);
				$Buildings = '';
				$Structures = structureslist($Colony, $Planet);
			}
			// END ( building ) //

			// BEGIN ( research ) //
			if ($Research && $i >= $Research['end']) {
				$Colony[$Research['name']]++;
				$Player['score'] += $Research['score'];
				$db->query("UPDATE {$prefix}colonies SET {$Research['name']}='{$Colony[$Research['name']]}' WHERE id='{$Colony['id']}';");
				$db->query("DELETE FROM {$prefix}researches WHERE login='$name';");
				sendmessage($Lang['ResearchS'], $Lang['Research1'].'<b>'.$Lang['technologies'][$Research['name']]['name'] . '</b>' . $Lang['Research2'], '', $name, 'report');
				$Research = array();
				$Colony['scientistsfree'] += ceil(0.5 * $Colony['scientists']);
				$Technologies = technologieslist($Colony);
			}
			// END ( research ) //

			// BEGIN ( production ) //
			if ($Productions) foreach ($Productions as $k => $p) if ($i >= $p['end']) {
				$Colony[$p['name']] += $p['amount'];
				$Colony['colonistsfree']++;
				if ($Group) {
					$Group['score'] += $t = round($p['score'] * $Group['tax'] / 100);
					$Player['score'] += $p['score'] - $t;
					$db->query("UPDATE {$prefix}groups SET `score`=`score`+$t WHERE id='{$Group['id']}';");
				}
				else $Player['score'] += $p['score'];
				$db->query("UPDATE {$prefix}colonies SET `{$p['name']}`='{$Colony[$p['name']]}' WHERE id='{$Colony['id']}';");
				$db->query("DELETE FROM {$prefix}productions WHERE id='{$p['id']}';");
				unset($Productions[$k]);
				$Units = unitslist($Colony, $Planet);
			}
			// END ( production ) //

			// BEGIN ( explore ) //
			if ($Exploration && $i >= $Exploration['end']) {
				$r = explore($i, $Exploration, $Player, $Colony, $Units, $Planet);
				$Player = $r['Player'];
				$Colony = $r['Colony'];
				$Units = $r['Units'];
				$Planet = $r['Planet'];
				$Exploration = '';
			}
			// END ( explore ) //

			// BEGIN ( events ) //
			if ($Colony['base'] && $Colony['uran'] > $Colony['urancapacity'] && Rand(0, 9999) < 25-15*$Colony['urancentertechnology']) eventcorruption($i, $Colony, $Player);
			if ($Colony['energy'] < $Colony['base'] && $Colony['uran'] && Rand(0, 9999) < 25-15*$Colony['urancentertechnology']) eventcorruption($i, $Colony, $Player);
			if ($Colony['food'] < $Colony['base'] && Rand(0, 9999) < 50) eventstarving($i, $Colony, $Player);
			if (($i % 2) && Rand(0, 9999) < $Player['level']) eventexpend($i, $Colony, $Player);
			if ($Colony['crystals'] > 10000*$Colony['base'] && Rand(0, 9999) < $Player['level'] + $Planet['life']) eventcrystals($i, $Colony, $Player); 
			if ($Colony['satellite'] > 5000*$Colony['satellitestechnology'] && Rand(0, 9999) < 10) eventsattax($i, $Colony, $Player);
			// END ( events ) //

			// BEGIN ( detection ) //
			if ($Incoming) {
				$bb = FALSE;
				foreach ($Incoming as $t)
					if (! $t['status']) {
						$b = FALSE;
						for ($j = 0; $j < $Colony['detector']+$Colony['satellite']; $j++)
							if (Rand(0, 9999) < (1 + 0.1*$Colony['satellite'] + 2*$Colony['advancedscanningtechnology'])) {
								$b = TRUE;
								break;
							}
						if ($b) {
							$db->query("UPDATE `${prefix}attacks` SET `status` = '1' WHERE `id` = '${t['id']}' LIMIT 1;");
							$bb = TRUE;
							$msg = $Lang['Detected1'] . '<br /><br />';
							$msg .= $Lang['Attacker'] . ": <a href=\"whois.php?name=${t['login']}\">${t['login']}</a><br /><br />";
							$msg .= $Lang['Detected2'] . '<br />';
							sendmessage($Lang['DetectedS'] . $t['login'], $msg, '', $name, 'report', $i);
						}
					}
				if ($bb) $Incoming = readattacks('', $Colony['name']);
			}
			// END ( detection ) //

			// BEGIN ( incoming ) //
			if ($Incoming) {
				$b = FALSE;
				foreach($Incoming as $t) {
					if (($t['status'] < 2) && ($i >= $t['end'])) {
						$b = TRUE;
						echolog('Counting incoming battle ' . $Player['login'] . ' of ' . $Colony['name']);
						battle($t, $i, $Player, $Colony);
						if ($Group) $db->query("INSERT INTO `${prefix}clanmessages` (`type`,`time`,`clan`,`from`,`to`) VALUES ('attack','$stardate','${Group['name']}','${t['login']}','${Player['login']}');");
					}
				}
				if ($b) $Incoming = readattacks('', $Colony['name']);
			}
			// END ( incoming ) //

			// BEGIN ( attacks ) //
			do {
				$b = FALSE;
				if ($Attacks) {
					foreach($Attacks as $t) {
						if ($i >= $t['end']) {
							if ($t['status'] < 2) {
								$db->query("SELECT `owner` FROM `${prefix}colonies` WHERE `name` = '${t['target']}' LIMIT 1;");
								if ($o  = $db->fetchrow()) {
									if (! isset($chk[$o['owner']])) {
										echolog('Counting outgoing battle ' . $o['owner']);
										$trash = engine($i, $o['owner']);
										$chk[$o['owner']] = TRUE;
										$b = TRUE;
									}
								}
								else {
									$t['status'] = 5;
									$t['begin'] = $t['end'];
									$db->query("UPDATE `{$prefix}attacks` SET `begin` = '${t['begin']}', `status` = '${t['status']}' WHERE `id`='${t['id']}' LIMIT 1;");
									$b = TRUE;
								}
							}
							else {
								$b = TRUE;

								$Player['credits'] += $t['credits']; if ($Player['credits'] < 0) $Player['credits'] = 0;
								$Player['exp'] += $t['exp']; if ($Player['exp'] < 0) $Player['exp'] = 0;
								$Player['score'] += $t['score']; if ($Player['score'] < 0) $Player['score'] = 0;

								$Colony['metal'] += $t['metal'];
								$Colony['uran'] += $t['uran'];
								$Colony['crystals'] += $t['crystals'];
								$Colony['soldiers'] -= $t['soldierslost'];
								$Colony['soldiersfree'] += $t['soldiers'] - $t['soldierslost'];
								$Colony['bx10'] += $t['bx10'] - $t['bx10lost'];
								$Colony['hawk'] += $t['hawk'] - $t['hawklost'];
								$Colony['crusader'] += $t['crusader'] - $t['crusaderlost'];
								$Colony['warrior'] += $t['warrior'] - $t['warriorlost'];
								$Colony['dragon'] += $t['dragon'] - $t['dragonlost'];
								$Colony['whisper'] += $t['whisper'] - $t['whisperlost'];
								$Colony['nemesis'] += $t['nemesis'] - $t['nemesislost'];
								$Colony['scavenger'] += $t['scavenger'] - $t['scavengerlost'];
								$Colony['carrier'] += $t['carrier'] - $t['carrierlost'];
								$Colony['bee'] += $t['bee'] - $t['beelost'];

								$sql = "UPDATE `${prefix}colonies` SET `bx10`='${Colony['bx10']}',`soldiers`='${Colony['soldiers']}',`hawk`='${Colony['hawk']}',`crusader`='${Colony['crusader']}',";
								$sql .= "`bee`='${Colony['bee']}',";
								$sql .= "`warrior`='${Colony['warrior']}',`dragon`='${Colony['dragon']}',`warrior`='${Colony['warrior']}',`nemesis`='${Colony['nemesis']}',`scavenger`='${Colony['scavenger']}',`carrier`='${Colony['carrier']}' WHERE `id`='${Colony['id']}' AND `owner`='${Player['login']}' LIMIT 1";
								$db->query($sql);
								if (!$db->affectedrows()) echolog($sql);

								$db->query("DELETE FROM `${prefix}attacks` WHERE `id`='${t['id']}' LIMIT 1");

								sendmessage($Lang['AttackR'] . $t['target'], attackerreport($t), '', $Player['login'], 'report', $i);
							}
						}
					}
				}
				if ($b) $Attacks = readattacks($name);
			} while ($b);
			// END ( attacks ) //
			
			$Colony['energy'] -= $Colony['energyminus'];
			$Colony['silicon'] -= $Colony['siliconminus'];
			$Colony['metal'] -= $Colony['metalminus'];
			$Colony['uran'] -= $Colony['uranminus'];
			$Colony['plutonium'] -= $Colony['plutoniumminus'];
			$Colony['deuterium'] -= $Colony['deuteriumminus'];
			$Colony['food'] -= $Colony['foodminus'];

			if ($Colony['energy'] < $Colony['energycapacity']) {
				$Colony['energy'] += $Colony['energyplus'];
				if ($Colony['energy'] > $Colony['energycapacity']) $Colony['energy'] = $Colony['energycapacity'];
			}
			if ($Colony['silicon'] < $Colony['siliconcapacity']) {
				$Colony['silicon'] += $Colony['siliconplus'];
				if ($Colony['silicon'] > $Colony['siliconcapacity']) $Colony['silicon'] = $Colony['siliconcapacity'];
			}
			if ($Colony['metal'] < $Colony['metalcapacity']) {
				$Colony['metal'] += $Colony['metalplus'];
				if ($Colony['metal'] > $Colony['metalcapacity']) $Colony['metal'] = $Colony['metalcapacity'];
			}
			if ($Colony['uran'] < $Colony['urancapacity']) {
				$Colony['uran'] += $Colony['uranplus'];
				if ($Colony['uran'] > $Colony['urancapacity']) $Colony['uran'] = $Colony['urancapacity'];
			}
			if ($Colony['plutonium'] < $Colony['plutoniumcapacity']) {
				$Colony['plutonium'] += $Colony['plutoniumplus'];
				if ($Colony['plutonium'] > $Colony['plutoniumcapacity']) $Colony['plutonium'] = $Colony['plutoniumcapacity'];
			}
			if ($Colony['deuterium'] < $Colony['deuteriumcapacity']) {
				$Colony['deuterium'] += $Colony['deuteriumplus'];
				if ($Colony['deuterium'] > $Colony['deuteriumcapacity']) $Colony['deuterium'] = $Colony['deuteriumcapacity'];
			}
			if ($Colony['food'] < $Colony['foodcapacity']) {
				$Colony['food'] += $Colony['foodplus'];
				if ($Colony['food'] > $Colony['foodcapacity']) $Colony['food'] = $Colony['foodcapacity'];
			}

			if ($Colony['energy'] < 0) $Colony['energy'] = 0;
			if ($Colony['silicon'] < 0) $Colony['silicon'] = 0;
			if ($Colony['metal'] < 0) $Colony['metal'] = 0;
			if ($Colony['uran'] < 0) $Colony['uran'] = 0;
			if ($Colony['plutonium'] < 0) $Colony['plutonium'] = 0;
			if ($Colony['silicon'] < 0) $Colony['silicon'] = 0;
			if ($Colony['food'] < 0) $Colony['food'] = 0;

			countrates($Colony, $Planet, $Units);
		}

		$Colony['thicks'] = $stardate;
		updatecolony($Colony);
	}

	updateplayer($Player);

	if ($Colony) {
		$Builds = buildslist($Colony, $Player);
		$ProductionsAvailable = productionslist($Colony);
		$Colony['workforce'] = 0.1 + $Colony['colonistsfree'] + $Colony['tron'];
		$Colony['scienceforce'] = 0.1 + $Colony['scientistsfree']*$Colony['laboratory'] + 10*$Colony['databank'];
		if ($Units) foreach ($Units as $u) {
			$Colony['workforce'] += $u['amount']*@$u['workforce'];
			$Colony['scienceforce'] += $u['amount']*@$u['scienceforce'];
		}
	}
	else {
		$Builds = '';
		$ProductionsAvailable = '';
	}

	$result = array(
		'Player' => $Player,
		'Group' => $Group,
		'Galaxy' => $Galaxy,
		'Planet' => $Planet,
		'Colony' => $Colony,
		'Exploration' => $Exploration,
		'Buildings' => $Buildings,
		'Builds' => $Builds,
		'Structures' => $Structures,
		'Units' => $Units,
		'Productions' => $Productions,
		'ProductionsAvailable' => $ProductionsAvailable,
		'Research' => $Research,
		'Technologies' => $Technologies,
		'Attacks' => $Attacks,
		'Incoming' => $Incoming,
		'Equipment' => $Equipment,
	);

	if ($localechanged) {
		$language = $prevlanguage;
		locale('galaxy');
		locale('units');
	}

	return $result;
}

// ===================================================================
// ACTIONS
// ===================================================================

function actiondeleteaccount() {
	global $auth, $login, $db, $prefix, $Colony, $secret;
	if (($confirm = getvar('confirm')) == $secret) {
		if ($Colony) {
			$db->query("UPDATE `${prefix}space` SET `abandoned`=`abandoned`+1 WHERE `name`='${Colony['planet']}' LIMIT 1;");
			$db->query("DELETE FROM `${prefix}colonies` WHERE `owner`='$login';");
			$db->query("DELETE FROM `${prefix}researches` WHERE `login`='$login';");
			$db->query("DELETE FROM `${prefix}exploration` WHERE `login`='$login';");
			$db->query("DELETE FROM `${prefix}buildings` WHERE `login`='$login';");
			$db->query("DELETE FROM `${prefix}productions` WHERE `login`='$login';");
			$db->query("DELETE FROM `${prefix}attacks` WHERE `login`='$login';");
			@chat("<font color=\"yellow\">${Colony['owner']}</font>", "<font class=\"capacity\"><i>abandoned <b>${Colony['name']}</b></i></font>...");
		}

		$db->query("DELETE FROM ${prefix}messages WHERE to='$login';");
		$db->query("DELETE FROM ${prefix}users WHERE login='$login' LIMIT 1;");

		$auth = FALSE;
		$Colony = '';
		$Player = '';
	}
}

// -------------------------------------------------------------------
// Destroy building, structure or whatever you like to call it...
// -------------------------------------------------------------------

function actiondestroybuildings()
{
	global $login, $db, $prefix, $errors, $Structures, $Colony, $Cost, $Planet, $Lang, $amount, $name;
	if ((! $amount) || ($Colony[$Structures[$name]['id']] < $amount)) $errors .= $Lang['ErrDestroy1'] . '<br />';
	elseif (isset($Structures[$name])) {
		if  (isset($Structures[$name]['metal'])) $metal = round($Structures[$name]['metal'] * $amount * 0.33); else $metal = 0;
		$Colony['metal'] += $metal;
		if ($Colony['metal'] > $Colony['metalcapacity']) $Colony['metal'] = $Colony['metalcapacity'];
		$Colony[$Structures[$name]['id']] -= $amount;
		$db->query("UPDATE `${prefix}colonies` SET `metal` = '${Colony['metal']}', `" . $Structures[$name]['id'] . "` = '" . $Colony[$Structures[$name]['id']] . "' WHERE `name` = '${Colony['name']}' LIMIT 1");
		$Structures = structureslist($Colony, $Planet);
		$Cost['metal'] = $metal;
	}
}

function actionchangedescription($name='')
{
	global $login, $db, $prefix, $Colony;
	if (!$name) $name = $login;
	$description = escapesql(getvar('description'));
	if ($Colony) {
		$db->query("UPDATE {$prefix}colonies SET description='$description' WHERE owner='$name';");
		$Colony['description'] = $description;
	}
}

function actionchangeavatar($name='')
{
	global $login, $db, $prefix, $Colony;
	if (! $name) $name = $login;
	$url = getvar('url');
	if ($url && strpos('-' . $url, 'http://') != 1) $url = 'http://' . $url;
//		if (strpos('..', $url) >= 0) $url = '';
	if (ereg(";' \"", $url)) $url = '';
	$ext = substr(strrchr($url, '.'), 1);
	if (($ext == 'jpg') || ($ext == 'gif') || ($ext == 'png') || ($ext == 'jpeg') || (! $url)) {
		$db->query("UPDATE `{$prefix}colonies` SET `avatar` = '$url' WHERE `owner` = '$name' LIMIT 1;");
		$Colony['avatar'] = $url;
	}
}

function actionchangeplayeravatar($name = '')
{
	global $login, $db, $prefix, $Player;
	if (! $name) $name = $login;
	$url = getvar('url');
	if ($url && strpos('-' . $url, 'http://') != 1) $url = 'http://' . $url;
	if (ereg(";' \"", $url)) $url = '';
//		if (strpos('..', $url) >= 0) $url = '';
	$ext = substr(strrchr($url, '.'), 1);
	if (($ext == 'jpg') || ($ext == 'gif') || ($ext == 'png') || ($ext == 'jpeg') || (! $url)) {
		$db->query("UPDATE `{$prefix}users` SET `avatar` = '$url' WHERE `login` = '$name' LIMIT 1");
		$Player['avatar'] = $url;
	}
}

// -------------------------------------------------------------------
// Prepare colony attack (check if we're able to do this)
// -------------------------------------------------------------------

function actionprepare()
{
	global $db, $prefix, $distance, $errors, $name, $Player, $Units, $Colony, $Attacks, $Lang;
	if ($Player['level'] < 5) $errors .= $Lang['ErrorLvl2Lo'] . '<br />';
	if (!$owner = colonyexists($name)) $errors .= $Lang['ErrorColonyNotExists'] . '<br />';
	else {
		if ($Player['clan'] && (playergroup($owner) == $Player['clan'])) $errors .= $Lang['ErrorAlly'] . '<br />';
		elseif (playerlevel($owner) < 5) $errors .= $Lang['ErrorProtected'] . '<br />';
		elseif (playerscore($owner) < $Player['score'] / 10) $errors .= $Lang['ErrorScoreDifference'] . '<br />';
		if ($Attacks && count($Attacks) >= $Colony['maxattacks']) $errors .= $Lang['ErrorTooManyAttacks'] . '<br />';
		if (! $Colony['soldiersfree']) $errors .= $Lang['ErrorNeedSoldiers'] . '<br />';
		$b = TRUE;
		if ($Units)
			foreach ($Units as $u)
				if ($u['type'] == 'fighter' || $u['type'] == 'thief') {
					$b = FALSE;
					break;
				}
		if ($b) $errors .= $Lang['ErrorNeedUnits'] . '<br />';
		if ($owner == $Player['login']) $errors = $Lang['RUMad?'] . '<br />';
	}
	if (! $errors) {
		$db->query("SELECT * FROM `${prefix}space` WHERE `name`='${Colony['planet']}' LIMIT 1;");
		$sp = $db->fetchrow();
		$db->query("SELECT * FROM `${prefix}universe` WHERE `name`='${sp['galaxy']}' LIMIT 1;");
		$sg = $db->fetchrow();
		$db->query("SELECT * FROM `${prefix}colonies` WHERE `name`='$name' LIMIT 1;");
		$dp = $db->fetchrow();
		$db->query("SELECT * FROM `${prefix}space` WHERE `name`='${dp['planet']}' LIMIT 1;");
		$dp = $db->fetchrow();
		$db->query("SELECT * FROM `${prefix}universe` WHERE `name`='${dp['galaxy']}' LIMIT 1;");
		$dg = $db->fetchrow();

		$distance = galaxydistance($dg, $sg) + planetdistance($dp, $sp);
	}
	return $owner;
}

// -------------------------------------------------------------------
// Initiate attack
// -------------------------------------------------------------------

function actionattack()
{
	global $db, $prefix, $distance, $errors, $name, $login, $Player, $Group, $Units, $Colony, $Attacks, $Lang, $stardate, $Attackers;
	$owner = actionprepare();

	foreach ($Attackers as $u) $s[] = array('id'=>$u,'amount'=>abs(postvar($u)));

	$soldiers = abs(getvar('soldiers'));
	$strategy = (int)getvar('strategy');

	$a = 0;
	$credits = 0;
	$energy = 0;
	$metal = 0;
	$uran = 0;
	$food = $soldiers;
	$quarters = 0;
	$count = 0;
	$crew = 0;
	$ground = 0;
	$bonus = 0;
	$speed = 1000;
	$hnut = FALSE;
	$sql1 = '';
	$sql2 = '';
	$sql3 = '';

	if ($Player['clan']) $bonus += $Group['attack'];

	foreach ($s as $u)
		if ($u['amount']) {
			if (!((isset($Units[$u['id']]) && ($u['amount'] <= $Units[$u['id']]['amount'])))) $hnut = TRUE;
			else {
				$a += $u['amount'] * @$Units[$u['id']]['attack'];
				$credits += $u['amount'] * 0.1 * @$Units[$u['id']]['credits'];
				$energy += $u['amount'] * 6 * @$Units[$u['id']]['e'];
				$metal += $u['amount'] * 2 * @$Units[$u['id']]['m'];
				$uran += $u['amount'] * 2 * @$Units[$u['id']]['u'];
				$food += $u['amount'] * 2 * @$Units[$u['id']]['f'];

				if ($Units[$u['id']]['type'] != 'robot') {
					$count += $u['amount'];
					if (@$Units[$u['id']]['quarters']) $crew += $u['amount'];
					$quarters += $u['amount']*@$Units[$u['id']]['quarters'];
					if ($speed > $Units[$u['id']]['speed']) $speed = $Units[$u['id']]['speed'];
				}
				else $ground += $u['amount'];

				$sql1 .= ",`${u['id']}`";
				$sql2 .= ",'${u['amount']}'";
				$sql3 .= ",`${u['id']}`='" . ($Units[$u['id']]['amount'] - $u['amount']) . "'";
			}
		}
	if ($hnut) $errors .= $Lang['ErrorHNUT'] . '<br />';
	if ($credits > $Player['credits'] || $energy > $Colony['energy'] || $metal > $Colony['metal'] || $uran > $Colony['uran'] || $food > $Colony['food']) $errors .= $Lang['ErrorNotEnoughResources'].'<br />'.$Lang['NeedAtLeast'].': <b>[E]</b> '.div($energy).', <b>[M]</b> ' . div($metal).', <b>[U]</b> '.div($uran).', <b>[F]</b> '.div($food).', <b>[!]</b> '.div($credits).'!<br />';
	if ($soldiers > $Colony['soldiersfree'] || $soldiers < $sn) $errors .= $Lang['ErrorNeedMoreSoldiers'] . '<br />';
	if ($soldiers + $ground > $quarters) $errors .= $Lang['Error2MGU'].'<br />';
	if ($a < 100) $errors .= $Lang['Error2LU'].'<br />';

	if (!$errors) {
		$time = 6 + round($distance / 6 / $speed);

		$Player['credits'] -= $credits;
		$Colony['energy'] -= $energy;
		$Colony['metal'] -= $metal;
		$Colony['uran'] -= $uran;
		$Colony['food'] -= $food;
		$Colony['soldiersfree'] -= $soldiers;

		foreach ($s as $u) if ($u['amount'] && (isset($Units[$u['id']]) && ($u['amount'] <= $Units[$u['id']]['amount']))) $Colony[$u['id']] -= $u['amount'];

		$db->query("UPDATE {$prefix}users SET credits='{$Player['credits']}' WHERE login='{$Player['login']}';");
		$db->query("UPDATE `${prefix}colonies` SET `energy`=${Colony['energy']},`metal`='${Colony['metal']}',`uran`='${Colony['uran']}',`food`='${Colony['food']}'$sql3 WHERE `owner`='$login' LIMIT 1;");
		$db->query("INSERT INTO `${prefix}attacks` (`login`,`owner`, `target`,`begin`,`time`,`strategy`,`bonus`,`soldiers`$sql1) VALUES ('$login','${Colony['name']}','$name','$stardate','$time','$strategy','$bonus','$soldiers'$sql2);");

		$Attacks = readattacks($login);
	}
}

// -------------------------------------------------------------------
// Cancel attack
// -------------------------------------------------------------------

function actioncancelattack()
{
	global $db, $prefix, $stardate, $login, $Colony, $Attacks;

	if (($id = getvar('id')) && $Attacks) {
		foreach ($Attacks as $a) if (($a['id'] == $id) && ($a['status'] < 2) && !$a['communicationlost']) {
			if (rand(0, 99) < 10) $db->query("UPDATE {$prefix}attacks SET communicationlost=1 WHERE id='$id';");
			else {
				$time = $stardate - $a['begin'];
				$db->query("UPDATE `${prefix}attacks` SET `status`=5,`begin`='$stardate',`time`='$time' WHERE `id`='$id' AND `login`='$login' LIMIT 1;");
				$Attacks = readattacks($login);
			}
		}
	}
}

// -------------------------------------------------------------------
// Initialization and execution stuff... ;-)
// -------------------------------------------------------------------

$stardate = stardate();
//	$stardate = 72580;
$starmonth = 1 + floor(($stardate % 3456) / 288);

$r = engine();

$Player = $r['Player'];
$Group = $r['Group'];
$Equipment = $r['Equipment'];
$Colony = $r['Colony'];
$Structures = $r['Structures'];
$Units = $r['Units'];
$Exploration = $r['Exploration'];
$Buildings = $r['Buildings'];
$Builds = $r['Builds'];
$Productions = $r['Productions'];
$ProductionsAvailable = $r['ProductionsAvailable'];
$Research = $r['Research'];
$Technologies = $r['Technologies'];
$Attacks = $r['Attacks'];
$Incoming = $r['Incoming'];
$Planet = $r['Planet'];
$Galaxy = $r['Galaxy'];

$playernature = playernature($Player['reputation']);
$playerspeed = 0.1 + $Var['units'][$Player['ship']]['speed'];
$planet = $Player['planet'];
if ($Colony && $Colony['academy']) $soldierstraincost = floor(reputationmodifier($Player['reputation']) * 5000 / $Colony['academy']);

switch ($action) {
	case 'build': case 'destroyunits': case 'product': case 'initiate': case 'repair': case 'shipexchange': case 'disable': case 'enable': case 'canceltravel': case 'travel': case 'cancelbuilding': case 'management': case 'cancelresearch': case 'cancelexpedition': case 'cancelproduction': case 'train': case 'scanobject': case 'explore': 
		include("modules/galaxy/actions.php"); eval("action$action();"); break;

	case 'fight':
		include('modules/galaxy/fight.php'); actionfight(FALSE); break;

	case 'buy': case 'sell': case 'hire': case 'heal': case 'deposit': case 'withdraw': case 'banktransfer': case 'teleport': case 'track': case 'gamble': case 'mine': case 'thoria': case 'academy':
		include('modules/galaxy/places.php'); eval("action$action();"); break;

	case 'admit': case 'join': case 'reject': case 'invitate': case 'leave': case 'recultivation': case 'changeclanavatar': case 'foundclan': case 'clandonation': case 'userdonation': case 'clanadmin': case 'addtocouncil': case 'rejectfromcouncil': case 'changeclanowner':
		include('modules/galaxy/clan.php'); eval("action$action();"); break;

	case 'giveitems': case 'dropitem': case 'equip': case 'unequip': case 'use': case 'buyitem': case 'sellitem':
		include('modules/galaxy/items.php'); eval("action$action();"); break;

	case 'changedescription': actionchangedescription(); break;
	case 'changeavatar': actionchangeavatar(); break;
	case 'changeplayeravatar': actionchangeplayeravatar(); break;
	case 'build': actionbuild(); break;
	case 'destroybuildings': actiondestroybuildings(); break;
	case 'destroyunits': actiondestroyunits(); break;

	case 'prepare': actionprepare(); break;
	case 'attack': actionattack(); break;
	case 'cancelattack': actioncancelattack(); break;
	case 'deleteaccount': actiondeleteaccount(); break;
}

define('__MOD_GALAXY__', 1);

}
