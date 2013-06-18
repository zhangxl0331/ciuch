<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Dwoo Parser Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @license	 http://philsturgeon.co.uk/code/dbad-license
 * @link		http://philsturgeon.co.uk/code/codeigniter-dwoo
 */

class MY_Parser extends CI_Parser {

	private $_ci;

	function __construct($config = array())
	{
		$this->_ci = & get_instance();
		
		if(file_exists($filepath = APPPATH.'third_party/Lex/Parser.php'))
		{
			if ( ! class_exists('Lex\Parser'))
			{
				include $filepath;
			}
		}
		
		if(file_exists($filepath = APPPATH.'third_party/Lex/ParsingException.php'))
		{
			if ( ! class_exists('Lex\ParsingException'))
			{
				include $filepath;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a view file
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public function parse($view, $data = array(), $return = FALSE, $is_include = FALSE)
	{				
		$view = (pathinfo($view, PATHINFO_EXTENSION)) ? $view : $view.EXT;

		$filepath = '';
		
		// Get module views
		if (method_exists( $this->_ci->router, 'fetch_module' ))
		{
			$module = $this->_ci->router->fetch_module();
			foreach (Modules::$locations as $location => $offset)
			{
				if (file_exists($location.$module.'/views/'.$view))
				{
					$filepath = $location.$module.'/views/'.$view;
					break;
				}
			}
		}
		
		// So there are no module views
		foreach($this->_ci->load->get_package_paths() as $path)
		{
			if (file_exists($path.'views/'.$view))
			{
				$filepath = $path.'views/'.$view;
				break;
			}
		}
				
		if (empty($filepath))
		{
			show_error('Unable to load the requested file: '.pathinfo($view, PATHINFO_BASENAME));
		}
		
		$string = @file_get_contents($filepath);
		
		return $this->_parse($string, $data, $return, $is_include);		
	}

	// --------------------------------------------------------------------
	
	/**
	 * Evaluates the PHP in the given string.
	 *
	 * @param   string  $text  Text to evaluate
	 * @return  string
	 */
	public function parse_php($string)
	{
		$string = preg_replace("/<\?xml(.*?)\?>/Ui", "&lt;?xml\\1?&gt;", htmlspecialchars_decode($string));		
		extract($this->_ci->load->_ci_cached_vars);		
		ob_start();
		echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $string)));
		
		return ob_get_clean();
	}
	
	/**
	 *  String parse
	 *
	 * Parses pseudo-variables contained in the string content,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public function parse_string($view, $data = array(), $return = FALSE, $is_include = FALSE)
	{
		if ( ! file_exists($view))
		{
			show_error('Unable to load the requested file: '.pathinfo($view, PATHINFO_BASENAME));
		}
		
		$string = @file_get_contents($view);
		return $this->_parse($string, $data, $return, $is_include);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	protected
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function _parse($string, $data, $return = FALSE, $is_include = FALSE)
	{	
		// Start benchmark
		$this->_ci->benchmark->mark('parse_start');

		// Convert from object to array
		is_array($data) or $data = (array) $data;

		$this->_ci->load->_ci_cached_vars = array_merge($data, $this->_ci->load->_ci_cached_vars);

		$parser = new Lex\Parser();
		$parser->scopeGlue(':');
		$parser->cumulativeNoparse(TRUE);
		$string = preg_replace("/<\?xml(.*?)\?>/Uis", "&lt;?xml\\1?&gt;", $string);
		$string = $parser->parse($string, $this->_ci->load->_ci_cached_vars, array($this, 'parser_callback'));		
		$string = $this->parse_php($string);
		$string = preg_replace("/&lt;\?xml(.*?)\?&gt;/Uis", "<?xml\\1?>", $string);
		// Finish benchmark
		$this->_ci->benchmark->mark('parse_end');
		
		// Return results or not ?
		if ( ! $return)
		{
			$this->_ci->output->append_output($string);
			return;
		}

		return $string;
	}

	// --------------------------------------------------------------------

	/**
	 * Callback from template parser
	 *
	 * @param	array
	 * @return	 mixed
	 */
	public function parser_callback($plugin, $attributes, $content)
	{
		$this->_ci->load->library('plugins');

		$return_data = $this->_ci->plugins->locate($plugin, $attributes, $content);
		
		if (is_array($return_data) && $return_data)
		{
			if ( ! $this->_is_multi($return_data))
			{
				$return_data = $this->_make_multi($return_data);
			}
			
			// $content = $data['content']; # TODO What was this doing other than throw warnings in 2.0?
			$parsed_return = '';

			$parser = new Lex\Parser();
			$parser->scopeGlue(':');
			
			if( ! empty($attributes['noloop']))
			{
				$parsed_return .= $parser->parse($content, $return_data, array($this, 'parser_callback'));
				unset($attributes['noloop']);
			}
			else 
			{
				foreach ($return_data as $result)
				{				
					$parsed_return .= $parser->parse($content, (array)$result, array($this, 'parser_callback'));				
				}
			}

			unset($parser);

			$return_data = $parsed_return;
		}

		return $return_data ? $return_data : NULL;
	}

	// ------------------------------------------------------------------------

	/**
	 * Ensure we have a multi array
	 *
	 * @param	array
	 * @return	 int
	 */
	private function _is_multi($array)
	{
		return (count($array) != count($array, 1));
	}

	// --------------------------------------------------------------------

	/**
	 * Forces a standard array in multidimensional.
	 *
	 * @param	array
	 * @param	int		Used for recursion
	 * @return	array	The multi array
	 */
	private function _make_multi($flat, $i=0)
	{
		$multi = array();
		$return = array();
		foreach ($flat as $item => $value)
		{
			if (is_object($value))
			{
				$return[$item] = (array) $value;
			}
			else
			{
				$return[$i][$item] = $value;
			}
		}
		return $return;
	}

}

// END MY_Parser Class

/* End of file MY_Parser.php */