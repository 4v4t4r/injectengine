<?php
App::uses('AppModel', 'Model');
/**
 * ScoreEngineChecks Model
 *
 */
class ScoreEngineChecks extends AppModel {
	public $useDbConfig = 'scoreengine';
	public $useTable = 'checks';

	public function getChecks() {
		$sql = "SELECT
				SUM(checks.status = 0) AS passed,
				COUNT(checks.status) AS total,
				teams.name
			FROM
				checks
			LEFT JOIN
				teams
			ON
				checks.team_id = teams.id
			GROUP BY
				checks.team_id
			ORDER BY
				teams.id";

		return $this->query($sql);
	}

	public function getTeamChecks($tid) {
		$sql = "SELECT
				SUM(checks.status = 0) AS passed,
				COUNT(checks.status) AS total,
				services.name,
				services.id
			FROM
				checks
			LEFT JOIN
				services
			ON
				checks.service_id = services.id
			WHERE
				checks.team_id = {$tid}
			GROUP BY
				checks.service_id";

		return $this->query($sql);
	}

	// It's 3:55AM, screw it we'll get everything then filter it out
	// -jamesdro
	public function getLastTeamCheck($tid) {
		$sql = "SELECT
				checks.service_id,
				checks.status,
				checks.time,
				services.name
			FROM
				checks
			LEFT JOIN
				services ON (checks.service_id = services.id)
			WHERE
				checks.team_id = 1
			ORDER BY
				checks.time DESC";

		$result = $this->query($sql);
		$data = array();

		foreach ( $result AS $res ) {
			if ( !isset($data[$res['services']['name']]) ) {
				$data[$res['services']['name']] = $res['checks'];
			}
		}

		return $data;
	}
}
