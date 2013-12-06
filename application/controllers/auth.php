<?php
class Auth extends CI_Controller {
	public function index() {
		if (checkAuth ( $this )) {
			redirect ( '/home/', 'refresh' );
		} else {
			
			$header ['author'] = '';
			$header ['loggedIn'] = false;
			$header ['title'] = 'Login';
			$header ['perm_level'] = - 1;
			$this->load->view ( 'header', $header );
			
			$this->load->library ( 'form_validation' );
			$this->form_validation->set_rules ( 'user', 'user', 'trim|required|xss_clean' );
			$this->form_validation->set_rules ( 'password', 'password', 'trim|required|xss_clean' );
			
			$msg = array ();
			if ($this->form_validation->run () == FALSE) {
				$this->load->view ( 'login_index' );
			} else {
				$username = $this->input->post ( 'user' );
				$password = $this->input->post ( 'password' );
				$extended = $this->input->post ( 'extended' );
				
				if ($extended = 'true') {
					
					$this->session->sess_expiration = 63072000;
					$this->session->sess_expire_on_close = FALSE;
				} else {
					$this->session->sess_expire_on_close = TRUE;
					$this->session->sess_expiration = 3600;
				}
				
				$this->load->model ( 'User_model' );
				$user_id = $this->User_model->checkPassword ( $username, $password );
				
				if ($user_id < 0) {
					$msg ['bad_login'] = true;
					$this->load->view ( 'login_index', $msg );
				} else {
					// Auth and redirect
					$this->session->set_userdata ( 'logged_in', $user_id );
					redirect ( '/home/', 'refresh' );
				}
			}
			
			$this->load->view ( 'footer' );
		}
	}
	public function logout() {
		$this->session->sess_destroy ();
		redirect ( '/auth/', 'refresh' );
	}
	public function register() {
		$this->load->library ( 'form_validation' );
		$this->form_validation->set_rules ( 'username', 'Username', 'trim|required|xss_clean|is_unique[Users.userName]' );
		$this->form_validation->set_rules ( 'password', 'Password', 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'password2', 'Password Confirmation', 'trim|required|xss_clean|matches[password]' );
		$this->form_validation->set_rules ( 'email', 'E-Mail', 'trim|required|xss_clean|valid_email|is_unique[Users.userEmail]' );
		$this->form_validation->set_rules ( 'firstName', 'First Name', 'trim|required|xss_clean' );
		$this->form_validation->set_rules ( 'lastName', 'Last Name', 'trim|required|xss_clean' );
		
		if (checkAuth ( $this )) {
			redirect ( '/home/', 'refresh' );
		} else {
			$header ['author'] = '';
			$header ['loggedIn'] = false;
			$header ['title'] = 'Register';
			$header ['perm_level'] = - 1;
			$this->load->view ( 'header', $header );
			
			$this->load->model ( 'User_model' );
			if ($this->form_validation->run () == FALSE) {
				$this->load->model ( 'Network_model' );
				$reg = array ();
				$reg ['nets'] = $this->Network_model->getAllNetworks ();
				$this->load->view ( 'register', $reg );
			} else {
				$this->load->model ( 'User_model' );
				$username = $this->input->post ( 'username' );
				$lastName = $this->input->post ( 'lastName' );
				$firstName = $this->input->post ( 'firstName' );
				$password = $this->input->post ( 'password' );
				$email = $this->input->post ( 'email' );
				$super = $this->input->post ( 'superuser' );
				$nets = $this->input->post ( 'nets' );
				
				$user_id = $this->User_model->putUser ( $username, $firstName, $lastName, "", "", $email, $password );
				$this->session->set_userdata ( 'logged_in', $user_id );
				
				if ($super == "true") {
					$this->User_model->grantGlobalPerms ( $user_id, 4 );
				}
				
				$this->User_model->setNetworks ( $user_id, $nets );
				
				redirect ( '/home/', 'refresh' );
			}
			$this->load->view ( 'footer' );
		}
	}
}

