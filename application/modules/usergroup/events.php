<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Usergroup
{
	private $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();   
		$this->CI->load->model('usergroup/usergroup_m');    
        
		// register the public_controller event when this file is autoloaded
		Events::register('checkclose', array($this, 'checkclose'), 1);
		Events::register('checkcache', array($this, 'checkcache'));
		Events::register('checkperm', array($this, 'checkperm'));
		Events::register('ckinterval', array($this, 'ckinterval'));
		Events::register('ckseccode', array($this, 'ckseccode'));
	}
     
	public function checkclose($data = array())
	{
		$this->CI->usergroup_m->checkclose();
	}    
     
	public function checkcache($data = array())
	{
		$usergroup = $this->CI->usergroup_m->usergroup_cache();

		$this->CI->load->vars(array('usergroup'=>$usergroup));
	}
	
	public function checkperm($data = array())
	{
		if(!$return = $this->CI->usergroup_m->checkperm((array)$data[0]))
		{
			if(empty($data[1]))
			{
				$this->CI->load->vars(array('message'=>$this->CI->lang->line('no_privilege'), 'url_forword'=>'', 'second'=>1));
				exit($this->CI->template->build('showmessage', array(), TRUE));
			}
		}
		return $return;
	}
	
	public function ckinterval($type)
	{
		if($waittime = $this->CI->usergroup_m->ckinterval($type))
		{
			$this->CI->load->vars(array('message'=>sprintf($this->CI->lang->line('operating_too_fast'), $waittime), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
	
	public function ckseccode()
	{
		if($this->CI->usergroup_m->ckseccode($type))
		{
			$this->CI->load->vars(array('message'=>$this->CI->lang->line('incorrect_code'), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
     
}
/* End of file events.php */


