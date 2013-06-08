<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Plugin
 *
 * Display theme templates
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Template extends Plugin
{
	/**
	 * Return template variables
	 * Example: {{ template:title }}
	 * 
	 * @param type $foo
	 * @param type $arguments
	 * @return type 
	 */
	public function __call($foo, $arguments)
	{
		$data = & $this->load->_ci_cached_vars;

		return isset($data['template'][$foo]) ? $data['template'][$foo] : NULL;
	}

}