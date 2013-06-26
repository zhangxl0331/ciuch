<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Doing_m extends MY_Model
{	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_blog_count($where)
	{
		return $this->db
		->where($where)
		->count_all_results('doing');;
	}
	
	public function get_blog_list($where, $orderby='blog.dateline DESC', $offset=0, $length=10)
	{
		return $this->db
		->select('blog.*, blogfield.message, blogfield.target_ids')
		->join('blogfield', 'blog.blogid = blogfield.blogid', 'left')
		->where($where)
		->order_by($orderby)
		->limit($length, $offset)
		->get('doing')
		->result_array();
	}
	
	public function deletedoings($ids) 
	{
		$auth = $this->load->get_var('auth');
		$usergroup = $this->load->get_var('usergroup');

		$allowmanage = isset($usergroup[$auth['groupid']]['managedoing'])?$usergroup[$auth['groupid']]['managedoing']:0;
		$newdoids = array();
		if($list = $this->db->where_in('doid', $ids)->get('doing')->result_array())
		{
			foreach($list as $value)
			{
				if($allowmanage || $value['uid'] == $auth['uid']) {
					$newdoids[] = $value['doid'];
				}
			}
		}
		
		$this->db->where_in('doid', $newdoids)->delete('doing');
	
		//ɾ������
// 		$_SGLOBAL['db']->query("DELETE FROM ".tname('docomment')." WHERE doid IN (".simplode($newdoids).")");
	
		return $newdoids;
	}

}