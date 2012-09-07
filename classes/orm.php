<?php defined('SYSPATH') or die('No direct script access.');


class ORM extends Kohana_ORM
{
	/**
	 * availible languages - thus TORM can extend the ORM object
	 * @var array
	 */
	protected $_lang;
	
	/**
	 * seperator between fieldname and language
	 * @var string
	 */
	protected $_seperator;
	
	
	/**
	 * Extends the ORM constructor to add the translated fields to the _object
	 * array. This is needed for the ORM __get() and __set() magic.
	 *
	 * @return void
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (!property_exists($this, '_translated_fields')) 
		{
			return;
		}

		$this->_seperator = Kohana::$config->load('multilanguage')->torm_seperator;
		$this->_lang = Kohana::$config->load('multilanguage')->language_key_mapping;
		
		foreach ($this->_translated_fields as $tfield)
		{
			foreach ($this->_lang as $lang)
			{
				$this->_object[$tfield . $this->_seperator . $lang] = '';
			}
		}
	}
	
	
	/**
	 * Retruns the translated field content, if $column is in the _translated_fields-Array.
	 *
	 * @param   string $column Column name
	 * @return  mixed
	 */
	public function __get($column)
	{
		if (!property_exists($this, '_translated_fields')) 
		{
			return parent::__get($column);
		}
		
		$lang = $this->get_language($column);
		$field = $this->get_field($column);
		
		if (!$lang)
		{
			if (!in_array($column, $this->_translated_fields))
			{
				return parent::__get($column);
			}
			
			// per default, if a TORM field is selected without ISO 639-1 language key,
			// the value for the current language will returned.
			$lang = Multilanguage::current();
			$field = $column;
		}
		
		$query = DB::query(Database::SELECT, 'SELECT value FROM translations WHERE model = :model AND language = :lang AND field = :field AND foreign_key = :foreignkey');
		$query->parameters(array(
			':lang'			=> $lang,
			':model'		=> $this->_table_name,
			':field'		=> $field,	
			':foreignkey'	=> $this->id,
		));
			
		$res = $query->execute();
		return $res->get('value');
	}
	
	
	
	/**
	 * Insert new translation into the database
	 * @param  Validation $validation Validation object
	 * @return ORM
	 */
	public function create(Validation $validation = NULL)
	{
		if (!property_exists($this, '_translated_fields')) 
		{
			return parent::create($validation);	
		}
		
		$translated_object = array();
		
		foreach ($this->_translated_fields as $tfield)
		{
			foreach ($this->_lang as $lang)
			{
				if (array_key_exists($tfield . $this->_seperator . $lang, $this->_changed))
				{
					unset($this->_changed[$tfield . $this->_seperator . $lang]);
					$translated_object[$tfield . $this->_seperator . $lang] = $this->_object[$tfield . '_' . $lang];
				}
			
				unset($this->_object[$tfield . $this->_seperator . $lang]);
			}
		}		
		
		$tmp = parent::create($validation);		
		foreach ($translated_object as $column => $value)
		{
			$lang = $this->get_language($column);
			$field = $this->get_field($column);
			$query = DB::insert('translations', array('model', 'language', 'field', 'foreign_key', 'value'))->values(array($this->_table_name, $lang, $field, $this->id, $value));
			
			$query->execute($this->_db);
		}
		
		return $tmp;
	}
	
	
	
	/**
	 * Updates the translations
	 *
	 * @chainable
	 * @param  Validation $validation Validation object
	 * @return ORM
	 */
	public function update(Validation $validation = NULL)
	{
		if (!property_exists($this, '_translated_fields')) 
		{
			return parent::update($validation);
		}
		
		foreach ($this->_translated_fields as $tfield)
		{
			foreach ($this->_lang as $lang)
			{
				if (array_key_exists($tfield . $this->_seperator . $lang, $this->_changed))
				{
					unset($this->_changed[$tfield . $this->_seperator . $lang]);
					$value = $this->_object[$tfield . $this->_seperator . $lang];

					
					//delete & insert is less expensive than search AND (update or insert)
					DB::delete('translations')
						->where('model', '=', $this->_table_name)->where('language', '=', $lang)->where('field', '=', $tfield)->where('foreign_key', '=', $this->id)
						->execute($this->_db);	
					
					if ($value)
					{
						DB::insert('translations', array('model', 'language', 'field', 'foreign_key', 'value'))->values(array($this->_table_name, $lang, $tfield, $this->id, $value))->execute($this->_db);
					}
				}
			
				unset($this->_object[$tfield . $this->_seperator . $lang]);
			}
		}	

		return parent::update($validation);
	}
	
	
	/**
	 * Deletes the attached translations
	 *
	 * @chainable
	 * @return ORM
	 */
	public function delete()
	{
		$id = $this->id;
		
		//to call the exeption
		$tmp = parent::delete();
		
		DB::delete('translations')
				->where('model', '=', $this->_table_name)->where('foreign_key', '=', $id)
				->execute($this->_db);				

		return $tmp;
	}
	
	
	/**
	 * get the field name for a language-merged column
	 *
	 * @param   string $column Column name
	 * @return	string
	 */
	protected function get_field($column)
	{
		return substr($column, 0, -3);
	}
	
	
	/**
	 * get the language (ISO 639-1) name for a language-merged column
	 *
	 * @param   string $column Column name
	 * @return	string
	 */
	protected function get_language($column)
	{
		foreach ($this->_lang as $lang)
		{ 
			if (substr($column, -3) === $this->_seperator.$lang)
			{
				return $lang;
			}
		}
		
		return FALSE;
	}
}