<?php

class MediaObjectDisplaysHandler extends BtDisplayHandler {


	public function pluginSettings($plugin, $settings, $view_mode, &$element){
		$element = media_object_form_default('display', $settings);
	}

	public function displaySummary($plugin, &$summary){
		$summary = t('Configure Media Object Display Settings');
	}

	public function displayOutput($display, &$items){
		if($display['type'] == 'media_object' && !empty($items)){
			$content = array();
			$content['wrapper'] = BtApi::Container(array('media-obj-entity'));
			foreach($items as $delta => $vid){
				$content['wrapper'][$delta] = entity_taxonomy_block_load_vocab_children($vid['vid']);
			}
			$items = $content;
		}
	}
	

}




class MediaObjectTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		
	}

	public function displayOutput(&$content, $settings = array()){
		$content->taxonomy_term = array();
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}

}


class MediaObjectVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$default_values = array();
		if(!empty($defaults)){
			foreach($defaults as $key => $value){
				$default_values[$key] = $value;
			}
		}
		$settings['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Header'),
			'#default_value' => !empty($default_values['title']) ? $default_values['title'] : '',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$terms = $content->terms;
		}
			
	}

}