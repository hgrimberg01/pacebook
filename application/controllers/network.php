<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Network extends CI_Controller {
	public function index($network_id) {
		if (checkAuth ( $this )) {
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	public function add() {
		if (checkAuth ( $this )) {
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	public function edit($network_id) {
		if (checkAuth ( $this )) {
			
			
			
			
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
}
?>