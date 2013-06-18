<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Router extends Plugin
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
		// A constant
		if (defined(strtoupper($name)))
		{
			return constant(strtoupper($name));
		}
		
		// A global variable ($this->controller etc)
		elseif (isset(get_instance()->$name) AND is_scalar($this->$name))
		{
			return $this->$name;
		}

		return NULL;
	}

	/**
	 * Get the total count of the set.
	 *
	 * Not to be used without a {{ helper:count }}
	 *
	 * Usage:
	 * {{ helper:show_count identifier="files" }}
	 *
	 * 	Outputs:
	 * 	Test -- test
	 * 	Second -- second
	 * 	You have 2 files.
	 *
	 * @return int The total set count.
	 */
	public function module()
	{
		if (method_exists( $this->router, 'fetch_module' ))
		{
			if($lang = $this->attribute('lang'))
			{
				return $this->lang->line($this->router->fetch_module());;
			}
			return $this->router->fetch_module();
		}
		
		return FALSE;
	}
	
	/**
	 * Data
	 *
	 * Loads a theme partial
	 *
	 * Usage:
	 * {{ helper:lang line="foo" }}
	 *
	 * @return string The language string
	 */
	public function rewrite()
	{
		$rws = array();
		$key = $this->attribute('key');
		$value = $this->attribute('value');
		if ($key AND $value) $_GET[$key] = $value;
		ksort($_GET);
		foreach($_GET as $k=>$v)
		{
			$rws[] = $k.'-'.$v;
		}
				
		return implode('-', $rws);
	}
	
}