-- SQL Database Update {update-0.4.5.sql}
-- Version: 0.4.5

UPDATE galaxy_config SET config_value='0.4.5' WHERE config_key='Version';

ALTER TABLE `galaxy_users` ADD `seed` VARCHAR( 16 ) NOT NULL AFTER `password` ;

ALTER TABLE `galaxy_users` CHANGE `group` `usergroup` VARCHAR( 16 ) NOT NULL;
