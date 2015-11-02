<?php

class OwlTestimonialsVocabsHandler extends BtVocabularyHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$default_values = array();
		$element = array();
		if(!empty($defaults)){
			foreach($defaults as $key => $value){
				$default_values[$key] = $value;
			}
		}
		$settings['title'] = array(
			'#type' => 'textfield',
			'#title' => t('title'),
			'#default_value' => !empty($default_values['title']) ? $default_values['title'] : '',
		);
		$settings['display_position'] = array(
			'#type' => 'select',
			'#title' => t('Testimonial Name Position'),
			'#options' => array(
				'above' => 'Above Testimonial',
				'below' => 'Below Testimonial',
			),
			'#default_value' => !empty($default_values['display_position']) ? $default_values['display_position'] : 'below',
		);
		$settings['owl_settings'] = array(
			'#type' => 'fieldset',
			'#title' => 'Owl Settings',
			'#collapsed' => TRUE,
			'#collpasible' => TRUE,
		);
		$settings['owl_settings']['items'] = array(
			'#type' => 'textfield',
			'#title' => t('Number Of Visible Items'),
			'#default_value' => !empty($default_values['owl_settings']['items']) ? $default_values['owl_settings']['items'] : '',
		);
		$settings['owl_settings']['autoPlay'] = array(
			'#type' => 'textfield',
			'#title' => t('Auto Speed'),
			'#description' => t('AutoPlay speed in <strong>Milliseconds</strong>. Set autoPlay to 0 to disable it.'),
			'#default_value' => !empty($default_values['owl_settings']['autoPlay']) ? $default_values['owl_settings']['autoPlay'] : 0,
		);
		$settings['owl_settings']['navigation'] = array(
			'#type' => 'select',
			'#title' => t('Navigation'),
			'#default_value' => !empty($default_values['owl_settings']['navigation']) ? $default_values['owl_settings']['navigation'] : array('false'),
			'#options' => array(
				'true' => 'On',
				'false' => 'Off',
			),
		);
		$settings['owl_settings']['pagination'] = array(
			'#type' => 'select',
			'#title' => t('Pagination'),
			'#default_value' => !empty($default_values['owl_settings']['pagination']) ? $default_values['owl_settings']['pagination'] : array('false'),
			'#options' => array(
				'true' => 'On',
				'false' => 'Off',
			),
		);
		$settings['owl_settings']['autoHeight'] = array(
			'#type' => 'select',
			'#title' => t('Auto Height'),
			'#default_value' => !empty($default_values['owl_settings']['autoHeight']) ? $default_values['owl_settings']['autoHeight'] : array('false'),
			'#options' => array(
				'true' => 'On',
				'false' => 'Off',
			),
		);
		$settings['owl_settings']['itemsDesktop'] = array(
			'#type' => 'textfield',
			'#title' => t('itemsDesktop'),
			'#default_value' => !empty($default_values['owl_settings']['itemsDesktop']) ? $default_values['owl_settings']['itemsDesktop'] : '1199, 3',
		);
		$settings['owl_settings']['itemsDesktopSmall'] = array(
			'#type' => 'textfield',
			'#title' => t('itemsDesktopSmall'),
			'#default_value' => !empty($default_values['owl_settings']['itemsDesktopSmall']) ? $default_values['owl_settings']['itemsDesktopSmall'] : '991, 3',
		);
		$settings['owl_settings']['itemsTablet'] = array(
			'#type' => 'textfield',
			'#title' => t('itemsTablet'),
			'#default_value' => !empty($default_values['owl_settings']['itemsTablet']) ? $default_values['owl_settings']['itemsTablet'] : '767, 2',
		);
		$settings['owl_settings']['itemsMobile'] = array(
			'#type' => 'textfield',
			'#title' => t('itemsMobile'),
			'#default_value' => !empty($default_values['owl_settings']['itemsMobile']) ? $default_values['owl_settings']['itemsMobile'] : '567, 1',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms)){
			$terms = $content->terms;
			$view_mode = key($settings);
			$s_settings = current($settings);
			$html = array();
			$html['wrapper'] = BtApi::Container(array('owl-container'));
			$html['wrapper']['main_title'] = BtApi::htmlTag($s_settings['title'], 'h2', array('owl-title'), -100);
			unset($s_settings['title']);
			$html['wrapper']['owl'] = BtApi::Container(array('owl-plugin-carousel'));
			// process settings
			$this->processSettings($content, $s_settings);
			// add dependencies
			$this->addDependancies();
			$content->html = $html;
		}
	}


	private function processSettings($content, $settings){
		if(!empty($settings['owl_settings'])){
			$break_points = array('itemsDesktop', 'itemsDesktopSmall', 'itemsTablet', 'itemsMobile');
			foreach($break_points as $delta => $value){
				if(array_key_exists($value, $settings['owl_settings'])){
					$explode_settings = explode(',', $settings['owl_settings'][$value]);
					if(!empty($explode_settings[1])){
						$settings['owl_settings'][$value] = $explode_settings;
					}
				}
			}
			if($settings['owl_settings']['autoPlay'] == 0){
				$settings['owl_settings']['autoPlay'] = 999999;
			}
			$content->processed_settings = $settings['owl_settings'];
		}
	}

	private function addDependancies(){
		drupal_add_css(drupal_get_path('module', 'owl') . '/css/owl.carousel.css', array('type' => 'file', 'weight' => -100, 'scope' => 'header'));
		drupal_add_css(drupal_get_path('module', 'owl') . '/js/owl.theme.css');
		drupal_add_css(drupal_get_path('module', 'owl') . '/js/owl.transitions.css');
		drupal_add_js(drupal_get_path('module', 'owl') . '/js/owl.carousel.min.js', array('type' => 'file', 'weight' => -100, 'scope' => 'header'));
	}

}



class OwlTermsHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
		$settings['glyphicon'] = array(
			'#type' => 'textfield',
			'#title' => t('Icon'),
			'#default_value' => !empty($defaults['glyphicon']) ? $defaults['glyphicon'] : '',
		);
		$settings['desc'] = array(
			'#type' => 'select',
			'#title' => t('Show Description'),
			'#options' => array(
				'yes' => 'Yes',
				'no' => 'No',
			),
			'#default_value' => !empty($defaults['desc']) ? $defaults['desc'] : 'yes',
		);
		$settings['title_display'] = array(
			'#type' => 'select',
			'#title' => t('Icon Display'),
			'#options' => array(
				'below' => 'Below Title',
				'above' => 'Above Title',
			),
			'#default_value' => !empty($defaults['title_display']) ? $defaults['title_display'] : 'above',
		);
		$settings['link'] = array(
			'#type' => 'select',
			'#title' => t('Show Link'),
			'#options' => array(
				'yes' => 'Yes',
				'no' => 'No',
			),
			'#default_value' => !empty($defaults['link']) ? $defaults['link'] : 'yes',
		);
		$settings['link_path'] = array(
			'#type' => 'textfield',
			'#title' => t('Link Path'),
			'#description' => 'Leave blank to use this Terms default path.',
			'#states' => array(
				'visible' => array(
					':input[name="bt_taxonomy_block[plugin_settings][link]"]' => array('value' => 'yes'),
				),
			),
			'#default_value' => !empty($defaults['link_path']) ? $defaults['link_path'] : '',
		);
		$settings['title_type'] = array(
			'#type' => 'select',
			'#title' => t('Title Display Type'),
			'#options' => array(
				'overlay' => 'Overlay',
				'bold' => 'Bold'
			),
			'#default_value' => !empty($defaults['layout_plugin_settings']['title_type']) ? $defaults['layout_plugin_settings']['title_type'] : 'bold',
		);
	}

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->term)){
			$term = $content->term;
			$settings = $settings['owl'];
			
			$wrapper = array();
			$wrapper['wrapper'] = BtApi::Container(array('item'));
			$wrapper['wrapper']['content'] = BtApi::Container(array('content'));
			$wrapper['wrapper']['content']['img'] = BtApi::Container(array('owl-img-container'));
			$icon_weight = $settings['title_display'] == 'above' ? -1 : 1;
			
			switch($settings['title_type']){
			  case 'overlay':
			  	$name_class = 'owl-name';
			  	break;
			  case 'bold':
			  	$name_class = 'owl-title-bold box-title';
			  	break;
			}
					
			$wrapper['wrapper']['content']['img']['name'] = BtApi::htmlTag($term->name, 'div', array($name_class), 1);
			
			if(!empty($settings['glyphicon'])){
			  $wrapper['wrapper']['content']['img']['icon'] = BtApi::Container(array('box-icon'));
			  $wrapper['wrapper']['content']['img']['icon']['icon'] = BtApi::htmlTag('', 'i', array($settings['glyphicon'], 'owl-icon'), -1);
			}
			
			if(!empty($settings['desc']) && $settings['desc'] == 'yes'){
			  $wrapper['wrapper']['content']['img']['desc'] = BtApi::htmlTag($term->description, 'div', array('box-content'), 3);
			}
			
			if($settings['link'] == 'yes'){
				if(empty($settings['link_path'])){
					$uri = "taxonomy/term/$term->tid";
					$link = l('Read more →', $uri);
					dpm($link);
					$wrapper['wrapper']['content']['img']['link'] = BtApi::htmlTag($link, 'div', array('owl-link'), 4);
				} else {
					$link = l('Read more →', $settings['link_path']);
					$wrapper['wrapper']['content']['img']['link'] = BtApi::htmlTag($link, 'div', array('owl-link'), 4);
				}
			}
			$content->taxonomy_term = $wrapper;
		}
	}
	
	public function pluginFieldInstances($plugin, $bundle){

	}
	
	private function loadImage($term){
		if(!empty($term->field_image['und'][0])){
			$image = &$term->field_image['und'][0];
			$options = array(
				'html' => TRUE,
			);
			$link = l($term->name, '');
			$img = field_view_value('taxonomy_term', $term, 'field_image', $image, 'full', $langcode = NULL);
			return l(render($img), '', $options);
		}
	}

}