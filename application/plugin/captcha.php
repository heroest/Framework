<?php namespace lightning\application\plugin;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\core\SystemClass;

class captcha extends SystemClass
{
	private $config;

	public function __construct()
	{
		$this->config = $this->get_config('captcha');
	}

	public function form_element()
	{
		$public_key = $this->config['public_key'];
		$str  = "<div class='g-recaptcha' data-sitekey='$public_key'></div>";
		$str .= "<script src='https://www.google.com/recaptcha/api.js'></script>";
		return $str;
	}

	public function verify()
	{
		$response   = $this->request->getPost('g-recaptcha-response');
		$secret_key = $this->config['secret_key'];
		$post_data  = "secret={$secret_key}&response={$response}";

		$ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$json_data = curl_exec($ch);
		$data = json_decode($json_data, true);
		if(! $data['success']) {
			$msg = implode(", ", $data['error-codes']);
			show_error("Error in Captcha->Verify(): {$msg}");
		}
	}

}


?>