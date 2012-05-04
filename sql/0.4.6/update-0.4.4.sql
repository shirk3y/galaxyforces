-- SQL Database Update {update-0.4.4.sql}
-- Version: 0.4.4

UPDATE galaxy_config SET config_value='0.4.4' WHERE config_key='Version';

ALTER TABLE `galaxy_attacks` ADD `walker` INT DEFAULT '0' NOT NULL AFTER `bx10killed` ,
ADD `walkerlost` INT DEFAULT '0' NOT NULL AFTER `walker` ,
ADD `walkerkilled` INT DEFAULT '0' NOT NULL AFTER `walkerlost` ;

ALTER TABLE `galaxy_attacks` ADD `valkyrie` INT DEFAULT '0' NOT NULL AFTER `hawkkilled` ,
ADD `valkyrielost` INT DEFAULT '0' NOT NULL AFTER `valkyrie` ,
ADD `valkyriekilled` INT DEFAULT '0' NOT NULL AFTER `valkyrielost` ;

