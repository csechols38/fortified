<?php

abstract class BtVocabularyHandler {
	
	
	abstract function pluginSettings($plugin, &$defaults, &$form, &$settings);
	
	abstract function displayOutput(&$content, $settings = array());
	
	// loads the vocabulary terms in thier desired plugin
	public function loadLayoutPlugin($plugin, $layout){
		if(!empty($plugin['machine'])){
			$pluginm = new BtPluginManager;
			$lplugin = $pluginm->loadLayoutFromCache('term', $plugin['machine'], $layout);
			if($lplugin){
				$layout = new $lplugin['handler']();
				$pluginm->loadStyleSheets($lplugin);
				return array('handler' => $layout, 'plugin' => $lplugin);
			}
		}
	}
	
}