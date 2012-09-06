#ORM with translated attributes

## Example introduction

When you configured the Multilanguage module you can directly start to extend your ORM models. For example we use a simple Article model represented through ORM. The article persists the following data:

* `author`
* `crdate`
* `visible`
* `title`
* `text` 

Hereby the `author` is a relation to an user model, the `crdate` is a DATE, the `visible` field is a bool flag, and `title` and `text` are fields with a different content for each language. Now the Article database structure looks like the following:

## Article database structure

	CREATE TABLE `articles` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `crdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `enabled` varchar(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


As you can see there are no `title` and `text` fields directly attached to the articles database structure. This is because the translated values will be stored inside the translations table. This is more elegant than something like this:

## What we want to avoid
	
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


## The Article model definition

To declare the `title` and `text` fields, the `$_translated_fields` array must be defined inside the Article model like the following: 

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
	
[!!] to enable and configure the languages, please look at the [the configuration page](configuration)

## Getter / Setter

Now you can simple access each value: 

	//setter
	$article->enabled = TRUE:
	$article->title_de = 'Musik 2012';
	$article->title_en = 'Music 2012';
	$article->save();
	
	//getter
	echo $article->title_de;
	
	//current language feature:
	echo $article->title;
	
	
[!!] to use the current language feature, it is necessary to set the ` I18n::$lang`as described in the [template example](examples/template)
