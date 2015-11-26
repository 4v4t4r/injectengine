<?php
App::uses('AppModel', 'Model');
/**
 * Help Model
 *
 */
class Help extends AppModel {
	public $useTable = 'help';

	public function getExtendedInfo($id) {
		return $this->find('first', array(
			'fields' => array('*'),
			'joins'  => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type'  => 'INNER',
					'conditions' => array(
						'Help.requested_user_id = User.id',
					),
				),
				array(
					'table' => 'teams',
					'alias' => 'Team',
					'type'  => 'INNER',
					'conditions' => array(
						'Help.requested_team_id = Team.id',
					),
				),
				array(
					'table' => 'injects',
					'alias' => 'Inject',
					'type'  => 'INNER',
					'conditions' => array(
						'Help.inject_id = Inject.id',
					),
				),
			),
			'conditions' => array(
				'Help.id' => $id,
			),
		));
	}
}
