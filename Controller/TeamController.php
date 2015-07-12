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
		$this->set('timeline', $this->getTimelineInfo($this->teaminfo['id']));
	}

	public function membership() {
		$this->set('members', $this->getTeamMembership($this->teaminfo['id']));
	}
}
