<?php

function tb_node_refrence_taxonomy_block_layout_handler(){
	return array('handler' => 'TbNodeRefrenceLayoutHandler');
}


class TbNodeRefrenceLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Node Refrence'),
			'machine' => 'tb_node_refrence',
			'description' => t('Allows you to reference nodes. Configure field displays in the default view mode for this vocab.'),
			'module' => 'tb_node_refrence',
			'parent plugin' => 'tb_refrence',
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
		$settings['layout'] = array(
			'#type' => 'select',
			'#title' => t('Layout'),
			'#options' => array(
				'stacked' => 'Stacked',
			),
			'#default_value' => !empty($defaults['layout']) ? $defaults['layout'] : 'stacked',
		);
	}
	

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->term)){
			$term = $content->term;
			$r = new ApiContent();
			$html = array();
			$layout = $settings['tb_refrence']['layout_plugin_settings']['layout'];
			
			switch($layout){
				case 'stacked':
					// main wrapper
						$html['w'] = $r->type('container')->_class('node-refrence-stacked')->r();
						$html['w']['title'] = $r->type('html_tag')->tag('h4')->value($term->name)->_class('container-title')->r();
						$html['w']['desc'] = $r->type('html_tag')->tag('div')->value($term->description)->_class('container-desc')->r();
						if(!empty($term->field_tb_node_refrence['und'])){
						foreach($term->field_tb_node_refrence['und'] as $delta => $product){
							$field = field_view_value('taxonomy_term', $term, 'field_tb_node_refrence', $product, 'default', $langcode = NULL);
							$html['w'][$delta] = $r->type('container')->_class('refrenced-node')->r();
							$html['w'][$delta]['product'] = $r->type('html_tag')->tag('div')->value(render($field))->_class('refrenced-product')->r();
						}
					}
					if(!empty($term->field_tb_refrence_btn['und'][0])){
						$btn = field_view_value('taxonomy_term', $term, 'field_tb_refrence_btn', $term->field_tb_refrence_btn['und'][0], 'default', $langcode = NULL);
						$html['w']['footer'] = $r->type('container')->_class('node-refrence-footer')->r();
						$html['w']['footer']['btn'] = $r->type('html_tag')->tag('div')->value(render($btn))->_class('footer-btn')->r();
					}
					
					break;
			}
			
			$content->html = $html;
		}
	}
	
	
	public function pluginFieldInstances($plugin, $bundle){
		$instances = array(
			array(
				'field_name' => 'field_tb_node_refrence',
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
		
		if(!$instance = field_info_instance('taxonomy_term', 'field_tb_refrence_btn', $bundle)){
				$instances[] = array(
					  'field_name' => 'field_tb_refrence_btn',
					  'label' => 'Button / Link',
					  'entity_type' => 'taxonomy_term',
					  'bundle' => $bundle,
				);
		}
		return $instances;
	}
	
	public function pluginFields($plugin, $settings){
		
		$fields = array(
			'field_tb_node_refrence' => array(
				'field_name' => 'field_tb_node_refrence', 
				'type' => 'entityreference',
				'module' => 'entityreference',
				'cardinality' => FIELD_CARDINALITY_UNLIMITED,
				'settings' => array(
		      'target_type' => 'node',
		      'handler_settings' => array('target_bundles' => array()),
				),
			),
		);
		if(!empty($settings['tb_refrence']['layout_plugin_settings']['layout']) && $settings['tb_refrence']['layout_plugin_settings']['layout'] == 'stacked'){
			$fields['field_tb_refrence_btn'] = array(
				'field_name' => 'field_tb_refrence_btn', 
				'type' => 'link_field',
				'module' => 'link_field',
				'cardinality' => 1,
			);
		}
		return $fields;
	}
	
}


?>