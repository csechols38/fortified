<?php

class BootstrapTabsTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$default_values = array();
		if(!empty($defaults)){
			foreach($defaults as $key => $value){
				$default_values[$key] = $value;
			}
		}
	}

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->term)){
			
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}

}


class BoostrapTabs {
	
	public function __construct($terms, &$html, $settings){
		$this->terms = $terms;
		$this->content = $html;
		$this->settings = $settings;
	}
	
	public function buildTabs(){
		$wrapper = &$this->content['wrapper'];
		$r = new ApiContent();
		$tabs = array();
		$wrapper['tab_content'] = $r->type('container')->_class('tab-content')->weight(100)->r();
		$wrapper['desc'] = $r->type('container')->_class('term-data')->weight(-100)->r();
		$i = 0;
		foreach($this->terms as $type => $data){
			$display = $data['term_display'];
			$term = $data['term'];
			if($data['term_settings']->view_mode == 'bootstrap_tabs'){
				if($i == 0){
					$class = 'active';
				} else {
					$class = NULL;
				}
				
				$i++;
				$t_options = array(
					'fragment' => $term->tid,
					'attributes' => array(
						'data-toggle' => 'tab',
					),
				);
				$link = '<a data-toggle="tab" href="#'. $term->tid .'">'. $term->name .'</a>';
				$tabs[$term->tid] = array(
					'data' => l($term->name, '', $t_options),
					'class' => array($class),
				);
				if($this->settings['bootstrap_tabs']['layout_plugins'] != '_none'){
					$wrapper['tab_content'][$term->tid] = $r->type('html_tag')->tag('div')->value(render($display))->_class('tab-pane fade in ' . $class)->id($term->tid)->r();
				} else {
					$description = '<div class="tab-description">'. $term->description .'</div>';
					$wrapper['tab_content'][$term->tid] = $r->type('html_tag')->tag('div')->value($description)->_class('tab-pane fade in ' . $class)->id($term->tid)->r();
				}
			} else {
				$wrapper['desc'][$term->tid] = $r->type('html_tag')->tag('div')->value(render($display))->_class('term-data')->r();
			}
			
		}
		$wrapper['tabs'] = array(
			'#theme' => 'item_list',
			'#items' => $tabs,
			'#attributes' => array(
				'class' => array('nav', 'nav-tabs'),
			),
		);
	}
	
	
	
}


class BootstrapTabsVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		
	}
	
	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$content->html = array();	
			$r = new ApiContent();
			$html = &$content->html;
			$html['wrapper'] = $r->type('container')->_class('bootstrap-tabs')->r();
			$tabs = new BoostrapTabs($content->terms, $html, $settings);
			$tabs->buildTabs();
			$content->html = $tabs->content;
		}
	}
	
}