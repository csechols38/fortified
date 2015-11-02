<?php

function owl_carousel_taxonomy_block_layout_handler(){
	return array('handler' => 'owlCarouselLayoutHandler');
}


class owlCarouselLayoutHandler extends BtVocabsLayoutsHandler {

	public function pluginInfo(){
		return array(
			'version' => '1.0',
			'name' => t('Owl Carousel'),
			'machine' => 'owl_carousel',
			'description' => t('Owl Carousel'),
			'module' => 'owl',
			'parent plugin' => 'owl',
			'instances' => TRUE,
			'stylesheets' => array(
				'less' => array(
					'path' => 'less/',
					'excludes' => array(),
				),
			),
		);
	}

	public function pluginSettings($plugin, &$defaults, &$form, &$form_state, &$settings){
		$defaults = $defaults['layout_plugin_settings'];
		$settings['bullets'] = array(
			'#type' => 'select',
			'#title' => t('Bullets'),
			'#default_value' => !empty($defaults['bullets']) ? $defaults['bullets'] : array('false'),
			'#options' => array(
				'true' => 'On',
				'false' => 'Off',
			),
		);
		$settings['overlay_title'] = array(
			'#type' => 'select',
			'#title' => t('Overlay Title'),
			'#default_value' => !empty($defaults['overlay_title']) ? $defaults['overlay_title'] : array('false'),
			'#options' => array(
				'true' => 'On',
				'false' => 'Off',
			),
		);
		$settings['overlay_desc'] = array(
			'#type' => 'select',
			'#title' => t('Overlay Description'),
			'#default_value' => !empty($defaults['overlay_desc']) ? $defaults['overlay_desc'] : array('false'),
			'#options' => array(
				'true' => 'On',
				'false' => 'Off',
			),
		);
		$settings['bullets_type'] = array(
			'#type' => 'select',
			'#title' => t('Bullets Type'),
			'#default_value' => !empty($defaults['bullets_type']) ? $defaults['bullets_type'] : array('false'),
			'#options' => array(
				'standard' => 'Standard',
				'background' => 'With Background Image',
			),
			'#states' => array(
				'visible' => array(   // action to take.
					':input[name="bt_taxonomy_block[plugin_settings][layout_plugin_settings][bullets]"]' => array('value' => 'true'),
				),
			),
		);
	}

	public function displayOutput(&$content, $settings = array()){
		if(!empty($content->terms) && !empty($content->html)){
			$items = array();
			foreach($content->terms as $type => $data){
				$term = $data['term'];
				$items[] = $term;
			}
			$plugin_settings = $settings['owl']['layout_plugin_settings'];
			$carousel = new OwlCarousel($content->html, $items, $content->processed_settings, $plugin_settings);
			$carousel->buildItems();
			$content->html = $carousel->carousel;
		}
	}


	public function pluginFieldInstances($plugin, $bundle){
		return array(
			array(
				'field_name' => 'field_owl_link_image',
				'label' => 'Carousel Image',
				'entity_type' => 'taxonomy_term',
				'bundle' => $bundle,
			),
		);
	}
}


class OwlCarousel {

	public function __construct(&$html = array(), $items, $settings = array(), $plugin_settings = array()){	
		$this->api = new APIContent();
		$this->items = $items;
		$this->settings = $settings;
		$this->carousel_id = drupal_html_id('owl-carousel-0');
		$this->plugin_settings = $plugin_settings;
		$this->carousel = &$html['wrapper']['owl'];
		$carousel = &$this->carousel;
		$carousel['wrapper'] = $this->api->type('container')->_class($this->carousel_id)->r();
		$this->processSettings();
		$this->addDependancies();
	}
	
	private function processSettings(){
		$this->settings['wrapper_class'] = $this->carousel_id;
		$this->settings['bullets'] = false;
		if($this->plugin_settings['bullets'] == 'true' && $this->plugin_settings['bullets_type'] == 'background'){
			$this->settings['bullets'] = 'background';
		}
	}

	public function buildItems(){
		$carousel = &$this->carousel['wrapper'];
		$api = &$this->api;
		foreach($this->items as $delta => $value){
			if(!empty($value->field_owl_link_image['und'])){
				foreach($value->field_owl_link_image['und'] as $d => $img){
					$image = $this->loadImage($value, $img);
					$carousel[$delta] = $api->type('container')->_class('item-wrapper')->r();
			
					
					$carousel[$delta]['overlay'] = $api->type('container')->_class('owl-carousel-overlay')->r();
					$overlay = '';
					if($this->plugin_settings['overlay_title'] == 'true'){
						$title = $api->type('html_tag')->tag('h1')->value($value->name)->_class('owl-heading')->r();
						$overlay .= render($title);
					}
					if($this->plugin_settings['overlay_desc'] == 'true'){
						$desc = $api->type('html_tag')->tag('h3')->value($value->description)->_class('owl-caption')->r();
						$overlay .= render($desc);
					}
					$carousel[$delta]['overlay']['inner'] = $api->type('html_tag')->tag('div')->value($overlay)->_class('overlay-inner')->r();
					
					
					$api->type('html_tag')->tag('div')->value($value->description)->_class('owl-carousel-desc')->r();
				
					//$style = array('height: auto; width:100%;');
					$carousel[$delta]['img'] = $api->type('html_tag')->tag('div')->value($image)->_class('carousel-image')->style($style)->r();

				}
			}
		}
		return $this;
	}

	private function loadImage($term, $image){
			$options = array(
				'html' => TRUE,
			);
			$img = field_view_value('taxonomy_term', $term, 'field_owl_link_image', $image, 'full', $langcode = NULL);
			dpm($img);
			return render($img);
	}

	private function addDependancies(){
		drupal_add_js(array('owl' => array('settings' => $this->settings)), 'setting');
		drupal_add_js(drupal_get_path('module', 'owl') . '/layouts/owl_carousel/js/owl.js', array('type' => 'file', 'weight' => 100, 'scope' => 'header'));
	}
}


?>