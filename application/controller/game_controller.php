<?php
namespace lightning\application\controller;
use lightning\system\MVC\AbstractController;

if ( ! defined('framework_name')) exit('No direct script access allowed');
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