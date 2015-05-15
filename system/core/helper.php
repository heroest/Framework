<?php if ( ! defined('framework_name')) exit('No direct script access allowed');

function base_url($url='')
{
	$instance = lightning\system\core\SystemClass::getInstance();
	$req = $instance->request;
	$pre = $req->getGlobal('SERVER', 'HTTPS') === True ? 'https://' : 'http://';
	return $pre . $req->getGlobal('SERVER', 'SERVER_NAME') . '/' . $url;
}

function show_error($message)
{
	//ob_clean();
	header("HTTP/1.0 500");
	$config = lightning\system\core\SystemClass::getInstance()->get_config('website');

	if($config['show_error_stack'] === True) {
		$stack = array_reverse(debug_backtrace());
		$tbody = '';
		$thead = "
				<tr>
					<th>Class</th>
					<th>File</th>
					<th>Line</th>
					<th>Function</th>
				</tr>";
		foreach($stack as $error) {
			$line 	= empty($error['line']) 	? '' : $error['line'];
			$func 	= empty($error['function']) ? '' : $error['function'];
			$file 	= empty($error['file']) 	? '' : $error['file'];
			$class 	= empty($error['class'])	? '' : $error['class'];
			if(! empty($file)) {
				$file = Cpath($file);
			}
			$tbody .= "
					<tr>
						<td>{$class}</td>
						<td>{$file}</td>
						<td>{$line}</td>
						<td>{$func}</td>
					</tr>";
		}
		$table = "<table class='table table-hover'
					<thead>{$thead}</thead>
					<tbody>{$tbody}</tbody>
				</table>";
	} else {
		$table = '[Hidden]';
	}

	exit(include_once(view_path . "error/500.phtml"));
}

function show_404($message)
{
	header("HTTP/1.0 404 Not Found");
	exit(include_once(view_path . "error/404.phtml"));
}

function redirect($url='', $refresh = true)
{
	$url = empty($url) ? base_url() : $url;
	header('LOCATION: ' . $url);
	exit();
}

function Chash($code='', $salt='', $algo='')
{
	if(empty($algo)) {
		$instance = lightning\system\core\SystemClass::getInstance();
		$config   = $instance->get_config('website');
		$algo     = $config['hash_algorithm'];
	}
	$salt = empty($salt) ? sha1(uniqid(mt_rand(),true)) : $salt;
	$code = empty($code) ? md5(uniqid(mt_rand(),true))  : $code;
	return hash_hmac($algo, $code, $salt);
}

function Crandom($type, $len=10)
{
	$lower = 'abcdefghijklmnopqrstuvwxyz';
	$upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$nums  = '0123456789';
	$hash  = '';
	$str   = '';
	switch ($type) {
		case 'all':
			$str = $lower . $upper . $nums;
			break;
		case 'uc':
			$str = $upper;
			break;
		case 'lc':
			$str = $lower;
			break;
		case 'num':
			$str = $nums;
			break;
	}
	$arr = str_split(str_shuffle($str));
	$max = count($arr) - 1;
	for($i=0; $i<$len; $i++){
		$hash .= $arr[mt_rand(0, $max)];
	}
	return $hash;
}

function Cmatch($a, $b)
{
	return (count($a) === count($b)) and (md5($a) === md5($b));
}

function Cpath($path)
{
	$root = root_path;
	return substr($path, strlen($root) - 1);
}

function ip_to_bit($ip)
{
	$arr = array_map(function($item){
		return str_pad($item, 3, '0', STR_PAD_LEFT);
	}, explode(".", $ip));
	$arr = str_split(implode("", $arr));
	$ret = array();
	foreach($arr as $item) {
		$ret[] = pow(2, intval($item));
	}
	return implode(" ", $ret);
}

//array_column for php < 5.5
if(! function_exists('array_column')) {

	function array_column($arr, $key)
	{
		return array_map($arr, function($item) use ($key) {
			return $item[$key];
		});
	}

}
?>