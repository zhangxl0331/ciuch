<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Spam
{
	private $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->model(array('spam/spam_m'));
        
		// register the public_controller event when this file is autoloaded
		Events::register('checkcache', array($this, 'checkcache'));
	}
	
	public function checkcache($data = array())
	{
		$spam = $this->CI->spam_m->spam_cache();
		$this->CI->load->vars(array('spam'=>$spam));
	}
}
/* End of file events.php */


