<?php 

class user extends SF_controller 
{

	public function loginAction()
	{
		if(! empty($this->error)){
			$data['page']['title'] = 'Fail to Login';
		} else {
			$data['page']['title'] = 'Login';
		}
		$this->render( 'general/header', $data );
		$this->render( 'login_view', $data );
		$this->render( 'general/footer' );
	}

	public function dologinAction()
	{
		$username = $this->request->post('username');
		$password = $this->request->post('password');
		$user = new users();
		if(! $user->login($username, $password)){
			$this->set_error('Uknown username or password');
			$this->loginAction();
			exit();
		} else {
			$this->session->set(array('userlogin' => $username));
			$this->session->set(  array('logout_salt' => md5(uniqid(True).rand()))  );
			redirect(base_url());
		}
	}

	public function registerAction()
	{	
		if(! empty($this->error)){
			$data['page']['title'] = 'Fail to Register';
		} else {
			$data['page']['title'] = 'Register';
		}

		//if user login already, redirect to main page
		if($this->session->get('userlogin')){
			redirect(base_url());
		}
		$this->render( 'general/header', $data );
		$this->render( 'register_view', $data );
		$this->render( 'general/footer' );
	}

	public function doregisterAction()
	{
		$username = $this->request->post('username');
		$password = $this->request->post('password');
		$verify = $this->request->post('verify');
		$email = $this->request->post('email');

		if(! $username){
			$this->set_error('Username Field is required');
		} else if(! $password){
			$this->set_error('Password Field is required');
		} else if(! $verify){
			$this->set_error('Verify Field is required');
		} else if(md5($password) != md5($verify) ){
			$this->set_error('Verify Password Field does not match Password Field');
		} else if(! $email){
			$email = '[secret]';
		}

		//if found any error in form, return to registerAction
		if(!empty($this->error)){
			$this->registerAction();
			exit();
		}

		$user_info = array(
			'username' => $username,
			'password' => $password,
			'verify' => $verify,
			'email' => $email
			);
		$user = new users();
		if(! $user->regsiter($user_info) ){
			$this->set_error($user->error);
			$this->registerAction();
			exit();
		} else {
			$this->session->set(array('userlogin' => $username));
			$this->session->set( array('logout_salt' => md5(uniqid(True).rand())) );
			redirect(base_url());
		}


	}
	

	public function logoutAction($logout_salt='')
	{	
		//if user not login, redirect to main page
		if(! $this->session->get('userlogin')){
			redirect(base_url());
		}

		$salt = $this->session->get('logout_salt');
		if(md5($salt) != md5($logout_salt)){
			show_error('Unauthorized logout operation');
		} else {
			//delete all session data
			$this->session->del();
			redirect(base_url());
		}

	}



}

?>

