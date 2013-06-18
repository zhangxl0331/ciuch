<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* load the MX_Router class */
require APPPATH."third_party/MX/Router.php";

class MY_Router extends MX_Router 
{
	public function _validate_request($segments) {
	
		if (count($segments) == 0) return $segments;
	
		$segments = $this->_parse_query_strings($segments);
		/* locate module controller */
		if ($located = $this->locate($segments))
		{
			return $this->_parse_query_strings($located);
		}
	
		/* use a default 404_override controller */
		if (isset($this->routes['404_override']) AND $this->routes['404_override']) {
			$segments = explode('/', $this->routes['404_override']);
			if ($located = $this->locate($segments))
			{
				return $this->_parse_query_strings($located);
			}
		}
	
		/* no controller found */
		show_error('no controller found');
	}
	
	public function _parse_query_strings($segments)
	{
		if ($this->config->item('enable_query_strings') === TRUE)
		{
			foreach($segments as $key=>$val)
			{
				$temp = explode('?', $val, 2);
				if(isset($temp[0]))
				{
					$segments[$key] = $temp[0];
				}
				else
				{
					unset($segments[$key]);
				}
				if(isset($temp[1]))
				{
					parse_str($temp[1], $arr);
					$_GET = array_merge_recursive($_GET, $arr);
				}
			}
		}
	
		return $segments;
	}
}