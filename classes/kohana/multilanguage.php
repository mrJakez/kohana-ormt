<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Multilanguage
{
	public static function url_keys()
	{
		$keys = array();

		foreach (Kohana::$config->load('multilanguage')->language_key_mapping as $urlkey => $lang)
		{
			$keys[] = $urlkey;
		}

		return '('.implode('|', $keys).')';
	}
	
	
	public static function default_language()
	{		
		return Multilanguage::get_urlkey(Kohana::$config->load('multilanguage')->default_language);
	}
	
	
	//returns the current language
	public static function current()
	{
		$urllang = Request::initial()->param('language');				
		return Kohana::$config->load('multilanguage')->language_key_mapping[$urllang];
	}
	
	
	public static function get_urlkey($lang)
	{
		return array_search($lang, Kohana::$config->load('multilanguage')->language_key_mapping);
	}
}