<?php

function icon_title_desc_taxonomy_block_layout_handler(){
	return array('handler' => 'IconTitleDescLayoutHandler');
}


class IconTitleDescLayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Icon Title Description'),
			'description' => t('A layout with an icon title and description'),
			'module' => 'img_block_txt',
			'parent plugin' => 'img_block_txt',
			'instances' => TRUE,
			'stylesheets' => array(
				'less' => array(
					'path' => 'less/',
					'excludes' => array(),
				),
				'css' => array(
					'path' => 'css',
					'excludes' => array(),
				),
			),
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$this->addDependencies();
			$terms = $content->terms;
			$view_mode = key($settings);
			$s_settings = current($settings);
			$html = array();
			$html['wrapper'] = BtApi::Container(array('img-block-text-columns'));
			$title = !empty($s_settings['title']) ? $s_settings['title'] : '';
			$html['wrapper']['main_title'] = BtApi::htmlTag($title, 'h2', array('img-block-text-columns-title'), -100);
			
			$andchor = drupal_html_id($title);
			$html['wrapper']['anchor'] = BtApi::htmlTag('', 'span', array('img-block-text-columns-anchor'), -101, "$andchor");
			
			$body = !empty($s_settings['body']['value']) ? $s_settings['body']['value'] : '';
			$html['wrapper']['main_desc'] = BtApi::htmlTag($body, 'div', array('img-block-text-columns-desc'), -99);
			
			$html['wrapper']['inner'] = BtApi::Container(array('img-block-text-columns-inner'), null, 1);
			foreach($terms as $tid => $data){
				$html['wrapper']['inner'][$tid] = BtApi::Container(array('img-block-text-column'), null, 1);
				$h = &$html['wrapper']['inner'][$tid];
				$term = $data['term'];
				$term_display = new IBTTermsHandler;
				$h['name'] = BtApi::htmlTag($term->name, 'h4', array('img-block-text-item-title'), 0);
				$h['desc'] = BtApi::htmlTag($term->description, 'div', array('img-block-text-item-desc'), 
					1
				);
				if(!empty($term->field_image['und'][0])){
					$image = &$term->field_image['und'][0];
					$img = field_view_value('taxonomy_term', $term, 'field_image', $image, 'full', $langcode = NULL);
					$h['icon'] = BtApi::htmlTag(render($img), 'div', array('img-block-text-item-img'), -100);
				}
			}
			$content->taxonomy_term = $html;
		}
	}
	
	private function addDependencies(){
		drupal_add_css(drupal_get_path('module', 'img_block_txt') . '/css/img_block_txt.css');
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

}


?>