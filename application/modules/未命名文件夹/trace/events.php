<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Trace
{
    private $_ci;
    
    public function __construct()
    {
        $this->_ci = get_instance();       
        
        // register the public_controller event when this file is autoloaded
        Events::register('update_blog_viewnum', array($this, 'update_blog_viewnum'));
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
    public function update_blog_viewnum($data = array())
    {
        $this->_ci->load->model('blog/blog_m');
        $this->_ci->blog_m->update_row(array('viewnum'=>'viewnum+1'), $data['blogid']);
    }

    
}
/* End of file events.php */


