-- SQL Database Update {update-0.3.12.sql}
-- Version: 0.3.12

UPDATE `galaxy_space` SET `moons` = '1', `illumination` = '43',`terrain`=21,`life`='53' WHERE `name` ='angus' LIMIT 1;
UPDATE `galaxy_space` SET `x` = '-3',`y` = '-5',`z` = '-1',`wind` = '17',`life` = '34',`gravity`=3,`terrain` = '41',`moons` = '11',`illumination` = '35' WHERE `name` ='ring' LIMIT 1 ;
UPDATE `galaxy_space` SET `wind`=16,`life` = '37',`terrain` = '31',`moons` = '2',`gravity`=2,`illumination` = '47' WHERE `name` ='ben' LIMIT 1 ;
UPDATE `galaxy_space` SET `life` = '17',`terrain` = '54',`moons` = '3',`illumination` = '21' WHERE `name`='mulahay' LIMIT 1 ;
UPDATE `galaxy_space` SET `x` = '5',`y` = '-7',`z` = '3',`wind` = '21',`life` = '71',`terrain` = '63',`gravity` = '5',`illumination` = '23' WHERE `name` ='amoeba' LIMIT 1 ;
UPDATE `galaxy_space` SET `x` = '4',`y` = '5',`z` = '-3',`wind` = '0',`life` = '0',`terrain` = '0',`gravity` = '10',`illumination` = '0' WHERE `name` ='gemini' LIMIT 1 ;
UPDATE `galaxy_space` SET `wind` = '79',`life` = '42',`terrain` = '59',`illumination` = '35' WHERE `name` ='phantomia' LIMIT 1 ;
UPDATE `galaxy_space` SET `life` = '63',`terrain` = '26',`gravity` = '3',`moons` = '2',`illumination` = '61' WHERE `name` ='mouse' LIMIT 1 ;
UPDATE `galaxy_space` SET `x` = '-4',`y` = '3',`life` = '11',`terrain` = '13',`gravity` = '7',`moons` = '4',`illumination` = '76' WHERE `name`='harmony' LIMIT 1 ;

ALTER TABLE `galaxy_universe` ADD `age` VARCHAR( 16 ) NOT NULL;
UPDATE `galaxy_universe` SET `discovered` = NULL ,`by` = NULL ,`age` = '233' WHERE `name` ='home' LIMIT 1 ;
UPDATE `galaxy_universe` SET `discovered` = NULL ,`by` = NULL ,`age` = '487' WHERE `name` ='onion' LIMIT 1 ;

ALTER TABLE `galaxy_items` CHANGE `type` `type` VARCHAR( 16 );
ALTER TABLE `galaxy_equipment` CHANGE `type` `type` VARCHAR( 16 );

INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'eye', 'forcesource', '1', '2');
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'dreamer', 'forcesource', '10', '5');
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'ring', 'itemshop', 'creditcard,spade', '1');
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'phantasmagoria', 'gemshop', 'yellowgem,orangegem,greengem,redgem', '1');
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'nemesis', 'gemshop', 'redgem,whitegem', '5');

ALTER TABLE `galaxy_users` ADD `hpmodifier` VARCHAR( 8 ) NOT NULL AFTER `hpgain` ;
ALTER TABLE `galaxy_users` ADD `mpmodifier` VARCHAR( 8 ) NOT NULL AFTER `mpgain` ;
ALTER TABLE `galaxy_users` ADD `strengthmodifier` VARCHAR( 8 ) NOT NULL AFTER `strength` ;
ALTER TABLE `galaxy_users` ADD `agilitymodifier` VARCHAR( 8 ) NOT NULL AFTER `agility` ;

ALTER TABLE `galaxy_items` ADD `use` INT NOT NULL;
ALTER TABLE `galaxy_equipment` ADD `use` INT NOT NULL;
ALTER TABLE `galaxy_colonies` ADD `satisfaction` FLOAT NOT NULL AFTER `soldiers`, ADD `lost` INT UNSIGNED NOT NULL AFTER `satisfaction` ;
ALTER TABLE `galaxy_users` CHANGE `reputation` `reputation` FLOAT DEFAULT '0' NOT NULL;
ALTER TABLE `galaxy_users` ADD `pocketstealing` FLOAT NOT NULL AFTER `agilitymodifier` , ADD `alcoholism` FLOAT NOT NULL AFTER `pocketstealing` ;

CREATE TABLE `galaxy_tips` (
`id` INT NOT NULL AUTO_INCREMENT ,
`locale` VARCHAR( 16 ) NOT NULL ,
`tip` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) 
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'If you have colony based on human technology you need people to work. You can hire them at the Mercenary places located on some planets but remember that the hire cost depends on your reputation.'
), (
'', 'pl', 'Jeœli posiadasz koloniê w technologii ludzi potrzebujesz ludzi do pracy. Mo¿esz ich naj¹æ w obozach najemników na niektórych planetach, pamiêtaj jednak, ¿e koszt najêcia zale¿y od twojej reputacji.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Ypur colony needs energy. It is good first to build some Wind generators or Solar batteries, these buldings give you some energy depend on planet parameters where your colony is located.'
), (
'', 'pl', 'Twoja kolonia potrzebuje pr¹du (energii). Dobrze jest najpierw wybudowaæ nieco elektrowni wiatrowych lub baterii s³onecznych, daj¹ one energiê w zale¿noœci od wspó³czynników planety na której znajduje siê twoja kolonia.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'The most important thing in creating your colony is to find some sources: uran, metal, etc. To find them you need to explore your planet or space.'
), (
'', 'pl', 'Najwa¿niejsz¹ rzecz¹ we wczesnej rozbudowie kolonii jest znalezienie Ÿróde³: metalu, uranu, itp. By je odnaleŸæ muisisz wysy³aæ wyprawy.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Instead of building you need to produce some units. There are few kinds of them, i.e. robots that work for you or fighters that fights :-) You need to build a factory to produce anything. The more factories you have the more productions you can have at the same time.'
), (
'', 'pl', 'Oprócz budynków potrzebujesz jednostek. Jest ich kilka rodzajów, np. roboty, które pracuj¹ dla ciebie lub statki wojenne, których potrzebujesz do wojny. Musisz wybudowaæ fabrykê by cokolwiek produkowaæ. Im wiêcej fabryk masz, tym wiêcej produkcji mo¿esz rozpocz¹æ w jednym czasie.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Building structures goes faster if your colony has large amount of robots. To produce robots faster you have to build more factories.'
), (
'', 'pl', 'Budowy konstrukcji trwaj¹ krócej jeœli twoja kolonia jest wyposa¿ona w du¿¹ iloœæ robotów. Produkcja robotów z kolei trwa krócej jeœli ma siê du¿o fabryk.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'To find more sources you need to explore your planet or space. Other way to gain some sources for your colony is to work in mines or thoria.'
), (
'', 'pl', 'By znaleŸæ Ÿród³a które nadaj¹ siê do eksploatacji musisz wysy³aæ wyprawy. Inny sposób to praca w kopalniach lub thorii.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Your colony and your hero are two different thing. You can travel around universe and still have control on your colony which is located on some planet that you have chosen.'
), (
'', 'pl', 'Twoja kolonia i twój bohater to dwie ró¿ne rzeczy. Mo¿esz podró¿owaæ po wszechœwiecie i wci¹¿ kontrolowaæ rozwój kolonii, która znajduje siê na planecie która zosta³a wybrana podczas jej tworzenia.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'If you have any questions you can use chat, normally it is full of some strange sayings but if you ask most likely someone will answer.'
), (
'', 'pl', 'Jeœli masz jakieœ pytania, zawsze mo¿esz u¿yæ czata. Jest on zazwyczaj wype³niony dziwnymi wypowiedziami, jednak jeœli zadasz pytanie, w wiêkszoœci wypadków, ktoœ na nie odpowie.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Forum is the first place you should visit to begin playing in our game.'
), (
'', 'pl', 'Forum jest pierwszym miejscem które powinieneœ (powinnaœ) odwiedziæ przed rozpoczêciem gry.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Your score is a rating that gives you higher position in high scores list but you must remember that the more score you have the stronger players can attack your colony!'
), (
'', 'pl', 'Twoje punkty daj¹ wy¿sz¹ pozycjê w wynikach, jednak musisz pamiêtaæ, ¿e im wiêcej punktów posiadasz, tym silniejsi gracze bêd¹ mogli atakowaæ tw¹ koloniê.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Sending scientists to exploration increases possibility to find valuable sources.'
), (
'', 'pl', 'Wys³anie naukowców na wyprawy zwiêksza mo¿liwoœæ znale¿enia cennych Ÿróde³.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'If you don''t care of food for your people they may die and your reputation will go down.'
), (
'', 'pl', 'Jeœli nie zadbasz o dostêpne jedzenie dla ludzi w kolonii, mog¹ oni umrzeæ z g³odu, tym samym spadnie twoja reputacja.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Always check your colony bilances. It is very valuable information.'
), (
'', 'pl', 'Zawsze sprawdzaj bilans przyrostów/zu¿ycia surowców swojej kolonii. Jest to naprawde cenna informacja.'
);

ALTER TABLE `galaxy_items` ADD `weight` FLOAT NOT NULL AFTER `req_hp` ;
ALTER TABLE `galaxy_equipment` ADD `weight` FLOAT NOT NULL AFTER `req_hp` ;

ALTER TABLE `galaxy_users` ADD `knowledge` FLOAT NOT NULL AFTER `alcoholism` ;

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Your hero attributes can be checked in whois section where you can go by clicking on your login.'
), (
'', 'pl', 'Wspó³czynniki swojej postaci mo¿esz zobaczyæ w oknie informacji o graczu do którego mo¿esz przejœæ klikaj¹æ po prostu na swój login.'
);

INSERT INTO `galaxy_tips` ( `id` , `locale` , `tip` ) 
VALUES (
'', 'en', 'Your hero can gain some new abilities during game. You won''t see them unless you gain at least one point to them. If you already have you will have ability to distribute skillpoints to them.'
), (
'', 'pl', 'Twoja postaæ mo¿e zyskaæ nowe zdolnoœci/atrybuty, nie zobaczysz ich jednak dopóki nie zdobêdziesz przynajmniej jednego punktu w danej zdolnoœci. Kiedy ju¿ postaæ uzyska now¹ zdolnoœæ, bêdzie mo¿na przeznaczyæ na ni¹ punkty zdolnoœci SP.'
);

INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `req_psi` , `req_force` , `req_mp` , `req_hp` , `weight` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` , `parameters` , `use` ) 
VALUES (
'', 'scanner', 'scanner', '', '0', '100000', '0', '5', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0'
), (
'', 'spyscanner', 'scanner', '', '0', '500000', '0', '10', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '2', '0'
);

INSERT INTO `galaxy_items` ( `id` , `name` , `type` , `class` , `count` , `price` , `distance` , `req_level` , `req_strength` , `req_agility` , `req_psi` , `req_force` , `req_mp` , `req_hp` , `weight` , `min` , `max` , `armor` , `hit` , `criticalhit` , `critical` , `block` , `speed` , `deaf` , `hide` , `protection` , `hp` , `mp` , `parameters` , `use` ) 
VALUES (
'', 'microscanner', 'scanner', '', '0', '100000', '0', '15', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '3', '0'
), (
'', 'ultrascanner', 'scanner', '', '0', '500000', '0', '20', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '4', '0'
);

ALTER TABLE `galaxy_colonies` CHANGE `crystals` `crystals` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL;
ALTER TABLE `galaxy_colonies` CHANGE `thicks` `thicks` INT UNSIGNED DEFAULT '0' NOT NULL ,
CHANGE `attacked` `attacked` INT UNSIGNED DEFAULT '0' NOT NULL ,
CHANGE `energy` `energy` FLOAT UNSIGNED DEFAULT '500' NOT NULL ,
CHANGE `metal` `metal` FLOAT UNSIGNED DEFAULT '300' NOT NULL ,
CHANGE `uran` `uran` FLOAT UNSIGNED DEFAULT '0' NOT NULL ,
CHANGE `food` `food` FLOAT UNSIGNED DEFAULT '200' NOT NULL ,
CHANGE `metalsources` `metalsources` INT UNSIGNED DEFAULT '0' NOT NULL ,
CHANGE `uransources` `uransources` INT UNSIGNED DEFAULT '0' NOT NULL ,
CHANGE `geosources` `geosources` INT UNSIGNED DEFAULT '0' NOT NULL ;

ALTER TABLE `galaxy_users` ADD `bancount` INT NOT NULL AFTER `banned` ;

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'antar', 'asteroid', '', 'none', 'wolf', 'Cularian Line', '-49', '30', '27', '0', '0', '0', '0', '0', '10', '0', '15'
);
INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'tatooine', 'planet', 'huge', 'human', 'wolf', 'Twin Sector', '-40', '43', '32', '0', '0', '63', '46', '60', '14', '2', '71'
);
INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'yavin', 'planet', 'small', 'human', 'wolf', 'Ando Space', '-53', '7', '-4', '0', '0', '17', '53', '41', '2.5', '0', '55'
);

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'endor', 'planet', 'big', 'human', 'wolf', 'Ando Space', '-51', '-1', '3', '0', '0', '15', '61', '33', '8.5', '0', '49'
);

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'dagobah', 'planet', 'medium', 'human', 'wolf', '', '-71', '42', '15', '0', '0', '33', '50', '67', '29', '1', '21'
);

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'velmor', 'asteroid', '', 'none', 'wolf', 'Cularian Line', '-44', '33', '25', '0', '0', '0', '0', '0', '10', '0', '15'
);

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` ) 
VALUES (
'', 'ruan', 'asteroid', '', 'none', 'wolf', 'Cularian Line', '-47', '31', '32', '0', '0', '0', '0', '0', '10', '0', '15'
);

ALTER TABLE `galaxy_universe` CHANGE `discovered` `discovered` VARCHAR( 10 ) NOT NULL , CHANGE `by` `by` VARCHAR( 16 ) NOT NULL ;

ALTER TABLE `galaxy_users` ADD `intellect` FLOAT NOT NULL AFTER `alcoholism` ;
ALTER TABLE `galaxy_users` ADD `hacking` FLOAT NOT NULL AFTER `knowledge` ;

ALTER TABLE `galaxy_items` ADD `req_intellect` INT NOT NULL AFTER `req_hp` ,
ADD `req_knowledge` INT NOT NULL AFTER `req_intellect` ,
ADD `req_pocketstealing` INT NOT NULL AFTER `req_knowledge` ,
ADD `req_hacking` INT NOT NULL AFTER `req_pocketstealing` ,
ADD `req_alcoholism` INT NOT NULL AFTER `req_hacking` ;

ALTER TABLE `galaxy_equipment` ADD `req_intellect` INT NOT NULL AFTER `req_hp` ,
ADD `req_knowledge` INT NOT NULL AFTER `req_intellect` ,
ADD `req_pocketstealing` INT NOT NULL AFTER `req_knowledge` ,
ADD `req_hacking` INT NOT NULL AFTER `req_pocketstealing` ,
ADD `req_alcoholism` INT NOT NULL AFTER `req_hacking` ;
ALTER TABLE `galaxy_users` CHANGE `email` `email` VARCHAR( 64 ) NOT NULL ;

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` , `moons` , `illumination` )
VALUES (
'', 'crematoria', 'planet', 'big', 'none', 'tron', '', '-15', '-24', '-31', '0', '0', '5', '0', '100', '10', '0', '100'
);

ALTER TABLE `galaxy_colonies` ADD `siliconsources` INT UNSIGNED NOT NULL AFTER `geosources` ,
ADD `plutoniumsources` INT UNSIGNED NOT NULL AFTER `siliconsources` ;

ALTER TABLE `galaxy_colonies` CHANGE `base` `base` INT UNSIGNED NOT NULL ,
CHANGE `tron` `tron` INT UNSIGNED NOT NULL ;

ALTER TABLE `galaxy_colonies` ADD `ami` INT UNSIGNED NOT NULL AFTER `tron` ,
ADD `cyber` INT UNSIGNED NOT NULL AFTER `ami` ,
ADD `necro` INT UNSIGNED NOT NULL AFTER `cyber` ;

ALTER TABLE `galaxy_space` CHANGE `technology` `technology` ENUM( 'none', 'human', 'tron', 'ami', 'cyber', 'necro', 'unknown' ) DEFAULT 'human' NOT NULL ;

UPDATE `galaxy_space` SET `technology` = 'necro' WHERE `name`='prophetie' LIMIT 1 ;
UPDATE `galaxy_space` SET `technology` = 'cyber' WHERE `name`='phantomia' LIMIT 1 ;
UPDATE `galaxy_space` SET `technology` = 'necro',`wind` = '33',`terrain` = '77',`illumination` = '1' WHERE `name`='yareach' LIMIT 1 ;

ALTER TABLE `galaxy_messages` CHANGE `timestamp` `timestamp` VARCHAR( 14 ) NOT NULL ;

ALTER TABLE `galaxy_colonies` ADD `silicon` INT UNSIGNED NOT NULL AFTER `metal` ,ADD `deuterium` INT UNSIGNED NOT NULL AFTER `silicon` ;

ALTER TABLE `galaxy_colonies` ADD `plutonium` INT UNSIGNED NOT NULL AFTER `uran` ;

ALTER TABLE `galaxy_colonies` ADD `worker` INT UNSIGNED NOT NULL AFTER `nemesis` ,
ADD `cage` INT UNSIGNED NOT NULL AFTER `worker` ;

ALTER TABLE `galaxy_colonies` DROP `scout` ;

ALTER TABLE `galaxy_colonies` ADD `scout` INT UNSIGNED NOT NULL AFTER `worker` ;
ALTER TABLE `galaxy_colonies` ADD `databank` INT UNSIGNED NOT NULL AFTER `laboratory` ;
ALTER TABLE `galaxy_colonies` ADD `mmu` INT UNSIGNED NOT NULL AFTER `nemesis` ;
ALTER TABLE `galaxy_colonies` ADD `trontechnology` INT NOT NULL ;
ALTER TABLE `galaxy_colonies` CHANGE `hawktechnology` `hawktechnology` INT NOT NULL ;
ALTER TABLE `galaxy_colonies` ADD `tacticstechnology` INT UNSIGNED NOT NULL AFTER `spaceshipstechnology` ;
