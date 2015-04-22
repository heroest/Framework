<?php
namespace lightning\system\core;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class security extends SystemClass
{
	private $config;
	private $csrf;

	public function __construct()
	{
		$this->config = $this->get_config('security');
	}

	public function url_check()
	{
		$url = $this->request->getQuery('_url');
		$url = implode('', explode('/', $url));
		$pattern = '/[^' . $this->config['permitted_uri_chars'] . ']/';
		if(preg_match($pattern, $url)){
			show_error('Error in security->url_check(): url contains disallowed characters.');
		}
		return;
	}

	public function add_security_header()
	{
		$headers = $this->config['security_header'];
		foreach($headers as $header) {
			header($header);
		}
	}

	private function csrf_generate()
	{
		$salt     = $this->config['csrf_salt'];
		$duration = $this->config['csrf_duration'];
		$limit	  = 0 - $this->config['csrf_token_limit'];
		$csrf['name']  = Crandom('lc', mt_rand(1,4)) . Chash();
		$csrf['token'] = Chash(Crandom('all'), $salt);
		$stored  = $this->session->get('CSRF');
		$stored  = empty($stored) ? array() : $stored;
		$stored[$csrf['name']] = array(
										'token'   => $csrf['token'],
										'expires' => time() + $duration,
									);
		$this->session->set(  array('CSRF' => array_slice($stored, $limit))  );
		$this->csrf = $csrf;
	}

	public function csrf_intput($renew = false)
	{
		if(empty($this->csrf) or $renew == True) {
			$this->csrf_generate();
		}
		$name  = $this->csrf['name'];
		$token = $this->csrf['token'];
		$str  = "<input type='hidden' name='{$name}' value='{$token}' />";
		return $str;
	}

	public function csrf_link($url, $renew = false)
	{
		if(empty($this->csrf) or $renew == True) {
			$this->csrf_generate();
		}
		$token = $this->csrf['token'];
		$str   = "$url/$token";
		return $str;
	}

	public function csrf_token($renew = false)
	{
		if(empty($this->csrf) or $renew == True) {
			$this->csrf_generate();
		}
		return $this->csrf;
	}

	public function csrf_validate_post()
	{
		$expires = '';
		$csrf    = $this->session->get('CSRF');
		$index   = '';
		foreach($csrf as $name => $obj) {
			$index   = $name;
			$expires = $obj['expires'];
			$expect  = $obj['token'];
			$match   = $this->request->getPost($name);
			if($match) break;
		}
		if(! $match) {
			show_error("Error in security->csrf_validate_post(): CSRF-Token is missing");

		} else if($expires < time()) {
			$this->remove_csrf($index);
			show_error("Error in security->csrf_validate_post(): CSRF-Token is expired");

		} else if(Cmatch($expect, $match)){
			$this->remove_csrf($index);
			return;

		} else {
			$this->remove_csrf($index);
			show_error("Error in security->csrf_validate_post(): CSRF-Token is incorrect");

		}
	}

	public function csrf_validate($match)
	{
		$csrf = $this->session->get('CSRF');
		$flag = False;
		foreach($csrf as $name => $obj) {
			$index   = $name;
			$token   = $obj['token'];
			$expires = $obj['expires'];
			if(Cmatch($match, $token)) {
				$flag = True;
				break;
			}
		}
		if(! $flag) {
			show_error("Error in security->csrf_validate_get(): CSRF-Token is incorrect");

		} else if($expires < time()) {
			$this->remove_csrf($index);
			show_error("Error in security->csrf_validate_get(): CSRF-Token is expired");

		} else {
			$this->remove_csrf($index);
		}
	}

	private function remove_csrf($index)
	{
		$csrf = $this->session->get('CSRF');
		if(isset($csrf[$index])) unset($csrf[$index]);
		$this->session->set(array('CSRF' => $csrf));
	}
	


	public function clean_key($str)
	{
		$pattern = '/[^a-zA-Z0-9_-]/';
		if(preg_match($pattern, $str)){
			show_error('Error in request:security->clean_key(): key contains disallowed characters.');
		} else {
			return $str;
		}
	}

	public function clean_value($str)
	{
		$case = $this->config['xss_clean'];
		switch ($case) {
			case 'no_html':
				$str = htmlentities($str);
				break;

			case 'no_script':
				$str = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $str);
				break;

			case 'no_check':
				break;

			default:
				$str = htmlentities($str);
				break;

		}
		return $str;
		
	}
	

}
?>