<?php
namespace SF_core;
class SF_controller
{
	protected $request;
	protected $error='';

	public function __construct(){}	

	public function render( $view_name, $view_bag = array() )
	{
		//fetch view_bag
		foreach( $view_bag as $key=>$value  ){
			$$key = $value;
		}
		if( file_exists( viewDir . $view_name . '.php' ) ){
			include( viewDir . $view_name . '.php' );
		} else {
			echo 'view page: ' , $view_name, '.php is missing';
			return;
		}

	}//end render function


	//load core object
	public function set_core($cores)
	{
		foreach($cores as $key=>$obj){
			$this->$key = is_callable($obj) ? call_user_func($obj) : $obj;
		}
	}

	public function set_error($msg)
	{
		$this->error .= $msg . '<br/>';
	}


}


?>