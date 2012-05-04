<?php

global $Lang, $Descriptions, $Var;

locale('descriptions');

$Descriptions = array(
	'energy'=>array('picture'=>'resources/energy.jpg','name'=>$Lang['Energy'],'type'=>$Lang['Resources'],'description'=>$Lang['EnergyDescription']),
	'silicon'=>array('picture'=>'resources/silicon.jpg','name'=>$Lang['Silicon'],'type'=>$Lang['Resources'],'description'=>$Lang['SiliconDescription']),
	'metal'=>array('picture'=>'resources/metal.jpg','name'=>$Lang['Metal'],'type'=>$Lang['Resources'],'description'=>$Lang['MetalDescription']),
	'uran'=>array('picture'=>'resources/uran.jpg','name'=>$Lang['Uran'],'type'=>$Lang['Resources'],'description'=>$Lang['UranDescription']),
	'plutonium'=>array('picture'=>'resources/plutonium.jpg','name'=>$Lang['Plutonium'],'type'=>$Lang['Resources'],'description'=>$Lang['PlutoniumDescription']),
	'deuterium'=>array('picture'=>'resources/deuterium.jpg','name'=>$Lang['Deuterium'],'type'=>$Lang['Resources'],'description'=>$Lang['DeuteriumDescription']),
	'food' => array(
		'picture' => 'resources/food.jpg',
		'name' => $Lang['Food'],
		'type' => $Lang['Resources'],
		'description' => $Lang['FoodDescription']
	),
	'crystals' => array(
		'picture' => 'resources/crystals.jpg',
		'name' => $Lang['Crystals'],
		'type' => $Lang['Resources'],
		'description' => $Lang['CrystalsDescription']
	),
	'windgenerator' => array(
		'picture' => 'buildings/windgenerator.jpg',
		'type' => $Lang['Powerplant'],
		'name' => $Lang['structures']['windgenerator']['name'],
		'description' => $Lang['WindGeneratorDescription']
	),
	'foodsilo' => array(
		'picture' => 'buildings/foodsilo.jpg',
		'type' => $Lang['Silo'],
		'name' => $Lang['structures']['foodsilo']['name'],
		'description' => $Lang['FoodSiloDescription']
	),
	'uransilo' => array(
		'picture' => 'buildings/uransilo.jpg',
		'type' => $Lang['Silo'],
		'name' => $Lang['structures']['uransilo']['name'],
		'description' => $Lang['UranSiloDescription']
	),
	'factory' => array(
		'picture' => 'buildings/factory.jpg',
		'type' => $Lang['BasicBuilding'],
		'name' => $Lang['structures']['factory']['name'],
		'description' => $Lang['FactoryDescription']
	),
	'flats' => array(
		'picture' => 'buildings/flats.jpg',
		'type' => $Lang['BasicBuilding'],
		'name' => $Lang['structures']['flats']['name'],
		'description' => $Lang['FlatsDescription']
	),
	'foodplanting' => array(
		'picture' => 'buildings/foodplanting.jpg',
		'type' => $Lang['AdvancedBuilding'],
		'name' => $Lang['structures']['foodplanting']['name'],
		'description' => $Lang['FoodPlantingDescription']
	),
	'barracks' => array(
		'picture' => 'buildings/barracks.jpg',
		'type' => $Lang['BasicBuilding'],
		'name' => $Lang['structures']['barracks']['name'],
		'description' => $Lang['BarracksDescription']
	),
	'academy' => array(
		'picture' => 'buildings/academy.jpg',
		'type' => $Lang['AdvancedBuilding'],
		'name' => $Lang['structures']['academy']['name'],
		'description' => $Lang['AcademyDescription']
	),
	'market' => array(
		'picture' => 'places/market.jpg',
		'type' => $Lang['Place'],
		'name' => $Lang['Market'],
		'description' => $Lang['MarketDescription']
	),
	'mercenary' => array(
		'picture' => 'places/mercenary.jpg',
		'type' => $Lang['Place'],
		'name' => $Lang['Mercenary'],
		'description' => $Lang['MercenaryDescription']
	),
	'arena' => array(
		'picture' => 'places/arena.jpg',
		'type' => $Lang['Place'],
		'name' => $Lang['BattleArena'],
		'description' => $Lang['ArenaDescription']
	),
	'mines' => array(
		'picture' => 'places/mines.jpg',
		'type' => $Lang['Place'],
		'name' => $Lang['TheMines'],
		'description' => $Lang['MinesDescription']
	),
	'clanhall' => array(
		'picture' => 'places/clanhall.jpg',
		'type' => $Lang['Place'],
		'name' => $Lang['ClanHall'],
		'description' => $Lang['ClanHallDescription']
	),
	'itemshop' => array('picture' => 'places/itemshop.jpg', 'type' => $Lang['Place'], 'name' => $Lang['ItemShop'], 'description' => $Lang['ItemShopDescription']),
);

foreach ($Lang['items'] as $key => $array) $Descriptions[$key] = array('picture'=>"items/${key}.jpg",'type'=>$Lang['ItemType[]'][$array['type']],'name'=>$array['name'],'description'=>'<p />' . $array['description']);
foreach ($Lang['units'] as $key => $array) $Descriptions[$key] = array('picture'=>"units/${key}.jpg",'type'=>$Lang['UnitType[]'][$Var['units'][$key]['type']],'name'=>$array['name'],'description'=>'<p />'.(@$array['full'] ? $array['full'] : $array['description']));
