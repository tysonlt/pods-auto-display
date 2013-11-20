<?php

/**
 * Automatically displays Pods custom fields below custom post content.
 * 
 * @author Tyson
 */
class PodsAutoDisplay {
	
	const DEFAULT_CONTENT_FILTER_PRIORITY = 2000; 
	const POD_TEMPLATE_FILTER_PREFIX = 'pods_auto_display_template_name_';
	
	const DEFAULT_FIELD_SEPARATOR = ': ';
	
	/**
	 * Show fields at the bottom of the content.
	 *
	 * Looks for a Pods template called <post_type>_detail.
	 * If that is not found, just prints a <ul> of field values.
	 *
	 * @param string $content
	 */
	public function handleFilter($content) {
	
		global $post;
		
		//always show main content
		$result = $content;

		//is this is our handle list?
		if (PodsAutoDisplaySettings::isPostTypeEnabled($post->post_type)) {

			//check for a pod with more yummy fields to show
			if ($this->isPod($post->post_type)) {
		
				//load the pod
				$pod = $this->loadPod($post);
				
				//guess an appropriate template name for this pod
				$template_name = $this->buildTemplateName($post);
			
				//ok, let's look for a template
				$template = $this->loadPodTemplate($pod, $template_name);
				if (false !== $template) {
		
					//OPTION 1: is there a template? ///
		
					//we have a template, so let's use that
					$result .= $pod->template($template_name);
		 
				} else {
		
					//OPTION 2: enumerate fields ///
							
					//was a list of fields set?
					$enabled_fields = PodsAutoDisplaySettings::getEnabledFields($post->post_type);
					if (is_array($enabled_fields) && !empty($enabled_fields)) {
						
						//open ul
						$result .= apply_filters('pods_auto_display_open_ul', '<ul>', array('pod'=>$pod));
						
						foreach ($enabled_fields as $field_enabled => $field_name) {
							
							//get the data 
							$field = $pod->fields[$field_name];
							$raw = $pod->field($field['name']);
							$display = $pod->display($field['name']);
							
							//build some args to pass to filters
							$args = array('pod'=>$pod, 'field'=>$field, 'raw'=>$raw, 'display'=>$display);
	
							//is it set?
							if (false == $this->isBlank($raw)) {
								
								//apply filters to the field value
								$display = apply_filters('pods_auto_display_format_field_type_'. $field['type'], $display, $args);
								
								//output list
								$result .= apply_filters('pods_auto_display_open_li', '<li>', $args);
								$result .= apply_filters('pods_auto_display_field_title', '<b>'. $field['label'] .'</b>', $args);
								$result .= apply_filters('pods_auto_display_field_separator', self::DEFAULT_FIELD_SEPARATOR, $args);
								$result .= apply_filters('pods_auto_display_field_value', $display, $args);
								$result .= apply_filters('pods_auto_display_close_li', '</li>', $args);
								
							} //end if: not blank
	
						} //end foreach: enabled fields
					
						//close ul
						$result .= apply_filters('pods_auto_display_close_ul', '</ul>', array('pod'=>$pod));
						
					} //end if: found any fields
		
				} //end if: did we find a template?
		
			} //end if: is this a pod?
			
		} //end if: is this post type enabled for auto-display? 

		//return the freshly-baked result
		return $result;
	
	}
	
	/**
	 * Advanced check for blank POD values.
	 * 
	 * @param mixed $value
	 */
	private function isBlank($value) {
		
		if (null === $value || false === $value) {
			return true;
		} else if (is_array($value)) {
			return empty($value);
		} else {
			return strlen(trim($value)) == 0;
		}
		
	}
	
	/**
	 * Get the filter priority to set.
	 * @return int Filter priority.
	 */
	public function getFilterPriority() {
		return self::DEFAULT_CONTENT_FILTER_PRIORITY;
	}

	/**
	 * Whether this post type is a pod.
	 * 
	 * @param string $post_type
	 */
	public function isPod($post_type) {
		return pods_api()->pod_exists($post_type);
	}
	
	/**
	 * 
	 * @param unknown_type $post
	 */
	private function loadPod($post) {
		$params = array('where' => 't.id = "'.$post->ID.'"');
		return pods($post->post_type)->find($params);
	}
	
	/**
	 * 
	 * @param unknown_type $template_name
	 */
	private function loadPodTemplate($pod, $template_name) {
		$result = false;
		if (strlen(trim($template_name)) > 0) {
			$result = $pod->api->load_template(array('name' => $template_name));
		}
		return $result;
	}
	
	/**
	 * Build/guess a template name.
	 *
	 * Uses filter: pods_auto_display_template_name_(post_type)
	 *
	 * @param WP_Post $post
	 * @return string
	 */
	private function buildTemplateName($post, $allow_default = true) {
		$template_name = PodsAutoDisplaySettings::getTemplateName($post->post_type);
		if (strlen(trim($template_name)) < 1 && $allow_default) {
			$template_name = PodsAutoDisplaySettings::buildDefaultTemplateName($post->post_type);
		}
		return apply_filters(self::POD_TEMPLATE_FILTER_PREFIX . $post->post_type, $template_name);
	}

}