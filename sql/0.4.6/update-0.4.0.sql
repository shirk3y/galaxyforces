-- SQL Database Update {update-0.4.0.sql}
-- Version: 0.4.0

CREATE TABLE galaxy_events (
  event_timestamp varchar(14) NOT NULL,
  event_from varchar(32) NOT NULL,
  event_subject varchar(32) NOT NULL,
  event_message varchar(255) NOT NULL,
  KEY event_timestamp (event_timestamp)
);

ALTER TABLE `galaxy_colonies` ADD `whisper` INT NOT NULL AFTER `dragon` ;

ALTER TABLE `galaxy_attacks` ADD `whisper` INT NOT NULL AFTER `dragonkilled` ,
ADD `whisperlost` INT NOT NULL AFTER `whisper` ,
ADD `whisperkilled` INT NOT NULL AFTER `whisperlost` ;

ALTER TABLE `galaxy_colonies` ADD `whispertechnology` TINYINT NOT NULL AFTER `dragontechnology` ;

