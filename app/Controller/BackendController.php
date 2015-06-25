<?php
App::uses('AppController', 'Controller');

class BackendController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireBackend();
		$this->set('at_backendpanel', true);
	}

	public function index() {

	}
}
