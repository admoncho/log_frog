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
						case 'unique_user_i':
							$check = $this->_db->get('_QU_i', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_user_e':
							$check = $this->_db->get('_QU_e', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_blog_article':
							$check = $this->_db->get('cms_blog_article', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_faq':
							$check = $this->_db->get('cms_faqs_qa', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_header_link':
							$check = $this->_db->get('cms_header_link', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_header_quick_menu_link':
							$check = $this->_db->get('cms_header_quick_menu_link', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_blog_article_image':
							$check = $this->_db->get('cms_blog_article_image', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_wop_seo_title_element':
							$check = $this->_db->get('cms_wop_seo', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_cms_wop_seo_meta_description_tag':
							$check = $this->_db->get('cms_wop_seo', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'email':
							if(!preg_match($rule_value, $value)) {
								$this->addError("Please provide a valid email address.");
							}
						break;

						// Product categories
						case 'unique_cms_product_category':
							$check = $this->_db->get('cms_product_category', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;

						// Day tour location
						case 'unique_cms_day_tour_location':
							$check = $this->_db->get('cms_day_tour_location', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;

						// Day tour highlight
						case 'unique_cms_day_tour_highlight':
							$check = $this->_db->get('cms_day_tour_highlight', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;

						// Day tour inclusion
						case 'unique_cms_day_tour_inclusion':
							$check = $this->_db->get('cms_day_tour_inclusion', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;

						// Day tour
						case 'unique_cms_day_tour':
							$check = $this->_db->get('cms_day_tour', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;

						// Product states
						case 'unique_cms_product_state':
							$check = $this->_db->get('cms_product_state', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;

						case 'unique_cms_product':
							$check = $this->_db->get('cms_product', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						
						// Truck loaders
						case 'unique_company_name':
							$check = $this->_db->get('user_e_profile_client', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_mc_number':
							$check = $this->_db->get('user_e_profile_client', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_us_dot_number':
							$check = $this->_db->get('user_e_profile_client', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_ein_number':
							$check = $this->_db->get('user_e_profile_client', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
							}
						break;
						case 'unique_load_number':
							$check = $this->_db->get('loader_load', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} is already taken.");
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

