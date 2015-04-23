<?php
namespace lightning\system\core\session\driver;
use lightning\system\core\SystemClass;

if(! defined('framework_name')) exit('No direct script access allowed');
class session_apc extends SystemClass implements \SessionHandlerInterface
{
	private $key;
	private $config;
	private $prefix;

	public function __construct()
	{
		$this->config = $this->get_config('session');
		$this->prefix = $this->config['key_name_prefix'];
	}

	public function open($path, $sess_name){}

	public function close()
	{
		return True;
	}

	public function read($session_id)
	{
		$key = "{$this->prefix}#{$session_id}";
		if(apc_exists($key)) {
			$this->key = $key;
			return apc_fetch($key);
		} else {
			return '';
		}
	}

	public function write($session_id, $session_data)
	{
		if(! empty($this->key)) apc_delete($this->key);
		$key = "{$this->prefix}#{$session_id}";
		$duration = $this->config['duration'];
		return apc_store($key, $session_data, $duration);
	}

	public function destroy($session_id)
	{
		$key = "_session_{$session_id}";
		return apc_delete($key);
	}

	public function gc($maxlifetime)
	{
		return True;
	}
}

?>