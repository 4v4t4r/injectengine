<?php
App::uses('AppController', 'Controller');

class TeamController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();
		$this->set('at_teampanel', true);
	}

	public function index() {

	}
}
