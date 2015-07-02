<?php
App::uses('AppController', 'Controller');

class TeamController extends AppController {
	public $uses = array('CompletedInject');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();
		$this->set('at_teampanel', true);
	}

	public function index() {
		$this->CompletedInject->bindModel(array(
			'belongsTo' => array('User', 'Inject'),
		));

		$this->set('timeline', $this->CompletedInject->find('all', array(
			'conditions' => array(
				'CompletedInject.team_id' => $this->teaminfo['id'],
			),
			'order' => 'CompletedInject.time DESC',
		)));
	}

	public function membership() {
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
