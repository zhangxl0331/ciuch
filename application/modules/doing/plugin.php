<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Doing extends Plugin
{
	public function __construct()
	{
		$this->load->model(
			array(
				'doing/doing_m',
			)	
		);
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
// 	{		$this->load->library(array('blog/blog_l'));
// 		$this->load->library('blog/blog_l');
		
// 		return $this->blog_l->$name;
// 	}
	
	public function ckprivacy()
	{
		return $this->space_m->ckprivacy('doing');
	}
	
	public function getcount()
	{
		$uch = $this->load->get_var('uch');
		$uid = intval($this->attribute('uid', $uch['space']['uid']));
		$view = $this->attribute('view', $this->input->get('view'));
		$space = $this->space_m->getspace($uid);
		if($view == 'all')
		{
			return $this->doing_m->db->count_all_results('doing');
		}
		elseif($view == 'me')
		{
			return $this->doing_m->db->where('uid', $uid)->count_all_results('doing');
		}
		else 
		{
			return $this->doing_m->db->where_in('uid', $space['feedfriend'])->count_all_results('doing');
		}
		
		
		return FALSE;
	}
	
	public function getlist()
	{
		$uch = $this->load->get_var('uch');
		$uid = intval($this->attribute('uid', $uch['space']['uid']));
		$view = $this->attribute('view', $this->input->get('view'));
		$space = $this->space_m->getspace($uid);
		$orderby = $this->attribute('orderby', 'dateline DESC');
		$limit = $this->attribute('limit', 5);
		$offset = $this->attribute('offset', 0);
		if($view == 'all')
		{
			return $this->doing_m->db->order_by($orderby)->get('doing', $limit, $offset)->result_array();
		}
		elseif($view == 'me')
		{
			return $this->doing_m->db->where('uid', $uid)->order_by($orderby)->get('doing', $limit, $offset)->result_array();
		}
		else
		{
			return $this->doing_m->db->where_in('uid', $space['feedfriend'])->order_by($orderby)->get('doing', $limit, $offset)->result_array();
		}
		return FALSE;
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

		return $this->blog_l->counts($uid);
	
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
		
		return $this->blog_l->lists($uid, $view, $orderby, $offset, $length);
		
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
	
	public function __call($name, $args)
	{
		$this->load->library('blog/blog_l');
	
		return $this->blog_l->$name;
	}
}