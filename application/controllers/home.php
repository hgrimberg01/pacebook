<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Home extends CI_Controller {
	
	
	
	
	
	public function friendLoad() {
		if (checkAuth ( $this )) {
			$user_id = $this->session->userdata ( 'logged_in' );
			
			$this->load->model ( 'User_model' );
			$this->load->model ( 'Network_model' );
			
			$user = $this->User_model->getUser ( $user_id );
			
			$name = $user->firstName . " " . $user->lastName;
			$username = $user->username;
			
			$friend_names = array ();
			
			$max_auth = $this->User_model->getUserGlobalPermission ( $user_id );
			
			$frnd = $this->User_model->getFriends ( $user_id );
			
			foreach ( $frnd as $row ) {
				array_push ( $friend_names, $row->fName . ' ' . $row->lName );
			}
			
			$ret = array ();
			$ret ['code'] = '200';
			$ret ['data'] = $friend_names;
			
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $ret ) );
		} else {
			
			$ret = array ();
			$ret ['code'] = '999X';
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $ret ) );
		}
	}
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
		
			$frnd = $this->User_model->getFriends ( $user_id );
			
			foreach ( $frnd as $row ) {
				array_push ( $friend_names, $row->fName . ' ' . $row->lName );
			}
			
			$my_group_names = array ();
			
			$net = $this->User_model->getNetworks ( $user_id );
			
			foreach ( $net as $row ) {
				array_push ( $my_group_names, $row->name );
			}
			
			if ($max_auth >= 3) {
				$all_networks = array ();
				$all_people = array ();
				
				foreach ( $this->User_model->getAllUsers () as $row ) {
					array_push ( $all_people, $row->fName . ' ' . $row->lName );
				}
				$home ['all_people'] = $all_people;
				foreach ( $this->Network_model->getAllNetworks () as $row ) {
					array_push ( $all_networks, $row->networkName );
				}
				$home ['all_networks'] = $all_networks;
			}
			$home ['friend_names'] = $friend_names;
			$home ['my_group_names'] = $my_group_names;
			$home ['admin_level'] = $max_auth;
			
			$header ['author'] = $name;
			$header ['loggedIn'] = true;
			$header ['title'] = 'Home - ' . $name;
			$header ['username'] = $username;
			$header ['name'] = $name;
			$header ['perm_level'] = $max_auth;
			
			$this->load->view ( 'header', $header );
			$this->load->view ( 'home', $home );
			$this->load->view ( 'footer' );
		} else {
			redirect ( '/auth/', 'refresh' );
		}
	}
}
?>