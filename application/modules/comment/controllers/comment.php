<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('comment/comment_m');
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
	public function index()
	{
		$this->template
		->build('welcome_message');
	}
	
	/**
	 * View a post
	 *
	 * @param string $slug The slug of the blog post.
	 */
	public function add($module, $id)
	{
		$config = $this->load->get_var('config');
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');
		if(empty($auth['uid']))
		{
			redirect(site_url('member/'.$config['login_action']));
		}
		
		Events::trigger('checkperm', 'allowcomment');
		
		Events::trigger('ckrealname', 'comment');
		
		Events::trigger('cknewusertime');
		
		Events::trigger('ckavatar');
		
		Events::trigger('ckfriendnum');
		
		Events::trigger('ckemail');
		
		Events::trigger('ckinterval', 'post');
		

// 		if ( ! $id OR ! $blog = $this->blog_m->get_blog($id))
// 		{
// 			redirect('blog');
// 		}
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('message', '', 'min_length[2]');
		$this->form_validation->set_message('min_length', lang('content_is_too_short'));
		if($this->input->post())
		{
			if($this->form_validation->run())
			{
				$setarr = array(
						'uid' => $auth['uid'],
						'id' => $id,
						'idtype' => $module,
						'authorid' => $auth['uid'],
						'author' => $auth['username'],
						'dateline' => time(),
						'message' => $_POST['message'],
						'ip' => $this->input->ip_address()
				);
				//���
				$cid = $this->comment_m->db->insert('comment', $setarr);
				redirect($_POST['refer']);
			}
			else
			{
				$this->load->vars(array('message'=>validation_errors(), 'url_forword'=>'', 'second'=>1));
				exit($this->template->build('showmessage', array(), TRUE));
			}
		}
		$data = array('module'=>$module, 'id'=>$id);
		$this->template->build('add', $data);	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */