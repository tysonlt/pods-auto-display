<?php

class PodsAutoDisplaySettings {
	
	const ROOT_OPTION_NAME = 'PodsAutoDisplayAdmin';
	const GENERAL_SETTINGS_PAGE = 'general_settings_page';
	const GENERAL_SETTINGS_SECTION = 'general_settings_section';

	const SETTING_ENABLE_POD_PREFIX = 'enable_pod_';
	const SETTING_TEMPLATE_POD_PREFIX = 'pod_template_';
	const SETTING_ENABLE_TEMPLATE_PREFIX = 'enable_template_';
	const SETTING_ENABLE_FIELDS_PREFIX = 'enable_fields_';
	
	const SECTION_PREFIX = 'section_';
	
	const DEFAULT_TEMPLATE_SUFFIX = '_detail';
	
	/**
	 * Get an option from the options array.
	 *
	 * @param string $key
	 * @param string $root_key
	 * @param string $section
	 */
	public static function getOption($key, $section = self::GENERAL_SETTINGS_SECTION, $page = self::GENERAL_SETTINGS_PAGE, $root_key = self::ROOT_OPTION_NAME) {
		$data = get_option($root_key);
		$option = $data[$page][$section][$key];
		return $option;
	}
	
	/**
	 * Whether this post type was enabled in settings.
	 *
	 * @param string $post_type
	 */
	public static function isPostTypeEnabled($post_type) {
		return self::getOption(self::buildPodEnabledSettingName($post_type), self::buildSectionName($post_type));
	}
	
	/**
	 * Get list of fields enabled for this pod.
	 * 
	 * @param string $post_type
	 * @return array
	 */
	public static function getEnabledFields($post_type) {
		$result = array();
		$option = self::getOption(self::buildFieldEnabledSettingName($post_type), self::buildSectionName($post_type));
		if (is_array($option)) {
			foreach ($option as $key => $value) {
				if ($value) {
					$result[] = $key;
				}
			}
		}
		return $result;
	}
	
	/**
	 * 
	 * @param string $post_type
	 */
	public static function getTemplateName($post_type) {
		return self::getOption(self::buildPodTemplateSettingName($post_type), self::buildSectionName($post_type));
	}
	
	/**
	 * 
	 * @param unknown_type $pod_name
	 * @return string
	 */
	public static function buildPodEnabledSettingName($pod_name) {
		return self::SETTING_ENABLE_POD_PREFIX . $pod_name;
	}
	
	/**
	 * 
	 * @param unknown_type $pod_name
	 * @return string
	 */
	public static function buildEnableTemplateSettingName($pod_name) {
		return self::SETTING_ENABLE_TEMPLATE_PREFIX . $pod_name;
	}
	
	/**
	 * 
	 * @param unknown_type $pod_name
	 * @return string
	 */
	public static function buildPodTemplateSettingName($pod_name) {
		return self::SETTING_TEMPLATE_POD_PREFIX . $pod_name;
	}
	
	/**
	 * 
	 * @param unknown_type $pod_name
	 * @return string
	 */
	public static function buildFieldEnabledSettingName($pod_name) {
		return self::SETTING_ENABLE_FIELDS_PREFIX . $pod_name;
	}

	/**
	 * 
	 * @param unknown_type $pod_name
	 * @return string
	 */
	public static function buildSectionName($pod_name) {
		return self::SECTION_PREFIX . $pod_name;
	}
	
	public static function buildDefaultTemplateName($pod_name) {
		return $pod_name . self::DEFAULT_TEMPLATE_SUFFIX;
	}
	
}