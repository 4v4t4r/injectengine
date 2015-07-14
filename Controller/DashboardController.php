<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {
	public $uses = array('Team', 'Inject', 'RequestedCheck', 'CompletedInject', 'Help', 'Log', 'UsedHint');

	const BLUE_TEAM_GID = 2;

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireDashboard();

		$this->set('at_dashboard', true);
	}

	public function index() { $this->redirect('/dashboard/overview'); }

	public function overview() { }

	public function timeline() { }

	public function personal($teams=false) {
		if ( $teams === false ) $this->redirect('/dashboard/setup');

		$teams = explode(',', $teams);

		if ( !is_array($teams) || $teams !== array_filter($teams, 'is_numeric') ) return $this->barf(true);

		$this->set('teams', $this->Team->findAllByGroupIdAndId(self::BLUE_TEAM_GID, $teams));
	}

	public function setup() {
		if ( $this->request->is('post') && isset($this->request->data['teams']) ) {
			if ( !is_array($this->request->data['teams']) ) {
				$teams = array($this->request->data['teams']);
			} else {
				$teams = $this->request->data['teams'];
			}

			if ( $teams !== array_filter($teams, 'is_numeric') ) $this->barf();

			$teams = implode(',', $teams);

			$this->redirect('/dashboard/personal/'.$teams);
		}

		$this->set('teams', $this->Team->findAllByGroupId(self::BLUE_TEAM_GID));
	}

	public function help($id=false) {
		if ( $id === false || !is_numeric($id) ) $this->barf();

		$help = $this->Help->findById($id);

		if ( empty($help) ) $this->barf();

		if ( $this->request->is('post') ) {
			if ( !isset($this->request->data['action']) || !is_numeric($this->request->data['action']) ) return $this->barf(true);

			$this->Help->id = $id;

			switch ( $this->request->data['action'] ) {
				case 1:
					$this->Help->save(array(
						'assigned_user_id' => $this->userinfo['id'],
						'time_started'     => time(),
						'status'           => self::HELP_STATUS_ACK,
					));

					return $this->ajaxResponse('');
				break;

				case 2:
					$this->Help->save(array(
						'time_finished'    => time(),
						'status'           => self::HELP_STATUS_FIN,
					));

					return $this->ajaxResponse('');
				break;

				default:
					return $this->barf(true);
				break;
			}
		}

		$help = $this->Help->getExtendedInfo($id);

		$assigned_user = array();
		if ( $help['Help']['assigned_user_id'] > 0 ) {
			$assigned_user = $this->User->findById($help['Help']['assigned_user_id']);
		}

		$this->set('help', $help);
		$this->set('assigned_user', $assigned_user);
	}

	public function check($id=false) {
		if ( $id === false || !is_numeric($id) ) $this->barf();

		$check = $this->RequestedCheck->findById($id);

		if ( empty($check) ) $this->barf();

		if ( $this->request->is('post') ) {
			if ( !isset($this->request->data['action']) || !is_numeric($this->request->data['action']) ) $this->barf();

			$this->RequestedCheck->id = $id;

			switch ( $this->request->data['action'] ) {
				case 0:
					$this->RequestedCheck->save(array(
						'time_finished'     => time(),
						'status'           => self::CHECK_STATUS_REJECTED,
					));
				break;

				case 1:
					$this->RequestedCheck->save(array(
						'time_finished'    => time(),
						'status'           => self::CHECK_STATUS_ACCEPTED,
					));

					$this->CompletedInject->create();
					$this->CompletedInject->save(array(
						'time'      => $check['RequestedCheck']['time_requested'],
						'user_id'   => $check['RequestedCheck']['user_id'],
						'team_id'   => $check['RequestedCheck']['team_id'],
						'inject_id' => $check['RequestedCheck']['inject_id'],
					));
				break;

				default:
					$this->barf();
				break;
			}

			// Set a flash message - TODO

			// Redirect!
			$this->redirect('/dashboard/personal/'.$check['RequestedCheck']['team_id']);
		}

		$this->set('check', $this->getExtendedInfo($id));
	}
}
