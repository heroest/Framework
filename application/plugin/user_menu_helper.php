<?php
namespace lightning\application\plugin;
use lightning\system\MVC\AbstractController;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class user_menu_helper extends AbstractController
{
	public function welcome($name)
	{
		return "Welcome, $name";
	}

	public function draw()
	{
		$user = $this->session->get('user_login');
		if($user) {
			$username = $user['username'];
			$logout_link = $this->security->csrf_link('/user/logout');
			$str = "
				<li class='dropdown'>
					<a href='javascript:void()' class='dropdown-toggle nav-element' data-toggle='dropdown' role='button' aria-expanded='false'>
						<span class='glyphicon glyphicon-user'></span><br /> [{$username}] <span class='caret'></span>
					</a>
					<ul class='dropdown-menu' role='menu'>
						<li><a href='/user/profile'>Profile <span class='glyphicon glyphicon-book'></span></a></li>
						<li><a href='{$logout_link}'>Logout <span class='glyphicon glyphicon-log-out'></span></a></li>
					</ul>
				</li>
				";
		} else {
			$str = "<li class='dropdown'>
					<a href='javascript:void()' class='dropdown-toggle nav-element' data-toggle='dropdown' role='button' aria-expanded='false'>
						<span class='glyphicon glyphicon-user'></span><br />
						User<br />
					</a>
					<ul class='dropdown-menu' role='menu'>
						<li><a href='/user/login'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>
						<li><a href='/user/register'><span class='glyphicon glyphicon-edit'></span> Register</a></li>
					</ul>
				</li>";
		}
		return $str;
	}
}


?>