<?php

abstract class BtDisplayHandler {
	
	abstract function pluginSettings($plugin, $settings, $view_mode, &$element);
	
	abstract function displaySummary($plugin, &$summary);
	
	abstract function displayOutput($display, &$items);
	
}