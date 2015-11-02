<?php

function ibt_featured_taxonomy_block_layout_handler(){
	return array('handler' => 'IbtFeaturedLayoutHandler');
}


class IbtFeaturedLayoutHandler extends BtTermsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Image Block Text Featured'),
			'machine' => 'ibt_featured',
			'description' => t('Image Block Text Featured'),
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
		$defaults = !empty($defaults['layout_plugin_settings']) ? $defaults['layout_plugin_settings'] : array();
		$settings['img_position'] = array(
			'#type' => 'select',
			'#title' => t('Image Position'),
			'#default_value' => !empty($defaults['img_position']) ? $defaults['img_position'] : 'right',
			'#options' => array(
				'top' => 'Top',
				'left' => 'Left',
				'right' => 'Right',
				'below' => 'Below',
			),
		);
		$settings['link'] = array(
			'#type' => 'textfield',
			'#title' => t('Link Path'),
			'#description' => 'Leave blank to not show a link. Type @default to use this terms default path.',
			'#default_value' => !empty($defaults['link']) ? $defaults['link'] : '',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		$this->plugin = $content;
		$this->api = new APIContent();
		$this->content = BtApi::Container(array('img-block-text-featured'), null, 0);
		$settings = $settings['img_block_txt']['layout_plugin_settings'];
		$this->settins = $settings;
		$this->term = $this->plugin->term;
		
		$this->procesSettings()
			->processImage()
			->processLink();
			
		$content->html = $this->content;
	}
	
	private function procesSettings(){
		$this->processLink();
		return $this;
	}
	
	private function processImage(){
		
		$position = !empty($this->settins['img_position']) ? $this->settins['img_position'] : 'right';
		if(!empty($this->term->field_image['und'][0])){
			$image = $this->loadImage($this->term->field_image['und'][0]);
			$image = $this->api->type('html_tag')
				->tag('div')
				->value(render($image))
				->_class($this->imgClass($position))
				->weight(100)
				->r();
			$this->content['image'] = $image;
		}
		return $this;
	}
	
	private function imgClass($position){
		$positions = array(
			'top' => 'ibt-featured-img-top',
			'left' => 'ibt-featured-img-left',
			'right' => 'ibt-featured-img-right',
			'below' => 'ibt-featured-img-below',
		);
		return $positions[$position];
	}
	
	private function processLink(){
		if(empty($this->settins['link'])){
		
		} else if($this->settins['link'] == '@default'){
			$link = l($this->term->name, '/taxonomy/term/'. $this->term->tid);
		} else {
			$link = l($this->term->name, $this->settins['link']);
		}
		if($link){
			$this->content['link'] = $this->api->type('html_tag')->tag('div')->value($link)->_class('ibt-featured-link')->weight(100)->r();
		}
		return $this;
	}
	
	private function loadImage($image){
			$options = array(
				'html' => TRUE,
			);
			$img = field_view_value('taxonomy_term', $this->term, 'field_image', $image, 'full', $langcode = NULL);
			return $img;
	}
	
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_image',
				'label' => 'Image',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
	public function pluginRegions(){
		return array(
			'media_object' => array(
				'name' => 'Media Object Style',
				'render' => $this->renderRegion('media_object'),
				'regions' => array(
					'left' => array(
						'name' => 'Left Image',
						'class' => 'ibt-featured-image-left',
					),
					'right' => array(
						'name' => 'Text Right',
						'class' => 'ibt-featured-text-right',
					),
					'link' => array(
						'name' => 'Link',
						'class' => 'ibt-featured-link-bottom',
					),
				),
			),
		);
	}
	
	private function renderRegion($type){
		switch($type){
			case 'media_object':
				$content = array();
				$api = new APIContent();
				$content['wrapper'] = $api->type('container')->_class('ibt-featured-media-object-container')->r();
				$content['wrapper']['left'] = $api->type('html_tag')->tag('div')->_class('ibt-featured-image-left')->r();
				$content['wrapper']['right'] = $api->type('html_tag')->tag('div')->_class('ibt-featured-text-right')->r();
				$content['wrapper']['link'] = $api->type('html_tag')->tag('div')->_class('ibt-featured-link-bottom')->r();
				return render($content);
				break;
		}
		
	}
}


?>