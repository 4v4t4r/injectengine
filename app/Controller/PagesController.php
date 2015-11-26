<?php
App::uses('AppController', 'Controller');

class PagesController extends AppController {
	public $uses = array('ScoreEngineChecks');

	public function index() {
		$this->set('at_home', true);
	}

	public function scoreboard() {
		$this->set('at_scoreboard', true);

		$this->set('data', $this->ScoreEngineChecks->getChecks());
	}
}
