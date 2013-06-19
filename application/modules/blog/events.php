<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Events_Blog
{
    private $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();       
        
        // register the public_controller event when this file is autoloaded
        Events::register('update_blog_viewnum', array($this, 'update_blog_viewnum'));
        Events::register('blog_post', array($this, 'blog_post'));
     }
    
    // this will be triggered by the Events::trigger('public_controller') code in Public_Controller.php
    public function update_blog_viewnum($data = array())
    {
        $this->CI->load->model('blog/blog_m');
        $this->CI->blog_m->update_row(array('viewnum'=>'viewnum+1'), $data['blogid']);
    }
	
    public function blog_post($data = array())
    {var_dump('aaa');exit;
//     	$this->CI->load->helper(array('date'));
//     	$this->CI->load->library(array('member/member_l'));
//     	$this->CI->load->model(array('blog/blog_m', 'blog/blogfield_m'));
//     	$uid = $this->CI->member_l->uid;
//     	$id = $this->CI->input->get_post('id')?intval($this->CI->input->get_post('id')):0;
//     	$subject = trim($this->CI->input->get_post('subject'));
//     	$message = $this->CI->input->get_post('message');
//     	$this->CI->load->library('form_validation');
//     	$validation = array(
//     			array(
//     					'field'   => 'formhash',
//     					'label'   => 'formhash',
//     					'rules'   => 'callback__valid_formhash'
//     			),
//     			array(
//     					'field' => 'subject',
//     					'label' => 'subject',
//     					'rules' => 'required|trim'
//     			),
//     			array(
//     					'field' => 'message',
//     					'label' => 'message',
//     					'rules' => 'required'
//     			),
//     	);
    	
//     	// Set the validation rules
//     	$this->CI->form_validation->set_rules($validation);
    	 
//     	if ($this->CI->form_validation->run())
//     	{
//     		if($id)
//     		{
//     			$this->CI->blog_m->update_row(array('subject'=>$subject), $id);
//     			$this->CI->blogfield_m->update_row(array('message'=>$message), $id);
//     		}
//     		else
//     		{
//     			$id = $this->CI->blog_m->insert_row(array('subject'=>$subject, 'uid'=>$uid, 'dateline'=>now()));
//     			$this->CI->blogfield_m->insert_row(array('blogid' => $id, 'message'=>$message, 'uid'=>$this->member_l->uid));
//     		}
//     		redirect(base_url()."blog?uid=$uid&id=$id");
//     	}
    }
    
}
/* End of file events.php */


