<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Member
{
	protected $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->model(array('member/member_m'));
		// register the public_controller event when this file is autoloaded
		Events::register('checkauth', array($this, 'checkauth'));
	}
    
	// this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
	public function checkauth($data = array())
	{
		$auth = $this->CI->member_m->checkauth();
		if($auth['uid'])
		{
			if( ! isset($auth['lastactivity']))
			{
				$this->CI->member_m->insertsession($auth);
			}
		}
		else 
		{
			$this->CI->member_m->clearcookie();
		}
		$this->CI->load->vars(array('auth'=>$auth));
	}

//     public function replace_member($data = array())
//     {
//     	$this->CI->load->library('member/member_l');
//     	$this->CI->member_l->replace_member($data['uid'], $data['username'], $data['password']);
//     }
}
/* End of file events.php */


