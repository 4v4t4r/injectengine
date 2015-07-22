<?php
App::uses('BackendAppController', 'Controller');

class BackendLogsController extends BackendAppController {
	public $uses = array('Log', 'User');
	public $components = array('Paginator');

	public $paginate = array(
		'limit' => 25,
		'order' => array(
			'Log.id' => 'DESC',
		),
	);

	public function index() {
		// Setup paginator
		$this->Paginator->settings = $this->paginate;

		// Bind the User model to the Logs
		$this->Log->bindModel(array(
			'belongsTo' => array('User'),
		));

		// Setup the view variables & we're done!
		$this->set('logs', $this->Paginator->paginate('Log'));
		$this->set('filtering', false);
	}

	public function filter($type=false) {
		if ( $type === false ) $this->redirect('/backend/logs');

		// Add paginator condition
		$this->paginate['conditions'] = array(
			'Log.type' => $type,
		);

		// Call the index action
		$this->index();

		// Set additional variables
		$this->set('filtering', true);
		$this->set('filtering_type', $type);

		// Render index
		$this->render('index');
	}
}
