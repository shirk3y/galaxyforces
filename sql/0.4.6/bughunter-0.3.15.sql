-- SQL Database Bug Hunter {bughunter.sql} 
-- Version: 0.3.15

UPDATE galaxy_space SET explored=0 WHERE explored<0;
UPDATE galaxy_users SET crystals=0 WHERE crystals<0;
UPDATE galaxy_users SET energy=0 WHERE energy<0;
UPDATE galaxy_users SET metal=0 WHERE metal<0;
DELETE FROM galaxy_space WHERE galaxy='';
UPDATE galaxy_users SET id=0 WHERE login='admin';
