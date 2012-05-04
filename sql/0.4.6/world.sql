INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` , `level` , `reputation` )
VALUES (
'', 'antar', 'trainingdocks', '10', '100', '5', '0'
);

INSERT INTO galaxy_places (position,type,parameters,extra,level) values ('galeon','itemshop','laserpistol,scanner,lighthelmet,electricspade','3','5');

INSERT INTO `galaxy_markets` ( `position` , `level` , `reputation` , `energybuyaverage` , `energybuy` , `energysellaverage` , `energysell` , `siliconbuyaverage` , `siliconbuy` , `siliconsellaverage` , `siliconsell` , `metalbuyaverage` , `metalbuy` , `metalsellaverage` , `metalsell` , `uranbuyaverage` , `uranbuy` , `uransellaverage` , `uransell` , `plutoniumbuyaverage` , `plutoniumbuy` , `plutoniumsellaverage` , `plutoniumsell` , `deuteriumbuyaverage` , `deuteriumbuy` , `deuteriumsellaverage` , `deuteriumsell` , `foodbuyaverage` , `foodbuy` , `foodsellaverage` , `foodsell` , `crystalsbuyaverage` , `crystalsbuy` , `crystalssellaverage` , `crystalssell` )
VALUES (
'coruscant', '0', '-3', '100', '0', '0.05', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'
);

INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` , `level` , `reputation` )
VALUES (
'', 'coruscant', 'mercenary', '0.7', '', '0', '0'
);

INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` , `level` , `reputation` )
VALUES (
'', 'endor', 'arena', '', '', '0', '0'
);
INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` , `level` , `reputation` )
VALUES (
'', 'antar', 'trainingdocks', '10', '100', '5', '0'
);

INSERT INTO `galaxy_places` ( `id` , `position` , `type` , `parameters` , `extra` , `level` , `reputation` ) 
VALUES (
'', 'galeon', 'itemshop', 'laserpistol,scanner,lighthelmet,electricspade', '3', '0', '0'
);


INSERT INTO `galaxy_space` VALUES (1, 'angus', 'planet', 'big', 'human', 'home', 'M-14', 3, 1, 3, 49, 337, 13, 53, 21, 5, 1, 43);
INSERT INTO `galaxy_space` VALUES (2, 'ring', 'planet', 'big', 'human', 'home', '', -3, -5, -1, 32.2739, 122, 17, 34, 41, 3, 11, 35);
INSERT INTO `galaxy_space` VALUES (3, 'ben', 'planet', 'medium', 'human', 'home', 'M-14', 0, 2, 3, 26.304, 200, 16, 37, 31, 2, 2, 47);
INSERT INTO `galaxy_space` VALUES (4, 'mouse', 'planet', 'small', 'human', 'onion', 'Bree-37', -1, 0, 0, 48.146, 39, 12, 63, 26, 3, 2, 61);
INSERT INTO `galaxy_space` VALUES (5, 'erathia', 'planet', 'big', 'ami', 'maya', '', -7, 4, 0, 0, 0, 1, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (6, 'darkstar', 'planet', 'medium', 'tron', 'tron', '', 0, -1, 2, 0.426, 66, 40, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (7, 'harmony', 'planet', 'big', 'ami', 'onion', '', -4, 3, 7, 0, 0, 3, 11, 13, 7, 4, 76);
INSERT INTO `galaxy_space` VALUES (8, 'hybrid', 'planet', 'huge', 'ami', 'maya', 'Ameno XIV', 3, 12, 8, 0, 0, 3, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (9, 'phantasmagoria', 'planet', 'giant', 'tron', 'tron', '', 4, 2, -1, 0, 67, 25, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (10, 'dreamer', 'planet', 'small', 'ami', 'maya', '', -5, 4, -3, 0, 0, 2, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (11, 'eye', 'planet', 'huge', 'ami', 'plexi', '', -2, -1, 7, 0, 0, 1, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (12, 'galeon', 'planet', 'huge', 'tron', 'plexi', '', -4, -20, 17, 0, 66, 35, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (13, 'mulahay', 'planet', 'medium', 'human', 'home', '', 20, 15, 10, 47.1, 103, 9, 17, 54, 2.5, 3, 21);
INSERT INTO `galaxy_space` VALUES (14, 'amoeba', 'planet', 'huge', 'human', 'onion', '', 5, -7, 3, 40.283, 132, 21, 71, 63, 5, 0, 23);
INSERT INTO `galaxy_space` VALUES (15, 'earth2', 'planet', 'medium', 'human', 'wolf', '', 0, 0, 0, 43.164, 155, 17, 70, 40, 2, 2, 40);
INSERT INTO `galaxy_space` VALUES (16, 'nemesis', 'planet', 'giant', 'tron', 'wolf', '', 66, 66, 66, 0, 66, 70, 30, 50, 2.5, 0, 15);
INSERT INTO `galaxy_space` VALUES (17, 'ariel', 'asteroid', 'medium', 'none', 'onion', 'Bree-37', -2, 0, -1, 0, 0, 0, 0, 0, 1, 0, 15);
INSERT INTO `galaxy_space` VALUES (18, 'gemini', 'meteor', 'big', 'none', 'onion', '', 4, 5, -3, 0, 0, 0, 0, 0, 10, 0, 0);
INSERT INTO `galaxy_space` VALUES (19, 'sula', 'asteroid', 'medium', 'none', 'maya', 'Ameno XIV', 4, 10, 7, 0, 0, 0, 0, 0, 1, 0, 15);
INSERT INTO `galaxy_space` VALUES (20, 'enea', 'asteroid', 'medium', 'none', 'tron', '42', 22, 5, -7, 0, 0, 0, 0, 0, 5, 0, 15);
INSERT INTO `galaxy_space` VALUES (21, 'phantomia', 'planet', 'medium', 'unknown', 'onion', 'Zoob-XVII', -7, -8, -10, 0.246, 2, 79, 42, 59, 20, 7, 35);
INSERT INTO `galaxy_space` VALUES (22, 'prophetie', 'planet', 'giant', 'unknown', 'underverse', '', 0, 0, 0, 0.063, 0, 0, -100, 100, 100, 50, 15);
INSERT INTO `galaxy_space` VALUES (23, 'quarell', 'meteor', 'small', 'none', 'plexi', '', -3, -10, 11, 0, 0, 0, 0, 0, 5, 0, 15);
INSERT INTO `galaxy_space` VALUES (24, 'yareach', 'planet', 'medium', 'unknown', 'underverse', '', 446, 336, 446, 0, 0, 0, 0, 0, 50, 3, 15);
INSERT INTO `galaxy_space` VALUES (25, 'courscant', 'planet', 'medium', 'human', 'wolf', 'Twin Sector', -43, 41, 30, 0, 0, 10, 73, 10, 3, 1, 61);
INSERT INTO `galaxy_space` VALUES (26, 'antar', 'asteroid', 'medium', 'none', 'wolf', 'Cularian Line', -49, 30, 27, 0, 0, 0, 0, 0, 10, 0, 15);
INSERT INTO `galaxy_space` VALUES (27, 'tatooine', 'planet', 'huge', 'human', 'wolf', 'Twin Sector', -40, 43, 32, 0, 0, 63, 46, 60, 14, 2, 71);
INSERT INTO `galaxy_space` VALUES (28, 'yavin', 'planet', 'small', 'human', 'wolf', 'Ando Space', -53, 7, -4, 0, 0, 17, 53, 41, 2.5, 0, 55);
INSERT INTO `galaxy_space` VALUES (29, 'endor', 'planet', 'big', 'human', 'wolf', 'Ando Space', -51, -1, 3, 0, 0, 15, 61, 33, 8.5, 0, 49);
INSERT INTO `galaxy_space` VALUES (30, 'dagobah', 'planet', 'medium', 'human', 'wolf', '', -71, 42, 15, 0, 0, 33, 50, 67, 29, 1, 21);
INSERT INTO `galaxy_space` VALUES (31, 'velmor', 'asteroid', '', 'none', 'wolf', 'Cularian Line', -44, 33, 25, 0, 0, 0, 0, 0, 10, 0, 15);
INSERT INTO `galaxy_space` VALUES (32, 'ruan', 'asteroid', '', 'none', 'wolf', 'Cularian Line', -47, 31, 32, 0, 0, 0, 0, 0, 10, 0, 15);
