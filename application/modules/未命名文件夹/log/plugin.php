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
class Plugin_Ad extends Plugin
{

	public function __call($name, $args)
	{
		$this->load->library('ad/ad');
		return $this->ad->get($name);
	}
}

/* End of file plugin.php */
