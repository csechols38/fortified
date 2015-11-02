<?php

function tb_product_refrence_taxonomy_block_layout_handler(){
	return array('handler' => 'TbProductRefrenceLayoutHandler');
}


class TbProductRefrenceLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'tier' => 1,
			'name' => t('Product Refrence'),
			'machine' => 'tb_product_refrence',
			'description' => t('Reference Products'),
			'module' => 'tb_refrence',
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
	
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->term)){
			$term = $content->term;
			$r = new ApiContent();
			$html = array();
			// main wrapper
			$html['w'] = $r->type('container')->_class('product-refrence')->r();
			$html['w']['title'] = $r->type('html_tag')->tag('h4')->value($term->name)->_class('container-title')->r();
			$html['w']['desc'] = $r->type('html_tag')->tag('div')->value($term->description)->_class('container-desc')->r();
			
			if(!empty($term->field_tb_product_refrence['und'])){
				foreach($term->field_tb_product_refrence['und'] as $delta => $product){
					
					$field = field_view_value('taxonomy_term', $term, 'field_tb_product_refrence', $product, 'default', $langcode = NULL);
					$html['w'][$delta] = $r->type('container')->_class('refrenced-product')->r();
					$html['w'][$delta]['product'] = $r->type('html_tag')->tag('div')->value(render($field))->_class('refrenced-product')->r();
				}
			}
			$content->html = $html;
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_tb_product_refrence',
				'label' => 'Product Refrence',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
				'widget' => array(
					'type' => 'entityreference_autocomplete_tags',
				),
				'settings' => array(
		      'target_type' => 'commerce_product',
		      'handler_settings' => array('target_bundles' => array('product')),
		    ),
			),
		);
	}
	
	public function pluginFields($plugin){
		return array(
			'field_tb_product_refrence' => array(
				'field_name' => 'field_tb_product_refrence', 
				'type' => 'entityreference',
				'module' => 'entityreference',
				'cardinality' => FIELD_CARDINALITY_UNLIMITED,
				'settings' => array(
		      'target_type' => 'commerce_product',
		      'handler_settings' => array('target_bundles' => array('product')),
				),
			),
		);
	}
	

}




?>