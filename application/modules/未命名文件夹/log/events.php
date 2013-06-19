<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Log
{
    private $_ci;
    
    public function __construct()
    {
        $this->_ci = get_instance();       
        
        // register the public_controller event when this file is autoloaded
        Events::register('log', array($this, 'log'));
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
    public function log($data = array())
    {
        $this->_ci->load->model('log/log_m');
        $this->_ci->log_m->insert_row($data);
    }

    
}
/* End of file events.php */


