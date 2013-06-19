<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Credit
{
    private $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();       
        
        // register the public_controller event when this file is autoloaded
//         Events::register('checkclose', array($this, 'checkclose'));
     }
     
     public function checkclose($data = array())
     {
     	$this->CI->load->helper(array('cookie', 'uch'));
     	$this->CI->load->model('config/config_m');
     	$config = $this->CI->config_m->config_cache();
     	@list($password, $uid) = explode("\t", authcode(get_cookie('auth'), 'DECODE', UC_KEY));
     	if($config['close'] AND !$uid)
     	{
     		if(empty($config['closereason'])) 
     		{
     			show_message('site_temporarily_closed');
     		} 
     		else 
     		{
     			show_message($config['closereason']);
     		}
     	}
     }    
}
/* End of file events.php */


