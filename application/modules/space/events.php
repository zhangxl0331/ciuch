<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Space
{
    protected $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();     
        $this->CI->load->model(array('space/space_m'));
        $this->module = 'space';
        
        // register the public_controller event when this file is autoloaded
        Events::register('updatelogin', array($this, 'updatelogin'));
        Events::register('getmember', array($this, 'getmember'));
//         Events::register('ckprivacy', array($this, 'ckprivacy'));
//         Events::register('privacy_tab', array($this, 'privacy_tab'), 1);
//         Events::register('replace_session', array($this, 'replace_session'));
//         Events::register('update_space', array($this, 'update_space'));
//         Events::register('update_space_lastlogin', array($this, 'update_space_lastlogin'));
//         Events::register('insert_space', array($this, 'insert_space'));
//         Events::register('checklogin', array($this, 'checklogin'));
//         Events::register('space_key', array($this, 'space_key'));
//         Events::register('ckprivacy', array($this, 'ckprivacy'));
//         Events::register('getspace', array($this, 'getspace'));
     }
     
	public function updatelogin($data = array())
	{
		$this->CI->space_m->updatelogin();
	}
	
	public function getmember($data = array())
	{
		$member = $this->CI->space_m->getmember();
		$this->CI->load->vars(array('member'=>$member));
	}
     
     public function ckprivacy($data = array())
     {
     	extract($data);
     	$uid = empty($uid)?0:$uid;
     	if(empty($module) OR in_array($this->module, (array)$module))
     	{
     		
     		return $this->CI->space_m->ckprivacy('index', 0, $uid);
     	}
     	 
     	return FALSE;
     }
     
     public function submitcheck($var) {
		$uch = $this->load->get_var('uch');
     	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
			if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == $uch['formhash']) {
				return true;
			} else {
				show_message('submit_invalid');
			}
		} else {
			return false;
		}
	}
     
     public function privacy_tab($data = array())
     {
     	extract($data);
     	$uid = empty($uid)?0:$uid;
     	if(empty($module) OR in_array($this->module, (array)$module))
     	{
     		$triggers = Events::trigger('ckprivacy', array('module'=>$this->module, 'uid'=>$uid), 'array');
     		$flag = true;
     		foreach($triggers as $key=>$value)
     		{
     			if(preg_match('/Events_'.ucfirst($this->module).'::.*/', $key))
     			{
     				$flag = $value;
     			}
     			break;
     		}
     		if($flag)
     		{
     			return $this->CI->parser->parser_callback('theme:partial', array('module'=>$this->module, 'name'=>'tab'), '');
     		}
     	}
     
     	return FALSE;
     }
     
     public function replace_session($data = array())
     {
     	$uid = $data['uid'];
     	unset($data['uid']);
     	$this->CI->space_m->db->update('session', $data, array('uid'=>$uid));
     }
     
     public function update_space($data = array())
     {
     	$uid = $data['uid'];
     	unset($data['uid']);
     	$this->CI->space_m->db->update('session', $data, array('uid'=>$uid));
     }
     
     function ckstart($data = array()) {
     	extract($data);
     	$start = empty($start)?0:intval($start);
     	$maxpage = empty($maxpage)?50:intval($maxpage);
     	$maxstart = $perpage*intval($maxpage);
     	if($start < 0 || ($maxstart > 0 && $start >= $maxstart)) {
     		show_message('length_is_not_within_the_scope_of');
     	}
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
    public function update_space_lastlogin($data = array())
    {
        $this->CI->load->helper('date');
        $setarr['lastlogin'] = now();
        $setarr['ip'] = $this->CI->input->ip_address();
        $this->CI->space_m->update_row($setarr, $data['uid']);
    }

    public function insert_space($data = array())
    {
    	$this->CI->load->libray('space/space_l');
    	if( ! $space = $this->CI->space_l->get_space($data['uid'])) 
    	{
    		$this->CI->space_l->space_open($data['uid'], $data['username'], $data['groupid'], $data['email']);
    	} 
    }
    
    
    
    
    function space_key($data = array()) {
    	return substr(md5($data['sitekey'].'|'.$data['uid'].(empty($data['appid'])?'':'|'.$data['appid'])), 8, 16);
    }
    
    
}
/* End of file events.php */


