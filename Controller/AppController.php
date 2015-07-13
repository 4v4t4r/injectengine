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
	public $uses = array('Team', 'User', 'Log');

	// User Information
	protected $userinfo  = array();
	protected $teaminfo  = array();
	protected $groupinfo = array();

	// Logged in
	protected $logged_in = false;

	// Permissions
	protected $backend_access = false;
	protected $dashboard_access = false;
	protected $teampanel_access = false;

	// Config
	const REFRESH_INTERVAL = (60*5); // 5 minutes
	const SESSION_TIMEOUT  = (60*30); // 30 minutes

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
		$this->logged_in        = !empty($this->userinfo);
		$this->backend_access   = $this->getPermission('backend_access');
		$this->dashboard_access = $this->getPermission('dashboard_access');
		$this->teampanel_access = $this->getPermission('teampanel_access');

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
		$this->set('dashboard_access', $this->dashboard_access);
		$this->set('teampanel_access', $this->teampanel_access);
		$this->set('emulating', $this->Session->read('User.emulating'));

		// If we're doing a backend request, require backend access
		if ( isset($this->request->params['backend']) && $this->request->params['backend'] ) {
			$this->requireBackend();
			$this->set('at_backendpanel', true);
		}

		// If we're doing an API request not logged in, kill it.
		if ( isset($this->request->params['api']) && $this->request->params['api'] && !$this->logged_in ) {
			return $this->ajaxResponse('Please login', 401);
		}

		// Extend the session
		$this->Session->write('Config.time', time()+self::SESSION_TIMEOUT);
	}

	public function afterFilter() {
		parent::afterFilter();

		if ( !$this->backend_access && false ) {
			// Clean the HTML
			$buffer = $this->response->body();

			$search = array(
				'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
				'/[^\S ]+\</s',  // strip whitespaces before tags, except space
				'/(\s)+/s'       // shorten multiple whitespace sequences
			);

			$replace = array(
				'>',
				'<',
				'\\1'
			);

			$this->response->body(preg_replace($search, $replace, $buffer));
		}
	}

	protected function requireAuthenticated($redirect_to='/user/login') {
		$this->Session->write('auth.from', $this->request->here);

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

	protected function requireDashboard($message='You are unauthorized to access this resource.') {
		$this->requireAuthenticated();

		if ( !$this->dashboard_access ) {
			throw new ForbiddenException($message);
		}
	}

	protected function requireTeamPanel($message='You are unauthorized to access this resource.') {
		$this->requireAuthenticated();

		if ( !$this->teampanel_access ) {
			throw new ForbiddenException($message);
		}
	}

	protected function populateInfo($userid) {
		// Fetch user info
		$userinfo = $this->User->findById($userid);

		if ( empty($userinfo) ) {
			throw new InternalErrorException('Unknown UserID.');
		}

		// Save specific info from the current session (if exists)
		$emulating = $this->Session->read('User.emulating');
		$emulating_from = -1;

		if ( $emulating ) {
			$emulating_from = $this->Session->read('User.emulating_from');
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
		$userinfo['User']['logout_token'] = Security::hash(CakeText::uuid());

		// Generate refresh interval (5 minutes)
		$userinfo['User']['refresh_info'] = time() + self::REFRESH_INTERVAL;

		// Add the emulating information
		$userinfo['User']['emulating'] = $emulating;
		$userinfo['User']['emulating_from'] = $emulating_from;

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

	// Helper function for funky cases
	protected function barf($ajax=false, $message='Stop trying to hack the InjectEngine!') {
		$this->logMessage('BARF', 'Barf triggered by user on '.$this->request->params['controller'].'@'.$this->request->params['action']);

		if ( $ajax ) {
			return $this->ajaxResponse($message, 400);
		}

		throw new BadRequestException($message);
	}

	protected function ajaxResponse($data, $status=200) {
		$this->layout = 'ajax';
		
		return new CakeResponse(array(
			'body' => (is_array($data) ? json_encode($data) : $data),
			'status' => $status
		));
	}

	protected function logMessage($type, $message, $ip=-1, $user_id=-1) {
		if ( $ip === -1 ) $ip = $_SERVER['REMOTE_ADDR'];
		if ( $user_id === -1 && !empty($this->userinfo) ) $user_id = $this->userinfo['id'];

		$this->Log->create();
		$this->Log->save(array(
			'type' => strtoupper($type),
			'text' => $message,
			'ip_address' => $ip,
			'user_id' => $user_id,
			'time' => time(),
		));
	}
}
