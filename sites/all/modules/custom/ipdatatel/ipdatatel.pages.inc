<?php

/**
 * Task view callback.
 */
function ipdatatel_view($task) {
  drupal_set_title(entity_label('ipdatatel', $task));
  return entity_view('ipdatatel', array(entity_id('ipdatatel', $task) => $task), 'full');
}
