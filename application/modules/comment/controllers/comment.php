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
	public function view($id = '')
	{
		if ( ! $id OR ! $blog = $this->blog_m->get_blog($id))
		{
			redirect('blog');
		}
		
		$this->template
		->title($blog->subject, '日志')
		->set_breadcrumb($blog->subject)
		->set('blog', $blog)
		->build('view');	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */