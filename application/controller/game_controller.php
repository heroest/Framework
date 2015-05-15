<?php namespace lightning\application\controller;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use \lightning\system\MVC\AbstractController;
use \lightning\application\library\Kalah\Kalah;

class game_controller extends AbstractController
{
	public function bombAction()
	{
		$title = 'Hex-a-bomb';
		$viewArray['title'] = $title;
		$this->render('layout/header',   $viewArray);
		$this->render('game/hex-a-bomb', $viewArray);
		$this->render('layout/footer');
	}

	public function kalahAction()
	{
		$cache_config = $this->get_config('cache');
		$prefix = $cache_config['key_name_prefix'];
		$pattern = "#^{$prefix}_kalah_cache_#";
		$count = 0;
		foreach(new \APCIterator('user', $pattern) as $item) {
			$count++;
		}
		
		$viewArray['title'] = "Kalah - Cached: {$count}";
		$this->render('layout/header', $viewArray);
		$this->render('game/kalah', $viewArray);
		$this->render('layout/footer');
	}

	public function kalahAI_Action()
	{
		$data = $this->request->getPost('game_data');
		$data = json_decode($data, True);
		$game_board = $this->data_to_board($data);
		$kalah = new Kalah($game_board, 10);
		echo json_encode($kalah->get_move());
	}

	private function data_to_board($arr)
	{
		$board = array();
		foreach($arr as $point) {
			$value = $point['value'];
			$x = $point['x'];
			$y = $point['y'];
			$board[$y][$x] = $value;
		}
		return $board;
	}

}

?>