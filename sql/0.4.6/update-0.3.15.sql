-- SQL Database Update {update-0.3.15.sql}
-- Version: 0.3.15

ALTER TABLE `galaxy_markets` ADD `energybuyaverage` FLOAT NOT NULL AFTER `position` ,ADD `energybuy` FLOAT NOT NULL AFTER `energybuyaverage` ,ADD `energysellaverage` FLOAT NOT NULL AFTER `energybuy` ,ADD `energysell` FLOAT NOT NULL AFTER `energysellaverage` ;
ALTER TABLE `galaxy_markets` ADD `level` INT NOT NULL AFTER `position` ,ADD `reputation` INT NOT NULL AFTER `level` ;

ALTER TABLE `galaxy_chat` CHANGE `from` `author` VARCHAR(64) NOT NULL;

ALTER TABLE `galaxy_places` ADD `level` INT NOT NULL , ADD `reputation` INT NOT NULL ;

ALTER TABLE `galaxy_colonies` CHANGE `trontechnology` `trontechnology` TINYINT NOT NULL;
ALTER TABLE `galaxy_colonies` ADD `ax3` INT NOT NULL AFTER `lost` , ADD `ax6` INT NOT NULL AFTER `ax3` ;
ALTER TABLE `galaxy_colonies` ADD `cx7` INT NOT NULL AFTER `bx10` , ADD `cx13` INT NOT NULL AFTER `cx7` , ADD `walker` INT NOT NULL AFTER `cx13` ;
ALTER TABLE `galaxy_colonies` ADD `infrastructure` INT DEFAULT '50' NOT NULL AFTER `necro` ,ADD `science` INT DEFAULT '25' NOT NULL AFTER `infrastructure` ,ADD `military` INT DEFAULT '25' NOT NULL AFTER `science` ;

ALTER TABLE `galaxy_messages` ADD INDEX ( `to` );
