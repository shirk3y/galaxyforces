-- SQL Database Update {update-0.4.1.sql}
-- Version: 0.4.1

CREATE TABLE galaxy_config(
config_key varchar( 255 ) NOT NULL ,
config_value varchar( 255 ) NOT NULL ,
PRIMARY KEY ( config_key ) 
);

INSERT INTO `galaxy_config` ( `config_key` , `config_value` ) 
VALUES (
'Version', '0.4.1'
);

ALTER TABLE `galaxy_attacks` ADD `satellitekilled` INT NOT NULL AFTER `detectorkilled` ;
