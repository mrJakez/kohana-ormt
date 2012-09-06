# Multilanguage

Kohana 3.x includes a powerful Object Relational Mapping (ORM) module that uses the active record pattern and database introspection to determine a model's column information. ORM is integrated tightly with the [Validation] library.

But if the ORM module is used for a Model with translated attributes it becomes a litte bit confusing. Therefore the Multilanguage module provides ORM Multilanguage support.

With the help of Multilanguage the different attributes wouldn't stored directly in the Model-specific table. They are stored inside a flat translations-table. Thus it is very easy to add additional languages Without changing each model and modify the database. 

## Getting started

Before we use Multilanguage, we must enable the modules required

	Kohana::modules(array(
		...
		'multilanguage' => MODPATH.'multilanguage',
		'orm' => MODPATH.'orm',
		...
	));

[!!] The ORM module is requried for the Multilanguage module to work. Because of the Cascading Filesystem the Multilanguage module must be loaded before the ORM module

Don't forget to create the translations table.

	CREATE TABLE `translations` (
	  `model` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `foreign_key` int(11) NOT NULL,
	  `language` varchar(5) CHARACTER SET utf8 NOT NULL,
	  `field` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `value` mediumtext CHARACTER SET utf8 NOT NULL,
	  PRIMARY KEY (`model`,`foreign_key`,`language`,`field`),
	  KEY `model` (`model`,`foreign_key`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;