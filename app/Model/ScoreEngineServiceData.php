<?php
App::uses('AppModel', 'Model');
/**
 * ScoreEngineServiceData Model
 *
 */
class ScoreEngineServiceData extends AppModel {
	public $useDbConfig = 'scoreengine';
	public $useTable = 'services_data';

	public function getData($tid) {
		$sql = "SELECT
				services_data.id,
				services_data.key,
				services_data.value,
				services_data.edit,
				services.name,
				services.id
			FROM
				services_data
			LEFT JOIN
				services ON ( services_data.service_id = services.id )
			WHERE
				services_data.team_id = {$tid}
			ORDER BY
				services_data.service_id,
				services_data.order";

		$result = $this->query($sql);
		$data = array();

		foreach ( $result AS $d ) {
			if ( !isset($data[$d['services']['name']]) ) {
				$data[$d['services']['name']] = array();
			}

			$data[$d['services']['name']][] = $d['services_data'];
		}

		return $data;
	}

	public function getConfig($team_id, $service_id, $key=false) {
		$conditions = array();
		$conditions['team_id'] = $team_id;
		$conditions['service_id'] = $service_id;

		if ( $key != false ) {
			$conditions['key'] = $key;
		}

		return $this->find('all', array(
			'conditions' => $conditions,
		));
	}

	public function updateConfig($id, $value) {
		$this->query('UPDATE services_data SET `value` = "'.$value.'" WHERE id = '.$id.' LIMIT 1');
	}
}
