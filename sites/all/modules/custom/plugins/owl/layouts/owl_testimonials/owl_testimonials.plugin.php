<?php

function owl_testimonials_taxonomy_block_layout_handler(){
	return array('handler' => 'OwlTestimonialsLayoutHandler');
}


class OwlTestimonialsLayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Owl Testimonials'),
			'machine' => 'owl_testimonials',
			'description' => t('owl_testimonials'),
			'module' => 'owl',
			'parent plugin' => 'owl',
			/*
'css' => array(
				'path' => '/css/',
				'filename' => 'layout.css',
			),
*/
		);
	}
	
	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			dpm($content);
		}
	}
	
	private function addDependencies(){
		drupal_add_css(drupal_get_path('module', 'img_block_txt') . '/css/img_block_txt.css');
	}

}


?>