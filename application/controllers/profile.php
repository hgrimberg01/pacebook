<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Profile extends CI_Controller {
	public function index($user) {
		if (checkAuth ( $this )) {
			
			
			
			
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
}
?>