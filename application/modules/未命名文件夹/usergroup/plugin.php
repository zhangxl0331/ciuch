<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Files Plugin
 *
 * Create a list of files
 *
 * @author		Marcos Coelho
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Files\Plugins
 */
class Plugin_Usergroup extends Plugin
{

	public function __construct()
	{
		
	}
	
	public function __call($name, $args)
	{
		$this->load->library('usergroup/usergroup_l');
		
		return $this->usergroup_l->$name;
	}
}

/* End of file plugin.php */
