<?php

class FiDisplaysHandler extends BtDisplayHandler {


	public function pluginSettings($plugin, $settings, $view_mode, &$element){

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




class FiTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		
	}

	public function displayOutput(&$content, $settings = array()){

	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}

}