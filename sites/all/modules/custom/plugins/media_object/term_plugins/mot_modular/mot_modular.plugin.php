<?php

function mot_modular_taxonomy_block_layout_handler(){
	return array('handler' => 'MotModularPluginhandler');
}


class MotModularPluginhandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Media Object: Modular'),
			'machine' => 'mot_modular',
			'description' => t('Modular Media Object'),
			'module' => 'media_object',
			'parent plugin' => 'media_object',
			'instances' => true,
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
		$settings['custom_header'] = array(
			'#type' => 'textfield',
			'#title' => t('Header'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['custom_header']) ? $defaults['layout_plugin_settings']['custom_header'] : '',
		);
		$settings['link'] = array(
			'#type' => 'textfield',
			'#title' => t('link'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['link']) ? $defaults['layout_plugin_settings']['link'] : '',
		);
		$settings['background'] = array(
			'#type' => 'checkbox',
			'#title' => t('Use Image As Background'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['background']) ? $defaults['layout_plugin_settings']['background'] : '',
		);
		$settings['background_args'] = array(
			'#type' => 'textfield',
			'#title' => t('Background Image Porperties'),
			'#description' => 'ex: no-repeat center',
			'#default_value' => !empty($defaults['layout_plugin_settings']['background_args']) ? $defaults['layout_plugin_settings']['background_args'] : '',
		);
		$settings['direction'] = array(
			'#type' => 'select',
			'#title' => t('Direction'),
			'#options' => array(
				'left' => 'Left',
				'right' => 'right',
			),
			'#default_value' => !empty($defaults['layout_plugin_settings']['direction']) ? $defaults['layout_plugin_settings']['direction'] : '',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		$term = $content->term;
		$r = new ApiContent();
		$settings = $settings['media_object']['layout_plugin_settings'];
		$wrapper = array();
		$head = l($term->name, $settings['link']);
		
		$wrapper['head'] = $r->type('container')->_class('mot-modular-head')->r();
		$design = '<div class="space"><span class="spacer_crosshair"><i class="fa fa-retweet"><!-- icon --></i></span></div>';
		$wrapper['head']['head'] =  $r->type('html_tag')->tag('h2')->_class('sub-title')->value($term->name)->r();
		$wrapper['head']['space'] =  $r->type('html_tag')->tag('h2')->_class('title-design')->value($design)->r();
		
		$wrapper['wrapper'] = $r->type('container')->_class('mot-modular-item media')->r();
		
		$direction = $settings['direction'];
		$wrapper['wrapper']['direction'] = $r->type('container')->_class('pull-' . $direction)->r();
		if(!$settings['background']){
			$image = $this->loadImage($term);
			$wrapper['wrapper']['direction']['image'] = $r->type('html_tag')->tag('div')->value(render($image))->_class('media-image')->r();
		} else {
			$wrapper['wrapper']['direction']['image'] = $r->type('html_tag')->tag('div')->value('')->_class('media-image')->r();
			$uri = file_create_url($term->field_mot_media_img_link['und'][0]['uri']);
			$args = 
			$wrapper['wrapper']['#attributes']['style'] = array('background:url('. $uri .') '. $settings['background_args'] .';');
		}
		// body
		$wrapper['wrapper']['body'] = $r->type('container')->_class('media-body')->r();
		$links = $this->loadLinks($term);

		//$wrapper['wrapper']['body']['head'] =  $r->type('html_tag')->tag('h2')->_class('mot-modular-title')->value($head)->r();
		$sub_head = !empty($term->field_mot_sub_header['und'][0]['value']) ? $term->field_mot_sub_header['und'][0]['value'] : '';
		$wrapper['wrapper']['body']['sub_header'] =  $r->type('html_tag')->tag('div')->_class('mot-modular-sub-head')->value($sub_head)->r();
		$wrapper['wrapper']['body']['description'] =  $r->type('html_tag')->tag('div')->_class('mot-modular-description')->value($term->description)->r();
		
		$wrapper['wrapper']['body']['links'] = $r->type('container')->_class('mot-modular-links')->r();
		$wrapper['wrapper']['body']['links']['links'] = $r->type('html_tag')->tag('div')->_class('btn-group')->value($links)->r();
		$content->taxonomy_term = $wrapper;
	}
	
	public function loadLinks($term){
		$r = new ApiContent();
		$items = '';
		if(isset($term->field_mot_links_inline['und'])){
			foreach($term->field_mot_links_inline['und'] as $delta => $value){
				$options = array(
					'attributes' => array(
						'class' => array('btn btn-primary'),
					),
				);
				$items .= l($value['title'], $value['url'], $options);
			}
			return $items;
		}
	}
	
	private function loadImage($term){
		if(!empty($term->field_mot_media_img_link['und'][0])){
			$image = &$term->field_mot_media_img_link['und'][0];
			return field_view_value('taxonomy_term', $term, 'field_mot_media_img_link', $image, 'full', $langcode = NULL);
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_mot_links_inline',
				'label' => 'Links',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
			array(
				'field_name' => 'field_mot_media_img_link',
				'label' => 'Image',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
			array(
				'field_name' => 'field_mot_sub_header',
				'label' => 'Sub Header',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
	public function pluginFields($plugin){
		return array(
			'field_mot_links_inline' => array(
				'field_name' => 'field_mot_links_inline', 
				'type' => 'link_field',
				'module' => 'link_field',
				'cardinality' => 0,
			),
			'field_mot_sub_header' => array(
				'field_name' => 'field_mot_sub_header', 
				'type' => 'text_long',
				'cardinality' => 0,
			),
			'field_mot_media_img_link' => array(
				'field_name' => 'field_mot_media_img_link', 
				'type' => 'linkimagefield',
				'module' => 'linkimagefield',
				'cardinality' => 1,
			),
		);
	}

}


?>