<?php
namespace lightning\system\MVC;
use lightning\system\core\SystemClass;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class AbstractController extends SystemClass
{
	public function __construct() {}

	public function render($template, $data=array())
	{
		foreach($data as $key=>$value)
		{
			$$key = $value;
		}
		$template_path = view_path . $template . '.phtml';
		if(! file_exists($template_path)) {
			show_error("Error in ActionController->render(): view/$template.phtml file is missing");
		} else {
			include_once($template_path);
		}	
	}
}

?>