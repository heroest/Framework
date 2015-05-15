<?php namespace lightning\application\library\Kalah;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\core\SystemClass;

class Kalah extends SystemClass
{
	private $data;
	private $key;
	private $depth;

	public function __construct($game_data, $depth=10)
	{
		$this->data  = $game_data;
		$this->key   = $this->data_to_key($game_data);
		$this->depth = $depth;
	}

	public function get_move()
	{
		if($this->cache->has($this->key)) {
			$data = json_decode($this->cache->get($this->key), True);
			$data['log'] .= '[c-hit]';
			return $data;
		}

		$result = array();
		$x_arr  = array(1, 2, 3, 4, 5, 6);
		shuffle($x_arr);
		$y = 0;

		foreach($x_arr as $x) {
			if(intval($this->data[$y][$x]) > 0) {
				$data = $this->game_move($x, $y, $this->data);
				if($this->depth > 0) {
					//go deepr
					$new_depth = ($data['at'] == 'house') ? $this->depth - 1 : $this->depth;
					$kalah = new Kalah($data['game'], $new_depth);
					$arr = $kalah->get_move();
					$result[$arr['point'] + $data['scored']] = array(
													'point' => $arr['point'] + $data['scored'],
													'x'     => $x, 
													'y'     => $y,
													'log'   => $arr['log'],
												);
				} else {
					//base-case at depth 1
					$result[$data['scored']] = array(
											'point' => $data['scored'],
											'x'     => $x,
											'y'     => $y,
											'log'   => '[max_depth]',
											);
				}
			}
		}
		$back_up = $result;
		ksort($result, SORT_NUMERIC);
		$item = array_pop($result);
		if(is_null($item)) {
			$item['point'] = 0;
			$item['log']   = '[gg]';
			$item['x'] = 'gg';
			$item['y'] = 'gg';
		} else {
			$item['log'] .= '[c]';
			$this->cache->set($this->key, json_encode($item), 2419200);
			return $item;
		}
		
	}

	public function data_to_key($data)
	{
		$key_part = array();
		for($y=0;$y<=1;$y++) {
			for($x=1;$x<=6;$x++) {
				$key_part[] = $data[$y][$x];
			}
		}
		return 'kalah_cache_' . implode('-', $key_part);
	}

	public function game_move($x, $y, $game_data)
	{
		$move = $game_data[$y][$x];
		$game_data[$y][$x] = 0;
		$point = 0;
		while($move > 0) {
			$next = $this->next_point($x, $y);
			$x = $next['x'];
			$y = $next['y'];
			if($x == 0 and $y == 0) {
				$point++;
			} else {
				$game_data[$y][$x] += 1;
			}
			$move--;
		}
		$pos = ($x==0 and $y==0) ? 'store' : 'house';
		return array('scored' => $point, 'at' => $pos, 'game' => $game_data);
	}

	public function next_point($x, $y)
	{
		if($y == 0) {
			$x--;
			if($x < 0) {
				$x = 1;
				$y = 1;
			}
		} else {
			$x++;
			if($x > 6) {
				$x = 6;
				$y = 0;
			}
		}
		return array('x' => $x, 'y' => $y);
	}

}



/*
class Kalah extends SystemClass
{
	private $data;

	public function __construct($game_data)
	{
		$this->data = $game_data;
	}

	public function get_move()
	{	
		$result = $this->capture();
		if($result !== False) {
			return $result;
		} else {
			$result = $this->short_cut();
			if($result !== False) {
				return $result;
			}
		}
		$arr_x = array(1, 2, 3, 4, 5, 6);
		shuffle($arr_x);

		foreach($arr_x as $x) {
			$y = 0;
			if($this->data[$y][$x] > 0) return array('x'=>$x, 'y'=>$y, 'result'=>'AI normal move');
		}
		return array();
	}

	private function capture()
	{
		$arr = array();
		for($y=0;$y<=1;$y++) {
			for($x=1;$x<=6;$x++) {
				$y_opp = abs($y-1);
				if($this->data[$y][$x] > 0 and $this->data[$y_opp][$x] == 0)
					$arr[] = array('x'=>$x, 'y'=>$y_opp);
			}
		}

		for($y=0;$y<=1;$y++) {
			for($x=1;$x<=6;$x++) {
				$move = $this->data[$y][$x];
				foreach($arr as $item) {
					if($this->reach($x, $y, $item['x'], $item['y'], $move) === True and
						$x != $item['x'] and $y != $item['y']) 
						return array('x' => $x, 'y'=> $y, 'result'=>'AI capture');
				}
			}
		}
		return False;
	}

	private function short_cut()
	{
		for($y=0;$y<=1;$y++) {
			for($x=1;$x<=6;$x++) {
				$move = $this->data[$y][$x];
				if($this->reach($x, $y, 0, 0, $move) === True) {
					return array('x'=>$x, 'y'=>$y, 'result'=>'AI short cut');
				}
					
			}
		}
		return False;
	}

	private function reach($x_a, $y_a, $x_b, $y_b, $move)
	{
		$old = $move;
		while($move > 0) {
			$move--;
			$next = $this->next_node($x_a, $y_a);
			if($move == 0 and $next['x'] == $x_b and $next['y'] == $y_b) {
				//echo "$x_a, $y_a moves to $x_b, $y_b in $old<br />";
				//echo "current, {$next['x']} and {$next['y']}<br/>";
				return True;
			}
			$x_a = $next['x'];
			$y_a = $next['y'];
		}
		return False;
	}

	private function next_node($x, $y)
	{
		if($y == 0) {
			$x--;
			if($x < 0) {
				$x = 1;
				$y = 1;
			}
		} else {
			$x++;
			if($x >= 7) {
				$x = 6;
				$y = 0;
			}
		}
		return array('x'=>$x, 'y'=>$y);
	}


}
*/
?>