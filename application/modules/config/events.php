<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Config
{
	private $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->model(array('config/config_m'));
        
		// register the public_controller event when this file is autoloaded
		Events::register('checkcache', array($this, 'checkcache'));
		Events::register('checkclose', array($this, 'checkclose'), 10);
	}
	
	public function checkcache($data = array())
	{
		$config = $this->CI->config_m->config_cache();

		$this->CI->load->vars(array('config'=>$config));
	}
	
	public function checkclose($data = array())
	{
		$this->CI->config_m->checkclose();
	}
}
/* End of file events.php */


