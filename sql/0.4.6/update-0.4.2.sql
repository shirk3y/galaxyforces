-- SQL Database Update {update-0.4.2.sql}
-- Version: 0.4.2

ALTER TABLE `galaxy_attacks` CHANGE `whisper` `whisper` INT NOT NULL DEFAULT '0',
CHANGE `whisperlost` `whisperlost` INT NOT NULL DEFAULT '0',
CHANGE `whisperkilled` `whisperkilled` INT NOT NULL DEFAULT '0';

ALTER TABLE `galaxy_attacks` CHANGE `satellitekilled` `satellitekilled` INT NOT NULL DEFAULT '0';
ALTER TABLE `galaxy_attacks` ADD INDEX ( `login` );
ALTER TABLE `galaxy_attacks` ADD INDEX ( `target` ) ;
ALTER TABLE `galaxy_attacks` CHANGE `bx1klled` `bx1killed` INT NOT NULL DEFAULT '0';

ALTER TABLE `galaxy_attacks` ADD `communicationlost` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `status` ;
