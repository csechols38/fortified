<?php

class FcDisplaysHandler extends BtDisplayHandler {


	public function pluginSettings($plugin, $settings, $view_mode, &$element){
		$element['number_of_items'] = array(
			'#type' => 'textfield',
			'#title' => t('Number Of Items'),
			'#default_value' => !empty($settings['number_of_items']) ? $settings['number_of_items'] : 5,
		);
	}

	public function displaySummary($plugin, &$summary){
		$summary = t('Configure Owl Content Slider Settings');
	}

	public function displayOutput($display, &$items){
		if($display['type'] == 'owl_slider_single' && !empty($items)){
			$html = array();
			$html['wrapper'] = BtApi::Container(array('main-featured-content-image-wrapper'), 'featured-content-carousel');
			foreach($items as $delta => $vid){
				$item = entity_taxonomy_block_load_vocab_children($vid['vid']);
				$html['wrapper'][$delta] = $item;
			}
			$items = $html;
		}
	}

}




class FcTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$default_values = array();
		$element = array();
		if(!empty($defaults)){
			foreach($defaults as $key => $value){
				$default_values[$key] = $value;
			}
		}
		$settings['number_of_items'] = array(
			'#type' => 'textfield',
			'#title' => t('Number Of Items'),
			'#default_value' => !empty($default_values['number_of_items']) ? $default_values['number_of_items'] : 5,
		);
		$settings['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Path'),
			'#default_value' => !empty($default_values['path']) ? $default_values['path'] : '#',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		$html = array();
		$html['wrapper'] = BtApi::Container(array('featured-content-image-container'));
		$description = $content->term->description;
		if(!empty($content->term->field_image['und'][0])){
			$image = &$content->term->field_image['und'][0];
			$path = !empty($content->settings->settings['owl_slider_single']['path']) ? $content->settings->settings['owl_slider_single']['path'] : '';
			$options = array(
				'html' => TRUE,
			);
			$link = l($content->term->name, $path);
			$img = field_view_value('taxonomy_term', $content->term, 'field_image', $image, 'full', $langcode = NULL);
			$image = l(render($img), $path, $options);
			$html['wrapper']['img'] = BtApi::htmlTag($image, 'div', array('featured-content-image'), 0);
			$html['wrapper']['name'] = BtApi::htmlTag($link, 'div', array('featured-content-name'), 1);
		}
		fc_add_carousel_dependacies();
		$content->taxonomy_term = $html;
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}

}