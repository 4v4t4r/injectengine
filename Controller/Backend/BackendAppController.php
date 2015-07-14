<?php
App::uses('AppController', 'Controller');

class BackendAppController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();

		// We're doing a backend request, require backend access
		$this->requireBackend();
		$this->set('at_backendpanel', true);
	}
}
