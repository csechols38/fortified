<?php


function ipdatatel_settings_form($form = array(), &$form_state){
	$form['tabs'] = array(
		'#type' => 'vertical_tabs',
	);
	$form['authentification'] = array(
		'#type' => 'fieldset',
		'#group' => 'tabs',
		'#title' => t('Authentification Settings'),
	);
	$form['authentification']['ipdatatel_dealer_id'] = array(
		'#type' => 'textfield',
		'#title' => t('Ipdatatel Dealer Id'),
		'#default_value' => variable_get('ipdatatel_dealer_id', ''),
	);
	$form['authentification']['ipdatatel_dealer_pass'] = array(
		'#type' => 'password',
		'#title' => t('Ipdatatel Dealer Password'),
		'#default_value' => variable_get('ipdatatel_dealer_pass', ''),
	);
	$form['authentification']['ipdatatel_api_url'] = array(
		'#type' => 'textfield',
		'#title' => t('Ipdatatel Api Url'),
		'#default_value' => variable_get('ipdatatel_api_url', ''),
	);
	
	return system_settings_form($form);
}



/**
 * Generates the task type editing form.
 */
function ipdatatel_entity_type_form($form, &$form_state, $task_type, $op = 'edit') {

  if ($op == 'clone') {
    $task_type->label .= ' (cloned)';
    $task_type->type = '';
  }

  $form['label'] = array(
    '#title' => t('Label'),
    '#type' => 'textfield',
    '#default_value' => $task_type->label,
    '#description' => t('The human-readable name of this task type.'),
    '#required' => TRUE,
    '#size' => 30,
  );

  // Machine-readable type name.
  $form['type'] = array(
    '#type' => 'machine_name',
    '#default_value' => isset($task_type->type) ? $task_type->type : '',
    '#maxlength' => 32,
    '#disabled' => $task_type->isLocked() && $op != 'clone',
    '#machine_name' => array(
      'exists' => 'ipdatatel_entity_type',
      'source' => array('label'),
    ),
    '#description' => t('A unique machine-readable name for this task type. It must only contain lowercase letters, numbers, and underscores.'),
  );

  $form['description'] = array(
    '#type' => 'textarea',
    '#default_value' => isset($task_type->description) ? $task_type->description : '',
    '#description' => t('Description about the task type.'),
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save Ipdatatel Entity'),
    '#weight' => 40,
  );

  if (!$task_type->isLocked() && $op != 'add' && $op != 'clone') {
    $form['actions']['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete Ipdatatel Entity'),
      '#weight' => 45,
      '#limit_validation_errors' => array(),
      '#submit' => array('ipdatatel_entity_type_form_submit_delete')
    );
  }
  return $form;
}

/**
 * Submit handler for creating/editing task_type.
 */
function ipdatatel_entity_type_form_submit(&$form, &$form_state) {
  $task_type = entity_ui_form_submit_build_entity($form, $form_state);
  // Save and go back.
  ipdatatel_entity_type_save($task_type);

  // Redirect user back to list of task types.
  $form_state['redirect'] = 'admin/structure/ipdatatel';
}

function ipdatatel_entity_type_form_submit_delete(&$form, &$form_state) {
  $form_state['redirect'] = 'admin/structure/ipdatatel/' . $form_state['ipdatatel_entity_type']->type . '/delete';
}

/**
 * Task type delete form.
 */
function ipdatatel_entity_type_form_delete_confirm($form, &$form_state, $task_type) {
  $form_state['task_type'] = $task_type;
  // Always provide entity id in the same form key as in the entity edit form.
  $form['task_type_id'] = array('#type' => 'value', '#value' => entity_id('ipdatatel_entity_type' ,$task_type));
  return confirm_form($form,
    t('Are you sure you want to delete task type %title?', array('%title' => entity_label('task_type', $task_type))),
    'task/' . entity_id('ipdatatel_entity_type' ,$task_type),
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Task type delete form submit handler.
 */
function ipdatatel_entity_type_form_delete_confirm_submit($form, &$form_state) {
  $task_type = $form_state['task_type'];
  ipdatatel_entity_type_delete($task_type);

  watchdog('ipdatatel_entity_type', '@type: deleted %title.', array('@type' => $task_type->type, '%title' => $task_type->label));
  drupal_set_message(t('@type %title has been deleted.', array('@type' => $task_type->type, '%title' => $task_type->label)));

  $form_state['redirect'] = 'admin/structure/ipdatatel';
}

/**
 * Page to select task Type to add new task.
 */
function ipdatatel_admin_add_page() {
  $items = array();
  foreach (ipdatatel_entity_type() as $task_type_key => $task_type) {
    $items[] = l(entity_label('ipdatatel_entity_type', $task_type), 'ipdatatel/add/' . $task_type_key);
  }
  return array('list' => array('#theme' => 'item_list', '#items' => $items, '#title' => t('Select type of task to create.')));
}



/**
 * Delete confirmation form.
 */
function ipdatatel_delete_form($form, &$form_state, $task) {
  $form_state['task'] = $task;
  // Always provide entity id in the same form key as in the entity edit form.
  $form['task_type_id'] = array('#type' => 'value', '#value' => entity_id('ipdatatel' ,$task));
  $task_uri = entity_uri('ipdatatel', $task);
  return confirm_form($form,
    t('Are you sure you want to delete task %title?', array('%title' => entity_label('ipdatatel', $task))),
    $task_uri['path'],
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Delete form submit handler.
 */
function ipdatatel_delete_form_submit($form, &$form_state) {
  $task = $form_state['task'];
  ipdatatel_delete($task);

  drupal_set_message(t('Task %title deleted.', array('%title' => entity_label('ipdatatel', $task))));

  $form_state['redirect'] = '<front>';
}
