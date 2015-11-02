<?php

function blazar_display_form($form = array(), &$form_state, $bundle){

	$form_state['bundle'] = $bundle;

	$form['layout'] = array(
		'#type' => 'select',
		'#title' => t('Plugin'),
		'#options' => array(
			'default' => t('Default'),
		),
	);
	
	$display = new BlazarDisplayForm($form, $form_state, 'node', $bundle);
	
	dpm($display);
	
	return $form;
}


trait BlazarFieldsUi {

	private function fieldUi(){
		$this->form['fields'] = $this
			->type('container')
			->_class('fields-ui')
			->r();
			
		$this->form['fields']['list'] = $this
			->type('container')
			->_class('fields-ui')
			->r();
			
		$this->form['fields']['list']['label'] = $this
			->type('html_tag')
			->tag('label')
			->value(t("Fields:"))
			->_class('fields-ui-label')
			->r();
		
		$this->listFields();
	}
	
	private function listFields(){
		foreach($this->instances as $machine => $data){
			$this->form['fields']['list'][$machine] = $this
				->type('html_tag')
				->tag('div')
				->value($data['label'])
				->_class('fields-ui-field')
				->r();
		}
	}
	
}


class BlazarDisplayForm {

	use BlazarFieldsUi, RenderApi;
	
	public function __construct(&$form, &$form_state, $entity_type, $bundle){
		$this->form = &$form;
		$this->form_state = &$form_state;
		$this->entity_type = $entity_type;
		$this->bundle = $bundle;
		$this->instances = field_info_instances('node', $bundle);
		$this->Ui();
	}
	
	
	
	private function Ui(){
		$this->fieldUi();
	}
	
	
	public function buildSections(){
		
	}
	
}


/*
class BlazarFieldsUi extends APIContent {

	public $form;
	
	public function __construct(&$form, &$form_state, $instances){
		$this->form = &$form;
		$this->buildUi();
	}
	
	
	
}
*/