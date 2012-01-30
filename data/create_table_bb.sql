CREATE TABLE IF NOT EXISTS `bb` (
  `buslines_id` int(11) NOT NULL,
  `busstops_id` int(11) NOT NULL,
  PRIMARY KEY (`buslines_id`,`busstops_id`),
  KEY `fk_buslines_has_busstops_buslines` (`buslines_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

