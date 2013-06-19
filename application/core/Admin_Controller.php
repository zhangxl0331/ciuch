<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Code here is run before frontend controllers
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Controllers
 */
class Admin_Controller extends MY_Controller
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
		
		Events::trigger('checklogin');
		
		if($this->input->get('uid')) {
    		$space = $this->space_m->getspace($this->input->get('uid'), 'uid');
    	} elseif ($this->input->get('username')) {
    		$space = $this->space_m->getspace($this->input->get('uid'), 'username');
    	} elseif ($this->input->get('domain')) {
    		$space = $this->space_m->getspace($this->input->get('uid'), 'domain');
    	} elseif($uch['global']['supe_uid']) {
    		$space = $this->space_m->getspace($uch['global']['supe_uid'], 'uid');
    	}			
		
		if($uch['global']['supe_uid']) {
			$this->space_m->getmember();
			Events::trigger('replace_session', array('lastactivity' => $uch['global']['timestamp'], 'uid'=>$uch['global']['supe_uid']));
		}
		
// 		if(!empty($this->sconfig['cronnextrun']) && $this->sconfig['cronnextrun'] <= $vars['timestamp']) {
// 			include_once S_ROOT.'./source/function_cron.php';
// 			runcron();
// 		}
		
		$this->benchmark->mark('space_controller_end');
	}
}
