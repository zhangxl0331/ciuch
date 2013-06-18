<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2012 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource	
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */
class MY_Cache extends CI_Cache {
	protected $valid_drivers 	= array(
		'cache_apc', 'cache_file', 'cache_memcached', 'cache_dummy', 'cache_redis'
	);

	protected $_cache_path		= NULL;		// Path of cache files (if file-based cache)
	protected $_adapter			= 'dummy';
	protected $_backup_driver;

	
	// ------------------------------------------------------------------------
	
	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * __get()
	 *
	 * @param 	child
	 * @return 	object
	 */
	public function __get($child)
	{
		if ( ! isset($this->lib_name))
		{
			$this->lib_name = get_class($this);
		}

		// The class will be prefixed with the parent lib
		$child_class = $this->lib_name.'_'.$child;
	
		// Remove the CI_ prefix and lowercase
		$lib_name = ucfirst(strtolower(preg_replace(array('/CI_/', '/'.config_item('subclass_prefix').'/'), '', $this->lib_name)));
		$driver_name = strtolower(preg_replace(array('/CI_/', '/'.config_item('subclass_prefix').'/'), '', $child_class));
		
		if (in_array($driver_name, array_map('strtolower', $this->valid_drivers)))
		{
			// check and see if the driver is in a separate file
			if ( ! class_exists($child_class))
			{
				// check application path first
				foreach (get_instance()->load->get_package_paths(TRUE) as $path)
				{
					// loves me some nesting!
					foreach (array(ucfirst($driver_name), $driver_name) as $class)
					{
						if( ! file_exists($filepath = $path.'libraries/'.$lib_name.'/drivers/'.config_item('subclass_prefix').$class.'.php'))
						{
							$filepath = $path.'libraries/'.$lib_name.'/drivers/'.$class.'.php';
						}

						if (file_exists($filepath))
						{
							include_once $filepath;
							break;
						}
					}
				}				
			}
			
			if (class_exists(config_item('subclass_prefix').$driver_name))
			{
				$child_class = config_item('subclass_prefix').$driver_name;
			}
			elseif (class_exists('CI_'.$driver_name))
			{
				$child_class = 'CI_'.$driver_name;
			}

			// it's a valid driver, but the file simply can't be found
			if ( ! class_exists($child_class))
			{
				log_message('error', "Unable to load the requested driver: ".$child_class);
				show_error("Unable to load the requested driver: ".$child_class);
			}

			$obj = new $child_class;
			$obj->decorate($this);
			$this->$child = $obj;
			if ( ! $obj->is_supported())
			{
				$this->_adapter = $this->_backup_driver;
			}
	
			return $obj;
		}
		else
		{
			// The requested driver isn't valid!
			log_message('error', "Invalid driver requested: ".$child_class);
			show_error("Invalid driver requested: ".$child_class);
		}
		
		
		//$obj = parent::__get($child);

		
	}
	
	// ------------------------------------------------------------------------
	
}
// End Class

/* End of file Cache.php */
/* Location: ./system/libraries/Cache/Cache.php */