<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Space extends Plugin
{
	public function __construct()
	{
// 		$this->load->library('space/space_l');
	}	
	
	public function tabs()
	{
		$uid = $this->attribute('uid');
		$tabs = Events::trigger('privacy_tab', array('uid'=>$uid), 'array');
		return implode("\n", $tabs);
	}
	
	public function icons()
	{
		$uid = $this->attribute('uid');
		$icons = Events::trigger('privacy_icon', array('uid'=>$uid), 'array');
		return implode("\n", $icons);
	}

}