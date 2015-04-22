<?php
namespace lightning\system\core;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class DI 
{
	private $storage;
	private $config;

	public function __construct() 
	{
		$this->storage = array();
		$this->config = array();
	}

	public function set_config($config_array) 
	{
		$this->config = $config_array;
		return $this;
	}

	public function set($name, $obj)
	{
		if(isset($this->storage[$name])) {
			show_error("ERROR in DI->set(): $name is already existed");
		} else {
			$this->storage[$name] = $obj;
		}
		return $this;
	}

	public function override($name, $obj)
	{
		$this->storage[$name] = $obj;
	}

	public function load_storage() {
		return $this->storage;
	}

	public function load_config() {
		return $this->config;
	}

	public function set_by_array($obj_arr) {
		foreach($obj_arr as $key => $name) {
			$this->set($key, function() use ($name){
				return new $name();
			});
		}
	}
}



?>