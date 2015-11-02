<?php

abstract class BtTermHandler {

	abstract function pluginSettings($plugin, &$defaults, &$form, &$settings);
	
	abstract function displayOutput(&$content, $settings = array());
	
	public function addContextualLinks($render, $links){
		$context = array();
		$context['wrapper'] = BtApi::Container(array('contextual-links-wrapper', 'contextual-links-processed'));
	}
	
	abstract function pluginFieldInstances($plugin, $bundle);
	
}