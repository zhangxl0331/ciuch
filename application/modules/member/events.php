<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Member
{
    protected $CI;
    
    public function __construct()
    {
        $this->CI = get_instance();       
        
        // register the public_controller event when this file is autoloaded
        Events::register('insert_session', array($this, 'insert_session'));
        Events::register('member_replace_member', array($this, 'replace_member'));
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
    public function insert_session($data = array())
    {
        $this->CI->load->model('member/session_m');
        $this->CI->load->helper('date');
        $this->CI->session_m->delete_by_uid_or_lastactivity($data['uid'], now()-$this->CI->config_l->onlinehold);
        $replace = array('uid'=>$data['uid'], 'username'=>$data['username'], 'password'=>$data['password'], 'lastactivity'=>now(), 'ip'=>$this->CI->input->ip_address());
        $this->CI->session_m->replace_row($replace);
        
        Events::trigger('update_space_lastlogin', $data);
    }

    public function replace_member($data = array())
    {
    	$this->CI->load->library('member/member_l');
    	$this->CI->member_l->replace_member($data['uid'], $data['username'], $data['password']);
    }
}
/* End of file events.php */


