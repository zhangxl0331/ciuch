<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Usergroup
{
    private $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();       
        
        // register the public_controller event when this file is autoloaded
        Events::register('checkclose', array($this, 'checkclose'));
        Events::register('updatecache', array($this, 'updatecache'));
        Events::register('getcache', array($this, 'getcache'));
     }
     
     public function checkclose($data = array())
     {
     	$closeignore = 0;
     	
     	$vars = $this->CI->load->get_var('vars');

     	$config = $this->CI->config_m->config_cache();     	
     	@list($password, $uid) = explode("\t", authcode(get_cookie('auth'), 'DECODE', UC_KEY));
     	if($vars['supe_uid'])
     	{
     		$space = $this->CI->space_m->getspace($vars['supe_uid']);
     		$usergroup = $this->CI->usergroup_m->usergroup_cache();
     		$gid = $this->CI->usergroup_m->getgroupid($space['credit'], $space['groupid']);
     		if($gid != $space['groupid']) {
     			$this->CI->space_m->update('space', array('groupid'=>$gid), array('uid'=>$vars['supe_uid']));
     		}
     		if($permtype == 'admin') {
     			$permtype = 'manageconfig';
     		}
     		$closeignore = @$vars['usergroup'][$gid][$permtype];
     	}
     	    	
     	if($vars['config']['close'] AND !$closeignore)
     	{
     		if(empty($vars['config']['closereason']))
     		{
     			show_message('site_temporarily_closed');
     		}
     		else
     		{
     			show_message($vars['config']['closereason']);
     		}
     	}
     }    
     
     public function updatecache($data = array())
     {
     	if(empty($data) || in_array('usergroup', $data))
     	{
     		return $this->CI->usergroup_m->usergroup_cache();
     	}
     	else 
     	{
     		return FALSE;
     	}
     }
     
     public function getcache($data = array())
     {
     	if(empty($data) || in_array('usergroup', $data))
     	{
     		return $this->CI->usergroup_m->usergroup();
     	}
     	else
     	{
     		return FALSE;
     	}
     }
}
/* End of file events.php */


