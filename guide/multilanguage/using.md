# Using
## Configuration

When the Multilanguage module is configured like this:

	return array(
	
		'default_language' => 'de',	
		
		'torm_seperator' => '_',
		
		'language_key_mapping' => array(
			'german' => 'de',
			'english' => 'en',
		),
	);
	
And a ORM class has defined the `$_translated_fields` property, this fields will be automatically translated.

	class Model_Article extends ORM {
		protected $_translated_fields = array('title', 'description');
	}

Now you can access and get this special fields with the default ORM features.

## Setter

	$article->title_de = 'Musik 2012';
	$article->title_en = 'Music 2012';
	$article->save();
	
## Getter
	echo $article->title_de;
	
	
## Default Value
It is also simple possible to access the value by:

	echo $article->title;
	
In this special case the Multilanguage module returns the value of the current page. But this feature is only availible if you modify your template a little bit. For further informations look at [the template example](examples/template)