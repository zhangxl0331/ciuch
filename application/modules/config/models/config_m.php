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
	
	public function config_cache($update=FALSE)
	{
		$config = $this->cache->get('config');
		
		if( ! $config || $update)
		{
			$rows = $this->db->get('config')->result_array();
			foreach($rows as $value)
			{
				if($value['var'] == 'privacy')
				{
					$value['datavalue'] = empty($value['datavalue'])?array():unserialize($value['datavalue']);
				}
				$config[$value['var']] = $value['datavalue'];
			}
		
			if(empty($config['sitekey']))
			{
				$config['sitekey'] = '';
			}
		
			if(empty($config['login_action']))
			{
				$config['login_action'] = md5('login'.md5($config['sitekey']));
			}
		
			if(empty($config['register_action']))
			{
				$config['register_action'] = md5('register'.md5($config['sitekey']));
			}
		
			if(empty($config['template']))
			{
				$config['template'] = 'default';
			}
		
			if(empty($config['onlinehold']) OR intval($config['onlinehold']) < 300)
			{
				$config['onlinehold'] = 300;
			}
			
			if(empty($config['feedfilternum']) || $config['feedfilternum']<1) $config['feedfilternum'] = 1;
			if(empty($config['showallfriendnum']) || $config['showallfriendnum']<1) $config['showallfriendnum'] = 10;
		
			$this->cache->save('config', $config);
		}
		
		return $config;
	}
	
	
}