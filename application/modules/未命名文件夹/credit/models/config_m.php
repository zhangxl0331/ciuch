<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_m extends MY_Model
{	
	protected $_table = 'config';
	
	public function __construct()
	{
		parent::__construct();
		
		
// 		$this->load->model('conf/data_m');
		$this->load->driver('cache');
	}
	
	public function config_cache($updatedata=true)
	{
		$return = array();
		
		
		if($return = $this->cache->get('config'))
		{
			return $return;
		}
		
		$rows = $this->db->get('config')->result_array();
		foreach($rows as $value) 
		{
			if($value['var'] == 'privacy') 
			{
				$value['datavalue'] = empty($value['datavalue'])?array():unserialize($value['datavalue']);
			}
			$return[$value['var']] = $value['datavalue'];
		}
		
		if(empty($return['sitekey']))
		{
			$return['sitekey'] = '';
		}
		
		if(empty($return['login_action']))
		{
			$return['login_action'] = md5('login'.md5($return['sitekey']));
		}
		
		if(empty($return['register_action']))
		{
			$return['register_action'] = md5('register'.md5($return['sitekey']));
		}
		
		if(empty($return['template']))
		{
			$return['template'] = 'default';
		}
		
		if(empty($return['onlinehold']) OR intval($return['onlinehold']) < 300)
		{
			$return['onlinehold'] = 300;
		}
		
		$this->cache->save('config', $return);
		
		
		
// 		if($updatedata) {
// 			$setting = $this->data_m->data_get('setting');
// 			$setting = empty($setting)?array():unserialize($setting);
// 			$this->cache->save('setting', $setting);
		
// 			$mail = $this->data_m->data_get('mail');
// 			$mail = empty($mail)?array():unserialize($mail);
// 			$this->cache->save('mail', $mail);
					
// 			$spam = $this->data_m->data_get('spam');
// 			$spam = empty($spam)?array():unserialize($spam);
// 			$this->cache->save('spam', $spam);
// 		}
		
		return $return;
	}

}