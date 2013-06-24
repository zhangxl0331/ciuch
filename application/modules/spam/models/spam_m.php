<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spam_m extends MY_Model
{	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->driver('cache');
	}
	
	public function spam_cache($update=FALSE)
	{
		$spam = $this->cache->get('spam');
		
		if( ! $spam || $update)
		{
			$row = $this->db->where('var', 'spam')->get('data')->row_array();
			$spam = empty($row['datavalue'])?array():unserialize($row['datavalue']);		
		
			$this->cache->save('spam', $spam);
		}
		
		return $spam;
	}
	

	public function question()
	{
		$spam = $this->cache->get('spam');
		$key = mt_rand(0, max(count($spam)-1, 0));
		return array('question'=>$spam['question'][$key], 'answer'=>$spam['answer'][$key]);
	}
	
	public function captcha()
	{
		return create_captcha(array(
				'img_path' => APPPATH.'cache/captcha/',
				'img_url' => base_url(APPPATH.'cache/captcha').'/'
		));
	}
}