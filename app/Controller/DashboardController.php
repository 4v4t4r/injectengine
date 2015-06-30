<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {
	public $uses = array('Team', 'Inject');

	const BLUE_TEAM_GID = 1;

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireDashboard();

		$this->set('at_dashboard', true);
	}

	public function index() { $this->redirect('/dashboard/overview'); }

	public function overview() { }

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

			$team['CurrentInject'] = $currentInject['Inject'];
		}

		$this->layout = 'ajax';
		$this->set('teams', $teams);

		$this->render($template);
	}

	//==========================[ JS APIS
	public function api_getTeams() {
		$this->Team->bindModel(array(
			'belongsTo' => array('Group')
		));

		return $this->ajaxResponse($this->Team->find('all', array(
			'conditions' => array(
				'Group.id !=' => $this->groupinfo['id'],
			),
		)));
	}
}
