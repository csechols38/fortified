<?php

function taxonomy_block_layouts_form($form = array(), &$form_state, $entity_type, $entity_id){

	$form['test'] = array(
		'#type' => 'markup',
		'#markup' => 'blablablab',
	);


	if(!empty($form_state['handler'])){
		$ajaxh = new BtTaxonomyLayoutAjaxHandler($form_state['handler'], $form, $form_state);
	}

	if($entity_type && $entity_id && empty($form_state['handler'])){
		$layout = new BtTaxonomyLayoutLoader($entity_type, $entity_id, $form, $form_state);
		$layout->loadRegions();
	}
	
	

	return $form;
}



function taxonomy_block_layouts_region_callback($form, &$form_state){
	return dpm($form['region_display']);
}




class BtTaxonomyLayoutAjaxHandler {
	
	public function __construct($handler, &$form, &$form_state){
		$this->handler = $handler;
		$this->form = $form;
		$this->form_state = $form_state;
		$this->region = $form_state['values']['region_display'];
		$this->loadRegion();
	}
	
	public function loadRegion(){
		$this->form['region_display'] = array(
			'#type' => 'markup',
			'#markup' => 'bfssfsfadalabalbaddsdlbalba',
			'#attributes' => array('id' => 'taxonomy-block-layout-region')
		);
	}
	
}


class BtTaxonomyLayoutFormHandler {

	public function __construct($handler, &$form, &$form_state){
		$this->handler = $handler;
		$this->form = $form;
		$this->form_state = $form_state;
	}
	
	
	
}




class BtTaxonomyLayoutLoader extends BtTaxonomyPluginLoader {

	public $region_options = array(
		'default' => 'Default',
	);

	public function __construct($entity_type, $entity_id, &$form, &$form_state){
		$this->form = &$form;
		$this->form_state = &$form_state;
		parent::__construct($entity_type, $entity_id);
		$this->form_state['handler'] = $this;
	}

	public function loadRegions(){
		if(!empty($this->regions)){
			foreach($this->regions as $p_type => $handler){
				$region = $handler->pluginRegions();
				if(!empty($region)){
					foreach($region as $machine => $regions){
						$this->region_options[$machine] = $regions['name'];
					}
					$this->regions[$p_type] = $region;
				}
			}
		}
		$this->regionOptions();
		return $this;
	}

	private function regionOptions(){
		$this->form['region_display'] = array(
			'#type' => 'hidden',
			'#attributes' => array('id' => 'taxonomy-block-layout-region'),
		);
		$this->form['region_options'] = array(
			'#type' => 'select',
			'#title' => t('Display Types'),
			'#options' => $this->region_options,
			'#ajax' => array(
				'wrapper' => 'taxonomy-block-layout-region',
				'callback' => 'taxonomy_block_layouts_region_callback',
				'method' => 'replace',
				'effect' => 'fade',
			),
		);
	}
	
	public function initRegion(){
		
	}
}




class BtTaxonomyPluginLoader extends BtPluginManager {

	public $regions = array();

	public function __construct($entity_type, $entity_id){
		$this->entity_type = $entity_type;
		$this->entity_id = $entity_id;
		switch($entity_type){
		case 'taxonomy_vocab':
			$this->type = 'vocab';
			$this->loadVocab();
			$this->vocabulary = taxonomy_vocabulary_load($this->entity_id);
			break;
		case 'taxonomy_term':
			$this->type = 'term';
			$this->loadTerm();
			$this->term = taxonomy_term_load($this->entity_id);
			break;
		}
	}

	private function loadTerm(){
		if($settings = _bt_taxonomy_load_term_settings($this->entity_id)){
			if(!empty($settings->settings)){
				$settings->settings = unserialize($settings->settings);
				$this->master_plugin_settings = $settings->settings;
			}
			$this->master_plugin_machine = key($settings->settings);
			$this->loadPluginData($this->type);
			if(!empty($this->master_plugin_settings[$this->master_plugin_machine]['layout_plugins'])){
				$this->layout_plugin_machine = $this->master_plugin_settings[$this->master_plugin_machine]['layout_plugins'];
				$this->loadPluginData('layout');
			}
		}
		return $this;
	}

	private function loadVocab(){
		if($settings = bt_taxonomy_load_vocabulary_settings($this->entity_id)){
			$this->master_plugin_machine = $settings['taxonomy_block_view_mode'];
			$this->loadPluginData($this->type);
			if(!empty($settings['settings']['layout_plugins'])){
				$this->layout_plugin_machine = $settings['settings']['layout_plugins'];
				$this->loadPluginData('layout');
			}
		}
		return $this;
	}

	private function loadPluginData($type){
		switch($type){
		case 'vocab':
		case 'term':
			$this->master_plugin = $this->loadPluginFromCache($this->master_plugin_machine, $type);
			$this->master_plugin_title = $this->master_plugin['title'];
			if(!empty($this->master_plugin['regions'])){
				$this->regions['master_plugin'] = new $this->master_plugin['handler']();
			}
			break;
		case 'layout':
			$this->layout_plugin = $this->loadLayoutFromCache($this->type, $this->master_plugin_machine, $this->layout_plugin_machine);
			$this->layout_plugin_title = $this->layout_plugin['name'];
			if(!empty($this->layout_plugin['regions'])){
				$this->regions['layout_plugin'] = new $this->layout_plugin['handler']();
			}
			break;
		case 'display':

			break;
		}
	}
}