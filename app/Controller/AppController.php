<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $uses = array('Team', 'User');

	// User Information
	protected $userinfo  = array();
	protected $teaminfo  = array();
	protected $groupinfo = array();

	// Logged in
	protected $logged_in = false;

	// Permissions
	protected $backend_access = false;

	// Config
	const REFRESH_INTERVAL = (60*5); // 5 minutes

	public function beforeFilter() {
		parent::beforeFilter();

		// Get user information
		if ( $this->Session->check('User') ) {
			$this->userinfo   = $this->Session->read('User');
			$this->teaminfo   = $this->Session->read('Team');
			$this->groupinfo  = $this->Session->read('Group');

			// Check for refresh
			if ( time() >= $this->userinfo['refresh_info'] || isset($_GET['force_refresh']) ) {
				$this->populateInfo($this->userinfo['id']);
			}
		}

		// Set important instance variables
		$this->logged_in      = !empty($this->userinfo);
		$this->backend_access = $this->getPermission('backend_access');

		// Git version (because it looks cool)
		if ( Cache::read('version', 'short') == false ) {
			exec('git describe --always', $mini_hash);
			exec('git log -1', $line);

			Cache::write('version', 'v1-'.trim($mini_hash[0]), 'short');
			Cache::write('version_long', str_replace('commit ','', $line[0]), 'short');
		}
		
		$this->set('version', Cache::read('version', 'short'));
		$this->set('version_long', Cache::read('version_long', 'short'));

		// Set template information
		$this->set('userinfo', $this->userinfo);
		$this->set('teaminfo', $this->teaminfo);
		$this->set('groupinfo', $this->groupinfo);
		$this->set('backend_access', $this->backend_access);
	}

	protected function requireAuthenticated($redirect_to='/user/login') {
		if ( !$this->logged_in ) {
			$this->redirect($redirect_to);
		}
	}

	protected function requireBackend($message='You are unauthorized to access this resource.') {
		$this->requireAuthenticated();

		if ( !$this->backend_access ) {
			throw new ForbiddenException($message);
		}
	}

	protected function populateInfo($userid) {
		// Fetch user info
		$userinfo = $this->User->findById($userid);

		if ( empty($userinfo) ) {
			throw new InternalErrorException('Unknown UserID.');
		}

		// Destroy the current session (if any)
		$this->Session->destroy();

		// Verify the account is enabled/not expired
		if ( $userinfo['User']['active'] != 1 ) {
			$this->redirect('/?account_disabled');
		}
		if ( $userinfo['User']['expires'] != 0 && $userinfo['User']['expires'] <= time() ) {
			$this->redirect('/?account_expired');
		}

		// Generate logout token
		$userinfo['User']['logout_token'] = sha1(String::uuid());

		// Generate refresh interval (5 minutes)
		$userinfo['User']['refresh_info'] = time() + self::REFRESH_INTERVAL;

		// Fetch the team/group info
		$teaminfo = $this->Team->findById($userinfo['User']['team_id']);

		// Clean the password (remove it from the array)
		unset($userinfo['User']['password']);

		// Set the new information
		$this->Session->write($userinfo);
		$this->Session->write($teaminfo);

		// Update our arrays
		$this->userinfo  = $userinfo['User'];
		$this->teaminfo  = $teaminfo['Team'];
		$this->groupinfo = $teaminfo['Group'];
	}

	protected function getPermission($name) {
		return isset($this->groupinfo[$name]) ? $this->groupinfo[$name] : false;
	}
}
