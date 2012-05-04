-- SQL Database Install {install.sql} 
-- Version: 0.5.1

-- --------------------------------------------------------

CREATE TABLE `galaxy_ads` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL default '0',
  `expires` int(11) NOT NULL default '0',
  `status` int(6) NOT NULL default '0',
  `lang` varchar(6) NOT NULL default '',
  `class` varchar(16) NOT NULL default '',
  `type` varchar(12) NOT NULL default '',
  `notify` int(11) NOT NULL default '0',
  `replies` varchar(12) NOT NULL default '0',
  `author` varchar(64) NOT NULL default '',
  `title` varchar(35) NOT NULL default '',
  `expired` tinyint(1) NOT NULL default '0',
  `content` mediumtext NOT NULL,
   PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

CREATE TABLE `galaxy_attacks` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `owner` varchar(32) NOT NULL default '',
  `target` varchar(32) NOT NULL default '',
  `status` int(11) NOT NULL default '0',
  `communicationlost` tinyint(1) NOT NULL default '0',
  `efficacy` float NOT NULL default '0',
  `begin` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `strategy` int(11) NOT NULL default '0',
  `bonus` int(11) NOT NULL default '0',
  `colonistskilled` int(11) NOT NULL default '0',
  `scientistskilled` int(11) NOT NULL default '0',
  `soldiers` int(11) NOT NULL default '0',
  `soldierslost` int(11) NOT NULL default '0',
  `soldierskilled` int(11) NOT NULL default '0',
  `bx1killed` int(11) NOT NULL default '0',
  `bx2killed` int(11) NOT NULL default '0',
  `bx5killed` int(11) NOT NULL default '0',
  `bx10` int(11) NOT NULL default '0',
  `bx10lost` int(11) NOT NULL default '0',
  `bx10killed` int(11) NOT NULL default '0',
  `walker` int(11) NOT NULL default '0',
  `walkerlost` int(11) NOT NULL default '0',
  `walkerkilled` int(11) NOT NULL default '0',
  `hawk` int(11) NOT NULL default '0',
  `hawklost` int(11) NOT NULL default '0',
  `hawkkilled` int(11) NOT NULL default '0',
  `valkyrie` int(11) NOT NULL default '0',
  `valkyrielost` int(11) NOT NULL default '0',
  `valkyriekilled` int(11) NOT NULL default '0',
  `crusader` int(11) NOT NULL default '0',
  `crusaderlost` int(11) NOT NULL default '0',
  `crusaderkilled` int(11) NOT NULL default '0',
  `warrior` int(11) NOT NULL default '0',
  `warriorlost` int(11) NOT NULL default '0',
  `warriorkilled` int(11) NOT NULL default '0',
  `dragon` int(11) NOT NULL default '0',
  `dragonlost` int(11) NOT NULL default '0',
  `dragonkilled` int(11) NOT NULL default '0',
  `whisper` int(11) NOT NULL default '0',
  `whisperlost` int(11) NOT NULL default '0',
  `whisperkilled` int(11) NOT NULL default '0',
  `nemesis` int(11) NOT NULL default '0',
  `nemesislost` int(11) NOT NULL default '0',
  `nemesiskilled` int(11) NOT NULL default '0',
  `scavenger` int(11) NOT NULL default '0',
  `scavengerlost` int(11) NOT NULL default '0',
  `scavengerkilled` int(11) NOT NULL default '0',
  `carrier` int(11) NOT NULL default '0',
  `carrierlost` int(11) NOT NULL default '0',
  `carrierkilled` int(11) NOT NULL default '0',
  `vesselkilled` int(11) NOT NULL default '0',
  `detectorkilled` int(11) NOT NULL default '0',
  `satellitekilled` int(11) NOT NULL default '0',
  `bee` int(11) NOT NULL default '0',
  `beelost` int(11) NOT NULL default '0',
  `beekilled` int(11) NOT NULL default '0',
  `windgenerator` int(11) NOT NULL default '0',
  `solarbattery` int(11) NOT NULL default '0',
  `fusionreactor` int(11) NOT NULL default '0',
  `bunker` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `metal` int(11) NOT NULL default '0',
  `uran` int(11) NOT NULL default '0',
  `crystals` int(11) NOT NULL default '0',
  `credits` bigint(20) NOT NULL default '0',
  `exp` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `login` (`login`),
  KEY `target` (`target`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_buildings`
--

CREATE TABLE `galaxy_buildings` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '0',
  `begin` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `amount` int(11) NOT NULL default '1',
  `score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_chat`
--

CREATE TABLE `galaxy_chat` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` varchar(14) NOT NULL default '',
  `hidden` tinyint(1) not null default 0,
  `author` varchar(64) NOT NULL default '',
  `message` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_clanmessages`
--

CREATE TABLE `galaxy_clanmessages` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('','attack','reject','donate','admit','join','recultivation','leave','councildismiss','ownerchange','counciladmit','namechange','statuschange') NOT NULL,
  `timestamp` timestamp NOT NULL,
  `time` int(11) NOT NULL default '0',
  `clan` varchar(32) NOT NULL default '',
  `from` varchar(32) NOT NULL default '',
  `to` varchar(32) NOT NULL default '',
  `subject` varchar(120) NOT NULL default '',
  `credits` int(11) NOT NULL default '0',
  `crystals` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `clan` (`clan`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_colonies`
--

CREATE TABLE `galaxy_colonies` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `owner` varchar(32) NOT NULL default '',
  `planet` varchar(32) NOT NULL default '',
  `oldplanet` varchar(32) NOT NULL default '',
  `jailto` varchar(14) NOT NULL default '',
  `avatar` varchar(128) NOT NULL default '',
  `thicks` int(11) NOT NULL default '0',
  `description` varchar(240) NOT NULL default '',
  `attacked` int(11) NOT NULL default '0',
  `damage` float NOT NULL default '0',
  `energy` float NOT NULL default '0',
  `metal` float NOT NULL default '0',
  `silicon` float NOT NULL default '0',
  `deuterium` float NOT NULL default '0',
  `uran` float NOT NULL default '0',
  `plutonium` float NOT NULL default '0',
  `organics` float NOT NULL,
  `food` float NOT NULL default '0',
  `crystals` int(10) unsigned NOT NULL default '0',
  `metalsources` int(11) NOT NULL default '0',
  `uransources` int(11) NOT NULL default '0',
  `geosources` int(11) NOT NULL default '0',
  `siliconsources` int(11) NOT NULL default '0',
  `plutoniumsources` int(11) NOT NULL default '0',
  `base` int(11) NOT NULL default '0',
  `tron` int(11) NOT NULL default '0',
  `ami` int(11) NOT NULL default '0',
  `cyber` int(11) NOT NULL default '0',
  `necro` int(11) NOT NULL default '0',
  `infrastructure` int(11) NOT NULL default '50',
  `science` int(11) NOT NULL default '25',
  `military` int(11) NOT NULL default '25',
  `colonists` int(11) NOT NULL default '0',
  `scientists` int(11) NOT NULL default '0',
  `soldiers` int(11) NOT NULL default '0',
  `clones` int(11) NOT NULL,
  `drones` int(11) NOT NULL,
  `souls` int(11) NOT NULL,
  `satisfaction` float NOT NULL default '0',
  `lost` int(11) NOT NULL default '0',
  `ax3` int(11) NOT NULL default '0',
  `ax6` int(11) NOT NULL default '0',
  `bx1` int(11) NOT NULL default '0',
  `bx2` int(11) NOT NULL default '0',
  `bx5` int(11) NOT NULL default '0',
  `bx10` int(11) NOT NULL default '0',
  `cx7` int(11) NOT NULL default '0',
  `cx13` int(11) NOT NULL default '0',
  `walker` int(11) NOT NULL default '0',
  `factory` int(11) NOT NULL default '0',
  `flats` int(11) NOT NULL default '0',
  `barracks` int(11) NOT NULL default '0',
  `laboratory` int(11) NOT NULL default '0',
  `databank` int(11) NOT NULL default '0',
  `depot` int(11) NOT NULL default '1',
  `bunker` int(11) NOT NULL default '0',
  `lasertower` int(11) NOT NULL default '0',
  `plasmatower` int(11) NOT NULL default '0',
  `academy` int(11) NOT NULL default '0',
  `spacedepot` int(11) NOT NULL default '0',
  `windgenerator` int(11) NOT NULL default '0',
  `solarbattery` int(11) NOT NULL default '0',
  `fusionreactor` int(11) NOT NULL default '0',
  `fusionreactoroff` tinyint(4) NOT NULL default '0',
  `metalextractor` int(11) NOT NULL default '0',
  `foodplanting` int(11) NOT NULL default '0',
  `uranmine` int(11) NOT NULL default '0',
  `uranmineoff` tinyint(4) NOT NULL default '0',
  `energysilo` int(11) NOT NULL default '0',
  `metalsilo` int(11) NOT NULL default '0',
  `uransilo` int(11) NOT NULL default '0',
  `foodsilo` int(11) NOT NULL default '0',
  `hawk` int(11) NOT NULL default '0',
  `scavenger` int(11) NOT NULL default '0',
  `crusader` int(11) NOT NULL default '0',
  `vessel` int(11) NOT NULL default '0',
  `carrier` int(11) NOT NULL default '0',
  `valkyrie` int(11) NOT NULL default '0',
  `punisher` int(11) NOT NULL default '0',
  `anathema` int(11) NOT NULL default '0',
  `grandfather` int(11) NOT NULL default '0',
  `firebird` int(11) NOT NULL default '0',
  `warrior` int(11) NOT NULL default '0',
  `dragon` int(11) NOT NULL default '0',
  `whisper` int(11) NOT NULL,
  `mystic` int(11) NOT NULL default '0',
  `detector` int(11) NOT NULL default '0',
  `satellite` int(11) NOT NULL default '0',
  `nemesis` int(11) NOT NULL default '0',
  `mmu` int(11) NOT NULL default '0',
  `worker` int(11) NOT NULL default '0',
  `scout` int(11) NOT NULL default '0',
  `cage` int(11) NOT NULL default '0',
  `bee` int(11) NOT NULL default '0',
  `bxtechnology` tinyint(4) NOT NULL default '0',
  `hawktechnology` tinyint(4) NOT NULL default '0',
  `crusadertechnology` tinyint(4) NOT NULL default '0',
  `vesseltechnology` tinyint(4) NOT NULL default '0',
  `scavengertechnology` tinyint(4) NOT NULL default '0',
  `warriortechnology` tinyint(4) NOT NULL default '0',
  `dragontechnology` tinyint(4) NOT NULL default '0',
  `whispertechnology` tinyint(4) NOT NULL default '0',
  `nemesistechnology` tinyint(4) NOT NULL default '0',
  `carriertechnology` tinyint(4) NOT NULL default '0',
  `detectortechnology` tinyint(4) NOT NULL default '0',
  `corthosiumtechnology` tinyint(4) NOT NULL default '0',
  `spaceshipstechnology` tinyint(4) NOT NULL default '0',
  `tacticstechnology` tinyint(4) NOT NULL default '0',
  `communicationstechnology` tinyint(4) NOT NULL default '0',
  `atomtechnology` tinyint(4) NOT NULL default '0',
  `biotechnology` tinyint(4) NOT NULL default '0',
  `resourcestechnology` tinyint(4) NOT NULL default '0',
  `cryogenictechnology` tinyint(4) NOT NULL default '0',
  `weapontechnology` tinyint(4) NOT NULL default '0',
  `plasmatechnology` tinyint(4) NOT NULL default '0',
  `moleculartechnology` tinyint(4) NOT NULL default '0',
  `nanotechnology` tinyint(4) NOT NULL default '0',
  `databankstechnology` tinyint(4) NOT NULL default '0',
  `warptechnology` tinyint(4) NOT NULL default '0',
  `mutationtechnology` tinyint(4) NOT NULL default '0',
  `procreationtechnology` tinyint(4) NOT NULL default '0',
  `regenerationtechnology` tinyint(4) NOT NULL default '0',
  `hyperwavestechnology` tinyint(4) NOT NULL default '0',
  `dimensionstechnology` tinyint(4) NOT NULL default '0',
  `uimtechnology` tinyint(4) NOT NULL default '0',
  `teleportationtechnology` tinyint(4) NOT NULL default '0',
  `collectivetechnology` tinyint(4) NOT NULL default '0',
  `crystaltechnology` tinyint(4) NOT NULL default '0',
  `advancedweapontechnology` tinyint(4) NOT NULL default '0',
  `energycentertechnology` tinyint(4) NOT NULL default '0',
  `metalcentertechnology` tinyint(4) NOT NULL default '0',
  `urancentertechnology` tinyint(4) NOT NULL default '0',
  `foodcentertechnology` tinyint(4) NOT NULL default '0',
  `crystalcentertechnology` tinyint(4) NOT NULL default '0',
  `advancedscanningtechnology` tinyint(4) NOT NULL default '0',
  `militarytechnology` tinyint(4) NOT NULL default '0',
  `satellitestechnology` tinyint(4) NOT NULL default '0',
  `defensivetechnology` tinyint(4) NOT NULL default '0',
  `offensivetechnology` tinyint(4) NOT NULL default '0',
  `trontechnology` tinyint(4) NOT NULL default '0',
  `managementtechnology` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `owner` (`owner`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_config`
--

CREATE TABLE `galaxy_config` (
  `config_key` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`config_key`)
);

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_descriptions`
--

CREATE TABLE `galaxy_descriptions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `locale` varchar(16) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_equipment`
--

CREATE TABLE `galaxy_equipment` (
  `id` int(11) NOT NULL auto_increment,
  `owner` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `type` varchar(16) default NULL,
  `class` enum('','gold','silver','bronze') NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `damaged` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `levelmax` int(11) NOT NULL default '0',
  `price` int(11) NOT NULL default '0',
  `distance` int(11) NOT NULL default '0',
  `req_level` int(11) NOT NULL default '0',
  `req_strength` int(11) NOT NULL default '0',
  `req_agility` int(11) NOT NULL default '0',
  `req_psi` int(11) NOT NULL default '0',
  `req_force` int(11) NOT NULL default '0',
  `req_mp` int(11) NOT NULL default '0',
  `req_hp` int(11) NOT NULL default '0',
  `req_intellect` int(11) NOT NULL default '0',
  `req_knowledge` int(11) NOT NULL default '0',
  `req_pocketstealing` int(11) NOT NULL default '0',
  `req_hacking` int(11) NOT NULL default '0',
  `req_alcoholism` int(11) NOT NULL default '0',
  `weight` float NOT NULL default '0',
  `min` float NOT NULL default '0',
  `max` float NOT NULL default '0',
  `armor` float NOT NULL default '0',
  `hit` int(11) NOT NULL default '0',
  `criticalhit` int(11) NOT NULL default '0',
  `critical` float NOT NULL default '0',
  `block` int(11) NOT NULL default '0',
  `speed` int(11) NOT NULL default '0',
  `deaf` int(11) NOT NULL default '0',
  `hide` int(11) NOT NULL default '0',
  `protection` int(11) NOT NULL default '0',
  `hp` int(11) NOT NULL default '0',
  `mp` int(11) NOT NULL default '0',
  `parameters` varchar(32) NOT NULL default '',
  `use` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_events`
--

CREATE TABLE `galaxy_events` (
  `event_timestamp` varchar(14) NOT NULL,
  `event_from` varchar(32) NOT NULL,
  `event_subject` varchar(32) NOT NULL,
  `event_message` varchar(255) NOT NULL,
  KEY `event_timestamp` (`event_timestamp`)
);

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_exploration`
--

CREATE TABLE `galaxy_exploration` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `type` enum('planet','galaxy') NOT NULL default 'planet',
  `target` varchar(16) NOT NULL default '',
  `begin` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `colonists` int(11) NOT NULL default '0',
  `scientists` int(11) NOT NULL default '0',
  `soldiers` int(11) NOT NULL default '0',
  `vessels` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_groups`
--

CREATE TABLE `galaxy_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `created` date NOT NULL default '0000-00-00',
  `description` varchar(240) NOT NULL default '',
  `avatar` varchar(128) NOT NULL default '',
  `owner` varchar(32) NOT NULL default '',
  `co1` varchar(32) NOT NULL default '',
  `co2` varchar(32) NOT NULL default '',
  `credits` int(11) NOT NULL default '0',
  `crystals` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `level` float NOT NULL default '1',
  `tax` int(11) NOT NULL default '15',
  `attack` int(11) NOT NULL default '500',
  `defense` int(11) NOT NULL default '500',
  `www` varchar(160) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_items`
--

CREATE TABLE `galaxy_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `type` varchar(16) default NULL,
  `class` enum('','gold','silver','bronze') NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `price` int(11) NOT NULL default '0',
  `distance` int(11) NOT NULL default '0',
  `req_level` int(11) NOT NULL default '0',
  `req_strength` int(11) NOT NULL default '0',
  `req_agility` int(11) NOT NULL default '0',
  `req_psi` int(11) NOT NULL default '0',
  `req_force` int(11) NOT NULL default '0',
  `req_mp` int(11) NOT NULL default '0',
  `req_hp` int(11) NOT NULL default '0',
  `req_intellect` int(11) NOT NULL default '0',
  `req_knowledge` int(11) NOT NULL default '0',
  `req_pocketstealing` int(11) NOT NULL default '0',
  `req_hacking` int(11) NOT NULL default '0',
  `req_alcoholism` int(11) NOT NULL default '0',
  `weight` float NOT NULL default '0',
  `min` float NOT NULL default '0',
  `max` float NOT NULL default '0',
  `armor` float NOT NULL default '0',
  `hit` int(11) NOT NULL default '0',
  `criticalhit` int(11) NOT NULL default '0',
  `critical` float NOT NULL default '0',
  `block` int(11) NOT NULL default '0',
  `speed` int(11) NOT NULL default '0',
  `deaf` int(11) NOT NULL default '0',
  `hide` int(11) NOT NULL default '0',
  `protection` int(11) NOT NULL default '0',
  `hp` int(11) NOT NULL default '0',
  `mp` int(11) NOT NULL default '0',
  `parameters` varchar(32) NOT NULL default '',
  `use` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
)  COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_markets`
--

CREATE TABLE `galaxy_markets` (
  `position` varchar(32) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `reputation` int(11) NOT NULL default '0',
  `energybuyaverage` float NOT NULL default '0',
  `energybuy` float NOT NULL default '0',
  `energysellaverage` float NOT NULL default '0',
  `energysell` float NOT NULL default '0',
  `siliconbuyaverage` float NOT NULL default '0',
  `siliconbuy` float NOT NULL default '0',
  `siliconsellaverage` float NOT NULL default '0',
  `siliconsell` float NOT NULL default '0',
  `metalbuyaverage` float NOT NULL default '0',
  `metalbuy` float NOT NULL default '0',
  `metalsellaverage` float NOT NULL default '0',
  `metalsell` float NOT NULL default '0',
  `uranbuyaverage` float NOT NULL default '0',
  `uranbuy` float NOT NULL default '0',
  `uransellaverage` float NOT NULL default '0',
  `uransell` float NOT NULL default '0',
  `plutoniumbuyaverage` float NOT NULL default '0',
  `plutoniumbuy` float NOT NULL default '0',
  `plutoniumsellaverage` float NOT NULL default '0',
  `plutoniumsell` float NOT NULL default '0',
  `deuteriumbuyaverage` float NOT NULL default '0',
  `deuteriumbuy` float NOT NULL default '0',
  `deuteriumsellaverage` float NOT NULL default '0',
  `deuteriumsell` float NOT NULL default '0',
  `foodbuyaverage` float NOT NULL default '0',
  `foodbuy` float NOT NULL default '0',
  `foodsellaverage` float NOT NULL default '0',
  `foodsell` float NOT NULL default '0',
  `crystalsbuyaverage` float NOT NULL default '0',
  `crystalsbuy` float NOT NULL default '0',
  `crystalssellaverage` float NOT NULL default '0',
  `crystalssell` float NOT NULL default '0',
  PRIMARY KEY  (`position`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_messages`
--

CREATE TABLE `galaxy_messages` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('message','report') NOT NULL default 'message',
  `from` varchar(32) NOT NULL default '',
  `to` varchar(32) NOT NULL default '',
  `subject` varchar(120) NOT NULL default '',
  `message` text NOT NULL,
  `read` tinyint(1) NOT NULL default '0',
  `timestamp` varchar(14) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `to` (`to`),
  KEY `read` (`read`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_news`
--

CREATE TABLE `galaxy_news` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` varchar(14) NOT NULL default '',
  `from` varchar(32) NOT NULL default '',
  `locale` varchar(16) NOT NULL default '',
  `message` text NOT NULL,
  PRIMARY KEY  (`id`)
) COMMENT='news';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_places`
--

CREATE TABLE `galaxy_places` (
  `id` int(11) NOT NULL auto_increment,
  `position` varchar(32) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `parameters` varchar(240) NOT NULL default '',
  `extra` varchar(64) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `reputation` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UI` (`position`,`type`)
)  COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_productions`
--

CREATE TABLE `galaxy_productions` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `begin` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `amount` mediumint(9) NOT NULL default '0',
  `score` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_researches`
--

CREATE TABLE `galaxy_researches` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `begin` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_space`
--
CREATE TABLE `galaxy_space` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(11) NOT NULL default '',
  `type` enum('planet','asteroid','meteor') NOT NULL default 'planet',
  `class` enum('small','medium','big','huge','giant') NOT NULL default 'medium',
  `technology` enum('none','human','tron','ami','cyber','necro','unknown') NOT NULL default 'human',
  `galaxy` varchar(6) NOT NULL default '',
  `system` varchar(11) NOT NULL default '',
  `x` int(11) NOT NULL default '0',
  `y` int(11) NOT NULL default '0',
  `z` int(11) NOT NULL default '0',
  `explored` float NOT NULL default '0',
  `abandoned` int(11) NOT NULL default '0',
  `wind` tinyint(4) NOT NULL default '15',
  `life` int(11) NOT NULL default '30',
  `terrain` varchar(50) NOT NULL default '50',
  `gravity` float NOT NULL default '2.5',
  `moons` int(11) NOT NULL default '0',
  `illumination` int(11) NOT NULL default '15',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UI` (`name`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_stats`
--

CREATE TABLE `galaxy_stats` (
  `key` varchar(32) NOT NULL default '',
  `value` varchar(224) NOT NULL default '',
  PRIMARY KEY  (`key`)
);

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_tales`
--

CREATE TABLE `galaxy_tales` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` timestamp NOT NULL,
  `from` varchar(32) NOT NULL default '',
  `locale` varchar(16) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  `subject` varchar(80) NOT NULL default '',
  `message` text NOT NULL,
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_tips`
--

CREATE TABLE `galaxy_tips` (
  `id` int(11) NOT NULL auto_increment,
  `locale` varchar(16) NOT NULL default '',
  `tip` text NOT NULL,
  PRIMARY KEY  (`id`)
);

--
-- Dumping data for table `galaxy_tips`
--

INSERT INTO `galaxy_tips` (`locale`, `tip`) 
VALUES 
('en','Everyone, even you, can help improving this game with new ideas'),
('pl','Każdy, nawet ty, może pomóc w rozwoju gry swoimi pomysłami'),
('en','If you have colony based on human technology you need people to work. You can hire them at the Mercenary places located on some planets but remember that the hire cost depends on your reputation'),
('pl','Jeśli posiadasz kolonię w technologii ludzi potrzebujesz ich do pracy. Możesz ich nająć w obozach najemników na niektórych planetach, pamiętaj jednak, że koszt najęcia zależy od twojej reputacji'),
('en','Your colony needs energy. It is good first to build some Wind generators or Solar batteries, these buldings give you some energy depend on planet parameters where your colony is located'),
('pl','Twoja kolonia potrzebuje prądu (energii). Dobrze jest najpierw wybudować nieco elektrowni wiatrowych lub baterii słonecznych, dają one energię w zależności od współczynników planety na której znajduje się twoja kolonia'),
('en','The most important thing in creating your colony is to find some sources: uran, metal, etc. To find them you need to explore your planet or space'),
('pl','Najważniejszą rzeczą we wczesnej rozbudowie kolonii jest znalezienie źródeł: metalu, uranu, itp. By je odnaleźć musisz wysyłać wyprawy'),
('en','Instead of building you need to produce some units. There are few kinds of them, i.e. robots that work for you or fighters that fights :-) You need to build a factory to produce anything. The more factories you have the more productions you can have at the same time'),
('pl','Oprócz budynków potrzebujesz jednostek. Jest ich kilka rodzajów, np. roboty, które pracują dla ciebie lub statki wojenne, których potrzebujesz do wojny. Musisz wybudować fabrykę by cokolwiek produkować. Im więcej fabryk masz, tym więcej produkcji możesz rozpocząć w jednym czasie'),
('en','Building structures goes faster if your colony has large amount of robots. To produce robots faster you have to build more factories'),
('pl','Budowy konstrukcji trwają krócej jeśli twoja kolonia jest wyposażona w dużą ilość robotów. Produkcja robotów z kolei trwa krócej jeślli ma się dużo fabryk'),
('en','To find more sources you need to explore your planet or space. Other way to gain some sources for your colony is to work in mines or thoria'),
('pl','By znaleźć źródła które nadają się do eksploatacji musisz wysyłać wyprawy. Inny sposób to praca w kopalniach lub thorii'),
('en','Your colony and your hero are two different thing. You can travel around universe and still have control on your colony which is located on some planet that you have chosen'),
('pl','Twoja kolonia i twój bohater to dwie różne rzeczy. Możesz podróżować po wszechświecie i wciąż kontrolować rozwój kolonii, która znajduje się na planecie która została wybrana podczas jej tworzenia'),
('en','If you have any questions you can use chat, normally it is full of some strange sayings but if you ask most likely someone will answer'),
('pl','Jeśli masz jakieś pytania, zawsze możesz użyć czata. Jest on zazwyczaj wypełniony dziwnymi wypowiedziami, jednak jeśli zadasz pytanie, w większości wypadków, ktoś na nie odpowie'),
('en','Forum is the first place you should visit to begin playing in our game'),
('pl','Forum jest pierwszym miejscem które należy odwiedzić przed rozpoczęciem gry'),
('en','Your score is a rating that gives you higher position in high scores list but you must remember that the more score you have the stronger players can attack your colony!'),
('pl','Twoje punkty dają wyższą pozycję w wynikach, jednak musisz pamiętać, że im więcej punktów posiadasz, tym silniejsi gracze będą mogli atakować twą kolonię'),
('en','Sending scientists to exploration increases possibility to find valuable sources'),
('pl','Wysłanie naukowców na wyprawy zwiększa możliwość znalezienia cennych źródeł'),
('en','If you don''t care of food for your people they may die and your reputation will go down'),
('pl','Jeśli nie zadbasz o dostępne jedzenie dla ludzi w kolonii, mogą oni umrzeć z głodu, tym samym spadnie twoja reputacja'),
('en','Always check your colony bilances. It is very valuable information'),
('pl','Zawsze sprawdzaj bilans zużycia surowców swojej kolonii. Jest to naprawde cenna informacja'),
('en','Your hero attributes can be checked in whois section where you can go by clicking on your login'),
('pl','Współczynniki swojej postaci możesz zobaczyć w oknie informacji o graczu do którego możesz przejść klikając po prostu na swój login'),
('en','Your hero can gain some new abilities during game. You won''t see them unless you gain at least one point to them. If you already have you will have ability to distribute skillpoints to them'),
('pl','Twoja postać może zyskać nowe zdolności/atrybuty, nie zobaczysz ich jednak dopóki nie zdobędziesz przynajmniej jednego punktu w danej zdolności. Kiedy już postać uzyska nową zdolność, będzie można przeznaczyć na nią punkty zdolności SP')
;

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_transfers`
--

CREATE TABLE `galaxy_transfers` (
  `id` int(11) NOT NULL auto_increment,
  `from` varchar(32) NOT NULL default '',
  `to` varchar(32) NOT NULL default '',
  `begin` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `metal` int(11) NOT NULL default '0',
  `uran` int(11) NOT NULL default '0',
  `food` int(11) NOT NULL default '0',
  `crystals` int(11) NOT NULL default '0',
  `colonists` int(11) NOT NULL default '0',
  `scientists` int(11) NOT NULL default '0',
  `soldiers` int(11) NOT NULL default '0',
  `bx1` int(11) NOT NULL default '0',
  `bx2` int(11) NOT NULL default '0',
  `bx5` int(11) NOT NULL default '0',
  `bx10` int(11) NOT NULL default '0',
  `hawk` int(11) NOT NULL default '0',
  `crusader` int(11) NOT NULL default '0',
  `warrior` int(11) NOT NULL default '0',
  `dragon` int(11) NOT NULL default '0',
  `nemesis` int(11) NOT NULL default '0',
  `scavenger` int(11) NOT NULL default '0',
  `carrier` int(11) NOT NULL default '0',
  `vessel` int(11) NOT NULL default '0',
  `detector` int(11) NOT NULL default '0',
  `bee` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_universe`
--

CREATE TABLE `galaxy_universe` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(16) NOT NULL default '',
  `x` int(11) NOT NULL default '0',
  `y` int(11) NOT NULL default '0',
  `z` int(11) NOT NULL default '0',
  `type` enum('galaxy','anomaly','blackhole') NOT NULL default 'galaxy',
  `discovered` varchar(10) NOT NULL default '',
  `by` varchar(16) NOT NULL default '',
  `age` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) COMMENT='galaxy';

-- --------------------------------------------------------

--
-- Table structure for table `galaxy_users`
--

CREATE TABLE `galaxy_users` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL default '0',
  `online` varchar(14) NOT NULL default '',
  `login` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `seed` varchar(16) NOT NULL,
  `usergroup` varchar(16) NOT NULL default '',
  `clan` varchar(32) NOT NULL default '',
  `oldclan` varchar(32) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  `gg` varchar(16) NOT NULL default '',
  `ggpublic` tinyint(4) NOT NULL default '0',
  `www` varchar(64) NOT NULL default '',
  `language` varchar(5) NOT NULL default '',
  `style` varchar(16) NOT NULL default '',
  `regid` varchar(12) NOT NULL default '',
  `registered` date default NULL,
  `seen` varchar(14) NOT NULL default '',
  `locked` varchar(14) NOT NULL default '',
  `banned` varchar(14) NOT NULL default '',
  `bancount` int(11) NOT NULL default '0',
  `who` varchar(35) NOT NULL default '',
  `reason` text NOT NULL,
  `injail` int(4) NOT NULL default '0',
  `ip` varchar(64) NOT NULL default '',
  `lastip` varchar(64) NOT NULL default '',
  `soundsoff` tinyint(4) NOT NULL default '0',
  `antispam` tinyint(1) NOT NULL default '0',
  `credits` bigint(20) NOT NULL default '50000',
  `bank` bigint(20) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `reputation` float NOT NULL default '0',
  `exp` varchar(32) NOT NULL default '0',
  `level` int(11) NOT NULL default '1',
  `homeworld` varchar(32) NOT NULL default '',
  `planet` varchar(32) NOT NULL default 'mulahay',
  `destination` varchar(32) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `distance` float NOT NULL default '0',
  `voyaged` float NOT NULL default '0',
  `hp` float NOT NULL default '10',
  `hpmin` mediumint(9) NOT NULL default '25',
  `hpmax` float NOT NULL default '10',
  `hpgain` float NOT NULL default '0.2',
  `hpmodifier` varchar(8) NOT NULL default '',
  `mp` float NOT NULL default '10',
  `mpmax` float NOT NULL default '10',
  `mpgain` float NOT NULL default '0.1',
  `mpmodifier` varchar(8) NOT NULL default '',
  `thicks` mediumint(9) NOT NULL default '0',
  `ship` varchar(16) NOT NULL default 'hawk',
  `strength` float NOT NULL default '1',
  `strengthmodifier` varchar(8) NOT NULL default '',
  `psi` float NOT NULL default '0',
  `force` float NOT NULL default '0',
  `agility` float NOT NULL default '1',
  `agilitymodifier` float NOT NULL default '0',
  `pocketstealing` float NOT NULL default '0',
  `alcoholism` float NOT NULL default '0',
  `intellect` float NOT NULL default '0',
  `knowledge` float NOT NULL default '0',
  `hacking` float NOT NULL default '0',
  `killed` mediumint(9) NOT NULL default '0',
  `killedby` mediumint(9) NOT NULL default '0',
  `lastkilled` varchar(32) NOT NULL default '',
  `lastkilledby` varchar(32) NOT NULL default '',
  `sp` mediumint(9) NOT NULL default '2',
  `avatar` varchar(128) default NULL,
  `clanstatus` varchar(64) NOT NULL default '',
  `dad` varchar(14) NOT NULL default '',
  `flag` int(1) NOT NULL default '0',
  `ban` int(1) NOT NULL default '0',
  `bantime` int(11) NOT NULL default '0',
  `begin` int(11) NOT NULL default '0',
  `race` VARCHAR(16) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) COMMENT='system';

--
-- Dumping data for table `galaxy_config`
--

INSERT INTO `galaxy_config` (`config_key`, `config_value`) VALUES ('Version', '0.5.2') ON DUPLICATE KEY UPDATE `config_value`='0.5.2';
