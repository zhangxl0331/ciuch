<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Theme Plugin
 *
 * Load partials and access data
 *
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Plugins
 */
class Plugin_Theme extends Plugin
{
	
	protected $viewpaths = array();
	
	public function __construct()
	{
		$this->load->helper('html');
		$this->load->library('template');	
	}
	/**
	 * Partial
	 *
	 * Loads a theme partial
	 *
	 * Usage:
	 * {{ theme:partial name="header" }}
	 *
	 * @return string The final rendered partial view.
	 */
	public function partial()
	{
		$name = $this->attribute('name');
		$module = $this->attribute('module');
		$view = 'partials/'.$name;
		$data = $this->load->get_vars();
		if ($module)
		{
			return $this->module_view($module, $view, $data);
		}
		else 
		{
			if (file_exists($this->template->get_theme_path().'views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT)))
			{
				$path = $this->template->get_theme_path().'views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT);
			}
			else
			{
				$path	= APPPATH.'views/'.$view.(pathinfo($view, PATHINFO_EXTENSION) ? '' : EXT);
			}
			$string = $this->load->_ci_load(array('_ci_path' => $path, '_ci_vars' => $data, '_ci_return' => TRUE));
			
			return $this->parser->parse_string($string, $data, TRUE);
		}		
	}
	
	public function get_filepath($file, $type)
	{
		if($remote = (strpos($file, '//') !== false))
		{
			return $file;
		}
		
		if(file_exists($filepath = $this->load->get_var('template_views').$type.'/'. $file))
		{
			return $filepath;
		}
		
		return FALSE;
	}

	/**
	 * Theme CSS
	 *
	 * Insert a CSS tag with location based for url or path from the theme or module
	 *
	 * Usage:
	 *  {{ theme:css file="" }}
	 *
	 * @return string The link HTML tag for the stylesheets.
	 */
	public function css()
	{
		$file = $this->attribute('file');
		$title = $this->attribute('title');
		$media = $this->attribute('media');
		$type = $this->attribute('type', 'text/css');
		$rel = $this->attribute('rel', 'stylesheet');
		$theme = $this->attribute('theme');

		return link_tag($this->css_url($file, $theme), $rel, $type, $title, $media);
	}

	/**
	 * Theme CSS URL
	 *
	 * Usage:
	 *  {{ theme:css_url file="" }}
	 *
	 * @return string The CSS URL
	 */
	public function css_url()
	{
		$file = $this->attribute('file');
		
		return $this->get_filepath($file, 'css');
	}

	/**
	 * Theme Image
	 *
	 * Insert a image tag with location based for url or path from the theme or module
	 *
	 * Usage:
	 *   {{ theme:image file="" }}
	 *
	 * @return string An empty string or the image tag.
	 */
	public function image()
	{
		$file = $this->attribute('file');
		$alt = $this->attribute('alt', $file);
		$attributes = $this->attributes();

		foreach (array('file', 'alt') as $key)
		{
			if (isset($attributes[$key]))
			{
				unset($attributes[$key]);
			}
			else if ($key == 'file')
			{
				return '';
			}
		}

		try
		{
			return Asset::img($file, $alt, $attributes);
		}
		catch (Asset_Exception $e)
		{
			return '';
		}
	}

	/**
	 * Theme Image URL
	 *
	 * Usage:
	 *   {{ theme:image_url file="" }}
	 *
	 * @return string The image URL
	 */
	public function image_url()
	{
		$file = $this->attribute('file');
		return $this->get_filepath($file, 'img');
	}

	/**
	 * Theme JS
	 *
	 * Insert a JS tag with location based for url or path from the theme or module
	 *
	 * Usage:
	 *
	 * {{ theme:js file="" }}
	 *
	 * @param string $return Not used
	 * @return string An empty string or the script tag.
	 */
	public function js($return = '')
	{
		$file = $this->attribute('file');
		return '<script src="'.$this->config->base_url().$this->js_url($file).'" type="text/javascript"></script>';
	}

	/**
	 * Theme JS URL
	 *
	 * Usage:
	 *   {{ theme:js_url file="" }}
	 *
	 * @return string The javascript asset URL.
	 */
	public function js_url()
	{
		$file = $this->attribute('file');
		return $this->get_filepath($file, 'js');
	}

	/**
	 * Theme Favicon
	 *
	 * Insert a link tag for favicon from your theme
	 *
	 * Usage:
	 *   {{ theme:favicon file="" [rel="foo"] [type="bar"] }}
	 *
	 * @return string The link HTML tag for the favicon.
	 */
	public function favicon()
	{
		$file = $this->get_filepath($this->attribute('file', 'favicon.ico'), 'img');

		$rel = $this->attribute('rel', 'shortcut icon');
		$type = $this->attribute('type', 'image/x-icon');
		$is_xhtml = in_array($this->attribute('xhtml', 'true'), array('1', 'y', 'yes', 'true'));

		$link = '<link ';
		$link .= 'href="'.$this->config->base_url().$file.'" ';
		$link .= 'rel="'.$rel.'" ';
		$link .= 'type="'.$type.'" ';
		$link .= ($is_xhtml ? '/' : '').'>';

		return $link;
	}

    /**
     * Theme Language line
     *
     * Fetch a single line of text from the language array
     *
     * Usage:
     *   {{ theme:lang lang="theme" line="theme_title" [default="PyroCMS"] }}
     *
     * @return string.
     */
    public function lang()
    {
        $lang_file = $this->attribute('lang');
        $line = $this->attribute('line');
        $default = $this->attribute('default');
        // Return an empty string as the attribute LINE is missing
        if ( !isset($line) ) {
            return "";
        }

        $deft_lang = CI::$APP->config->item('language');
        if ($lang = Modules::load_file($lang_file.'_lang', CI::$APP->template->get_theme_path().'/language/'.$deft_lang.'/', 'lang'))
        {
            CI::$APP->lang->language = array_merge(CI::$APP->lang->language, $lang);
            CI::$APP->lang->is_loaded[] = $lang_file . '_lang'.EXT;
            unset($lang);
        }
        $value = $this->lang->line($line);

        return $value?$value:$default;
    }

}