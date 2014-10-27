<?php

//CI style base_url()
function base_url( $url = '' )
{
	//get globals
	$sf = \SF_core\framework::getInstance();
	$req = $sf->get_core('request');

	$pre = $req->get_globals('SERVER', 'HTTPS') === True 
		&& strtolower($req->get_globals('SERVER', 'HTTPS')) !== 'off' ? 'https://' : 'http://';
	return $pre . $req->get_globals('SERVER', 'SERVER_NAME') . '/' . $url;
}

function show_404( $page= 'File', $warning = '' )
{
	header("HTTP/1.0 404 Not Found");
	include  viewDir . "_system/404_page.php";
	exit();
}

function show_error($warning = '')
{
	header("HTTP/1.0 500");
	include viewDir . "_system/500_page.php";
	exit();
}

function redirect($url)
{
	header('LOCATION: ' . $url);
	exit();
}