<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function comment_list($type, $id)
{
	$CI = & get_instance();
	$CI->load->model('comment/comment_m');
	return $CI->comment_m->db->where(array('idtype'=>$type, 'id'=>$id))->get('comment')->result_array();
}

?>