-- SQL Database Update {update.sql}
-- Version: 0.3.9
 
ALTER TABLE `galaxy_users` CHANGE `group` `clan` VARCHAR( 32 );
ALTER TABLE `galaxy_clanmessages` CHANGE `group` `clan` VARCHAR( 32 ) NOT NULL;
ALTER TABLE `galaxy_users` ADD `group` VARCHAR( 16 ) NOT NULL AFTER `password` ;
ALTER TABLE `galaxy_colonies` ADD `corthosiumtechnology` TINYINT( 1 ) NOT NULL ;
