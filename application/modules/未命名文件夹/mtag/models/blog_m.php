<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_m extends MY_Model
{
	protected $_table = 'blog';
	protected $_primary_key = 'blogid';
	
	public function get_blog_count($wherearr)
	{
		return $this->db
		->where($wherearr)
		->count_all_results($this->_table);
	}
	
	public function get_blog_list($where, $orderby='blog.dateline DESC', $offset=0, $length=10)
	{
		return $this->db
		->select('blog.*, blogfield.message, blogfield.target_ids')
		->join('blogfield', 'blog.blogid = blogfield.blogid', 'left')
		->where($where)
		->order_by($orderby)
		->limit($length, $offset)
		->get($this->_table)
		->result();
	}

}