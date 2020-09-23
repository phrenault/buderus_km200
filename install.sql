CREATE TABLE IF NOT EXISTS `main_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `saved_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `buderus_id` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sub_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_service_id` int(11) NOT NULL,
  `name` varchar(75) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `main_services` VALUES
(1, '/system', 1),
(2, '/heatingCircuits', 1),
(3, '/dhwCircuits', 1),
(4, '/gateway', 1),
(5, '/solarCircuits', 1),
(6, '/recordings', 1),
(7, '/heatSources', 1),
(8, '/notifications', 1);