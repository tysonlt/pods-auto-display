<?php        

if (!class_exists('AdminPageFramework')) {
	include_once(dirname(__FILE__) . '/class/admin-page-framework.php');
}

/**
 * Admin screen based on Michael Uno's delicious plugin.
 *  
 * @author Tyson
 */
class PodsAutoDisplayAdmin extends AdminPageFramework {
	
	const ROOT_MENU = 'Settings';
	const ROOT_SUB_MENU = 'Pods Auto-Display';
	
	/**
	 * (non-PHPdoc)
	 * @see AdminPageFramework::setUp()
	 */
	function setUp() {
		
		if (!function_exists('pods_api')) {
			trigger_error('The Pods Framework plugin must be active.', E_USER_ERROR);
		}
		
		$this->setRootMenuPage(self::ROOT_MENU); 
		$this->showPageHeadingTabs(false);
		$this->addSubMenuPage(self::ROOT_SUB_MENU, PodsAutoDisplaySettings::GENERAL_SETTINGS_PAGE);
		
		foreach ($this->getPodList() as $pod_name => $pod_label) {
			
			$section = 'section_'. $pod_name;
			
			$this->addSettingSection(
				array(
					'strSectionID' => $section,
					'strPageSlug'  => PodsAutoDisplaySettings::GENERAL_SETTINGS_PAGE,
					'strTitle'     => $pod_label,
				)
			);
			
			$this->addSettingField(
				array(
						'strFieldID' => PodsAutoDisplaySettings::buildPodEnabledSettingName($pod_name),
						'strSectionID' => $section,
						'strTitle' => 'Enable auto-display:',
						'strType' => 'checkbox',
				)
			);
			
			/*
			$this->addSettingField(
				array(
						'strFieldID' => PodsAutoDisplaySettings::buildEnableTemplateSettingName($pod_name),
						'strSectionID' => $section,
						'strTitle' => 'Look for Pod template:',
						'strType' => 'checkbox',
				)
			);
			*/
			
			$this->addSettingField(
				array(
						'strFieldID' => PodsAutoDisplaySettings::buildPodTemplateSettingName($pod_name),
						'strSectionID' => $section,
						'strTitle' => 'Pod template name:',
						'strDescription' => "Leave blank if not using a template. (Defaults to '{$pod_name}_detail')",
						'strType' => 'text',
						'vDefault' => $pod_name .'_detail'
				)
			);
			
			//add fields
			$pod_fields = $this->getPodFields($pod_name);
			if (!empty($pod_fields)) {
				$this->addSettingField(
					array(
							'strFieldID' => PodsAutoDisplaySettings::buildFieldEnabledSettingName($pod_name),
							'strSectionID' => $section,
							'strTitle' => 'Enable fields:',
							'strDescription' => "If a template is not found, the plugin will print out these fields.",
							'strType' => 'checkbox',
							'vLabel' => $pod_fields
					)
				);
			}
			
		}
		
	}
	
	/**
	 * 
	 * @return multitype:unknown
	 */
	function getPodList() {
		
		$result = array();
		$params = array("type" => "post_type"); 
		 
		foreach (pods_api()->load_pods($params) as $pod) {
			$result[$pod['name']] = $pod['label'];
		}
		return $result;
	}
	
	/**
	 * 
	 * @param unknown_type $pod_label
	 * @return multitype:
	 */
	function getPodFields($pod_label) {
		
		$result = array();
		
		foreach(pods($pod_label)->fields() as $field) {
			$result[$field['name']] = $field['label']; 
		}
		
		return $result;
		
	}
	
	/**
	 * 
	 */
	function do_general_settings_page() {  
		submit_button();
	}
	
} //end class
