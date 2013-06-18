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

		return $this->blog_l->counts($uid);
	
	}
	
	public function lists()
	{
		$attributes = array();
		$attributes['uid'] = $this->attribute('uid', $this->input->get('uid'));
		$attributes['view'] = $this->attribute('view', $this->input->get('view'));
		$attributes['classid'] = $this->attribute('classid', $this->input->get('classid'));		
		$attributes['friend'] = $this->attribute('friend', $this->input->get('friend'));
		$attributes['orderby'] = $this->attribute('orderby', 'blog.dateline DESC');
		$attributes['offset'] = $this->attribute('offset', 0);
		$attributes['length'] = $this->attribute('length', 10);
		
		return $this->blog_l->lists($attributes);
		
	}
	
	public function classlist()
	{
		$uid = $this->attribute('uid', $this->input->get('uid'));
		return $this->blog_l->classlist($uid);
	}
	
	public function classname()
	{
		$classid = $this->attribute('classid', $this->input->get('classid'));
		$class = $this->blog_l->classname($classid);
		return $class['classname'];
	}
	
	public function __call($name, $args)
	{
		$this->load->library('blog/blog_l');
	
		return $this->blog_l->$name;
	}
}