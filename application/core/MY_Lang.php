<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CodeIgniter i18n library by Jérôme Jaglale
// http://maestric.com/en/doc/php/codeigniter_i18n
// version 10 - May 10, 2012

class MY_Lang extends CI_Lang {

	/**************************************************
	 * configuration
	 ***************************************************/



	// special URIs (not localized)
	public $special = array(
		"admin",
		"login",
		"logout",
		"payment",
		"pay",
		"gapay"
	);

	public $default_load_language = 'english';

	// where to redirect if no language in URI
	public  $default_uri = '';

	/**************************************************/


	function __construct()
	{
		parent::__construct();
		global $CFG;
		global $URI;

		$segment = $URI->segment(1);
		if (isset($CFG->item('languages')[$segment]))	// URI with language -> ok
		{
			$language = $CFG->item('languages')[$segment];
			setcookie(LANGUAGE_DEFAULT_COOKIE, $segment, time() + 360 * 24 * 60 * 60, '/');
			$CFG->set_item('language', $language);
		}
		else if($this->is_special($segment)) // special URI -> no redirect
		{
			$cookie_lang = $_COOKIE[LANGUAGE_DEFAULT_COOKIE];
			if (!array_key_exists($cookie_lang, $CFG->item('languages'))) {
				$cookie_lang = $this->default_lang();
			}
			$lang = (empty($cookie_lang) ? $this->default_lang() : $cookie_lang);
			$CFG->set_item('language', $CFG->item('languages')[$lang]);
			return;
		}
		else	// URI without language -> redirect to default_uri
		{
			$cookie_lang = $_COOKIE[LANGUAGE_DEFAULT_COOKIE];
			if (!array_key_exists($cookie_lang, $CFG->item('languages'))) {
				$cookie_lang = $this->default_lang();
			}
			$lang = (empty($cookie_lang) ? $this->default_lang() : $cookie_lang);
			$CFG->set_item('language', $CFG->item('languages')[$lang]);
			$query = $_SERVER["QUERY_STRING"] ? '?' . $_SERVER["QUERY_STRING"] : '';
			$endpoint = $CFG->site_url($this->localized($URI->uri_string)).$query;
			header("Location: " . $endpoint . $query, TRUE, 302);
			exit;
		}

	}

	// get current language
	// ex: return 'en' if language in CI config is 'english'
	function lang()
	{
		global $CFG;
		$language = $CFG->item('language');

		$lang = array_search($language, $CFG->item('languages'));
		if ($lang)
		{
			return $lang;
		}

		return NULL;	// this should not happen
	}

	function is_special($uri)
	{

		$exploded = explode('/', $uri);
		if (in_array($exploded[0], $this->special))
		{
			return TRUE;
		}
		global $CFG;
		if(isset($CFG->item('languages')[$uri]))
		{
			return TRUE;
		}
		global $URI;
		if (preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $URI->uri_string)) {
			return TRUE;
		}
		return FALSE;
	}

	function switch_uri($lang)
	{
		$CI =& get_instance();

		$uri = $CI->uri->uri_string();
		if ($uri != "")
		{
			$exploded = explode('/', $uri);
			if($exploded[0] == $this->lang())
			{
				$exploded[0] = $lang;
			}
			$uri = implode('/',$exploded);
		} else {
			$uri = $lang;
		}
		return "/".$uri;
	}

	// is there a language segment in this $uri?
	function has_language($uri)
	{
		$first_segment = NULL;

		$exploded = explode('/', $uri);
		if(isset($exploded[0]))
		{
			if($exploded[0] != '')
			{
				$first_segment = $exploded[0];
			}
			else if(isset($exploded[1]) && $exploded[1] != '')
			{
				$first_segment = $exploded[1];
			}
		}
		global  $CFG;
		if($first_segment != NULL)
		{
			return isset($CFG->item('languages')[$first_segment]);
		}

		return FALSE;
	}

	// default language: first element of $this->languages
	function default_lang()
	{
		global $CFG;
		foreach ($CFG->item('languages') as $lang => $language) {
			return $lang;
		}
	}

	// add language segment to $uri (if appropriate)
	function localized($uri1)
	{
		if ($this->has_language($uri1) || $this->is_special($uri1) || preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri1)) {
			//return $URI->uri_string;
			// we don't need a language segment because:
			// - there's already one or
			// - it's a special uri (set in $special) or
			// - that's a link to a file
		}
		else
		{
			$u = $uri1;
			$uri1 = $this->lang() .  ($u ?'/'.$u:'');
//			$uri1 = $this->lang() . '/' . $uri1;
		}

		return $uri1;
	}

}

/* End of file */
