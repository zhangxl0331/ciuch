<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Member
{
	protected $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->model(array('member/member_m'));

		Events::register('checkauth', array($this, 'checkauth'));
	}
    
	public function checkauth($data = array())
	{
		$auth = $this->CI->member_m->checkauth();
		
		$this->CI->load->vars(array('auth'=>$auth));
	}
}
/* End of file events.php */


