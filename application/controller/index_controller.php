<?php namespace lightning\application\controller;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\MVC\AbstractController;

class index_controller extends AbstractController
{
	public function indexAction()
	{
		$footer = date('Y-m-d H:i:s');
		$data['title']  = 'Home';
		$data['footer'] = $footer;

		$this->render('layout/header', $data);
		$this->render('index/welcome', $data);
		$this->render('layout/footer', $data);
	}

}

?>