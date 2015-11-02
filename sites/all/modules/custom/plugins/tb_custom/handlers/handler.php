<?php

class TbCustomTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		switch($plugin){
		case 'custom':
			$settings['token_value_type'] = array(
				'#type' => 'select',
				'#title' => t('Token Value Type'),
				'#default_value' => !empty($defaults['token_value_type']) ? $defaults['token_value_type'] : array(),
				'#options' => array(
					'default' => 'default',
					'raw' => 'raw values',
				),
				'#states' => array(
					'visible' => array(
						':input[name="bt_taxonomy_block[taxonomy_block_view_mode]"]' => array('value' => 'custom'),
					),
				),
			);
			if (module_exists('token')) {
				$settings['token_tree'] = array(
					'#theme' => 'token_tree',
					'#token_types' => array(),
				);
			}
			break;
			case 'default':
				$settings['token_value_type'] = array(
					'#type' => 'markup',
					'#markup' => 'Default View Mode (Edit display settings is the "Manage Display Link")',
				);
				break;
		}

	}

	public function displayOutput(&$content, $settings = array()){
		switch($content->view_mode){
		case 'custom':
			$content->custom_display_settings = !empty($content->term->taxonomy_block_display_settings['und'][0]['value'])
				? $content->term->taxonomy_block_display_settings['und'][0]['value']
				: NULL;

			$default_fields = array('name' => $content->term->name);
			$content->settings->token_value_type = !empty($content->settings->token_value_type)
				? $content->settings->token_value_type
				: 'default';
			switch($settings['custom']['token_value_type']){
			case 'raw':
				foreach($content->term as $key => $value){
					if(is_array($value) && $key != 'rdf_mapping' && $key != 'taxonomy_block_display_settings' || $key == 'name' || $key == 'description'){
						$content->field_values[$key] = $value;
						if($instance = field_info_instance('taxonomy_term', $key, $content->vocabulary_machine_name)){
							$content->field_values[$key]['field_type'] = $instance['widget']['module'];
						}
					}
				}
				break;
			case 'default':
				$view_term = taxonomy_term_view($content->term, 'default', $langcode = NULL);
				$content->field_values = array_intersect_key($view_term, array_flip(element_children($view_term)));
				$content->field_values += $default_fields;
				break;
			}
			
			
			$custom_display_settings = token_replace($content->custom_display_settings, $content->field_values);
			$html = array();
			$html['wrapper'] = BtApi::Container(array('tb-custom-display'));
			$html['wrapper']['data'] = BtApi::htmlTag($custom_display_settings, 'div');
			$content->taxonomy_term = $html;
			
			break;
		case 'default':
			$view_term = taxonomy_term_view($content->term, 'default', $langcode = NULL);
			//dpm($view_term);
			//dpm(theme('theme_bt_taxonomy_block', array('taxonomy_term' => (array)$content)));
			$content->taxonomy_term = $view_term;
			break;
		}

	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}
}
	
	
	class TbCustomVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){

	}

	public function displayOutput(&$content, $settings = array()){
		$html = array();
		switch($content->view_mode){
		case 'default':		
			if(!empty($content->terms)){
				foreach($content->terms as $tid => $data){
					$term = $data['term_display'];
					$html[] = $term;
				}
			}
			break;
		}
		$content->taxonomy_term = $html;
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}


}

?>