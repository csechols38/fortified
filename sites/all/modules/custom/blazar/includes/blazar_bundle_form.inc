<?php


function blazar_bundle_form(&$form, &$form_state){

	if(!empty($form_state['build_info']['args'][0]->type)){
		$bundle = $form_state['build_info']['args'][0]->type;
		$uses_plugins = blazar_check_bundle($bundle);
	} else {
		$uses_plugins = 0;
	}
	
	$form['additional_settings'] = array(
      '#type' => 'vertical_tabs',
      '#theme_wrappers' => array('vertical_tabs'),
      '#prefix' => '<div>',
      '#suffix' => '</div>',
      '#tree' => TRUE,
    );
    
  $form['#attached']['js'][] = 'misc/form.js';
  $form['#attached']['js'][] = 'misc/collapse.js';

	$form['additional_settings']['plugin_settings'] = array(
		'#type' => 'fieldset',
		'#title' => t('Plugin Data'),
		'#collapsed' => TRUE,
		'#collapsible' => TRUE,
		'#attributes' => array(
			'id' => 'blazar-plugin-data',
		),
	);
	$form['additional_settings']['plugin_settings']['uses_plugins'] = array(
		'#type' => 'checkbox',
		'#title' => t('Enable Plugins for this bundle'),
		'#default_value' => $uses_plugins,
	);
	$form['additional_settings']['plugin_settings']['plugins'] = array(
		'#type' => 'select',
		'#title' => t('Plugin'),
		'#states' => array(
			'visible' => array(
				':input[name="plugin_settings[uses_plugins]"]' => array('checked' => TRUE),
			),
		),
		'#options' => array(
			'default' => t('Default'),
		),
	);
	
	$form['#attached']['js'][] = 'misc/form.js';
  $form['#attached']['js'][] = 'misc/collapse.js';
	
	$form['#submit'][] = 'blazar_bundle_submit';
}


function blazar_bundle_submit($form, &$form_state){
	if(!empty($form_state['values']['uses_plugins'])){
		if(!empty($form_state['build_info']['args'][0]->type)){
			$bundles = variable_get('blazar_bundles', array());
			$bundles = $bundles + array($form_state['build_info']['args'][0]->type);
			variable_set('blazar_bundles', $bundles);
		}
	}
}