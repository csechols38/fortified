<?php

function bt_taxonomy_block_config_form($form = array(), &$form_state){
		$form['bt_tax_block_plugins_location'] = array(
		'#type' => 'textfield',
		'#title' => t('Plugins Directory'),
		'#default_value' => variable_get('bt_tax_block_plugins_location', 'sites/all/modules/custom/plugins'),
	);
	return system_settings_form($form);
}