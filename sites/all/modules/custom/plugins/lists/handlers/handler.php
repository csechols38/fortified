<?php


class ListsTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){

	}

	public function displayOutput(&$content, $settings = array()){
		$content->taxonomy_term = array();
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}

}



class ListsVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$settings['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Title'),
			'#default_value' => !empty($defaults['title']) ? $defaults['title'] : '',
		);
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$settings = $settings['lists'];
			$terms = $content->terms;
			$api = new ApiContent();
			$list = array();
			foreach($terms as $type => $data){
				$term = $data['term'];
				
			}
		}
	}
	
}