<?php namespace lightning\application\controller;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\MVC\AbstractController;
use lightning\application\model\user;


class user_controller extends AbstractController
{
	public function loginAction()
	{
		$viewArray['title'] = 'Login';

		if($this->request->get_request_method() == 'post'){
			$username = $this->request->getPost('username');
			$password = $this->request->getPost('password');
			$remember = $this->request->getPost('remember-me');

			$user = new user();
			$user_data = $user->login($username, $password);
			if(! $user_data) {
				$viewArray['error'] = 'Wrong Username or Password!';
				$viewArray['title'] = 'Fail to login';
			} else {
				$this->session->set(array(
					'user_login' => array(
							'username' => $user_data['username'],
							'user_id'  => $user_data['id'],
					)
				));
				if($remember === 'true') {
					$this->session->set(array(
						'remember' => array(
							'username' => $username,
							'password' => $password,
							'remember' => $remember,
							)
					));
				} else {
					$this->session->delete('remember');
				}
			}

		}

		$storage = $this->session->get('remember');
		$viewArray['username'] = isset($storage['username']) ? $storage['username'] : '';
		$viewArray['password'] = isset($storage['password']) ? $storage['password'] : '';
		$viewArray['remember'] = isset($storage['remember']) ? $storage['remember'] : 'false';
	
		$this->render('layout/header', $viewArray);
		$this->render('user/login', $viewArray);
		$this->render('layout/footer');

	}

	public function registerAction()
	{
		$viewArray['title'] = 'Register';
		if($this->request->get_request_method() == 'post'){

		}
		$this->render('layout/header', $viewArray);
		$this->render('user/register', $viewArray);
		$this->render('layout/footer');

	}
}

?>