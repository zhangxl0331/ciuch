<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Config
{
	private $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->helper(array('cookie', 'uch'));
		$this->CI->load->model(array('config/config_m', 'config/data_m'));
        
		// register the public_controller event when this file is autoloaded
		Events::register('checkclose', array($this, 'checkclose'));
	}
     
	public function checkclose($data = array())
	{
		$uch = $this->CI->load->get_var('uch');

		if($uch['config']['close'])
		{
			if(empty($uch['config']['closereason'])) 
			{
				show_message('site_temporarily_closed');
			} 
			else 
			{
				show_message($uch['config']['closereason']);
			}
		}
	}
}
/* End of file events.php */


