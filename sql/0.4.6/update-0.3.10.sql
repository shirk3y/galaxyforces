-- SQL Database Update {update.sql}
-- Version: 0.3.10

ALTER TABLE `galaxy_space` ADD `moons` INT DEFAULT '0' NOT NULL, ADD `illumination` INT DEFAULT '15' NOT NULL;
ALTER TABLE `galaxy_equipment` CHANGE `type` `type` ENUM( 'guns', 'shields', 'engine', 'weapon', 'weapon2', 'helmet', 'armor', 'quest', 'backpack', 'belt', 'gloves', 'implant', 'artifact', 'special', 'useless', 'item', 'gem' ) DEFAULT NULL;
ALTER TABLE `galaxy_equipment` ADD `parameters` VARCHAR( 32 ) NOT NULL ;
ALTER TABLE `galaxy_equipment` ADD `class` ENUM( '', 'gold', 'silver', 'bronze' ) NOT NULL AFTER `type`;
ALTER TABLE `galaxy_equipment` ADD `req_mp` INT NOT NULL AFTER `req_force` , ADD `req_hp` INT NOT NULL AFTER `req_mp`;
ALTER TABLE `galaxy_users` ADD `ggpublic` TINYINT NOT NULL AFTER `gg` ;
ALTER TABLE `galaxy_space` ADD `moons` INT NOT NULL ;
ALTER TABLE `galaxy_users` CHANGE `online` `online` VARCHAR( 14 );
ALTER TABLE `galaxy_places` CHANGE `parameters` `parameters` VARCHAR( 240 ) NOT NULL ;