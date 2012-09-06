#Template example / Best practice

To use the default-value feature, the templates need to be modified. Simple set the `I18n::$lang`to the current Multilanguage value. This also wraps the url-key for the langauge to the ISO 639-1 language code.

	<?php defined('SYSPATH') or die('No direct script access.');
	
	class Controller_Maintemplate extends Controller_Template {
			
		public function before()
		{
		
			// set the current language to I18n. Attention: in I18n::$lang is now stored the  ISO 639-1 language code
			I18n::$lang = Multilanguage::current();		
	
			$this->template = 'templates/main';
			parent::before();
		}
	}
	

# Avoid Dublicated Content

To prevent the the deliver of an page without language parameter, you can redirect this cases to an url containing the default_language url segment.
	
	<?php defined('SYSPATH') or die('No direct script access.');
	
	class Controller_Maintemplate extends Controller_Template {
			
		public function before()
		{
		
			// set the current language to I18n. Attention: in I18n::$lang is now stored the  ISO 639-1 language code
			I18n::$lang = Multilanguage::current();		
	
			$this->template = 'templates/main';
			parent::before();
			

			

			$url = $this->request->uri();
    		
    		$foundLanguage = false;
    		foreach (Kohana::$config->load('multilanguage')->language_key_mapping as $urllang => $lang)
    		{
    			if (strpos($url, $urllang) === 0)
    			{
    				$foundLanguage = true;
    				break;
    			}
    		}
    		
    		if (!$foundLanguage)
    		{
    			Request::current()->redirect(Multilanguage::default_language() . '/' . $url);
    		}
			
		}
	}