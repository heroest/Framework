<?php namespace lightning\system\core\cache;
if(! defined('framework_name')) exit('No direct script access allowed');


Interface Interface_cache_handler
{
	public function get($key);


	public function get_arr($key_arr);


	public function set($key, $value, $timeout);


	public function set_arr($arr, $timeout);


	public function add($key, $value, $timeout);


	public function add_arr($arr, $timeout);


	public function has($key);


	public function has_arr($arr);


	public function delete($key);


	public function destory();
}

?>