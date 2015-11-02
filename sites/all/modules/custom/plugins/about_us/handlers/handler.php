<?php


class AboutUsTermhandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){

	}

	public function displayOutput(&$content, $settings = array()){
		
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_image',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}

}