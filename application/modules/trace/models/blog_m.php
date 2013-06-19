<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_m extends MY_Model
{
	protected $_table = 'blog';
	protected $_primary_key = 'blogid';
	
	public function get_blog_count($where)
	{
		return $this->db
		->select('blog.*, blogfield.message, blogfield.target_ids')
		->join('blogfield', 'blog.blogid = blogfield.blogid', 'left')
		->where($wherearr)
		->get($this->_table)
		->num_rows();
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