-- SQL Database Update {update-0.4.3.sql}
-- Version: 0.4.3

UPDATE `galaxy_config` SET `config_value` = '0.4.3' WHERE CONVERT( `config_key` USING utf8 ) = 'Version' LIMIT 1 ;

ALTER TABLE `galaxy_clanmessages` ADD INDEX ( `clan` ) ;

ALTER TABLE `galaxy_colonies` CHANGE `bxtechnology` `bxtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `hawktechnology` `hawktechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `crusadertechnology` `crusadertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `vesseltechnology` `vesseltechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `scavengertechnology` `scavengertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `warriortechnology` `warriortechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `dragontechnology` `dragontechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `whispertechnology` `whispertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `nemesistechnology` `nemesistechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `carriertechnology` `carriertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `detectortechnology` `detectortechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `corthosiumtechnology` `corthosiumtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `spaceshipstechnology` `spaceshipstechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `tacticstechnology` `tacticstechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `communicationstechnology` `communicationstechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `atomtechnology` `atomtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `biotechnology` `biotechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `resourcestechnology` `resourcestechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `cryogenictechnology` `cryogenictechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `weapontechnology` `weapontechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `plasmatechnology` `plasmatechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `moleculartechnology` `moleculartechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `nanotechnology` `nanotechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `databankstechnology` `databankstechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `warptechnology` `warptechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `mutationtechnology` `mutationtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `procreationtechnology` `procreationtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `regenerationtechnology` `regenerationtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `hyperwavestechnology` `hyperwavestechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `dimensionstechnology` `dimensionstechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `uimtechnology` `uimtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `teleportationtechnology` `teleportationtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `collectivetechnology` `collectivetechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `crystaltechnology` `crystaltechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `advancedweapontechnology` `advancedweapontechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `energycentertechnology` `energycentertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `metalcentertechnology` `metalcentertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `urancentertechnology` `urancentertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `foodcentertechnology` `foodcentertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `crystalcentertechnology` `crystalcentertechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `advancedscanningtechnology` `advancedscanningtechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `militarytechnology` `militarytechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `satellitestechnology` `satellitestechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `defensivetechnology` `defensivetechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `offensivetechnology` `offensivetechnology` TINYINT NOT NULL DEFAULT '0',
CHANGE `trontechnology` `trontechnology` TINYINT NOT NULL DEFAULT '0' ;

ALTER TABLE `galaxy_colonies` ADD `managementtechnology` TINYINT DEFAULT '0' NOT NULL AFTER `trontechnology` ;

