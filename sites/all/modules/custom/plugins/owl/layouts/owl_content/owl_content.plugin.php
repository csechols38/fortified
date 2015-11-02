<?php

function owl_content_taxonomy_block_layout_handler(){
	return array('handler' => 'OwlContentLayoutHandler');
}


class OwlContentLayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Owl Content'),
			'machine' => 'owl_content',
			'description' => t('Owl Content'),
			'load term plugins' => TRUE, // loads the terms in there respective plugin view mode if they have one chosen
			'module' => 'owl',
			'parent plugin' => 'owl',
			'stylesheets' => array(
				'less' => array(
					'path' => 'less/',
					'excludes' => array(),
				),
			),
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		$settings['media_type'] = array(
			'#type' => 'select',
			'#title' => t('Media Type'),
			'#options' => array(
				'image' => 'image',
				'icon' => 'icon'
			),
			'#default_value' => !empty($defaults['layout_plugin_settings']['media_type']) ? $defaults['layout_plugin_settings']['media_type'] : 'image',
		);
		$settings['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Title'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['title']) ? $defaults['layout_plugin_settings']['title'] : '',
		);
		$settings['desc'] = array(
			'#type' => 'text_format',
			'#title' => t('Description'),
			'#format' => !empty($defaults['layout_plugin_settings']['desc']['format']) ? $defaults['layout_plugin_settings']['desc']['format'] : 'full_html',
			'#default_value' => !empty($defaults['layout_plugin_settings']['desc']['value']) ? $defaults['layout_plugin_settings']['desc']['value'] : '',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms) && !empty($content->html)){
			$terms = $content->terms;
			$view_mode = key($settings);
			$s_settings = current($settings);
			$this->settings = $content->processed_settings;
			$this->carousel_id = drupal_html_id('owl-carousel-0');
			$content->html['wrapper']['owl']['w'] = BtApi::Container(array($this->carousel_id));
			$owl = &$content->html['wrapper']['owl']['w'];
			$layout_settings = $s_settings['layout_plugin_settings'];
			$name_weight = $s_settings['display_position'] == 'below' ? 1 : -1;
			
			$content->html['td'] = BtApi::Container(array('owl-td'));
			
			if($layout_settings['title']){
				$content->html['td']['title'] = BtApi::htmlTag($layout_settings['title'], 'h2', array('owl-section-title'), -100);
			}
			if($layout_settings['desc']['value']){
				$content->html['td']['desc'] = BtApi::htmlTag($layout_settings['desc']['value'], 'div', array('owl-section-div'), -99);
			}
			
			$content->html['td']['divider'] = BtApi::htmlTag('', 'div', array('hr', 'h1'), -98);
			
			foreach($terms as $tid => $term){
				if(!empty($term['term_display'])){
					$owl += array(
						$tid => $term['term_display'],
 					);
				}
			}
			$this->processSettings();
			$this->addDependancies();
		}
	}
	
	private function processSettings(){
		$this->settings['wrapper_class'] = $this->carousel_id;
	}
	
	private function addDependancies(){
		drupal_add_js(array('owl_content' => array('settings' => $this->settings)), 'setting');
		drupal_add_js(drupal_get_path('module', 'owl') . '/layouts/owl_content/js/owl_content.js', array('type' => 'file', 'weight' => 100, 'scope' => 'header'));
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
	
	private function loadIcon(){
		
	}

}


?>