<?php
class users extends SF_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function login($username, $password)
	{
		$this->filter($username);
		$this->filter($password);

		$sql = "SELECT 1 FROM users WHERE username='$username' AND password='$password'";
		$result = $this->db->query($sql);
		if( !$result ){
			show_error($this->db->error);
		} else if($result->num_rows == 1){
			return True;
		} else {
			return False;
		}
	}

	public function regsiter($user_info)
	{
		foreach($user_info as $key=>$value){
			$this->filter($value);
			if(strlen($value) >= 20){
				$this->set_error($key . ' Field is too long.');
			}
			$$key = $value;
		}
		//check username exist or not
		$sql = "SELECT 1 FROM users WHERE username='$username'";
		$result = $this->db->query($sql);
		if( $result->num_rows == 1 ){
			$this->set_error('Username: [' . $username . '] is not available');
		}

		if(!empty($this->error)){
			return False;
		}

		//prepare statement for insert
		$stmt = $this->db->prepare(
			"INSERT INTO users(username, password, email) VALUES (?,?,?)"
			);
		$stmt->bind_param("sss", $username, $password, $email );
		if(! $stmt->execute() ){
			show_error($stmt->error);
		} else {
			return True;
		}
	}


}


?>