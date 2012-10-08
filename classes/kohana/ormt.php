<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_ORMT
{
	public static function url_keys()
	{
		$keys = array();

		foreach (Kohana::$config->load('ormt')->language_key_mapping as $urlkey => $lang)
		{
			$keys[] = $urlkey;
		}

		return '('.implode('|', $keys).')';
	}
	
	
	public static function default_language()
	{		
		return ORMT::get_urlkey(Kohana::$config->load('ormt')->default_language);
	}
	
	
	//returns the current language
	public static function current()
	{
		$urllang = Request::initial()->param('language');				
		return Kohana::$config->load('ormt')->language_key_mapping[$urllang];
	}
	
	public static function current_key()
	{
		return self::get_urlkey(self::current());
	}
	
	
	public static function get_urlkey($lang)
	{
		return array_search($lang, Kohana::$config->load('ormt')->language_key_mapping);
	}
}
