<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Friends extends CI_Controller {
	
	// Show Friend Requests
	public function index() {
		if (checkAuth ( $this )) {
			
			$user_id = $this->session->userdata ( 'logged_in' );
			
			$this->load->model ( 'User_model' );
			$this->load->model ( 'Network_model' );
			
			$user = $this->User_model->getUser ( $user_id );
			
			$name = $user->firstName . " " . $user->lastName;
			$username = $user->username;
			
			$friend_names = array ();
			
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
			
			$inbound = $this->User_model->getInboundUnapprovedFriends ( $user_id );
			$outbound = $this->User_model->getOutboundUnapprovedFriends ( $user_id );
			$friends = $this->User_model->getApprovedFriends($user_id);
			$result = array ();
			
			$result ['outbound'] = $outbound;
			$result ['inbound'] = $inbound;
			$result ['friends'] = $friends;
			
			$header ['author'] = $name;
			$header ['loggedIn'] = true;
			$header ['title'] = 'Friends - ' . $name;
			$header ['username'] = $username;
			$header ['name'] = $name;
			$header ['perm_level'] = $max_auth;
			
			$this->load->view ( 'header', $header );
			$this->load->view ( 'friend_home', $result );
			$this->load->view ( 'footer' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
	
	public function confirm() {
		$ret = array ();
		
		if (checkAuth ( $this )) {
			
			$user_id = $this->session->userdata ( 'logged_in' );
			
			$uid = $user_id;
			
			$fid = $this->input->post ( 'fid' );
		
			$direction = $this->input->post ( 'dir' );
			
			$this->load->model ( 'User_model' );
			
			if ($direction == 'o') {
				$this->User_model->confirmFriendship ( $uid, $fid );
			} else if ($direction == 'i') {
				$this->User_model->confirmFriendship ( $fid, $uid );
			}
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
				
			$user_id = $this->session->userdata ( 'logged_in' );
				
			$uid = $user_id;
				
			$fid = $this->input->post ( 'fid' );
	
			$direction = $this->input->post ( 'dir' );
				
			$this->load->model ( 'User_model' );
				
			if ($direction == 'o') {
				$this->User_model->denyFriendship ( $uid, $fid );
			} else if ($direction == 'i') {
				$this->User_model->denyFriendship ( $fid, $uid );
			}
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
	
	public function add(){
		$ret = array ();
		
		if (checkAuth ( $this )) {
		
			$user_id = $this->session->userdata ( 'logged_in' );
		
			$uid = $user_id;
		
			$fid = $this->input->post ( 'fid' );
		

		
			$this->load->model ( 'User_model' );
			$this->User_model->addFriendship($uid,$fid);
			
			
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
	
	public function find(){
		if (checkAuth ( $this )) {
				
			$user_id = $this->session->userdata ( 'logged_in' );
				
			$this->load->model ( 'User_model' );
			$this->load->model ( 'Network_model' );
				
			$user = $this->User_model->getUser ( $user_id );
				
			$name = $user->firstName . " " . $user->lastName;
			$username = $user->username;
				
			$friend_names = array ();
				
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
		
			$friends = $this->User_model->getNotFriends($user_id);
			$result = array ();
			$result['friends'] = $friends;
			$header ['author'] = $name;
			$header ['loggedIn'] = true;
			$header ['title'] = 'Find Friends - ' . $name;
			$header ['username'] = $username;
			$header ['name'] = $name;
			$header ['perm_level'] = $max_auth;
				
			$this->load->view ( 'header', $header );
			$this->load->view ( 'find_friends', $result );
			$this->load->view ( 'footer' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
		
	}
}