-- SQL Database Update {update.sql}
-- Version: 0.3.8

UPDATE `galaxy_space` SET `name` = 'ariel' WHERE `name` = 'Ariel' LIMIT 1 ;
UPDATE `galaxy_space` SET `name` = 'gemini' WHERE `name` = 'Gemini' LIMIT 1 ;

UPDATE `galaxy_places` SET `extra` = '10' WHERE `position` = 'ariel' AND `type` = 'itemshop' LIMIT 1;
INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` ) VALUES ('', 'sula', 'asteroid', 'medium', 'none', 'maya', 'Ameno XIV', '4', '10', '7', '0', '0', '0', '0', '0', '1');
INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` ) VALUES ('', 'enea', 'asteroid', 'medium', 'none', 'tron', '42', '22', '5', '-7', '0', '0', '0', '0', '0', '5');

UPDATE `galaxy_space` SET `system` = 'Ameno XIV' WHERE `name` = 'hybrid' LIMIT 1 ;

INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'ariel', 'itemshop', 'knife,lightarmor,belt,xtd', '');
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'enea', 'itemshop', 'energyarmor,lightninggun,plasmagun,powerbelt,belt,knife', '15');
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` ) VALUES ('', 'sula', 'itemshop', 'pins,mshirt,knife,lasergun', '8');

