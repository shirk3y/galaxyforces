<?php

locale('items');

// -------------------------------------------------------------------
// Drop item
// -------------------------------------------------------------------

function actiondropitem()
{
	global $Equipment;
	if (@$Equipment[$id = getvar('id')] && !$Equipment[$id]['active']) delequipment($id);
}

// -------------------------------------------------------------------
// Give items
// -------------------------------------------------------------------

function actiongiveitems()
{
	global $login, $db, $prefix, $Player, $Equipment, $amount, $name;

	if (($item = @$Equipment[$id = getvar('id')]) && !$Equipment[$id]['active'] && $name) {
		if ($db->query("SELECT planet FROM {$prefix}users WHERE login='$name';") && $row = $db->fetchrow()) $planet = $row['planet']; else $planet = '';
		if ($item['count'] && $amount > $item['count']) $amount = $item['count']; elseif (!$amount) $amount = 1;
		if ($amount && $Player['planet'] == $planet) {
			if ($item['count']) $item['count'] = $amount;
			addequipment($item, $name);
			delequipment($id, $amount);
		}
	}
}

// -------------------------------------------------------------------
// Buy in the item shop
// -------------------------------------------------------------------

function actionbuyitem()
{
	global $login, $db, $prefix, $Player, $Equipment, $Lang, $amount, $places, $place, $name, $errors, $result;
	if ((checkplace('itemshop') || checkplace('gemshop')) && elementexists(explode(',', $place['parameters']), $name)) {
		$db->query("SELECT * FROM `${prefix}items` WHERE `name`='$name';");
		$item = $db->fetchrow();
		$ratio = $place['extra']; if ($ratio < 1) $ratio = 1;
		$mod = reputationmodifier($Player['reputation']);
		$price = round($mod * $ratio * $item['price']);
		if ($item['count']) {
			if (!$amount) $amount = 1;
			$price *= $amount;
			$item['count'] *= $amount;
		}
		if ($Player['credits'] >= $price) {
			$Player['credits'] -= $price;
			$db->query("UPDATE `${prefix}users` SET `credits`='${Player['credits']}' WHERE `id`='${Player['id']}';");
			addequipment($item);
			$result .= $Lang['ItemShopBought'] . ': <font class="capacity"><b>' . $Lang['items'][$item['name']]['name'] . "</b></font><br /><br />" . ($item['count'] ? "<b>${Lang['Count']}</b>: <font class=\"result\">${item['count']}</font><br /><br />" : '') . "<b>${Lang['Credits']}: <font class=\"minus\">" . div($price) . '</font> [!]</b><br />';
		}
		else $errors .= $Lang['ErrorNotEnoughCredits'] . '<br />';
	}
}

// -------------------------------------------------------------------
// Sell in the item shop
// -------------------------------------------------------------------

function actionsellitem() {
	global $login, $db, $prefix, $Player, $Equipment, $amount, $place;
	if (checkplace('itemshop') && @$Equipment[$id = getvar('id')] && ! $Equipment[$id]['active']) {
		$mod = reputationmodifier($Player['reputation']);
		if (($ratio = $place['extra']) < 1) $ratio = 1;
		$ratio /= 5;
		$price = round($Equipment[$id]['price'] * $ratio / $mod);
		if ($amount = delequipment($id, $amount)) {
			$Player['credits'] += $price * $amount;
			$db->query("UPDATE ${prefix}users SET credits='${Player['credits']}' WHERE id='${Player['id']}';");
		}
	}
}

// -------------------------------------------------------------------
// Use
// -------------------------------------------------------------------

function actionuse() {
	global $login, $db, $prefix, $errors, $result, $stardate, $Player, $Lang, $result, $id;
	if ($id = (int)getvar('id')) {
		$db->query("SELECT * FROM {$prefix}equipment WHERE id='$id' AND owner='$login' AND active=0 LIMIT 1;");
		$item = $db->fetchrow();
	}
	if (@$item) {
		if ($item['req_level'] > $Player['level'] || $item['req_strength'] > $Player['strength'] || $item['req_agility'] > $Player['agility'] || $item['req_psi'] > $Player['psi'] || $item['req_force'] > $Player['force'] || $item['req_mp'] > $Player['mp'] || $item['req_hp'] > $Player['hp'] || $item['req_intellect'] > $Player['intellect'] || $item['req_knowledge'] > $Player['knowledge'] || $item['req_pocketstealing'] > $Player['pocketstealing'] || $item['req_hacking'] > $Player['hacking'] || $item['req_alcoholism'] > $Player['alcoholism']) $errors .= $Lang['NEPR'].'<br />';
		else {
			$mod = -$Player['alcoholism'];

			if (($r = Rand(0, 99)+$mod) < $item['use'] || !$r) $result .= '<font class="error">'.$Lang['ErrorItemUse']."($r : {$item['use']} : $mod)!</font><br />";
			else switch ($item['name']) {

				case 'beer': case 'darkbeer': case 'lightbeer': case 'vodka': case 'malibu': case 'czar': case 'gin': case'whisky': case 'redwine': case 'cheapwine': case 'whitewine': case 'grandredwine': case 'grandwhitewine': 
					if ($Player['hp'] < 0.3*$Player['hpmax']) $errors .= $Lang['Drink2Weak'].'!<br />';
					elseif (Rand(0, 99) < $item['parameters']) {
						$Player['hpmodifier'] = -round(Rand(1, $item['parameters'])*$Player['hpmax']/100);
						$Player['hp'] = 0;
						$result .= $Lang['Drunk'].' ('.$Lang['HPmod:'].$Player['hpmodifier'].')<br />';
						if (Rand(0,99) < $item['parameters']) {
							$Player['alcoholism'] += $item['parameters'] / 50;
							$result .= $Lang['GainedAlcoholism'].'!<br />';
						}
						$db->query("UPDATE {$prefix}users SET hp='{$Player['hp']}',hpmodifier='{$Player['hpmodifier']}',alcoholism='{$Player['alcoholism']}' WHERE login='{$Player['login']}';");
					}
					else {	
						$Player['strengthmodifier'] += round($Player['strength'] * Rand(1, $item['parameters']) / 100);
						$Player['agilitymodifier'] -=  round($Player['agility'] * Rand(1, $item['parameters']) / 100);
						$result .= $Lang['DrinkDrink'].' ('.$Lang['Smod:'].$Player['strengthmodifier'].')<br />';
						$db->query("UPDATE {$prefix}users SET strengthmodifier='{$Player['strengthmodifier']}',agilitymodifier='{$Player['agilitymodifier']}' WHERE login='{$Player['login']}';");
					}
					break;

				default: $errors .= $Lang['NotAvailable'].'<br />';
			}			
			if (!$errors) {
				delequipment($item['id']);
				$result .= '<br /><font class="minus"><b>'.$Lang['ItemLost'].'</b>:</font> <a class="item" href="description.php?type=items&subject='.$item['name'].'">'.$Lang['items'][$item['name']]['name'].'</a><br />';
			}
		}
	}
}

// -------------------------------------------------------------------
// Equip
// -------------------------------------------------------------------

function actionequip()
{
	global $db, $prefix, $Player, $Equipment, $errors, $Lang;

	if (($id = (int)getvar('id')) && isset($Equipment[$id]) && ! $Equipment[$id]['active']) {
		switch ($Equipment[$id]['type']) {
			case 'guns':
			case 'shields':
			case 'engine':
				// SHIP //
				break;

			case 'belt':
			case 'helmet':
			case 'armor':
			case 'belt':
			case 'gloves':
			case 'implant':
			case 'artifact':
				foreach ($Equipment as $e) {
					if ($e['active'] && ($e['type'] == $Equipment[$id]['type'])) {
						$errors .= $Lang['ErrorCantEquip'] . '<br />';
						break;
					}
				}
				break;

			case 'weapon':
			case 'weapon2':
			$weapon = '';
			$weapon2 = '';
				foreach ($Equipment as $e) {
					if ($e['active'])
						switch ($e['type']) {
							case 'weapon': $weapon = $e['id']; $weapon2 = 0; break;
							case 'weapon2': if ($weapon) $weapon2 = $e['id']; else $weapon = $e['id']; break;
						}
				}
				if (($Equipment[$id]['type'] == 'weapon2') && ($weapon2 || $weapon && ($Equipment[$weapon]['type'] == 'weapon')) || ($Equipment[$id]['type'] == 'weapon') && $weapon) $errors .= $Lang['ErrorCantEquip'] . '<br />';
				break;

			default:
				$errors .= $Lang['ErrorCantEquip'] . '<br />';
		}

		if (! $errors) if ($Player['mp'] < $Equipment[$id]['req_mp'] || $Player['hp'] < $Equipment[$id]['req_hp'] || $Player['level'] < $Equipment[$id]['req_level'] || $Player['strength'] < $Equipment[$id]['req_strength'] || $Player['agility'] < $Equipment[$id]['req_agility'] || $Player['force'] < $Equipment[$id]['req_force'] || $Player['psi'] < $Equipment[$id]['req_psi'] || $Player['intellect'] < $Equipment[$id]['req_intellect'] || $Player['knowledge'] < $Equipment[$id]['req_knowledge'] || $Player['pocketstealing'] < $Equipment[$id]['req_pocketstealing'] || $Player['hacking'] < $Equipment[$id]['req_hacking'] || $Player['alcoholism'] < $Equipment[$id]['req_alcoholism']) $errors .= $Lang['NEPR'] . '<br />';
		if (! $errors) {
			$db->query("UPDATE `${prefix}equipment` SET `active`='1' WHERE `id`='${id}' AND `owner`='${Player['login']}';");
			$Equipment = readequipment($Player);
		}
	}
}

// -------------------------------------------------------------------
// Unequip
// -------------------------------------------------------------------

function actionunequip()
{
	global $db, $prefix, $Player, $Equipment;
	if (($id = (int)getvar('id')) && $Equipment[$id]['active']) {
		$db->query("UPDATE `${prefix}equipment` SET `active`='0' WHERE `id`='${id}' AND `owner`='${Player['login']}';");
		$Equipment = readequipment($Player);
	}
}
