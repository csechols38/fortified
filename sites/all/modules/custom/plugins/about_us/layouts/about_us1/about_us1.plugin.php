<?php

function about_us1_taxonomy_block_layout_handler(){
	return array('handler' => 'AboutUs1LayoutHandler');
}


class AboutUs1LayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('About Us - With background Image'),
			'description' => t('About us Block With title, desc, and background image'),
			'module' => 'about_us',
			'parent plugin' => 'about_us',
			'module title' => 'About Us With Background Image',
			'plugin type' => 'term',
			'css' => array(
				'path' => '/css/',
				'filename' => 'layout.css',
			),
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		
	}
	
	public function displayOutput(&$content, $settings = array()){
		$term = $content->term;
		$html['wrapper'] = BtApi::Container(array('about-us-layout-1-wrapper'));
		$html['wrapper']['main_title'] = BtApi::htmlTag($term->name, 'h2', array('about-us-layout-1-title'), -99);
		$html['wrapper']['desc'] = BtApi::htmlTag($term->description, 'div', array('about-us-layout-1-desc'), -98);
		if(!empty($term->field_image['und'][0])){
			$image = &$term->field_image['und'][0];
			$img = field_view_value('taxonomy_term', $term, 'field_image', $image, 'full', $langcode = NULL);
			$html['wrapper']['img'] = BtApi::htmlTag(render($img), 'div', array('about-us-layout-1-background-img'), -97);
		}
		$content->taxonomy_term = $html;
	}
}


?>