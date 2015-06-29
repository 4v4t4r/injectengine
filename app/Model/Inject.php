<?php
App::uses('AppModel', 'Model');
/**
 * Inject Model
 *
 */
class Inject extends AppModel {

	public function getAllActiveInjects($team_id, $group_id) {
		$query = '
			SELECT
				Inject.*, CompletedInject.*, User.username
			FROM 
				injects AS Inject 
			LEFT JOIN 
				completed_injects AS CompletedInject 
			ON 
				CompletedInject.inject_id = Inject.id AND CompletedInject.team_id = ?
			LEFT JOIN 
				users AS User
			ON 
				CompletedInject.user_id = User.id 
			WHERE 
				Inject.active = ? AND 
				Inject.group_id = ? AND 
				(
					(Inject.time_start <= ? OR Inject.time_start = ?) AND 
					(Inject.time_end >= ? OR Inject.time_end = ?)
				)
			ORDER BY
				Inject.order ASC';

		return $this->query($query, array($team_id, 1, $group_id, time(), 0, time(), 0));
	}
}
