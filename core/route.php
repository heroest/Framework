<?php
namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class route extends container{

	private $map;

	public function __construct($route_map){
		$this->map = $route_map;
	}

	public function fetch_url(){
		$query = array();
		$url = $this->request->get_url();

		if(isset($url) and empty($url)){
			$arr = explode('/', $this->map['Default_Controller']);

			//array($controller, $action, $query_array)
			return array($arr[0], $arr[1], $query);
		}

		foreach($this->map as $uri=>$dispatch){
			if($this->match($url, $uri)){
				$uri_array = explode('/', $uri);
				$req = explode('/', $url);
				$dis = explode('/', $dispatch);
				$query = array_slice($req, count($uri_array));

				//array($controller, $action, $query_array)
				return array($dis[0], $dis[1], $query);
			}
		}
		show_404('[ ' . base_url() . $url . ' ] is not found on the server');
	}

	private function match($url, $uri)
	{	
		$url_len = count($url);
		$uri_len = count($uri);
		if($url_len < $uri_len){
			return False;
		} else if($url_len == $uri_len){
			return strpos($url, $uri) === 0;
		} else {
			$chr = $url[$uri_len];
			return (strpos($url, $uri) === 0) and ($chr == '/');
		}
	}

}//end class route


?>