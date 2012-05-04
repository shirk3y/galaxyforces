-- SQL Database Update {update-0.3.16.sql}
-- Version: 0.3.16

ALTER TABLE `galaxy_users` ADD `homeworld` VARCHAR( 32 ) NOT NULL AFTER `level` ;

ALTER TABLE `galaxy_markets` ADD `level` INT NOT NULL AFTER `position` ,
ADD `reputation` INT NOT NULL AFTER `level` ;
