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
        Events::register('creditrule', array($this, 'creditrule'));
        Events::register('updatecache', array($this, 'updatecache'));
        Events::register('getcache', array($this, 'getcache'));
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

     public function checklogin($data = array())
     {
     	$uch = $this->load->get_var('uch');
     	
     	if(empty($uch['config']['networkpublic'])) {
     		$this->space_m->checklogin();
     	}
     }

     
     
     public function updatecache($data = array())
     {
     	if(empty($data) || in_array('sconfig', $data))
     	{
     		return $this->CI->config_m->config_cache();    	
     	}
     	else
     	{
     		return FALSE;
     	}
     }
}
/* End of file events.php */


