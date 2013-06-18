<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends Space_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('blog/blog_l');
	}
	
	public function index()
	{	
		if($blogid = $this->input->get('id'))
		{
			if( ! $blog = $this->blog_l->blog($blogid))
			{
				show_message('view_to_info_did_not_exist');
			}
			if(!ckfriend($blog)) 
			{
				$this->template->_find_view('space_privacy');
				exit();
			} 
			elseif($value['friend']==4 AND !ckinputpwd($blog)) 
			{
				$this->template->_find_view('do_inputpwd', $blog);
				exit();
			}
			$this->template->build('view', $blog);
		}
		else 
		{
			$this->template->build('list');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */