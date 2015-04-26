<?php namespace lightning\application\controller;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\MVC\AbstractController;

class index_controller extends AbstractController
{
	public function indexAction()
	{
		$footer = date('Y-m-d H:i:s');
		$body = "Thanks for using Lightning Framework";
		$data['title']  = 'Home';
		$data['body']   = $body;
		$data['footer'] = $footer;

		$this->render('layout/header', $data);
		$this->render('index/welcome', $data);
		$this->render('layout/footer', $data);
	}

	public function pathAction()
	{

		$this->render('layout/header', array('title' => 'path'));
		$this->render('index/path');
	}

}

?>