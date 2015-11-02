<?php

function mot_icon_taxonomy_block_layout_handler(){
	return array('handler' => 'MotIconPluginManager');
}


class MotIconPluginManager extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Media Object Icon'),
			'machine' => 'mot_icon',
			'description' => t(''),
			'module' => 'media_object',
			'parent plugin' => 'media_object',
			'stylesheets' => array(
				'less' => array(
					'path' => 'less/',
					'excludes' => array(),
				),
			),
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		$settings['header'] = array(
			'#type' => 'textfield',
			'#title' => t('Header'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['header']) ? $defaults['layout_plugin_settings']['header'] : '',
		);
		$settings['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Path'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['path']) ? $defaults['layout_plugin_settings']['path'] : '',
		);
		$settings['direction'] = array(
			'#type' => 'select',
			'#options' => array(
				'left' => t('Left'),
				'right' => t('Right'),
			),
			'#title' => t('Direction'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['direction']) ? $defaults['layout_plugin_settings']['direction'] : 'left',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		dpm($content);
	}

	
	
	private function loadImage($term){
		if(!empty($term->field_image['und'][0])){
			$image = &$term->field_image['und'][0];
			$options = array(
				'html' => TRUE,
			);
			$link = l($term->name, '');
			$img = field_view_value('taxonomy_term', $term, 'field_image', $image, 'full', $langcode = NULL);
			return l(render($img), '', $options);
		}
	}

}


?>