kohana-multilanguage
====================

With the help of Multilanguage the translated attributes of a ORM Model wouldn't stored directly in the Model-specific table. 
This as *translated field*  declared attributes are stored inside a flat translations-table. Thus it is very easy to add additional languages Without changing each model and modify the database. 

# What we want to avoid
	
```sql
	CREATE TABLE `articles` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `crdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `enabled` varchar(1) NOT NULL DEFAULT '0',
	  `title_de` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `title_en` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `title_es` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `title_nl` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `text_de` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `text_en` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `text_es` varchar(250) CHARACTER SET utf8 NOT NULL,
	  `text_nl` varchar(250) CHARACTER SET utf8 NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```

## Installation and Configuration

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


**torm_seperator**
> This seperator will bei used fork the ORM language attributes. Thus a attribute can be accessed by $model->name, whereby name has the format: attribute.torm_seperator.languagecode

**language_key_mapping**
> : Here you can enable the different languages and define their url-key. The array key is the url parameter, and the array value is the enabled language. If you don't want to use the url-key feature, simple define it like this:
```php
	'language_key_mapping' => array(
		'de' => 'de',
		'en' => 'en' ,
		...
	);
```


## Using / Examples


### Example introduction

When you configured the Multilanguage module you can directly start to extend your ORM models. For example we use a simple Article model represented through ORM. The article persists the following data:

* `author`
* `crdate`
* `visible`
* `title`
* `text` 

Hereby the `author` is a relation to an user model, the `crdate` is a DATE, the `visible` field is a bool flag, and `title` and `text` are fields with a different content for each language. Now the Article database structure looks like the following:

### Article database structure
```sql
	CREATE TABLE `articles` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `crdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `enabled` varchar(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```

As you can see there are no `title` and `text` fields directly attached to the articles database structure. This is because the translated values will be stored inside the translations table. This is more elegant than something like this:

### The Article model definition

To declare the `title` and `text` fields, the `$_translated_fields` array must be defined inside the Article model like the following: 

```php
	class Model_Article extends ORM
	{
		protected $_has_one = array
		(
		    'author' => array
		    (
		        'model'       => 'user',
		        'foreign_key' => 'author_id',
		    ),
		);
	    
		protected $_translated_fields = array
		(
			'title',
			'text'
		);
	}
```
> to enable and configure the languages, please look at the [the configuration page](configuration)

### Getter / Setter

Now you can simple access each value: 
```php
	//setter
	$article->enabled = TRUE:
	$article->title_de = 'Musik 2012';
	$article->title_en = 'Music 2012';
	$article->save();
	
	//getter
	echo $article->title_de;
	
	//current language feature:
	echo $article->title;
```
	
> to use the current language feature, it is necessary to set the ` I18n::$lang`as described in the [template example](examples/template)
