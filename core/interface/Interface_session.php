<?php

namespace SF_core;
Interface Interface_session{

	/* ===========================================
   	 * @function: set
	 * @Input:	session array with name=>value
	 * @output:	none
	 * ===========================================
	*/
	public function set($array);


	/* ===========================================
   	 * @function: get
	 * @Input:	session_key
	 * @output:	return associated value with session_key
	 * ===========================================
	*/
	public function get($key);


	/* ===========================================
   	 * @function: get_all
	 * @Input:	none
	 * @output:	return all session data as array
	 * ===========================================
	*/
	public function get_all();



	/* ===========================================
   	 * @function: delete
	 * @Input:	delete all session data or key item
	 * @output:	none
	 * ===========================================
	*/
	public function delete($key='');

}


?>