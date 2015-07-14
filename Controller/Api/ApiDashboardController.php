<?php
App::uses('ApiAppController', 'Controller');

class ApiDashboardController extends ApiAppController {
	public $uses = array('Team', 'Inject', 'RequestedCheck', 'CompletedInject', 'Help', 'Log', 'UsedHint');

	const BLUE_TEAM_GID = 2;

	public function getTeamsStatus($teams=false) {
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

	public function getTeamsTimeline($teams=false) {
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
	public function getTeamsInjectStandings($teams=false) {
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

	public function getInjectCompletionRates() {
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

	public function getHintUsagePerTeam($teams=false) {
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
