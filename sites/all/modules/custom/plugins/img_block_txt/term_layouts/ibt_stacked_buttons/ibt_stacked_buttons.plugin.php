<?php

function ibt_stacked_buttons_taxonomy_block_layout_handler(){
	return array('handler' => 'IbStackedButtonsLayoutHandler');
}


class IbStackedButtonsLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Title, Description, Links (stacked)'),
			'machine' => 'ibt_stacked_buttons',
			'description' => t('Displays this terms title, description, and 2 links below (inline). 2 links max.'),
			'module' => 'img_block_txt',
			'parent plugin' => 'img_block_txt',
			'instances' => TRUE,
			'fields' => true,
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
		
		$this->loadLinks($term, $html);
		$content->taxonomy_term = $html;
	}
	
	public function loadLinks($term, &$html){
		if(!empty($term->field_ibt_stacked_links['und'])){
			$html['w']['links'] = $this->r->type('container')->_class('ibt-stacked-links')->r();
			$link = field_view_field('taxonomy_term', $term, 'field_ibt_stacked_links', $term->field_ibt_stacked_links, 'default', $langcode = NULL);
			$link['#label_display'] = 'hidden';
			$html['w']['links'][] = $this->r->type('markup')->markup(render($link))->r();
		}
	}
	
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_ibt_stacked_links',
				'label' => 'Links',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
	public function pluginFields($plugin){
		return array(
			'field_ibt_stacked_links' => array(
				'field_name' => 'field_ibt_stacked_links', 
				'type' => 'link_field',
				'module' => 'link_field',
				'cardinality' => 2,
			),
		);
	}
	
}


?>