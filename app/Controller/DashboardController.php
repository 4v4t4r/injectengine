<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {
	public $uses = array('Team', 'Inject', 'RequestedCheck', 'CompletedInject', 'Help');

	const BLUE_TEAM_GID = 1;

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

		$this->set('teams', $this->Team->find('all', array(
			'conditions' => array(
				'Team.group_id' => self::BLUE_TEAM_GID,
				'Team.id' => $teams,
			),
		)));
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

		$this->set('teams', $this->Team->find('all', array(
			'conditions' => array(
				'group_id' => self::BLUE_TEAM_GID,
			),
		)));
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
						'status'           => 2,
					));

					return $this->ajaxResponse('');
				break;

				case 2:
					$this->Help->save(array(
						'time_finished'    => time(),
						'status'           => 3,
					));

					return $this->ajaxResponse('');
				break;

				default:
					return $this->barf(true);
				break;
			}
		}

		$help = $this->Help->find('first', array(
			'fields' => array('*'),
			'joins'  => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type'  => 'INNER',
					'conditions' => array(
						'Help.requested_user_id = User.id',
					),
				),
				array(
					'table' => 'teams',
					'alias' => 'Team',
					'type'  => 'INNER',
					'conditions' => array(
						'Help.requested_team_id = Team.id',
					),
				),
				array(
					'table' => 'injects',
					'alias' => 'Inject',
					'type'  => 'INNER',
					'conditions' => array(
						'Help.inject_id = Inject.id',
					),
				),
			),
			'conditions' => array(
				'Help.id' => $id,
			),
		));

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
						'status'           => 1,
					));
				break;

				case 1:
					$this->RequestedCheck->save(array(
						'time_finished'    => time(),
						'status'           => 2,
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

		$this->set('check', $this->RequestedCheck->find('first', array(
			'fields' => array('*'),
			'joins'  => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type'  => 'INNER',
					'conditions' => array(
						'RequestedCheck.user_id = User.id',
					),
				),
				array(
					'table' => 'teams',
					'alias' => 'Team',
					'type'  => 'INNER',
					'conditions' => array(
						'RequestedCheck.team_id = Team.id',
					),
				),
				array(
					'table' => 'injects',
					'alias' => 'Inject',
					'type'  => 'INNER',
					'conditions' => array(
						'RequestedCheck.inject_id = Inject.id',
					),
				),
			),
			'conditions' => array(
				'RequestedCheck.id' => $id,
			),
		)));
	}

	//==========================[ HTML APIS
	public function api_getTeamsStatus($teams=false) {
		$conditions = array(
			'Team.group_id' => self::BLUE_TEAM_GID,
		);

		if ( $teams === false ) {
			$template = 'api_get_teams_status_overview';
		} else {
			$template = 'api_get_teams_status_filtered';

			$teams = explode(',', $teams);

			if ( !is_array($teams) || $teams !== array_filter($teams, 'is_numeric') ) return $this->barf(true);

			$conditions['Team.id'] = $teams;
		}

		$this->Team->bindModel(array(
			'hasMany' => array('User'), 
		));
		$teams = $this->Team->find('all', array(
			'fields' => array('*'),
			'conditions' => $conditions,
			'joins' => array(
				array(
					'table' => 'help',
					'alias' => 'Help',
					'type' => 'LEFT',
					'conditions' => array(
						'Help.requested_team_id = Team.id',
						'Help.status' => array(1, 2),
					),
				),
				array(
					'table' => 'injects',
					'alias' => 'HelpInject',
					'type' => 'LEFT',
					'conditions' => array(
						'HelpInject.id = Help.inject_id',
					),
				),
				array(
					'table' => 'users',
					'alias' => 'HelpUser',
					'type' => 'LEFT',
					'conditions' => array(
						'HelpUser.id = Help.assigned_user_id'
					),
				),
			),
		));

		// Get what inject they're on
		// I seriously have no idea on how to do this better...sorry
		foreach ( $teams AS &$team ) {
			$currentInject = $this->Inject->find('first', array(
				'joins' => array(
					array(
						'table' => 'completed_injects',
						'alias' => 'CompletedInject',
						'type'  => 'LEFT',
						'conditions' => array(
							'CompletedInject.inject_id = Inject.id',
							'CompletedInject.team_id' => $team['Team']['id'],
						),
					),
				),
				'conditions' => array(
					'CompletedInject.id IS NULL'
				),
				'order' => array(
					'Inject.order ASC'
				),
			));

			$requestedChecks = $this->RequestedCheck->find('all', array(
				'fields' => '*',
				'joins' => array(
					array(
						'table' => 'injects',
						'alias' => 'Inject',
						'type'  => 'LEFT',
						'conditions' => array(
							'RequestedCheck.inject_id = Inject.id',
						),
					),
				),
				'conditions' => array(
					'team_id' => $team['Team']['id'],
					'status'  => 0,
				),
			));

			$team['CurrentInject'] = $currentInject['Inject'];
			$team['RequestedChecks'] = $requestedChecks;
		}

		$this->layout = 'ajax';
		$this->set('teams', $teams);

		$this->render($template);
	}

	public function api_getTeamsTimeline($teams=false) {
		$conditions = array();
		
		if ( $teams !== false ) {
			$teams = explode(',', $teams);

			if ( !is_array($teams) || $teams !== array_filter($teams, 'is_numeric') ) return $this->barf(true);

			$conditions['CompletedInject.team_id'] = $teams;
		}

		$this->CompletedInject->bindModel(array(
			'belongsTo' => array('User', 'Team', 'Inject'),
		));

		$timeline = $this->CompletedInject->find('all', array(
			'conditions' => $conditions,
			'order' => 'CompletedInject.time DESC',
		));

		$this->layout = 'ajax';
		$this->set('timeline', $timeline);
	}
}
