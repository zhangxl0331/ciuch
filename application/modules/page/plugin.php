<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Page extends Plugin
{

	public function __construct()
	{
		$this->load->model('spam/spam_m');
	}
	/**
	 * Execute whitelisted php functions
	 *
	 * {{ helper:foo parameter1="bar" parameter2="bar" }}
	 * NOTE: the attribute name is irrelevant as only 
	 * the values are concatenated and passed as arguments
	 *
	 */
	public function __call($name, $args)
	{
		
	}
	
	public function question()
	{
		return $this->spam_m->question();
	}
	
	public function captcha()
	{
		return $this->spam_m->captcha();
	}
}