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
			$this->load->library('form_validation');
			$this->form_validation->set_rules('networkName' , 'Network Name', 'trim|required|xss_clean|is_unique[Networks.networkName]');
			$this->form_validation->set_rules('networkDesc', 'Network Description', 'trim|xss_clean');
			
			$header ['author'] = ''; // TODO huh?
			$header ['loggedIn'] = true;
			$header ['title'] = 'Edit Network';
			$header ['perm_level'] = - 1; // TODO huh?
			$this->load->view ( 'header', $header );
			
			$this->load->model('Network_model');
			if ($this->form_validation->run() == FALSE) {
				$this->load->view( 'editNetwork', $network_id);
			} else {
				// TODO implement this
			}
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
}
?>