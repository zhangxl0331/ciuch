<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Events
 *
 * A simple events system for CodeIgniter.
 *
 * @version		1.0
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @author		Eric Barnes <http://ericlbarnes.com>
 * @license		Apache License v2.0
 * @copyright	2010 Dan Horrigan
 * @package		PyroCMS\Core\Libraries
 */

/**
 * Events Library
 */
class Events
{

	/**
	 * An array of listeners
	 * 
	 * @var	array
	 */
	protected static $_listeners = array();

	/**
	 * Constructor
	 * 
	 * Load up the modules. 
	 */
	public function __construct()
	{
		self::_load_modules();	
	}

	/**
	 * Load Modules
	 *
	 * Loads all active modules
	 */
	private static function _load_modules()
	{	
		//get all the modules
		$results = array();
		$ci = &get_instance();
		$locations = $ci->config->item('modules_locations');
		foreach($locations as $key=>$value)
		{
			$is_core = FALSE;
			if($key == APPPATH.'modules/')
			{
				$is_core = TRUE;
			}
			if($dh = opendir($key))
			{
				while (($file = readdir($dh)) !== false)
				{
					if( is_dir($key.$file) && $file != '.' && $file != '..')
					{
						$results[] = array('slug' => $file, 'is_core' => $is_core);
					}
				}
				closedir($dh);
			}
		}	
		
		if (!$results)
		{
			return FALSE;
		}

		foreach ($results as $row)
		{
			// This does not have a valid details.php file! :o
			if (!$details_class = self::_spawn_class($row['slug'], $row['is_core']))
			{
				continue;
			}
		}

		return TRUE;
	}

	/**
	 * Spawn Class
	 *
	 * Checks to see if a events.php exists and returns a class
	 * 
	 * @param string $slug The folder name of the module.
	 * @param boolean $is_core Whether the module is a core module.
	 * @return object|boolean 
	 */
	private static function _spawn_class($slug, $is_core = FALSE)
	{
		$path = $is_core ? APPPATH : ADDONPATH;

		// Before we can install anything we need to know some details about the module
		$events_file = $path.'modules/'.$slug.'/events'.EXT;

		// Check the details file exists
		if (!is_file($events_file))
		{
			return FALSE;
		}

		// Sweet, include the file
		include_once $events_file;

		// Now call the details class
		$class = 'Events_'.ucfirst(strtolower($slug));

		// Now we need to talk to it
		return class_exists($class) ? new $class : FALSE;
	}

	/**
	 * Register
	 *
	 * Registers a Callback for a given event
	 *
	 * @param string $event The name of the event.
	 * @param array $callback The callback for the event.
	 */
	public static function register($event, array $callback, $order='')
	{
		$key = get_class($callback[0]).'::'.$callback[1];
		$order = intval($order) ? intval($order) : 100;
		self::$_listeners[$event][str_pad($order, 3, 0, STR_PAD_LEFT).'_'.$key] = $callback;
		ksort(self::$_listeners[$event]);
		log_message('debug', 'Events::register() - Registered "'.$key.' with event "'.$event.'"');
	}

	/**
	 * Triggers an event and returns the results.
	 * 
	 * The results can be returned in the following formats:
	 *  - 'array'
	 *  - 'json'
	 *  - 'serialized'
	 *  - 'string'
	 *
	 * @param string $event The name of the event
	 * @param string $data Any data that is to be passed to the listener
	 * @param string $return_type The return type
	 * @return string|array The return of the listeners, in the return type
	 */
	public static function trigger($event, $data = '', $return_type = 'string')
	{
		log_message('debug', 'Events::trigger() - Triggering event "'.$event.'"');

		$calls = array();

		if (self::has_listeners($event))
		{
			foreach (self::$_listeners[$event] as $key => $listener)
			{
				if (is_callable($listener))
				{
					$order = substr($key, 4);
					$calls[$order] = call_user_func($listener, $data);
				}
			}
		}

		return self::_format_return($calls, $return_type);
	}

	/**
	 * Format Return
	 *
	 * Formats the return in the given type
	 *
	 * @param array $calls The array of returns
	 * @param string $return_type The return type
	 * @return array|null The formatted return
	 */
	protected static function _format_return(array $calls, $return_type)
	{
		log_message('debug', 'Events::_format_return() - Formating calls in type "'.$return_type.'"');

		switch ($return_type)
		{
			case 'array':
				return $calls;
				break;
			case 'json':
				return json_encode($calls);
				break;
			case 'serialized':
				return serialize($calls);
				break;
			case 'string':
				$str = '';
				foreach ($calls as $call)
				{
					$str .= $call;
				}
				return $str;
				break;
			default:
				return $calls;
				break;
		}

		// Does not do anything, so send NULL. FALSE would suggest an error
		return NULL;
	}

	/**
	 *
	 * @access	public
	 * @param	string	
	 * @return	bool	
	 */

	/**
	 * Checks if the event has listeners
	 *
	 * @param string $event The name of the event
	 * @return boolean Whether the event has listeners
	 */
	public static function has_listeners($event)
	{
		log_message('debug', 'Events::has_listeners() - Checking if event "'.$event.'" has listeners.');

		if (isset(self::$_listeners[$event]) AND count(self::$_listeners[$event]) > 0)
		{
			return TRUE;
		}

		return FALSE;
	}
	
	/**
	 *
	 * @access	public
	 * @param	string
	 * @return	array|bool
	 */
	
	/**
	 * Lists listeners if the event has listeners
	 *
	 * @param string $event The name of the event
	 * @return boolean Whether the event does not has listeners
	 */
	public static function event_listeners($event)
	{
		log_message('debug', 'Events::list_listeners() - Listing listeners if event "'.$event.'" has listeners.');
		

		if (isset(self::$_listeners[$event]) AND count(self::$_listeners[$event]) > 0)
		{
			ksort(self::$_listeners[$event]);
			return array_keys(self::$_listeners[$event]);
		}

		return FALSE;
	}
	
	/**
	 * Lists all the events and listeners
	 *
	 * @param string $event The name of the event
	 * @return boolean Whether the event does not has listeners
	 */
	 public static function list_listeners()
	{
		log_message('debug', 'Events::list_listeners() - Listing all the events and listeners.');
	
		if ( ! self::$_listeners)
		{
			return FALSE;
		}
		
		$result = array();
		foreach (self::$_listeners as $key => $value)
		{
			$event = self::event_listeners($key);
			$module = end(explode('_', current(explode('::', $event[0]))));
			$result[strtolower($module)][$key] = $event;
		}
	
		return $result;
	}
	
	/**
	 * Lists all the events and listeners of the module
	 *
	 * @param string $event The name of the event
	 * @return boolean Whether the event does not has listeners
	 */
	public static function module_listeners($module)
	{
		log_message('debug', 'Events::module_listeners() - Listing all the events and listeners of the module.');
	
		if ( ! self::$_listeners)
		{
			return FALSE;
		}
	
		$result = array();
		foreach (self::$_listeners as $key => $value)
		{
			$event = self::event_listeners($key);
			$module = end(explode('_', current(explode('::', $event[0]))));
			if(strnatcasecmp($module, end(explode('_', current(explode('::', $event[0]))))) == 0)
			{
				$result[$key] = $event;
			}
		}
	
		return $result;
	}

}