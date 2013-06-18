<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper Plugin
 *
 * Various helper plugins.
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Date extends Plugin
{
	public function __construct()
	{
		$this->load->helper('date');
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
	public function timestamp()
	{		
		
		return now();
	}
	
	function sgmdate()
	{
		$dateformat = $this->attribute('dateformat');
		$timestamp = $this->attribute('timestamp', now());
		$format = $this->attribute('format');
		$result = '';
		if($format)
		{
			$this->load->helper('language');
			$time = now() - $timestamp;
			if($time > 24*3600)
			{
				$result = gmdate($dateformat, $timestamp);
			}
			elseif ($time > 3600)
			{
				$result = intval($time/3600).lang('hour').lang('before');
			}
			elseif ($time > 60)
			{
				$result = intval($time/60).lang('minute').lang('before');
			}
			elseif ($time > 0)
			{
				$result = $time.lang('second').lang('before');
			}
			else
			{
				$result = lang('now');
			}
		}
		else
		{
			$result = gmdate($dateformat, $timestamp);
		}
		return $result;
	}
	
	
	public function __call($name, $args)
	{
		return FALSE;
	}

}