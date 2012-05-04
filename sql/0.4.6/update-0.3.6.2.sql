-- SQL Database Update {update.sql} 
-- Version: 0.3.6.2

DROP TABLE `galaxy_access`;
INSERT INTO `galaxy_news` ( `id` , `timestamp` , `from` , `locale` , `message` )
VALUES (
'5', NOW( ) , 'zoltarx', 'en', 'Little core changes in <b>PHP</b> code. There are some problems with loging in and registering due to unfinished server configuration.<p>Be patient, <b>0.3.7</b> version is comming...'
), (
'6', NOW( ) , 'zoltarx', 'pl', 'Małe zmiany w kodzie <b>PHP</b> gry. W tej chwili jest kilka problemów z logowaniem i rejestracją (dokładniej: wysyłaniem potwierdzeń) z powodu nieukończonej jeszcze konfiguracji serwera.<p>Bądźcie cierpliwi, wersja <b>0.3.7</b> już niedługo...'
);
