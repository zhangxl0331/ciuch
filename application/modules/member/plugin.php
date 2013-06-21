<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global Plugin
 *
 * Make global constants available as tags
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Member extends Plugin
{
	public function __construct()
	{
		$this->load->model(
			array(
				'member/member_m',
			)
		);
	}

	/**
	 * Execute whitelisted php functions
	 *
	 * {{ helper:foo parameter1="bar" parameter2="bar" }}
	 * NOTE: the attribute name is irrelevant as only 
	 * the values are concatenated and passed as arguments
	 *
	 */
	public function __call($name, $args)
	{
		$this->load->library('member/member_l');
		return $this->member_l->$name;
	}
	
	public function avatar()
	{
		$uid = $this->attribute('uid', $this->input->get('uid'));
		$size = $this->attribute('type', 'middle');
		
		return $this->member_m->avatar($uid, $size);
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