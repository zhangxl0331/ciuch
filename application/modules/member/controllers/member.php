<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('uch');
		$this->load->language(array('message', 'member/member'));
		$this->load->model(array('member/member_m'));
	}

	public function login()
	{
		$password = $this->input->post('password');
		$username = trim($this->input->post('username'));
		$cookietime = intval($this->input->post('cookietime'));
		$cookiecheck = $cookietime?' checked':'';
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', lang('username'), 'required|callback_cklogin[loginsubmit]');
		$this->form_validation->set_rules('password', lang('password'), 'required');		
		$this->form_validation->run();
				
		$this->template
		->set('nosidebar', 1)
		->build('login');
	}
	
	function cklogin($username, $var)
	{
		if( ! submitcheck($var, formhash(0, '', '')))
		{
			$this->form_validation->set_message('cklogin', lang('submit_invalid'));
			return FALSE;
		}
		$passport = $this->rest->get('uc/user/login', array('username'=>$username, 'password'=>$this->input->post('password')));

		if($passport['uid'] < 0)
		{
			$this->form_validation->set_message('cklogin', lang('login_failure_please_re_login'));
			return FALSE;
		}
		else 
		{
			$setarr = array(
					'uid' => $passport['uid'],
					'username' => $passport['username'],
					'password' => md5("$passport[uid]|time()"),
					'lastactivity' => time(),
					'ip' => $this->input->ip_address()
			);
			
			$this->member_m->db->replace('session', $setarr);
			set_cookie('auth', authcode("$setarr[password]\t$setarr[uid]", 'ENCODE'), intval($_POST['cookietime']));
			set_cookie('loginuser', $username, 31536000);
			set_cookie('_refer', '');
			redirect('space/home');
			return TRUE;
		}
	}
	
	/**
	 * View a post
	 *
	 * @param string $slug The slug of the blog post.
	 */
	public function register()
	{
		$this->template
		->set('nosidebar', 1)
		->build('register');	
	}
	
// 	public function _remap($ac, $params = array())
// 	{
// 		$this->load->library('config/config_l');
// 		if($ac == $this->config_l->login_action) 
// 		{
// 			$ac = 'login';
// 		} 
// 		elseif($ac == 'login') 
// 		{
// 			$ac = '';
// 		}
// 		if($ac == $this->config_l->register_action) 
// 		{
// 			$ac = 'register';
// 		} 
// 		elseif($ac == 'register') 
// 		{
// 			$ac = '';
// 		}
		
// 		if(empty($ac) || !in_array($ac, array('login', 'register'))) 
// 		{
// 			show_404();
// 		}
		
// 		if (is_callable(array($this, $ac)))
// 		{
// 			call_user_func(array($this, $ac), $params);
// 		}
// 	}

	public function logout()
	{
		if($this->user) {
			$this->member_m->db->delete('session', array('uid'=>$this->user['uid']));
			$this->member_m->db->delete('adminsession', array('uid'=>$this->user['uid']));
		}
		
		//$this->rest->get('uc/user/synlogout', array('username'=>$username, 'password'=>$this->input->post('password')));
		
		set_cookie('auth', '', -86400 * 365);
		set_cookie('_refer', '');

		redirect('homepage');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */