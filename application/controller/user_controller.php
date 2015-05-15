<?php namespace lightning\application\controller;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\MVC\AbstractController;
use lightning\application\model\service\user;


class user_controller extends AbstractController
{
	public function loginAction()
	{
		$viewArray['title'] = 'Login';

		if($this->request->get_request_method() == 'post'){
			$username = $this->request->getPost('username');
			$password = $this->request->getPost('password');
			$remember = $this->request->getPost('remember-me');

			$this->session->delete('remember');
			$user = new user();
			$user_data = $user->login($username, $password);

			if($user_data === false) {
				$viewArray['error'] = 'Error: Username or Password is incorrect.';
				$viewArray['title'] = 'Fail to login';
			} else {
				$this->session->set(array(
					'user_login' => array(
							'username' => $user_data['username'],
							'user_id'  => $user_data['user_id'],
					)
				));
				if($remember === 'true') {
					$this->session->set(array(
						'remember' => array(
							'username' => $username,
							'password' => $password,
							'remember' => $remember,
							)
					), true);
				}

				if($this->session->has('current_page')) {
					$url = $this->session->pop('current_page');
					redirect(base_url($url));
				} else {
					redirect(base_url());
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
			$post_data = (array)$this->request->getPost();
			$user = new user();
			$result = $user->register($post_data);
			if($result === false) {
				$viewArray['error'] = $user->get_error();
			} else {
				$this->session->set(array('user_login' => array(
						'username' => $result['username'],
						'user_id'  => $result['user_id'],
					)
				));
				if($this->session->has('current_page')) {
					redirect(base_url($this->session->pop('current_page')));
				} else {
					redirect(base_url());
				}
			}
		}
		$this->render('layout/header', $viewArray);
		$this->render('user/register', $viewArray);
		$this->render('layout/footer');

	}

	public function logoutAction()
	{
		$this->session->delete('user_login');
		if($this->session->has('current_page')) {
			redirect(base_url($this->session->pop('current_page')));
		} else {
			redirect(base_url());
		}
	}
}

?>