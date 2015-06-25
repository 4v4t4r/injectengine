<?php
App::uses('AppController', 'Controller');

class InjectsController extends AppController {
	public $uses = array('Hint', 'Inject', 'CompletedInject', 'UsedHint');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->requireAuthenticated();
		$this->set('at_injects', true);
	}

	public function index() {
		$this->set('injects', $this->Inject->getAllActiveInjects($this->teaminfo['id'], $this->groupinfo['id']));
	}

	public function hint($id=false) {
		if ( $id === false || !is_numeric($id) ) $this->barf();

		// Get the inject
		$inject = $this->Inject->findByIdAndActiveAndHintsEnabled($id, 1, 1);
		if ( empty($inject) ) $this->barf();

		// Get the hints
		$hints = $this->Hint->getHintsForInject($id);
		if ( empty($hints) ) $this->barf();

		// Get the used hints
		$usedhints = $this->UsedHint->findAllByTeamIdAndInjectId($this->teaminfo['id'], $id);

		// Merge the used hints + hints
		$unlockedHint = function($id) use($usedhints) {
			foreach ( $usedhints AS $uh ) {
				if ( $uh['UsedHint']['hint_id'] == $id ) return true;
			}

			return false;
		};

		foreach ( $hints AS &$hint ) {
			$hint['Hint']['unlocked'] = $unlockedHint($hint['Hint']['id']);
		}

		$this->layout = 'ajax';

		$this->set('inject', $inject);
		$this->set('hints', $hints);
	}

	public function takeHint() {
		if ( !$this->request->is('post') ) $this->redirect('/');

		if ( !isset($this->request->data['id']) ) $this->barf();

		// Check the inject
		$inject = $this->Inject->findByIdAndActiveAndHintsEnabled($this->request->data['id'], 1, 1);

		if ( empty($inject) ) $this->barf();

		// Get the next hint
		$hint = $this->Hint->getNextHintForInject($this->request->data['id'], $this->teaminfo['id']);

		if ( empty($hint) ) throw new BadRequestException('There are seriously no more hints, stop requesting for them.');
		$hint = current($hint);

		// Mark it as used
		//$used_hints = $this->UsedHint->findAllByTeamIdAndInjectId($this->teaminfo['id'], $this->request->data['id']);
		$this->UsedHint->create();
		$this->UsedHint->save(array(
			'user_id' => $this->userinfo['id'],
			'team_id' => $this->teaminfo['id'],
			'inject_id' => $this->request->data['id'],
			'hint_id' => $hint['Hint']['id'],
			'time' => time(),
		));

		die($hint['Hint']['description']);
	}

	public function submit() {
		if ( !$this->request->is('post') ) $this->redirect('/');

		if ( !isset($this->request->data['id']) ) $this->barf();

		$inject = $this->Inject->findByIdAndActive($this->request->data['id'], 1);

		if ( empty($inject) ) $this->barf();

		switch ( $inject['Inject']['type'] ) {
			case 1:
				// Flag based inject
				if ( !isset($this->request->data['value']) ) $this->barf();

				if ( $this->request->data['value'] !== $inject['Inject']['flag'] ) {
					throw new BadRequestException('Incorrect flag!');
				}

				// They solved it...
				$this->CompletedInject->create();
				$this->CompletedInject->save(array(
					'inject_id' => $this->request->data['id'],
					'user_id'   => $this->userinfo['id'],
					'team_id'   => $this->teaminfo['id'],
					'time'      => time(),
				));

				die('Correct flag!');
			break;

			case 2:

			break;

			default:
				throw new BadRequestException('Unknown type for this inject!');
			break;
		}
	}

	// Helper function for funky cases
	private function _barf() {
		throw new BadRequestException('Stop trying to hack the InjectEngine!');
	}
}
