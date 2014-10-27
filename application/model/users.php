<?php
class users extends \SF_core\SF_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function login($username, $password)
	{
		$this->filter($username);
		$this->filter($password);

		$sql = "SELECT password,salt,id FROM users WHERE username='$username' LIMIT 1";
		$result = $this->db->query($sql);
		if( !$result ){
			show_error($this->db->error);
		}
		$obj = mysqli_fetch_object($result);
		if( $obj->password === md5($password . $obj->salt) ){
			return $obj->id;
		} else {
			return False;
		}
	}

	public function regsiter($user_info)
	{
		foreach($user_info as $key=>$value){
			$this->filter($value);
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
		//password-hash
		$password = md5($password . $salt);
		//prepare statement for insert
		$stmt = $this->db->prepare(
			"INSERT INTO users(username, password, email, salt) VALUES (?,?,?,?)"
			);
		$stmt->bind_param("ssss", $username, $password, $email, $salt );
		if(! $stmt->execute() ){
			show_error($stmt->error);
		} else {
			return True;
		}
	}

	public function get_id($username)
	{
		$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
		$result = $this->db->query($sql);
		$obj = mysqli_fetch_object($result);
		return $obj->id;
	}

	public function delete_user($user_id)
	{
		$user_id = $this->filter($user_id);
		$sql = "DELETE FROM users WHERE id='$user_id'";
		if(! $this->db->query($sql)){
			$this->set_error($this->db->error);
			return False;
		} else {
			return True;
		}
	}

	public function get_group_setting($user_id)
	{
		$user_id = $this->filter($user_id);
		$sql = "SELECT groups.setting as setting
				FROM users
				LEFT JOIN groups
				ON users.group_id = groups.id
				WHERE users.id = '$user_id'";
		$result = $this->db->query($sql);
		if(! $result){
			$this->set_error($this->db->error);
		} else {
			$obj = mysqli_fetch_object($result);
			$arr = json_decode($obj->setting, True);
			return $arr;
		}
	}


}


?>