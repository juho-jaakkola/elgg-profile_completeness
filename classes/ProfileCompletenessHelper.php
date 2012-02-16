<?php

class ProfileCompletenessHelper {
	private $user;
	private $total = 0;
	private $filled = array();
	private $missing = array();
	private $profile_fields = array();
	private $enabled_fields = array();
	private $settings;
	
	/**
	 * Initialize profile fields.
	 */
	public function __construct(ElggUser $user = null) {
		$this->user = $user;
		$profile_defaults = elgg_get_config('profile_fields');
		$this->profile_fields = elgg_trigger_plugin_hook('profile:fields', 'profile', NULL, $profile_defaults);
	   
		// Add icon manually
		$this->profile_fields['icon'] = 'icon';
		
		// We don't need these for all functionalities
		if ($user) {
			$this->getPluginSettings();
			$this->getEnabledFields();
			$this->total = count($this->enabled_fields);
			$this->checkProfileFields();
		}
	}
	
	private function getPluginSettings() {
		$this->settings = elgg_get_plugin_from_id('profile_completeness');
	}
	
	/**
	 * Get all profile fields defined in the system.
	 * 
	 * @return array $this->profile_fields
	 */
	public function getFields() {
		return $this->profile_fields;
	}
	
	/**
	 * Get enabled fields as defined in plugin configuration.
	 * 
	 * @return array
	 */
	public function getEnabledFields() {
		if (empty($this->enabled_fields)) {
			foreach($this->profile_fields as $field => $type) {
				$name = "enable_$field";
				
				if ($this->settings->$name) {
					$this->enabled_fields[$field] = $type;
				}
			}
		}
		
		return $this->enabled_fields;
	}
	
	/**
	 * Check if user has added a profile image.
	 * 
	 * @return boolean
	 */
	public function hasImage() {
		return $this->user->icontime !== null;

	}
	
	/**
	 * Check if icon is required field and missing.
	 * 
	 * Returns false if not required or required and also added.
	 * 
	 * @return boolean
	 */
	public function isImageMissing() {
		if ($this->settings->enable_icon) {
			return !$this->hasImage(); 
		} else {
			return false;
		}
	}
	
	/**
	 * Check profile fields and sort them by completeness.
	 */
	private function checkProfileFields () {
		foreach($this->enabled_fields as $field => $type) {
			// Check user icon separately
			if ($field == 'icon') {
				if ($this->hasImage()) {
					$this->filled[] = 'icon';
				} else {
					$this->missing[] = 'icon';
				}
				continue;
			}
			
			// Check normal profile fields
			if (empty($this->user->$field)) {
				$this->missing[] = $field;
			} else {
				$this->filled[] = $field;
			}
		}
	}
	
	/**
	 * Check if user's profile is complete.
	 * 
	 * @return boolean
	 */
	public function isProfileComplete() {
		return (count($this->filled) == $this->total);
	}

	/**
	 * Get percentage of completed information.
	 * 
	 * @return float The percentage
	 */
	public function getPercentage() {
		// Count filled fields
		$filled = count($this->filled);
		
		// Return percentage rounded up to zero decimals
		return number_format($filled / $this->total * 100, 0);
	}
	
	/**
	 * Get amount of profile filling tips to be shown to the user (default 3).
	 * 
	 * @return int $tip_amount
	 */
	public function getTipAmount() {
		$tip_amount = $this->settings->tip_amount;
		$tip_amount = $tip_amount ? $tip_amount : 3;
		
		// Maximum amount of tips is the amount of missing fields
		$tip_amount = $tip_amount <= count($this->missing) ? $tip_amount : count($this->missing);
		
		return $tip_amount;
	}

	/**
	 * Get filled fields.
	 * 
	 * @return array $this->filled
	 */
	public function getFilledFields() {
		return $this->filled;
	}

	/**
	 * Get missing fields.
	 * 
	 * @return array $this->missing
	 */
	public function getMissingFields() {
		return $this->missing;
	}
}
