<?php

function ibt_default_taxonomy_block_layout_handler(){
	return array('handler' => 'IbtDefaultLayoutHandler');
}


class IbtDefaultLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Description, Title, Image'),
			'machine' => 'ibt_default',
			'description' => t('Displays this terms title, image, and description. 1 image max.'),
			'module' => 'img_block_txt',
			'parent plugin' => 'img_block_txt',
			'instances' => TRUE,
			'regions' => TRUE,
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
		$settings['image_position'] = array(
			'#type' => 'select',
			'#options' => array(
				'top' => t('Top'),
				'bottom' => t('Bottom'),
			),
			'#title' => t('Image Position'),
			'#default_value' => !empty($defaults['image_position']) ? $defaults['image_position'] : 'top',
		);
		$settings['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Path'),
			'#default_value' => !empty($defaults['path']) ? $defaults['path'] : '',
		);
		$settings['learn_more'] = array(
			'#type' => 'checkbox',
			'#title' => t('Learn More Button'),
			'#default_value' => !empty($defaults['learn_more']) ? $defaults['learn_more'] : '',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		if(!empty($settings['img_block_txt']['layout_plugin_settings'])){
			$settings = $settings['img_block_txt']['layout_plugin_settings'];
			$r = new ApiContent();
			$img_block_txt = $r->type('container')->_class('img-block-text-single')->weight(1)->r();
			$position = !empty($settings['image_position']) ? $settings['image_position'] : 'top';
			
			if(!empty($content->term->field_link['und'][0]['url'])){
				$path = $content->term->field_link['und'][0]['url'];
			} else {
				$path = $settings['path'];
			}
			
			$img_block_txt['name_c'] = $r->type('container')->_class('img-block-text-title')->weight(0)->r();
			$img_block_txt['name_c']['name_c_inner'] = $r->type('container')->_class('img-block-text-link-container')->weight(0)->r();
			$link = l($content->term->name, $path);
			$img_block_txt['name_c']['name_c_inner']['name'] = $r->type('html_tag')->tag('h4')->value($link)->_class('ibt-title')->weight(0)->r();
			$img_block_txt['desc'] = $r->type('html_tag')->tag('div')->value($content->term->description)->_class('img-block-text-desc')->weight(1)->r();

			if(!empty($content->term->field_image['und'][0])){
				$image = &$content->term->field_image['und'][0];
				$img = field_view_value('taxonomy_term', $content->term, 'field_image', $image, 'full', $langcode = NULL);
				if($position == 'top'){
					$image_weight = -1;
				}
				if($position == 'bottom'){
					$image_weight = 100;
				}
				$img_block_txt['img'] = $r->theme('link')->path($path)->text(render($img))->_options(TRUE, 'img-block-text-img')->weight($image_weight)->r();
				
				if(!empty($settings['learn_more'])){
					$options = array(
						'attributes' => array(
							'class' => array('btn btn-primary'),
						),
					);
					$lm = l("Learn More", $path, $options);
					$img_block_txt['learn_more'] = $r->type('html_tag')->tag('div')->value($lm)->_class('ibt-lm')->weight(2)->r();
				}
			}
			
			$content->taxonomy_term = $img_block_txt;
		} else {
			
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_link',
				'label' => 'Link',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
			array(
				'field_name' => 'field_image',
				'label' => 'Image',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
	public function pluginFields($plugin){
		return array(
			'field_link' => array(
				'field_name' => 'field_link', 
				'type' => 'link_field',
				'cardinality' => 1,
			),
		);
	}
	
}


?>