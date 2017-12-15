<?php
class User {
	private $_db,
			$_sessionName = null,
			$_cookieName = null,
			$_data = array(),
			$_isLoggedIn = false,
			$_bcrypt;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_bcrypt = new Bcrypt;
		
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		// Check if a session exists and set user if so.
		if(Session::exists($this->_sessionName) && !$user) {
			$user = Session::get($this->_sessionName);

			if($this->find($user)) {
				$this->_isLoggedIn = true;
			} else {
				$this->logout();
			}
		} else {
			$this->find($user);
		}
	}

	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	public function find($user = null) {
		// Check if user_id specified and grab details
		if($user) {
			$field = (is_numeric($user)) ? 'user_id' : 'email';
			$data = $this->_db->get('_QU_e', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('_QU_e', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->user_id;
		}
		
		if(!$this->_db->update('_QU_e', $id, $fields)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function login($username = null, $password = null, $remember = false) {

		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->user_id);
		} else {
			$user = $this->find($username);

			if($user) {
				if($this->_bcrypt->verify($password, $this->data()->password)){
					Session::put($this->_sessionName, $this->data()->user_id);

					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('_QU_e_session', array('user_id', '=', $this->data()->user_id));

						if(!$hashCheck->count()) {
							$this->_db->insert('_QU_e_session', array(
								'user_id' => $this->data()->user_id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}
			}
		}

		return false;
	}

	/*public function hasPermission($key) {
		$group_id = $this->_db->query("SELECT * FROM _QU_e_group_assoc WHERE user_id = ?", array($this->data()->user_id));
		foreach ($group_id->results() as $group_id_data) {
			$group_id_value = $group_id_data->group_id;
		}
		// $group = $this->_db->query("SELECT * FROM _QU_e_group WHERE group_id = ?", array($this->data()->user_group));
		$group = $this->_db->query("SELECT * FROM _QU_e_group WHERE group_id = ?", array($group_id_value));
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);

			if($permissions[$key] === 1) {
				return true;
			}
		}

		return false;
	}*/

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function data() {
		return $this->_data;
	}

	public function logout() {
		$this->_db->delete('_QU_e_session', array('user_id', '=', $this->data()->user_id));

		Cookie::delete($this->_cookieName);
		Session::delete($this->_sessionName);
	}
}