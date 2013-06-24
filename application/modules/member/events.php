<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Member
{
	protected $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->model(array('member/member_m'));
		$this->module = 'member';

		Events::register('checkauth', array($this, 'checkauth'));
		Events::register('privacy_tab', array($this, 'privacy_tab'), 1);
	}
    
	public function checkauth($data = array())
	{
		$auth = $this->CI->member_m->checkauth();
		
		$this->CI->load->vars(array('auth'=>$auth));
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
}
/* End of file events.php */


