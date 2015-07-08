<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {
	public $belongsTo = array('Team');

	public function getTeamMembership($id) {
		return $this->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'team_id' => $id,
			),
			'fields' => array(
				'User.id', 'User.username',
			),
		));
	}
}
