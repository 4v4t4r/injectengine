<?php
App::uses('AppController', 'Controller');

class ApiAppController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();

		// If we're doing an API request not logged in, kill it.
		if ( !$this->logged_in ) {
			return $this->ajaxResponse('Please login', 401);
		}
	}
}
