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
class Plugin_Privacy extends Plugin
{

	public function __construct()
	{

	}
	
	public function __call($name, $args)
	{
		$this->load->library('space/space_l');
		
		return empty($this->space_l->privacy['view'][$name]) OR $this->space_l->isfriend;

	}
}

/* End of file plugin.php */
