-- SQL Database Update {update-0.3.14.sql}
-- Version: 0.3.14

ALTER TABLE `galaxy_chat` CHANGE `message` `message` MEDIUMTEXT NOT NULL;
ALTER TABLE `galaxy_chat` CHANGE `time` `timestamp` VARCHAR( 14 ) NOT NULL;

CREATE TABLE `galaxy_stats` (
`key` VARCHAR( 32 ) NOT NULL ,
`value` VARCHAR( 224 ) NOT NULL ,
PRIMARY KEY ( `key` ) 
);

ALTER TABLE `galaxy_markets` ADD `siliconbuyaverage` FLOAT NOT NULL AFTER `position` ,
ADD `siliconbuy` FLOAT NOT NULL AFTER `siliconbuyaverage` ,
ADD `siliconsellaverage` FLOAT NOT NULL AFTER `siliconbuy` ,
ADD `siliconsell` FLOAT NOT NULL AFTER `siliconsellaverage` ;

ALTER TABLE `galaxy_markets` CHANGE `metalbuy` `metalbuyaverage` FLOAT NOT NULL ,
CHANGE `metalsell` `metalsellaverage` FLOAT NOT NULL ,
CHANGE `uranbuy` `uranbuyaverage` FLOAT NOT NULL ,
CHANGE `uransell` `uransellaverage` FLOAT NOT NULL ,
CHANGE `foodbuy` `foodbuyaverage` FLOAT NOT NULL ,
CHANGE `foodsell` `foodsellaverage` FLOAT NOT NULL ,
CHANGE `crystalsbuy` `crystalsbuyaverage` FLOAT NOT NULL ,
CHANGE `crystalssell` `crystalssellaverage` FLOAT NOT NULL;

ALTER TABLE `galaxy_markets` ADD `metalbuy` FLOAT NOT NULL AFTER `metalbuyaverage` ;
ALTER TABLE `galaxy_markets` ADD `metalsell` FLOAT NOT NULL AFTER `metalsellaverage` ;
ALTER TABLE `galaxy_markets` ADD `uranbuy` FLOAT NOT NULL AFTER `uranbuyaverage` ;
ALTER TABLE `galaxy_markets` ADD `uransell` FLOAT NOT NULL AFTER `uransellaverage` ;
ALTER TABLE `galaxy_markets` ADD `foodbuy` FLOAT NOT NULL AFTER `foodbuyaverage` ;
ALTER TABLE `galaxy_markets` ADD `foodsell` FLOAT NOT NULL AFTER `foodsellaverage` ;
ALTER TABLE `galaxy_markets` ADD `crystalsbuy` FLOAT NOT NULL AFTER `crystalsbuyaverage` ;
ALTER TABLE `galaxy_markets` ADD `crystalssell` FLOAT NOT NULL AFTER `crystalssellaverage` ;

ALTER TABLE `galaxy_markets` ADD `plutoniumsellaverage` FLOAT NOT NULL AFTER `uransell` ,
ADD `plutoniumsell` FLOAT NOT NULL AFTER `plutoniumsellaverage` ,
ADD `plutoniumbuyaverage` FLOAT NOT NULL AFTER `plutoniumsell` ,
ADD `plutoniumbuy` FLOAT NOT NULL AFTER `plutoniumbuyaverage` ,
ADD `deuteriumsellaverage` FLOAT NOT NULL AFTER `plutoniumbuy` ,
ADD `deuteriumsell` FLOAT NOT NULL AFTER `deuteriumsellaverage` ,
ADD `deuteriumbuyaverage` FLOAT NOT NULL AFTER `deuteriumsell` ,
ADD `deuteriumbuy` FLOAT NOT NULL AFTER `deuteriumbuyaverage` ;

ALTER TABLE `galaxy_markets` DROP `id`;
ALTER TABLE `galaxy_markets` ADD PRIMARY KEY ( `position` );

ALTER TABLE `galaxy_news` CHANGE `timestamp` `timestamp` VARCHAR( 14 ) NOT NULL;

CREATE TABLE `galaxy_descriptions` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 32 ) NOT NULL ,
`locale` VARCHAR( 16 ) NOT NULL ,
`description` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) 
);

INSERT INTO `galaxy_markets` ( `position` , `siliconbuyaverage` , `siliconbuy` , `siliconsellaverage` , `siliconsell` , `metalbuyaverage` , `metalbuy` , `metalsellaverage` , `metalsell` , `uranbuyaverage` , `uranbuy` , `uransellaverage` , `uransell` , `plutoniumsellaverage` , `plutoniumsell` , `plutoniumbuyaverage` , `plutoniumbuy` , `deuteriumsellaverage` , `deuteriumsell` , `deuteriumbuyaverage` , `deuteriumbuy` , `foodbuyaverage` , `foodbuy` , `foodsellaverage` , `foodsell` , `crystalsbuyaverage` , `crystalsbuy` , `crystalssellaverage` , `crystalssell` ) 
VALUES (
'phantasmagoria', '0', '0', '0', '0', '0', '0', '0', '0', '220', '0', '45', '0', '100', '0', '400', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'
);

ALTER TABLE `galaxy_markets` CHANGE `plutoniumsellaverage` `plutoniumbuyaverage` FLOAT NOT NULL ,
CHANGE `plutoniumsell` `plutoniumbuy` FLOAT NOT NULL ,
CHANGE `plutoniumbuyaverage` `plutoniumsellaverage` FLOAT NOT NULL ,
CHANGE `plutoniumbuy` `plutoniumsell` FLOAT NOT NULL ,
CHANGE `deuteriumsellaverage` `deuteriumbuyaverage` FLOAT NOT NULL ,
CHANGE `deuteriumsell` `deuteriumbuy` FLOAT NOT NULL ,
CHANGE `deuteriumbuyaverage` `deuteriumsellaverage` FLOAT NOT NULL ,
CHANGE `deuteriumbuy` `deuteriumsell` FLOAT NOT NULL;

UPDATE `galaxy_markets` SET `plutoniumbuyaverage` = '400',
`plutoniumsellaverage` = '100' WHERE `position` = 'phantasmagoria' LIMIT 1 ;
