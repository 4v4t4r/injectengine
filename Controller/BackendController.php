<?php
App::uses('AppController', 'Controller');

class BackendController extends AppController {
	public $uses = array('Log', 'User');
	public $components = array('Paginator');

	public $paginate = array(
		'limit' => 25,
		'order' => array(
			'Log.id' => 'DESC',
		),
	);

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireBackend();
		$this->set('at_backendpanel', true);
	}

	public function index() {
		$this->redirect('/');
	}

	public function logs($type=false) {
		$this->Paginator->settings = $this->paginate;

		if ( $type !== false ) {
			$this->Paginator->settings['conditions'] = array(
				'Log.type' => $type,
			);

			$this->set('filtering', true);
			$this->set('filtering_type', $type);
		} else {
			$this->set('filtering', false);
		}

		$this->Log->bindModel(array(
			'belongsTo' => array('User'),
		));
		$this->set('logs', $this->Paginator->paginate('Log'));
	}
}
