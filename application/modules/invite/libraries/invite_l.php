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
class Invite_l 
{	
	protected $CI;
	
	public function __construct()
	{
		$this->CI = & get_instance();

		$this->CI->load->model(
			array(
				'invite/invite_m',
			)
		);
		$this->CI->load->helper(
				array(
						'global', 'uch', 'date'
				)
		);
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
	
	public function isinvite($uid, $code)
	{
		if($this->CI->invite_m->get_row_array_by(array('uid'=>$uid, 'code'=>$code, 'fuid'=>0)))
		{
			return TRUE;
		}
		return FALSE;
	}

}

/* End of file Settings.php */