<?php


function bootstrap_tabs_refrence_taxonomy_block_layout_handler(){
	return array('handler' => 'BootstrapTabsRefrenceLayoutHandler');
}


class BootstrapTabsRefrenceLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Bootstrap Tabs Entity Refrence'),
			'machine' => 'bootstrap_tabs_refrence',
			'description' => t('Allows you to reference nodes. Configure field displays in the default view mode for this vocab.'),
			'module' => 'bootstrap_tabs',
			'parent plugin' => 'bootstrap_tabs',
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
		
	}
	

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->term)){
			$term = $content->term;
			$r = new ApiContent();
			$html = array();
			// main wrapper
	  	$html[$term->tid] = $r->type('container')->_class('bootstrap-tab-refrence')->r();
	  	if(!empty($term->field_bootstrap_tab_refrence['und'])){
		  	foreach($term->field_bootstrap_tab_refrence['und'] as $delta => $refrence){
			  	$field = field_view_value('taxonomy_term', $term, 'field_bootstrap_tab_refrence', $refrence, 'default', $langcode = NULL);
					$html[$term->tid][$delta] = $r->type('html_tag')->tag('div')->value(render($field))->_class('bootstrap-tab-item')->r();
		  	}
	  	}
			$content->html = $html;
		}
	}
	
	
	public function pluginFieldInstances($plugin, $bundle){
		$instances = array(
			array(
				'field_name' => 'field_bootstrap_tab_refrence',
				'label' => 'Node Refrence',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
				'widget' => array(
					'type' => 'entityreference_autocomplete_tags',
				),
				'settings' => array(
		      'target_type' => 'node',
		      'handler_settings' => array('target_bundles' => array()),
		    ),
			),
		);
		
		return $instances;
	}
	
	public function pluginFields($plugin, $settings){
	
		$fields = array(
			'field_bootstrap_tab_refrence' => array(
				'field_name' => 'field_bootstrap_tab_refrence', 
				'type' => 'entityreference',
				'module' => 'entityreference',
				'cardinality' => FIELD_CARDINALITY_UNLIMITED,
				'settings' => array(
		      'target_type' => 'node',
		      'handler_settings' => array('target_bundles' => array()),
				),
			),
		);
		
		return $fields;
	}
	
}


?>