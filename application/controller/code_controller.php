<?php 
if ( ! defined('root_path')) exit('No direct script access allowed');

class code_controller extends \SF_core\SF_controller
{
	public function __construct(){}

	public function viewcodeAction()
	{
		$data['page']['title'] = 'MVC_Code';
		$data['controller_code'] = highlight_file(app_path . 'controller/index_controller.php', true);
		$data['view_code'] = highlight_file(app_path . 'view/index/index_view.php', true);
		$data['model_code'] = highlight_file(app_path . 'model/posts.php', true);
		$this->render('_general/header', $data);
		$this->render('code/code_view', $data);
		$this->render('_general/footer', $data);
	}
}
?>