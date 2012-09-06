kohana-multilanguage
====================

With the help of Multilanguage the different attributes wouldn't stored directly in the Model-specific table. They are stored inside a flat translations-table. Thus it is very easy to add additional languages Without changing each model and modify the database. 

## Getting started

### Enable Module

Before we use Multilanguage, we must enable the modules required
```php
  Kohana::modules(array(
		...
		'multilanguage' => MODPATH.'multilanguage',
		'orm' => MODPATH.'orm',
		...
	));
  ?>
```

The ORM module is requried for the Multilanguage module to work. Because of the Cascading Filesystem the Multilanguage module must be loaded before the ORM module
### create translation table
```sql
	CREATE TABLE `translations` (
	  `model` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `foreign_key` int(11) NOT NULL,
	  `language` varchar(5) CHARACTER SET utf8 NOT NULL,
	  `field` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `value` mediumtext CHARACTER SET utf8 NOT NULL,
	  PRIMARY KEY (`model`,`foreign_key`,`language`,`field`),
	  KEY `model` (`model`,`foreign_key`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### Configuration
```php
  <?php defined('SYSPATH') or die('No direct access allowed.');
	
	return array(
	
		'default_language' => 'de',	
		
		'torm_seperator' => '_',
		
		// enabled languages and the urlkey. URLKEY => ISO 639-1 Language Codes
		'language_key_mapping' => array(
			'german' => 'de',
			'english' => 'en',
		),
	);
```
#### Configuration values

Understanding each of these settings is important.


**default_language**
> If no language key is found in the URI, the route will use this default parameter. The value must be a ISO 639-1 language code

torm_seperator
:	This seperator will bei used fork the ORM language attributes. Thus a attribute can be accessed by $model->name, whereby name has the format: attribute.torm_seperator.languagecode

language_key_mapping
: Here you can enable the different languages and define their url-key. The array key is the url parameter, and the array value is the enabled language. If you don't want to use the url-key feature, simple define it like this:

		'language_key_mapping' => array(
			'de' => 'de',
			'en' => 'en' ,
			...
		),