<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Doing
{
    private $CI;
    private $module;
    
    public function __construct()
    {
        $this->CI =& get_instance();      
        $this->CI->load->model('doing/doing_m');
        $this->CI->load->language('doing/doing');
        
        $this->module = 'doing';
		
//         // register the public_controller event when this file is autoloaded
//         Events::register('ckprivacy', array($this, 'ckprivacy'));
        Events::register('privacy_tab', array($this, 'privacy_tab'));
//         Events::register('privacy_icon', array($this, 'privacy_icon'));
     }

// 	public function ckprivacy($data = array())
// 	{
//      	extract($data);
//      	$uid = empty($uid)?0:$uid;
//     	if(empty($module) OR in_array($this->module, (array)$module))
// 		{
//      		return $this->CI->space_m->ckprivacy($this->module, 0, $uid);
//      	}
     	 
//      	return FALSE;
// 	}
     
	// this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
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
    
    
//     public function privacy_icon($data = array())
//     {
//     	extract($data);
//     	$uid = empty($uid)?0:$uid;
//     	if(empty($module) OR in_array($this->module, (array)$module))
//     	{
//     		$triggers = Events::trigger('ckprivacy', array('module'=>$this->module, 'uid'=>$uid), 'array');
//     		$flag = true;
//     		foreach($triggers as $key=>$value)
//     		{
//     			if(preg_match('/Events_'.ucfirst($this->module).'::.*/', $key))
//     			{
//     				$flag = $value;
//     			}
//     			break;
//     		}
//     		if($flag)
//     		$count = $this->CI->{$this->module.'_m'}->db->where('uid', $uid)->count_all_results($this->module);
//     		if($flag AND $count)
//     		{
//     			return $this->CI->parser->parser_callback('theme:partial', array('module'=>$this->module, 'name'=>'icon'), '');
//     		}
//     	}
    
//     	return FALSE;
//     }

    
}
/* End of file events.php */


