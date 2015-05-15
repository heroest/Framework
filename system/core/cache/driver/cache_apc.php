<?php
namespace lightning\system\core\cache\driver;
use lightning\system\core\SystemClass;
use lightning\system\core\cache\Interface_cache_handler;

if(! defined('framework_name')) exit('No direct script access allowed');
class cache_apc extends SystemClass implements Interface_cache_handler
{
	private $prefix;
	private $config;

	public function __construct()
	{
		$this->config = $this->get_config('cache');
		$this->prefix = $this->config['key_name_prefix'];
	}

	public function get($key)
	{
		$key = "{$this->prefix}_{$key}";
		return apc_fetch($key);
	}

	public function get_arr($key_arr)
	{
		$prefix = $this->prefix;
		$key_arr = array_map(function($item) use ($prefix) {
			return "{$prefix}_item";
		}, $key_arr);
		return apc_fetch($key_arr);
	}

	public function set($key, $value, $timeout)
	{
		$key = "{$this->prefix}_{$key}";
		return apc_store($key, $value, $timeout);
	}

	public function set_arr($arr, $timeout)
	{
		$data = array();
		foreach($arr as $key=>$value) {
			$key = "{$this->prefix}_{$key}";
			$data[$key] = $value;
		}
		return apc_store($data, $timeout);
	}

	public function add($key, $value, $timeout)
	{
		$key = "{$this->prefix}_{$key}";
		return apc_add($key, $value, $timeout);
	}

	public function add_arr($arr, $timeout)
	{
		$data = array();
		foreach($arr as $key=>$value) {
			$key = "{$this->prefix}_{$key}";
			$data[$key] = $value;
		}
		return apc_add($data, $timeout);
	}

	public function has($key)
	{
		$key = "{$this->prefix}_{$key}";
		return apc_exists($key);
	}

	public function has_arr($arr)
	{
		$data = array();
		foreach($arr as $keye) {
			$key = "{$this->prefix}_{$key}";
			$data[] = $key;
		}
		return apc_exists($data);
	}

	public function delete($key)
	{
		$key = "{$this->prefix}_{$key}";
		return apc_delete($key);
	}

	public function destory()
	{
		$pattern = "#^{$this->prefix}_#";
		foreach(new \APCIterator('user', $pattern) as $item) {
			apc_delete($item['key']);
		}
	}

}//end-class
?>