-- SQL Database Update {update-0.4.6.sql}
-- Version: 0.4.6

UPDATE galaxy_config SET config_value='0.4.6' WHERE config_key='Version';

ALTER TABLE `galaxy_colonies` ADD `clones` INT NOT NULL AFTER `soldiers` ,ADD `drones` INT NOT NULL AFTER `clones` ,ADD `souls` INT NOT NULL AFTER `drones` ;
ALTER TABLE `galaxy_colonies` ADD `organics` FLOAT NOT NULL AFTER `plutonium` ;

ALTER TABLE `galaxy_users` ADD `antispam` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `soundsoff` ;
