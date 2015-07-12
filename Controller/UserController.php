<?php
App::uses('AppController', 'Controller');

class UserController extends AppController {
	public $uses = array('User', 'Team', 'Group');

	// ===============[ REGULAR ROUTES
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
					// Get the redirect information
					$redirect_to = ($this->Session->check('auth.from') ? $this->Session->read('auth.from') : '/');

					// Populate information
					$this->populateInfo($attempted_user['User']['id']);

					// Log it
					$this->logMessage('AUTH', 'User just logged in');

					// Redirect
					$this->redirect($redirect_to);
				} else {
					// Log it
					$this->logMessage('AUTH', 'Attempted login for '.$attempted_user['User']['username'].' - incorrect password given');
				}
			} else {
				// Log it
				$this->logMessage('AUTH', 'Attempted login for "'.htmlentities($username).'" - no such user');
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

			// Log it
			$this->logMessage('AUTH', 'User just logged out');

			// Redirect home
			$this->redirect('/');
		}

		// Otherwise display a nice message saying they failed in life
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
				// Update password
				$this->User->id = $this->userinfo['id'];
				$this->User->saveField('password', Security::hash($new_password, 'blowfish'));

				// Log it
				$this->logMessage('USER', 'User just updated his/her password');

				$saved = true;
			} else {
				$error = true;
			}
		}

		$this->set('saved', $saved);
		$this->set('error', $error);
	}

	public function emulate_clear() {
		$this->requireAuthenticated();

		if ( !$this->Session->read('User.emulating') ) $this->redirect('/');

		// Log it
		$emulating_user = $this->userinfo['username'];
		$emulating_uid  = $this->userinfo['id'];
		$actual_uid     = $this->Session->read('User.emulating_from');

		// Reset session variables
		$this->Session->write('User.emulating', false);

		// Go back to normal
		$this->populateInfo($actual_uid);

		// Log it
		$this->logMessage('USER_EMULATING', $this->userinfo['username'].' finished emulating '.$emulating_user.' (UID: #'.$emulating_uid.')');

		// Redirect home
		$this->redirect('/');
	}

	// ===============[ ADMIN ROUTES
	public function backend_index() {
		$this->set('users', $this->User->find('all', array(
			'fields' => array(
				'User.id', 'User.username', 'User.expires', 'User.active', 'Team.name'
			),
		)));
	}

	public function backend_create() {
		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['username']) || 
				!isset($this->request->data['password']) || 
				!isset($this->request->data['team_id']) || 
				!isset($this->request->data['expires']) || 
				!isset($this->request->data['active']) ||
				!is_numeric($this->request->data['expires']) || 
				!is_numeric($this->request->data['active']) ||
				!is_numeric($this->request->data['team_id']) || 
				count($this->request->data) != 5
			) {
				$this->barf();
			}

			$userinfo = $this->request->data;

			// Verify we don't have a username conflict
			$usernameCheck = $this->User->findByUsername($userinfo['username']);

			if ( !empty($usernameCheck) ) throw new BadRequestException('Username already exists!');

			$userinfo['password'] = Security::hash($userinfo['password'], 'blowfish');

			// Create it
			$this->User->create();
			$this->User->save($userinfo);

			// Log it
			$this->logMessage('BACKEND_USER', 'New user created - '.$userinfo['username']);

			// Some happy message saying the user was created....TODO
		}

		$this->set('teams', $this->Team->find('all'));
	}

	public function backend_edit($uid=false) {
		if ( $uid === false || !is_numeric($uid) ) $this->barf();

		// Does this UID even exist?
		$user = $this->User->findById($uid);

		if ( empty($user) ) $this->barf();

		if ( $this->request->is('post') ) {
			if ( 
				!isset($this->request->data['username']) || 
				!isset($this->request->data['password']) || 
				!isset($this->request->data['team_id']) || 
				!isset($this->request->data['expires']) || 
				!isset($this->request->data['active']) ||
				!is_numeric($this->request->data['expires']) || 
				!is_numeric($this->request->data['active']) ||
				!is_numeric($this->request->data['team_id']) || 
				count($this->request->data) != 5
			) {
				$this->barf();
			}

			$userinfo = $this->request->data;

			if ( !empty($userinfo['password']) ) {
				$userinfo['password'] = Security::hash($userinfo['password'], 'blowfish');
			} else {
				unset($userinfo['password']);
			}

			// Update user
			$this->User->id = $uid;
			$this->User->save($userinfo);

			// Log it
			$this->logMessage('BACKEND_USER', 'User '.$userinfo['username'].' was just modified');

			// Set the userinfo to be the saved info we have
			$user = array('User' => $userinfo);

			// Display some message...TODO
		}

		$this->set('teams', $this->Team->find('all'));
		$this->set('user', $user);
	}

	public function backend_toggleActive($uid=false) {
		if ( $uid === false || !is_numeric($uid) ) $this->barf();

		// Check if it's ourself
		if ( $uid === $this->userinfo['id'] ) $this->barf();

		// Does this UID even exist?
		$user = $this->User->findById($uid);

		if ( empty($user) ) $this->barf();

		// Update user
		$this->User->id = $uid;
		$this->User->saveField('active', ($user['User']['active'] == 1 ? 0 : 1));

		// Log it
		$this->logMessage('BACKEND_USER', 'User '.$userinfo['username'].' status was just toggled');

		$this->redirect('/backend/user');
	}

	public function backend_emulate($uid=false) {
		if ( $uid === false || !is_numeric($uid) ) $this->barf();

		// Check if it's ourself
		if ( $uid === $this->userinfo['id'] ) $this->barf();

		// Check if we're already emulating
		if ( $this->Session->read('User.emulating') ) $this->barf();

		// Check the UID
		$user = $this->User->findById($uid);

		if ( empty($user) ) $this->barf();

		// Save in the session who we are
		$this->Session->write('User.emulating', true);
		$this->Session->write('User.emulating_from', $this->userinfo['id']);

		// Log it
		$this->logMessage('USER_EMULATING', $this->userinfo['username'].' is emulating '.$user['User']['username'].' (UID: #'.$uid.')');

		// Emulate!
		$this->populateInfo($uid);

		// Redirect home
		$this->redirect('/');
	}

	public function backend_teams() {
		if ( $this->request->is('post') ) {
			if ( !isset($this->request->data['op']) OR !is_numeric($this->request->data['op']) ) {
				$this->barf();
			}

			switch ( $this->request->data['op'] ) {
				// OP1: Add a user to a team
				case 1:
					if ( 
						!isset($this->request->data['uid']) OR 
						!isset($this->request->data['tid']) OR 
						!is_numeric($this->request->data['uid']) OR 
						!is_numeric($this->request->data['tid'])
					) {
						$this->barf();
					}

					$user = $this->User->findById($this->request->data['uid']);

					if ( empty($user) ) $this->barf();

					// Update team
					$this->User->id = $user['User']['id'];
					$this->User->saveField('team_id', $this->request->data['tid']);

					// Log it
					$this->logMessage('BACKEND_USER', 'User '.$user['User']['username'].' team was changed from TID #'.$user['User']['team_id'].' to TID #'.$this->request->data['tid']);
				break;

				// OP2: Create a new team
				case 2:
					if ( 
						!isset($this->request->data['team_name']) OR 
						!isset($this->request->data['gid']) OR 
						!is_numeric($this->request->data['gid'])
					) {
						$this->barf();
					}

					$team = $this->Team->findByName($this->request->data['team_name']);
					if ( !empty($user) ) throw new BadRequestException('A team with this name already exists!');

					$group = $this->Group->findById($this->request->data['gid']);
					if ( empty($group) ) $this->barf();

					// Create the team
					$this->Team->create();
					$this->Team->save(array(
						'name' => $this->request->data['team_name'],
						'group_id' => $this->request->data['gid'],
					));

					// Log it
					$this->logMessage('BACKEND_USER', 'New team, "'.$this->request->data['team_name'].'" was created and assigned to group GID #'.$this->request->data['gid']);
				break;

				// OP3: Delete a team
				case 3:
					if ( 
						!isset($this->request->data['id']) OR 
						!is_numeric($this->request->data['id'])
					) {
						$this->barf();
					}

					$team = $this->Team->findById($this->request->data['id']);
					if ( empty($team) ) $this->barf();

					// Verify the team has no assigned users
					$users = $this->User->findAllByTeamId($this->request->data['id']);
					if ( !empty($users) ) $this->barf();

					// Delete it
					$this->Team->delete($this->request->data['id']);

					// Log it
					$this->logMessage('BACKEND_USER', 'Team '.$team['Team']['name'].' (#'.$this->request->data['id'].') was deleted');
				break;

				default:
					$this->barf();
				break;
			}
		}

		$this->Team->bindModel(array(
			'hasMany' => array('User'),
		));

		$this->set('teams', $this->Team->find('all'));
		$this->set('groups', $this->Group->find('all'));
	}

	public function backend_groups() {
		if ( $this->request->is('post') ) {
			if ( !isset($this->request->data['op']) OR !is_numeric($this->request->data['op']) ) {
				$this->barf();
			}

			switch ( $this->request->data['op'] ) {
				// OP 1: Add a Team to a Group
				case 1:
					if (
						!isset($this->request->data['tid']) OR 
						!isset($this->request->data['gid']) OR 
						!is_numeric($this->request->data['tid']) OR 
						!is_numeric($this->request->data['gid']) 
					) {
						$this->barf();
					}

					$team = $this->Team->findById($this->request->data['tid']);

					if ( empty($team) ) $this->barf();

					// Update group
					$this->Team->id = $team['Team']['id'];
					$this->Team->saveField('group_id', $this->request->data['gid']);

					// Log it
					$this->logMessage('BACKEND_USER', 'Team #'.$team['Team']['id'].' group was changed from GID #'.$team['Team']['group_id'].' to GID #'.$this->request->data['gid']);
				break;

				// OP 2: Create a new group
				case 2:
					if ( 
						!isset($this->request->data['group_name']) OR
						!isset($this->request->data['backend_access']) OR 
						!is_numeric($this->request->data['backend_access'])
					) {
						$this->barf();
					}

					$group = $this->Group->findByName($this->request->data['group_name']);

					if ( !empty($group) ) {
						throw new BadRequestException('A group with this name already exists!');
					}

					// Create it
					$this->Group->create();
					$this->Group->save(array(
						'name' => $this->request->data['group_name'],
						'backend_access' => $this->request->data['backend_access'],
					));

					// Log it
					$this->logMessage('BACKEND_USER', 'New group, "'.$this->request->data['group_name'].'" was created with backend_access of '.$this->request->data['backend_access']);
				break;

				// OP 3: Delete a group
				case 3:
					if ( !isset($this->request->data['id']) || !is_numeric($this->request->data['id']) ) {
						$this->barf();
					}

					$group = $this->Group->findById($this->request->data['id']);
					if ( empty($group) ) $this->barf();

					// Make sure the group has no teams
					$teams = $this->Team->findAllByGroupId($group['Group']['id']);
					if ( !empty($teams) ) $this->barf();

					// Delete it
					$this->Group->delete($group['Group']['id']);

					// Log it
					$this->logMessage('BACKEND_USER', 'Group "'.$group['Group']['name'].'" (#'.$group['Group']['id'].') was deleted');
				break;

				default:
					$this->barf();
				break;
			}
		}

		$this->Group->bindModel(array(
			'hasMany' => array('Team'),
		));

		$this->set('groups', $this->Group->find('all'));
	}

	// ===============[ ADMIN JSON ROUTES
	public function backend_getUsers($team=false) {
		if ( $team === false || !is_numeric($team) ) return $this->barf(true);

		$users = $this->User->find('all', array(
			'conditions' => array(
				'team_id !=' => $team,
			),
			'fields' => array(
				'User.id', 'User.username'
			),
		));

		return $this->ajaxResponse($users);
	}

	public function backend_getTeams($group=false) {
		if ( $group === false || !is_numeric($group) ) return $this->barf(true);

		$teams = $this->Team->find('all', array(
			'conditions' => array(
				'group_id !=' => $group,
			),
			'fields' => array(
				'Team.id', 'Team.name'
			),
		));

		return $this->ajaxResponse($teams);
	}
}
