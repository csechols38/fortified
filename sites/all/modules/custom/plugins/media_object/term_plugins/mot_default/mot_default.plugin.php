<?php

function mot_default_taxonomy_block_layout_handler(){
	return array('handler' => 'MotDefaultPluginHandler');
}


class MotDefaultPluginHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Default Media Object'),
			'machine' => 'mot_default',
			'description' => t('Default Media Object'),
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
		$content->taxonomy_term = build_media_object_display($content->term, $settings['media_object']['layout_plugin_settings']);
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