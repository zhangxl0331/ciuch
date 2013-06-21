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

}