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
	ob_clean();
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
				$root = root_path;
				$file = substr($file, strlen($root));
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

	include_once(view_path . "error/500.phtml");
	exit();
}

function show_404($message)
{
	header("HTTP/1.0 404 Not Found");
	include_once(view_path . "error/404.phtml");
	exit();
}

function redirect($url)
{
	if($refresh) {
		header('LOCATION: ' . $url);
		exit();
	}
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
	$salt = Chash(CRandom('all', 8));
	return (count($a) === count($b)) and (Chash($a, $salt) === Chash($b, $salt));
}

function vd($obj)
{
	var_dump($obj);
}

//array_column for php < 5.5
if(! function_exists('array_column')) {

	function array_column($arr, $key)
	{
		array_map($arr, function($item) use ($key) {
			return $item[$key];
		});
	}

}
?>