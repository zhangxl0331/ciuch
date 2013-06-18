<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper Plugin
 *
 * Various helper plugins.
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Helper extends Plugin
{

	/**
	 * A flag for the counter functions for loops.
	 *
	 * @var boolean
	 */
	static $_counter_increment = TRUE;

	/**
	 * Data
	 *
	 * Loads a theme partial
	 *
	 * Usage:
	 * {{ helper:lang line="foo" }}
	 *
	 * @return string The language string
	 */
	public function lang()
	{
		$line = $this->attribute('line');
		return $this->lang->line($line);
	}

	/**
	 * Config
	 *
	 * Displays a config item
	 *
	 * Usage:
	 * {{ helper:config item="foo" }}
	 *
	 * @return string The configuration key's value
	 */
	public function config()
	{
		$item = $this->attribute('item');
		return config_item($item);
	}
	
	

	/**
	 * Gravatar
	 * 
	 * Provides for showing Gravatar images.
	 * 
	 * Usage:
	 * {{ helper:gravatar email="some-guy@example.com" size="50" rating="g" url-only="true" }}
	 *
	 * @return string An image element or the URL to the gravatar.
	 */
	public function gravatar()
	{
		$email = $this->attribute('email', '');
		$size = $this->attribute('size', '50');
		$rating = $this->attribute('rating', 'g');
		
		$url_only = (bool) in_array($this->attribute('url-only', 'false'), array('1', 'y', 'yes', 'true'));

		return gravatar($email, $size, $rating, $url_only);
	}

	/**
	 * Replace
	 * 
	 * Replaces whitespace in the content.
	 * 
	 * Usage:
	 * {{ helper:replace replace="   " }} Ouputs all whitespace replaced by three spaces.
	 *
	 * @return string The final content string.
	 */
	public function strip()
	{
		return preg_replace('!\s+!', $this->attribute('replace', ' '), $this->content());
	}

	/**
	 * Add counting to tag loops
	 *
	 * @example 
	 * Usage:
	 * {{ blog:posts }}
	 * 		{{ helper:count }} -- {{title}}
	 * {{ /blog:posts }}
	 *
	 * Outputs:
	 * 1 -- This is an example title
	 * 2 -- This is another title
	 *
	 * @example
	 * Usage:
	 * {{ blog:posts }}
	 * 		{{ helper:count mode="subtract" start="10" }} -- {{title}}
	 * {{ /blog:posts }}
	 *
	 * Outputs:
	 * 10 -- This is an example title
	 * 9  -- This is another title
	 * 
	 * You can add a second counter to a page by setting a unique identifier:
	 * {{ files:listing folder="foo" }}
	 * 		{{ helper:count identifier="files" return="false" }}
	 * 		{{name}} -- {{slug}}
	 * 	{{ /files:listing }}
	 * 	You have {{ helper:show_count identifier="files" }} files.
	 */
	public function count()
	{
		static $count = array();
		
		$identifier = $this->attribute('identifier', 'default');

		// Use a default of 1 if they haven't specified one and it's the first iteration
		if (!isset($count[$identifier]))
		{
			$count[$identifier] = $this->attribute('start', 1);
		}

		// lets check to see if they're only wanting to show the count
		if (self::$_counter_increment)
		{
			// count up unless they specify to "subtract"
			$value = ($this->attribute('mode') == 'subtract') ? $count[$identifier]-- : $count[$identifier]++;

			// go ahead and increment but return an empty string
			if (strtolower($this->attribute('return')) === 'false')
			{
				return '';
			}
			
			return $value;
		}
		
		return $count[$identifier];
	}

	/**
	 * Get the total count of the set.
	 * 
	 * Not to be used without a {{ helper:count }}
	 * 
	 * Usage:
	 * {{ helper:show_count identifier="files" }}
	 *
	 * 	Outputs:
	 * 	Test -- test
	 * 	Second -- second
	 * 	You have 2 files.
	 * 
	 * @return int The total set count.
	 */
	public function show_count()
	{
		self::$_counter_increment = FALSE;

		return self::count();
	}
	
	/**
	 * Get the total count of the set.
	 *
	 * Not to be used without a {{ helper:count }}
	 *
	 * Usage:
	 * {{ helper:show_count identifier="files" }}
	 *
	 * 	Outputs:
	 * 	Test -- test
	 * 	Second -- second
	 * 	You have 2 files.
	 *
	 * @return int The total set count.
	 */
	function page()
	{
		$config['base_url']				= $this->attribute('base_url');
		$config['prefix']				= $this->attribute('prefix');
		$config['suffix']				= $this->attribute('suffix');
		$config['total_rows']			= $this->attribute('total_rows');
		$config['per_page']				= $this->attribute('per_page');
		$config['num_links']			= $this->attribute('num_links');
		$config['cur_page']				= $this->attribute('cur_page');
		$config['use_page_numbers']		= $this->attribute('use_page_numbers');
		$config['first_link']			= $this->attribute('first_link');
		$config['next_link']			= $this->attribute('next_link');		
		$config['prev_link']			= $this->attribute('prev_link');
		$config['last_link']			= $this->attribute('last_link');
		$config['uri_segment']			= $this->attribute('uri_segment');
		$config['full_tag_open']		= $this->attribute('full_tag_open');
		$config['full_tag_close']		= $this->attribute('full_tag_close');
		$config['first_tag_open']		= $this->attribute('first_tag_open');
		$config['first_tag_close']		= $this->attribute('first_tag_close');
		$config['last_tag_open']		= $this->attribute('last_tag_open');
		$config['last_tag_close']		= $this->attribute('last_tag_close');
		$config['first_url']			= $this->attribute('first_url');		
		$config['cur_tag_open']			= $this->attribute('cur_tag_open');
		$config['cur_tag_close']		= $this->attribute('cur_tag_close');
		$config['next_tag_open']		= $this->attribute('next_tag_open');
		$config['next_tag_close']		= $this->attribute('next_tag_close');
		$config['prev_tag_open']		= $this->attribute('prev_tag_open');
		$config['prev_tag_close']		= $this->attribute('prev_tag_close');
		$config['num_tag_open']			= $this->attribute('num_tag_open');
		$config['num_tag_close']		= $this->attribute('num_tag_close');
		$config['page_query_string']	= $this->attribute('page_query_string');
		$config['query_string_segment']	= $this->attribute('query_string_segment');
		$config['display_pages']		= $this->attribute('display_pages');
		$config['anchor_class']			= $this->attribute('anchor_class');

		$this->load->library('pagination');
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}
	
	function formhash()
	{
		$uid = $this->attribute('uid', $this->member_l->uid);
		$sitekey = $this->attribute('sitekey', $this->config_l->sitekey);
		$hashadd = $this->attribute('hashadd', '');
		// 		$hashadd = defined('IN_ADMINCP') ? 'Only For UCenter Home AdminCP' : '';
		return substr(md5(substr(time(), 0, -7).'|'.$uid.'|'.md5($sitekey).'|'.$hashadd), 8, 8);
			
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
		$this->config->load('parser');

		if (function_exists($name) and in_array($name, config_item('allowed_functions')))
		{
			return call_user_func_array($name, $this->attributes());
		}
		
		return 'Function not found or is not allowed';
	}
}