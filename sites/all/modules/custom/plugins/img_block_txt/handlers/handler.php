<?php

class IBTDisplaysHandler extends BtDisplayHandler {


	public function pluginSettings($plugin, $settings, $view_mode, &$element){
		$element['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Title'),
			'#default_value' => !empty($settings['title']) ? $settings['title'] : '',
		);
		$element['body'] = array(
			'#type' => 'text_format',
			'#title' => t('Body'),
			'#default_value' => !empty($settings['body']['value']) ? $settings['body']['value'] : '',
			'#format' => !empty($settings['body']['format']) ? $settings['body']['format'] : 'full_html',
		);
	}

	public function displaySummary($plugin, &$summary){
		$summary = t('Configure Image Block Text Display Settings');
	}

	public function displayOutput($display, &$items){
		if($display['type'] == 'img_block_txt' && !empty($items)){
			$content = array();
			$content['wrapper'] = BtApi::Container(array('img-block-text-entity-wrapper'));
			$title = !empty($display['settings']['title']) ? $display['settings']['title'] : '';
			$content['wrapper']['main_title'] = BtApi::htmlTag($title, 'h2', array('img-block-text-main-title'), -10);
			$body = !empty($display['settings']['body']['value']) ? $display['settings']['body']['value'] : '';
			$content['wrapper']['main_desc'] = BtApi::htmlTag($body, 'div', array('img-block-text-main-body'), -10);
			$content['wrapper']['inner'] = BtApi::Container(array('img-block-text-entity-inner'), 1);
			foreach($items as $delta => $vid){
				$content['wrapper']['inner'][$delta] = entity_taxonomy_block_load_vocab_children($vid['vid']);
			}
			$items = $content;
		}
	}
}




class IBTTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		/*
$settings['image_position'] = array(
			'#type' => 'select',
			'#options' => array(
				'top' => t('Top'),
				'bottom' => t('Bottom'),
			),
			'#title' => t('Image Position'),
			'#default_value' => !empty($defaults['image_position']) ? $defaults['image_position'] : 'bottom',
		);
		$settings['path'] = array(
			'#type' => 'textfield',
			'#title' => t('Path'),
			'#default_value' => !empty($defaults['path']) ? $defaults['path'] : '',
		);
*/
	}

	public function displayOutput(&$content, $settings = array()){
		
		
		/*
if(empty($settings['img_block_txt']['layout_plugin_settings'])){
			$r = new ApiContent();
			$img_block_txt = BtApi::Container(array('img-block-text-single'), null, 1);
			$position = !empty($settings['img_block_txt']['image_position']) ? $settings['img_block_txt']['image_position'] : 'top';
			$path = $settings['img_block_txt']['path'];
			$link = l($content->term->name, $path);
			$img_block_txt['name'] = BtApi::htmlTag($link, 'div', array('img-block-text-title'), 0);
			$img_block_txt['desc'] = BtApi::htmlTag($content->term->description, 'div', array('img-block-text-desc'), 1);
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
			}
			$content->taxonomy_term = $img_block_txt;
		} else {
			
		}
*/
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

}


class IBTVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$default_values = array();
		$element = array();
		if(!empty($defaults)){
			foreach($defaults as $key => $value){
				$default_values[$key] = $value;
			}
		}
		$settings['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Title'),
			'#default_value' => !empty($default_values['title']) ? $default_values['title'] : '',
		);
		$settings['body'] = array(
			'#type' => 'text_format',
			'#title' => t('Body'),
			'#default_value' => !empty($default_values['body']['value']) ? $default_values['body']['value'] : '',
			'#format' => !empty($default_values['body']['format']) ? $default_values['body']['format'] : 'full_html',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$this->addDependencies();
			$terms = $content->terms;
			$view_mode = key($settings);
			$s_settings = current($settings);
			$html = array();
			$html['wrapper'] = BtApi::Container(array('img-block-text-vocabluary-wrapper'));
			$title = !empty($s_settings['title']) ? $s_settings['title'] : '';
			$html['wrapper']['main_title'] = BtApi::htmlTag($title, 'h2', array('img-block-text-main-title'), -10);
			
			$andchor = drupal_html_id($title);
			$html['wrapper']['anchor'] = BtApi::htmlTag('', 'span', array('img-block-text-service-column-s-title'), -101, "#$andchor");
			
			
			$body = !empty($s_settings['body']['value']) ? $s_settings['body']['value'] : '';
			$html['wrapper']['main_desc'] = BtApi::htmlTag($body, 'div', array('img-block-text-main-body'), -10);
			
			$html['wrapper']['inner'] = BtApi::Container(array('img-block-text-entity-inner'), null, 1);
			foreach($terms as $tid => $data){
				$term_display = new IBTTermsHandler;
				$c = new stdClass;
				$c->term = $data['term'];
				$term_display->displayOutput($c, $settings);
				$html['wrapper']['inner'][$tid] = !empty($c->taxonomy_term) ? $c->taxonomy_term : '';
			}
			$content->taxonomy_term = $html;
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_image',
				'label' => 'Icon',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
	
	private function addDependencies(){
		drupal_add_css(drupal_get_path('module', 'img_block_txt') . '/css/img_block_txt.css');
	}
	
}