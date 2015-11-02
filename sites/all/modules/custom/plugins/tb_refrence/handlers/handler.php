<?php


class TbRefrenceTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		/*
$entities = entity_get_info();
		$entity_options = array();
		foreach($entities as $entity => $data){
			$entity_options[$entity] = $data['label'];
		}
		$settings['entity'] = array(
			'#type' => 'select',
			'#options' => $entity_options,
			'#title' => t('Entity Type'),
			'#default_value' => !empty($defaults['layout_plugin_settings']['entity']) ? $defaults['layout_plugin_settings']['entity'] : 'node',
		);
*/
	}

	public function displayOutput(&$content, $settings = array()){
		
	}
	
	public function pluginFieldInstances($plugin, $bundle){

	}
	
	public function pluginFields($plugin){
		return array(
			'field_tb_refrence' => array(
				'field_name' => 'field_tb_refrence', 
				'type' => 'link_field',
				'module' => 'link_field',
				'cardinality' => 0,
			),
		);
	}

}
