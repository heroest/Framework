<?php namespace lightning\application\controller;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\MVC\AbstractController;

class game_controller extends AbstractController
{
	public function indexAction()
	{
		$title = 'Hex-a-bomb';
		$viewArray['title'] = $title;
		$this->render('layout/header', $viewArray);
		$this->render('game/index',	   $viewArray);
		$this->render('layout/footer');
	}
}

?>