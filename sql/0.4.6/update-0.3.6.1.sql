-- SQL Database Update {update.sql} 
-- Version: 0.3.6-1

UPDATE `items` SET `level` = '3' WHERE `id` = '1' LIMIT 1 ;
UPDATE `items` SET `price` = '2500' WHERE `id` = '1' LIMIT 1 ;
UPDATE `items` SET `level` = '2' WHERE `id` = '11' LIMIT 1 ;
UPDATE `items` SET `level` = '5',`price` = '10000' WHERE `id` = '3' LIMIT 1 ;
UPDATE `items` SET `price` = '25000', `req_level` = '5', `req_strength` = '2' WHERE `id` = '16' LIMIT 1;

ALTER TABLE `items` ADD `hp` INT NOT NULL , ADD `mp` INT NOT NULL ;
ALTER TABLE `equipment` ADD `hp` INT NOT NULL , ADD `mp` INT NOT NULL ;
ALTER TABLE `items` CHANGE `type` `type` ENUM( 'guns', 'shields', 'engine', 'weapon', 'weapon2', 'helmet', 'armor', 'quest', 'backpack', 'belt', 'gloves', 'implant', 'artifact', 'special', 'useless', 'item' ) DEFAULT NULL;
ALTER TABLE `equipment` CHANGE `type` `type` ENUM( 'guns', 'shields', 'engine', 'weapon', 'weapon2', 'helmet', 'armor', 'quest', 'backpack', 'belt', 'gloves', 'implant', 'artifact', 'special', 'useless', 'item' ) DEFAULT NULL;

INSERT INTO `items` ( `id` , `name` , `type` , `class` , `count` , `level` , `levelmax` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` )
VALUES (
'', 'medpack', 'item', '', '1', '0', '0', '3000', '0', '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '8', '0'
);

ALTER TABLE `items` ORDER BY `id`;



ALTER TABLE `access` RENAME `galaxy_access`;
ALTER TABLE `buildings` RENAME `galaxy_buildings`;
ALTER TABLE `chat` RENAME `galaxy_chat`;
ALTER TABLE `clanmessages` RENAME `galaxy_clanmessages`;
ALTER TABLE `colonies` RENAME `galaxy_colonies`;
ALTER TABLE `equipment` RENAME `galaxy_equipment`;
ALTER TABLE `exploration` RENAME `galaxy_exploration`;
ALTER TABLE `groups` RENAME `galaxy_groups`;
ALTER TABLE `items` RENAME `galaxy_items`;
ALTER TABLE `markets` RENAME `galaxy_markets`;
ALTER TABLE `messages` RENAME `galaxy_messages`;
ALTER TABLE `places` RENAME `galaxy_places`;
ALTER TABLE `planets` RENAME `galaxy_planets`;
ALTER TABLE `productions` RENAME `galaxy_productions`;
ALTER TABLE `researches` RENAME `galaxy_researches`;
ALTER TABLE `transfers` RENAME `galaxy_transfers`;
ALTER TABLE `universe` RENAME `galaxy_universe`;
ALTER TABLE `users` RENAME `galaxy_users`;
ALTER TABLE `attacks` RENAME `galaxy_attacks`;

ALTER TABLE `galaxy_access` COMMENT = 'galaxy';
ALTER TABLE `galaxy_buildings` COMMENT = 'galaxy';
ALTER TABLE `galaxy_chat` COMMENT = 'galaxy';
ALTER TABLE `galaxy_clanmessages` COMMENT = 'galaxy';
ALTER TABLE `galaxy_colonies` COMMENT = 'galaxy';
ALTER TABLE `galaxy_equipment` COMMENT = 'galaxy';
ALTER TABLE `galaxy_exploration` COMMENT = 'galaxy';
ALTER TABLE `galaxy_groups` COMMENT = 'galaxy';
ALTER TABLE `galaxy_items` COMMENT = 'galaxy';
ALTER TABLE `galaxy_markets` COMMENT = 'galaxy';
ALTER TABLE `galaxy_messages` COMMENT = 'galaxy';
ALTER TABLE `galaxy_places` COMMENT = 'galaxy_places';
ALTER TABLE `galaxy_planets` COMMENT = 'galaxy';
ALTER TABLE `galaxy_productions` COMMENT = 'galaxy';
ALTER TABLE `galaxy_researches` COMMENT = 'galaxy';
ALTER TABLE `galaxy_transfers` COMMENT = 'galaxy';
ALTER TABLE `galaxy_universe` COMMENT = 'galaxy';
ALTER TABLE `galaxy_users` COMMENT = 'system';
ALTER TABLE `galaxy_attacks` COMMENT = 'galaxy';

CREATE TABLE `galaxy_news` (
`id` INT NOT NULL AUTO_INCREMENT ,
`timestamp` TIMESTAMP NOT NULL ,
`login` VARCHAR( 32 ) NOT NULL ,
`message` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
) COMMENT = 'news';

ALTER TABLE `galaxy_news` CHANGE `login` `from` VARCHAR( 32 ) NOT NULL ;

ALTER TABLE `galaxy_news` ADD `locale` VARCHAR( 16 ) NOT NULL AFTER `from` ;


CREATE TABLE `galaxy_tales` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` timestamp(14) NOT NULL,
  `from` varchar(32) NOT NULL default '',
  `locale` varchar(16) NOT NULL default '',
  `subject` varchar(80) NOT NULL default '',
  `message` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='galaxy';

ALTER TABLE `galaxy_tales` ADD `level` INT NOT NULL AFTER `locale` ;

INSERT INTO `galaxy_news` VALUES (1, '20050116024946', 'zoltarx', 'en', 'Testing <b>news</b> section... seems to me workin'' ;-)<p />This would probably be the best global messaging system related to the game itself.<p />Finnaly tried to bring it out as an <i>OpenSource</i> project. And of course FREE, think it''s due to all game users which support this project with lots of good ideas (preferably)...<p />Students'' time is coming soon (egzams) so it will be a little break in working around code... Be patient.<p />Game status changed to development stage. As long as it will be <i>unstable</i> you may be affected by some bugs - HUMAN ERRORS, eh...<p /><b>Grtx</b> to all mentioned in <a href="CHANGELOG">CHANGELOG</a>.');
INSERT INTO `galaxy_news` VALUES (2, '20050116025616', 'zoltarx', 'pl', 'Sprawdzenie sekcji <b>nowości</b>... wygląda na to, że działa ;-)<p />To prawdopodobnie będzie najlepszy system wiadomości związanych z samą grą. W końcu próba wystawienia na wierzch jako projekt łopensorsowy. I oczywiście WOLNY, myślę, że dzięki wszystkim użytkownikom którzy wspierają ten projekt mnóstwem dobrych pomysłów (przeważnie)...<p />Sesja nadchodzi więc będzie mała przerwa w pracy nad kodem...<p />Status gry zmieniony na wersję rozwojową. Tak długo jak pozostanie <i>niestabilną</i> możesz doświadczyć pewnych niedogodności związanych z wylęgającymi się błędami w kodzie...<p /><b>Pozdrv</b> dla wszystkich wymienionych w <a href="CHANGELOG">CHANGELOGu</a>.');

INSERT INTO `galaxy_tales` VALUES (1, '20050117155931', 'zoltarx', 'en', 10, 'Maya War part I', 'It is well known fact that one of the older conflicts between Ami and Tron has rather small influence on humanoid races. If not metioned that it helped a little Human technology to grown again of course. Maya War was the first real sign that Tron armies are not so significant. One of the biggest galaxies Maya became unreachable for them.<p>During that times Tron discovered very dangerous <i>starship</i> made in the Ami technology. First stolen <a href="description.php?type=unit&name=bee">Bee</a> units shown Human technology has to be modified.<p>Maya War was the first open army conflict which Tron lost almost all its power. Unfortunately most of the information about that conflict is incomplete. The duration of Maya War is said to be about 2k star-years.');
INSERT INTO `galaxy_tales` VALUES (2, '20050117155931', 'zoltarx', 'pl', 10, 'Maya War część I', 'Jest bardzo dobrze znanym fakt że jeden z najsatrszych konfliktów pomiędzy Ami i Tronem miał niewielki wpływ na rasy humanoidalne. Jeśli oczywiście nie wspomnieć o tym, że pomogło to nieco powstać na nowo technologii ludzi. Wojna w galaktyce Maya stała się pierwszym prawdziwym znakiem, że armie Tronu nie są aż tak znaczące. Jedna z największych galaktek stała się dla nich nieosiągalna.<p>Podczas tamtych czasów Tron odkrył straszną <i>jednostkę</i> zbudowaną w technologii Ami. Pierwsze ukradzione jednostki <a href="description.php?type=unit&name=bee">Bee</a> ukazały potrzebę modyfikacji technologii ludzkiej.<p>Maya War była pierwszym otwartym zbrojnym konfliktem podczas którego Tron stracił prawie całą swą moc. Niestety większość informacji na temat tego konfliktu jest niekompletna. Czas trwania wojny określa się na ok. 2k lat gwiezdnych.');
        
ALTER TABLE `galaxy_planets` ADD `life` INT DEFAULT '30' NOT NULL ,
ADD `terrain` INT DEFAULT '50' NOT NULL ;

ALTER TABLE `galaxy_planets` CHANGE `exploration` `explored` FLOAT DEFAULT '0' NOT NULL;

ALTER TABLE `galaxy_items`
  DROP `level`,
  DROP `levelmax`;

ALTER TABLE `galaxy_planets` ADD `gravity` FLOAT DEFAULT '2.5' NOT NULL ;

ALTER TABLE `galaxy_planets` ADD `system` VARCHAR( 32 ) NOT NULL AFTER `galaxy` ;

UPDATE `galaxy_planets` SET `system` = 'M-14' WHERE `id` = '3' LIMIT 1 ;
UPDATE `galaxy_planets` SET `system` = 'M-14', `y` = '1', `gravity` = '5' WHERE `id` = '1' LIMIT 1 ;

UPDATE `galaxy_items` SET `req_level` = '7' WHERE `id` = '6' LIMIT 1 ;
UPDATE `galaxy_items` SET `req_level` = '12' WHERE `id` = '7' LIMIT 1 ;
UPDATE `galaxy_items` SET `req_level` = '17' WHERE `id` = '8' LIMIT 1 ;
UPDATE `galaxy_items` SET `req_level` = '22' WHERE `id` = '9' LIMIT 1 ;
UPDATE `galaxy_items` SET `distance` = '1' WHERE `id` = '17' LIMIT 1 ;

INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` ) VALUES ('', 'xtd', 'weapon', '', '0', '50000', '1', '10', '5', '2', '3', '7', '0', '15', '3', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` ) VALUES ('', 'xtd-2', 'weapon', '', '0', '75000', '1', '16', '5', '2', '2', '8', '0', '15', '3', '0', '5', '0', '0', '0', '5', '0', '0');
INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` ) VALUES ('', 'xtd-m', 'weapon', '', '0', '100000', '1', '22', '10', '5', '1.5', '9', '1', '15', '0', '3', '10', '0', '0', '0', '10', '0', '0');
INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` ) VALUES ('', 'lasergun', 'weapon', '', '0', '22000', '1', '3', '2', '2', '2', '5', '0', '2', '0', '1', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` ) VALUES ('', 'plasmagun', 'weapon', '', '0', '88000', '1', '8', '0', '6', '3.5', '8.5', '0.25', '20', '0', '2', '2', '0', '0', '0', '0', '0', '0');

UPDATE `galaxy_items` SET `name` = 'lance',
`price` = '15000',
`req_level` = '3',
`req_agility` = '5',
`min` = '1,25',
`max` = '2,75',
`criticalhit` = '0',
`critical` = '0' WHERE `id` = '2' LIMIT 1;

UPDATE `galaxy_planets` SET `system` = 'Bree-37' WHERE `id` = '4' LIMIT 1 ;

