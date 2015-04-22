<?php
namespace lightning\system\core;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class router extends SystemClass
{
	private $map;

	public function __construct() 
	{
		$this->map = require_once(config_path . 'route.php');
	}

	public function parse($url)
	{
		if(! $url or empty($url)) {
			list($controller, $action) = explode('/', $this->map['_Default']);
			return array($controller, $action, array());
		} else {
			foreach($this->map as $pattern => $dispatch) {
				$pattern = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $pattern);
				if(preg_match("#^" . $pattern . "$#", $url, $param_arr)) {
					$action_arr = explode('/', $dispatch);
					array_shift($param_arr);
					return array($action_arr[0], $action_arr[1], $param_arr);
				}
			}
		}
		$full_url = base_url($url);
		show_404("Error in URL->parse(): '$full_url' was not found on this server");
	}

}
?>