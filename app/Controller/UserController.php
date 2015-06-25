<?php
App::uses('AppController', 'Controller');

class UserController extends AppController {

	public function login() {
		// Require unauthenticated users
		if ( $this->logged_in ) {
			$this->redirect('/');
		}

		$failed_attempt = false;
		$username = null;

		if ( $this->request->is('post') ) {
			// Attempt Login
			$username = $this->request->data['username'];
			$password = $this->request->data['password'];

			$attempted_user = $this->User->findByUsername($username);

			if ( !empty($attempted_user) ) {
				$actual_password = $attempted_user['User']['password'];
				$attempted_password = Security::hash($password, 'blowfish', $actual_password);

				if ( $actual_password === $attempted_password ) {
					// Populate information
					$this->populateInfo($attempted_user['User']['id']);

					// Redirect home
					$this->redirect('/');
				}
			}

			$failed_attempt = true;
		}

		$this->set('failed_attempt', $failed_attempt);
		$this->set('username', $username);
	}

	public function logout($token=false) {
		$this->requireAuthenticated('/');

		if ( $token === $this->userinfo['logout_token'] ) {
			// Destroy the session
			$this->Session->destroy();

			// Redirect home
			$this->redirect('/');
		}

		// Display a nice message
	}

	public function profile() {
		$this->requireAuthenticated();

		$saved = false;
		$error = false;

		if ( $this->request->is('post') ) {
			// Update Password
			$old_password = $this->request->data['old_password'];
			$new_password = $this->request->data['new_password'];

			// Fetch the current password
			$user = $this->User->findById($this->userinfo['id']);
			$cur_password = $user['User']['password'];

			if ( Security::hash($old_password, 'blowfish', $cur_password) === $cur_password ) {
				$this->User->id = $this->userinfo['id'];
				$this->User->saveField('password', Security::hash($new_password, 'blowfish'));
				$saved = true;
			} else {
				$error = true;
			}
		}

		$this->set('saved', $saved);
		$this->set('error', $error);
	}
}
