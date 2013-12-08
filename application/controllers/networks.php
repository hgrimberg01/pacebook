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
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
			
			$currentIDs = $this->User_model->getNetworks_IDs($user_id);
			$currentNetworks = $this->Network_model->getNetworks($currentIDs);
			
			$pendingIDs = $this->User_model->getPendingNetworkJoins($user_id);
			$networkRequest = $this->Network_model->getPendingNetworkJoins($pendingIDs, $user_id);
			
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
			$this->load->library ( 'form_validation' );
			$this->form_validation->set_rules ( 'nName', 'Network Name', 'trim|required|xss_clean|is_unique[Networks.networkName]' );
			$this->form_validation->set_rules ( 'NDesc', 'Network Description', 'trim|xss_clean' );
				
			if ($this->form_validation->run () == FALSE) {
				// do nothing, because why are you on this page without a network to add?
				$this->load->view ( 'network_home', $result );
			} else {
				$nName = $this->input->post ( 'nName' );
				$nDesc = $this->input->post ( 'nDesc' );
					
				// add new network
				$networkID = $this->Network_model->putNetwork($nName, $nDesc);
				// add user to new network
				$this->User_model->putNetworks($user_id, array($networkID));
				$this->load->view ( 'network_home', $result );
					
				redirect ( '/networks/', 'refresh' );
			}
			$this->load->view ( 'footer' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	public function leave($networkID) {
		if (checkAuth($this)) {
			$user_id = $this->session->userdata ( 'logged_in' );
			$this->load->model('User_model');
			$this->User_model->leaveNetwork($user_id, $networkID);
			// TODO handle network owner leaving
			redirect ( '/networks/', 'refresh' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	public function cancel($networkID) {
		// TODO check if has permission to delete the network
		if (checkAuth($this)) {
			$user_id = $this->session->userdata ( 'logged_in' );
			$this->load->model('User_model');
			$this->User_model->leaveNetwork($user_id, $networkID);
			$this->load->model('Network_model');
			$this->Network_model->deleteNetwork($networkID);
			redirect ( '/networks/', 'refresh' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	
	public function join() {
		if (checkAuth ( $this )) {
			$user_id = $this->session->userdata ( 'logged_in' );
			
			$this->load->model ( 'User_model' );
			$this->load->model ( 'Network_model' );
			
			$user = $this->User_model->getUser ( $user_id );
			
			$name = $user->firstName . " " . $user->lastName;
			$username = $user->username;
			
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
			
			$userNetworkIDs = $this->User_model->getAllNetworkIDs($user_id);
			$networks = $this->Network_model->getNetworksNotIn($userNetworkIDs);
			$result = array ();
			$result['networks'] = $networks;
			
			$header ['author'] = $name;
			$header ['loggedIn'] = true;
			$header ['title'] = 'Join Networks - ' . $name;
			$header ['username'] = $username;
			$header ['name'] = $name;
			$header ['perm_level'] = $max_auth;
			
			$this->load->view ( 'header', $header );
			$this->load->view ( 'join_networks', $result );
			$this->load->view ( 'footer' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	public function add() {
		$ret = array ();
		
		if (checkAuth ( $this )) {
		
			$user_id = $this->session->userdata ( 'logged_in' );
		
			$uid = $user_id;
		
			$nid = $this->input->post ( 'nid' );
		
		
		
			$this->load->model ( 'User_model' );
			$this->User_model->joinNetwork($uid,$nid);
				
				
			$ret['status'] = '200';
			if ($this->input->is_ajax_request ()) {
				echo json_encode ( $ret );
			}else{
				// Conf. Page
			}
		} else {
			$ret['status'] = '503';
			if ($this->input->is_ajax_request ()) {
				echo json_encode ( $ret );
			}else{
				// Conf. Page
			}
		}
	}
	public function manage() {
		if (checkAuth ( $this )) {
			$user_id = $this->session->userdata ( 'logged_in' );
				
			$this->load->model ( 'User_model' );
			$this->load->model ( 'Network_model' );
				
			$user = $this->User_model->getUser ( $user_id );
				
			$name = $user->firstName . " " . $user->lastName;
			$username = $user->username;
				
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
				
			$pending = $this->Network_model->getAllPendingApprovals();
			$result = array ();
			$result['networks'] = array();
			$result['pending'] = $pending;
				
			$header ['author'] = $name;
			$header ['loggedIn'] = true;
			$header ['title'] = 'Manage Networks - ' . $name;
			$header ['username'] = $username;
			$header ['name'] = $name;
			$header ['perm_level'] = $max_auth;
				
			$this->load->view ( 'header', $header );
			$this->load->view ( 'manage_networks', $result );
			$this->load->view ( 'footer' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	public function approve() {
		$ret = array ();
		
		if (checkAuth ( $this )) {
			// TODO make sure that this user has permission to approve
			$user_id = $this->session->userdata ( 'logged_in' );
		
			$uid = $user_id;
		
			$nid = $this->input->post ( 'nid' );
			$note = ""; // TODO implement notes
		
		
		
			$this->load->model ( 'Network_model' );
			$this->Network_model->setApprovalState($nid,true,$note,$uid);
		
		
			$ret['status'] = '200';
			if ($this->input->is_ajax_request ()) {
				echo json_encode ( $ret );
			}else{
				// Conf. Page
			}
		} else {
			$ret['status'] = '503';
			if ($this->input->is_ajax_request ()) {
				echo json_encode ( $ret );
			}else{
				// Conf. Page
			}
		}
	}
	public function deny() {
		$ret = array ();
		
		if (checkAuth ( $this )) {
			// TODO make sure that this user has permission to deny
			$user_id = $this->session->userdata ( 'logged_in' );
		
			$uid = $user_id;
		
			$nid = $this->input->post ( 'nid' );
			$note = ""; // TODO implement notes
		
		
		
			$this->load->model ( 'Network_model' );
			$this->Network_model->setApprovalState($nid,false,$note,$uid);
		
		
			$ret['status'] = '200';
			if ($this->input->is_ajax_request ()) {
				echo json_encode ( $ret );
			}else{
				// Conf. Page
			}
		} else {
			$ret['status'] = '503';
			if ($this->input->is_ajax_request ()) {
				echo json_encode ( $ret );
			}else{
				// Conf. Page
			}
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