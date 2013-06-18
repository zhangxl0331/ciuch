<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Uc
{
    private $_ci;
    
    public function __construct()
    {
        $this->_ci = get_instance();       
        
        // register the public_controller event when this file is autoloaded
        Events::register('update_blog_viewnum', array($this, 'update_blog_viewnum'));
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
    public function uc_user_login($data = array())
    {
        $this->_ci->load->library('uc/user_l');
        $this->_ci->user_l->login($data);
    }

    
}
/* End of file events.php */


