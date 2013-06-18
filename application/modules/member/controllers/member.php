<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function login()
	{
		$username = trim($this->input->get_post('username'));
		$password = $this->input->get_post('password');
		$cookietime = intval($this->input->get_post('cookietime'));
		
		$this->load->library('form_validation');
		$validation = array(
				array(
						'field'   => 'loginsubmit',
						'label'   => 'loginsubmit',
						'rules'   => 'required|trim'
				),
				array(
						'field'   => 'formhash',
						'label'   => 'formhash',
						'rules'   => 'callback__valid_formhash'
				),
				array(
						'field' => 'username',
						'label' => 'username',
						'rules' => 'required|trim'
				),
				array(
						'field' => 'password',
						'label' => 'password',
						'rules' => 'required'
				),
		);
			
		// Set the validation rules
		$this->form_validation->set_rules($validation);
		
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
		
		$this->template
		->set('nosidebar', 1)
		->build('login');
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
	
	public function _remap($ac, $params = array())
	{
		$this->load->library('config/config_l');
		if($ac == $this->config_l->login_action) 
		{
			$ac = 'login';
		} 
// 		elseif($ac == 'login') 
// 		{
// 			$ac = '';
// 		}
		if($ac == $this->config_l->register_action) 
		{
			$ac = 'register';
		} 
// 		elseif($ac == 'register') 
// 		{
// 			$ac = '';
// 		}
		
		if(empty($ac) || !in_array($ac, array('login', 'register'))) 
		{
			show_404();
		}
		
		if (is_callable(array($this, $ac)))
		{
			call_user_func(array($this, $ac), $params);
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */