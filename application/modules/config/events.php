<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Config
{
	private $CI;
    
	public function __construct()
	{
		$this->CI =& get_instance();       
		$this->CI->load->model(array('config/config_m'));
        
		// register the public_controller event when this file is autoloaded
		Events::register('checkcache', array($this, 'checkcache'));
		Events::register('checkclose', array($this, 'checkclose'), 10);
		Events::register('ckrealname', array($this, 'ckrealname'));
		Events::register('cknewusertime', array($this, 'cknewusertime'));
		Events::register('ckavatar', array($this, 'ckavatar'));
		Events::register('ckfriendnum', array($this, 'ckfriendnum'));
		Events::register('ckemail', array($this, 'ckemail'));
	}
	
	public function checkcache($data = array())
	{
		$config = $this->CI->config_m->config_cache();

		$this->CI->load->vars(array('config'=>$config));
	}
	
	public function checkclose($data = array())
	{
		$this->CI->config_m->checkclose();
	}
	
	public function ckrealname($type)
	{
		if($this->CI->config_m->ckrealname($type))
		{
			$this->CI->load->vars(array('message'=>$this->CI->lang->line('no_privilege_realname'), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
	
	public function cknewusertime()
	{
		if($this->CI->config_m->cknewusertime())
		{
			$this->CI->load->vars(array('message'=>$this->CI->lang->line('no_privilege_newusertime'), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
	
	public function ckavatar()
	{
		if($this->CI->config_m->ckavatar()) {
			$this->CI->load->vars(array('message'=>$this->CI->lang->line('no_privilege_avatar'), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
	
	public function ckfriendnum()
	{
		if($this->CI->config_m->ckfriendnum()) {
			$this->CI->load->vars(array('message'=>$this->CI->lang->line('no_privilege_friendnum'), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
	
	public function ckemail()
	{
		if($this->CI->config_m->ckemail()) {
			$this->CI->load->vars(array('message'=>$this->CI->lang->line('no_privilege_email'), 'url_forword'=>'', 'second'=>1));
			exit($this->CI->template->build('showmessage', array(), TRUE));
		}
	}
}
/* End of file events.php */


