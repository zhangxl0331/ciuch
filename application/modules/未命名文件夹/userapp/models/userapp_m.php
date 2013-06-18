<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userapp_m extends MY_Model
{
	protected $_table = 'userapp';
	protected $_primary_key = 'appid';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	function userapp_cache() {
		global $_SGLOBAL, $_SCONFIG;
	
		$_SGLOBAL['userapp'] = array();
		if($_SCONFIG['my_status']) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('myapp')." WHERE flag='1' ORDER BY displayorder", 'SILENT');
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				$_SGLOBAL['userapp'][$value['appid']] = $value;
			}
		}
		cache_write('userapp', "_SGLOBAL['userapp']", $_SGLOBAL['userapp']);
	}

}