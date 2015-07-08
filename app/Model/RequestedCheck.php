<?php
App::uses('AppModel', 'Model');
/**
 * RequestedCheck Model
 *
 */
class RequestedCheck extends AppModel {
	public $useTable = 'requested_checks';

	public function getExtendedInfo($id) {
		return $this->find('first', array(
			'fields' => array('*'),
			'joins'  => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type'  => 'INNER',
					'conditions' => array(
						'RequestedCheck.user_id = User.id',
					),
				),
				array(
					'table' => 'teams',
					'alias' => 'Team',
					'type'  => 'INNER',
					'conditions' => array(
						'RequestedCheck.team_id = Team.id',
					),
				),
				array(
					'table' => 'injects',
					'alias' => 'Inject',
					'type'  => 'INNER',
					'conditions' => array(
						'RequestedCheck.inject_id = Inject.id',
					),
				),
			),
			'conditions' => array(
				'RequestedCheck.id' => $id,
			),
		));
	}
}
