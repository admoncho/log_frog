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
		// Check if id specified and grab details
		if($user) {

			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('user', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('user', $fields)) {
			throw new Exception('Se produjo un error creando la cuenta, por favor intente de nuevo.');
		}
	}

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		
		if(!$this->_db->update('user', $id, $fields)) {
			throw new Exception('Se produjo un error.');
		}
	}

	public function login($username = null, $password = null, $remember = false) {

		if(!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			$user = $this->find($username);

			if($user) {
				if($this->_bcrypt->verify($password, $this->data()->password)){
					Session::put($this->_sessionName, $this->data()->id);

					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('user_session', array('user_id', '=', $this->data()->id));

						if(!$hashCheck->count()) {
							$this->_db->insert('user_session', array(
								'user_id' => $this->data()->id,
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

	public function hasPermission($key) {
		
		$group = $this->_db->query("SELECT * FROM user_group WHERE group_id = ?", array($this->data()->user_group));
		
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);

			if($permissions[$key] === 1) {
				return true;
			}
		}

		return false;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function data() {
		return $this->_data;
	}

	public function logout() {
		$this->_db->delete('user_session', array('user_id', '=', $this->data()->id));

		Cookie::delete($this->_cookieName);
		Session::delete($this->_sessionName);
	}
}