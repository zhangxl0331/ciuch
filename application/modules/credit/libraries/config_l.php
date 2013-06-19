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
class Config_l
{		

	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model(array('config/config_m', 'config/data_m'));
	}
	
	public function __get($var)
	{
		$method = strtolower(preg_replace('/(_l|_lib)?$/', '', get_class($this)));
	
		if (is_callable(array($this, $method)))
		{
			$callback = call_user_func(array($this, $method));
			if(isset($callback[$var]))
			{
				return $callback[$var];
			}
		}
	
		return FALSE;
	}
	
	public function config()
	{
		$return = array();
		
		$cachekey = 'config';
		if( ! $return = $this->CI->cache->get($cachekey))
		{
			if($dbresult = $this->CI->config_m->get_all())
			{
				foreach ($dbresult as $value)
				{
					if($value->var == 'privacy')
					{
						$value->datavalue = empty($value->datavalue)?array():unserialize($value->datavalue);
					}
						
					$return[$value->var] = $value->datavalue;				
				}
				$this->CI->cache->save($cachekey, $return);
			}			
		}
		
		if(empty($return['sitekey']))
		{
			$return['sitekey'] = '';
		}
		
		if(empty($return['login_action']))
		{
			$return['login_action'] = md5('login'.md5($return['sitekey']));
		}
		
		if(empty($return['register_action']))
		{
			$return['register_action'] = md5('register'.md5($return['sitekey']));
		}
		
		if(empty($return['template'])) 
		{
			$return['template'] = 'default';
		}
		
		if(empty($return['onlinehold']) OR intval($return['onlinehold']) < 300)
		{
			$return['onlinehold'] = 300;
		}
		
		return $return;
	}
	
	public function data_get($var)
	{
		$cachekey = 'data_'.$var;
		if($return = $this->CI->cache->get($cachekey))
		{
			return $return;
		}
		if($data = $this->CI->data_m->get_row_array_by('var', $var))
		{
			$return = unserialize($data['datavalue']);
			$this->CI->cache->save($cachekey, $return);
			return $return;
		
		}
		
		return FALSE;
	}
	
	

}

/* End of file Settings.php */