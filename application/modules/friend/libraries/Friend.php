<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * PyroCMS Settings Library
 *
 * Allows for an easy interface for site settings
 *
 * @author		Dan Horrigan <dan@dhorrigan.com>
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Settings\Libraries
 */
class Friend {

	protected $_ci;
	/**
	 * Settings cache
	 *
	 * @var	array
	 */
	private static $var = '';
	private static $cache = array();
	
	public function __construct()
	{
		$this->_ci = & get_instance();
		$this->_ci->load->model('firend/firend_m');	
	}

	/**
	 * Getter
	 *
	 * Gets the setting value requested
	 *
	 * @param	string	$name
	 */
	public function __get($name)
	{
		return self::get($name);
	}

	/**
	 * Setter
	 *
	 * Sets the setting value requested
	 *
	 * @param	string	$name
	 * @param	string	$value
	 * @return	bool
	 */
	public function __set($name, $value)
	{
		return self::set($name, $value);
	}

	/**
	 * All
	 *
	 * Gets all the settings
	 *
	 * @return	array
	 */
	public function get_space($value, $key='uid', $auto_open=0)
	{
		
	}
	
	/**
	 * Get
	 *
	 * Gets a setting.
	 *
	 * @param	string	$name
	 * @return	bool
	 */
	public static function get($name)
	{
		
	}

}

/* End of file Settings.php */