<?php

function ibt_stacked_taxonomy_block_layout_handler(){
	return array('handler' => 'IbStackedLayoutHandler');
}


class IbStackedLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Title, Description, Images (stacked)'),
			'machine' => 'ibt_stacked',
			'description' => t('Displays this terms title, description, and 2 images below (inline). 2 images max.'),
			'module' => 'img_block_txt',
			'parent plugin' => 'img_block_txt',
			'instances' => TRUE,
			'fields' => TRUE,
			'hide unused fields' => true,
			'stylesheets' => array(
				'less' => array(
					'path' => 'less/',
					'excludes' => array(),
				),
			),
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		$defaults = !empty($defaults['layout_plugin_settings']) ? $defaults['layout_plugin_settings'] : '';
		$settings['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Path'),
			'#default_value' => !empty($defaults['path']) ? $defaults['path'] : '',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		$settings = $settings['img_block_txt']['layout_plugin_settings'];
		$r = new ApiContent();
		$this->r = $r;
		$term = $content->term;
		$html = array();
		$html['w'] = $r->type('container')->_class('ibt-stacked')->r();
		$html['w']['name'] = $r->type('html_tag')->tag('h4')->value($term->name)->_class('ibt-stacked-title')->r();
		$html['w']['desc'] = $r->type('html_tag')->tag('div')->value($term->description)->_class('ibt-stacked-desc')->r();
		
		$this->loadImage($term, $html);
		$content->taxonomy_term = $html;
	}
	
	public function loadImage($term, &$html){
		if(!empty($term->field_link_image['und'])){
			$html['w']['imges'] = $this->r->type('container')->_class('ibt-stacked-images')->r();
			foreach($term->field_link_image['und'] as $delta => $value){
				$img = field_view_value('taxonomy_term', $term, 'field_link_image', $value, 'full', $langcode = NULL);
				$html['w']['imges'][$delta] = $this->r->type('html_tag')->tag('div')->value(render($img))->_class('ibt-stacked-img')->r();
			}
		}
		return $img;
	}
	
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_link_image',
				'label' => 'Image',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
	public function pluginFields($plugin){
		return array(
			'field_link_image' => array(
				'field_name' => 'field_link_image', 
				'type' => 'linkimagefield',
				'module' => 'linkimagefield',
				'cardinality' => 2,
			),
		);
	}
	
}


?>