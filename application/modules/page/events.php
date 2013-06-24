<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Page
{
	private $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
        
		// register the public_controller event when this file is autoloaded
		//Events::register('checkcache', array($this, 'checkcache'));
	}

}
/* End of file events.php */


