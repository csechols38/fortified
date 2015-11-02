<?php


class NahSlickForm {

	public $commands = array();
	public $defaults = array(
		'entity_type' => 'node',
		'bundle_type' => null,
		'view_mode' => 'default',
		'entities' => '',
	);

	public function __construct(&$form, &$form_state){
		$this->form = &$form;
		$this->form_state = &$form_state;
		$this->load();
	}

	private function load(){
		if(!empty($this->form['field_carousel_settings']['und'][0]['value']['#default_value'])){
			$this->defaults = unserialize($this->form['field_carousel_settings']['und'][0]['value']['#default_value']);
		}
		
		foreach($this->defaults as $key => &$default){
			if(isset($this->form_state['input'][$key])){
				$default = $this->form_state['input'][$key];
			}
		}
		$this->entity_type = $this->defaults['entity_type'];
	}

	public function build(){
		// main fieldset
		$this->form['slick_carousel'] = array(
			'#type' => 'fieldset',
			'#title' => t('Slides and Settings'),
			'#collapsed' =>  false,
			'#collapsible' => true,
			'#weight' => 90,
			'#attributes' => array(
				'class' => array('nah-slick-container'),
				'id' => 'nah-slick-container',
			),
		);
		// step 1 entity type
		$this->step1();
		// step 2 bundle type
		$this->step2();
		
		// step 5
		$this->step5();
		if(!empty($this->defaults['bundle_type']) || $this->defaults['entity_type'] == 'user'){
			// step 3 view modes
			$this->step3();
			// step 4 node auotcomplete
			$this->step4();
			// step 5 slick settings step
			//$this->step5();
		}
		
	}
	
	// step 1
	private function step1(){
		$this->form['slick_carousel']['show_title'] = $this->showTitle();
		$this->form['slick_carousel']['entity_type'] = $this->entityType();
	}
	
	// step 2
	private function step2(){
		$this->form['slick_carousel']['bundle_type'] = $this->bundleType();
	}
	
	// step 3
	private function step3(){
		$this->form['slick_carousel']['view_mode'] = $this->viewMode();
	}
	
	// step 4
	private function step4(){
		$this->form['slick_carousel']['entities'] = $this->nodeSelection();
	}
	
	// step 5
	private function step5(){
		$this->form['slick_carousel']['slick'] = array(
			'#type' => 'fieldset',
			'#title' => t('Slick Settings'),
			'#weight' => 100,
			'#tree' => true,
			'#collapsed' =>  true,
			'#collapsible' => true,
		);

		// Build slick options as form elements.
		$options = $this->slickItems();
		foreach ($options as $name => $values) {
			$this->form['slick_carousel']['slick'][$name] = array();
			$this->form['slick_carousel']['slick'][$name]['#title'] = $values['title'];
			$this->form['slick_carousel']['slick'][$name]['#description'] = $values['description'];
			if($values['type'] != 'select'){
				$this->form['slick_carousel']['slick'][$name]['#type'] = ($values['type'] == 'boolean') ? 'checkbox' : 'textfield';
			} elseif($values['type'] == 'select') {
				$this->form['slick_carousel']['slick'][$name]['#type'] = $values['type'];
				$this->form['slick_carousel']['slick'][$name]['#options'] = $values['options'];
			}
			$default_value = isset($this->defaults['slick'][$name]) ? $this->defaults['slick'][$name] : $values['default'];
			$this->form['slick_carousel']['slick'][$name]['#default_value'] = $default_value;
		}
	}
	
	

	private function viewMode(){
		$view_modes = array();
		if(!empty($this->defaults['bundle_type'])){
			$view_modes = $this->viewModes($this->defaults['bundle_type']);
		} 
		if(empty($view_modes)){
			$view_modes = array(
				'default' => 'Default',
				'full' => 'Full',
				'teaser' => 'Teaser',
			);
		}
		return array(
			'#type' => 'select',
			'#title' => t('View Mode'),
			'#description' => t('Content view mode.'),
			'#options' => $view_modes,
			'#default_value' => !empty($this->defaults['view_mode']) ? $this->defaults['view_mode'] : 'default',
		);
	}
	
	private function entityType(){
		return array(
			'#type' => 'select',
			'#title' => t('Entity Type'),
			//'#multiple' => TRUE,
			'#description' => t('Select the entity type to reference from.'),
			'#options' => $this->_entityOptions(),
			//'#size' => 10,
			'#default_value' => $this->defaults['entity_type'],
			'#ajax' => array(
				'callback' => '_nah_slick_select_callback',
				'wrapper' => 'nah-slick-container',
				'effect' => 'fade',
				'method' => 'replace',
			),
		);
	}
	
	private function nodeSelection(){
		$options = array();
		$query = new EntityFieldQuery();
		$query->entityCondition('entity_type', $this->entity_type);
		if($this->entity_type != 'user'){
			$query->entityCondition('bundle', $this->defaults['bundle_type']);
		}
		$result = $query->execute();
		if (isset($result[$this->entity_type])) {
			$nids = array_keys($result[$this->entity_type]);
			$nodes =  entity_load($this->entity_type, $nids);
			foreach($nodes as $nid => $node){
				if(!empty($node->title)){
					$display_name = $node->title;
				} else if(!empty($node->name)){
					$display_name = $node->name;
				} else {
					$display_name = "N/A";
				}
				$options[$nid] = $display_name;
				
			}
		}
		return array(
			'#type' => 'select',
			'#title' => t('Content'),
			'#multiple' => true,
			//'#multiple' => TRUE,
			'#description' => t('They content to show in a slider.'),
			'#options' => $options,
			//'#size' => 10,
			'#default_value' => !empty($this->defaults['entities'] ) ? $this->defaults['entities'] : null,
		);
	}

	private function bundleType(){
		return array(
			'#type' => 'select',
			'#title' => t('Bundle'),
			//'#multiple' => TRUE,
			'#description' => t('Select the bundle to reference content from.'),
			'#options' => $this->_bundleOptions(),
			//'#size' => 10,
			'#default_value' => !empty($this->defaults['bundle_type'] ) ? $this->defaults['bundle_type'] : null,
			'#ajax' => array(
				'callback' => '_nah_slick_select_callback',
				'wrapper' => 'nah-slick-container',
				'effect' => 'fade',
				'method' => 'replace',
			),
		);
	}
	
	private function showTitle(){
		return array(
			'#type' => 'checkbox',
			'#title' => t('Show Block Title'),
			'#description' => t('Select the bundle to reference content from.'),
			'#default_value' => isset($this->defaults['show_title']) ? $this->defaults['show_title'] : 0,
		);
	}
	
	private function _entityOptions(){
		$this->entity_types = array(
			'node' => 'Node',
			'user' => 'User',
			'taxonomy_term' => 'Taxonomy Term',
		);
		return $this->entity_types;
	}

	private function _bundleOptions(){
		$options = array();
		switch($this->entity_type){
			case 'node':
				$options = array(null => '- Select Bundle -');
				$this->bundle_types = node_type_get_types();
					foreach($this->bundle_types as $machine => $c_type){
						$options[$machine] = $c_type->name;
					}
				break;
			case 'user':
				$this->bundle_types = array('user' => 'user');
				$options = $this->bundle_types;
				break;
			case 'taxonomy_term':
				$this->bundle_types = taxonomy_get_vocabularies();
				foreach($this->bundle_types as $machine => $c_type){
						$options[$c_type->machine_name] = $c_type->name;
					}
				break;
		}
		return $options;
	}
	

	private function viewModes($name = ''){
		$bundle_settings = field_bundle_settings($this->entity_type, $name);
		$view_modes = array();
		foreach($bundle_settings['view_modes'] as $key => $data){
			$view_modes[$key] = ucfirst($key);
		}
		return $view_modes;

	}

	private function _bundleName($name){
		return !empty($this->bundle_types[$name]->name) ? $this->bundle_types[$name]->name : null;
	}

	public function slickItems(){
		$options['accessibility'] = array(
			'name' => 'accessibility',
			'title' => t('Accessibility'),
			'description' => t('Enables tabbing and arrow key navigation'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['vertical'] = array(
			'name' => 'vertical',
			'title' => t('Vertical'),
			'description' => t('Vertical slide direction'),
			'type' => 'boolean',
			'default' => FALSE,
		);

		$options['autoplay'] = array(
			'name' => 'autoplay',
			'title' => t('Autoplay'),
			'description' => t('Enables Autoplay'),
			'type' => 'boolean',
			'default' => FALSE,
		);

		$options['autoplayspeed'] = array(
			'name' => 'autoplaySpeed',
			'title' => t('Autoplay speed'),
			'description' => t('Autoplay Speed in milliseconds'),
			'type' => 'integer',
			'default' => 3000,
		);

		$options['arrows'] = array(
			'name' => 'arrows',
			'title' => t('Arrows'),
			'description' => t('Show prev/next arrows'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['dots'] = array(
			'name' => 'dots',
			'title' => t('Dots'),
			'description' => t('Current slide indicator dots'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['draggable'] = array(
			'name' => 'draggable',
			'title' => t('Draggable'),
			'description' => t('Enable mouse dragging'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['swipe'] = array(
			'name' => 'swipe',
			'title' => t('Swipe'),
			'description' => t('Enable swiping'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['touchmove'] = array(
			'name' => 'touchMove',
			'title' => t('TouchMove'),
			'description' => t('Enable slide motion with touch'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['touchthreshold'] = array(
			'name' => 'touchThreshold',
			'title' => t('touchThreshold'),
			'description' => t('Swipe distance threshold'),
			'type' => 'integer',
			'default' => 5,
		);

		$options['fade'] = array(
			'name' => 'fade',
			'title' => t('Fade'),
			'description' => t('Enable fade'),
			'type' => 'boolean',
			'default' => FALSE,
		);

		$options['infinite'] = array(
			'name' => 'infinite',
			'title' => t('Infinite'),
			'description' => t('Infinite loop sliding'),
			'type' => 'boolean',
			'default' => TRUE,
		);

		$options['cssease'] = array(
			'name' => 'cssEase',
			'title' => t('cssEase'),
			'description' => t('CSS3 Animation Easing'),
			'type' => 'varchar',
			'default' => 'ease',
		);

		$options['slidestoshow'] = array(
			'name' => 'slidesToShow',
			'title' => t('Slides to show'),
			'description' => t('Number of slides to show'),
			'type' => 'integer',
			'default' => 1,
		);

		$options['adaptiveheight'] = array(
			'name' => 'adaptiveHeight',
			'title' => t('adaptiveHeight'),
			'description' => t('adaptiveHeight'),
			'type' => 'boolean',
			'default' => FALSE,
		);

		$options['slidestoscroll'] = array(
			'name' => 'slidesToScroll',
			'title' => t('Slides to scroll'),
			'description' => t('Number of slides to scroll'),
			'type' => 'integer',
			'default' => 1,
		);

		$options['speed'] = array(
			'name' => 'speed',
			'title' => t('Speed'),
			'description' => t('Slide speed in milliseconds'),
			'type' => 'integer',
			'default' => 300,
		);
		
		$options['theme'] = array(
			'name' => 'button theme',
			'title' => t('Navigation Theme'),
			'description' => t('The theme to use for the navigation buttons'),
			'type' => 'select',
			'options' => array(
				'large-images' => 'Large Images',
				'large-text' => 'Large Text',
				'small-images' => 'Small Images',
				'small-text' => 'Small Text',
				'large-clear-images' => 'Large Clear',
				'small-clear-images' => 'Small Clear',
			),
			'default' => !empty($this->defaults['slick']['theme']) ? $this->defaults['slick']['theme'] : 'large-images',
		);

		return $options;
	}
}
