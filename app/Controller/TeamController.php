<?php
App::uses('AppController', 'Controller');

class TeamController extends AppController {
	public $uses = array('CompletedInject', 'ScoreEngineChecks', 'ScoreEngineServiceData');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();
		$this->set('at_teampanel', true);
	}

	public function index() {
		// sorry
		$tid = (int) substr($this->userinfo['username'], -1);

		$this->set('data', $this->ScoreEngineChecks->getTeamChecks($tid));
		$this->set('latest', $this->ScoreEngineChecks->getLastTeamCheck($tid));
	}

	public function service($id=false) {
		if ( $id === false ) $this->barf();

		// sorry
		$tid = (int) substr($this->userinfo['username'], -1);

		$this->set('data', $this->ScoreEngineChecks->find('all', array(
			'conditions' => array(
				'team_id' => $tid,
				'service_id' => $id
			),
			'limit' => 20,
			'order' => 'time DESC',
		)));
	}

	public function events() {
		$this->set('timeline', $this->CompletedInject->getTimelineInfo($this->teaminfo['id']));
	}

	public function membership() {
		$this->set('members', $this->User->getTeamMembership($this->teaminfo['id']));
	}

	public function config() {
		// sorry
		$tid = (int) substr($this->userinfo['username'], -1);
		$data = $this->ScoreEngineServiceData->getData($tid);

		$canEdit = function($id) use($data) {
			foreach ( $data AS $group => $options ) {
				foreach ( $options AS $opt ) {
					if ( $opt['id'] == $id ) {
						return $opt['edit'];
					}
				}
			}

			return false;
		};

		$updateOpt = function($id, $value) use(&$data) {
			foreach ( $data AS $group => &$options ) {
				foreach ( $options AS &$opt ) {
					if ( $opt['id'] == $id ) {
						$opt['value'] = $value;
					}
				}
			}

			return false;
		};

		if ( $this->request->is('post') ) {
			foreach ( $this->request->data AS $opt => $value ) {
				$opt = (int) str_replace('opt', '', $opt);
				if ( $opt < 0 || !is_numeric($opt) ) continue;

				if ( $canEdit($opt) ) {
					$this->ScoreEngineServiceData->updateConfig($opt, $value);

					$updateOpt($opt, $value);
				}
			}

			// Message
			$this->Flash->success('Updated Score Engine Config!');
		}

		$this->set('data', $data);
	}
}
