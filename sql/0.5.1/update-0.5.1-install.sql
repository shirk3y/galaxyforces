
INSERT INTO `galaxy_config` (`config_key`, `config_value`) VALUES ('Version', '0.4.7') ON DUPLICATE KEY UPDATE `config_value`='0.4.7';

ALTER IGNORE TABLE `galaxy_places` ADD UNIQUE `UI` (`position`, `type`);

ALTER IGNORE TABLE `galaxy_space` DROP INDEX `id`;

ALTER IGNORE TABLE `galaxy_space` DROP INDEX `name`;

ALTER IGNORE TABLE `galaxy_space` ADD UNIQUE `UI` (`name`);

ALTER IGNORE TABLE  `galaxy_space` CHANGE  `terrain`  `terrain` VARCHAR( 50 ) NOT NULL DEFAULT  '50';

CREATE TABLE IF NOT EXISTS `galaxy_ads` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  `expires` int(11) NOT NULL default '0',
  `status` int(6) NOT NULL default '0',
  `lang` varchar(6) NOT NULL default '',
  `class` varchar(16) NOT NULL default '',
  `type` varchar(12) NOT NULL default '',
  `notify` int(11) NOT NULL default '0',
  `replies` varchar(12) NOT NULL default '0',
  `author` varchar(64) NOT NULL default '',
  `title` varchar(35) NOT NULL default '',
  `expired` tinyint(1) NOT NULL default '0',
  `content` mediumtext NOT NULL,
   PRIMARY KEY (`id`)
);

ALTER TABLE `galaxy_users` ADD `race` VARCHAR( 16 ) NOT NULL ;
