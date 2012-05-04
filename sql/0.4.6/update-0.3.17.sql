-- SQL Database Update {update-0.3.17.sql}
-- Version: 0.3.17

ALTER TABLE `galaxy_clanmessages` CHANGE `type` `type` ENUM( '', 'attack', 'reject', 'donate', 'admit', 'join', 'recultivation', 'leave', 'councildismiss', 'ownerchange', 'counciladmit', 'namechange','statuschange') NOT NULL;
