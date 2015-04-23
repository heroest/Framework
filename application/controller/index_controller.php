<?php
namespace lightning\application\controller;
use lightning\system\MVC\AbstractController;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class index_controller extends AbstractController
{
	public function indexAction()
	{
		$title = $this->myPlugin->welcome('Lightning');
		$footer = date('Y-m-d H:i:s');
		$body = "Thanks for using Lightning Framework";
		$data['title'] = $title;
		$data['footer'] = $footer;
		$data['body'] = $body;

		$this->cache->set('name', 'neo');
		$this->session->set(array('user'=>'neo'));

		var_dump($this->session->get('user'));
		var_dump($this->cache->has('name'));
		var_dump($this->cache->has('abc'));

		$this->render('layout/header', $data);
		$this->render('index/welcome', $data);

	}


	public function testAction($a,$b,$c)
	{
		var_dump($a);
		var_dump($b);
		var_dump($c);
	}

	public function pathAction()
	{

		$this->render('layout/header', array('title' => 'path'));
		$this->render('index/path');
	}

}

?>