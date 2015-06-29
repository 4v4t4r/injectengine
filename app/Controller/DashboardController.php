<?php
App::uses('AppController', 'Controller');

class DashboardController extends AppController {
	public $uses = array('Team');

	const BLUE_TEAM_GID = 1;

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireDashboard();

		$this->set('at_dashboard', true);
	}

	public function index() { $this->redirect('/dashboard/overview'); }

	public function overview() {
		$this->Team->bindModel(array(
			'hasMany' => array('User'), 
		));
		$this->set('teams', $this->Team->find('all', array(
			'fields' => array('*'),
			'conditions' => array(
				'group_id' => self::BLUE_TEAM_GID,
			),
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
		)));
	}

	public function personal($teams=false) {
		if ( $teams === false ) $this->redirect('/dashboard/setup');

		$teams = explode(',', $teams);

		if ( !is_array($teams) || $teams !== array_filter($teams, 'is_numeric') ) $this->barf();

		$this->Team->bindModel(array(
			'hasMany' => array('User'), 
		));
		$this->set('teams', $this->Team->find('all', array(
			'fields' => array('*'),
			'conditions' => array(
				'Team.group_id' => self::BLUE_TEAM_GID,
				'Team.id' => $teams,
			),
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

	//==========================[ JS APIS
	public function getTeams() {
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
