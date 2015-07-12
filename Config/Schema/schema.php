<?php
App::uses('ClassRegistry', 'Utility');
App::uses('Security', 'Utility');

class AppSchema extends CakeSchema {

	public function before($event = array()) {
		ConnectionManager::getDataSource('default')->cacheSources = false;

		return true;
	}

	public function after($event = array()) {
		if ( !isset($event['create']) ) return;

		switch ( $event['create'] ) {
			case 'groups':
				$this->_create('Group', array(
					'name'              => 'Management',
					'backend_access'    => 1,
					'dashboard_access'  => 1,
					'teamportal_access' => 0,
				));

				$this->_create('Group', array(
					'name'              => 'Blue Teams',
					'backend_access'    => 0,
					'dashboard_access'  => 9,
					'teamportal_access' => 1,
				));
			break;

			case 'teams':
				$this->_create('Team', array(
					'name' => 'White Team',
					'group_id' => 1,
				));
			break;

			case 'users':
				$this->_create('User', array(
					'team_id'  => 1,
					'username' => 'admin',
					'password' => Security::hash('admin', 'blowfish'),
					'expires'  => 0,
					'active'   => 1,
				));
			break;

			case 'logs':
				$this->_create('Log', array(
					'type'       => 'INSTALLATION',
					'text'       => 'InjectEngine was just installed.',
					'time'       => time(),
					'ip_address' => '127.0.0.1',
					'user_id'    => 1,
				));
			break;
		}
	}

	private function _create($tbl, $data) {
		$table = ClassRegistry::init($tbl);

		$table->create();
		$table->save(array($tbl => $data));
	}

	public $attachments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'inject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'data' => array('type' => 'binary', 'null' => false, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $completed_injects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'time' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'inject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'backend_access' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'dashboard_access' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'teampanel_access' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $help = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'inject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'requested_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'requested_team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'requested_time' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'assigned_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'time_started' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'time_finished' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'status' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false, 'comment' => '1 = New / 2 = ACK / 3 = FIN'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hints = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'inject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'time_wait' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'time_available' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $injects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'time_start' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'time_end' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'dependency' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'Public description', 'charset' => 'latin1'),
		'explanation' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'Used for White Teams only', 'charset' => 'latin1'),
		'type' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false, 'comment' => '1 = Flag | 2 = Submit | 3 = Manual Check'),
		'flag' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'hints_enabled' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'order' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'text' => array('type' => 'string', 'null' => false, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'time' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'ip_address' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $requested_checks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'inject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'time_requested' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'time_finished' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false, 'comment' => '0 = New | 1 = Denied | 2 = Accepted'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $teams = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $used_hints = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'inject_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'hint_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'time' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'username' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'password' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'expires' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

}
