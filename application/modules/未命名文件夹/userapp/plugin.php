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
class Plugin_Userapp extends Plugin
{

	public function __construct()
	{
		$this->load->library('userapp/userapp');
	}
	
	public function default_app()
	{
		return $this->userapp->default_app();
	}

	public function my_menu()
	{
		$result = array();
		$uid = $this->attribute('uid');
		$limit = $this->attribute('limit', 10);
		
		return $this->userapp->my_menu($uid, $limit);
	}
}

/* End of file plugin.php */
