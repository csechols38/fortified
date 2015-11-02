<?php

/**
 * implements hook_taxonomy_block_plugin_alter();
 */
function hook_taxonomy_block_plugin_alter(&$plugins){
	$plugins += array('media_object' => 'Bootstrap Media Object');
}

/**
 * implements hook_taxonomy_block_display_alter();
 */
function hook_taxonomy_block_display_alter(&$content, $settings = array()){

}

/**
 * implements hook_taxonomy_block_plugin_settings_alter();
 */
function hook_taxonomy_block_plugin_settings_alter(&$plugin, &$settings, &$form, $defaults = array()){

}


/**
 * implements hook_taxonomy_block_plugin_terms_info
 */
function hook_taxonomy_block_plugin_terms_info(&$plugins){

}

/**
 * implements hook_taxonomy_block_plugin_vocabs_info
 */
function hook_taxonomy_block_plugin_vocabs_info(&$plugins){

}


/**
 * implements hook_taxonomy_block_plugin_displays_info
 */
function hook_taxonomy_block_plugin_displays_info(&$plugins){

}