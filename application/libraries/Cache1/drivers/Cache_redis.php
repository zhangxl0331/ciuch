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
 * CodeIgniter Memcached Caching Class 
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link		
 */

class CI_Cache_redis extends CI_Driver {

	private $_redis;	// Holds the memcached object

	protected $_redis_conf 	= array(
					'default' => array(
						'default_host'		=> '127.0.0.1',
						'default_port'		=> 6379,
						'default_timeout'	=> '1'
					)
				);

	// ------------------------------------------------------------------------	

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */	
	public function get($id)
	{	
		$data = $this->_redis->get($id);
		
		return $data ? unserialize($data) : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Save
	 *
	 * @param 	string		unique identifier
	 * @param 	mixed		data being cached
	 * @param 	int			time to live
	 * @return 	boolean 	true on success, false on failure
	 */
	public function save($id, $data, $ttl = 60)
	{
		$this->_redis->set($id, serialize($data));
		if($ttl>0)
		{
			$this->_redis->expire($id, $ttl);
		}
		
		return $this->_redis->exists($id);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		key to be deleted.
	 * @return 	boolean 	true on success, false on failure
	 */
	public function delete($id)
	{
		return $this->_redis->delete($id);
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */
	public function clean()
	{
		return $this->_redis->flushDB();
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @param 	null		type not supported in memcached
	 * @return 	mixed 		array on success, false on failure
	 */
	public function cache_info($type = NULL)
	{
		return $this->_redis->info();
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		FALSE on failure, array on success.
	 */
	public function get_metadata($id)
	{
		$stored = $this->_redis->get($id);

		if (count($stored) !== 3)
		{
			return FALSE;
		}

		list($data, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}

	// ------------------------------------------------------------------------

	/**
	 * Setup memcached.
	 */
	private function _setup_redis()
	{
		// Try to load memcached server info from the config file.
		$CI =& get_instance();
		if ($CI->config->load('redis', TRUE, TRUE))
		{
			if (is_array($CI->config->config['redis']))
			{
				$this->_redis_conf = NULL;

				foreach ($CI->config->config['redis'] as $name => $conf)
				{
					$this->_redis_conf[$name] = $conf;
				}				
			}			
		}
		
		if(file_exists($filepath = APPPATH.'third_party/Predis/Autoloader.php'))
		{
			if ( ! class_exists('Predis\Autoloader'))
			{
				include $filepath;
				Predis\Autoloader::register();
				$this->_redis = new Predis\Client($this->_redis_conf);
			}
		}
		else 
		{
			$this->_redis = new Redis();
			foreach ($this->_redis_conf as $name => $cache_server)
			{
				if ( ! array_key_exists('host', $cache_server))
				{
					$cache_server['host'] = $this->_redis_conf['default']['default_host'];
				}
		
				if ( ! array_key_exists('port', $cache_server))
				{
					$cache_server['port'] = $this->_redis_conf['default']['default_port'];
				}
		
				if ( ! array_key_exists('timeout', $cache_server))
				{
					$cache_server['timeout'] = $this->_redis_conf['default']['default_timeout'];
				}
	
				$this->_redis->connect(
					$cache_server['host'], $cache_server['port'], $cache_server['timeout']
				);	
			}
		}	
	}

	// ------------------------------------------------------------------------


	/**
	 * Is supported
	 *
	 * Returns FALSE if memcached is not supported on the system.
	 * If it is, we setup the memcached object & return TRUE
	 */
	public function is_supported()
	{
		if ( ! file_exists(APPPATH.'third_party/Predis/Autoloader.php') AND !extension_loaded('redis'))
		{
			log_message('error', 'The Redis Extension must be loaded to use Redis Cache.');
			
			return FALSE;
		}
		
		$this->_setup_redis();
		return TRUE;
	}

	// ------------------------------------------------------------------------

}
// End Class

/* End of file Cache_memcached.php */
/* Location: ./system/libraries/Cache/drivers/Cache_memcached.php */