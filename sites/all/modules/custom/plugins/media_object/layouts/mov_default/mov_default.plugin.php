<?php

function mov_default_taxonomy_block_layout_handler(){
	return array('handler' => 'MediaObjectDefaultPluginHandler');
}


class MediaObjectDefaultPluginHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Default Plugin'),
			'machine' => 'mov_default',
			'description' => t('Default'),
			'load term plugins' => TRUE, // loads the terms in there respective plugin view mode if they have one chosen
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

	}
	
	
	public function displayOutput(&$content, $settings = array()){
		$r = new ApiContent();
		$wrapper = array();
		$wrapper['container'] = $r->type('container')->_class('mot-modular-container')->r();
		foreach($content->terms as $type => $value){
			$wrapper['container'][] = $r->type('html_tag')->tag('div')->value(render($value['term_display']))->r();
		}
		$content->taxonomy_term = $wrapper;
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
	
	public function pluginFieldInstances($plugin, $bundle){
		
	}

}


?>