<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('uch'));
		$this->load->library(array('blog/blog_l'));
	}
	
	public function add()
	{
		Events::trigger('blog_post', array());
		
		$this->template->build('create');
	}
	
	public function edit()
	{
		Events::trigger('blog_post', array());
	
		$this->template->build('create');
	}
	
	public function trace()
	{
		Events::trigger('blog_post', array());
	
		$this->template->build('create');
	}
	
	public function delete()
	{
		$blogid = $this->input->get('blogid');
		if(submitcheck('deletesubmit')) {
			include_once(S_ROOT.'./source/function_delete.php');
			if(deleteblogs(array($blogid))) {
				show_message('do_success', "space.php?uid=$blog[uid]&do=blog&view=me");
			} else {
				show_message('failed_to_delete_operation');
			}
		}
		$this->template->build('delete');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */