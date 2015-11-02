<?php

function mot_image_list_taxonomy_block_layout_handler(){
	return array('handler' => 'MoTImageListPluginHandler');
}


class MoTImageListPluginHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Image With List'),
			'machine' => 'mot_image_list',
			'description' => t('Image With List'),
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
		$settings['image_position'] = array(
			'#type' => 'select',
			'#title' => t('Image Position'),
			'#options' => array(
				'left' => 'Left',
				'right' => 'Right'
			),
			'#default_value' => !empty($defaults['layout_plugin_settings']['image_position']) ? $defaults['layout_plugin_settings']['image_position'] : 'left',
		);
		$settings['list_title'] = array(
			'#type' => 'textfield',
			'#title' => t('List Title'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['list_title']) ? $defaults['layout_plugin_settings']['list_title'] : '',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->term)){
			$this->html = array();
			$term = $content->term;
			$this->r = new ApiContent();
			$this->position = $settings['media_object']['layout_plugin_settings']['image_position'];
			$this->list_title = $settings['media_object']['layout_plugin_settings']['list_title'];
			$this->html['w'] = $this->r->type('container')->_class('mot-list-item container')->r();
			$this->html['w']['tc'] = $this->r->type('container')->_class('mot-list-header')->r();
			$this->html['w']['tc']['title'] = $this->r->type('html_tag')->tag('h2')->value($term->name)->_class('mot-list-name')->r();
			$this->html['w']['tc']['desc'] = $this->r->type('html_tag')->tag('div')->value($term->description)->_class('mot-list-desc')->r();
			$this->html['w']['main'] = $this->r->type('container')->_class('media')->r();
			
			$this->loadImage($term);
			$this->buildList($term);
			$content->taxonomy_term = $this->html;
		}
	}
	
	private function buildList($term){
		if(!empty($term->field_mo_list)){
			$this->html['w']['main']['w']['l'] = $this->r->type('container')->_class('media-body')->r();
			$this->html['w']['main']['w']['l']['w'] = $this->r->type('container')->_class('list-c ' . $this->position)->r();
			$this->html['w']['main']['w']['l']['w']['t'] = $this->r->type('html_tag')->tag('h3')->value($this->list_title)->_class('mot-l-title')->r();
			$items = array();
			foreach($term->field_mo_list['und'] as $delta => $value){
				$items[] = array(
					'data' => $value['value'],
					'class' => array('list-item'),
				);
			}
			$this->html['w']['main']['w']['l']['w']['list'] = array(
				'#theme' => 'item_list',
				'#items' => $items,
				'#attributes' => array(
					'class' => array('mot-list'),
				),
			);
			$this->html['w']['main']['w']['l']['w']['btn_wrapper'] = $this->r->type('container')->_class('btn-group')->r();
			$this->html['w']['main']['w']['l']['w']['btn_wrapper']['btn'] = $this->r->type('html_tag')->tag('button')->value("Learn more")->_class('btn btn-primary')->r();
			$this->html['w']['main']['w']['l']['w']['btn_wrapper']['qquote'] = $this->r->type('html_tag')->tag('button')->value("Get a quote")->_class('btn btn-primary')->r();
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
			$this->html['w']['main']['w']['img'] = $this->r->type('html_tag')->tag('div')->value(l(render($img), '', $options))->_class('mot-image '. 'pull-'. $this->position)->r();
		}
	}

}


?>