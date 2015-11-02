<?php

/**
 * implements hook_taxonomy_block_plugin_alter();
 */
function hook_entity_taxonomy_block_plugin_alter(&$plugins){
	$plugins += array('media_object' => 'Bootstrap Media Object');
}

/**
 * implements hook_taxonomy_block_display_alter();
 */
function hook_entity_taxonomy_block_display_alter(&$content, $settings = array()){

}

/**
 * implements hook_taxonomy_block_display_alter();
 */
function hook_entity_taxonomy_block_display_summary_alter($plugin, &$summary){

}

/**
 * implements hook_taxonomy_block_plugin_settings_alter();
 */
function hook_entity_taxonomy_block_plugin_settings_alter($plugin, $settings, $view_mode, &$element){

}