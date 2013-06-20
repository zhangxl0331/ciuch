<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config_m extends MY_Model
{	
	public function __construct()
	{
		parent::__construct();
		
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
	
	public function checkclose()
	{
		extract($this->load->get_var('global'));
		if(isset($config['close']) AND $config['close']) 
		{
			if(empty($config['closereason'])) 
			{
				showmessage('site_temporarily_closed');
			} 
			else 
			{
				showmessage($config['closereason']);
			}
		}
		
		if((isset($config['ipaccess']) AND !$this->ipaccess($config['ipaccess'])) OR (isset($config['ipbanned']) AND $this->ipbanned($config['ipbanned']))) 
		{
			showmessage('ip_is_not_allowed_to_visit');
		}
	}
	
	//ip��������
	function ipaccess($ipaccess) {
		return empty($ipaccess)?true:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipaccess, '/')).")/", $this->input->ip_address());
	}
	
	//ip���ʽ�ֹ
	function ipbanned($ipbanned) {
		return empty($ipbanned)?false:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipbanned, '/')).")/", $this->input->ip_address());
	}
}