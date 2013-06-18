<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Lang extends Plugin
{

	/**
	 * Load a constant
	 *
	 * Magic method to get a constant or global var
	 *
	 * @return	null|string
	 */
	function __call($name, $data)
	{
		return lang($name);
	}

}