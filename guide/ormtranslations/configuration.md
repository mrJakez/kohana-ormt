# Configuration

The default config file is located in `MODPATH/multilanguage/config/multilanguage.php`.  You should copy this file to `APPPATH/config/multilanguage.php` and make changes there, in keeping with the [cascading filesystem](../kohana/files).


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
	
# Settings

Understanding each of these settings is important.

default_language
:	If no language key is found in the URI, the route will use this default parameter. The value must be a ISO 639-1 language code

torm_seperator
:	This seperator will bei used fork the ORM language attributes. Thus a attribute can be accessed by $model->name, whereby name has the format: attribute.torm_seperator.languagecode

language_key_mapping
: Here you can enable the different languages and define their url-key. The array key is the url parameter, and the array value is the enabled language. If you don't want to use the url-key feature, simple define it like this:

		'language_key_mapping' => array(
			'de' => 'de',
			'en' => 'en' ,
			...
		),