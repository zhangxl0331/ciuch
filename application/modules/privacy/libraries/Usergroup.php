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
class Usergroup extends Uch 
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Getter
	 *
	 * Gets the setting value requested
	 *
	 * @param	string	$name
	 */
	public function __get($var)
	{
		if (is_callable(array($this, get_class($this))))
		{
			return call_user_func_array(array($this, get_class($this)), (array)$var);
		}
		
		return FALSE;
	}

}

/* End of file Settings.php */