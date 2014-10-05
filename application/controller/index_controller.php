<?php if ( ! defined('root_path')) exit('No direct script access allowed');

class index extends SF_controller
{
	public function __construct(){}

	public function indexAction()
	{	
		$data['page']['title'] = date('Y/m/d') . ' ';
		$post = new posts();
		$result = $post->findAll();
		$data['posts'] = $result;
		$this->render( 'general/header', $data );
		$this->render( 'index_view', $data );
		$this->render( 'general/footer' );
	}

	public function newpostAction()
	{
		$title = $this->request->post('title');
		$text = $this->request->post('text');
		$poster = $this->session->get('userlogin') ? $this->session->get('userlogin') : 'Guest';
		$post_info = array(
			'title' => $title,
			'text' => $text,
			'poster' => $poster
			);
		$post = new posts();

		if(! $post->newpost($post_info)){
			$this->set_error($post->error);
			$this->indexAction();
			exit();
		} else {
			redirect(base_url());
		}

	}
}//end class

?>