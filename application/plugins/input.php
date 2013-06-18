<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper Plugin
 *
 * Various helper plugins.
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Input extends Plugin
{
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
	public function get_post()
	{
		$index = $this->attribute('index');
		$xss_clean = $this->attribute('xss_clean');
		return $this->input->get_post($index, $xss_clean);
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
	public function cookie()
	{
		$index = $this->attribute('index');
		$xss_clean = $this->attribute('xss_clean');
		return $this->input->cookie($index, $xss_clean);
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
	public function server()
	{
		$index = $this->attribute('index');
		$xss_clean = $this->attribute('xss_clean');
		return $this->input->server($index, $xss_clean);
	}
	
	
	
	public function __call($name, $args)
	{
		if(isset($_GET[$name]))
		{
			return $this->input->get($name);
		}
		elseif(isset($_POST[$name]))
		{
			return $this->input->post($name);
		}
		elseif(isset($_SERVER[$name]))
		{
			return $this->input->server($name);
		}
		elseif(isset($_COOKIE[$name]))
		{
			return $this->input->cookie($name);
		}
		else
		{
			return FALSE;
		}
	}

}