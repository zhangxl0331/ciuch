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
		$this->load->library(
			array(
				'config/config_l',
				'member/member_l',
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
		
		return $this->member_l->avatar($uid, $size);
	}
}