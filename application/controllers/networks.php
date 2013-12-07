<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Networks extends CI_Controller {
	// show network requests
	public function index() {
		if (checkAuth($this)) {
			$user_id = $this->session->userdata ( 'logged_in' );
				
			$this->load->model ( 'User_model' );
			$this->load->model ( 'Network_model' );
				
			$user = $this->User_model->getUser ( $user_id );
				
			$name = $user->firstName . " " . $user->lastName;
			$username = $user->username;
			
			$currentIDs = $this->User_model->getNetworks_IDs($user_id);
			$currentNetworks = $this->Network_model->getNetworks($currentIDs);
			
			$pendingIDs = $this->User_model->getPendingNetworkJoins($user_id);
			$networkRequest = $this->Network_model->getPendingNetworkJoins($pendingIDs);
			
			$networkApproveRequests = $this->Network_model->getPendingNetworkApprovals($currentIDs);
				
			$result = array ();
			
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
				
			$result ['currentNetworks'] = $currentNetworks;
			$result ['networkRequests'] = $networkRequest;
			$result ['approveRequests'] = $networkApproveRequests;
				
			$header ['author'] = $name;
			$header ['loggedIn'] = true;
			$header ['title'] = 'Networks - ' . $name;
			$header ['username'] = $username;
			$header ['name'] = $name;
			$header ['perm_level'] = $max_auth;
				
			$this->load->view ( 'header', $header );
			$this->load->view ( 'network_home', $result );
			$this->load->view ( 'footer' );
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