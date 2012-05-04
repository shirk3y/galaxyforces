-- SQL Database Update {update-0.3.13.sql}
-- Version: 0.3.13

ALTER TABLE `galaxy_messages` DROP `time`;
ALTER TABLE `galaxy_clanmessages` CHANGE `type` `type` ENUM( 'attack', 'reject', 'donate', 'admit', 'join', 'recultivation', 'leave', 'councildismiss', 'ownerchange', 'counciladmit' ) DEFAULT 'attack' NOT NULL ;
ALTER TABLE `galaxy_clanmessages` CHANGE `type` `type` ENUM( '', 'attack', 'reject', 'donate', 'admit', 'join', 'recultivation', 'leave', 'councildismiss', 'ownerchange', 'counciladmit' ) NOT NULL;
