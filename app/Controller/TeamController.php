<?php
App::uses('AppController', 'Controller');

class TeamController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();
		$this->set('at_teampanel', true);
	}

	public function index() {
		$this->set('members', $this->User->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'team_id' => $this->teaminfo['id'],
			),
			'fields' => array(
				'User.id', 'User.username',
			),
		)));
	}
}
