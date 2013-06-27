<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Comment
{
    private $CI;
    private $module;
    
    public function __construct()
    {
        $this->CI =& get_instance();      
        $this->CI->load->model('comment/comment_m');
        $this->CI->load->helper('comment/comment');
//         $this->CI->load->language('doing/doing');
        
        $this->module = 'comment';
		
//         // register the public_controller event when this file is autoloaded
//         Events::register('ckprivacy', array($this, 'ckprivacy'));
     }    
}
/* End of file events.php */


