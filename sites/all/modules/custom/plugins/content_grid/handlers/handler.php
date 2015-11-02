<?php



class ContentGridAjaxDisplayHandler extends BtDisplayHandler {


	public function pluginSettings($plugin, $settings, $view_mode, &$element){
		$element['title'] = array(
			'#type' => 'textfield',
			'#title' => t('Grid Title'),
			'#default_value' => !empty($settings['title']) ? $settings['title'] : '',
		);
		$element['mobile_enabled'] = array(
				'#type' => 'select',
				'#title' => t('Enable Mobile'),
				'#description' => t('Disables the ajax and displays the items with title on top then image then desc.'),
				'#options' => array(
					'yes' => 'Yes',
					'no' => 'No',
				),
				'#default_value' => !empty($default_values['mobile_enabled']) ? $default_values['mobile_enabled'] : 'yes',
			);
	}

	public function displaySummary($plugin, &$summary){
		$summary = t('Configure Content Grid Ajax Settings');
	}

	public function displayOutput($display, &$items){

		if($display['type'] == 'content_grid_ajax' && !empty($items)){
			$settings = $display['settings'];
			drupal_add_library('system', 'drupal.ajax');
			$html = array();
			$html['wrapper'] = BtApi::Container(array('content-grid-ajax-wrapper'));
			$i = 0;
			foreach($items as $delta => $vid){
				$children = entity_taxonomy_block_load_vocab_child_terms($vid['vid']);
				foreach($children as $d => $item){
					if($i == 0){
						$item = taxonomy_term_load($item->tid);
						$item->settings = array(
							'path' => '',
							'direction' => 'left',
							'header' => $item->name,
							'image_style' => array(
								'width' => '510px',
								'height' => '285px',							
							),
						);
						$html['wrapper']['featured'] = BtApi::Container(array('content-grid-ajax-featured', 'mobile-hidden', 'tablet-hidden'));
						$featured = theme('media_object', (array)$item);
						$html['wrapper']['featured']['term'] = BtApi::htmlTag(render($featured), 'div', array('content-grid-ajax-term-featured'), -1);
					}
					$term_display = entity_taxonomy_block_display_term($item);
					$html['wrapper'][$d] = BtApi::htmlTag(render($term_display), 'div', array('content-grid-ajax-term'), $d);
					$i++;
				}
			}
			$items = $html;
		}
	}
	
	public function addDependencies(){
		drupal_add_css(drupal_get_path('module', 'content_grid') . '/css/content_grid.css');
	}

}



class ContentGridAjaxTermHandler extends BtTermHandler {

	public function pluginSettings($plugin, &$defaults, &$form, &$settings){
			$default_values = array();
			$element = array();
			if(!empty($defaults)){
				foreach($defaults as $key => $value){
					$default_values[$key] = $value;
				}
			}
			$settings['path'] = array(
				'#type' => 'textfield',
				'#title' => t('path'),
				'#default_value' => !empty($default_values['path']) ? $default_values['path'] : '',
			);
	}
	
	public function displayOutput(&$content, $settings = array()){
		$this->addDependencies();
		$html = array();
		$html['wrapper'] = BtApi::Container(array('content-grid-ajax-layout-item'));
		$image = _content_grid_load_image($content, $settings, true);
		$html['wrapper']['img'] = BtApi::htmlTag($image, 'div', array('content-grid-item-image'), 1);
		$description = $content->term->description;
		$link = _content_grid_load_path($content);
		$options = array(
			'attributes' => array(
				'class' => array('use-ajax'),
			),
		);
		$ajax_link = l($content->term->name, 'content-grid/load/'. $content->term->tid, $options);
		$html['wrapper']['name'] = BtApi::htmlTag($ajax_link, 'div', array('content-grid-item-name', 'mobile-hidden', 'tablet-hidden'), 0);
		$html['wrapper']['mobile_name'] = BtApi::htmlTag($content->term->name, 'h4', array('content-grid-item-name-mobile', 'desktop-hidden', 'mobile-visible'), 0);
		$html['wrapper']['desc'] = BtApi::htmlTag($description, 'div', array('content-grid-item-description', 'desktop-hidden'), 2);
		$content->taxonomy_term = $html;
		content_grid_add_carousel_dependacies();
	}
	
	public function pluginFieldInstances($plugin, $bundle){
		return false;
	}
	
	public function addDependencies(){
		drupal_add_css(drupal_get_path('module', 'content_grid') . '/css/content_grid.css');
	}
	
}