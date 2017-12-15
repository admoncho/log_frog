<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {
				
				$value = trim($source[$item]);

				if($rule === 'required' && $rule_value === true && empty($value)) {
					$this->addError("{$item} is required.");
				} else if (!empty($value)) {

					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be a maximum of {$rule_value} characters.");
							}
						break;
						# Numbers only
						case 'number':
							if(!preg_match($rule_value, $value)) {
								$this->addError("Numbers only.");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}.");
							}
						break;
						case 'unique_user':
							$check = $this->_db->get('user', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_blog_item':
							$check = $this->_db->get('blog_item', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'email':
							if(!preg_match($rule_value, $value)) {
								$this->addError("Please provide a valid email address.");
							}
						break;
					}

				}

			}
		}

		if(empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	protected function addError($error) {
		$this->_errors[] = $error;
	}

	public function passed() {
		return $this->_passed;
	}

	public function errors() {
		return $this->_errors;
	}
}

