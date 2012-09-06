CREATE TABLE `translations` (
  `model` varchar(250) CHARACTER SET utf8 NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `language` varchar(5) CHARACTER SET utf8 NOT NULL,
  `field` varchar(250) CHARACTER SET utf8 NOT NULL,
  `value` mediumtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`model`,`foreign_key`,`language`,`field`),
  KEY `model` (`model`,`foreign_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;