<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('uch');
		$this->load->language(array('cp', 'member/member'));
	}

	public function login()
	{
		$username = trim($this->input->get_post('username'));
		$password = $this->input->get_post('password');
		$cookietime = intval($this->input->get_post('cookietime'));
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('loginsubmit', '', 'required');
		$this->form_validation->set_rules('formhash', lang('formhash'), 'callback_cksubmit['.formhash(0, '1', '').']');
		$this->form_validation->set_rules('username', lang('username'), 'required');
		$this->form_validation->set_rules('password', lang('password'), 'required');		
		if ($this->form_validation->run())
		{
			$this->load->library('uc/user_l', '', 'uc_user_l');
			$passport = $this->uc_user_l->login($username, $password);
			if( $passport[0] < 0)
			{
				show_error('login_failure_please_re_login');
			}
			
			$setarr = array(
					'uid' => $passport[0],
					'username' => $passport[1],
					'password' => md5("$passport[0]|".now()),
					'email'	=> $passport[3],
					'groupid' => 0,
			);
			$this->load->library('member/member_l');
			if( ! $space = $this->member_l->member($setarr['uid']))
			{
    			$this->member_l->replace_member($setarr['uid'], $setarr['username'], $setarr['password']);
			}
			
			$this->load->library('space/space_l');
	    	if( ! $space = $this->space_l->space($setarr['uid'])) 
	    	{
	    		$this->space_l->space_open($setarr['uid'], $setarr['username'], $setarr['groupid'], $setarr['email']);
// 				uc_pm_send
// 				feed_add
	    	} 


			Events::trigger('insert_session', $setarr);
			
			$this->load->helper('global');
			set_cookie('auth', authcode("$setarr[password]\t$setarr[uid]", 'ENCODE'), $cookietime);
			set_cookie('loginuser', $setarr['username'], 31536000);
			set_cookie('_refer', '');
			$this->load->helper('url');
			redirect(base_url().'blog');
			$ucsynlogin = uc_user_synlogin($setarr['uid']);
			exit;
			// Kill the session
			$this->session->unset_userdata('redirect_to');
		
			// Deprecated.
			$this->hooks->_call_hook('post_user_login');
		
			// trigger a post login event for third party devs
			Events::trigger('post_user_login');
		
			if ($this->input->is_ajax_request())
			{
				$user = $this->ion_auth->get_user_by_email($user->email);
				$user->password = '';
				$user->salt = '';
		
				exit(json_encode(array('status' => true, 'message' => lang('user_logged_in'), 'data' => $user)));
			}
			else
			{
				$this->session->set_flashdata('success', lang('user_logged_in'));
			}
		
			// Don't allow protocols or cheeky requests
			if (strpos($redirect_to, ':') !== FALSE and strpos($redirect_to, site_url()) !== 0)
			{
				// Just login to the homepage
				redirect('');
			}
		
			// Passes muster, on your way
			else
			{
				redirect($redirect_to ? $redirect_to : '');
			}
		}
		$membername = ! get_cookie('loginuser')?'':get_cookie('loginuser');
		$cookiecheck = ' checked';
		
		$this->load->vars(array('membername'=>'', 'password'=>'', 'cookiecheck'=>true, 'refer'=>''));
		
		$this->template
		->set('nosidebar', 1)
		->build('login');
	}
	
	function _valid_formhash($input)
	{
		exit($input);return FALSE;
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */