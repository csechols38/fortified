<?php

function image_list_taxonomy_block_layout_handler(){
	return array('handler' => 'ImageListPluginHandler');
}


class ImageListPluginHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Image With List'),
			'machine' => 'mo_image_list',
			'description' => t('Image With List'),
			'load term plugins' => TRUE, // loads the terms in there respective plugin view mode if they have one chosen
			'instances' => TRUE,
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
		if(!empty($content->terms)){
			$this->r = new ApiContent();
			$this->html['w'] = $this->r->type('container')->_class('mot-vocab-list-wrapper')->r();
			foreach($content->terms as $type => $data){
				$display = $data['term_display'];
				$this->html['w'][] = $this->r->type('html_tag')->tag('div')->value(render($display))->_class('list-item-wrap')->r();
			}
			$content->taxonomy_term = $this->html;
		}
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
		$field = array(
			'field_name' => 'field_mo_list', 
			'type' => 'text',
			'cardinality' => -1,
		);
		if(!field_info_field('field_mo_list')){
			field_create_field($field);
		}
		return array(
			array(
				'field_name' => 'field_mo_list',
				'label' => 'Media Object List',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
				'cardinality' => -1,
			),
		);
	}

}


?>