<?php 

class posts extends SF_model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function findAll()
	{
		$result = $this->db->query('SELECT * FROM posts');
		$ret = array();
		while($arr = mysqli_fetch_assoc($result)){
			array_push( $ret, $arr );
		}
		return $ret;
	}

	public function newpost( $post_info )
	{
		foreach($post_info as $key=>$value){
			$this->filter($value);
			if(strlen($value) > 100){
				$this->set_error($key . ' filed is too long');
			}
			$$key = $value;
		}

		$stmt = $this->db->prepare(
			'INSERT INTO posts(title, text, datetime, poster) VALUES (?,?,?,?)'
		);
		$post_time = date('Y-m-d H:i:s');
		$post_title = $title;
		$post_text = $text;
		$post_poster = $poster;
		$stmt->bind_param('ssss', $post_title, $post_text, $post_time, $post_poster);
		if( ! $stmt->execute() ){
			show_error($stmt->error);
			return False;
		} else {
			return True;
		}

	}

}


?>