<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Profile extends CI_Controller {
	public function index() {
	}
	public function show() {
		echo  $par = $this->uri->segment(2);
	}
}
