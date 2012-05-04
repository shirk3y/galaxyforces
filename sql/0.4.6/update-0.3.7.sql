-- SQL Database Update {update.sql} 
-- Version: 0.3.7

INSERT INTO `galaxy_news` ( `id` , `timestamp` , `from` , `locale` , `message` ) VALUES 
('', NOW( ) , 'zoltarx', 'en', 'The authentication system was fixed so everyone should recover password with <a href="lostpassword.php">lost password</a> form.'),
('', NOW( ) , 'zoltarx', 'pl', 'System autoryzacji został zmieniony więc każdy powinien odzyskać swoje hasło używając <a href="lostpassword.php">formularza</a>.');

ALTER TABLE `galaxy_planets` ADD `type` ENUM( 'planet', 'asteroid', 'meteor' ) DEFAULT 'planet' NOT NULL AFTER `name` ;

ALTER TABLE `galaxy_planets` RENAME `galaxy_space` ;

ALTER TABLE `galaxy_space` CHANGE `technology` `technology` ENUM( 'none', 'human', 'tron', 'ami', 'unknown' ) DEFAULT 'human' NOT NULL;

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` ) VALUES
('', 'ariel', 'asteroid', 'medium', 'none', 'nebula', 'Bree-37', '-2', '0', '-1', '0', '0', '0', '0', '0', '1');

INSERT INTO `galaxy_space` ( `id` , `name` , `type` , `class` , `technology` , `galaxy` , `system` , `x` , `y` , `z` , `explored` , `abandoned` , `wind` , `life` , `terrain` , `gravity` ) VALUES
('', 'gemini', 'meteor', 'big', 'none', 'nebula', '', '-2', '1', '5', '0', '0', '15', '30', '50', '2.5');

ALTER TABLE `galaxy_users` ADD `seen` TIMESTAMP DEFAULT 'NOW()' NOT NULL AFTER `registered` ;
ALTER TABLE `galaxy_users` CHANGE `seen` `seen` TIMESTAMP( 14 );

ALTER TABLE `galaxy_groups` CHANGE `tax` `tax` INT( 11 ) DEFAULT '15' NOT NULL;
ALTER TABLE `galaxy_groups` ADD `created` TIMESTAMP DEFAULT 'NOW()' NOT NULL AFTER `name`;
ALTER TABLE `galaxy_groups` CHANGE `created` `created` TIMESTAMP( 14 );
ALTER TABLE `galaxy_groups` CHANGE `created` `created` DATE NOT NULL;

