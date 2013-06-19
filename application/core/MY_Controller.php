<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH."third_party/MX/Controller.php";

/**
 * Controller Controller
 * --------------------------------------
 * Author       : $Author$
 * Revision     : $Revision$
 * Date         : $Date$
 * Position     : $HeadURL$
 *
 */

class MY_Controller extends MX_Controller {
	  
	function __construct()
	{
		parent::__construct();
	    
		$this->benchmark->mark('my_controller_start');		
		
		$caches = Events::trigger('updatecache', array(), 'array');
		foreach($caches as $key=>$value)
		{
			$uch[strtolower(preg_replace('/Events_(.*)::.*/', '\\1', $key))] = $value;
		}		
				
		$this->space_m->checkauth();
		
// 		$this->getuserapp();

		if($uch['config']['seccode_login'])
		{
			if($uch['config']['questionmode'])
			{
				$spams = $this->cache->get('spam');
				$this->load->vars('spam', $spams[mt_rand(0, max(count($spams)-1, 0))]);
			}
			else
			{
				$captcha = create_captcha(array(
						'img_path' => APPPATH.'cache/captcha/',
						'img_url' => base_url('application/cache/captcha/')
				));
				$this->load->vars('captcha', $captcha);
			}			
		}
			
		$this->load->vars(array('uch'=>$uch));
		Events::trigger('checkclose');
		
		
		$this->benchmark->mark('my_controller_end');
	}
	
	// end function_cache.php
}