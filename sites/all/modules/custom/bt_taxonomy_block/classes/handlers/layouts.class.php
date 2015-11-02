<?php


abstract class BtVocabsLayoutsHandler {
	
	abstract function pluginInfo();
	
	abstract function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings);
	
	abstract function displayOutput(&$content, $settings = array());
	
	
}


abstract class BtTermsLayoutsHandler {
	
	abstract function pluginInfo();
	
	abstract function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings);
	
	abstract function displayOutput(&$content, $settings = array());
	
	
}