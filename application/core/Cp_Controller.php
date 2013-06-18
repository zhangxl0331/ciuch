<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Code here is run before frontend controllers
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Controllers
 */
class Cp_Controller extends MY_Controller
{
	public $isinvite = 0;
	public $uid = 0;
	public $username = '';
	public $domain = '';
	
	/**
	 * Loads the gazillion of stuff, in Flash Gordon speed.
	 * @todo Document properly please.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->benchmark->mark('space_controller_start');
		
		$uch = $this->load->get_var('uch');
				
		if(empty($uch['supe_uid'])) {
			if($_SERVER['REQUEST_METHOD'] == 'GET') {
				set_cookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
			} else {
				set_cookie('_refer', rawurlencode(base_url($this->router->fetch_module().'cp')));
			}
			show_message('to_login', base_url('space/ac/'.$uch['config']['login_action']));
		}
		
		$this->benchmark->mark('space_controller_end');
	}
}
