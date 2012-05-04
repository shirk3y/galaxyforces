-- SQL Database Update {update.sql}
-- Version: 0.3.11

ALTER TABLE `galaxy_users` CHANGE `login` `login` VARCHAR( 32 ) NOT NULL;
ALTER TABLE `galaxy_buildings` CHANGE `login` `login` VARCHAR( 32 ) NOT NULL;
ALTER TABLE `galaxy_attacks` CHANGE `login` `login` VARCHAR( 32 ) NOT NULL;
ALTER TABLE `galaxy_clanmessages` CHANGE `from` `from` VARCHAR( 32 ) NOT NULL , CHANGE `to` `to` VARCHAR( 32 ) NOT NULL ;
ALTER TABLE `galaxy_colonies` CHANGE `owner` `owner` VARCHAR( 32 ) DEFAULT NULL ;
ALTER TABLE `galaxy_exploration` CHANGE `login` `login` VARCHAR( 32 ) NOT NULL ;
ALTER TABLE `galaxy_messages` CHANGE `from` `from` VARCHAR( 32 ) NOT NULL ,CHANGE `to` `to` VARCHAR( 32 ) NOT NULL ;
ALTER TABLE `galaxy_productions` CHANGE `login` `login` VARCHAR( 32 ) NOT NULL ;
ALTER TABLE `galaxy_researches` CHANGE `login` `login` VARCHAR( 32 ) NOT NULL ;

ALTER TABLE `galaxy_colonies` DROP `varg` ,
DROP `xwing` ,
DROP `bwing` ;

ALTER TABLE `galaxy_colonies` ADD `tron` INT NOT NULL AFTER `base` ;
ALTER TABLE `galaxy_colonies` ADD `spaceshipstechnology` TINYINT NOT NULL ,
ADD `communicationstechnology` TINYINT NOT NULL ,
ADD `atomtechnology` TINYINT NOT NULL ,
ADD `biotechnology` TINYINT NOT NULL ,
ADD `resourcestechnology` TINYINT NOT NULL ,
ADD `cryogenictechnology` TINYINT NOT NULL ,
ADD `weapontechnology` TINYINT NOT NULL ,
ADD `plasmatechnology` TINYINT NOT NULL ,
ADD `moleculartechnology` TINYINT NOT NULL ,
ADD `nanotechnology` TINYINT NOT NULL ,
ADD `databankstechnology` TINYINT NOT NULL ,
ADD `warptechnology` TINYINT NOT NULL ,
ADD `mutationtechnology` TINYINT NOT NULL ,
ADD `procreationtechnology` TINYINT NOT NULL ,
ADD `regenerationtechnology` TINYINT NOT NULL ;

ALTER TABLE `galaxy_colonies` CHANGE `spaceshipstechnology` `spaceshipstechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `communicationstechnology` `communicationstechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `atomtechnology` `atomtechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `biotechnology` `biotechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `resourcestechnology` `resourcestechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `cryogenictechnology` `cryogenictechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `weapontechnology` `weapontechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `plasmatechnology` `plasmatechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `moleculartechnology` `moleculartechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `nanotechnology` `nanotechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `databankstechnology` `databankstechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `warptechnology` `warptechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `mutationtechnology` `mutationtechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `procreationtechnology` `procreationtechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `regenerationtechnology` `regenerationtechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ;

ALTER TABLE `galaxy_colonies` ADD `hyperwavestechnology` TINYINT( 1 ) NOT NULL ,
ADD `dimensionstechnology` TINYINT( 1 ) NOT NULL ,
ADD `uimtechnology` TINYINT( 1 ) NOT NULL ,
ADD `teleportationtechnology` TINYINT( 1 ) NOT NULL ,
ADD `collectivetechnology` TINYINT( 1 ) NOT NULL ,
ADD `crystaltechnology` TINYINT( 1 ) NOT NULL ,
ADD `advancedweapontechnology` TINYINT( 1 ) NOT NULL ;

ALTER TABLE `galaxy_users` ADD `soundsoff` TINYINT NOT NULL AFTER `lastip` ;

ALTER TABLE `galaxy_colonies` ADD `energycenter` TINYINT NOT NULL ,
ADD `metalcenter` TINYINT NOT NULL ,
ADD `urancenter` TINYINT NOT NULL ,
ADD `foodcenter` TINYINT NOT NULL ,
ADD `crystalcenter` TINYINT NOT NULL ;

ALTER TABLE `galaxy_colonies` CHANGE `energycenter` `energycentertechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `metalcenter` `metalcentertechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `urancenter` `urancentertechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `foodcenter` `foodcentertechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
CHANGE `crystalcenter` `crystalcentertechnology` TINYINT( 1 ) DEFAULT '0' NOT NULL;

ALTER TABLE `galaxy_colonies` ADD `advancedscanningtechnology` TINYINT( 1 ) NOT NULL ;

ALTER TABLE `galaxy_colonies` ADD `militarytechnology` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `galaxy_attacks` CHANGE `credits` `credits` VARCHAR( 32 ) NOT NULL , CHANGE `exp` `exp` VARCHAR( 32 ) NOT NULL;
ALTER TABLE `galaxy_attacks` CHANGE `credits` `credits` BIGINT( 20 ) DEFAULT '0' NOT NULL;
ALTER TABLE `galaxy_attacks` ADD `fusionreactor` INT NOT NULL AFTER `solarbattery` ,ADD `bunker` INT NOT NULL AFTER `fusionreactor` ;

ALTER TABLE `galaxy_colonies` ADD `bunker` INT NOT NULL AFTER `depot` ;
ALTER TABLE `galaxy_colonies` ADD `satellitestechnology` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `galaxy_colonies` ADD `defensivetechnology` TINYINT( 1 ) NOT NULL , ADD `offensivetechnology` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `galaxy_colonies` ADD `spacedepot` INT NOT NULL AFTER `academy` ;
ALTER TABLE `galaxy_colonies` ADD `satellite` INT NOT NULL AFTER `detector` ;
ALTER TABLE `galaxy_colonies` ADD `lasertower` INT NOT NULL AFTER `bunker` ,ADD `plasmatower` INT NOT NULL AFTER `lasertower` ;
