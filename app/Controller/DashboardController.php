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

			$team['CurrentInject'] = isset($currentInject['Inject']) ? $currentInject['Inject'] : array(
				'title' => 'N/A',

			);
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
		$completed_timeline = $this->CompletedInject->find('all', array(
			'conditions' => $conditions,
			'order' => 'CompletedInject.time DESC',
		));

		$this->Log->bindModel(array(
			'belongsTo' => array('User'),
		));
		$logs_timeline = $this->Log->find('all', array(
			'fields' => array('*'),
			'joins' => array(
				array(
					'table' => 'teams',
					'alias' => 'Team',
					'type'  => 'INNER',
					'conditions' => array(
						'Team.id = User.team_id'
					),
				),
			),
			'conditions' => array(
				'Log.type' => 'INJECT',
				'Log.text LIKE' => '% flag was entered incorrectly - user put "%"',
			),
			'order' => 'Log.time DESC'
		));

		$timeline = array_merge($completed_timeline, $logs_timeline);

		usort($timeline, function($aa, $bb) {
			$a = isset($aa['CompletedInject']) ? $aa['CompletedInject']['time'] : $aa['Log']['time'];
			$b = isset($bb['CompletedInject']) ? $bb['CompletedInject']['time'] : $bb['Log']['time'];

			return ($a < $b) ? -1 : 1;
		});

		$this->layout = 'ajax';
		$this->set('timeline', $timeline);
	}

	//==========================[ JS APIS
	public function api_getTeamsInjectStandings($teams=false) {
		$conditions = array(
			'group_id' => self::BLUE_TEAM_GID,
		);
		
		if ( $teams !== false ) {
			$teams = explode(',', $teams);

			if ( !is_array($teams) || $teams !== array_filter($teams, 'is_numeric') ) return $this->barf(true);

			$conditions['Team.id'] = $teams;
		}

		$teams = $this->Team->find('all', array(
			'conditions' => $conditions,
		));

		$output = array(
			'cols' => array(
				array(
					'id' => 'team',
					'label' => 'Team Name',
					'type' => 'string',
				),
				array(
					'id' => 'inject',
					'label' => 'Current Inject',
					'type' => 'number',
				),
			),
			'rows' => array(),
		);

		foreach ( $teams AS $team ) {
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

			$output['rows'][] = array(
				'c' => array(
					array(
						'v' => $team['Team']['name'],
					),
					array(
						'v' => (int) isset($currentInject['Inject']['id']) ? $currentInject['Inject']['id'] : 0,
						'f' => isset($currentInject['Inject']['title']) ? $currentInject['Inject']['title'] : 'N/A',
					),
				),
			);
		}

		return $this->ajaxResponse($output);
	}

	public function api_getInjectCompletionRates() {
		$output = array(
			'cols' => array(
				array(
					'id' => 'inject',
					'label' => 'Inject',
					'type' => 'string',
				),
				array(
					'id' => 'completions',
					'label' => 'Completions',
					'type' => 'number',
				),
			),
			'rows' => array(),
		);

		$injects = $this->Inject->find('all', array(
			'fields' => array(
				'Inject.title', 'COUNT(CompletedInject.id) AS Completions',
			),
			'joins' => array(
				array(
					'table' => 'completed_injects',
					'alias' => 'CompletedInject',
					'type'  => 'LEFT',
					'conditions' => array(
						'CompletedInject.inject_id = Inject.id',
					),
				),
			),
			'group' => array('Inject.id'),
		));

		foreach ( $injects AS $inject ) {
			$output['rows'][] = array(
				'c' => array(
					array(
						'v' => $inject['Inject']['title'],
					),
					array(
						'v' => (int) $inject[0]['Completions'],
					),
				),
			);
		}

		return $this->ajaxResponse($output);
	}

	public function api_getHintUsagePerTeam($teams=false) {
		$conditions = array(
			'group_id' => self::BLUE_TEAM_GID,
		);
		
		if ( $teams !== false ) {
			$teams = explode(',', $teams);

			if ( !is_array($teams) || $teams !== array_filter($teams, 'is_numeric') ) return $this->barf(true);

			$conditions['Team.id'] = $teams;
		}

		$output = array(
			'cols' => array(
				array(
					'id' => 'team',
					'label' => 'Team',
					'type' => 'string',
				),
				array(
					'id' => 'used_hints',
					'label' => 'Used Hints',
					'type' => 'number',
				),
			),
			'rows' => array(),
		);

		$teams = $this->Team->find('all', array(
			'fields' => array(
				'Team.name', 'COUNT(UsedHint.id) AS UsedHints',
			),
			'joins' => array(
				array(
					'table' => 'used_hints',
					'alias' => 'UsedHint',
					'type'  => 'LEFT',
					'conditions' => array(
						'UsedHint.team_id = Team.id',
					),
				),
			),
			'conditions' => $conditions,
			'group' => array('Team.id'),
		));

		foreach ( $teams AS $team ) {
			$output['rows'][] = array(
				'c' => array(
					array(
						'v' => $team['Team']['name'],
					),
					array(
						'v' => (int) $team[0]['UsedHints'],
					),
				),
			);
		}

		return $this->ajaxResponse($output);
	}
}
