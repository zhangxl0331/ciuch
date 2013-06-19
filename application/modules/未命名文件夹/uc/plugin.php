<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Blog extends Plugin
{
	public function __construct()
	{
		$this->load->model(
			array(
				'blog/trace_m',
				'comment/comment_m',	
			)	
		);
		$this->load->library(array('blog/blog_l'));
	}
	
	/**
	 * Execute whitelisted php functions
	 *
	 * {{ helper:foo parameter1="bar" parameter2="bar" }}
	 * NOTE: the attribute name is irrelevant as only 
	 * the values are concatenated and passed as arguments
	 *
	 */
// 	public function __call($name, $args)
// 	{
// 		$this->load->library('blog/blog_l');
		
// 		return $this->blog_l->$name;
// 	}
	
	public function trace()
	{
		$id = $this->attribute('id', $this->input->get('id'));		
		$orderby = $this->attribute('orderby', 'dateline');
		$direction = $this->attribute('direction', 'DESC');
		$offset = $this->attribute('offset', 0);
		$length = $this->attribute('length', 18);
		
		return $this->trace_m->get_trace($id, $orderby, $direction, $offset, $length);
	}
	
	public function comment()
	{
		$id = $this->attribute('id', $this->input->get('id'));
		$cid = $this->attribute('cid', $this->input->get('cid'));
		$orderby = $this->attribute('orderby', 'dateline');
		$direction = $this->attribute('direction', 'DESC');
		$offset = $this->attribute('offset', 0);
		$length = $this->attribute('length', 18);
		
		$wherearr = array('id'=>$id);
		if($cid)
		{
			$wherearr = array_merge($wherearr, array('cid'=>$cid));
		}
	
		return $this->comment_m->get_comment($wherearr, $orderby, $direction, $offset, $length);
	}
	
	public function counts()
	{
		$uid = $this->attribute('uid', $this->input->get('uid'));
		$view = $this->attribute('view', $this->input->get('view'));
		$classid = $this->attribute('classid', $this->input->get('classid'));
		$friend = $this->attribute('friend', $this->input->get('friend'));
	
	
		return $this->blog_l->counts($uid, $view, $classid, $friend);
	
	}
	
	public function lists()
	{
		$uid = $this->attribute('uid', $this->input->get('uid'));
		$view = $this->attribute('view', $this->input->get('view'));
		$classid = $this->attribute('classid', $this->input->get('classid'));		
		$friend = $this->attribute('friend', $this->input->get('friend'));
		$orderby = $this->attribute('orderby', 'blog.dateline DESC');
		$offset = $this->attribute('offset', 0);
		$length = $this->attribute('length', 10);
		
		
		return $this->blog_l->lists($uid, $view, $classid, $friend, $orderby, $offset, $length);
		
	}
	
	public function classlist()
	{
		$uid = $this->attribute('uid', $this->input->get('uid'));
		return $this->blog_l->classlist($uid);
	}
	
	public function classname()
	{
		$classid = $this->attribute('classid', $this->input->get('classid'));
		return $this->blog_l->classname($classid);
	}

}